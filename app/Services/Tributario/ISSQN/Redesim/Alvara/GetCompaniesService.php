<?php

namespace App\Services\Tributario\ISSQN\Redesim\Alvara;

use App\Repositories\Tributario\ISSQN\Redesim\Alvara\Filters\ObterEmpresasFilter;
use App\Repositories\Tributario\ISSQN\Redesim\Alvara\ObterEmpresas;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApiSettings;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\CompanyDTO;
use GuzzleHttp\ClientInterface;
use Illuminate\Database\Eloquent\Model;

class GetCompaniesService
{
    protected Model $apiRedesimSettings;
    protected IRedesimApiSettings $redesimSettingsRepository;
    protected ClientInterface $client;

    public function __construct(Model $apiRedesimSettings, IRedesimApiSettings $redesimSettingsRepository, ClientInterface $client)
    {
        $this->apiRedesimSettings = $apiRedesimSettings;
        $this->redesimSettingsRepository = $redesimSettingsRepository;
        $this->client = $client;
    }

    /**
     * @param ObterEmpresasFilter|null $filter
     * @return CompanyDTO[]|null
     * @throws \BusinessException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(ObterEmpresasFilter $filter = null): ?array
    {
        $obterAtividadesRepository = new ObterEmpresas($this->redesimSettingsRepository, $this->client);
        return $obterAtividadesRepository->post($filter);
    }

}
