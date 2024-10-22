<?php

namespace App\Repositories\Configuracoes;

use App\Models\Configuracoes\DbDepart;

class DbDepartRepository {
    private DbDepart $model;

    public function __construct()
    {
        $this->model = new DbDepart();
    }

    public function getDbDepart(){
        $result = $this->model
                ->orderby('descrdepto', 'asc')
                ->get()
                ->toArray();

        return $result;
    }
}
