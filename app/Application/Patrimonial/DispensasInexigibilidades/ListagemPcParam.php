<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\Orcamento\GetOrcParametroService;
use App\Services\PcParam\GetPcParamService;

class ListagemPcParam implements HandleRepositoryInterface{

    private GetPcParamService $getPcParamService;
    private GetOrcParametroService $getOrcParametroService;

    public function __construct()
    {
        $this->getPcParamService = new GetPcParamService();
        $this->getOrcParametroService = new GetOrcParametroService();
    }

    public function handle(object $data)
    {
        return ['data' => array_merge($this->getPcParamService->execute($data), $this->getOrcParametroService->execute($data))];
    }
}
