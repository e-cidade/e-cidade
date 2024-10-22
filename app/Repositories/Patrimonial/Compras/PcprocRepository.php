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

    public function getProcessodeComprasLicitacao(string $where):array
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->queryProcessodeComprasLicitacao($where);
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

    public function getOrigimProcessodeCompras($pc80_codproc):array
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->sqlOrigemProcessoDecompras($pc80_codproc);
        return DB::select($sql);
    }

    public function getEstimativasOnPcproc(int $pc80_codproc)
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->sqlEstimativas($pc80_codproc);
        return DB::select($sql);
    }

    public function getLicitacaoPcproc(int $pc80_codproc)
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->getlicitacao($pc80_codproc);
        return DB::select($sql);
    }
}
