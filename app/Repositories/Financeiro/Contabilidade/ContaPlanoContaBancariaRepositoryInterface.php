<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoContaBancaria;
use App\Models\Financeiro\Contabilidade\ConPlanoContaBancaria;

interface ContaPlanoContaBancariaRepositoryInterface
{
    
    public function delete(int $sequencial): bool;

    public function saveByContaPlanoContaBancaria(ContaPlanoContaBancaria $dadosContaPlano): ?ConPlanoContaBancaria;

}
