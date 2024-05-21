<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function singleUpload(UploadedFile $file, string $fileName, string $path): string
    {
        /**
         * file upload islemi
         */
        // $file = $request->file('logo');

        $extension = $file->getClientOriginalExtension();
        $name = Str::slug($fileName) . uniqid() . '.' . $extension;
        $path = 'public/uploads/brands/original';

        return Storage::putFileAs($path, $file, $name);
    }
}