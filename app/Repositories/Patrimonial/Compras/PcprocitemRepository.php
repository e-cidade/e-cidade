<?php

namespace App\Repositories\Patrimonial\Compras;
use App\Models\Patrimonial\Compras\Pcprocitem;
use cl_pcprocitem;
use Illuminate\Support\Facades\DB;

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
        $campos = "pc11_seq,pc11_numero,pc01_codmater,pc01_descrmater,pc01_complmater,pc11_quant,pc81_codprocitem,si02_vlprecoreferencia,si02_vltotalprecoreferencia";
        $sql = $pcprocitem->queryGetItens($pc81_codproc,$campos,"","pc11_seq");
        return DB::select($sql);
    }

    public function verificaCota($pc01_codmater,$pc11_numero):bool
    {
        $pcprocitem = new cl_pcprocitem();
        $campos = "*";
        $where = "pc01_codmater = $pc01_codmater and pc11_numero = $pc11_numero and pc11_reservado='t'";
        $sql = $pcprocitem->queryGetItens("",$campos,$where,"pc11_seq");
        $rscota = DB::select($sql);
        if($rscota){
            return true;
        }
        return false;
    }
    public function getItensLicitacao($pc81_codproc)
    {
        $pcprocitem = new cl_pcprocitem();
        $campos = "
            pc11_seq,
            pc01_codmater,
            pc01_descrmater,
            pc11_quant,
            pc81_codprocitem,
            si02_vlprecoreferencia,
            si02_vltotalprecoreferencia,
            m61_codmatunid,
            m61_descr,
            pc11_reservado
        ";
        $sql = $pcprocitem->queryGetItens($pc81_codproc,$campos,"","pc11_seq");
        return DB::select($sql);
    }

    public function getItensLicLicitemAndLicitacao(?int $pc81_codproc, int $l20_codigo, int $limit = 15, int $offset = 0, bool $isPaginate = true){
        $query = $this->model->query();

        $query->select(
            'pc01_codmater',
            'pc11_seq',
            'pc01_descrmater',
            'pc01_complmater',
            'pc11_quant',
            'm61_codmatunid',
            'm61_descr',
            'pc11_reservado',
            DB::raw('
                CASE
                    WHEN pc11_reservado = \'t\' THEN \'SIM\'
                    ELSE \'NÃO\'
                END as reservado
            '),
            'pc81_codprocitem',
            'l21_codigo',
            'l04_codigo',
            'l04_descricao',
            'l21_sigilo',
            'pc81_codproc',
            'pc68_nome'
        );

        $query->leftJoin(
            'processocompraloteitem',
            'pc69_pcprocitem',
            '=',
            'pc81_codprocitem'
        );
        $query->leftJoin(
            'processocompralote',
            'pc68_sequencial',
            '=',
            'pc69_processocompralote'
        );
        $query->join(
            'solicitem',
            'pc11_codigo',
            '=',
            'pc81_solicitem'
        );
        $query->join(
            'solicitempcmater',
            'pc16_solicitem',
            '=',
            'pc11_codigo'
        );
        $query->join(
            'solicitemunid',
            'pc17_codigo',
            '=',
            'pc11_codigo'
        );
        $query->join(
            'matunid',
            'm61_codmatunid',
            '=',
            'pc17_unid'
        );
        $query->join(
            'pcmater',
            'pc01_codmater',
            '=',
            'pc16_codmater'
        );
        $query->join(
            'pcorcamitemproc',
            'pc31_pcprocitem',
            '=',
            'pc81_codprocitem'
        );
        $query->join(
            'pcorcamitem',
            'pc22_orcamitem',
            '=',
            'pc31_orcamitem'
        );
        $query->join(
            'itemprecoreferencia',
            'si02_itemproccompra',
            '=',
            'pc22_orcamitem'
        );

        if(!empty($pc81_codproc)){
            $query->leftJoin(
                'liclicitem',
                function($join) use ($l20_codigo) {
                    $join->on(
                        'l21_codpcprocitem',
                        '=',
                        'pc81_codprocitem'
                    )
                    ->on(
                        'l21_codliclicita',
                        '=',
                        DB::raw($l20_codigo)
                    );
                }
            );
        } else {
            $query->join(
                'liclicitem',
                function($join) use ($l20_codigo) {
                    $join->on(
                        'l21_codpcprocitem',
                        '=',
                        'pc81_codprocitem'
                    )
                    ->on(
                        'l21_codliclicita',
                        '=',
                        DB::raw($l20_codigo)
                    );
                }
            );
        }

        $query->leftJoin(
            'liclicitemlote',
            'l04_liclicitem',
            '=',
            'l21_codigo'
        );

        if(!empty($pc81_codproc)){
            $query->where('pc81_codproc', '=', $pc81_codproc);
        }

        $query->orderBy('l21_ordem', 'asc');

        $total = $query->count();

        if($isPaginate){
            $query->limit($limit);
            $query->offset(($offset * $limit));
        }

        $data = $query->get()->toArray();

        return ['total' => $total, 'data' => $data];
    }

    public function getItensProcesso($l20_codigo){
        return $this->model
            ->distinct()
            ->select('pc81_codproc')
            ->join('solicitem', 'pc81_solicitem', '=', 'pc11_codigo')
            ->whereIn('pc81_codprocitem', function ($query) use ($l20_codigo) {
                $query->select('l21_codpcprocitem')
                    ->from('liclicitem')
                    ->where('l21_codliclicita', $l20_codigo);
            })
            ->orderBy('pc81_codproc')
            ->get()
            ->toArray();
    }

    public function getItensCota($pc81_codproc)
    {
        $pcprocitem = new cl_pcprocitem();
        $campos = "pc11_seq,pc11_numero,pc01_codmater,pc01_descrmater,pc11_quant,pc81_codprocitem,pc11_reservado,pc11_exclusivo,si02_vlprecoreferencia,si02_vltotalprecoreferencia";
        $sql = $pcprocitem->queryGetItens($pc81_codproc,$campos,"","pc11_seq");
        return DB::select($sql);
    }

    public function getItensProcOnLiclicitem($pc81_codproc,$l21_codliclicita)
    {
        $pcprocitem = new cl_pcprocitem();
        $sql = $pcprocitem->queryItensOnLiclicitem($pc81_codproc,$l21_codliclicita);
        return DB::select($sql);
    }

    public function getSolicitemfromPcprocItem($campos, $pc81_codprocitem): array
    {
        $pcprocitem = new cl_pcprocitem();
        $sql = $pcprocitem->getItensonSolicitem($campos,$pc81_codprocitem);
        return DB::select($sql);
    }

    public function getOrcamento($pc81_codprocitem)
    {
        $pcprocitem = new cl_pcprocitem();
        $sql = $pcprocitem->getOrcamento($pc81_codprocitem);
        return DB::select($sql);
    }

    public function insert($dados): Pcprocitem
    {
        $pc81_codprocitem = $this->model->getNextval();
        $dados['pc81_codprocitem'] =  $pc81_codprocitem;
        return $this->model->create($dados);
    }

    public function excluir(string $where)
    {
        $sql = "DELETE FROM pcprocitem WHERE $where";
        return DB::statement($sql);
    }

    public function getLotesCompras(int $pc81_codprocitem)
    {
        $sql = "select processocompraloteitem.*,processocompralote.* from pcprocitem
                    join processocompraloteitem on pc69_pcprocitem = pc81_codprocitem
                    join processocompralote on pc68_sequencial = pc69_processocompralote
                    where pc81_codprocitem = $pc81_codprocitem";
        $resultLotes = DB::select($sql);
        return $resultLotes[0];
    }
}
