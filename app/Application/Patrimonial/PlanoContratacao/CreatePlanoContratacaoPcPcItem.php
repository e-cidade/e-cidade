<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PlanoContratacaoPcPcItemService;

class CreatePlanoContratacaoPcPcItem implements HandleRepositoryInterface{
  private PlanoContratacaoPcPcItemService $pcPlanoContratacaoPcPcItemService;

  public function __construct()
  {
    $this->pcPlanoContratacaoPcPcItemService = new PlanoContratacaoPcPcItemService();
  }

  public function handle(object $data)
  {
    $planoContratacao = $this->pcPlanoContratacaoPcPcItemService->create(
        $data->mpc01_codigo,
        $data->itens,
        $data->mpc02_datap
    );

    return ['planoContratacaoPcPcItem' => $planoContratacao];
  }

}
