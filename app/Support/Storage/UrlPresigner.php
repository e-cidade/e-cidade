<?php

namespace App\Support\Storage;

use Exception;

class UrlPresigner
{
    /**
     * @throws Exception
     */
    public function getPresignedUrl(string $url): string
    {
        switch (config('filesystems.default')) {
            case 'local':
                return $url;
            case 'wasabi':
                return (new S3UrlPresigner())->getPresignedUrl($url);
            default:
                throw new Exception('Method UrlPresigner::getPresignedUrl() not implemented for cloud filesystem: ' . config('filesystems.default'));
        }
    }
}
