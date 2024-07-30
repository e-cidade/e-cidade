<?php

namespace App\Domain\Financeiro\Contabilidade\Factory;

use App\Domain\Financeiro\Contabilidade\ContaPlanoExe;
use stdClass;

class ContaPlanoExeFactory
{
   
    public function createByStdLegacy(stdClass $contaPlanoExeRaw)
    {

        $contaPlanoExe = new ContaPlanoExe();
   
        $contaPlanoExe->setC62Anousu((int) $contaPlanoExeRaw->c62_anousu)
            ->setC62Reduz((int) $contaPlanoExeRaw->c62_reduz)
            ->setC62Codrec((int)$contaPlanoExeRaw->c62_codrec)
            ->setC62Vlrcre((int)$contaPlanoExeRaw->c62_vlrcre)
            ->setC62Vlrdeb((int)$contaPlanoExeRaw->c62_vlrdeb)
            ;
            
        return $contaPlanoExe;
    }
    
}
