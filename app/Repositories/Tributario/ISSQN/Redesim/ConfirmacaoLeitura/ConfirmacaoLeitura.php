<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\ConfirmacaoLeitura;

use App\Repositories\Tributario\ISSQN\Redesim\ApiRedesim;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IFilters;
use BusinessException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class ConfirmacaoLeitura extends ApiRedesim
{

    /**
     * @param IFilters|null $filters
     * @return string[]
     * @throws BusinessException
     * @throws GuzzleException
     */
    public function post(IFilters $filters = null): array
    {
        try {
            /**
             * @var string[]
             */
            return parent::post($filters);
        } catch (ClientException|RequestException $e) {
            throw new BusinessException($e->getMessage());
        }
    }
}
