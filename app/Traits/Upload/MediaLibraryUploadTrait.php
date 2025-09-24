<?php

namespace App\Traits\Upload;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait MediaLibraryUploadTrait
{
    use Helpers;

    /**
     * Upload any file using Spatie Media Library
     *
     * @param UploadedFile $file
     * @param string $collection
     * @param string $disk
     * @return Media|null
     */
    public function uploadFile(UploadedFile $file, string $collection = 'default', string $disk = 'uploads')
    {
        $uniqueFileName = $this->generateUniqueFileName($file);

        return $this->addMedia($file)
            ->usingFileName($uniqueFileName)
            ->toMediaCollection($collection, $disk)->id;
    }

    /**
     * Backward-compatible alias for uploading images. Delegates to uploadFile.
     */
    public function uploadImage(UploadedFile $file, string $collection = 'default', string $disk = 'uploads')
    {
        return $this->uploadFile($file, $collection, $disk);
    }

    /**
     * Register default image conversions (thumbnail, small).
     * This method should be called in your model's registerMediaConversions().
     *
     * @param Media|null $media
     * @return void
     */
    public function registerImageConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('small')
            ->width(300)
            ->nonQueued();
    }

    /**
     * Delete a media item by its ID from Spatie Media Library.
     */
    public function deleteMediaLibraryFile(int $mediaId): bool
    {
        $media = Media::find($mediaId);
        if (! $media) {
            return false;
        }
        $media->delete();
        return true;
    }
}
