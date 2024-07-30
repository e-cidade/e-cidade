<?php

namespace App\Services\Financeiro\Contabilidade\Command;

use App\Domain\Financeiro\Contabilidade\ContaPlanoConta;

interface InsertContaPlanoContaCommandInterface
{
    public function execute(ContaPlanoConta $contaplanoconta): bool;
}
