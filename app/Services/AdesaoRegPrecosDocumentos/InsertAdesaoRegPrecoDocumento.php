<?php

namespace App\Services\AdesaoRegPrecosDocumentos;

use App\Models\Sicom\AdesaoRegPrecosDocumentos;
use App\Repositories\Sicom\AdesaoRegPrecosDocumentosRepository;
use Illuminate\Database\Capsule\Manager as DB;

class InsertAdesaoRegPrecoDocumento {
  private AdesaoRegPrecosDocumentosRepository $adesaoRegPrecosDocumentosRepository;

  public function __construct()
  {
    $this->adesaoRegPrecosDocumentosRepository = new AdesaoRegPrecosDocumentosRepository();
  }

  public function execute(object $data){
    global $conn;

    if(empty($data->si06_sequencial)){
      return [
        'status' => 500,
        'message' => 'Informe o identificador da adesão'
      ];
    }
    
    if(empty($data->files['documento'])){
      return [
        'status' => 500,
        'message' => 'Informe o documento'
      ];
    }

    DB::beginTransaction();
    try{
      $file = pathinfo($data->files['documento']['name']);

      $conteudo = file_get_contents($data->files['documento']['tmp_name']);

      $oData = new AdesaoRegPrecosDocumentos([
        'sd1_sequencial' => $this->adesaoRegPrecosDocumentosRepository->getNextVal(),
        'sd1_nomearquivo' => $file['filename'],
        'sd1_arquivo' => base64_encode($conteudo),
        'sd1_extensao' => $file['extension'],
        'sd1_sequencialadesao' => $data->si06_sequencial,
      ]);

      $this->adesaoRegPrecosDocumentosRepository->save($oData);

      DB::commit();
      return [
        'status' => 200,
        'message' => 'Documento inserido com sucesso!',
        'data' => [
          'adesao' => $oData->toArray()
        ]
      ];
    } catch(\Throwable $e){
      DB::rollBack();
      return [
          'status' => 500,
          'message' => $e->getMessage()
      ];
    }
  }

}