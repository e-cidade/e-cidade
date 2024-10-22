<?php

namespace App\Support\Storage;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class S3UrlPresigner
{
    public function getPresignedUrl(string $url): string
    {
        $key = $this->getKeyFromUrl($url);
        if (empty($key)) {
            return '';
        }

        return Storage::disk(env('FILESYSTEM_DRIVER'))->temporaryUrl($key, Carbon::now()->addMinutes(5));
    }

    private function getKeyFromUrl(string $url): string
    {
        $url = preg_replace('/\?.*/', '', $url);

        return implode('/', array_slice(explode('/', $url), 3));
    }
}
