<?php

namespace App\Services\AdesaoRegPrecos;

use App\Repositories\Sicom\AdesaoRegPrecosRepository;

class GetAdesaoRegPrecos {
  private AdesaoRegPrecosRepository $adesaoRegPrecosRepository;

  public function __construct()
  {
    $this->adesaoRegPrecosRepository = new AdesaoRegPrecosRepository();
  }

  public function execute(object $data){
    $aData = $this->adesaoRegPrecosRepository->getAllByFilters(
      $data->si06_sequencial,
      $data->si06_numeroprc,
      $data->si06_numlicitacao,
      $data->si06_numeroadm,
      $data->orderable,
      $data->search,
      $data->limit,
      $data->offset,
      $data->si06_instit
    );

    return [
      'status' => 200,
      'message' => 'Sucesso',
      'data' => [
          'total' => $aData['total'],
          'adesoes' => $aData['data']
      ]
    ];
  }

}