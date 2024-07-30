<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicAbastPosto;

class VeicAbastPostoRepository
{
    private VeicAbastPosto $model;

    public function __construct()
    {
        $this->model = new VeicAbastPosto();
    }

    public function insert(array $dados): ? VeicAbastPosto
    {
        $ve71Codigo = $this->model->getNextval();
        $dados['ve71_codigo'] =  $ve71Codigo;
        return $this->model->create($dados);
    }
}

