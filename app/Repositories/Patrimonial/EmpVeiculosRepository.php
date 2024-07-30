<?php

namespace App\Repositories\Patrimonial;

use App\Models\EmpVeiculos;


class EmpVeiculosRepository
{
    private EmpVeiculos $model;

    public function __construct()
    {
        $this->model = new EmpVeiculos();
    }

    public function insert(array $dados): ?EmpVeiculos
    {
        $si05Sequencial = $this->model->getNextval();
        $dados['si05_sequencial'] = $si05Sequencial;
        return $this->model->create($dados);
    }
}
