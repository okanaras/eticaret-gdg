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

        $filePath = 'public/' . $path; // storage/public/{$gelenPath}
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug($fileName) . uniqid() . '.' . $extension;

        Storage::putFileAs($filePath, $file, $name);

        return 'storage/' . $path . '/' . $name; // db ye kaydedilecegi path
    }

    public function deleteImage(string $path)
    {
        return Storage::delete($path);
    }
}