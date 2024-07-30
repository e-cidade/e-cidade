<?php

namespace App\Services\Financeiro\Contabilidade;

use stdClass;

interface ContaPlanoContaServiceInterface
{
   
    public function insertContaPlanoConta(stdClass $contaplanoconta,int $codconplano,int $ano);

}
