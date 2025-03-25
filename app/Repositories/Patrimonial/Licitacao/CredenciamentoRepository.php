<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Credenciamento;
use Illuminate\Support\Facades\DB;

class CredenciamentoRepository{
    private Credenciamento $model;

    public function __construct()
    {
        $this->model = new Credenciamento();
    }

    public function findByLicitacao(int $l20_codigo){
        return $this->model->where('l205_licitacao', $l20_codigo)->first();
    }

    public function deleteByLicitacao(int $l20_codigo){
        return $this->model->where('l205_licitacao', $l20_codigo)->delete();
    }
}
