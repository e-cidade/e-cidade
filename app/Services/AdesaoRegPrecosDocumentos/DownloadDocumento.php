<?php

namespace App\Services\AdesaoRegPrecosDocumentos;

use App\Repositories\Sicom\AdesaoRegPrecosDocumentosRepository;
use Illuminate\Database\Capsule\Manager as DB;

class DownloadDocumento {
  private AdesaoRegPrecosDocumentosRepository $adesaoRegPrecosDocumentosRepository;

  public function __construct()
  {
    $this->adesaoRegPrecosDocumentosRepository = new AdesaoRegPrecosDocumentosRepository();
  }

  public function execute(object $data) {
    global $conn;

    $oDocumento = $this->adesaoRegPrecosDocumentosRepository->getByCode($data->sd1_sequencial);
    $nomeArquivo = $oDocumento->sd1_nomearquivo . '.' . $oDocumento->sd1_extensao;
   
    $sNomeTemp = "tmp/" . $nomeArquivo;

    file_put_contents($sNomeTemp, base64_decode($oDocumento->sd1_arquivo));

    return [
      'status' => 200,
      'message' => 'Arquivo carregado com sucesso',
      'data' => [
        'nome' => $sNomeTemp
      ]
    ];
  }
}