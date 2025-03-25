<?php

namespace App\Application\Patrimonial\Pareceres;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\ParecerLicitacao\GetParecerLicitacaoByCodigo;

class GetParecerByCodigo implements HandleRepositoryInterface{
  private GetParecerLicitacaoByCodigo $getParecerLicitacaoByCodigo;

  public function __construct(){
    $this->getParecerLicitacaoByCodigo = new GetParecerLicitacaoByCodigo();
  }

  public function handle(object $data){
    return $this->getParecerLicitacaoByCodigo->execute($data);
  }
}
