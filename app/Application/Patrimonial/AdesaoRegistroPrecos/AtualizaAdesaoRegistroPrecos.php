<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecos\UpdateAdesaoRegPrecos;

class AtualizaAdesaoRegistroPrecos implements HandleRepositoryInterface {
  
  private UpdateAdesaoRegPrecos $updateAdesaoRegPrecos;

  public function __construct()
  {
    $this->updateAdesaoRegPrecos = new UpdateAdesaoRegPrecos();
  }

  public function handle(object $data)
  {
    return $this->updateAdesaoRegPrecos->execute($data);
  }

}