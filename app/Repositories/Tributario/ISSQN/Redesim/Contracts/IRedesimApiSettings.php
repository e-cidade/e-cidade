<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Contracts;

use Illuminate\Database\Eloquent\Model;

interface IRedesimApiSettings
{
    public function __construct(Model $model);

    public function getUrlApi(): string;

    public function toJson(): string;

    public function toArray(): array;
}
