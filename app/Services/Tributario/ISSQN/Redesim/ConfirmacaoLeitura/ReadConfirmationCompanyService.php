<?php

namespace App\Services\Tributario\ISSQN\Redesim\ConfirmacaoLeitura;

use App\Repositories\Tributario\ISSQN\Redesim\ConfirmacaoLeitura\Empresas;
use App\Repositories\Tributario\ISSQN\Redesim\ConfirmacaoLeitura\Filters\ConfirmacaoLeituraEmpresaFilter;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApiSettings;
use GuzzleHttp\ClientInterface;
use Illuminate\Database\Eloquent\Model;

class ReadConfirmationCompanyService
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
     * @param ConfirmacaoLeituraEmpresaFilter|null $filter
     * @throws \BusinessException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(ConfirmacaoLeituraEmpresaFilter $filter = null): void
    {
        $empresasRepository = new Empresas($this->redesimSettingsRepository, $this->client);
        $empresasRepository->post($filter);
    }
}
