<?php

namespace App\Services\Financeiro\Contabilidade\Command;

use App\Domain\Financeiro\Contabilidade\ContaPlano;

interface InsertContaPlanoCommandInterface
{
    public function execute(ContaPlano $contaplano): bool;
}
