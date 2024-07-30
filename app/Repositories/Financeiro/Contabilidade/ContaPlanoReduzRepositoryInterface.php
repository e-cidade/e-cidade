<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoReduz;
use App\Models\Financeiro\Contabilidade\ConPlanoReduz;
use Illuminate\Database\Eloquent\Collection;

interface ContaPlanoReduzRepositoryInterface
{
    
    public function delete(int $sequencial): bool;

    public function update(int $sequencialcontaplanoreduz, array $dadosContaPlanoReduz) : bool;

    public function saveByContaPlanoReduz(ContaPlanoReduz $dadosContaPlanoReduz): ?ConPlanoReduz;

    public function searchContaPlanoReduzAccounts(): ?Collection;

    public function setvalContaPlanoReduzAccounts(int $sequencialcontaplanoreduz) : bool;

}
