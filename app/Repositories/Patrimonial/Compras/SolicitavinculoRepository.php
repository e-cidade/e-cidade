<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Solicitavinculo;
use Illuminate\Database\Capsule\Manager as DB;
use cl_solicitavinculo;
class SolicitavinculoRepository
{
    private Solicitavinculo $model;

    public function __construct()
    {
        $this->model = new Solicitavinculo();
    }

    public function getAbertura(int $compilacao)
    {
        $clSolicitavinculo = new \cl_solicitavinculo();
        $sql = $clSolicitavinculo->sqlSolicitavinculo($compilacao);
        return DB::select($sql);
    }

    public function getEstimativas(int $abertura): array
    {
        $clSolicitavinculo = new \cl_solicitavinculo();
        $sql = $clSolicitavinculo->sqlEstimativas($abertura);
        return DB::select($sql);
    }
}
