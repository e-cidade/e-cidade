<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ('libs/db_conn.php');
require_once ('libs/db_stdlib.php');
require_once ('libs/db_utils.php');
require_once ("libs/db_app.utils.php");
require_once ('libs/db_conecta.php');
require_once ('libs/JSON.php');
require_once ('libs/db_utils.php');
require_once ('dbforms/db_funcoes.php');
require_once ("classes/db_bancoagencia_classe.php");
require_once ("classes/db_contabancaria_classe.php");
require_once ("classes/db_caiparametro_classe.php");
require_once ("model/financeiro/ContaBancaria.model.php");
require_once ("std/DBDate.php");

db_app::import("exceptions.*");

use  App\Services\Financeiro\Contabilidade\ContaPlanoService;
use  App\Services\Financeiro\Contabilidade\ContaPlanoContaService;
use  App\Services\Financeiro\Contabilidade\ContaPlanoContaBancariaService;
use  App\Services\Financeiro\Contabilidade\ContaPlanoContaCorrenteService;
use  App\Services\Financeiro\Contabilidade\ContaPlanoReduzService;
use  App\Services\Financeiro\Contabilidade\ContaPlanoExeService;
use  App\Services\Financeiro\Tesouraria\ContaBancariaService;
use  App\Services\Financeiro\Tesouraria\SaltesService;
use  App\Services\Financeiro\Empenho\EmpagetipoService;

$oDaoBancoAgencia    = new cl_bancoagencia();
$oDaoContaBancaria   = new cl_contabancaria();
$oJson               = new services_json();

$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$aRetorno            = array();
$oRetorno->status    = 1;
$oRetorno->message   = 'teste';

$aContasBancarias = array("237" => "BANCO BRADESCO S.A",
                          "001" => "BANCO DO BRASIL",
                          "341" => "BANCO ITAU S/A",
                          "041" => "BANRISUL",
                          "104" => "CAIXA ECONOMICA FEDERAL");

/**
 * $iRetorno = 0  retornar um objeto.
 * $iRetorno = 1  retorna o array para o widget do autocomplete
 */
$iRetorno            = 0;

