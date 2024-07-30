<?php

namespace App\Domain\Financeiro\Tesouraria\Factory;

use App\Domain\Financeiro\Tesouraria\ContaBancarias;
use DateTime;
use stdClass;

class ContaBancariaFactory
{
   
    public function createByStdLegacy(stdClass $contaBancariaRaw)
    {
        
        $contaBancaria    = new ContaBancarias();
        
        $oDataImplantacao     = DateTime::createFromFormat('d/m/Y', $contaBancariaRaw->db83_dataimplantaoconta);
        $oDataLimite          = $contaBancariaRaw->k13_limite != '' ? DateTime::createFromFormat('d/m/Y', $contaBancariaRaw->k13_limite) : null;
        $oDataReativacaoconta = $contaBancariaRaw->k13_dtreativacaoconta != '' ? DateTime::createFromFormat('d/m/Y', $contaBancariaRaw->k13_dtreativacaoconta) : null;
      
        $contaBancaria->setDb83Sequencial((int) $contaBancariaRaw->db83_sequencial)
            ->setDb83Descricao((string) $contaBancariaRaw->db83_descricao)
            ->setDb83BancoAgencia((int) $contaBancariaRaw->db83_bancoagencia)
            ->setDb83Conta($contaBancariaRaw->db83_conta)
            ->setDb83DvConta($contaBancariaRaw->db83_dvconta)
            ->setDb83Identificador($contaBancariaRaw->db83_identificador)
            ->setDb83CodigoOperacao($contaBancariaRaw->db83_codigooperacao)
            ->setDb83TipoConta((int) $contaBancariaRaw->db83_tipoconta)
            ->setDb83ContaPlano((bool) $contaBancariaRaw->db83_contaplano)
            ->setDb83Convenio((int)$contaBancariaRaw->db83_convenio)
            ->setDb83TipoAplicacao((int) $contaBancariaRaw->db83_tipoaplicacao)
            ->setDb83NumConvenio((int) $contaBancariaRaw->db83_numconvenio)
            ->setDb83NroSeqAplicacao((int) $contaBancariaRaw->db83_nroseqaplicacao)
            ->setDb83CodigoOpCredito($contaBancariaRaw->db83_codigoopcredito)
            ->setDb83CodigoTce((int)$contaBancariaRaw->db83_codigotce)
            ->setDb83FontePrincipal ($contaBancariaRaw->iCodigoRecurso)
            ->setDb83Reduzido ($contaBancariaRaw->db83_reduzido)
            ->setDb83CodBanco((int) $contaBancariaRaw->db83_codbanco)
            ->setDb83DvAgencia($contaBancariaRaw->db83_dvagencia)
            ->setDb83DataImplantaoConta($oDataImplantacao)
            ->setDb83DataLimite($oDataLimite)
            ->setDb83DataReativacaoConta($oDataReativacaoconta);
        
        return $contaBancaria;
    }

}
