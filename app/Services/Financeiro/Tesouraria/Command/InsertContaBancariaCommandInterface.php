<?php

namespace App\Services\Financeiro\Tesouraria\Command;

use App\Domain\Financeiro\Tesouraria\ContaBancarias;

interface InsertContaBancariaCommandInterface
{
    public function execute(ContaBancarias $contabancaria): bool;
}
