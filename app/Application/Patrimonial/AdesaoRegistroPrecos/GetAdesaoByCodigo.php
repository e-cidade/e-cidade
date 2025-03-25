<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecos\GetAdesaoByCodigo as AdesaoRegPrecosGetAdesaoByCodigo;

class GetAdesaoByCodigo implements HandleRepositoryInterface {
  
  private AdesaoRegPrecosGetAdesaoByCodigo $getAdesaoByCodigo;

  public function __construct()
  {
    $this->getAdesaoByCodigo = new AdesaoRegPrecosGetAdesaoByCodigo();
  }

  public function handle(object $data)
  {
    return $this->getAdesaoByCodigo->execute($data);
  }

}