<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Contracts;

interface IFilters
{
    public function toJson(): string;

    public function toArray(): array;
}
