<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Solicitemunid;
use Illuminate\Support\Facades\DB;

class SolicitemunidRepository
{
    private Solicitemunid $model;

    public function __construct()
    {
        $this->model = new Solicitemunid();
    }

    public function insert($dados): Solicitemunid
    {
        return $this->model->create($dados);
    }

    public function excluir($pc17_codigo)
    {
        $sql = "DELETE FROM solicitemunid WHERE pc17_codigo IN ($pc17_codigo)";
        return DB::statement($sql);
    }
}
