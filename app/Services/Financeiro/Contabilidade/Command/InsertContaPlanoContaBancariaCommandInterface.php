<?php

namespace App\Services\Financeiro\Contabilidade\Command;

use App\Domain\Financeiro\Contabilidade\ContaPlanoContaBancaria;

interface InsertContaPlanoContaBancariaCommandInterface
{
    public function execute(ContaPlanoContaBancaria $contaplano): bool;
}
