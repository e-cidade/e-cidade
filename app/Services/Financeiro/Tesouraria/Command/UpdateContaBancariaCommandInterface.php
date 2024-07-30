<?php

namespace App\Services\Financeiro\Tesouraria\Command;

use App\Domain\Financeiro\Tesouraria\ContaBancarias;
use stdClass;

interface UpdateContaBancariaCommandInterface
{
    public function execute(ContaBancarias $contabancaria,stdClass $chavestabela): bool;
    
}
