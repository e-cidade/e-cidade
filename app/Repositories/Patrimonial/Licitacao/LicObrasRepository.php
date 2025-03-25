<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicLicitaWeb;
use App\Models\Patrimonial\Licitacao\LicObras;
use App\Repositories\Contracts\Patrimonial\Licitacao\LicObrasRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class LicObrasRepository implements LicObrasRepositoryInterface
{
    private LicObras $model;

    public function __construct()
    {
        $this->model = new LicObras();
    }

    public function getDadosByFilter(int $obr01_licitacao):?array
    {
        $query = $this->model->query();

        $query->where('obr01_licitacao', $obr01_licitacao);

        return $query->get()->toArray();
    }

    public function findByLicitacao(int $l20_codigo){
        return $this->model
            ->join(
                'liclicita',
                'liclicita.l20_codigo',
                '=',
                'licobras.obr01_licitacao'
            )
            ->join(
                'cflicita',
                'cflicita.l03_codigo',
                '=',
                'liclicita.l20_codtipocom'
            )
            ->join(
                'licobraslicitacao',
                'obr07_sequencial',
                '=',
                'obr01_licitacao'
            )
            ->where('obr01_licitacao', $l20_codigo)
            ->first();
    }
}
