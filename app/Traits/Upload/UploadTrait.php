<?php

namespace App\Traits\Upload;
use Illuminate\Http\UploadedFile;

trait UploadTrait
{
    use CustomUploadTrait, MediaLibraryUploadTrait, GetUrl;
    public function upload(UploadedFile $file, string $directory = 'default', string $collection = 'default', string $disk = 'uploads' , $uploadMethod = 'custom')
    {
        return $uploadMethod === 'custom'
            ? $this->uploadImageToCustomDirectory($file, $directory)
            : $this->uploadImage($file, $collection, $disk);
    }

    public function handleImagePath($file, string $directory = 'default', string $collection = 'default', string $conversion = '', string $defaultImage = 'default.png'  , $uploadMethod = 'custom'): ?string
    {
        return $uploadMethod === 'custom'
            ? $this->getCustomUploadUrl($file, $directory, $defaultImage)
            : $this->getMediaLibraryUrl((int) $file, $collection, $conversion);
    }
}
