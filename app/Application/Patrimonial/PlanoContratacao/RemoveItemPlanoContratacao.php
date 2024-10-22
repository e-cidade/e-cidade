<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoItemService;
use App\Services\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemService;

class RemoveItemPlanoContratacao  implements HandleRepositoryInterface{
    private PlanoContratacaoPcPcItemService $planoContratacaoItemService;

    public function __construct()
    {
        $this->planoContratacaoItemService = new PlanoContratacaoPcPcItemService();
    }

    public function handle(object $data)
    {
        return $this->planoContratacaoItemService->deleteItem(
            $data->mpc02_codigo,
            $data->mpc01_codigo,
            $data->mpcpc01_codigo,
            $data->justificativa,
            $data->anousu,
            $data->instit,
            $data->id_usuario,
            $data->datausu
        );
    }

}
