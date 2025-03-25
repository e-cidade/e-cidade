<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Solicitemvinculo;
use Illuminate\Support\Facades\DB;

class SolicitemvinculoRepository
{
    private Solicitemvinculo $model;

    public function __construct()
    {
        $this->model = new Solicitemvinculo();
    }

    public function insert($dados): Solicitemvinculo
    {
        $pc55_sequencial = $this->model->getNextval();
        $dados['pc55_sequencial'] =  $pc55_sequencial;
        return $this->model->create($dados);
    }
    public function excluir($pc55_solicitempai){
        $sql = "DELETE FROM solicitemvinculo WHERE pc55_solicitempai= $pc55_solicitempai";
        return DB::statement($sql);
    }
}
