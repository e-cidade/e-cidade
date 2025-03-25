<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\PcProc\GetProcessoComprasService;

class GetProcessoCompras implements HandleRepositoryInterface{

    private GetProcessoComprasService $getProcessoComprasService;

    public function __construct()
    {
        $this->getProcessoComprasService = new GetProcessoComprasService();
    }

    public function handle(object $data)
    {
        return $this->getProcessoComprasService->execute($data);
    }
}
