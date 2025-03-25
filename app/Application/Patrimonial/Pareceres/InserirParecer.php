<?php

namespace App\Application\Patrimonial\Pareceres;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\ParecerLicitacao\InserirParecerService;

class InserirParecer implements HandleRepositoryInterface{
  private InserirParecerService $inserirParecerService;

  public function __construct(){
    $this->inserirParecerService = new InserirParecerService();
  }

  public function handle(object $data){
    return $this->inserirParecerService->execute($data);
  }
}
