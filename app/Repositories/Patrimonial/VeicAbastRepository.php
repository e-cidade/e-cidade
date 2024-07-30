<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicAbast;

class VeicAbastRepository
{
    /**
     * @var VeicAbast
     */
    private VeicAbast $model;

    public function __construct()
    {
        $this->model = new VeicAbast();
    }

    /**
     * @param array $dados
     * @return VeicAbast|null
     */
    public function insert(array $dados): ?VeicAbast
    {
        $ve70Codigo = $this->model->getNextval();
        $dados['ve70_codigo'] =  $ve70Codigo;
        return $this->model->create($dados);
    }
}

