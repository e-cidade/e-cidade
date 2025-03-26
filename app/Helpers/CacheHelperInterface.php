<?php 

declare(strict_types=1);

namespace App\Helpers;

interface CacheHelperInterface
{
    public static function set(string $key, string $value, ?string $drive): bool;
    /**
     * @return string|false
     */
    public static function get(string $key, ?string $drive);
    public static function remove(string $key, ?string $drive): bool;
}
