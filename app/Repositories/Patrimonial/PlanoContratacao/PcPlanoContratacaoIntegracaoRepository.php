<?php
namespace App\Repositories\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacaoIntegracao;
use App\Repositories\Contracts\Patrimonial\PlanoDeContratacao\PcPlanoContratacaoIntegracaoInterface;

class PcPlanoContratacaoIntegracaoRepository implements PcPlanoContratacaoIntegracaoInterface{
    private PcPlanoContratacaoIntegracao $model;

    public function __construct()
    {
        $this->model = new PcPlanoContratacaoIntegracao();
    }

    public function save(PcPlanoContratacaoIntegracao $pcPlanoIntegracao): PcPlanoContratacaoIntegracao
    {
        $pcPlanoIntegracao->save();
        return $pcPlanoIntegracao;
    }

    public function getCodigo(): int
    {
        return $this->model->getNextval();
    }

}
