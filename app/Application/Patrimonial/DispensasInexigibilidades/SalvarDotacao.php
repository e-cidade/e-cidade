<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\PcParam\InsertDotacaoService;

class SalvarDotacao implements HandleRepositoryInterface{

    private InsertDotacaoService $insertDotacaoService;

    public function __construct()
    {
        $this->insertDotacaoService = new InsertDotacaoService();
    }

    public function handle(object $data)
    {
        return $this->insertDotacaoService->execute($data);
    }
}
