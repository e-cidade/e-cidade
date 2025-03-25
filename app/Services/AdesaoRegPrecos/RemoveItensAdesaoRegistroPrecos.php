<?php

namespace App\Services\AdesaoRegPrecos;

use App\Models\Sicom\ItensRegPreco;
use App\Repositories\Sicom\ItensRegPrecoRepository;
use Illuminate\Database\Capsule\Manager as DB;

class RemoveItensAdesaoRegistroPrecos {
  private ItensRegPrecoRepository $itensRegPrecoRepository;

  public function __construct()
  {
    $this->itensRegPrecoRepository = new ItensRegPrecoRepository();
  }

  public function execute(object $data){
    if(empty($data->si07_sequencial)){
      return [
        'status' => 500,
        'message' => 'Por favor informe item',
        'data' => []
      ];
    }

    DB::beginTransaction();
    try{
      $this->itensRegPrecoRepository->delete($data->si07_sequencial);
      
      DB::commit();
      return [
        'status' => 200,
        'message' => 'Informações removido com sucesso!',
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