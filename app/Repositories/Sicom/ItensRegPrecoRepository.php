<?php

namespace App\Repositories\Sicom;

use App\Models\Sicom\ItensRegPreco;

class ItensRegPrecoRepository{
  private ItensRegPreco $model;

  public function __construct()
  {
    $this->model = new ItensRegPreco();
  }

  function getNextVal(){
    return $this->model->getNextval();
  }

  function save(ItensRegPreco $data){
    $data->save();
    return $data;
  }

  public function update(int $si07_sequencial, array $data){
    $oAdesaoPrecos = $this->model->findOrFail($si07_sequencial);
    $oAdesaoPrecos->update($data);

    return $oAdesaoPrecos;
  }
  
  public function getItensReg(int $si07_sequencialadesao){
    return $this->model
      ->where('si07_sequencialadesao', $si07_sequencialadesao)
      ->get();
  }

  public function deleteByAdesao($si07_sequencialadesao){
    $this->model
        ->where('si07_sequencialadesao', $si07_sequencialadesao)
        ->delete();
  }

  public function getCountIntensReg($si07_sequencialadesao){
    return $this->model
      ->where('si07_sequencialadesao', $si07_sequencialadesao)
      ->count();
  }

  public function getNextNumeroItem($si07_sequencialadesao){
    return $this->model
      ->where('si07_sequencialadesao', $si07_sequencialadesao)
      ->max('si07_numeroitem') + 1;
  }
  
  public function delete($si07_sequencial){
    $this->model
        ->where('si07_sequencial', $si07_sequencial)
        ->delete();
  }

  public function validateLoteByName($si07_numerolote, $si07_descricaolote, $si07_sequencialadesao){
    return $this->model
      ->where('si07_descricaolote', 'ILIKE', $si07_descricaolote)
      ->where('si07_numerolote', '!=', $si07_numerolote)
      ->where('si07_sequencialadesao', $si07_sequencialadesao)
      ->count();
  }

}