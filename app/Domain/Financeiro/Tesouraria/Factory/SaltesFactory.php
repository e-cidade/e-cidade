<?php

namespace App\Domain\Financeiro\Tesouraria\Factory;

use App\Domain\Financeiro\Tesouraria\ContaSaltes;
use DateTime;
use stdClass;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;

class SaltesFactory
{
   
    public function createByStdLegacy(stdClass $saltesDados)
    {
  
        $saltes = new ContaSaltes();
        
        $saltes->setK13Conta((int) $saltesDados->k13_conta)
            ->setK13Reduz((int) $saltesDados->k13_reduz)
            ->setK13Descr((string) $saltesDados->k13_descr)
            ->setK13Saldo((float) $saltesDados->k13_saldo)
            ->setK13Ident((string)$saltesDados->k13_ident)
            ->setK13Vlratu((float) $saltesDados->k13_vlratu)
            ->setK13Datvlr((string) $saltesDados->k13_datvlr)
            ->setK13Limite((string) $saltesDados->k13_limite)
            ->setK13Dtimplantacao((string) $saltesDados->k13_dtimplantacao);

        return $saltes;
    }
    
}
