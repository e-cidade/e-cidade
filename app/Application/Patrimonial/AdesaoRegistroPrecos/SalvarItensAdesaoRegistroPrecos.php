<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecos\SaveItensAdesaoRegistroPrecos;

class SalvarItensAdesaoRegistroPrecos implements HandleRepositoryInterface {
  
  private SaveItensAdesaoRegistroPrecos $saveItensAdesaoRegistroPrecos;

  public function __construct()
  {
    $this->saveItensAdesaoRegistroPrecos = new SaveItensAdesaoRegistroPrecos();
  }

  public function handle(object $data)
  {
    return $this->saveItensAdesaoRegistroPrecos->execute($data);
  }

}