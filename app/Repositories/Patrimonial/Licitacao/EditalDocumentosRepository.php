<?php

namespace App\Repositories\Patrimonial\Licitacao;
use App\Models\Patrimonial\Licitacao\EditalDocumentos;
use Illuminate\Support\Facades\DB;

class EditalDocumentosRepository
{
    private EditalDocumentos $model;

    public function __construct()
    {
        $this->model = new EditalDocumentos();
    }

    public function getEditalDocumentosByLicitacao(int $l20_codigo){
        return $this->model
            ->where('l48_liclicita', $l20_codigo)
            ->get();
    }

    public function delete(EditalDocumentos $aData){
        $aData->delete();
    }

}
