<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecos\InsertAdesaoRegPrecos;

class InserirAdesaoRegistroPrecos implements HandleRepositoryInterface {
  
  private InsertAdesaoRegPrecos $insertAdesaoRegPrecos;

  public function __construct()
  {
    $this->insertAdesaoRegPrecos = new InsertAdesaoRegPrecos();
  }

  public function handle(object $data)
  {
    return $this->insertAdesaoRegPrecos->execute($data);
  }

}