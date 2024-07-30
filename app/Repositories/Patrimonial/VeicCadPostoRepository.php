<?php
namespace App\Repositories\Patrimonial;

use App\Models\VeicCadPosto;

class VeicCadPostoRepository
{
    private VeicCadPosto $model;

    public function __construct()
    {
        $this->model = new VeicCadPosto();
    }

    public function insert(int $tipo)
    {
        $ve29Codigo = $this->model->getNextval();
        $dados = ['ve29_codigo' => $ve29Codigo, 've29_tipo' => $tipo];
        return $this->model->create($dados);
    }

}
