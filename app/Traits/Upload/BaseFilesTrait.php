<?php
namespace App\Traits\Upload;

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
        if (
            in_array($key, static::FILES, true) &&
            ! $this->hasGetMutator($key) &&
            ! method_exists($this, 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key))) . 'Attribute') &&
            ! method_exists($this, $key)
        ) {
            if (! empty($this->attributes[$key])) {
                return $this->handleImagePath($this->attributes[$key], self::UPLOAD_DIRECTORY, self::UPLOAD_COLLECTION, self::UPLOAD_TYPE);
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
        if ($value instanceof \Illuminate\Http\UploadedFile  && $value->isValid()) {
            if (isset($this->attributes[$key])) {
                $this->deleteFile($this->attributes[$key], self::UPLOAD_DIRECTORY);
            }
            $this->attributes[$key] = $this->upload($value, self::UPLOAD_DIRECTORY, self::UPLOAD_COLLECTION, self::UPLOAD_TYPE);
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
