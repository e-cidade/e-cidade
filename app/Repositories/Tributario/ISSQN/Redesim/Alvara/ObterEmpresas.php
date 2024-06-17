<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Alvara;

use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IFilters;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApiSettings;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use App\Support\String\StringHelper;
use BusinessException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class ObterEmpresas extends Leitura
{

    public function __construct(IRedesimApiSettings $settings, ClientInterface $client)
    {
        parent::__construct($settings, $client);
        $this->baseUrl = 'service/alvara/obterEmpresas';
    }

    /**
     * @param IFilters|null $filters
     * @return null|CompanyDTO[]
     * @throws BusinessException
     * @throws GuzzleException
     */
    public function post(IFilters $filters = null): array
    {
        $response = null;
        $result = parent::post($filters);

        foreach ($result as $item) {
            $response[] = new CompanyDTO((array)StringHelper::utf8_decode_all($item->dados));
        }
        return $response;
    }
}
