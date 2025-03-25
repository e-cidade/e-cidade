<?php

namespace App\Repositories\Financeiro\Tesouraria;

use App\Domain\Financeiro\Tesouraria\ContaBancarias;
use App\Models\Financeiro\Tesouraria\ContaBancaria;
use Illuminate\Database\Eloquent\Collection;

interface ContaBancariaRepositoryInterface
{
    
    public function delete(int $sequencial): bool;

    public function update(int $sequencialcontabancaria, array $dadosContaBancaria) : bool;
    
    public function saveByContaBancaria(ContaBancarias $dadosContaBancaria): ?ContaBancaria;

    public function searchSequentialAccounts(): ?Collection;

    public function checkAccountExists(int $sequencial): ?Collection;

    public function checkAllTables(int $sequencial,int $reduzido,int $instituicao): ?Collection;

    public function checkRepeated(int $agencia, string $conta, int $tipoconta, string $fonte, int $nroseqaplicacao): ?Collection;

}
