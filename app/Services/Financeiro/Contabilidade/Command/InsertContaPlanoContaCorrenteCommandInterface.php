<?php

namespace App\Services\Financeiro\Contabilidade\Command;

use App\Domain\Financeiro\Contabilidade\ContaPlanoContaCorrente;

interface InsertContaPlanoContaCorrenteCommandInterface
{
    public function execute(ContaPlanoContaCorrente $contaplano): bool;
}
