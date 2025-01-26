<?
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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_condataconf_classe.php");
require_once("classes/db_acordomovimentacao_classe.php");
require_once("classes/db_acordoitemdotacao_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clacordo             = new cl_acordo;
$clacordomovimentacao = new cl_acordomovimentacao;
$clacordoitemdotacao  = new cl_acordoitemdotacao;
$clcondataconf        = new cl_condataconf;

$db_opcao = 1;

$clacordo->rotulo->label();
$clacordomovimentacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
$clrotulo->label("ac10_datamovimento");
$clrotulo->label("ac10_obs");

$anousu = db_getsession('DB_anousu');

$sSQL = "select to_char(c99_datapat,'YYYY') c99_datapat
          from condataconf
            where c99_instit = " . db_getsession('DB_instit') . "
              order by c99_anousu desc limit 1";

$rsResult       = db_query($sSQL);
$maxC99_datapat = db_utils::fieldsMemory($rsResult, 0)->c99_datapat;

$sNSQL = "";
if ($anousu > $maxC99_datapat) {

  $sNSQL = $clcondataconf->sql_query_file($maxC99_datapat, db_getsession('DB_instit'), 'c99_datapat,c99_data');
} else {
  $sNSQL = $clcondataconf->sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit'), 'c99_datapat,c99_data');
}

$result = db_query($sNSQL);
$c99_datapat = db_utils::fieldsMemory($result, 0)->c99_datapat;
$c99_data = db_utils::fieldsMemory($result, 0)->c99_data;
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, strings.js, prototype.js, roundDecimal.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
  <style>
    td {
      white-space: nowrap;
    }

    fieldset table td:first-child {
      width: 80px;
      white-space: nowrap;
    }
  </style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?= '<input id="c99_datapat_hidden" type="hidden" value="' . $c99_datapat . '">' ?>
  <table border="0" align="center" cellspacing="0" cellpadding="0" style="padding-top:40px;">
    <tr>
      <td valign="top" align="center">
        <fieldset>
          <legend><b>Assinatura do Acordo</b></legend>
          <table align="center" border="0">
            <tr>
              <td title="<?= @$Tac16_sequencial ?>" align="left">
                <?php db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);", $db_opcao); ?>
              </td>
              <td align="left">
                <?
                db_input(
                  'ac16_sequencial',
                  10,
                  $Iac16_sequencial,
                  true,
                  'text',
                  $db_opcao,
                  " onchange='js_pesquisaac16_sequencial(false);'"
                );
                ?>
              </td>
              <td align="left">
                <?
                db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
                ?>
              </td>
              <td align="left">
                <?
                db_input('ac16_origem', 2, $Iac16_origem, true, 'hidden', 3);
                ?>
              </td>

            </tr>

            <tr>
              <td title="<?= @$Tac10_datamovimento ?>" align="left">
                <b>Data:</b>
              </td>

              <td align="left">
                <?
                db_inputdata(
                  'ac10_datamovimento',
                  @$ac10_datamovimento_dia,
                  @$ac10_datamovimento_mes,
                  @$ac10_datamovimento_ano,
                  true,
                  'text',
                  $db_opcao,
                  ""
                );
                ?>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="left" title="Data da publica��o do contrato ou termo de parceria. &#013;Campo:ac16_datapublicacao">
                <b> Data de publica��o </b>
              </td>

              <td align="left">
                <?
                db_inputdata(
                  'ac16_datapublicacao',
                  @$ac16_datapublicacao_dia,
                  @$ac16_datapublicacao_mes,
                  @$ac16_datapublicacao_ano,
                  true,
                  'text',
                  $db_opcao
                );
                ?>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr id="trdatareferencia" style="display:none;">
              <td align="left" title="<?= @$Tac16_datareferencia ?>">
                <b>Data de Refer�ncia:</b>
              </td>

              <td align="left">
                <?
                db_inputdata(
                  'ac16_datareferencia',
                  @$ac16_datareferencia_dia,
                  @$ac16_datareferencia_mes,
                  @$ac16_datareferencia_ano,
                  true,
                  'text',
                  $db_opcao
                );
                ?>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="left" title="Ve�culo de divulga��o onde o contrato ou termo de parceria foi publicado.&#013;Campo:ac16_veiculodivulgacao">
                <b> Ve�culo de divulga��o </b>
              </td>
              <td align="left" colspan="2">
                <?
                db_input('ac16_veiculodivulgacao', 50, $Iac16_veiculodivulgacao, true, 'text', $db_opcao, '', '', '', '', 50);
                ?>
              </td>

            </tr>
            <tr>
              <td colspan="3">
                <fieldset>
                  <legend>
                    <b>Observa��o</b>
                  </legend>
                  <?
                  db_textarea('ac10_obs', 5, 66, $Iac10_obs, true, 'text', $db_opcao, "");
                  ?>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="center">
        <input id="incluir" name="incluir" type="button" value="Incluir" onclick="return js_checaValor();">
      </td>
    </tr>
  </table>

  <?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>

