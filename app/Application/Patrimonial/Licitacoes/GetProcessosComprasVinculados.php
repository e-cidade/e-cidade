<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\PcProc\GetProcessoCromprasVinculadosService;

class GetProcessosComprasVinculados implements HandleRepositoryInterface{
    private GetProcessoCromprasVinculadosService $getProcessoCromprasVinculadosService;

    public function __construct()
    {
        $this->getProcessoCromprasVinculadosService = new GetProcessoCromprasVinculadosService();
    }

    public function handle(object $data)
    {
        return $this->getProcessoCromprasVinculadosService->execute($data);
    }
}
