<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicAbastRetirada;

class VeicAbastRetiradaRepository
{
    private VeicAbastRetirada $model;

    public function __construct()
    {
        $this->model = new VeicAbastRetirada();
    }

    public function insert(array $dados): ?VeicAbastRetirada
    {
        $ve73Codigo = $this->model->getNextval();
        $dados['ve73_codigo'] =  $ve73Codigo;
        return $this->model->create($dados);
    }
}

