<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemService;

class RemovePlanoItemContratacao implements HandleRepositoryInterface{
    private PlanoContratacaoPcPcItemService $pcPlanoPcItem;

    public function __construct()
    {
        $this->pcPlanoPcItem = new PlanoContratacaoPcPcItemService();
    }

    public function handle(object $data)
    {
        return $this->pcPlanoPcItem->removerItem(
            $data->mpc01_codigo,
            $data->justificativa,
            $data->anousu,
            $data->instit,
            $data->id_usuario,
            $data->datausu
        );
    }

}
