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

/**
 * 
 * @author I
 * @revision $Author: dbbruno.silva $
 * @version $Revision: 1.9 $
 */
require("libs/db_stdlib.php");
require("std/db_stdClass.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once("classes/db_solicitem_classe.php");
require_once("classes/db_solicitempcmater_classe.php");
require_once("classes/db_solicitemunid_classe.php");
include("libs/PHPExcel/Classes/PHPExcel.php");


db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label("pc16_codmater");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc11_just");
$clrotulo->label("pc11_resum");
$clrotulo->label("pc11_pgto");
$clrotulo->label("pc11_prazo");
$oDaoUnidades = db_utils::getDao("matunid");
$sSqlUnid     = $oDaoUnidades->sql_query_file(null, "m61_codmatunid,substr(m61_descr,1,20) as m61_descr,
                                                     m61_usaquant,m61_usadec", "m61_descr");
$rsUnid              = $oDaoUnidades->sql_record($sSqlUnid);
$aUnidades           = db_utils::getColectionByRecord($rsUnid);
$aParametrosCompras  = db_stdClass::getParametro("pcparam", array(db_getsession("DB_anousu")));

$sDisplay = '';
$aParametrosRegistro = db_stdClass::getParametro("registroprecoparam", array(db_getsession("DB_instit")));
$db_opcao            = 1;
if (count($aParametrosRegistro) > 0) {

  if ($aParametrosRegistro[0]->pc08_incluiritemestimativa == 'f') {
    $sDisplay = 'none';
  }
}



if ($processar) {

  $oEstimativa = $_SESSION["oSolicita"];
  $iSolicitacao = $oEstimativa->getCodigoSolicitacao();

  $novo_nome = $_FILES["uploadfile"]["name"];

  // Nome do novo arquivo
  $nomearq = $_FILES["uploadfile"]["name"];

  $extensao = strtolower(substr($nomearq, -5));

  $diretorio = "libs/Pat_xls_import/";

  // Nome do arquivo tempor�rio gerado no /tmp
  $nometmp = $_FILES["uploadfile"]["tmp_name"];

  // Seta o nome do arquivo destino do upload
  $arquivoDocument = "$diretorio" . "$novo_nome";

  if ($nomearq == "") {
    db_msgbox("Planilha n�o selecionada.");
    unlink($nometmp);
    $lFail = true;
    db_redireciona("com4_solicitaestimativaitens.php");
  }

  if ($extensao != ".xlsx") {
    db_msgbox("Arquivo inv�lido! O arquivo selecionado deve ser do tipo .xlsx");
    unlink($nometmp);
    $lFail = true;
    db_redireciona("com4_solicitaestimativaitens.php");
  }


  $files = glob('libs/Pat_xls_import/*');
  foreach ($files as $file) {
    if (is_file($file)) {
      unlink($file);
    }
  }

  // Faz um upload do arquivo para o local especificado
  if (move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $diretorio . $novo_nome)) {

    $href = $arquivoDocument;
  } else {

    db_msgbox("Erro ao enviar arquivo.");
    unlink($nometmp);
    $lFail = true;
    return false;
  }

  $dir = "libs/Pat_xls_import/";
  $files1 = scandir($dir, 1);
  $arquivo = "libs/Pat_xls_import/" . $files1[0];

  if (!file_exists($arquivo)) {
    echo "<script>alert('Arquivo n�o localizado')</script>";
  } else {

    $objPHPExcel = PHPExcel_IOFactory::load($arquivo);


    $objWorksheet = $objPHPExcel->getActiveSheet();

    //monto array com todos as linhas da planilha
    foreach ($objWorksheet->getRowIterator() as $row) {
      $rowIndex = $row->getRowIndex();
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false); //varre todas as c�lulas
      foreach ($cellIterator as $cell) {
        $colIndex = PHPExcel_Cell::columnIndexFromString($cell->getColumn());
        $val = $cell->getValue();
        $dataArr[$rowIndex][$colIndex] = $val;
      }
    }

    $arrayItensPlanilha = array();
    $arrayItensErro = array();

    foreach ($dataArr as $keyRow => $Row) {
      $objItensPlanilha = new stdClass();

      if ($keyRow >= 2) {

        //encerra o loop caso a linha atual da planilha esteja vazia.
        if ($Row[1] == "" && $Row[2] == "" && $Row[3] == "") break;

        foreach ($Row as $keyCel => $cell) {

          // c�lula do c�digo do material
          if ($keyCel == 1) {
            $objItensPlanilha->sequencial = $cell;
          }

          if ($keyCel == 2) {
            $objItensPlanilha->codmaterial = $cell;
          }

          if ($keyCel == 3) {
            $objItensPlanilha->quantidade = $cell;
            if (is_numeric($cell) != 1) {
              $arrayItensErro[] = $Row[2];
            }
          }
        }

        $arrayItensPlanilha[] = $objItensPlanilha;
      }
    }


    $clsolicitem         = new cl_solicitem;
    $clsolicitemunid     = new cl_solicitemunid;
    $clsolicitempcmater  = new cl_solicitempcmater;
    $cl_solicitemregistropreco = new cl_solicitemregistropreco;


    if (count($arrayItensErro) == 0) {
      db_inicio_transacao();

      $rsSolicitem = db_query("SELECT * FROM solicitem where pc11_numero = $iSolicitacao order by pc11_seq");

      for ($i = 0; $i < pg_num_rows($rsSolicitem); $i++) {
        if ($arrayItensPlanilha[$i]->quantidade != 1) {
          $arrayItensErro[] = $arrayItensPlanilha[$i]->codmaterial;
        }
        $item = db_utils::fieldsmemory($rsSolicitem, $i);
        db_query("update solicitem set pc11_quant = " . $arrayItensPlanilha[$i]->quantidade . " where pc11_codigo = " . $item->pc11_codigo);
      }

      db_fim_transacao(false);
      db_msgbox("Itens Incluidos com sucesso.");
    } else {
      db_msgbox("Usu�rio: Verifique a quantidade do(s) item(ns) " . json_encode($arrayItensErro) . ". Quantidade inv�lida.");
    }
  }
}


