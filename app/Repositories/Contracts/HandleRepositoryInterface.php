<?php

namespace App\Repositories\Contracts;

interface HandleRepositoryInterface
{
    public function handle(Object $data);
}
