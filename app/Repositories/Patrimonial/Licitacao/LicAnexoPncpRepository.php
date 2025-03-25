<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicAnexoPncp;
use Illuminate\Database\Capsule\Manager as DB;

class LicAnexoPncpRepository
{
    private LicAnexoPncp $model;

    public function __construct()
    {
        $this->model = new LicAnexoPncp();
    }

    public function getDadosByLicitacao(int $l20_codigo):?array
    {
        $query = $this->model->query();
        $query->where('l215_liclicita', $l20_codigo);

        return $query->get()->toArray();
    }

    public function getByCodigo(int $l215_sequencial){
        return $this->model->where('l215_sequencial', $l215_sequencial)->first();
    }

    public function delete(LicAnexoPncp $aData){
        $aData->delete();
    }

}
