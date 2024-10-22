<?php

namespace App\Repositories\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcCategoria;
use App\Repositories\Contracts\Patrimonial\PlanoDeContratacao\PcCategoriaRepositoryInterface;

class PcCategoriaRepository implements PcCategoriaRepositoryInterface
{
    private PcCategoria $model;

    public function __construct()
    {
        $this->model = new PcCategoria();
    }

    public function getDados()
    {
        $result = $this->model
            ->orderby('mpc03_pcdesc', 'asc')
            ->get()
            ->toArray();

        return $result;
    }
}
