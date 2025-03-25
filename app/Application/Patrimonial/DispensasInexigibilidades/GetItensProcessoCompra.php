<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\DispensasInexigibilidades\LicLicitaService;
use App\Services\PcProcItem\GetPcProcItemService;

class GetItensProcessoCompra implements HandleRepositoryInterface{

    private GetPcProcItemService $getPcProcItemService;

    public function __construct()
    {
        $this->getPcProcItemService = new GetPcProcItemService();
    }

    public function handle(object $data)
    {
        return $this->getPcProcItemService->execute($data);
    }
}