</body>
<script>
  $('ac16_sequencial').style.width = "100%";
  $('ac16_resumoobjeto').style.width = "100%";

  var sUrl = 'con4_contratosmovimento.RPC.php';
  const c99_data = "<?= $c99_data ?>";
  /**
   * Pesquisa acordos ao clicar no botao assinar acordo na aba Penalidades
   */

  js_pesquisaac16_sequencial(false);

  /**
   * Pesquisa acordos
   */
  function js_pesquisaac16_sequencial(lMostrar) {

    if (lMostrar == true) {

      let sUrl = 'func_acordo.php?semvigencia=true&assinatura=true&funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto|ac16_origem';
      js_OpenJanelaIframe('top.corpo',
        'db_iframe_acordo',
        sUrl,
        'Pesquisar Acordo',
        true);
    } else {

      if ($('ac16_sequencial').value != '') {

        let sUrl = 'func_acordo.php?semvigencia=true&descricao=true&assinatura=true&pesquisa_chave=' + $('ac16_sequencial').value +
          '&funcao_js=parent.js_mostraacordo';

        js_OpenJanelaIframe('top.corpo',
          'db_iframe_acordo',
          sUrl,
          'Pesquisar Acordo',
          false);
      } else {
        $('ac16_sequencial').value = '';
      }
    }
  }

  /**
   * Retorno da pesquisa acordos
   */
  function js_mostraacordo(chave1, chave2, chave3, erro) {
    if (erro == true) {

      $('ac16_sequencial').value = '';
      $('ac16_resumoobjeto').value = chave2;
      $('ac16_sequencial').focus();
    } else {

      $('ac16_sequencial').value = chave1;
      $('ac16_resumoobjeto').value = chave2;
      $('ac16_origem').value = chave3;
    }
  }

  /**
   * Retorno da pesquisa acordos
   */
  function js_mostraacordo1(chave1, chave2, chave3) {
    $('ac16_sequencial').value = chave1;
    $('ac16_resumoobjeto').value = chave2;
    let origem = '';
    switch (chave3.toLowerCase()) {
      case 'manual':
        origem = 3;
        break;
      case 'processo de compras':
        origem = 1;
        break;
      case 'licita��o':
        origem = 2;
        break;
    }
    $('ac16_origem').value = origem;
    db_iframe_acordo.hide();
  }

  /**
   * Incluir assinatura para o contrato
   */

  function js_checaValor() {

    if (document.getElementById('ac16_veiculodivulgacao').value.replace(/\s/g, '') == '') {
      alert('Campo Ve�culo de Divulga��o est� vazio!');
      return;
    }

    var oParam = new Object();
    oParam.sequencial = $('ac16_sequencial').value;
    oParam.exec = 'checaValoresItensAcordo';
    var oAjax = new Ajax.Request('aco4_acordo.RPC.php', {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_assinarContrato
    });
  }

  function js_assinarContrato(obj) {
    let response = JSON.parse(obj.responseText);

    if (response.erro) {
      alert(response.message.urlDecode());
      return;
    }

    try {

      if ($('ac16_sequencial').value == '') {
        throw new Error('Acordo n�o informado!');
      }
      if ($('ac10_datamovimento').value == '') {
        throw new Error('Data n�o informada!');
      }

      if ($('ac16_datapublicacao').value == '') {
        throw new Error('Data da publica��o n�o informada!');
      }

      if ($('ac16_veiculodivulgacao').value == '') {
        throw new Error('Ve�culo de divulga��o n�o informado!');
      }

    } catch (e) {

      alert(e.message);
      return false;

    }

    var oParam = new Object();
    oParam.exec = "assinarContrato";
    oParam.acordo = $F('ac16_sequencial');
    oParam.dtmovimentacao = $F('ac10_datamovimento');
    oParam.dtpublicacao = $F('ac16_datapublicacao');
    oParam.veiculodivulgacao = encodeURIComponent(tagString($F('ac16_veiculodivulgacao')));
    oParam.observacao = encodeURIComponent(tagString($F('ac10_obs')));
    oParam.dtreferencia = $F('ac16_datareferencia');

    /**
     * Verificar Encerramento Periodo Patrimonial
     */
    //    DATA DO MOVIMENTO
    let partesData = oParam.dtmovimentacao.split("/");
    const dataMovimento = new Date(partesData[2], partesData[1] - 1, partesData[0]);

    //    DATA DO FECHAMENTO Contabil
    partesData = c99_data.split("-");
    const dataContabil = new Date(partesData[0], partesData[1] - 1, partesData[2]);

    //    DATA DO FECHAMENTO PATRIMONIAL
    partesData = $("c99_datapat_hidden").value.split("-");
    const dataPatrimonial = new Date(partesData[0], partesData[1] - 1, partesData[2]);

    //    DATA DE REFER�NCIA
    partesData = oParam.dtreferencia.split("/");
    const dataReferencia = new Date(partesData[2], partesData[1] - 1, partesData[0]);

    if (oParam.dtreferencia != "") {

      if ((dataReferencia.getMonth() == dataPatrimonial.getMonth()
        && dataReferencia.getFullYear() == dataPatrimonial.getFullYear())
        || (dataReferencia.getMonth() == dataContabil.getMonth()
        && dataReferencia.getFullYear() == dataContabil.getFullYear())) {
        alert("Usu�rio: A data de refer�ncia dever� ser no m�s posterior ao m�s da data inserida.");
        return;
      }

      if (dataReferencia <= dataPatrimonial || dataReferencia <= dataContabil) {
        alert("O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.");
        return;
      }


    } else {
      oParam.dtreferencia = null;
      if (document.getElementById("trdatareferencia").style.display == 'contents') {
        alert('Data de refer�ncia n�o informada!');
        return;
      }

    }


    if (oParam.dtreferencia == null && ((dataMovimento <= dataPatrimonial) || (dataMovimento <= dataContabil ))) {
      document.getElementById("trdatareferencia").style.display = 'contents';
      alert("O per�odo j� foi encerrado para envio do SICOM. Preencha o campo Data de Refer�ncia com uma data no m�s subsequente.");
      return;
    }

    js_divCarregando('Aguarde incluindo assinatura...', 'msgBoxAssianturaContrato');

    var oAjax = new Ajax.Request(sUrl, {
      method: 'post',
      parameters: 'json=' + js_objectToJson(oParam),
      onComplete: js_retornoDadosAssinatura
    });
  }

  /**
   * Retorna os dados da inclus�o assinatura
   */
  function js_retornoDadosAssinatura(oAjax) {
  js_removeObj("msgBoxAssianturaContrato");

  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.status == 2) {
    alert(oRetorno.erro.urlDecode());
    return false;
  }
  $("ac16_datareferencia").value = "";
  $("ac10_datamovimento").value = "";
  $("ac16_datapublicacao").value = "";
  $("ac16_veiculodivulgacao").value = "";
  $("ac10_obs").value = "";
  document.getElementById("trdatareferencia").style.display == "none";
  alert("Inclus�o efetuada com Sucesso.");
  return true;
}

  function js_finalizaracordo() {
    let iAcordo = $('ac16_sequencial').value;
    document.location.href = 'aco4_homologacaoinclusao001.php?ac16_sequencial=' + iAcordo;
  }
</script>

</html>
