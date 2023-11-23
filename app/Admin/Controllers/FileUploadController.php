<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;


class FileUploadController
{
    public function upload(Request $request)
    {
        if ($request->file('file')) {
            $file = $request->file('file');

            if ($file->getClientOriginalExtension() === 'gif') {
                $filename = $file->getClientOriginalName() . '-' . uniqid() . '.gif';
                $file->move(public_path('images/'), $filename);
                $fileUrl = asset('images/' . $filename);

                return json_encode(['location' => $fileUrl]);
            } else {
                $filename = $file->getClientOriginalName() . '-' . uniqid() . '.webp';

                $image = Image::make($file)->encode('webp', 80);
                $image->save(public_path('images/' . $filename));

                $fileUrl = asset('images/' . $filename);

                return json_encode(['location' => $fileUrl]);
            }
        }
    }
}