?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <?
  db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
  db_app::load("widgets/windowAux.widget.js");
  ?>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()">
  <center>
    <form name='frmItens' id='frmItens' method="POST" enctype="multipart/form-data">
      <table width="70%">

        <tr>
          <td>
            <fieldset>
              <legend><strong>Importar Itens</strong></legend>

              <table align="left" style="margin-left:44px;">
                <tr>
                  <td>
                    <b> Importar Itens: </b>
                    <select id="importaritens" name="importaritens" onchange="importar()" ali>
                      <option value="1" selected>N�o</option>
                      <option value="2">Sim</option>
                    </select>
                  </td>
                  <td id="anexarimportacao" style="display: none;">
                    <b style="margin-left:100px;"> Importar xls: </b>
                    <?php
                    db_input("uploadfile", 30, 0, true, "file", 1);
                    db_input("namefile", 31, 0, true, "hidden", 1);


                    ?>
                  </td>
                </tr>
              </table>
              <table id="inputimportacao" style="display: none;margin-top: 30px;margin-left: -200px;" align="left">
                <tr>
                  <td>
                    <input name='processar' type='submit' id="Processar" value="Processar" />
                  </td>
                </tr>
              </table>
              <div id='anexo' style='display:none'></div>

            </fieldset>
          </td>
        </tr>


        <tr>
          <td>
            <fieldset id='itensEstimativa' style='display: <?= $sDisplay ?>'>
              <legend>
                <b>Adicionar Item</b>
              </legend>
              <table>
                <tr>
                  <td>
                    <?
                    db_ancora(@$Lpc16_codmater, "js_pesquisapc16_codmater(true);", 1);
                    ?>
                  </td>
                  <td nowrap>
                    <?
                    $pc17_quant = 1;
                    db_input('pc16_codmater', 8, $Ipc16_codmater, true, 'text', 1, " onchange='js_pesquisapc16_codmater(false);'");
                    db_input('pc01_descrmater', 45, $Ipc01_descrmater, true, 'text', 1, '');
                    db_select('pc17_unid', array(), true, 1, "style='width:150px' onchange='js_usaQuantidade(this)'");
                    db_input('pc17_quant', 5, 0, true, 'text', 1, "style='display:none'");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    &nbsp;
                  </td>
                  <td>
                    <input type='button' id='btnOutrasInf' value='Mais Informa��es'>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Quantidade:</b>
                  </td>
                  <td>
                    <?
                    db_input('quantidade', 8, $Ipc16_codmater, true, 'text', 1);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" style="text-align: center;">
                    <input type="button" value='Adicionar Item' id='btnAddItem'>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td>
            <fieldset>
              <legend>
                <b>Itens Cadastrados</b>
              </legend>
              <div id='gridItensSolicitacao'>

              </div>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center;">
            <input type="button" value="Salvar Itens" id='btnSalvarItens'>
            <input style="display: none;" onclick="js_incluirCompilacao()" type="button" value="Incluir Compila��o" id='btnIncluirCompilacao'>
          </td>
        </tr>
      </table>
    </form>
  </center>
