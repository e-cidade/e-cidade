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


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_bensmedida_classe.php");
require_once("classes/db_bensmodelo_classe.php");
require_once("classes/db_bensmarca_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clBensMedida     = new cl_bensmedida;
$clBensMarca      = new cl_bensmarca;
$clBensModelo     = new cl_bensmodelo;

$db_opcao = 1;
$db_botao = false;

$lUsaPCASP = "false";
if (USE_PCASP) {
  $lUsaPCASP = "true";
}

$lMostraViewNotasPendentes = 'true';
$iCodigoNota               = '0';
if (isset($oGet->iCodigoEmpNotaItem) && !empty($oGet->iCodigoEmpNotaItem)) {

  $db_opcao = 1;
  $db_botao = true;
  $lMostraViewNotasPendentes = 'false';
  $iCodigoNota               = $oGet->iCodigoEmpNotaItem;
}
//Saber se possui integra��o patrimonial
require_once "classes/db_parametrointegracaopatrimonial_classe.php";
$clBens = new cl_parametrointegracaopatrimonial;
$rsBem = $clBens->sql_record($clBens->sql_query_file(null, '*', null, " c01_modulo = 1"));
$oBemIntegracao = db_utils::fieldsMemory($rsBem, 0);
$integracao = $oBemIntegracao->c01_modulo;

$oDaoEmpNotaItem = new cl_empnotaitem();
$campos = "  empnota.e69_numero AS notafiscal, empempitem.e62_numemp AS seqempenho,
             empempenho.e60_codemp || '/' || empempenho.e60_anousu AS numerodoempenho,
             empnotaord.m72_codordem AS ordemdecompra, cgm.z01_nome as nome" ;
$sSqlBuscaItemNota = $oDaoEmpNotaItem->sql_query_dados_material(null, $campos, null, "e72_sequencial = {$oGet->iCodigoEmpNotaItem}");
$rsBuscaItemNota   = $oDaoEmpNotaItem->sql_record($sSqlBuscaItemNota);

if ($oDaoEmpNotaItem->numrows > 0) {
      
  $oStdDadosItem     = db_utils::fieldsMemory($rsBuscaItemNota, 0);
  $cod_notafiscal    = $oStdDadosItem->notafiscal;     
  $cod_ordemdecompra = $oStdDadosItem->ordemdecompra;
  $emp_sistema_select_descr = 's' ;
  $t53_empen         = $oStdDadosItem->seqempenho;
  $z01_nome_empenho  = $oStdDadosItem->nome;
     
}
?>

<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <?php
  db_app::load("scripts.js, prototype.js, strings.js, DBToogle.widget.js, dbmessageBoard.widget.js");
  db_app::load("estilos.css, grid.style.css, classes/DBViewNotasPendentes.classe.js, widgets/windowAux.widget.js, datagrid.widget.js");
  ?>
  <style type="text/css">
    .bold {
      font-weight: bold;
    }

    #fieldsetBensNovo table {
      border-collapse: collapse;
    }

    #fieldsetBensNovo table tr td {
      padding-top: 4px;
      white-space: nowrap;
    }

    #fieldsetBensNovo table tr td:first-child {
      text-align: left;
      width: 130px;
    }

    /* pega a segunda td */
    #fieldsetBensNovo table tr td+td {}

    /* pega a terceira td */
    #fieldsetBensNovo table tr td+td+td {
      text-align: right;
      padding-left: 5px;
      width: 100px;
    }

    #fieldsetBensNovo table tr td+td+td+td {
      text-align: left;
      width: 150px;
    }

    .ancora {
      font-weight: bold;
    }

    .readOnly {
      backgroud-color: #DEB887;
    }

    div.header-container table.table-header tr td#col1.table_header.cell {
      width: 120px !important;

    }

    div.body-container table.table-body tr td#ctnGridNotasPendentesrow0cell0.linhagrid.cell {
      width: 120px !important;

    }

    div.header-container table.table-header tr td#col2.table_header.cell {
      width: 90px !important;

    }

    div.body-container table.table-body tr td#ctnGridNotasPendentesrow0cell1.linhagrid.cell {
      width: 90px !important;

    }


    div.header-container table.table-header tr td#col3.table_header.cell {
      width: 80px !important;

    }

    div.body-container table.table-body tr td#ctnGridNotasPendentesrow0cell2.linhagrid.cell {
      width: 80px !important;

    }


    div.header-container table.table-header tr td#col4.table_header.cell {
      width: 255px !important;

    }

    div.body-container table.table-body tr td#ctnGridNotasPendentesrow0cell3.linhagrid.cell {
      width: 255px !important;

    }


    div.header-container table.table-header tr td#col5.table_header.cell {
      width: 65px !important;

    }

    div.body-container table.table-body tr td#ctnGridNotasPendentesrow0cell4.linhagrid.cell {
      width: 65px !important;

    }


    div.header-container table.table-header tr td#col6.table_header.cell {
      width: 130px !important;

    }

    div.body-container table.table-body tr td#ctnGridNotasPendentesrow0cell5.linhagrid.cell {
      width: 130px !important;

    }


    div.header-container table.table-header tr td#col7.table_header.cell {
      width: 110px !important;

    }

    div.body-container table.table-body tr td#ctnGridNotasPendentesrow0cell6.linhagrid.cell {
      width: 110px !important;

    }
  </style>
