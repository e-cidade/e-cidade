<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Pcgrupo;
use Illuminate\Database\Capsule\Manager as DB;

class PcgrupoRepository
{
    private Pcgrupo $model;

    public function __construct()
    {
        $this->model = new Pcgrupo();
    }

    public function getGrupo($pc03_codgrupo)
    {
        $sql = "select * from pcgrupo where pc03_codgrupo = $pc03_codgrupo and pc03_instit in (0,".db_getsession('DB_instit').")";
        return DB::select($sql);
    }
}
