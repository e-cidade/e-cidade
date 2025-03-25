<?php

namespace App\Services\AdesaoRegPrecosDocumentos;

use App\Models\Sicom\AdesaoRegPrecosDocumentos;
use App\Repositories\Sicom\AdesaoRegPrecosDocumentosRepository;
use Illuminate\Database\Capsule\Manager as DB;

class RemoveDocumento {
  private AdesaoRegPrecosDocumentosRepository $adesaoRegPrecosDocumentosRepository;

  public function __construct()
  {
    $this->adesaoRegPrecosDocumentosRepository = new AdesaoRegPrecosDocumentosRepository();
  }

  public function execute(object $data){
    if(empty($data->sd1_sequencial)){
      return [
        'status' => 500,
        'message' => 'Informe o identificador da documento'
      ];
    }
    
    DB::beginTransaction();
    try{
      $this->adesaoRegPrecosDocumentosRepository->delete($data->sd1_sequencial);

      DB::commit();
      return [
        'status' => 200,
        'message' => 'Documento inserido com sucesso!',
        'data' => []
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