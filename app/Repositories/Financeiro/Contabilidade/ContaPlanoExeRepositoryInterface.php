<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoExe;
use App\Models\Financeiro\Contabilidade\ConPlanoExe;

interface ContaPlanoExeRepositoryInterface
{
    
    public function delete(int $sequencial): bool;

    public function update(int $sequencialcontaplanoexe, array $dadosContaPlanoexe) : bool;

    public function saveByContaPlanoExe(ContaPlanoExe $dadosContaPlanoExe): ?ConPlanoExe;

}
