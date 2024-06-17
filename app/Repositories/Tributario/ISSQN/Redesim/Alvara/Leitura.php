<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Alvara;

use App\Repositories\Tributario\ISSQN\Redesim\ApiRedesim;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IFilters;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\RedesimApiResponse;
use BusinessException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class Leitura extends ApiRedesim
{

    /**
     * @param IFilters|null $filters
     * @return RedesimApiResponse[]
     * @throws BusinessException
     * @throws GuzzleException
     */
    public function post(IFilters $filters = null): array
    {
        $response = [];

        try {

            /**
             * @var RedesimApiResponse[] $listaObj
             */
            $listaObj = parent::post($filters);

            foreach ($listaObj as $item) {

                $redesimApiresponse = new RedesimApiResponse();
                $redesimApiresponse->id = $item->id;
                $redesimApiresponse->cliente = $item->cliente;
                $redesimApiresponse->dados = $item->dados;
                $response[] = $redesimApiresponse;
            }

        } catch (ClientException|RequestException $e) {

            throw new BusinessException($e->getMessage());
        }

        return $response;
    }
}
