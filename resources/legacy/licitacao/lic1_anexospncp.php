<?php
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/renderComponents/index.php");

$iOpcaoLicitacao = 1;
$lExibirMenus   = true;
$cltipoanexo = new cl_tipoanexo;
$cllicanexopncp = new cl_licanexopncp;

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"], $result);

/**
 * Codigo do precesso informado por GET
 * - Pesquisa numero e ano do Licitacao
 */


$oRotulo  = new rotulocampo;
$oDaoLicanexopncpdocumento = db_utils::getDao('licanexopncpdocumento');
$oDaoLicanexopncpdocumento->rotulo->label();

$oRotulo->label("l20_codigo");
$oRotulo->label("l20_objeto");
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
  db_app::load("estilos.css, grid.style.css");
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
  ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <div class="container" style="width:650px;">

    <fieldset>
      <legend>Anexos PNCP</legend>
      <form name="form" id="form" method="post" action="" enctype="multipart/form-data">


        <?php db_input("namefile", 30, 0, true, "hidden", 1); ?>
        <?php db_input("iddocumento", 30, 0, true, "hidden", 1); ?>

        <table class="form-container">

          <tr>
            <td nowrap title="<?php echo $Tl216_licanexospncp; ?>">
              <?php db_ancora("Licita��o: ", "js_pesquisarLicitacao(true);", $iOpcaoLicitacao); ?>
            </td>
            <td>
              <?php
              db_input('l20_codigo', 12, $Il20_codigo, true, 'text', $iOpcaoLicitacao, " onChange='js_pesquisarLicitacao(false);'");
              db_input('l20_objeto', 60, $Il20_objeto, true, 'text', 3, "");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tl216_documento; ?>">
              <?php echo $Ll216_documento; ?>
            </td>
            <td>
              <?php db_input("uploadfile", 53, 0, true, "file", 1); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?= @$Tl213_sequencial ?>">
              <b>
                <?
                db_ancora("Tipo de Anexo :", "js_pesquisal20_codtipocom(true);", 3);
                ?>
              </b>
            </td>
            <td>
              <?


              $tipo = array();
              $tipo[0] = "Selecione";
              $result_tipo = $cltipoanexo->sql_record($cltipoanexo->sql_query(null, "*", "l213_sequencial", "l213_sequencial in (1,2,3,4,5,6,7,8,9,10,16,18,19,20)"));


              for ($iIndiceTipo = 0; $iIndiceTipo < $cltipoanexo->numrows; $iIndiceTipo++) {

                $oTipo = db_utils::fieldsMemory($result_tipo, $iIndiceTipo);

                  $tipo[$oTipo->l213_sequencial] = urldecode(utf8_decode($oTipo->l213_descricao));
              }

              if ($cltipoanexo->numrows == 0) {
                db_msgbox("Nenhuma Tipo de anexo cadastrado!!");
                $result_tipo = "";
                $db_opcao = 3;
                $db_botao = false;
                db_input("l213_sequencial", 10, "", true, "text");
                db_input("l213_sequencial", 40, "", true, "text");
              } else {
                db_select("l213_sequencial", $tipo, true, $db_opcao, "");
              }
              ?>
            </td>
          </tr>

        </table>
      </form>
    </fieldset>

    <div style="display: flex; justify-content: center; align-items: center; align-content: center;">

      <?php $component->render('buttons/solid', [
        'type' => 'button',
        'id' => 'btnSalvar',
        'designButton' => 'success',
        'size' => 'sm',
        'onclick' => 'js_salvar()',
        'message' => 'Salvar'
      ]); ?>

      <?php $component->render('buttons/solid', [
        'type' => 'button',
        'id' => 'btnAlterar',
        'designButton' => 'success',
        'size' => 'sm',
        'onclick' => 'js_alterar()',
        'message' => 'Alterar'
      ]); ?>

    </div>

    <fieldset style="margin-top:15px;">
      <legend>Documentos Anexados</legend>
      <div id="ctnDbGridDocumentos"></div>
    </fieldset>

    <div style="display: flex; justify-content: center; align-items: center; align-content: center;">

      <?php $component->render('buttons/solid', [
        'type' => 'button',
        'id' => 'btnEnviarPNCP',
        'designButton' => 'success',
        'size' => 'sm',
        'onclick' => 'js_enviarDocumentoPNCP()',
        'message' => 'Envia documento para o PNCP'
      ]); ?>

      <?php $component->render('buttons/solid', [
        'type' => 'button',
        'id' => 'btnExcluirPNCP',
        'designButton' => 'danger',
        'size' => 'sm',
        'onclick' => 'js_excluirDocumentoPNCPClickValidation()',
        'message' => 'Excluir documento no PNCP'
      ]); ?>

      <?php $component->render('buttons/solid', [
        'type' => 'button',
        'id' => 'btnExcluir',
        'designButton' => 'danger',
        'size' => 'sm',
        'onclick' => 'js_excluirSelecionados()',
        'message' => 'Excluir Selecionados'
      ]); ?>

      <?php $component->render('buttons/solid', [
        'type' => 'button',
        'id' => 'btnDownloadAnexos',
        'designButton' => 'secondary',
        'size' => 'sm',
        'onclick' => 'js_downloadAnexos()',
        'message' => 'Download'
      ]); ?>
      
    </div>

  </div>

  <?php if ($lExibirMenus) : ?>
    <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
  <?php endif; ?>

  <?php $component->render('modais/simpleModal/startModal', [
      'title' => 'Justificativa para o PNCP',
      'id' => 'justificativaModal',
      'size' => 'lg'
  ], true); ?>
    <?php db_textarea('justificativapncp', 10, 48, false, true, 'text', $db_opcao, "", "", "justificativapncp", "255"); ?>
    
    <div style="width: 100%; display: flex; justify-content: center;">
        <?php $component->render('buttons/solid', [
            'designButton' => 'success',
            'type' => 'submit',
            'message' => 'Salvar justificativa PNCP',
            'onclick' => "js_excluirDocumentoPNCP()",
            'size' => 'md'
        ]); ?>
    </div>
  <?php $component->render('modais/simpleModal/endModal', [], true); ?>

  <div id="teste" style="display:none;"></div>
