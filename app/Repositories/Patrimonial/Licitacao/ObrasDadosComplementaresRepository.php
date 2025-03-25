<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\ObrasDadosComplementares;
use Illuminate\Database\Capsule\Manager as DB;

class ObrasDadosComplementaresRepository
{
    private ObrasDadosComplementares $model;

    public function __construct()
    {
        $this->model = new ObrasDadosComplementares();
    }

    public function delete(int $db151_codigoobra){
        $this->model
            ->where('db151_codigoobra', $db151_codigoobra)
            ->delete();
    }
}
