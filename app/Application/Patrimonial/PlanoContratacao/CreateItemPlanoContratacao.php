<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoItemService;
use App\Services\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemService;

class CreateItemPlanoContratacao implements HandleRepositoryInterface{
    private PlanoContratacaoPcPcItemService $planoContratacaoItemService;

    public function __construct()
    {
        $this->planoContratacaoItemService = new PlanoContratacaoPcPcItemService();
    }

    public function handle($data){
        $resultado = $this->planoContratacaoItemService->createItem(
            $data->mpc01_codigo,
            $data->mpc02_codmater,
            $data->mpcpc01_datap,
            $data->mpc02_categoria,
            $data->mpcpc01_qtdd,
            $data->mpcpc01_vlrunit,
            $data->mpcpc01_vlrtotal,
            $data->mpc02_un,
            $data->mpc02_depto,
            $data->mpc02_catalogo,
            $data->mpc02_tproduto,
            $data->mpc02_subgrupo
        );

        return $resultado;
    }
}
