<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\PcProc\GetProcessoCromprasVinculadosService;

class getProcessosComprasVinculados implements HandleRepositoryInterface{

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
