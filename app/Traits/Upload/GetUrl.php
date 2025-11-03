<?php
namespace App\Traits\Upload;

use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaModel;

trait GetUrl {
    /**
     * Get the URL for a custom uploaded file
     *
     * @param string|null $fileName
     * @param string $directory
     * @param string $defaultImage
     * @return string|null
     */
    public function getCustomUploadUrl(?string $fileName, string $directory, string $defaultImage = 'default.png'): ?string {
        if ($fileName !== null && File::exists(public_path('/storage/uploads/' . $directory . '/' . $fileName))) {
            return asset('storage/uploads/' . $directory) . '/' . $fileName;
        } elseif ($fileName !== null && File::exists(public_path('/defaults/' . $directory . '/' . $fileName))) {
            return asset('defaults/' . $directory) . '/' . $fileName;
        } elseif (File::exists(public_path('/defaults/' . $directory . '/' . $fileName))) {
            return asset('defaults/' . $directory) . '/' . $fileName;
        } elseif (File::exists(public_path('/defaults/' . $directory . '/default.png'))) {
            return asset('defaults/' . $directory) . '/default.png';
        } else {
            return asset('defaults') . '/default.png';
        }
    }

    /**
     * Get the URL for a media library uploaded file
     *
     * @param int|null $mediaId
     * @param string $collection
     * @param string $conversion
     * @return string|null
     */
    public function getMediaLibraryUrl(?int $mediaId, string $collection = 'default', string $conversion = ''): ?string {
        if ($mediaId) {
            $media = MediaModel::find($mediaId);
            if ($media) {
                return $conversion ? $media->getUrl($conversion) : $media->getUrl();
            }
        }

        // Try to get the first media URL from the collection as a fallback
        $mediaUrl = $this->getFirstMediaUrl($collection, $conversion);
        if ($mediaUrl) {
            return $mediaUrl;
        }

        return null;
    }
}