</body>
<div id='divOUtrasInf' style='display: none; text-align: center;'>
  <table width="100%">
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Dados Complementares</b>
          </legend>
          <table>
            <tr>
              <td nowrap title="<?= @$Tpc11_prazo ?>">
                <?= @$Lpc11_prazo ?>
              </td>
              <td>
                <?
                db_textarea('pc11_prazo', 3, 30, $Ipc11_prazo, true, 'text', $db_opcao)
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?= @$Tpc11_pgto ?>">
                <?= @$Lpc11_pgto ?>
              </td>
              <td>
                <?
                db_textarea('pc11_pgto', 3, 30, $Ipc11_pgto, true, 'text', $db_opcao);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?= @$Tpc11_resum ?>">
                <?= @$Lpc11_resum ?>
              </td>
              <td>
                <?
                db_textarea('pc11_resum', 3, 30, $Ipc11_resum, true, 'text', $db_opcao)
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?= @$Tpc11_just ?>">
                <?= @$Lpc11_just ?>
              </td>
              <td>
                <?
                db_textarea('pc11_just', 3, 30, $Ipc11_just, true, 'text', $db_opcao)
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="4" style='text-align: center'>
        <input type='button' value='Salvar Informa��es' id='btnFecharWindowAux' onclick='windowAuxiliar.hide()'>
      </td>
    </tr>
  </table>
</div>

