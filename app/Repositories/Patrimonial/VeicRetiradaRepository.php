<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicRetirada;

class VeicRetiradaRepository
{
    private VeicRetirada $model;

    public function __construct()
    {
        $this->model = new VeicRetirada();
    }

    public function insert(array $dados): ?VeicRetirada
    {
        $ve60Codigo = $this->model->getNextval();
        $dados['ve60_codigo'] =  $ve60Codigo;
        return $this->model->create($dados);
    }

    public function verificaRetiradaSemDevolucao($veiculo)
    {

    }
}