</body>

</html>

<style>
  #justificativapncp {
    width: 100%;
    margin-bottom: 7px;
    font-size: 1rem;
  }
</style>

<script type="text/javascript">

  loadComponents([
    'buttonsSolid',
    'simpleModal',
  ]);

  document.getElementById("btnAlterar").style.display = "none";

  /**
   * Pesquisa Licitacao do protocolo e depois os documentos anexados
   */
  if (!empty($('l20_codigo').value)) {
    js_pesquisarLicitacao(false);
  }

  /**
   * Mensagens do programa
   * @type constant
   */
  const MENSAGENS = 'patrimonial.licitacao.lic1_anexospncp.';

  var sUrlRpc = 'lic1_anexospncp.RPC.php';

  var oGridDocumentos = new DBGrid('gridDocumentos');

  oGridDocumentos.nameInstance = "oGridDocumentos";
  oGridDocumentos.setCheckbox(0);
  oGridDocumentos.setCellAlign(new Array("center", "center", "center", "center"));
  oGridDocumentos.setCellWidth(["0%", "10%", "60%", "30%"]);
  oGridDocumentos.setHeader(new Array("Seq", "C�digo", "Tipo", "A��o"));
  oGridDocumentos.aHeaders[1].lDisplayed = false;
  oGridDocumentos.allowSelectColumns(true);
  oGridDocumentos.show($('ctnDbGridDocumentos'));


  /**
   * Buscar documentos do processo
   * @return boolean
   */
  function js_buscarDocumentos() {

    var iCodigoProcesso = $('l20_codigo').value;

    if (empty(iCodigoProcesso)) {
      return false;
    }

    js_divCarregando('mensagem_buscando_documentos', 'msgbox');

    var oParametros = new Object();

    oParametros.exec = 'carregarDocumentos';
    oParametros.iCodigoProcesso = iCodigoProcesso;

    var oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          var oRetorno = eval('(' + oAjax.responseText + ")");

          var sMensagem = oRetorno.sMensagem.urlDecode();

          if (oRetorno.iStatus > 1) {

            alert(sMensagem);
            return false;
          }

          oGridDocumentos.clearAll(true);
          var iDocumentos = oRetorno.aDocumentosVinculados.length;

          for (var iIndice = 0; iIndice < iDocumentos; iIndice++) {

            var oDocumento = oRetorno.aDocumentosVinculados[iIndice];
            var sDescricaoDocumento = oDocumento.sDescricaoDocumento.urlDecode();


            var sHTMLBotoes = '<input type="button" value="Alterar" onClick="js_alterarDocumento(' + oDocumento.iCodigoDocumento + ', \'' + sDescricaoDocumento + '\');" />  ';
            sHTMLBotoes += '<input type="button" value="Excluir" onClick="js_excluirDocumento(' + oDocumento.iCodigoDocumento + ');" />  ';

            $bBloquea = false;



            var aLinha = [oDocumento.iCodigoDocumento, iIndice + 1, sDescricaoDocumento, sHTMLBotoes];
            oGridDocumentos.addRow(aLinha, false, $bBloquea);
          }

          oGridDocumentos.renderRows();
        }
      }
    );

  }

  /**
   * Altera descricao de um documento
   * @param integer iCodigoDocumento
   * @param string sDescricaoDocumento
   * @return void
   */
  function js_alterarDocumento(iCodigoDocumento) {


    if (empty(iCodigoDocumento)) {

      alert('Codigo do documento vazio.');
      return false;
    }

    js_divCarregando('Aguarde... Buscando documento.', 'msgbox');

    var oParametros = new Object();

    oParametros.exec = 'buscardocumento';
    oParametros.iCodigoDocumento = iCodigoDocumento;

    var oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          var oRetorno = eval('(' + oAjax.responseText + ")");
          var sMensagem = oRetorno.sMensagem.urlDecode();

          if (oRetorno.iStatus > 1) {

            alert(sMensagem);
            return false;
          }

          $('l213_sequencial').value = oRetorno.idtipo;
          $('namefile').value = '';
          $('uploadfile').value = '';
          $('iddocumento').value = iCodigoDocumento;
          $('uploadfile').disabled = true;
          $("btnSalvar").style.display = "none";
          $("btnAlterar").style.display = "";


        }
      });


  }

  /**
   * Altera descricao de um documento
   * @param integer iCodigoDocumento
   * @param string sDescricaoDocumento
   * @return void
   */
  function js_alterar() {

    var iCodigoDocumento = $('iddocumento').value;
    var itipoanexo = $('l213_sequencial').value;
    if (empty(iCodigoDocumento)) {

      alert('Codigo do documento vazio.');
      return false;
    }

    if (itipoanexo == 0) {

      alert('Selecione um tipo de anexo.');
      return false;
    }

    js_divCarregando('Aguarde... Alterando documento.', 'msgbox');

    var oParametros = new Object();

    oParametros.exec = 'alterardocumento';
    oParametros.iCodigoDocumento = iCodigoDocumento;
    oParametros.itipoanexo = itipoanexo;

    var oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          var oRetorno = eval('(' + oAjax.responseText + ")");
          var sMensagem = oRetorno.sMensagem.urlDecode();

          if (oRetorno.iStatus > 1) {

            alert(sMensagem);
            return false;
          }

          $('l213_sequencial').value = 0;
          $('namefile').value = '';
          $('uploadfile').value = '';
          $('iddocumento').value = '';
          $('uploadfile').disabled = false;
          $("btnSalvar").style.display = "";
          $("btnAlterar").style.display = "none";
          js_buscarDocumentos();
          alert(sMensagem);
        }
      });


  }



  /**
   * Download de um documento
   * - busca arquivo do banco e salva no tmp
   * - exibe janela com link para download
   * @param  integer iCodigoDocumento
   * @return void
   */
  function js_downloadDocumento(iCodigoDocumento) {

    js_divCarregando('mensagem_carregando_documento', 'msgbox');

    var oParametros = new Object();

    oParametros.exec = 'download';
    oParametros.iCodigoDocumento = iCodigoDocumento;

    var oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          var oRetorno = eval('(' + oAjax.responseText + ")");
          var sMensagem = oRetorno.sMensagem.urlDecode();

          if (oRetorno.iStatus > 1) {

            alert(sMensagem);
            return false;
          }

          var sCaminhoDownloadArquivo = oRetorno.sCaminhoDownloadArquivo.urlDecode();
          var sTituloArquivo = oRetorno.sTituloArquivo.urlDecode();

          window.open("db_download.php?arquivo=" + sCaminhoDownloadArquivo);
        }
      });

  }


  /**
   * Exclui documentos selecionados
   * @return boolean
   */
  function js_excluirDocumento(iCodigoDocumento) {
    js_divCarregando('Excluindo documento...', 'msgbox');

    var oParametros = new Object();
    const iCodigoProcesso = $('l20_codigo').value

    oParametros.exec = 'excluir';
    oParametros.iCodigoDocumento = iCodigoDocumento;
    oParametros.iCodigoProcesso = iCodigoProcesso;

    var oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          var oRetorno = eval('(' + oAjax.responseText + ")");
          var sMensagem = oRetorno.sMensagem.urlDecode();

          if (oRetorno.iStatus > 1) {

            alert(sMensagem);
            return false;
          }

          alert(sMensagem);
          js_buscarDocumentos();
        }
      });

  }

  /**
   * Realiza o download de todos os anexos ou apenas selecionados
   * @return boolean
   */
  const js_downloadAnexos = () => {


    const iCodigoProcesso = $('l20_codigo').value

    if (empty(iCodigoProcesso)) {
      return false
    }

    const documentosSelecionados = oGridDocumentos.getSelection("object")
    if (documentosSelecionados.length == 0) {
      alert('Selecione pelo menos arquivo para download')
      return false
    }
    js_divCarregando('Aguarde... Organizando documentos para o download', 'msgbox')
    let codigosDosDocumentos = []

    for (documento of documentosSelecionados) {
      const codigoDoDocumento = documento.aCells[0].getValue()
      codigosDosDocumentos.push(codigoDoDocumento)
    }

    if (codigosDosDocumentos.length == '1') {
      js_downloadDocumento(codigosDosDocumentos[0])
      js_removeObj("msgbox");
      return false
    }

    let documentos = []
    if (documentosSelecionados.length === 0) {
      codigosDosDocumentos = js_documentosDeUmProcesso(iCodigoProcesso)
    }

    const urlDosArquivos = []

    codigosDosDocumentos.map(codigoDoDocumento => {
      const oCodigoDocumento = new Object()

      oCodigoDocumento.exec = 'download'
      oCodigoDocumento.iCodigoDocumento = codigoDoDocumento

      urlDosArquivos.push(js_arquivos(oCodigoDocumento))
    })

    js_ziparAnexos(urlDosArquivos, nomeDoZip => {
      js_removeObj("msgbox")
      window.open(`db_download.php?arquivo=${nomeDoZip}`)

      setTimeout(() => {
        js_apagarZip(nomeDoZip)
      }, 3000)
    });

  }


  const js_arquivos = oCodigoDocumento => {
    var oRetorno

    var oAjax2 = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oCodigoDocumento),
        method: 'post',
        asynchronous: false,

        /**
         * Retorno do RPC
         */
        onComplete: oAjax2 => {
          oRetorno = eval('(' + oAjax2.responseText + ")")
          var sMensagem = oRetorno.sMensagem.urlDecode()

          if (oRetorno.iStatus > 1) {

            alert(sMensagem)
            return false
          }
        }
      })

    return oRetorno;

  }

  const js_documentosDeUmProcesso = iCodigoProcesso => {


    if (empty(iCodigoProcesso)) {
      return false
    }

    const oParametros = new Object()

    oParametros.exec = 'carregarDocumentos'
    oParametros.iCodigoProcesso = iCodigoProcesso

    const codigosDeDocumentos = []

    const oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,
        onComplete: oAjax => {
          const documentos = JSON.parse(oAjax.responseText).aDocumentosVinculados
          documentos.map(documento => {
            codigosDeDocumentos.push(documento.iCodigoDocumento)
          })
          const sMensagem = oRetorno.sMensagem.urlDecode()

          if (oRetorno.iStatus > 1) {
            alert(sMensagem)
            return false
          }
        }
      }
    )
    return codigosDeDocumentos

  }

  const js_ziparAnexos = (arquivos, callback) => {

    var oParametros = new Object()
    oParametros.exec = 'ziparAnexos'

    arquivos.map(documento => {
      novoTituloDeArquivo = documento.sCaminhoDownloadArquivo.replace('/tmp/', '')
      documento.sTituloArquivo = novoTituloDeArquivo
    })

    oParametros.arquivos = arquivos

    let oRetorno = null
    var oAjax = new Ajax.Request(
      sUrlRpc, {
        method: 'post',
        parameters: `json=${JSON.stringify(oParametros)}`,
        onComplete: oAjax => {

          oRetorno = eval('(' + oAjax.responseText + ')')
          var sMensagem = oRetorno.sMensagem.urlDecode()

          if (oRetorno.iStatus > 1) {

            alert(sMensagem)
            return false
          }

          return callback(oRetorno.nomeDoZip)

        }
      })

  }

  const js_apagarZip = nomeDoZip => {
    const oParametros = new Object()
    oParametros.exec = 'apagarZip'
    oParametros.nomeDoZip = nomeDoZip
    const oAjax = new Ajax.Request(
      sUrlRpc, {
        method: 'post',
        parameters: `json=${JSON.stringify(oParametros)}`,
        onComplete: oAjax => {

          oRetorno = eval('(' + oAjax.responseText + ')')
          var sMensagem = oRetorno.sMensagem.urlDecode()

          if (oRetorno.iStatus > 1) {

            alert(sMensagem)
            return false
          }
        }
      })
  }

  /**
   * Pesquisar Licitacao
   *
   * @param boolean lMostra
   * @return boolean
   */
  function js_pesquisarLicitacao(lMostra) {

    var sArquivo = 'func_liclicita.php?situacao=0&pncp=1&lei=1&funcao_js=parent.';

    if (lMostra) {
      sArquivo += 'js_mostraLicitacao|l20_codigo|l20_objeto';
    } else {

      var iNumeroLicitacao = $('l20_codigo').value;

      if (empty(iNumeroLicitacao)) {
        return false;
      }

      sArquivo += 'js_mostraLicitacaoHidden&pesquisa_chave=' + iNumeroLicitacao + '&sCampoRetorno=l20_codigo';
    }

    js_OpenJanelaIframe('', 'db_iframe_proc', sArquivo, 'Pesquisa de Licita��o', lMostra);
  }

  /**
   * Retorno da js_pesquisarLicitacao apor clicar em uma Licitacao
   * @param  integer iCodigoLicitacao
   * @param  integer iNumeroLicitacao
   * @param  string descricao
   * @return void
   */
  function js_mostraLicitacao(iCodigoLicitacao, descricao) {

    $('l20_codigo').value = iCodigoLicitacao;
    $('l20_objeto').value = descricao;
    $('uploadfile').value = '';
    $('uploadfile').disabled = false;
    $('iddocumento').value = '';
    $('namefile').value = '';
    db_iframe_proc.hide();

    js_buscarDocumentos();

  }

  function js_excluirSelecionados() {

    var documentosSelecionados = oGridDocumentos.getSelection("object");
    var iSelecionados = documentosSelecionados.length;
    var iCodigoProcesso = $('l20_codigo').value;
    var aDocumentos = [];

    if (iSelecionados == 0) {

      alert('Nenhum docuento selecionado.');
      return false;
    }



    if (empty(iCodigoProcesso)) {

      alert('Licita��o n�o informada.');
      return false;
    }

    for (var iIndice = 0; iIndice < iSelecionados; iIndice++) {

      var iDocumento = documentosSelecionados[iIndice].aCells[0].getValue();
      aDocumentos.push(iDocumento);
    }

    js_divCarregando('Aguarde... Excluindo documentos!', 'msgbox');

    var oParametros = new Object();

    oParametros.exec = 'excluirDocumento';
    oParametros.iCodigoProcesso = iCodigoProcesso;
    oParametros.aDocumentosExclusao = aDocumentos;

    var oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          var oRetorno = eval('(' + oAjax.responseText + ")");
          var sMensagem = oRetorno.sMensagem.urlDecode();

          if (oRetorno.iStatus > 1) {

            alert(sMensagem);
            return false;
          }

          alert(sMensagem);
          js_buscarDocumentos();
        }
      });

  }

  /**
   * Retorno da pesquisa js_pesquisarLicitacao apos mudar o campo l20_codigo
   * @param  integer iCodigoLicitacao
   * @param  string descricao
   * @param  boolean lErro
   * @return void
   */
  function js_mostraLicitacaoHidden(descricao, lErro) {

    /**
     * Nao encontrou Licitacao
     */
    if (lErro) {

      $('l20_codigo').value = '';
      $('uploadfile').value = '';
      $('iddocumento').value = '';
      $('namefile').value = '';
      $('uploadfile').disabled = false;
      oGridDocumentos.clearAll(true);
    }

    $('l20_objeto').value = descricao;
    js_buscarDocumentos();
  }

  /**
   * Cria um listener para subir a imagem, e criar um preview da mesma
   */
  $("uploadfile").onchange = function() {

    startLoading();
    var iFrame = document.createElement("iframe");
    iFrame.src = 'func_uploadfilelicitacaopncp.php?clone=form';
    iFrame.id = 'uploadIframe';
    $('teste').appendChild(iFrame);
  }

  function startLoading() {
    js_divCarregando('Aguarde... Enviando documento.', 'msgbox');
  }

  function endLoading() {
    js_removeObj('msgbox');
  }

  function js_salvar() {

    var iCodigoLicitacao = $('l20_codigo').value;



    if (empty(iCodigoLicitacao)) {

      alert('Licita��o n�o incluida');
      return false;
    }

    var iCodigoDocumento = $('l213_sequencial').value;

    if (iCodigoDocumento == 0) {
      alert('Selecione um tipo de anexo!');
      return false;

    }

    var sCaminhoArquivo = $('namefile').value;


    if (sCaminhoArquivo == '') {

      alert('Arquivo anexo n�o informado!');
      return false;
    }

    startLoading()

    var oParametros = new Object();

    oParametros.exec = 'salvarDocumento';
    oParametros.iCodigoDocumento = iCodigoDocumento;
    oParametros.iCodigoLicitacao = iCodigoLicitacao;
    oParametros.sCaminhoArquivo = sCaminhoArquivo;
    oParametros.sTitulo = $("uploadfile").files[0].name;

    var oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,
        onComplete: function(oAjax) {

          endLoading();
          var oRetorno = eval('(' + oAjax.responseText + ")");
          var sMensagem = oRetorno.sMensagem.urlDecode();

          if (oRetorno.iStatus > 1) {

            alert(sMensagem);
            return false;
          }

          $('l213_sequencial').value = 0;
          $('uploadfile').value = '';
          $('namefile').value = '';
          $('uploadfile').disabled = false;
          js_buscarDocumentos();
          alert(sMensagem);
        }
      });
  }

  function js_enviarDocumentoPNCP() {

    const documentosSelecionados = oGridDocumentos.getSelection("object")
    var iSelecionados = documentosSelecionados.length;
    var iCodigoProcesso = $('l20_codigo').value;
    var aDocumentos = [];
    var aTipo = [];
    let aCodigoAnexo = [];

    if (iSelecionados == 0) {
      alert('Selecione pelo menos arquivo para Enviar')
      return false
    }
    if (!confirm('Confirma o Envio do Documento?')) {
      return false;
    }

    if (empty(iCodigoProcesso)) {

      alert('Licita��o n�o informada.');
      return false;
    }

    for (var iIndice = 0; iIndice < iSelecionados; iIndice++) {

      var iDocumento = documentosSelecionados[iIndice].aCells[0].getValue();
      aDocumentos.push(iDocumento);

      var iTipo = documentosSelecionados[iIndice].aCells[3].getValue();
      aTipo.push(iTipo);

      aCodigoAnexo.push(documentosSelecionados[iIndice].aCells[2].getValue());

    }

    startLoading()

    var oParametros = new Object();

    oParametros.exec = 'EnviarDocumentoPNCP';
    oParametros.iCodigoProcesso = iCodigoProcesso;
    oParametros.aDocumentos = aDocumentos;
    oParametros.aTipoDocumentos = aTipo;
    oParametros.aCodigoAnexo = aCodigoAnexo;

    var oAjax = new Ajax.Request(
      'lic1_envioanexos.RPC.php', {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         *
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

            endLoading();
          var oRetorno = eval('(' + oAjax.responseText + ")");

          if (oRetorno.status == 1) {
            alert("Anexo(s) Enviado(s) com Sucesso!");
          } else {
            alert(oRetorno.message.urlDecode());
          }
        }
      });

  }

  function js_excluirDocumentoPNCPClickValidation() {
    const documentosSelecionados = oGridDocumentos.getSelection("object")
    var iSelecionados = documentosSelecionados.length;

    if (iSelecionados == 0) {
      alert('Selecione pelo menos arquivo para Excluir')
      return false
    } else {
      openModal('justificativaModal')
    }
  }

  function js_excluirDocumentoPNCP() {
    const documentosSelecionados = oGridDocumentos.getSelection("object")
    var iSelecionados = documentosSelecionados.length;
    var iCodigoProcesso = $('l20_codigo').value;
    var aDocumentos = [];
    var aTipo = [];
    let justificativa = document.getElementById('justificativapncp').value.trim();
    let aCodigoAnexo = [];

    if (justificativa == '') {
      alert('A justificativa n�o pode estar vazia');
      return false;
    }

    if (!confirm('Confirma a Exclus�o do Documento?')) {
      return false;
    }

    if (empty(iCodigoProcesso)) {

      alert('Licita��o n�o informada.');
      return false;
    }

    for (var iIndice = 0; iIndice < iSelecionados; iIndice++) {

      var iDocumento = documentosSelecionados[iIndice].aCells[0].getValue();
      aDocumentos.push(iDocumento);

      var iTipo = documentosSelecionados[iIndice].aCells[3].getValue();
      aTipo.push(iTipo);

      aCodigoAnexo.push(documentosSelecionados[iIndice].aCells[2].getValue());

    }

      startLoading();

    var oParametros = new Object();

    oParametros.exec = 'ExcluirDocumentoPNCP';
    oParametros.iCodigoProcesso = iCodigoProcesso;
    oParametros.aDocumentos = aDocumentos;
    oParametros.aTipoDocumentos = aTipo;
    oParametros.justificativa = justificativa;
    oParametros.aCodigoAnexo = aCodigoAnexo;

    var oAjax = new Ajax.Request(
      'lic1_envioanexos.RPC.php', {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         *
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

            endLoading();
          var oRetorno = eval('(' + oAjax.responseText + ")");
          console.log(oRetorno);
          if (oRetorno.status == 1) {
            closeModal('justificativaModal');
            clearModaFieldsRenderComponents();
            alert("Anexo(s) Excluidos(s) com Sucesso!");
          } else {
            alert(oRetorno.message.urlDecode());
          }
        }
      });
  }
</script>
