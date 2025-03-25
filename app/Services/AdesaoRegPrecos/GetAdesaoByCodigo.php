<?php

namespace App\Services\AdesaoRegPrecos;

use App\Repositories\Sicom\AdesaoRegPrecosRepository;

class GetAdesaoByCodigo {
  private AdesaoRegPrecosRepository $adesaoRegPrecosRepository;

  public function __construct()
  {
    $this->adesaoRegPrecosRepository = new AdesaoRegPrecosRepository();
  }

  public function execute(object $data){
    $oData = $this->adesaoRegPrecosRepository->getAdesaoRegPrecoByCodigo($data->si06_sequencial);
    return [
      'status' => 200,
      'message' => 'Sucesso',
      'data' => [
          'adesao' => !empty($oData)? $oData->toArray() : []
      ]
    ];
  }

}