<?php

namespace App\Domain\Financeiro\Contabilidade\Factory;

use App\Domain\Financeiro\Contabilidade\ContaPlanoReduz;
use stdClass;

class ContaPlanoReduzFactory
{
   
    public function createByStdLegacy(stdClass $contaPlanoRaw)
    {

        $contaPlano = new ContaPlanoReduz();
   
        $contaPlano->setC61Codcon((int) $contaPlanoRaw->c61_codcon)
            ->setC61Anousu((int) $contaPlanoRaw->c61_anousu)
            ->setC61Reduz((int)$contaPlanoRaw->c61_reduz)
            ->setC61Instit((int)$contaPlanoRaw->c61_instit)
            ->setC61Codigo((int)$contaPlanoRaw->c61_codigo)
            ->setC61Contrapartida((int) $contaPlanoRaw->c61_contrapartida)
            ->setC61Codtce((int) $contaPlanoRaw->c61_codtce)
            ;
            
        return $contaPlano;
    }
    
}
