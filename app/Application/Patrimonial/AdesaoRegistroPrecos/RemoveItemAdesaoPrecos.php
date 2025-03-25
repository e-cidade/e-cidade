<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecos\RemoveItensAdesaoRegistroPrecos;

class RemoveItemAdesaoPrecos implements HandleRepositoryInterface {
  
  private RemoveItensAdesaoRegistroPrecos $removeItensAdesaoRegistroPrecos;

  public function __construct()
  {
    $this->removeItensAdesaoRegistroPrecos = new RemoveItensAdesaoRegistroPrecos();
  }

  public function handle(object $data)
  {
    return $this->removeItensAdesaoRegistroPrecos->execute($data);
  }

}