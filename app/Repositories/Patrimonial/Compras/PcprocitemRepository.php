<?php

namespace App\Repositories\Patrimonial\Compras;
use App\Models\Patrimonial\Compras\Pcprocitem;
use cl_pcprocitem;
use Illuminate\Database\Capsule\Manager as DB;

class PcprocitemRepository
{
    private Pcprocitem $model;

    public function __construct()
    {
        $this->model = new Pcprocitem();
    }

    public function getItens($pc81_codproc)
    {
        $pcprocitem = new cl_pcprocitem();
        $sql = $pcprocitem->queryGetItens($pc81_codproc);
        return DB::select($sql);
    }

    public function getItensProcOnLiclicitem($pc81_codproc,$l21_codliclicita)
    {
        $pcprocitem = new cl_pcprocitem();
        $sql = $pcprocitem->queryItensOnLiclicitem($pc81_codproc,$l21_codliclicita);
        return DB::select($sql);
    }
}
