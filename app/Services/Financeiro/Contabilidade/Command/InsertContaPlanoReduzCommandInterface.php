<?php

namespace App\Services\Financeiro\Contabilidade\Command;

use App\Domain\Financeiro\Contabilidade\ContaPlanoReduz;

interface InsertContaPlanoReduzCommandInterface
{
    public function execute(ContaPlanoReduz $contaplanoreduz): bool;
}
