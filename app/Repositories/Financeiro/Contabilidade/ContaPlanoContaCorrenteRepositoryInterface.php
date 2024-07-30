<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoContaCorrente;
use App\Models\Financeiro\Contabilidade\ConPlanoContaCorrente;

interface ContaPlanoContaCorrenteRepositoryInterface
{

    public function delete(int $sequencial): bool;

    public function saveByContaPlanoContaCorrente(ContaPlanoContaCorrente $dadosContaPlano): ?ConPlanoContaCorrente;

}
