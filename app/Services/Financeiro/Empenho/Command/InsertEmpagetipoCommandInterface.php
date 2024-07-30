<?php

namespace App\Services\Financeiro\Empenho\Command;

use App\Domain\Financeiro\Empenho\Empenhopagetipo;

interface InsertEmpagetipoCommandInterface
{
    public function execute(Empenhopagetipo $empagetipo): bool;
}
