<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecos\DeleteAdesaoRegPrecos;

class RemoveAdesaoPreco implements HandleRepositoryInterface {
  
  private DeleteAdesaoRegPrecos $deleteAdesaoRegPrecos;

  public function __construct()
  {
    $this->deleteAdesaoRegPrecos = new DeleteAdesaoRegPrecos();
  }

  public function handle(object $data)
  {
    return $this->deleteAdesaoRegPrecos->execute($data);
  }

}