<?php

namespace App\Admin\Controllers;

use Illuminate\Support\Facades\Storage;
use Encore\Admin\Layout\Content;

class BackupController extends \Encore\Admin\Controllers\AdminController
{
    protected $title = 'Backups';

    public function index(Content $content)
    {
        $backupDirectory = env('APP_NAME', 'CodeBR');
        $disk = Storage::disk('local');

        if (!$disk->exists($backupDirectory)) {
            $disk->makeDirectory($backupDirectory);
        }

        $files = $disk->files($backupDirectory);

        usort($files, function ($a, $b) use ($disk) {
            return $disk->lastModified($b) <=> $disk->lastModified($a);
        });

        $backups = collect($files)->map(function ($file) use ($disk) {
            return [
                'file' => basename($file),
                'date' => \Carbon\Carbon::createFromTimestamp($disk->lastModified($file))->format('d/m/Y H:i'),
                'size' => number_format($disk->size($file) / 1024 / 1024, 2) . ' MB',
                'downloadUrl' => admin_url("backups/download/" . basename($file)),
                'deleteUrl' => admin_url("backups/delete/" . basename($file)),
            ];
        });

        return $content
            ->header('Backups')
            ->description('Lista de Backups')
            ->view('admin.backups.index', ['backups' => $backups]);
    }

    public function download($filename)
    {
        $backupDirectory = env('APP_NAME', 'CodeBR');
        $disk = Storage::disk('local');

        $filePath = "{$backupDirectory}/{$filename}";

        if ($disk->exists($filePath)) {
            return $disk->download($filePath);
        }

        abort(404, 'Arquivo não encontrado.');
    }

    public function delete($filename)
    {
        $backupDirectory = env('APP_NAME', 'CodeBR');
        $disk = Storage::disk('local');

        $filePath = "{$backupDirectory}/{$filename}";

        if ($disk->exists($filePath)) {
            $disk->delete($filePath);
            admin_toastr('Backup excluído com sucesso!', 'success');
        } else {
            admin_toastr('Arquivo não encontrado.', 'error');
        }

        return redirect(admin_url('backups'));
    }
}