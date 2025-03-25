<?php

namespace App\Application\Patrimonial\Pareceres;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\ParecerLicitacao\RemoveParecerService;

class RemoveParecer implements HandleRepositoryInterface{
  private RemoveParecerService $removeParecerService;

  public function __construct(){
    $this->removeParecerService = new RemoveParecerService();
  }

  public function handle(object $data){
    return $this->removeParecerService->execute($data);
  }
}
