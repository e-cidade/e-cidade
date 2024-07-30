<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlano;
use App\Models\Financeiro\Contabilidade\ConPlano;
use Illuminate\Database\Eloquent\Collection;

interface ContaPlanoRepositoryInterface
{

    public function delete(int $sequencial): bool;
    
    public function update(int $sequencialcontaplano, array $dadosContaPlano) : bool;

    public function setvalContaPlanoAccounts(int $sequencialcontaplano) : bool;

    public function saveByContaPlano(ContaPlano $dadosContaPlano): ?ConPlano;

    public function searchContaPlanoAccounts(): ?Collection;

    public function searchEstruturalAccounts(int $ultimoEstrut,int $ano): ?Collection;

    public function searchNewEstruturalAccounts(int $ultimoEstrut,int $ano): ?Collection;
    
}
