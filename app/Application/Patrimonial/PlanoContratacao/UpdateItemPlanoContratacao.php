<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoItemService;
use App\Services\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemService;

class UpdateItemPlanoContratacao implements HandleRepositoryInterface{
    private PlanoContratacaoPcPcItemService $planoContratacaoItemService;

    public function __construct()
    {
        $this->planoContratacaoItemService = new PlanoContratacaoPcPcItemService();
    }

    public function handle($data){
        $planoContratacaoItem = $this->planoContratacaoItemService->updateItem(
            $data->mpcpc01_codigo,
            $data->mpc02_codmater,
            $data->mpc02_categoria,
            $data->mpc02_un,
            $data->mpc02_depto,
            $data->mpc02_catalogo,
            $data->mpc02_tproduto,
            $data->mpc02_subgrupo,
            $data->mpcpc01_qtdd,
            $data->mpcpc01_vlrunit,
            $data->mpcpc01_vlrtotal,
            $data->mpcpc01_datap
        );

        return ['pcpcitem' => $planoContratacaoItem];
    }

}
