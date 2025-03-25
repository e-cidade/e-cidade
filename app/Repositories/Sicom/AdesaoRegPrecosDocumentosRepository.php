<?php

namespace App\Repositories\Sicom;

use App\Models\Sicom\AdesaoRegPrecosDocumentos;
use Illuminate\Support\Facades\DB;

class AdesaoRegPrecosDocumentosRepository{
  private AdesaoRegPrecosDocumentos $model;

  public function __construct()
  {
    $this->model = new AdesaoRegPrecosDocumentos();
  }

  function getNextVal(){
    return $this->model->getNextval();
  }

  public function getByCode(int $sd1_sequencial){
    return $this->model->where('sd1_sequencial', $sd1_sequencial)->first();
  }

  function save(AdesaoRegPrecosDocumentos $data){
    $data->save();
    return $data;
  }

  public function update(int $sd1_sequencial, array $data){
    $oAdesaoPrecos = $this->model->findOrFail($sd1_sequencial);
    $oAdesaoPrecos->update($data);

    return $oAdesaoPrecos;
  }
  
  public function delete($sd1_sequencial){
    $this->model
        ->where('sd1_sequencial', $sd1_sequencial)
        ->delete();
  }

  public function getAllByFilters($sd1_sequencialadesao){
    $query = $this->model->query();

    $query->select(
      'sd1_sequencial',
      'sd1_nomearquivo',
      'sd1_tipo',
      'sd1_liclicita',
      'sd1_extensao',
      'sd1_sequencialadesao',
    );

    $query->where('sd1_sequencialadesao', $sd1_sequencialadesao ?? 1);

    $total = $query->count();

    $data = $query->get();

    return ['total' => $total, 'data' => $data->toArray()];
  }

  public function deleteByAdesao($sd1_sequencialadesao){
    $this->model
      ->where('sd1_sequencialadesao', $sd1_sequencialadesao)
      ->delete();
  }

}