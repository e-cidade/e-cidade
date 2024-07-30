<?php

namespace App\Domain\Financeiro\Contabilidade\Factory;

use App\Domain\Financeiro\Contabilidade\ContaPlanoContaCorrente;
use stdClass;

class ContaPlanoContaCorrenteFactory
{
   
    public function createByStdLegacy(stdClass $contaPlanoRaw)
    {

        $contaPlano = new ContaPlanoContaCorrente();
   
        $contaPlano->setC18Sequencial((int) $contaPlanoRaw->c18_sequenciall)
            ->setC18Codcon((int)$contaPlanoRaw->c18_codcon)
            ->setC18Anousu((int)$contaPlanoRaw->c18_anousu)
            ->setC18Contacorrente((int) $contaPlanoRaw->c18_contacorrente)
            ;
            
        return $contaPlano;
    }
    
}
