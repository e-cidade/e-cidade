<?php

namespace App\Domain\Financeiro\Empenho\Factory;

use App\Domain\Financeiro\Empenho\Empenhopagetipo;
use stdClass;

class EmpagetipoFactory
{
   
    public function createByStdLegacy(stdClass $empagetipo)
    {

        $empagetipoNew = new Empenhopagetipo();
   
        $empagetipoNew->setE83Codtipo((int) $empagetipo->e83_codtipo)
            ->setE83Descr((string) $empagetipo->e83_descr)
            ->setE83Conta((int)$empagetipo->e83_conta)
            ->setE83Codmod((int)$empagetipo->e83_codmod)
            ->setE83Convenio($empagetipo->e83_convenio)
            ->setE83Sequencia((int) $empagetipo->e83_sequencia)
            ->setE83Codigocompromisso($empagetipo->e83_codigocompromisso)
            ;
            
        return $empagetipoNew;
    }
    
}
