<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Precoreferencia;
use Illuminate\Support\Facades\DB;

class PrecoreferenciaRepository
{
    private Precoreferencia $model;

    public function __construct()
    {
        $this->model = new Precoreferencia();
    }

    public function excluir($si01_sequencial):bool
    {
        $sql = "DELETE FROM precoreferencia WHERE si01_sequencial IN ($si01_sequencial)";
        return DB::statement($sql);
    }

}
