<?php

namespace App\Repositories\Patrimonial\Compras;
use App\Models\Patrimonial\Compras\Pcproc;
use cl_pcproc;
use Illuminate\Support\Facades\DB;

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

    public function getProcessodeComprasLicitacaoByCriterioAdjudicacao(string $where):array
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->queryProcessodeComprasLicitacaoByCriterioAdjudicacao($where);
        return DB::select($sql);
    }

    public function getProcessodeComprasVinculados(int $l20_codigo, ?int $pc80_codprocexcluir = null):array
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->queryProcessosdeComprasVinculados($l20_codigo, $pc80_codprocexcluir);

        if(!empty($pc80_codprocexcluir)){
            $sql .= " AND pc81_codproc = $pc80_codprocexcluir ";
        }

        return DB::select($sql);
    }

    public function getDadosProcesso($pc80_codproc):array
    {
        $clPcproc = new cl_pcproc();
        $sql = $clPcproc->getDadosProcessoCompras($pc80_codproc);
        return DB::select($sql);
    }

    public function getDadosProcessoByCodigo($pc80_codproc, $instit):?array{
        return $this->model
            ->select()
            ->join(
                'db_usuarios',
                'db_usuarios.id_usuario',
                '=',
                'pcproc.pc80_usuario'
            )
            ->join(
                'db_depart',
                'db_depart.coddepto',
                '=',
                'pcproc.pc80_depto'
            )
            ->where('db_depart.instit', $instit)
            ->where('pcproc.pc80_codproc', $pc80_codproc)
            ->get()
            ->toArray();
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

    public function getByCod(int $pc80_codproc){
        return $this->model
            ->where('pcproc.pc80_codproc', $pc80_codproc)
            ->first();
    }

    public function getItensByAdesaoPreco($si06_sequencial, $si06_processocompra, $si06_criterioadjudicacao){
        $query = DB::table('pcproc')
            ->selectRaw('
                DISTINCT pc01_codmater, 
                pc11_seq, 
                pc81_codprocitem, 
                pc01_descrmater, 
                si06_sequencial, 
                m61_descr, 
                m61_codmatunid, 
                pc11_quant, 
                z01_nome, 
                itensregpreco.*, 
                CASE 
                    WHEN si07_percentual IS NULL THEN si02_mediapercentual 
                    ELSE si07_percentual 
                END AS percentual
            ')
            ->join(
                'adesaoregprecos', 
                'si06_processocompra', 
                '=', 
                'pc80_codproc'
            )
            ->join(
                'precoreferencia', 
                'si01_processocompra', 
                '=', 
                'pc80_codproc'
            )
            ->join(
                'pcprocitem', 
                'pc81_codproc', 
                '=', 
                'pc80_codproc'
            )
            ->join('solicitem', 'pc11_codigo', '=', 'pc81_solicitem')
            ->join('solicitemunid', 'pc17_codigo', '=', 'pc11_codigo')
            ->join('solicitempcmater', 'pc16_solicitem', '=', 'pc11_codigo')
            ->join('matunid', 'm61_codmatunid', '=', 'pc17_unid')
            ->join('pcmater', 'pc01_codmater', '=', 'pc16_codmater')
            ->leftJoin('itensregpreco', function ($join) {
                $join->on('si07_item', '=', 'pc01_codmater')
                    ->on('si07_sequencialadesao', '=', 'si06_sequencial');
            })
            ->leftJoin('cgm', 'z01_numcgm', '=', 'si07_fornecedor')
            ->join('pcorcamitemproc', 'pc31_pcprocitem', '=', 'pc81_codprocitem')
            ->join('itemprecoreferencia', 'si02_itemproccompra', '=', 'pc31_orcamitem')
            ->where('si06_sequencial', $si06_sequencial)
            ->where('si06_processocompra', $si06_processocompra)
            ->orderBy('pc11_seq');
    
        if ($si06_criterioadjudicacao == 1){
            $query->where('pc01_tabela', 't');
        } 

        if ($si06_criterioadjudicacao == 2){
            $query->where('pc01_taxa', 't');
        } 
        
        $total = $query->count();

        $data = $query->get()->toArray();

        return ['total' => $total, 'data' => $data];
    }
}
