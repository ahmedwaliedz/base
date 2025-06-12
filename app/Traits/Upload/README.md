# Upload Traits

This directory contains traits for handling file uploads in different ways.

## Overview

The upload system has been refactored to support two different upload methods:

1. **Custom Directory Upload**: Uploads files to a custom directory in the public storage.
2. **Media Library Upload**: Uploads files using the Spatie Media Library package.

## Traits

### UploadTrait

The main trait that provides a unified interface for both upload methods. It uses both `CustomUploadTrait` and `MediaLibraryUploadTrait` internally and provides methods to switch between them.

```php
use App\Traits\Upload\UploadTrait;

// Set the upload method
$model->setUploadMethod('custom'); // or 'media_library'

// Get the current upload method
$method = $model->getUploadMethod();

// Upload a file using the current method
$result = $model->upload($file, $directory, $collection, $disk);

// Get the URL of an uploaded file
$url = $model->getUploadUrl($fileIdentifier, $directory, $collection, $conversion, $defaultImage);
```

### CustomUploadTrait

Handles uploads to a custom directory in the public storage.

```php
use App\Traits\Upload\CustomUploadTrait;

// Upload an image to a custom directory
$fileName = $model->uploadImageToCustomDirectory($file, $directory);
```

### MediaLibraryUploadTrait

Handles uploads using the Spatie Media Library package.

```php
use App\Traits\Upload\MediaLibraryUploadTrait;

// Upload an image using Spatie Media Library
$media = $model->uploadImage($file, $collection, $disk);

// Register image conversions
$model->registerImageConversions($media);
```

### GetUrl

Provides methods for getting URLs of uploaded files.

```php
use App\Traits\Upload\GetUrl;

// Get the URL for a custom uploaded file
$url = $model->getCustomUploadUrl($fileName, $directory, $defaultImage);

// Get the URL for a media library uploaded file
$url = $model->getMediaLibraryUrl($mediaId, $collection, $conversion);
```

### Helpers

Contains utility methods used by other traits.

```php
use App\Traits\Upload\Helpers;

// Generate a unique filename for an uploaded file
$fileName = $model->generateUniqueFileName($file);
```

## Usage Example

See the `examples/upload_trait_usage_example.php` file for a complete example of how to use these traits.

## Implementation in Models

To implement these traits in your models, you need to:

1. Use the `UploadTrait` in your model
2. If using Media Library, implement the `HasMedia` interface and use the `InteractsWithMedia` trait
3. Implement the `registerMediaConversions` method if needed

```php
use App\Traits\Upload\UploadTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MyModel extends Model implements HasMedia
{
    use InteractsWithMedia;
    use UploadTrait;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->registerImageConversions($media);
    }
}
```

You can then switch between upload methods as needed:

```php
// Use custom directory upload
$model->setUploadMethod('custom');
$model->value = $file;

// Use media library upload
$model->setUploadMethod('media_library');
$model->value = $file;
```
