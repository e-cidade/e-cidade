<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoConta;
use App\Models\Financeiro\Contabilidade\ConPlanoConta;

interface ContaPlanoContaRepositoryInterface
{
    
    public function delete(int $sequencial): bool;

    public function dataSessao(int $ano,int $instituicao);

    public function update(int $sequencialcontaplanoconta, array $dadosContaPlanoconta) : bool;

    public function saveByContaPlanoConta(ContaPlanoConta $dadosContaPlano): ?ConPlanoConta;


}
