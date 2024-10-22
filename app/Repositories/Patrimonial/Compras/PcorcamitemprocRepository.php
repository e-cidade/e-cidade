<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Pcorcamitemproc;
use Illuminate\Database\Capsule\Manager as DB;

class PcorcamitemprocRepository
{
    private Pcorcamitemproc $model;

    public function __construct()
    {
        $this->model = new Pcorcamitemproc();
    }

    public function insert($dados): bool
    {
        $pc31_orcamitem = $dados['pc31_orcamitem'];
        $pc31_pcprocitem = $dados['pc31_pcprocitem'];

        $sql = "insert into pcorcamitemproc values($pc31_orcamitem,$pc31_pcprocitem)";
        return DB::statement($sql);
    }

    public function excluir($pc31_orcamitem)
    {
        $sql = "DELETE FROM pcorcamitemproc WHERE pc31_orcamitem IN ($pc31_orcamitem)";
        return DB::statement($sql);
    }
}
