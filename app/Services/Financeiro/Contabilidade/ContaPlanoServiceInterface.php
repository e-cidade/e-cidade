<?php

namespace App\Services\Financeiro\Contabilidade;

use stdClass;

interface ContaPlanoServiceInterface
{
    
    public function insertContaPlano(stdClass $contaplano);

    public function searchContaPlano();

    public function searchEstrutural(int $ultimoEstrut,int $ano);

    public function searchNewEstrutural(int $ultimoEstrut,int $ano);


}
