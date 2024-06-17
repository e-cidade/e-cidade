<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\Alvara;

use App\Repositories\Tributario\ISSQN\Redesim\Alvara\Filters\ObterAtividadesFilter;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IFilters;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApiSettings;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\ActivityApiResponse;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\ActivityDTO;
use BusinessException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class ObterAtividades extends Leitura
{

    public function __construct(IRedesimApiSettings $settings, ClientInterface $client)
    {
        parent::__construct($settings, $client);
        $this->baseUrl = 'service/alvara/obterAtividades';
    }

    /**
     * @param ObterAtividadesFilter|null $filters
     * @return null|ActivityDTO[]
     * @throws BusinessException
     * @throws GuzzleException
     */
    public function post(IFilters $filters = null): array
    {
        $response = null;
        /**
         * @var ActivityApiResponse[] $result
         */
        $result = parent::post($filters);

        foreach ($result as $item) {
            $response[] = new ActivityDTO((array)$item->dados);
        }
        return $response;
    }
}
