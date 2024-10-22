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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/db_app.utils.php';
require_once 'dbforms/db_funcoes.php';

?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
  db_app::load('estilos.css, grid.style.css');
  db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
  ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <div class="container" style="width:650px;">

    <fieldset>
      <legend>Anexos de Empenho</legend>
      <form name="form" id="form" method="post" action="" enctype="multipart/form-data">


        <?php db_input('namefile', 30, 0, true, 'hidden', 1); ?>
        <?php db_input('sequencialAnexo', 30, 0, true, 'hidden', 1); ?>

        <table class="form-container">

          <tr>
            <td nowrap title="<?php echo $Tl216_licanexospncp; ?>">
              <?php db_ancora('Empenho: ', 'js_pesquisaEmpenho(true);', 1); ?>
            </td>
            <td>
              <?php
              db_input('e60_numemp', 12, 1, true, 'text', 1, " onChange='js_pesquisaEmpenho(false);'");
              db_input('e60_resumo', 60, 1, true, 'text', 3, '');
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <b>Identificação Documento:</b>
            </td>
            <td>
              <?php db_input('uploadfile', 53, 0, true, 'file', 1); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?= @$Tl213_sequencial; ?>">
              <b>
                <?php
                db_ancora('Tipo de Anexo :', 'js_pesquisal20_codtipocom(true);', 3);
                ?>
              </b>
            </td>
            <td>
              <?php

              $tipo = [];
              $tipo[0] = 'Selecione';
              $cltipoanexo = new cl_tipoanexo();
              $rsTipoAnexo = $cltipoanexo->sql_record($cltipoanexo->sql_query(null, '*', 'l213_sequencial', 'l213_sequencial in (16,17)'));

              for ($iIndiceTipo = 0; $iIndiceTipo < $cltipoanexo->numrows; ++$iIndiceTipo) {
                  $oTipo = db_utils::fieldsMemory($rsTipoAnexo, $iIndiceTipo);
                  $tipo[$oTipo->l213_sequencial] = urldecode(utf8_decode($oTipo->l213_descricao));
              }

              if ($cltipoanexo->numrows == 0) {
                  db_msgbox('Nenhuma Tipo de anexo cadastrado!!');
                  $rsTipoAnexo = '';
                  $db_opcao = 3;
                  $db_botao = false;
                  db_input('l213_sequencial', 10, '', true, 'text');
                  db_input('l213_sequencial', 40, '', true, 'text');
              } else {
                  db_select('l213_sequencial', $tipo, true, 1, '');
              }
              ?>
            </td>
          </tr>

        </table>
      </form>
    </fieldset>

    <input type="button" id="btnSalvar" onClick="js_salvar();" value="Salvar" />
    <input type="button" id="btnAlterar" onClick="js_salvarAlteracoes();" value="Alterar" />


    <fieldset style="margin-top:15px;">
      <legend>Documentos Anexados</legend>
      <div id="ctnDbGridDocumentos"></div>
    </fieldset>

    <input type="button" id="btnEnviarPNCP" value="Envia documento para o PNCP" onClick="js_enviarAnexoPNCP();" />
    <input type="button" id="btnExcluirPNCP" value="Excluir documento no PNCP" onClick="js_excluirAnexoPNCP();" />
    <input type="button" id="btnExcluir" value="Excluir Selecionados" onClick="js_excluirSelecionados();" />
    <input type="button" id="btnDownloadAnexos" value="Download" onClick="js_download();" />

  </div>

  <?php db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit')); ?>
  <div id="teste" style="display:none;"></div>              
</body>

