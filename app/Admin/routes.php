<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\ArtigoController;
use App\Admin\Controllers\ComentarioController;
use App\Admin\Controllers\BackupController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('home');
    $router->post('/file_oupload', 'FileUploadController@upload');
    $router->resource('artigos', ArtigoController::class);
    $router->resource('comentarios', ComentarioController::class);

    $router->get('backups', [BackupController::class, 'index'])->name('admin.backups');
    $router->get('backups/download/{filename}', [BackupController::class, 'download'])->name('admin.backups.download');
    $router->get('backups/delete/{filename}', [BackupController::class, 'delete'])->name('admin.backups.delete');
});
