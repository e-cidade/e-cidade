<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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
//ini_set('display_errors','on');
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_licacontrolenexospncp_classe.php");
require_once("classes/db_licanexopncp_classe.php");

define("URL_MENSAGEM_LIC1ANEXOSPNCP", "patrimonial.licitacao.lic1_anexospncp.");
const PATH_ANEXO_LICITACAO = 'model/licitacao/PNCP/anexoslicitacao/';

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';
try {

  /*
   * In�cio Transa��o
   */
  db_inicio_transacao();

  switch ($oParam->exec) {


    case "salvarDocumento":

      $oLicitacaoAnexo = new LicitacaoAnexo($oParam->iCodigoLicitacao);
      $oLicitacaoAnexo->setLicitacao($oParam->iCodigoLicitacao);
      $oLicitacaoAnexo->salvar();

      $oLicitacaoDocumento = new LicitacaoDocumento();
      $oLicitacaoDocumento->setProcessoProtocolo($oLicitacaoAnexo);
      $oLicitacaoDocumento->setTipoanexo($oParam->iCodigoDocumento);

      if (!empty($oParam->sCaminhoArquivo)) {
        $oLicitacaoDocumento->setCaminhoArquivo($oParam->sCaminhoArquivo);
        $oLicitacaoDocumento->setNomeArquivo($oParam->sTitulo);
      }

      $oRetorno->sMensagem = urlencode($oLicitacaoDocumento->salvar());
      break;

    case "carregarDocumentos":

      $oProcessoProtocolo    = new LicitacaoAnexo($oParam->iCodigoProcesso);
      $aDocumentosVinculados = $oProcessoProtocolo->getDocumentos();
      $aDocumentosRetorno    = array();
      foreach ($aDocumentosVinculados as $oProcessoLicitacao) {

        $oStdDocumento = new stdClass();
        $oStdDocumento->iCodigoDocumento    = $oProcessoLicitacao->getCodigo();
        $oStdDocumento->sDescricaoDocumento = urlencode(utf8_decode($oProcessoLicitacao->getDescricaoTipo()));

        $aDocumentosRetorno[] = $oStdDocumento;
      }
      $oRetorno->aDocumentosVinculados = $aDocumentosRetorno;

      break;

    case "download":

      $oProcessoDocumento                = new LicitacaoDocumento($oParam->iCodigoDocumento);
      $oRetorno->sCaminhoDownloadArquivo = $oProcessoDocumento->download();
      $oRetorno->sTituloArquivo          = urlencode($oProcessoDocumento->getNomeDocumento());

      break;

    case "ziparAnexos":
      $nomeDoZip = '/tmp/anexos-' . time() . '.zip';
      $zip = new ZipArchive();
      if ($zip->open($nomeDoZip, ZipArchive::CREATE) === true) {
        foreach ($oParam->arquivos as $oArquivo) {
          $zip->addFile($oArquivo->sCaminhoDownloadArquivo, $oArquivo->sTituloArquivo);
        }
        $zip->close();
      }

      $oRetorno->nomeDoZip = $nomeDoZip;
      break;

    case "excluir":

      $clliccontroleanexopncp = new cl_liccontroleanexopncp();
      $cllicanexopncp = new cl_licanexopncp;
      $cllicanexopncpdocumento = new cl_licanexopncpdocumento;


      $rsAnexosPNCP = $clliccontroleanexopncp->sql_record($clliccontroleanexopncp->sql_query(null,"*",null, "l218_sequencialarquivo = $oParam->iCodigoDocumento"));

        if(pg_num_rows($rsAnexosPNCP)){
            $oRetorno->sMensagem = urlencode('Anexo j� enviado ao PNCP, para exclu�-lo � necess�rio que seja primeiro EXCLU�DO no PNCP.');
        }else {
            $oProcessoDocumento = new LicitacaoDocumento($oParam->iCodigoDocumento);
            $oProcessoDocumento->excluir();

            //verifica se ainda existe anexos no servidor
            $rsAnexos = $cllicanexopncp->sql_record($cllicanexopncp->sql_anexos_licitacao_exclusao($oParam->iCodigoProcesso));
            if(!pg_num_rows($rsAnexos)){
                $cllicanexopncp->excluir(null,"l215_liclicita = $oParam->iCodigoProcesso");
            }

            $oRetorno->sMensagem = urlencode('Exclus�o realizada com sucesso!');
        }
      break;

    case "alterardocumento":
      $oProcessoDocumento = new LicitacaoDocumento();
      $oProcessoDocumento->alterartipo($oParam->iCodigoDocumento, $oParam->itipoanexo);
      $oRetorno->sMensagm = urlencode('Altera��o realizada com sucesso!');
      break;

    case "excluirDocumento":

        foreach ($oParam->aDocumentosExclusao as $documento) {

            $clliccontroleanexopncp = new cl_liccontroleanexopncp();

            $rsAnexosPNCP = $clliccontroleanexopncp->sql_record($clliccontroleanexopncp->sql_query(null,"*",null, "l218_sequencialarquivo = $documento"));

            if(pg_num_rows($rsAnexosPNCP)){
                $oRetorno->sMensagem = urlencode('Anexo j� enviado ao PNCP, para exclu�-lo � necess�rio que seja primeiro EXCLU�DO no PNCP.');
            }else{
                $aDocumentosNaoExcluidos = array();
                foreach ($oParam->aDocumentosExclusao as $iCodigoDocumento) {
                    $oProcessoDocumento = new LicitacaoDocumento($iCodigoDocumento);
                    $oProcessoDocumento->excluir();
                }
                $oRetorno->sMensagem = urlencode('Exclus�o realizada com sucesso!');
            }
        }

      break;

    case "buscardocumento":
      $oProcessoDocumento = new LicitacaoDocumento($oParam->iCodigoDocumento);
      $oRetorno->idtipo = $oProcessoDocumento->buscartipo();
      break;

    case "verifica":
      $oProcessoDocumento = new LicitacaoDocumento();
      $oRetorno->idtipo = $oProcessoDocumento->verificavinculo($oParam->iCodigoDocumento);
      break;

    case "apagarZip":
      if (file_exists($oParam->nomeDoZip) && unlink($oParam->nomeDoZip)) {
        $oRetorno->zipApagado = 1;
      } else {
        $oRetorno->zipApagado = 0;
      }
      break;
  }

  /**
   * Fim Transa��o
   */
  db_fim_transacao(false);
} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);
