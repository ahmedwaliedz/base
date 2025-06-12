<?php

namespace App\Traits\Upload;

use Illuminate\Http\UploadedFile;

trait CustomUploadTrait
{
    use Helpers;

    /**
     * Upload an image to a custom directory in the public storage
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    public function uploadImageToCustomDirectory(UploadedFile $file, string $directory): string
    {
        $fileName = $this->generateUniqueFileName($file);
        $file->storeAs("uploads/{$directory}", $fileName, 'public');
        return $fileName;
    }
}
