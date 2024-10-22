<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemService;

class GetPlanoContratacaoItemByCodigo implements HandleRepositoryInterface{
    private PlanoContratacaoPcPcItemService $planoContratacaoItemService;

    public function __construct()
    {
        $this->planoContratacaoItemService = new PlanoContratacaoPcPcItemService();
    }

    public function handle(object $data)
    {
        return ['planoContratacaoItem' => $this->planoContratacaoItemService->getItemPlanoContratacao($data->mpc02_codigo, $data->mpc01_codigo)];
    }
}
