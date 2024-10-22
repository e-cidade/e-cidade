<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_liclicitem;
use App\Models\Patrimonial\Licitacao\Liclicitem;
use Illuminate\Database\Capsule\Manager as DB;

class LicilicitemRepository
{
    private Liclicitem $model;

    public function __construct()
    {
        $this->model = new Liclicitem();
    }

    public function insert(array $dados): ?Liclicitem
    {
        $l21_codigo = $this->model->getNextval();
        $dados['l21_codigo'] =  $l21_codigo;
        return $this->model->create($dados);
    }

    public function delete($l21_codigo)
    {
        $sql = "DELETE FROM liclicitem WHERE l21_codigo = $l21_codigo";
        return DB::statement($sql);
    }

    public function getOrdemItens($l20_codigo)
    {
        $cllicitem = new cl_liclicitem();
        $sql = $cllicitem->queryOrdemItens($l20_codigo);
        return DB::select($sql);
    }
    public function getItensLicitacao($l20_codigo,$l224_forne,$lote)
    {
        $cllicitem = new cl_liclicitem();
        $sql = $cllicitem->getItensLicitacao($l20_codigo,$l224_forne,$lote);
        return DB::select($sql);
    }
    public function getPrecoReferencia($l20_codigo)
    {
        $cllicitem = new cl_liclicitem();
        $sql = $cllicitem->getPrecoReferencia($l20_codigo);
        return DB::select($sql);
    }
    
}
