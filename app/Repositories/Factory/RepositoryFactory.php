<?php

namespace App\Repositories\Factory;

use App\Repositories\Contracts\HandleRepositoryInterface;

class RepositoryFactory {
    public static function create(string $path, string $type): HandleRepositoryInterface
    {
        $class = '\\App\\Application\\' . ucfirst($path) . ucfirst($type);

        if(class_exists($class)){
            return new $class();
        }

        throw new \InvalidArgumentException("Application {$type} does not exist in {$path}.");
    }
}
