<?php

namespace App\Repositories\Patrimonial;

use App\Models\Veiculo;

class VeiculosRepository
{
    private Veiculo $model;

    public function __construct()
    {
        $this->model = new Veiculo();
    }

    public function

    getVeiculoByPlaca(string $placa, int $ve01_instit,array $campos = ['*']): ?Veiculo
    {
        return $this->model->where('ve01_placa', $placa)
            ->where('ve01_instit',$ve01_instit)
            ->first($campos);
    }
}
