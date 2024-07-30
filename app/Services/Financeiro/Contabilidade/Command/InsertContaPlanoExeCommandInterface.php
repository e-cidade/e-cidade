<?php

namespace App\Services\Financeiro\Contabilidade\Command;

use App\Domain\Financeiro\Contabilidade\ContaPlanoExe;

interface InsertContaPlanoExeCommandInterface
{
    public function execute(ContaPlanoExe $contaplanoexe): bool;
}
