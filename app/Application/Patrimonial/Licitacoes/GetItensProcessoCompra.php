<?php
namespace App\Application\Patrimonial\Licitacoes;

use App\Repositories\Contracts\HandleRepositoryInterface;
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