</html>
<script>
  var sUrlRC = 'com4_solicitacaoCompras.RPC.php';

  function js_completaPesquisa() {

    if (parent.iframe_registro.document.getElementById('pc10_numero').value == "") return false;
    var oParam = new Object();
    oParam.exec = "pesquisarAbertura";
    oParam.iSolicitacao = parent.iframe_registro.document.getElementById('pc10_numero').value;
    oParam.tipo = 4;
    var oAjax = new Ajax.Request(sUrlRC, {
      method: "post",
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_retornoCompletaPesquisa
    });


  }

  function js_retornoCompletaPesquisa(oAjax) {
    var oRetorno = eval("(" + oAjax.responseText + ")");
    js_preencheGrid(oRetorno.itens);
  }

  js_completaPesquisa();

  function importar() {
    if (document.getElementById('importaritens').value == "2") {
      document.getElementById('inputimportacao').style.display = '';
      document.getElementById('anexarimportacao').style.display = '';
    } else {
      document.getElementById('inputimportacao').style.display = 'none';
      document.getElementById('anexarimportacao').style.display = 'none';
    }
  }

  function js_init() {

    oGridItens = new DBGrid('gridItens');
    oGridItens.nameInstance = "gridItens";
    oGridItens.setCellAlign(new Array("right", "right", "Left", "center", "center"));
    oGridItens.setCellWidth(new Array("4%", "10%", "50%", '10%', "5%", "10%", '16%', '10%'));
    oGridItens.setHeader(new Array("Seq", "Codigo", "Descri��o", "Unidade", "Out.Inf.", "Qtde", "A��o"));
    oGridItens.show($('gridItensSolicitacao'));
    js_makeWindow();
    $('btnSalvarItens').observe("click", js_salvarItens);
    $('btnAddItem').observe("click", js_adicionarItem);
    $('pc17_unid').style.height = $('pc01_descrmater').style.height + "px";
    $('btnOutrasInf').observe("click", js_maisInformacoes);
    js_parametros();

  }

  function js_pesquisapc16_codmater(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('',
        'db_iframe_pcmater',
        'func_pcmatersolicita.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater',
        'Pesquisar Materias/Servi�os',
        true,
        '0'
      );
    } else {

      if ($F('pc16_codmater') != '') {

        js_OpenJanelaIframe('',
          'db_iframe_pcmater',
          'func_pcmatersolicita.php?pesquisa_chave=' +
          $F('pc16_codmater') +
          '&funcao_js=parent.js_mostrapcmater',
          'Pesquisar Materiais/Servi�os',
          false, '0'
        );
      } else {
        $('pc16_codmater').value = '';
      }
    }
  }

  function js_mostrapcmater(sDescricaoMaterial, Erro) {
    $('pc01_descrmater').value = sDescricaoMaterial;
    if (Erro == true) {
      $('pc16_codmater').value = "";
    }
  }

  function js_mostrapcmater1(iCodigoMaterial, sDescricaoMaterial) {

    $('pc16_codmater').value = iCodigoMaterial;
    $('pc01_descrmater').value = sDescricaoMaterial;
    db_iframe_pcmater.hide();
  }

  /**
   * Adiciona o item a solicitacao
   */
  function js_adicionarItem() {

    js_divCarregando('Aguarde, adicionando item', "msgBox");
    var iCodigoItem = $F('pc16_codmater');
    var oParam = new Object();
    oParam.exec = "adicionarItemAbertura";
    oParam.iCodigoItem = iCodigoItem;
    oParam.quantidade = $F('quantidade');
    oParam.sJustificativa = encodeURIComponent(tagString($F('pc11_just')));
    oParam.sResumo = encodeURIComponent(tagString($F('pc11_resum')));
    oParam.sPrazo = encodeURIComponent(tagString($F('pc11_prazo')));
    oParam.sPgto = encodeURIComponent(tagString($F('pc11_pgto')));
    oParam.iUnidade = $F('pc17_unid');
    oParam.nQuantUnidade = $F('pc17_quant');
    var oAjax = new Ajax.Request(sUrlRC, {
      method: "post",
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_retornoadicionarItem
    });
  }

  function js_retornoadicionarItem(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("(" + oAjax.responseText + ")");
    if (oRetorno.status == 1) {

      js_preencheGrid(oRetorno.itens);
      js_limparForm();

    } else {
      alert(oRetorno.message.urlDecode());
    }
  }

  function js_preencheGrid(aItens) {
    oGridItens.clearAll(true);
    for (var i = 0; i < aItens.length; i++) {

      with(aItens[i]) {
        var aLinha = new Array();
        aLinha[0] = i + 1;
        aLinha[1] = codigoitem;
        aLinha[2] = descricaoitem.urlDecode();
        aLinha[3] = unidade_descricao.urlDecode();
        aLinha[4] = "<span id='justificativa" + indice + "' style='display:none'>" + justificativa.urlDecode() + "</span>";
        aLinha[4] += "<span id='resumo" + indice + "'        style='display:none'>" + resumo.urlDecode() + "</span>";
        aLinha[4] += "<span id='pgto" + indice + "'          style='display:none'>" + pagamento.urlDecode() + "</span>";
        aLinha[4] += "<span id='prazo" + indice + "'         style='display:none'>" + prazo.urlDecode() + "</span>";
        aLinha[4] += "<span><a href='#' onclick='js_showInfo(" + indice + ")'><img src='imagens/edittext.png' border='0' ></a>...</span>";
        aLinha[5] = "<input type='text' onKeyPress=\"return js_mask(event,'0-9|.|-')\"";
        aLinha[5] += "onfocus='js_liberaDigitacao(this)' onblur='js_bloqueiaDigitacao(this);";
        aLinha[5] += " js_salvarQuantidade(" + indice + ",this.value, true)'";
        aLinha[5] += " onkeyDown='js_verifica(this,event,false)' ";
        aLinha[5] += "style='width:100%;text-align:right;border:1px solid transparent' value='" + js_formatar(aItens[i].quantidade, 'f', '4') + "'>";

        if (automatico) {
          aLinha[6] = "&nbsp;";
        } else {
          aLinha[6] = "<input type='button' value='excluir' onclick='js_excluirLinha(" + indice + ")'>";
        }

        oGridItens.addRow(aLinha);
        oGridItens.aRows[i].aCells[0].sStyle += "background-color:#DED5CB;font-weight:bold;padding:1px";

      }
    }
    var lAlteracao = <?=isset($alterar)?"true":"false";?>;
    if(aItens.length > 0 && lAlteracao) document.getElementById('btnIncluirCompilacao').style.display = '';
    oGridItens.renderRows();
  }

  function js_salvarItens() {

    js_divCarregando('Aguarde, Salvando Itens', "msgBox");
    var oParam = new Object();
    oParam.exec = "salvarItensAbertura";
    var oAjax = new Ajax.Request(sUrlRC, {
      method: "post",
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_retornoSalvarItem
    });

  }

  function js_retornoSalvarItem(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("(" + oAjax.responseText + ")");
    if (oRetorno.status == 1) {
      document.getElementById("btnIncluirCompilacao").style.display = '';
      return alert('Itens Salvos Com sucesso.')
    } 

    alert(oRetorno.message.urlDecode());
    
  }
  oAutoComplete = new dbAutoComplete($('pc01_descrmater'), 'com4_pesquisamateriais.RPC.php');
  oAutoComplete.setTxtFieldId(document.getElementById('pc16_codmater'));
  oAutoComplete.show();
  oAutoComplete.setCallBackFunction(function(id, label) {

    document.getElementById('pc16_codmater').value = id;
    document.getElementById('pc01_descrmater').value = label;
    $('div_lista_' + oAutoComplete.sTxtFieldName).innerHTML = '';
    $('quantidade').focus();

  });


  function js_limparForm() {

    $('pc16_codmater').value = "";
    $('pc01_descrmater').value = "";
    $('pc11_resum').value = "";
    $('pc11_just').value = "";
    $('pc11_pgto').value = "";
    $('pc11_prazo').value = "";

  }

  function js_excluirLinha(iSeq) {

    js_divCarregando('Aguarde, removendo item', "msgBox");
    var oParam = new Object();
    oParam.exec = "excluirItens";
    oParam.iItemRemover = iSeq;
    var oAjax = new Ajax.Request(sUrlRC, {
      method: "post",
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_retornoadicionarItem
    });

  }

  /**
   * Adicionamos as unidades ao combo pc17_unid
   */
  <?

  foreach ($aUnidades as $oUnidade) {

    echo "var oOption = new Option('{$oUnidade->m61_descr}',{$oUnidade->m61_codmatunid});\n";
    echo "oOption.setAttribute('usadecimal', '{$oUnidade->m61_usadec}');\n";
    echo "oOption.setAttribute('usaquantidade', '{$oUnidade->m61_usaquant}');\n";
    echo "\$('pc17_unid').add(oOption, null);\n";
  }
  ?>
  /**
   * Libera  o input passado como parametro para a digitacao.
   * � Retirado a mascara do valor e liberado para Edi��o
   * � Colocado a Variavel nValorObjeto no escopo GLOBAL
   */
  function js_liberaDigitacao(object) {

    nValorObjeto = object.value;
    object.value = js_strToFloat(object.value).valueOf();
    object.style.border = '1px solid black';
    object.readOnly = false;
    object.style.fontWeight = "bold";
    object.select();

  }

  /**
   * bloqueia  o input passado como parametro para a digitacao.
   * � colocado  a mascara do valor e bloqueado para Edi��o
   */
  function js_bloqueiaDigitacao(object, iBold) {


    object.readOnly = true;
    object.style.border = '0px';
    object.style.fontWeight = "normal";
    if (iBold) {
      object.style.fontWeight = "bold";
    }
    object.value = js_formatar(object.value, 'f');



  }
  /**
   * Verifica se  o usu�rio cancelou a digita��o dos valores.
   * Caso foi cancelado, voltamos ao valor do objeto, e 
   * bloqueamos a digita��o
   */
  function js_verifica(object, event, iBold) {

    var teclaPressionada = event.which;
    if (teclaPressionada == 27) {
      object.value = nValorObjeto;
      js_bloqueiaDigitacao(object, iBold);
    }
  }

  function js_salvarQuantidade(iIndice, nQuantidade) {


    js_divCarregando('Aguarde, Salvando informa��o', "msgBox");
    var oParam = new Object();
    oParam.exec = "salvarItensValor";
    oParam.iIndice = iIndice;
    oParam.quantidade = js_strToFloat(nQuantidade).valueOf();
    var oAjax = new Ajax.Request(sUrlRC, {
      method: "post",
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_retornoSalvarQuantidade
    });
  }

  function js_retornoSalvarQuantidade(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("(" + oAjax.responseText + ")");
    if (oRetorno.status == 2) {
      alert(oRetorno.message.urlDecode());
    }
  }

  function js_usaQuantidade(oSelect) {

    if (oSelect.options[oSelect.selectedIndex].getAttribute("usaquantidade") == "t") {
      $('pc17_quant').style.display = '';
    } else {
      $('pc17_quant').style.display = 'none';
    }

  }

  function js_parametros() {

    var oParam = new Object();
    oParam.exec = "getParametros";
    oParam.sleep = 5;
    oParam.aParametros = new Array();
    var oParamCompras = new Object();
    oParamCompras.sParam = "pcparam";
    oParamCompras.aKeys = new Array();
    oParam.aParametros.push(oParamCompras);
    var oParamOrcam = new Object();
    oParamOrcam.sParam = "orcparametro";
    oParamOrcam.aKeys = new Array();
    oParamOrcam.aKeys.push(2009);
    oParam.aParametros.push(oParamOrcam);
    var oAjax = new Ajax.Request('sys4_parametros.RPC.php', {
      method: "post",
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_retornoparametro,
      assynchronous: false
    });
  }

  function js_maisInformacoes() {

    windowAuxiliar.show(10, 10);
    $('pc11_prazo').focus();

  }

  function js_makeWindow() {

    windowAuxiliar = new windowAux('wndAuxiliar', 'Dados Complementares', 600, 500);
    windowAuxiliar.setObjectForContent($('divOUtrasInf'));
    windowAuxiliar.hide();
    //$('divOUtrasInf').style.display= '';

  }

  function js_showInfo(iIndice) {

    iIndice = iIndice;
    $('pc11_resum').value = $('resumo' + iIndice).innerHTML;
    $('pc11_just').value = $('justificativa' + iIndice).innerHTML;
    $('pc11_pgto').value = $('pgto' + iIndice).innerHTML;
    $('pc11_prazo').value = $('prazo' + iIndice).innerHTML;
    $('btnFecharWindowAux').onclick = function() {

      $('resumo' + iIndice).innerHTML = $('pc11_resum').value;
      $('justificativa' + iIndice).innerHTML = $('pc11_just').value;
      $('pgto' + iIndice).innerHTML = $('pc11_pgto').value;
      $('prazo' + iIndice).innerHTML = $('pc11_prazo').value;

      $('pc11_resum').value = "";
      $('pc11_just').value = "";
      $('pc11_pgto').value = "";
      $('pc11_prazo').value = "";
      $('btnFecharWindowAux').onclick = function() {
        windowAuxiliar.hide();
      }
      js_alterarDados(iIndice);
      windowAuxiliar.hide();

    }

    windowAuxiliar.show(10, 10);

  }

  function js_alterarDados(iIndice) {

    js_divCarregando('Aguarde, alterando item', "msgBox");
    var oParam = new Object();
    oParam.iIndice = iIndice;
    oParam.sJustificativa = encodeURIComponent(tagString($('justificativa' + iIndice).innerHTML));
    oParam.sResumo = encodeURIComponent(tagString($('resumo' + iIndice).innerHTML));
    oParam.sPrazo = encodeURIComponent(tagString($('prazo' + iIndice).innerHTML));
    oParam.sPgto = encodeURIComponent(tagString($('pgto' + iIndice).innerHTML));
    oParam.exec = "alterarItem";
    var oAjax = new Ajax.Request(sUrlRC, {
      method: "post",
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_retornoadicionarItem
    });
  }

  function js_retornoparametro(oAjax) {

    var oRetorno = eval("(" + oAjax.responseText + ")");
    for (var iParam = 0; iParam < oRetorno.itens.length; iParam++) {

      with(oRetorno.itens[iParam]) {
        eval("o" + name.valueOf() + "=fields");
      }
    }
    $('pc17_unid').value = opcparam[0].pc30_unid;
  }

  function js_incluirCompilacao(){
    var codigoAbertura = parent.iframe_registro.document.getElementById('pc54_solicita').value;
    CurrentWindow.corpo.document.location.href='com4_registroprecocompilacao001.php?codigoAbertura='+codigoAbertura;
  }
  
</script>