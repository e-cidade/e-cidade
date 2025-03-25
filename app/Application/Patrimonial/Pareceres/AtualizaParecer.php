<?php

namespace App\Application\Patrimonial\Pareceres;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\ParecerLicitacao\AtualizarParecerService;
use App\Services\ParecerLicitacao\GetParecerLicitacaoByFilters;

class AtualizaParecer implements HandleRepositoryInterface{
  private AtualizarParecerService $atualizarParecerService;

  public function __construct(){
    $this->atualizarParecerService = new AtualizarParecerService();
  }

  public function handle(object $data){
    return $this->atualizarParecerService->execute($data);
  }

}
