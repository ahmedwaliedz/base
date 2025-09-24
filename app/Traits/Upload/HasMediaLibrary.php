<?php
namespace App\Traits\Upload;

use App\Traits\Upload\MediaLibraryUploadTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMediaLibrary {

    use UploadTrait, InteractsWithMedia, MediaLibraryUploadTrait;

    protected const UPLOAD_TYPE = 'media-library';

    public function registerMediaConversions(?Media $media = null): void {
        $this->registerImageConversions($media);
    }

}
