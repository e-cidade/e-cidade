<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\CredenciamentoSaldo;
use Illuminate\Support\Facades\DB;

class CredenciamentoSaldoRepository{
    private CredenciamentoSaldo $model;

    public function __construct()
    {
        $this->model = new CredenciamentoSaldo();
    }

    public function findByLicitacao(int $l20_codigo){
        return $this->model->where('l213_licitacao', $l20_codigo)->first();
    }

    public function deleteByLicitacao(int $l20_codigo){
        return $this->model->where('l213_licitacao', $l20_codigo)->delete();
    }
}
