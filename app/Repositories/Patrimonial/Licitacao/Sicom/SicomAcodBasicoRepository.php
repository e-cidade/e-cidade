<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom;

use App\Models\Sicom\SicomAcodBasico;
use Illuminate\Database\Capsule\Manager as DB;

class SicomAcodBasicoRepository
{
  private SicomAcodBasico $model;

  public function __construct()
  {
      $this->model = new SicomAcodBasico();
  }

  function getNextVal(){
    return $this->model->getNextval();
  }

  function getNextRemessa($instit) {
    return $this->model
      ->where('l228_instit', $instit) // Filtro aplicado antes do select
      ->selectRaw('COALESCE(MAX(l228_seqremessa), 0) + 1 as l228_seqremessa')
      ->first();
  }

  public function save(SicomAcodBasico $sicom){
    $sicom->save();
    return $sicom;
  }

}
