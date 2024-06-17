<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Contracts;

use GuzzleHttp\ClientInterface;

interface IRedesimApi
{
    public function __construct(IRedesimApiSettings $settings, ClientInterface $client);

    public function post(IFilters $filters = null): array;
}
