<?php

namespace App\Domain\Financeiro\Contabilidade\Factory;

use App\Domain\Financeiro\Contabilidade\ContaPlanoContaBancaria;
use stdClass;

class ContaPlanoContaBancariaFactory
{
   
    public function createByStdLegacy(stdClass $contaPlanoRaw)
    {

        $contaPlano = new ContaPlanoContaBancaria();
   
        $contaPlano->setC56Sequencial((int) $contaPlanoRaw->c56_sequencial)
            ->setC56Contabancaria((int) $contaPlanoRaw->c56_contabancaria)
            ->setC56Codcon((int)$contaPlanoRaw->c56_codcon)
            ->setC56Anousu((int)$contaPlanoRaw->c56_anousu)
            ;
            
        return $contaPlano;
    }
    
}
