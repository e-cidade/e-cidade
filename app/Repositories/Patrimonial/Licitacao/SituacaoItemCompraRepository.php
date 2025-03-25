<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\SituacaoItemCompra;
use Illuminate\Support\Facades\DB;

class SituacaoItemCompraRepository{
    private SituacaoItemCompra $model;

    public function __construct()
    {
        $this->model = new SituacaoItemCompra();
    }

    public function findByLicLicita(int $l20_codigo){
        return $this->model->where('l218_codigolicitacao', $l20_codigo)->get();
    }

    public function delete(SituacaoItemCompra $data){
        $data->delete();
    }

    public function deleteAllByLicitacao(int $l20_codigo){
        return $this->model
            ->where('l218_codigolicitacao', $l20_codigo)
            ->delete();
    }
}
