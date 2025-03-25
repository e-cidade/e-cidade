<?php
namespace App\Application\Patrimonial\DispensasInexigibilidades;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\PcParam\DeleteDotacaoService;

class RemoveDotacao implements HandleRepositoryInterface{

    private DeleteDotacaoService $deleteDotacaoService;

    public function __construct()
    {
        $this->deleteDotacaoService = new DeleteDotacaoService();
    }

    public function handle(object $data)
    {
        return $this->deleteDotacaoService->execute($data);
    }
}
