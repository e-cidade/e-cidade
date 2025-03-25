<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\PcProc\GetDadosPcProcService;

class GetDadosProcessoCompras implements HandleRepositoryInterface{
    private GetDadosPcProcService $getDadosPcProcService;

    public function __construct()
    {
        $this->getDadosPcProcService = new GetDadosPcProcService();
    }

    public function handle(object $data)
    {
        return $this->getDadosPcProcService->execute($data);
    }
}
