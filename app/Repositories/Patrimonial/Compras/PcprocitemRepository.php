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
        $campos = "pc11_seq,pc01_codmater,pc01_descrmater,pc11_quant,pc81_codprocitem,si02_vlprecoreferencia,si02_vltotalprecoreferencia,m61_descr,pc11_reservado";
        $sql = $pcprocitem->queryGetItens($pc81_codproc,$campos,"","pc11_seq");
        return DB::select($sql);
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
