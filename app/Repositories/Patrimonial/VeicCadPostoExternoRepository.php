<?php

namespace App\Repositories\Patrimonial;

use App\Models\VeicCadPostoExterno;

class VeicCadPostoExternoRepository
{
    private VeicCadPostoExterno $model;

    public function __construct()
    {
        $this->model = new VeicCadPostoExterno();
    }

    public function getFirstByCgm(int $cgm): ?VeicCadPostoExterno
    {
        return $this->model->where('ve34_numcgm', $cgm)
            ->orderBy('ve34_codigo')
            ->first();
    }

    public function insert(int $ve34Veiccadposto, int $ve34Numcgm)
    {
        $ve34Codigo = $this->model->getNextval();
        $dados = [
            've34_codigo' => $ve34Codigo,
            've34_veiccadposto' => $ve34Veiccadposto,
            've34_numcgm'=> $ve34Numcgm
        ];

        return $this->model->create($dados);
    }
}
