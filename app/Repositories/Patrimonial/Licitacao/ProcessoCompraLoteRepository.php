<?php

namespace App\Repositories\Patrimonial\Licitacao;
use App\Models\Patrimonial\Licitacao\ProcessoCompraLote;
use Illuminate\Support\Facades\DB;

class ProcessoCompraLoteRepository
{
    private ProcessoCompraLote $model;

    public function __construct()
    {
        $this->model = new ProcessoCompraLote();
    }

    public function getLoteByProcessoCompra(int $pc68_pcproc){
        return $this->model
            ->where('pc68_pcproc', $pc68_pcproc)
            ->get();
    }

    public function delete(ProcessoCompraLote $aData){
        $aData->delete();
    }

}
