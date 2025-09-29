<?php
namespace App\Traits\Upload;
use Illuminate\Http\UploadedFile;

trait UploadTrait {
    use CustomUploadTrait, MediaLibraryUploadTrait, GetUrl;
    public function upload(UploadedFile $file, string $directory = 'default', string $collection = 'default', string $disk = 'uploads', $uploadMethod = 'custom') {
        return $uploadMethod === 'custom'
        ? $this->uploadFileToCustomDirectory($file, $directory)
        : $this->uploadFile($file, $collection, $disk);
    }

    public function handleImagePath($file, string $directory = 'default', string $collection = 'default', string $conversion = '', string $defaultImage = 'default.png', $uploadMethod = 'custom'): ?string {
        return $uploadMethod === 'custom'
        ? $this->getCustomUploadUrl($file, $directory, $defaultImage)
        : $this->getMediaLibraryUrl((int) $file, $collection, $conversion);
    }

    // choose delete method based on UPLOAD_TYPE
    public function deleteFile($file, ?string $directory = null): bool {
        if (empty($file)) {return false;}

        $className  = get_class($this);
        $uploadType = defined($className . '::UPLOAD_TYPE') ? (string) constant($className . '::UPLOAD_TYPE') : 'custom';

        if ($uploadType === 'media-library') {
            return is_numeric($file) ? $this->deleteMediaLibraryFile((int) $file) : false;
        }

        $dir = $directory ?? (defined($className . '::UPLOAD_DIRECTORY') ? (string) constant($className . '::UPLOAD_DIRECTORY') : 'default');

        return $this->deleteCustomUploadFile((string) $file, $dir);
    }
}
