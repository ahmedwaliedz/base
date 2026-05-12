<?php
namespace App\Traits\Upload;

use Spatie\MediaLibrary\InteractsWithMedia;

// use App\Models\TempFile;

trait BaseFilesTrait {

    use UploadTrait;

    // protected const FILES             = [];
    // protected const UPLOAD_DIRECTORY  = 'default';
    // protected const UPLOAD_COLLECTION = 'default';
    // protected const UPLOAD_TYPE       = 'custom'; // or 'media-library' based on your implementation

    public static function deleteFiles($model) {
        foreach (static::FILES as $fileName) {
            if (! empty($model->attributes[$fileName] ?? null)) {
                $model->deleteFile($model->attributes[$fileName], static::UPLOAD_DIRECTORY);
            }
        }
    }

    public function getAttribute($key) {

        $uploadDirectory  = defined(static::class . '::UPLOAD_DIRECTORY') ? constant(static::class . '::UPLOAD_DIRECTORY') : 'default';
        $uploadCollection = defined(static::class . '::UPLOAD_COLLECTION') ? constant(static::class . '::UPLOAD_COLLECTION') : 'default';
        $uploadType       = defined(static::class . '::UPLOAD_TYPE') ? constant(static::class . '::UPLOAD_TYPE') : 'custom';

        if (str_ends_with($key, '_url')) {
            $fileKey = substr($key, 0, -4);

            if (
                in_array($fileKey, static::FILES, true) &&
                ! $this->hasGetMutator($fileKey) &&
                ! method_exists($this, 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $fileKey))) . 'Attribute') &&
                ! method_exists($this, $fileKey)
            ) {
                if (! empty($this->attributes[$fileKey])) {
                    return $this->handleImagePath($this->attributes[$fileKey], $uploadDirectory, $uploadCollection, $uploadType);
                }

                return asset('site/imgs/default.webp');
            }
        }

        if (
            in_array($key, static::FILES, true) &&
            ! $this->hasGetMutator($key) &&
            ! method_exists($this, 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key))) . 'Attribute') &&
            ! method_exists($this, $key)
        ) {
            if (! empty($this->attributes[$key])) {
                return $this->handleImagePath($this->attributes[$key], $uploadDirectory, $uploadCollection, $uploadType);
            }

            return asset('site/imgs/default.webp');
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value) {
        $setterMethod = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key))) . 'Attribute';

        if (
            in_array($key, static::FILES, true) &&
            ! method_exists($this, $setterMethod) &&
            ! method_exists($this, $key)
        ) {
            return $this->handleFileAssignment($key, $value);
        }

        return parent::setAttribute($key, $value); // fallback to Eloquent logic
    }

    protected function handleFileAssignment($key, $value) {

        $uploadDirectory  = defined(static::class . '::UPLOAD_DIRECTORY') ? constant(static::class . '::UPLOAD_DIRECTORY') : 'default';
        $uploadCollection = defined(static::class . '::UPLOAD_COLLECTION') ? constant(static::class . '::UPLOAD_COLLECTION') : 'default';
        $uploadType       = defined(static::class . '::UPLOAD_TYPE') ? constant(static::class . '::UPLOAD_TYPE') : 'custom';

        if ($value instanceof \Illuminate\Http\UploadedFile  && $value->isValid()) {
            if (isset($this->attributes[$key])) {
                $this->deleteFile($this->attributes[$key], $uploadDirectory);
            }
            $this->attributes[$key] = $this->upload($value, $uploadDirectory, $uploadCollection, $uploadType);
        }
        // elseif ($value instanceof TempFile) {

        //     if (isset($this->attributes[$key])) {
        //         $this->deleteFile($this->attributes[$key], static::PATH);
        //     }

        //     $value->moveTo($this, $key);
        // }

        return $this;
    }

}
