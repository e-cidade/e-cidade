<?php

namespace App\Repositories\Configuracao\Empenho;

use App\Models\Empenho\Empempaut;

class EmpempautRepository
{
    private Empempaut $model;

    public function __construct()
    {
        $this->model = new Empempaut();
    }

    public function getEmpPautByAdesaoPreco($e54_adesaoregpreco, $e54_anousu){
      return $this->model
        ->select('e61_autori', 'e61_numemp')
        ->join(
          'empautoriza',
          'e61_autori',
          '=',
          'e54_autori'
        )
        ->where('e54_adesaoregpreco', $e54_adesaoregpreco)
        ->where('e54_anousu', $e54_anousu)
        ->get();
    }
}
