<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecosDocumentos\DownloadDocumento;

class DownloadDocumentoAdesao implements HandleRepositoryInterface {
  
  private DownloadDocumento $downloadDocumento;

  public function __construct()
  {
    $this->downloadDocumento = new DownloadDocumento();
  }

  public function handle(object $data)
  {
    return $this->downloadDocumento->execute($data);
  }

}