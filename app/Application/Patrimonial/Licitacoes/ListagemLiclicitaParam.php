<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\LicLicitaParam\GetLicLicitaParamService;
use App\Services\PcParam\GetPcParamService;

class ListagemLiclicitaParam implements HandleRepositoryInterface{
    private GetLicLicitaParamService $getLicLicitaParamService;
    private GetPcParamService $getPcParamService;

    public function __construct()
    {
        $this->getLicLicitaParamService = new GetLicLicitaParamService();
        $this->getPcParamService = new GetPcParamService();
    }

    public function handle(object $data)
    {
        return ['data' => array_merge($this->getLicLicitaParamService->execute($data), $this->getPcParamService->execute($data))];
    }
}
