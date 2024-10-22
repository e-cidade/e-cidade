<?php

namespace App\Services;

use App\Support\Storage\UrlPresigner;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    private UrlPresigner $urlPresigner;

    public function __construct(UrlPresigner $urlPresigner)
    {
        $this->urlPresigner = $urlPresigner;
    }

    /**
     * @throws Exception
     */
    public function getFile(string $url): string
    {
        return $this->urlPresigner->getPresignedUrl($url);
    }

    public function deleteFilesFromStorage(string $url)
    {
        switch (config('filesystems.default')) {
            case 'local':
                $url = env('WAS_BUCKET') . '/' . implode('/', array_slice(explode('/', $url), 5));
                break;
            case 'wasabi':
                $url = implode('/', array_slice(explode('/', $url), 3));
                break;
        }

        Storage::delete($url);
    }

    public function upload(UploadedFile $uploadFile, string $path = ''): string
    {
        $tenant = env('WAS_BUCKET');
        $bucketPath = empty($path) ? $tenant : $path;
        Storage::put($bucketPath, $uploadFile);

        return Storage::url($uploadFile->hashName($bucketPath));
    }
}
