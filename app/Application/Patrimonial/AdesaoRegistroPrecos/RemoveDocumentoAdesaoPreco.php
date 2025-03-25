<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecosDocumentos\RemoveDocumento;

class RemoveDocumentoAdesaoPreco implements HandleRepositoryInterface {
  
  private RemoveDocumento $removeDocumento;

  public function __construct()
  {
    $this->removeDocumento = new RemoveDocumento();
  }

  public function handle(object $data)
  {
    return $this->removeDocumento->execute($data);
  }

}