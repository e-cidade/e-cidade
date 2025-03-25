<?php

namespace App\Repositories\Configuracoes;

use App\Models\Configuracoes\ManutencaoLicitacao;

class ManutencaoLicitacaoRepository {
    private ManutencaoLicitacao $model;

    public function __construct()
    {
        $this->model = new ManutencaoLicitacao();
    }

    public function getByLiclicita(int $l20_codigo){
        $result = $this->model
                ->where('manutlic_licitacao', $l20_codigo)
                ->get()
                ->toArray();

        return $result;
    }

    function delete(ManutencaoLicitacao $aData){
        $aData->delete();
    }

    public function getManutencaoByCodigo($manutlic_sequencial){
        return $this->model->where('manutlic_sequencial', $manutlic_sequencial)->first();
    }

}
