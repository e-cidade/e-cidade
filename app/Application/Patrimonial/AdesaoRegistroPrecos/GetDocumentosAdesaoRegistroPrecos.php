<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecosDocumentos\GetDocumentosByAdesao;

class GetDocumentosAdesaoRegistroPrecos implements HandleRepositoryInterface {
  
  private GetDocumentosByAdesao $getDocumentosByAdesao;

  public function __construct()
  {
    $this->getDocumentosByAdesao = new GetDocumentosByAdesao();
  }

  public function handle(object $data)
  {
    return $this->getDocumentosByAdesao->execute($data);
  }

}