</html>
<script type="text/javascript">

  document.getElementById("btnAlterar").style.display = "none";
  var sUrlRpc = 'lic1_anexoempenho.RPC.php';
  var oGridDocumentos = new DBGrid('gridDocumentos');

  oGridDocumentos.nameInstance = "oGridDocumentos";
  oGridDocumentos.setCheckbox(0);
  oGridDocumentos.setCellAlign(new Array("center","center", "center", "center"));
  oGridDocumentos.setCellWidth(["10%","40%", "30%", "30%"]);
  oGridDocumentos.setHeader(new Array("Seq","Descrição","Tipo", "Ação"));
  oGridDocumentos.aHeaders[1].lDisplayed = false;
  oGridDocumentos.allowSelectColumns(true);
  oGridDocumentos.show($('ctnDbGridDocumentos'));

  /**
   * Buscar anexos de um empenho
   * @return boolean
   */
  function js_getAnexos() {

    let iNumeroEmpenho = $('e60_numemp').value;

    if (empty(iNumeroEmpenho)) {
      return false;
    }

    js_divCarregando('mensagem_buscando_documentos', 'msgbox');

    let oParametros = new Object();

    oParametros.sExecuta = 'carregarAnexosDeEmpenho';
    oParametros.iNumeroEmpenho = iNumeroEmpenho;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          let oRetorno = eval('(' + oAjax.responseText + ")");

          oGridDocumentos.clearAll(true);

          for (let iIndice = 0; iIndice < oRetorno.aAnexos.length; iIndice++) {

            let oDocumento = oRetorno.aAnexos[iIndice];
            let sHTMLBotoes = '<input type="button" value="Alterar" onClick="js_alterarAnexo(' + oDocumento.e100_sequencial + ', \'' + oDocumento.l213_descricao + '\');" />  ';
            sHTMLBotoes += '<input type="button" value="Excluir" onClick="js_excluirAnexo(' + oDocumento.e100_sequencial + ');" />  ';
            let aLinha = [oDocumento.e100_sequencial,oDocumento.e100_titulo, oDocumento.l213_descricao, sHTMLBotoes];
            oGridDocumentos.addRow(aLinha, false, false);
            
          }

          oGridDocumentos.renderRows();
        }
      }
    );

  }

  /**
   * Alteração do tipo do Anexo
   * @param integer iSequencialEmpAnexo
   * @return void
   */
  function js_alterarAnexo(iSequencialEmpAnexo) {

    js_divCarregando('Aguarde... Carregando Anexo.', 'msgbox');

    let oParametros = new Object();

    oParametros.sExecuta = 'carregarAnexo';
    oParametros.iSequencialEmpAnexo = iSequencialEmpAnexo;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          let oRetorno = eval('(' + oAjax.responseText + ")");
          let sMensagem = oRetorno.erro.urlDecode();

          if (oRetorno.status > 1) {
            return alert(sMensagem);
          }

          $('l213_sequencial').value = oRetorno.iTipo;
          $('namefile').value = '';
          $('uploadfile').value = '';
          $('sequencialAnexo').value = iSequencialEmpAnexo;
          $('uploadfile').disabled = true;
          $("btnSalvar").style.display = "none";
          $("btnAlterar").style.display = "";

        }

      });

  }

    /**
   * Salva alteração do Anexo
   * @return void
   */
  function js_salvarAlteracoes() {

    if ($('l213_sequencial').value == 0) {
      return alert('Selecione um tipo de anexo.');
    }

    js_divCarregando('Aguarde... Salvando Alterações.', 'msgbox');

    let oParametros = new Object();

    oParametros.sExecuta = 'alterarAnexo';

    let aDadosEmpAnexo = {e100_tipoanexo: $('l213_sequencial').value};

    oParametros.iSequencialEmpAnexo = $('sequencialAnexo').value;
    oParametros.aDadosEmpAnexo = aDadosEmpAnexo ;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          let oRetorno = eval('(' + oAjax.responseText + ")");
          let sMensagem = oRetorno.erro.urlDecode();

          if (oRetorno.status > 1) {
            return alert(sMensagem);
          }
          
          alert("Alteração Efetuada com sucesso");

          js_limparCampos();
          js_getAnexos();
        }
      });

  }

  /**
   * Exclui o anexo selecionado.
   * @return boolean
   */
  function js_excluirAnexo(iSequencialEmpAnexo) {

    let confirmacao = confirm("Deseja realmente excluir o anexo?");
    if(!confirmacao) return false;

    js_divCarregando('Excluindo documento...', 'msgbox');

    let oParametros = new Object();

    oParametros.sExecuta = 'excluirAnexo';
    oParametros.iSequencialEmpAnexo = iSequencialEmpAnexo;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          let oRetorno = eval('(' + oAjax.responseText + ")");
          let sMensagem = oRetorno.erro.urlDecode();

          if (oRetorno.status > 1) {
            return alert(sMensagem);
          }

          alert("Anexo excluído com sucesso !");
          js_getAnexos();
        }
      });

  }

  function js_excluirSelecionados(){

    let anexosSelecionados = oGridDocumentos.getSelection("object");

    if (anexosSelecionados.length == 0) {
      return alert('Selecione pelo menos arquivo para download')
    }

    let aSequencialEmpAnexo = [];

    for (anexo of anexosSelecionados) {
      let codigoDoAnexo = anexo.aCells[0].getValue()
      aSequencialEmpAnexo.push(codigoDoAnexo);
    }

    js_divCarregando('Excluindo Anexos...', 'msgbox');

    let oParametros = new Object();

    oParametros.sExecuta = 'excluirAnexos';
    oParametros.aSequencialEmpAnexo = aSequencialEmpAnexo;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj("msgbox");
          let oRetorno = eval('(' + oAjax.responseText + ")");
          let sMensagem = oRetorno.erro.urlDecode();

          if (oRetorno.status > 1) {
            return alert(sMensagem);
          }

          alert("Anexo(s) excluído(s) com sucesso !");
          js_getAnexos();
        }
      });

  }

  /**
   * Realiza o download dos anexos selecionados
   * @return boolean
   */
  function js_download () {

    let anexosSelecionados = oGridDocumentos.getSelection("object");

    if (anexosSelecionados.length == 0) {
      return alert('Selecione pelo menos arquivo para download')
    }

    if (anexosSelecionados.length == 1) {
      js_downloadAnexo(anexosSelecionados[0].aCells[0].getValue());
      return;
    }

    let sSequencialEmpAnexo = [];
    let codigoDoAnexo

    for (anexo of anexosSelecionados) {
      let codigoDoAnexo = anexo.aCells[0].getValue()
      sSequencialEmpAnexo.push(codigoDoAnexo)
    }

    js_downloadAnexos(sSequencialEmpAnexo.toString());

  }

  function js_downloadAnexos(sSequencialEmpAnexo){

    js_divCarregando('mensagem_carregando_documento', 'msgbox');

    let oParametros = new Object();

    oParametros.sExecuta = 'downloadAnexos';
    oParametros.sSequencialEmpAnexo = sSequencialEmpAnexo;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
      parameters: 'json=' + Object.toJSON(oParametros),
      method: 'post',
      asynchronous: false,

    /**
     * Retorno do RPC
     */
    onComplete: function(oAjax) {

      js_removeObj("msgbox");
      let oRetorno = eval('(' + oAjax.responseText + ")");
      let sMensagem = oRetorno.erro.urlDecode();

      if (oRetorno.status > 1) {
        return alert(sMensagem);
      }

      window.open("db_download.php?arquivo=" + 'anexosEmpenho.zip');
    }

    });
  }

    /**
   * Download de um anexo
   * - busca arquivo do banco e salva no tmp
   * - exibe janela com link para download
   * @param  integer iCodigoAnexo
   * @return void
   */
  function js_downloadAnexo(iCodigoAnexo) {

    js_divCarregando('Aguarde... Organizando documentos para o download', 'msgbox')

    let oParametros = new Object();

    oParametros.sExecuta = 'downloadAnexo';
    oParametros.iCodigoAnexo = iCodigoAnexo;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

      /**
       * Retorno do RPC
       */
      onComplete: function(oAjax) {

        js_removeObj("msgbox");
        let oRetorno = eval('(' + oAjax.responseText + ")");
        let sMensagem = oRetorno.erro.urlDecode();

        if (oRetorno.status > 1) {
          return alert(sMensagem);
        }

        let nomearquivo = oRetorno.nomearquivo.urlDecode();

        window.open("db_download.php?arquivo=" + nomearquivo);
        }
        
    });

  }

  /**
   * Pesquisar Empenho
   *
   * @param boolean lMostra
   * @return boolean
   */
  function js_pesquisaEmpenho(lMostra) {

    if (lMostra) {
      js_OpenJanelaIframe('', 'db_iframe_empenho', 'func_empempenhoanexos.php?funcao_js=parent.js_preencheEmpenho|e60_numemp|e60_resumo', 'Pesquisa', true);
      return;
    }

    js_OpenJanelaIframe('', 'db_iframe_empenho', `func_empempenhoanexos.php?funcao_js=parent.js_preencheEmpenho&pesquisa_chave=${$('e60_numemp').value}`, 'Pesquisa', false);

  }

  function js_preencheEmpenho(codigoEmpenho,resumo,erro){
    if(erro == true){
      $('e60_numemp').value = '';
      $('e60_resumo').value = '';
      return;
    }
    $('e60_numemp').value = codigoEmpenho;
    $('e60_resumo').value = resumo;
    js_getAnexos();
    db_iframe_empenho.hide();
  }

  function js_salvar() {

    if (empty($('e60_numemp').value)) {
      return alert('Selecione um Empenho');
    }

    if ($('l213_sequencial').value == 0) {
      return alert('Selecione um tipo de anexo!');
    }

    if (empty($('namefile').value)) {
      return alert('Arquivo anexo não informado!');
    }

    js_divCarregando('Aguarde... Enviando documento.', 'msgbox');

    let oParametros = new Object();
    oDadosAnexo = new Object();

    oParametros.sExecuta = 'salvarAnexo';
    oDadosAnexo.e100_tipoanexo = $('l213_sequencial').value;
    oDadosAnexo.e100_empenho = $('e60_numemp').value;
    oDadosAnexo.e100_usuario = '';
    oDadosAnexo.e100_instit = '';
    oDadosAnexo.e100_datalancamento = '';
    oDadosAnexo.e100_titulo = $("uploadfile").files[0].name;
    oDadosAnexo.e100_anexo = '';
    oDadosAnexo.e100_sequencial = '';
    oParametros.aDadosEmpAnexo = oDadosAnexo;

    oParametros.sCaminhoArquivo = $('namefile').value;

    let oAjax = new Ajax.Request(
      sUrlRpc, {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,
        onComplete: function(oAjax) {

          js_removeObj('msgbox');
          let oRetorno = eval('(' + oAjax.responseText + ")");
          let sMensagem = oRetorno.erro.urlDecode();

          if (oRetorno.iStatus > 1) {

            alert(sMensagem);
            return false;
          }

          alert("Anexo(s) Salvos(s) com Sucesso!");
          js_getAnexos();
          js_limparCampos();
        }
      });
  }

  function js_enviarAnexoPNCP(){

    let anexosSelecionados = oGridDocumentos.getSelection("object")
    let aAnexos = [];

    if (anexosSelecionados.length == 0) {
      return alert('Selecione pelo menos um arquivo para Enviar');
    }

    if (!confirm('Confirma o Envio do Documento?')) {
      return false;
    }

    if (empty($('e60_numemp').value)) {
      return alert('Empenho não informado.');
    }

    for (i = 0; i < anexosSelecionados.length; i++) {

      let oAnexo = new Object();
      oAnexo.sequencial = anexosSelecionados[i].aCells[0].getValue();
      aAnexos.push(oAnexo);

    }

    js_divCarregando('Aguarde... Enviando documentos.', 'msgbox');

    let oParametros = new Object();

    oParametros.sExecuta = 'EnviarAnexoPNCP';
    oParametros.aAnexos = aAnexos;
    oParametros.iNumeroEmpenho = $('e60_numemp').value;

    let oAjax = new Ajax.Request(
      'lic1_anexoempenho.RPC.php', {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         *
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj('msgbox');
          var oRetorno = eval('(' + oAjax.responseText + ")");

          if (oRetorno.status == 1) {
            return alert("Anexo(s) Enviado(s) com Sucesso!");
          } 
          
          return alert(oRetorno.erro.urlDecode());
          
        }

      });
  }

  function js_excluirAnexoPNCP(){

    let anexosSelecionados = oGridDocumentos.getSelection("object")
    let aAnexos = [];

    if (anexosSelecionados.length == 0) {
      return alert('Selecione pelo menos um arquivo para Enviar');
    }

    if (!confirm('Confirma a Exclusão do Documento?')) {
      return false;
    }

    if (empty($('e60_numemp').value)) {
      return alert('Empenho não informado.');
    }

    for (i = 0; i < anexosSelecionados.length; i++) {

      let oAnexo = new Object();
      oAnexo.sequencial = anexosSelecionados[i].aCells[0].getValue();
      aAnexos.push(oAnexo);

    }

    js_divCarregando('Aguarde... Enviando documentos.', 'msgbox');

    let oParametros = new Object();

    oParametros.sExecuta = 'ExcluirAnexoPNCP';
    oParametros.aAnexos = aAnexos;
    oParametros.iNumeroEmpenho = $('e60_numemp').value;

    let oAjax = new Ajax.Request(
      'lic1_anexoempenho.RPC.php', {
        parameters: 'json=' + Object.toJSON(oParametros),
        method: 'post',
        asynchronous: false,

        /**
         *
         * Retorno do RPC
         */
        onComplete: function(oAjax) {

          js_removeObj('msgbox');
          var oRetorno = eval('(' + oAjax.responseText + ")");

          if (oRetorno.status == 1) {
            return alert("Anexo(s) Excluidos(s) com Sucesso!");
          } 
          
          return alert(oRetorno.erro.urlDecode());
          
        }

      });

  }

  function js_limparCampos(){
    $('l213_sequencial').value = 0;
    $('namefile').value = '';
    $('uploadfile').value = '';
    $('sequencialAnexo').value = '';
    $('uploadfile').disabled = false;
    $("btnSalvar").style.display = "";
    $("btnAlterar").style.display = "none";
  }

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

</script>
