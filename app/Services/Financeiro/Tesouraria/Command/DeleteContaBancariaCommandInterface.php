<?php

namespace App\Services\Financeiro\Tesouraria\Command;

use stdClass;

interface DeleteContaBancariaCommandInterface
{
    public function execute(stdClass $chavestabela): bool;
    
}
