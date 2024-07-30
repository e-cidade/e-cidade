<?php

namespace App\Domain\Financeiro\Contabilidade\Factory;

use App\Domain\Financeiro\Contabilidade\ContaPlanoConta;
use stdClass;

class ContaPlanoContaFactory
{
   
    public function createByStdLegacy(stdClass $contaPlanoRaw,int $codcon,int $ano)
    {

        $contaPlano = new ContaPlanoConta();
   
        $contaPlano->setC63Codcon((int) $codcon)
            ->setC63Anousu((int) $ano)
            ->setC63Banco((string)$contaPlanoRaw->db83_codbanco)
            ->setC63Agencia((string)$contaPlanoRaw->db83_bancoagencia)
            ->setC63Conta((string)$contaPlanoRaw->db83_conta)
            ->setC63Dvconta((string)$contaPlanoRaw->db83_dvconta)
            ->setC63Dvagencia((string) $contaPlanoRaw->db83_dvagencia)
            ->setC63Identificador((string) $contaPlanoRaw->db83_identificador)
            ->setC63Codigooperacao((string) $contaPlanoRaw->db83_codigooperacao)
            ->setC63Tipoconta((int) $contaPlanoRaw->db83_tipoconta)
            ;
            
        return $contaPlano;
    }
    
}
