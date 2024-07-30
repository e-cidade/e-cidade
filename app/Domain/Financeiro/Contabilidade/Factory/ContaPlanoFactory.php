<?php

namespace App\Domain\Financeiro\Contabilidade\Factory;

use App\Domain\Financeiro\Contabilidade\ContaPlano;
use stdClass;

class ContaPlanoFactory
{
   
    public function createByStdLegacy(stdClass $contaPlanoRaw)
    {

        $contaPlano = new ContaPlano();
   
        $contaPlano->setC60Codcon((int) $contaPlanoRaw->c60_codcon)
            ->setC60Anousu((int) $contaPlanoRaw->c60_anousu)
            ->setC60Estrut((string) $contaPlanoRaw->c60_estrut)
            ->setC60Descr((string) $contaPlanoRaw->c60_descr)
            ->setC60Finali((string)$contaPlanoRaw->c60_finali)
            ->setC60Codsis((int) $contaPlanoRaw->c60_codsis)
            ->setC60Codcla((int) $contaPlanoRaw->c60_codcla)
            ->setC60Consistemaconta((int) $contaPlanoRaw->c60_consistemaconta)
            ->setC60Identificadorfinanceiro((string) $contaPlanoRaw->c60_identificadorfinanceiro)
            ->setC60Naturezasaldo((int)$contaPlanoRaw->c60_naturezasaldo)
            ->setC60Funcao((string) $contaPlanoRaw->c60_funcao)
            ->setC60Tipolancamento((int) $contaPlanoRaw->c60_tipolancamento)
            ->setC60Subtipolancamento((int) $contaPlanoRaw->c60_subtipolancamento)
            ->setC60Desdobramneto((int) $contaPlanoRaw->c60_desdobramneto)
            ->setC60Nregobrig((int) $contaPlanoRaw->c60_nregobrig)
            ->setC60Cgmpessoa((int) $contaPlanoRaw->c60_cgmpessoa)
            ->setC60Naturezadareceita((int) $contaPlanoRaw->c60_naturezadareceita)
            ->setC60Infcompmsc((int) $contaPlanoRaw->c60_infcompmsc)
            ;
            
        return $contaPlano;
    }
    
}