switch ($oParam->exec) {


  case "getDados":
    
    $oDaoContaBancaria                 = new ContaBancaria($oParam->iCodigoContaBancaria);
    $oRetorno->db89_sequencial         = $oDaoContaBancaria->getSequencialBancoAgencia();
    $oRetorno->db89_db_bancos          = $oDaoContaBancaria->getCodigoBanco();
    $oRetorno->db90_descr              = $oDaoContaBancaria->getDescricaoBanco();
    $oRetorno->db89_codagencia         = $oDaoContaBancaria->getNumeroAgencia();
    $oRetorno->db89_digito             = $oDaoContaBancaria->getDVAgencia();
    $oRetorno->db83_conta              = $oDaoContaBancaria->getNumeroConta();
    $oRetorno->db83_dvconta            = $oDaoContaBancaria->getDVConta();
    $oRetorno->db83_identificador      = $oDaoContaBancaria->getIdentificador();
    $oRetorno->db83_codigooperacao     = $oDaoContaBancaria->getCodigoOperacao();
    $oRetorno->db83_tipoconta          = $oDaoContaBancaria->getTipoConta();
    $oRetorno->db83_sequencial         = $oDaoContaBancaria->getSequencialContaBancaria();
   break;
  case "getAgencia":

     $sSqlAgencias = $oDaoBancoAgencia->sql_query("",
                                                " distinct  db89_codagencia, db89_digito  ",
                                                " db89_codagencia ",
                                                " db90_codban = '{$oParam->sBanco}' and db89_codagencia ilike '{$oParam->sAgencia}%' ");
     $rsAgencias   = $oDaoBancoAgencia->sql_record($sSqlAgencias);
     $oAgencias    = db_utils::getCollectionByRecord($rsAgencias);

     foreach ($oAgencias as $oAgencia) {

      $oItensAutoComplete        = new stdClass();
      $oItensAutoComplete->cod   = $oAgencia->db89_codagencia;
      $oItensAutoComplete->label = $oAgencia->db89_codagencia ."-".$oAgencia->db89_digito;
      $aRetorno[]                = $oItensAutoComplete;
      unset($oItensAutoComplete);
     }
     $iRetorno = 1;
  break;
  case "getConta":

    /**
     * Valida se é uma conta do plano de contas
     */
    $lPlanoConta = 'false';
    if ($oParam->isContaPlano) {
      $lPlanoConta = 'true';
    }
    $sSqlContas = $oDaoContaBancaria->sql_query("",
                                              " distinct  db83_sequencial, db83_conta, db83_dvconta,db83_identificador, db83_codigooperacao,db83_tipoconta ",
                                              " db83_conta ",
                                              " db90_codban      ilike '{$oParam->sBanco}'    and
                                                db89_codagencia  ilike '{$oParam->sAgencia}'  and
                                                db83_conta       ilike '{$oParam->sConta}%'   and
                                                db83_contaplano is {$lPlanoConta}
                                              ");
    $rsContas   = $oDaoContaBancaria->sql_record($sSqlContas);
    $oContas    = db_utils::getCollectionByRecord($rsContas);

    foreach ($oContas as $oConta) {

      $oItensAutoComplete        = new stdClass();
      $oItensAutoComplete->cod   = $oJson->encode($oConta);
      $oItensAutoComplete->label = $oConta->db83_conta ."-".$oConta->db83_dvconta. "-Op:".$oConta->db83_codigooperacao;
      $aRetorno[]                = $oItensAutoComplete;
      unset($oItensAutoComplete);
    }
    $iRetorno = 1;
  break;
  case "salvarDados":

    try {

      db_inicio_transacao();
      $oRetorno->sDescricaoContaBancaria = '';

      $oContaBancaria = new ContaBancaria($oParam->oDados->iSequencialConta);
      $oContaBancaria->setDVAgencia       ($oParam->oDados->inputDvAgencia)
                     ->setNumeroAgencia   ($oParam->oDados->inputNumeroAgencia  )
                     ->setCodigoBanco     ($oParam->oDados->inputCodigoBanco)
                     ->setNumeroConta     ($oParam->oDados->inputNumeroConta)
                     ->setDVConta         ($oParam->oDados->inputDvConta)
                     ->setIdentificador   ($oParam->oDados->inputIdentificador)
                     ->setCodigoOperacao  ($oParam->oDados->inputOperacao)
                     ->setTipoConta       ($oParam->oDados->cboTipoConta)
                     ->setPlanoConta      ($oParam->oDados->lContaPlano)
                     ->salvar_conta_pcasp();
      $oRetorno->iSequencialContaBancaria = $oContaBancaria->getSequencialContaBancaria();
      $oRetorno->sDescricaoContaBancaria  = $oContaBancaria->getDadosConta();
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = $eErro->getMessage();
    }
    break;

  case "getDadosContaBancaria":

    try {

      $oContaBancaria    = new ContaBancaria($oParam->iCodigoConta);
      $oRetorno->iCodigoContaBancaria = $oParam->iCodigoConta;
      $oRetorno->iCodigoBanco         = $oContaBancaria->getCodigoBanco();
      $oRetorno->sDescricaoBanco      = $oContaBancaria->getDescricaoBanco();
      $oRetorno->iCodigoAgencia       = $oContaBancaria->getNumeroAgencia();
      $oRetorno->iDigitoAgencia       = $oContaBancaria->getDVAgencia();


    } catch (Exception $eErro) {

      $oRetorno->status = 2;
      $oRetorno->message = $eErro->getMessage();
    }

    break;


  case "getContasPorCodigoBanco":

    try {

      $sWhereContaBancaria  = "     db_bancos.db90_codban = '{$oParam->sCodigoBanco}' \n";
      $sWhereContaBancaria .= "     and db83_contaplano is true                       \n";

      $oDaoContaBancaria    = db_utils::getDao('contabancaria');
      $sSqlBuscaContas      = $oDaoContaBancaria->sql_query(null,
                                                           "distinct db83_identificador",
                                                           null,
                                                           $sWhereContaBancaria);
      $rsBuscaContas       = $oDaoContaBancaria->sql_record($sSqlBuscaContas);

      if ($oDaoContaBancaria->numrows == 0) {

        $sMsgErro  = "Não foi possível buscar os dados de conta bancária para o banco ";
        $sMsgErro .= "{$oParam->sCodigoBanco} - {$aContasBancarias[$oParam->sCodigoBanco]}.\n\n";
        $sMsgErro .= "Verifique cadastro de contas bancárias.";
        throw new BusinessException($sMsgErro);
      }

      $oRetorno->aContasBancarias = db_utils::getCollectionByRecord($rsBuscaContas);

    } catch (Exception $eErro) {

      $oRetorno->message = $eErro->getMessage();
      $oRetorno->status = 2;
    }

    break;
  case "salvarDadosContas":

    if (checkAccountingPeriod($oParam->db83_dataimplantaoconta,$oParam)) {
        $oRetorno->message = checkAccountingPeriod($oParam->db83_dataimplantaoconta,$oParam);
        $oRetorno->status = 2;
        break;
    };
        
    $iAno          = db_getsession('DB_anousu');
    $ultimoEstrut  = strval(lastStructural($oParam->db83_tipoconta));
    $ultimoEstrut  ++;
    $cpservice     = new ContaPlanoService();
    $codEstrutural = $cpservice->searchEstrutural($ultimoEstrut,$iAno);

    if (!$codEstrutural['status']) {
       $estrutValido = $ultimoEstrut ; 
    } else {
       $estrutValido = 1;
       while ($estrutValido == 1) {
          $codEstrutural ++ ; 
          $estrutValido =  searchStutural($codEstrutural,$iAno);
       }
    } 

    $tipodeconta = validatEstructureType($estrutValido);
    $resultado = createBankAccounts($tipodeconta,$oParam,$estrutValido);
    
    $oRetorno->status  = $resultado[0];
    $oRetorno->message = $resultado[1];

  break;
  case "alterarDadosContas":

    try {

      if (checkAccountingPeriod($oParam->db83_dataimplantaoconta,$oParam)) {
        $oRetorno->message = checkAccountingPeriod($oParam->db83_dataimplantaoconta,$oParam);
        $oRetorno->status = 2;
        break;
      };

      $service     = new ContaBancariaService();
   
      $ckeysTables = $service->checkGeneral($oParam->db83_sequencial);
      
      $tablesObject = new stdClass();
      $tablesObject->c60_codcon = $ckeysTables->c60_codcon;
      $tablesObject->c61_codcon = $ckeysTables->c61_codcon;
      $tablesObject->c62_reduz  = $ckeysTables->c62_reduz;
      $tablesObject->k13_conta  = $ckeysTables->k13_conta;
      $tablesObject->c63_codcon = $ckeysTables->c63_codcon;
      $tablesObject->e83_conta  = $ckeysTables->e83_conta;
   
      $result      = $service->updateContaBancaria($oParam,$tablesObject);
    
      if (!$result['status']) {

        $sMsgErro  = "Não foi possível alterar dados da conta bancária {$oParam->db83_reduzido} ";
        throw new BusinessException($sMsgErro);
      }

      $oRetorno->status  = $result['status'];
      $oRetorno->message ="Conta bancária {$oParam->db83_reduzido} alterada com sucesso!";

    } catch (Exception $eErro) {

      $oRetorno->message = $eErro->getMessage();
      $oRetorno->status = 2;
    }

  break;
  case "excluirDadosContas":

    try {

      if (checkAccountingPeriod($oParam->db83_dataimplantaoconta,$oParam)) {
        $oRetorno->message = checkAccountingPeriod($oParam->db83_dataimplantaoconta,$oParam);
        $oRetorno->status = 2;
        break;
      };

      $service     = new ContaBancariaService();
      $ckeysTables = $service->checkGeneral($oParam->db83_sequencial);
    
      $tablesObject = new stdClass();
      $tablesObject->c60_codcon        = $ckeysTables->c60_codcon;
      $tablesObject->c61_codcon        = $ckeysTables->c61_codcon;
      $tablesObject->c62_reduz         = $ckeysTables->c62_reduz;
      $tablesObject->k13_conta         = $ckeysTables->k13_conta;
      $tablesObject->c63_codcon        = $ckeysTables->c63_codcon;
      $tablesObject->c18_codcon        = $ckeysTables->c18_codcon;
      $tablesObject->e83_codtipo       = $ckeysTables->e83_codtipo;
      $tablesObject->c56_contabancaria = $ckeysTables-> c56_contabancaria;
      $tablesObject->db83_sequencial   = $oParam->db83_sequencial;
      
      $result      = $service->deleteContaBancaria($tablesObject);
      
      if (!$result['status']) {
        $sMsgErro  = "Não foi possível excluir a conta bancária {$oParam->db83_reduzido} ";
        throw new BusinessException($sMsgErro);
      }

      $oRetorno->status  = 1;
      $oRetorno->message ="Conta bancária {$oParam->db83_reduzido} excluída com sucesso!";

    } catch (Exception $eErro) {

      $oRetorno->message = $eErro->getMessage();
      $oRetorno->status = 2;
    }
  break;

}

$oRetorno->message = urlencode($oRetorno->message);
if ($iRetorno == 1) {
  echo($oJson->encode($aRetorno));
} else {
  echo($oJson->encode($oRetorno));
}

function createBankAccounts($tipodeconta,$oParam,$estrutValido) 
{ 
  switch ($tipodeconta) {

    case "sintetico":
       
      accountsSynthetic($estrutValido);
     
      $iAno      = db_getsession('DB_anousu');
      $cpservice = new ContaPlanoService();
      $estrutValido ++;
      $codEstrutural = $cpservice->searchEstrutural($estrutValido, $iAno);

      if (!$codEstrutural['status']) {
        $estrutValido = $estrutValido; 
      } else {
         $estrutValido =  searchStutural($codEstrutural,$iAno);
         while (!searchStutural($codEstrutural,$iAno)) {
            $estrutValido =  searchStutural($codEstrutural,$iAno);
            $codEstrutural ++ ;
         }
      }  
      return accountsAnalytics($oParam,$estrutValido);
    break;
    case "analitico":
      return accountsAnalytics($oParam,$estrutValido);
    break;
    case "reiniciarestrutural":
      return restartStructural();
    break;
  }
}

function accountsAnalytics($oParam,$estrutValido)
{
  
  $iMaximoAno             = db_getsession('DB_anousu');
  $oDataImplantacao = date('Y-m-d', strtotime(str_replace('/', '-', $oParam->db83_dataimplantaoconta)));

  //Tabela ContaBancaria
  $service = new ContaBancariaService();
  $result = $service->insertContaBancaria($oParam);

  if ($result['status'] === false) {
    throw new Exception($result['message']);
  }

  $cpservice  = new ContaPlanoService();
  $cprservice = new ContaPlanoReduzService();
  $reduzido   = $cprservice->searchContaPlanoReduz();
  $codContaPlano = $cpservice->searchContaPlano();
  $cpservice->setvalContaPlano($codContaPlano);
  $cprservice->setvalContaPlanoReduz($reduzido);
  
  for ($iMaximoAno ; $iMaximoAno <= getLastYearPlan(); $iMaximoAno++) {

    //Tabela ConPlano 
    $objetoContaPlano = new stdClass();
    $objetoContaPlano->c60_codcon                  = $codContaPlano;
    $objetoContaPlano->c60_anousu                  = $iMaximoAno;
    $objetoContaPlano->c60_estrut                  = $estrutValido;
    $objetoContaPlano->c60_descr                   = $oParam->db83_descricao;
    $objetoContaPlano->c60_finali                  = "";
    $objetoContaPlano->c60_codsis                  = "6";
    $objetoContaPlano->c60_codcla                  = "1";
    $objetoContaPlano->c60_consistemaconta         = "2";
    $objetoContaPlano->c60_identificadorfinanceiro = "F";
    $objetoContaPlano->c60_naturezasaldo           = "1";
    $objetoContaPlano->c60_funcao                  = "";
    $objetoContaPlano->c60_tipolancamento          = "1";
    $objetoContaPlano->c60_subtipolancamento       = "";
    $objetoContaPlano->c60_desdobramneto           = "";
    $objetoContaPlano->c60_nregobrig               = "17";
    $objetoContaPlano->c60_cgmpessoa               = "";
    $objetoContaPlano->c60_naturezadareceita       = "";
    $objetoContaPlano->c60_infcompmsc              = "4";

    $result2 = $cpservice->insertContaPlano($objetoContaPlano);

    if ($result2['status'] === false) {
      throw new Exception($result2['message']);
    }

     /* Tabela ConPlanoContaConta */
    $cpcservice = new ContaPlanoContaService();
    $result3 = $cpcservice->insertContaPlanoConta($oParam,$codContaPlano,$iMaximoAno);
    
    if ($result3['status'] === false) {
      throw new Exception($result3['message']);
    }
   
    /* Tabela ConPlanoContaBancaria */
    $sequencial = $service->searchSequential();
    $objetoContaPlanoContaBancaria = new stdClass();
    $objetoContaPlanoContaBancaria->c56_contabancaria = $sequencial; 
    $objetoContaPlanoContaBancaria->c56_codcon        = $codContaPlano;
    $objetoContaPlanoContaBancaria->c56_anousu        = $iMaximoAno;

    $cpcbservice = new ContaPlanoContaBancariaService();
    $result4 = $cpcbservice->insertContaPlanoContaBancaria($objetoContaPlanoContaBancaria);

    if ($result4['status'] === false) {
      throw new Exception($result4['message']);
    }
    
    /*Tabela ConPlanoContaCorrente*/
    $objetoContaPlanoContaCorrente = new stdClass();
    $objetoContaPlanoContaCorrente->c18_codcon        = $codContaPlano;
    $objetoContaPlanoContaCorrente->c18_anousu        = $iMaximoAno;
    $objetoContaPlanoContaCorrente->c18_contacorrente = "103";

    $cpccservice = new ContaPlanoContaCorrenteService();
    $result5 = $cpccservice->insertContaPlanoContaCorrente($objetoContaPlanoContaCorrente);

    if ($result5['status'] === false) {
      throw new Exception($result5['message']);
    }

    /*Tabela ConPlanoReduz*/
    $objetoContaPlanoReduz = new stdClass();
    $objetoContaPlanoReduz->c61_codcon        = $codContaPlano;
    $objetoContaPlanoReduz->c61_anousu        = $iMaximoAno;
    $objetoContaPlanoReduz->c61_reduz         = $reduzido;
    $objetoContaPlanoReduz->c61_instit        = $oParam->db83_instituicao;
    $objetoContaPlanoReduz->c61_codigo        = $oParam->iCodigoRecurso;
    $objetoContaPlanoReduz->c61_contrapartida = "0";
    $objetoContaPlanoReduz->c61_codtce        = "";
    
    $result6 = $cprservice->insertContaPlanoReduz($objetoContaPlanoReduz);
    if ($result6['status'] === false) {
      throw new Exception($result6['message']);
    }

    /*Tabela ConPlanoExe*/
    $objetoContaPlanoExe = new stdClass();
    $objetoContaPlanoExe->c62_anousu        = $iMaximoAno;
    $objetoContaPlanoExe->c62_reduz         = $reduzido;
    $objetoContaPlanoExe->c62_codrec        = $oParam->iCodigoRecurso;
    $objetoContaPlanoExe->c62_vlrcre        = "0";
    $objetoContaPlanoExe->c62_vlrdeb        = "0";

    $cpexeservice = new ContaPlanoExeService();
    $result9 = $cpexeservice->insertContaPlanoExe($objetoContaPlanoExe);
    if ($result9['status'] === false) {
      throw new Exception($result9['message']);
    }
  }  
  /*Tabela saltes*/
  $objetoSaltes = new stdClass();
  $objetoSaltes->k13_conta         = $reduzido;
  $objetoSaltes->k13_reduz         = $reduzido;
  $objetoSaltes->k13_descr         = substr($oParam->db83_descricao,0,40);
  $objetoSaltes->k13_saldo         = 0;
  $objetoSaltes->k13_ident         = "";
  $objetoSaltes->k13_vlratu        = "0";
  $objetoSaltes->k13_datvlr        = $oDataImplantacao;
  $objetoSaltes->k13_limite        = "";
  $objetoSaltes->k13_dtimplantacao = $oDataImplantacao;
  
  $saltesservice = new SaltesService();
  $result7 = $saltesservice->insertSaltes($objetoSaltes);
  if ($result7['status'] === false) {
    throw new Exception($result7['message']);
  }

  /*Tabela Empagetipo*/
  $objetoEmpagetipo = new stdClass();
  $objetoEmpagetipo->e83_descr             = $oParam->db83_descricao;
  $objetoEmpagetipo->e83_conta             = $reduzido;
  $objetoEmpagetipo->e83_codmod            = "3";
  $objetoEmpagetipo->e83_convenio          = "0";
  $objetoEmpagetipo->e83_sequencia         = "0";
  $objetoEmpagetipo->e83_codigocompromisso = "00";
     
  $empagetiposervice = new EmpagetipoService();
  $result8 = $empagetiposervice->insertEmpagetipo($objetoEmpagetipo);
  if ($result8['status'] === false) {
    throw new Exception($result8['message']);
  }

  return [$result['status'], "Conta bancária {$reduzido} cadastrada com sucesso!"];
}

function accountsSynthetic($estrutValido) 
{
  $iAno = db_getsession('DB_anousu');
  $cpservice = new ContaPlanoService();
  $codContaPlano = $cpservice->searchContaPlano();
  //Tabela ConPlano    
  for ($iAno ; $iAno <= getLastYearPlan(); $iAno++) {

    $objetoContaPlano = new stdClass();

    $objetoContaPlano->c60_codcon                  = $codContaPlano;
    $objetoContaPlano->c60_anousu                  = $iAno;
    $objetoContaPlano->c60_estrut                  = $estrutValido;
    $objetoContaPlano->c60_descr                   = "CONTINUACAO CONTAS BANCARIAS";
    $objetoContaPlano->c60_finali                  = "";
    $objetoContaPlano->c60_codsis                  = "6";
    $objetoContaPlano->c60_codcla                  = "1";
    $objetoContaPlano->c60_consistemaconta         = "2";
    $objetoContaPlano->c60_identificadorfinanceiro = "F";
    $objetoContaPlano->c60_naturezasaldo           = "1";
    $objetoContaPlano->c60_funcao                  = "";
    $objetoContaPlano->c60_tipolancamento          = "1";
    $objetoContaPlano->c60_subtipolancamento       = "";
    $objetoContaPlano->c60_desdobramneto           = "";
    $objetoContaPlano->c60_nregobrig               = "17";
    $objetoContaPlano->c60_cgmpessoa               = "";
    $objetoContaPlano->c60_naturezadareceita       = "";
    $objetoContaPlano->c60_infcompmsc              = "4";

    
    $result2 = $cpservice->insertContaPlano($objetoContaPlano);

    if ($result2['status'] === false) {
      throw new Exception($result2['message']);
    }

  }  

  return [$result2['status'], "Conta Bancária Salva com Sucesso!"];
}

function restartStructural()
{
    return ["Inclusão não permitida, realize a manutenção do estrutural no menu DB:FINANCEIRO > Tesouraria > Procedimentos > Parâmetros > Financeiro "];
}

function getLastYearPlan() 
{

  $oDaoVinculo = db_utils::getDao("conaberturaexe");
  $sCampo = "max(c91_anousudestino) as c60_anousu";
  $sWhere = "c91_instit = ".db_getsession("DB_instit");
  $sSqlMaximoAno = $oDaoVinculo->sql_query_file(null, $sCampo, null, $sWhere);
  $rsMaximoAno   = $oDaoVinculo->sql_record($sSqlMaximoAno);

  if ( pg_num_rows($rsMaximoAno) == 0 ) {
    $oDaoVinculo = db_utils::getDao("conplano");
    $sCampo        = "max(c60_anousu) as c60_anousu";
    $sSqlMaximoAno = $oDaoVinculo->sql_query_file(null, null, $sCampo, null, null);
    $rsMaximoAno   = $oDaoVinculo->sql_record($sSqlMaximoAno);
      
    return db_utils::fieldsMemory($rsMaximoAno, 0)->c60_anousu;

  } else {
    return db_utils::fieldsMemory($rsMaximoAno, 0)->c60_anousu;
  }
    
}

function checkStructural($novoEstrut, $iano) 
{
  $cpservice = new ContaPlanoService();
  $codEstrutural = $cpservice->searchNewEstrutural($novoEstrut,$iano);

  if ($codEstrutural) {
      return true;
  }
  return false;
}

function searchStutural($ultimoEstrut,$iano)
{

      $novoEstrut = $ultimoEstrut;
      if (checkStructural($novoEstrut,$iano)) {
        return true;
      }
      return $novoEstrut;
    
}

function validatEstructureType($ultimoEstrut)
{
    $final = substr($ultimoEstrut, -2);
    $finalThree = substr($ultimoEstrut, -3);
    
    if ($final === '00') {
        return 'sintetico';
    } elseif ($finalThree === '999') {
        return 'reiniciarestrutural';
    } else {
        return 'analitico';
    }
    
}
function lastStructural($db83_tipoconta)
{

  $clcaiparametro             = new cl_caiparametro;
  $k29_instit                 = db_getsession("DB_instit");
  $sSql   = $clcaiparametro->sql_query($k29_instit);
  $result = $clcaiparametro->sql_record($sSql);

  if($result != false && $clcaiparametro->numrows > 0 ) {
    $rsResult = db_query($sSql);
    $dadosEstrut = db_utils::fieldsMemory($rsResult, 0);
  }

  if ($db83_tipoconta == 1) {
      return $dadosEstrut->k29_estrutcontacorrente;
  }
  return $dadosEstrut->k29_estrutcontaaplicacao;
}

function checkAccountingPeriod($db83_dataimplantaoconta,$oParam)
{

    $oDaoConDataConf             = new cl_condataconf();
    $oDataImplantacao = date('Y-m-d', strtotime(str_replace('/', '-',$db83_dataimplantaoconta)));
    $sWhere = "    c99_data   >= '{$oDataImplantacao}'::date
    and c99_instit  = " . db_getsession('DB_instit');
    $sSqlValidaFechamentoContabilidade = $oDaoConDataConf->sql_query(null, null, '*', null, $sWhere);
    $rsValidaFechamentoContabilidade   = $oDaoConDataConf->sql_record($sSqlValidaFechamentoContabilidade);

    if ($oDaoConDataConf->numrows > 0) {

        if ($oParam->exec == 'alterarDadosContas') {
          return false;
        }

        $sMsg  = "Operação não permitida!\n";
        $sMsg .= "A data de encerramento da contabilidade é posterior a data de implantação da conta.\n";
        return $sMsg;

    } 
    return false;
  }
?>
