<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\SituacaoItemLic;
use Illuminate\Support\Facades\DB;

class SituacaoItemLicRepository{
    private SituacaoItemLic $model;

    public function __construct()
    {
        $this->model = new SituacaoItemLic();
    }

    public function deleteBySituacaoItem(int $l218_codigo){
        return $this->model->where('l219_codigo', $l218_codigo)->delete();
    }

    public function deleteByLicitacao(int $l20_codigo){{
        return $this->model->whereIn('l219_codigo', function($query) use ($l20_codigo){
            $query->select('l218_codigo')
                ->from('situacaoitemcompra')
                ->where('l218_codigolicitacao', $l20_codigo);
        })
        ->delete();
    }

    }
}
