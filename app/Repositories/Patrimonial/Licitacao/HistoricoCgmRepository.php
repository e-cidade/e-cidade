<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\HistoricoCgm;
use cl_liclicita;
use Illuminate\Database\Capsule\Manager as DB;

class HistoricoCgmRepository
{
  private HistoricoCgm $model;

  public function __construct()
  {
    $this->model = new HistoricoCgm();
  }

   public function getNumeroByNumCgm($z09_numcgm, $z09_tipo){
      return $this->model->where('z09_numcgm', $z09_numcgm)->where('z09_tipo', $z09_tipo)->first();
   }
}   
