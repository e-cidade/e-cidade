<?php
use App\Helpers\CacheHelper;
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
try {

  $oJson               = new services_json();
  $oParametros         = $oJson->decode(str_replace("\\", "", $_POST["json"]));
  $db_id_usuario       = db_getsession('DB_id_usuario');
  $db_login            = db_getsession('DB_login');

  $oRetorno            = new stdClass();
  $oRetorno->iStatus   = 1;
  $oRetorno->sMensagem = '';

  switch ($oParametros->sExec) {

    case 'salvar':

      $oTraceLog = TraceLog::getInstance();


      if ($oParametros->lActive) {
        CacheHelper::remove('TracelogObject_'.$db_id_usuario);

        $sMessage = "[INFO | ". date("d/m/Y - H:i:s") ."] Enabled Trace Log - db_id_usuario: $db_id_usuario. db_login: $db_login \n";
        $oTraceLog->write($sMessage);
      }

      $oTraceLog->setProperty('lActive', $oParametros->lActive);
      $oTraceLog->setProperty('lShowAccount', $oParametros->lShowAccount);
      $oTraceLog->setProperty('lShowSourceInfo', $oParametros->lShowSourceInfo);
      $oTraceLog->setProperty('lShowFunctionName', $oParametros->lShowFunctionName);
      $oTraceLog->setProperty('lShowTime', $oParametros->lShowTime);
      $oTraceLog->setProperty('lShowBackTrace', $oParametros->lShowBackTrace);


      if (!$oParametros->lShowAccount) {
        db_destroysession("DB_traceLogAcount");
      }

      if (!$oParametros->lActive) {
        $sMessage = "[INFO | ". date("d/m/Y - H:i:s") ."] Disabled Trace Log - db_id_usuario: $db_id_usuario. db_login: $db_login \n";
        $oTraceLog->write($sMessage);

        CacheHelper::remove('TracelogObject_'.$db_id_usuario);
        //  db_destroysession("DB_traceLogAcount");
      }

      break;

    case 'testar':

      db_query("select 'Teste 1 de Tracelog';");
      db_query("select 'Teste 2 de Tracelog';");
      db_query("select 'Teste 3 de Tracelog';");
      break;

    case 'retornarStaus':

      $oTraceLog                              = TraceLog::getInstance();
      $oRetorno->oTracelog                    = new stdClass();
      $oRetorno->oTracelog->lActive           = $oTraceLog->isActive();
      $oRetorno->oTracelog->lShowAccount      = $oTraceLog->isDisplayed('Account');
      $oRetorno->oTracelog->lShowSourceInfo   = $oTraceLog->isDisplayed('SourceInfo');
      $oRetorno->oTracelog->lShowFunctionName = $oTraceLog->isDisplayed('FunctionName');
      $oRetorno->oTracelog->lShowTime         = $oTraceLog->isDisplayed('Time');
      $oRetorno->oTracelog->lShowBackTrace    = $oTraceLog->isDisplayed('BackTrace');
      $oRetorno->oTracelog->sFilePath         = '';
      $sArquivo                               = $oTraceLog->getFilePath();
      if (file_exists($sArquivo)) {
        $oRetorno->oTracelog->sFilePath         = $sArquivo;
      }

      break;

    case "lerArquivo":

      if (!isset($_SESSION["DB_ultima_linha_trace_log"])) {
        $_SESSION["DB_ultima_linha_trace_log"] = 0;
      }

      $hArquivoAberto = fopen($oParametros->sArquivo, "r");
      $aDadosRetorno  = array();

      /**
       * Definição das variáveis de controle
       */
      $iLinhaAtual    = 0;
      $lEmTransacao  = false;
      $lFimTransacao = false;


      while (!feof($hArquivoAberto)) {

        /**
         * Pego a string com tamanho de 30000 caracteres.
         */
        $sInstrucaoSQL = fgets($hArquivoAberto, 30000);

        /**
         * Leio somente as linhas que ainda n�o foram lidas.
         */
        if ($_SESSION["DB_ultima_linha_trace_log"] > $iLinhaAtual) {

          $iLinhaAtual++;
          continue;
        }

        if (trim($sInstrucaoSQL) == "") {
          continue;
        }

        /*
       * Verificamos se ocorreu erro para executar a query
       */
        $lLinhaDeErro = false;
        if (strpos($sInstrucaoSQL, "ERRO") > 0) {
          $lLinhaDeErro = true;
        }

        /*
       * Verificamos se está sendo iniciando uma transação com o banco de dados
       */
        if (strpos(strtolower($sInstrucaoSQL), "begin") > 0) {
          $lEmTransacao = true;
        }

        $oStdDado               = new stdClass();
        $oStdDado->lErro        = $lLinhaDeErro;
        $oStdDado->sSql         = urlencode($sInstrucaoSQL);
        $oStdDado->iLinha       = $iLinhaAtual;
        $oStdDado->lEmTransacao = $lEmTransacao;

        /*
       * Caso esteja concluido a transação com o banco, é alterado o parãmetro
       */
        if (strpos(strtolower($sInstrucaoSQL), "rollback") > 0 || strpos(strtolower($sInstrucaoSQL), "commit") > 0) {
          $lEmTransacao = false;
        }

        $aDadosRetorno[] = $oStdDado;
        $iLinhaAtual++;
      }

      fclose($hArquivoAberto);

      /*
     * Guardo a última linha lida pelo programa
     */
      $_SESSION["DB_ultima_linha_trace_log"] = $iLinhaAtual;

      $oRetorno->aInstrucoesSQL = $aDadosRetorno;


      break;

    case "limparUltimaLinhaLidaTraceLog":
      unset($_SESSION["DB_ultima_linha_trace_log"]);
      break;

    default:
      break;
  }
} catch (Exception $eErro) {

  $oRetorno            = new stdClass();
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
