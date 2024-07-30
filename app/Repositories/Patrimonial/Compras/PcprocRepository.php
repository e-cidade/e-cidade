<?php

namespace App\Repositories\Patrimonial\Compras;
use App\Models\Patrimonial\Compras\Pcproc;
use cl_pcproc;
use Illuminate\Database\Capsule\Manager as DB;

class PcprocRepository
{
    private Pcproc $model;

    public function __construct()
    {
        $this->model = new Pcproc();
    }

    public function getProcessodeComprasLicitacao():array
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->queryProcessodeComprasLicitacao();
        return DB::select($sql);
    }

    public function getProcessodeComprasVinculados($l20_codigo):array
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->queryProcessosdeComprasVinculados($l20_codigo);
        return DB::select($sql);
    }

    public function getDadosProcesso($pc80_codproc):array
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->getDadosProcessoCompras($pc80_codproc);
        return DB::select($sql);
    }
}
