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
            $filename = $file->getClientOriginalName() . '-' . uniqid() . '.webp';
    
            $image = Image::make($file)->encode('webp', 75);
            $image->save(public_path('images/' . $filename));
    
            $fileUrl = asset('images/' . $filename);
    
            return json_encode(['location' => $fileUrl]);
        }
    }
}
