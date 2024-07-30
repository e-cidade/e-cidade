<?php

namespace App\Services\Financeiro\Contabilidade;

use stdClass;

interface ContaPlanoReduzServiceInterface
{

    public function insertContaPlanoReduz(stdClass $contaplanoreduz);

    public function searchContaPlanoReduz();

}
