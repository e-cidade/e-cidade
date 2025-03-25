<?php

namespace App\Services\AdesaoRegPrecosDocumentos;

use App\Repositories\Sicom\AdesaoRegPrecosDocumentosRepository;
use Illuminate\Database\Capsule\Manager as DB;

class GetDocumentosByAdesao {
  private AdesaoRegPrecosDocumentosRepository $adesaoRegPrecosDocumentosRepository;

  public function __construct()
  {
    $this->adesaoRegPrecosDocumentosRepository = new AdesaoRegPrecosDocumentosRepository();
  }

  public function execute(object $data){
    $aData = $this->adesaoRegPrecosDocumentosRepository->getAllByFilters($data->si06_sequencial);

    return [
      'status' => 200,
      'message' => 'Sucesso',
      'data' => [
          'total' => $aData['total'],
          'documentos' => $aData['data']
      ]
    ];
  }

}