<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicAbast;
use App\Models\VeicDevolucao;

class VeicDevolucaoRepository
{
    private VeicDevolucao $model;

    public function __construct()
    {
        $this->model = new VeicDevolucao();
    }

    /**
     * @param array $dados
     * @return VeicDevolucao|null
     */
    public function insert(array $dados): ?VeicDevolucao
    {
        $ve61Codigo = $this->model->getNextval();
        $dados['ve61_codigo'] = $ve61Codigo;
        return $this->model->create($dados);
    }
}

