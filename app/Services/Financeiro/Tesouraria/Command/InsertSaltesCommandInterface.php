<?php

namespace App\Services\Financeiro\Tesouraria\Command;

use App\Domain\Financeiro\Tesouraria\ContaSaltes;

interface InsertSaltesCommandInterface
{
    public function execute(ContaSaltes $saltes): bool;
}
