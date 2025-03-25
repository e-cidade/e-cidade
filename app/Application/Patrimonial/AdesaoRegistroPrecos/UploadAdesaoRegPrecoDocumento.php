<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecosDocumentos\InsertAdesaoRegPrecoDocumento;

class UploadAdesaoRegPrecoDocumento implements HandleRepositoryInterface {
  
  private InsertAdesaoRegPrecoDocumento $insertAdesaoRegPrecoDocumento;

  public function __construct()
  {
    $this->insertAdesaoRegPrecoDocumento = new InsertAdesaoRegPrecoDocumento();
  }

  public function handle(object $data)
  {
    return $this->insertAdesaoRegPrecoDocumento->execute($data);
  }

}