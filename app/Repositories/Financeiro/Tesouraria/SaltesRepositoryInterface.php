<?php

namespace App\Repositories\Financeiro\Tesouraria;

use App\Domain\Financeiro\Tesouraria\ContaSaltes;
use App\Models\Financeiro\Tesouraria\Saltes;

interface SaltesRepositoryInterface
{
    
    public function delete(int $sequencial): bool;

    public function update(int $sequencialsaltes, array $dadossaltes) : bool;

    public function saveBySaltes(ContaSaltes $dadosSaltes): ?Saltes;

}