</head>

<body bgcolor="#CCCCCC" onload="js_carregaDadosForm(<?= $db_opcao ?>);">
  <div style="margin-top: 25px;"></div>
  <center>
    <div align="center" style="width: 720px; display: block;">
      <?php
      include("forms/db_frm_bensnovo.php");
      ?>
    </div>
  </center>
</body>

</html>

<script>
  lMostraViewNotasPendentes = <?php echo $lMostraViewNotasPendentes; ?>;
  var integracao = "<?php print $integracao; ?>";
  if (!integracao) {
    $("contabilizado").style.display = 'none';
    document.getElementById("contabilizador").style.display = 'none';
    $("contabilizado").value = 'nao';
  }

  if (lMostraViewNotasPendentes == true) {

    /**
     * Direciona o usu�rio para a inclusão de bens Individual ou Global, dependendo
     * da quantidade do item.
     */
    function loadDadosBem(oDadosLinha) {

      var iQuantidadeItem = oDadosLinha.iQuantidadeItem;
      var iCodigoEmpNotaItem = oDadosLinha.iCodigoEmpNotaItem;

      var sUrlDireciona = "";
      if (iQuantidadeItem == 1) {
        sUrlDireciona = "pat1_bens001.php?iCodigoEmpNotaItem=" + iCodigoEmpNotaItem;
      } else {
        sUrlDireciona = "pat1_bensglobalnovo001.php?iCodigoEmpNotaItem=" + iCodigoEmpNotaItem;
      }

      if (oDBViewNotasPendentes.getLocationGlobal()) {
        window.location = sUrlDireciona;
      } else {
        parent.window.location = sUrlDireciona;
      }
    }

    var oDBViewNotasPendentes = new DBViewNotasPendentes('oDBViewNotasPendentes', <?php echo $lUsaPCASP; ?>);
    oDBViewNotasPendentes.setCallBackDoubleClick(loadDadosBem);
    oDBViewNotasPendentes.setTextoRodape("<b>* Dois cliques sob a linha para carregar o bem</b>");
    oDBViewNotasPendentes.show();

  } else {

    var oParam = new Object();
    oParam.exec = "getDadosItemNota";
    oParam.iCodigoItemNota = <?php echo $iCodigoNota; ?>;

    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.carregando'), "msgBox");
    var oAjax = new Ajax.Request("pat1_bensnovo.RPC.php", {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oParam),
      onComplete: js_preencheFormulario
    });

  }

  function js_preencheFormulario(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oAjax.responseText + ")");

    if (oRetorno.status == 1) {

      $("t52_dtaqu").value = js_formatar(oRetorno.e69_dtnota, 'd');
      $("t52_numcgm").value = oRetorno.e60_numcgm;
      $("z01_nome").value = oRetorno.z01_nome;
      $("vlAquisicao").value = oRetorno.e62_vlrun;
      $("t52_descr").value = oRetorno.pc01_descrmater;
      $("iCodigoItemNota").value = <?php echo $iCodigoNota; ?>;
     
      $("t52_dtaqu").style.backgroundColor = '#FFFFFF';
      $("t52_numcgm").style.backgroundColor = '#DEB887';
      $("z01_nome").style.backgroundColor = '#DEB887';
      $("vlAquisicao").style.backgroundColor = '#DEB887';
      $('tdFornecedor').innerHTML = "<b>Fornecedor:</b>";

      $("t52_dtaqu").readOnly = true;
      $("t52_numcgm").readOnly = true;
      $("z01_nome").readOnly = true;
      $("vlAquisicao").readOnly = true;

      $("contabilizado").style.display = 'none';
      document.getElementById("contabilizador").style.display = 'none';
      $("contabilizado").value = 'nao';

      js_validarBusDadosMat($("iCodigoItemNota").value);
    }
  }

  function js_validarBusDadosMat(valor)
  {
    console.log(valor);
    if (valor) {
      sCor = '#DEB887';
      $("t53_empen").disabled = true;
      $("t53_empen").style.backgroundColor = sCor;
      $("t53_empen").style.color = "#000";
      $("cod_notafiscal").disabled = true;
      $("cod_notafiscal").style.backgroundColor = sCor;
      $("cod_notafiscal").style.color = "#000";
      $("cod_ordemdecompra").disabled = true;
      $("cod_ordemdecompra").style.backgroundColor = sCor;
      $("cod_ordemdecompra").style.color = "#000";
      $("emp_sistema_select_descr").disabled = true;
      $("emp_sistema_select_descr").style.backgroundColor = sCor;
      $("emp_sistema_select_descr").style.color = "#000";
    } 
  }

</script>

<?
if (isset($incluir)) {

  if (trim(@$erro_msg) != "") {
    db_msgbox($erro_msg);
  }
  if ($sqlerro == true) {

    if ($clbens->erro_campo != "") {

      echo "<script> document.form1." . $clbens->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clbens->erro_campo . ".focus();</script>";
    }
  } else {
    db_redireciona("pat1_bensglobal001.php?" . $parametros . "liberaaba=true&chavepesquisa=$t52_bem");
  }
}
?>