<?php

namespace App\Repositories\Financeiro\Empenho;

use App\Domain\Financeiro\Empenho\Empenhopagetipo;
use App\Models\Financeiro\Empenho\Empagetipo;

interface EmpagetipoRepositoryInterface
{
    public function delete(int $sequencial): bool;

    public function update(int $sequencialempagetipo, array $dadosempagetipo) : bool;

    public function saveByEmpagetipo(Empenhopagetipo $dadosEmpagetipo): ?Empagetipo;

}
