<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemService;

class RemovePlanoContratacaoPcPcItem implements HandleRepositoryInterface{
    private PlanoContratacaoPcPcItemService $pcPlanoContratacaoPcPcItemService;

    public function __construct()
    {
        $this->pcPlanoContratacaoPcPcItemService = new PlanoContratacaoPcPcItemService();
    }

    public function handle(object $data)
    {
        return $this->pcPlanoContratacaoPcPcItemService->delete(
            $data->mpc01_codigo,
            $data->itens,
            $data->justificativa,
            $data->anousu,
            $data->instit,
            $data->id_usuario,
            $data->datausu
        );
    }

}
