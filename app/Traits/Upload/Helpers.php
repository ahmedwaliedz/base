<?php


namespace  App\Traits\Upload;

use Illuminate\Http\UploadedFile;

trait Helpers
{
    protected function generateUniqueFileName(UploadedFile $file): string
    {
        return time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    }

    public function isUploadedFile($value): bool
    {
        return $value instanceof \Illuminate\Http\UploadedFile;
    }


}
