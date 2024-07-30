<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Alvara;

use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IFilters;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApiSettings;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
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

        if(empty($result)) {
            return [];
        }

        foreach ($result as $item) {
            $response[] = new CompanyDTO((array)$item->dados);
        }
        return $response;
    }
}
