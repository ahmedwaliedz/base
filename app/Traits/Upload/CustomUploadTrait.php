<?php

namespace App\Traits\Upload;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait CustomUploadTrait
{
    use Helpers;

    /**
     * Upload a file to a custom directory in the public storage
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    public function uploadFileToCustomDirectory(UploadedFile $file, string $directory): string
    {
        $fileName = $this->generateUniqueFileName($file);
        $file->storeAs("uploads/{$directory}", $fileName, 'public');
        return $fileName;
    }

    /**
     * Backward-compatible alias for uploading images. Delegates to uploadFileToCustomDirectory.
     */
    public function uploadImageToCustomDirectory(UploadedFile $file, string $directory): string
    {
        return $this->uploadFileToCustomDirectory($file, $directory);
    }

    /**
     * Delete a file from custom uploads storage.
     */
    public function deleteCustomUploadFile(string $fileName, string $directory): bool
    {
        $path = "uploads/{$directory}/{$fileName}";
        return Storage::disk('public')->exists($path)
            ? Storage::disk('public')->delete($path)
            : false;
    }
}
