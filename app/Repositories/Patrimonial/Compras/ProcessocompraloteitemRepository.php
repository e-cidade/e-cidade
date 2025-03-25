<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Processocompraloteitem;
use Illuminate\Support\Facades\DB;
class ProcessocompraloteitemRepository
{
    private Processocompraloteitem $model;

    public function __construct()
    {
        $this->model = new Processocompraloteitem();
    }

    public function insert($dados): Processocompraloteitem
    {
        return $this->model->create($dados);
    }

    public function excluir($pc69_pcprocitem)
    {
        $sql = "DELETE FROM processocompraloteitem WHERE pc69_pcprocitem IN ($pc69_pcprocitem)";
        return DB::statement($sql);
    }
}
