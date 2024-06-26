<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_libpessoal.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
global $db_config;
db_selectmax("db_config","select lower(trim(munic)) as d08_carnes , cgc from db_config where codigo = ".db_getsession("DB_instit"));

if(trim($db_config[0]["cgc"]) == "90940172000138"){
     $d08_carnes = "daeb";
}else{
     $d08_carnes = $db_config[0]["d08_carnes"];
}
$db_opcao = 1;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh01_numcgm");
$clrotulo->label("rh21_descr");
$clrotulo->label("rh08_descr");
$clrotulo->label("rh18_descr");
$clrotulo->label("rh37_descr");
$clrotulo->label("r70_descr");
$clrotulo->label("r59_descr");
$clrotulo->label("db90_descr");
$clrotulo->label("rh50_oid");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?php
db_app::load('prototype.js,
              strings.js,
              scripts.js');

db_app::load("estilos.css");

?>

<script>
function js_emite(){
  //js_controlarodape(true);
  qry  = 'ano_base='+ document.form1.ano_base.value;
  qry += '&oriret='+ document.form1.oriret.value;
  qry += '&codret='+ document.form1.codret.value;
  qry += '&nomeresp=' + document.form1.nomeresp.value;
  qry += '&cpfresp=' + document.form1.cpfresp.value;
  qry += '&dddresp=' + document.form1.dddresp.value;
  qry += '&foneresp=' + document.form1.foneresp.value;
  qry += '&r70_numcgm=' + document.form1.r70_numcgm.value;
  if(document.form1.pref_fun){
    qry += '&pref_fun=' + document.form1.pref_fun.value;
  }
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_geradirf','pes4_geradirf002.php?'+qry,'Gerando Arquivo',true);
}

function js_erro(msg){

  CurrentWindow.corpo.db_iframe_geradirf.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_geradirf.hide();
}
function js_controlarodape(mostra){

  if(mostra == true){

    document.form1.rodape.value = parent.bstatus.document.getElementById('st').innerHTML;
    parent.bstatus.document.getElementById('st').innerHTML = '<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    parent.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
}

function js_detectaarquivo(arquivo,pdf){

  CurrentWindow.corpo.db_iframe_geradirf.hide();
  listagem = arquivo+"#Download Arquivo TXT |";
  listagem+= pdf+"#Download Relat�rio";
  js_montarlista(listagem,"form1");
}
</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

    <center>
    <form name="form1" method="post" id='form1' action="">
    <table border='0' style="margin-top:20px">

	   <tr>
	    <td><fieldset><legend><strong>Informa��es de Processamento</strong></legend>
	      <table>
	       <tr>
	        <td nowrap>
	          <strong>Tipo de Processamento:</strong>
	        </td>
	        <td nowrap>
	          <?php
	            $aTipoProcessamento = array("1" => "Geral",
	                                        "2" => "Selecionados");
	            db_select("iTipoProcessamento", $aTipoProcessamento, true, 1, "onchange='return js_tipoProcessamento();' style='width: 100%;'");
	          ?>
	        </td>
          <td nowrap>
            <strong>Somente Valores Acima de <span id="valor_minimo"></span>: </strong>
            <input type="hidden" name="codigo_arquivo" id="codigo_arquivo" value="" />
          </td>
          <td>
            <?php
             $arr_acima = array("N"=>"N�o","S"=>"Sim");
             db_select('acima6000',$arr_acima,true,4,"");
	          ?>
          </td>
	      </tr>
	     </table>
	    </fieldset>
	   </td>
	   </tr>

     <tr>
       <td>
         <fieldset><legend><strong>Dados DIRF</strong></legend>
          <table>
            <tr>
              <td align="right" nowrap title="Digite o Ano Base">
                <strong>Ano Base:</strong>
              </td>
              <td align="left">
                <?php
                  db_select('ano_base',null,true,4,'onChange="js_buscaValor()"');
                ?>
              </td>
            </tr>
            <tr align="center">
              <td align="right" nowrap title="Tipo de Declara��o: ">
                <strong>Tipo de Declara��o:</strong>
              </td>
              <td align="left">
                <?php
                 $xy = array("O"=>"Original","R"=>"Retificadora");
                 db_select('oriret',$xy,true,4,"");
                ?>
              </td>
            </tr>
            <tr id='recibo'>
              <td align="right" nowrap>
                <strong>N�mero do Recibo:</strong>
              </td>
              <td>
                <?php
                 db_input('numerorecibo', 10, 1, true, 'text', 2, '');
                ?>
              </td>
            </tr>
          </table>
       </fieldset>
     </td>
     </tr>
    <tr>
     <td colspan="2"  align="center">
     <fieldset>
        <legend><strong>Dados do Respons�vel</strong></legend>
        <table width="100%">
      <tr>
        <td align="right" nowrap title="Nome do Respons�vel ">
         <strong>Nome:</strong>
        </td>
        <td align="left">
          <?php
            db_input('nomeresp',40,'',true,'text',2,'');
	        ?>
        </td>
        </tr>
        <tr>
        <td align="right" nowrap title="C�digo Nacional de Pessoal F�SICA" >
        <strong>CPF:</strong>
        </td>
        <td align="left">
          <?php
            db_input('cpfresp', 11, '', true, 'text', 2,
                      "  onBlur='js_verificaCGCCPF(this)'
                         onKeyDown='return js_controla_tecla_enter(this,event);'",
                         null, null, null, 11);
          ?>
        </td>
        </tr>
        <tr>
        <td align="right" nowrap title="DDD do Respons�vel">
         <strong>DDD:</strong>
        </td>
        <td align="left">
          <?php
            db_input('dddresp', 4, 1, true, 'text', 2, '', null, null, null, 2);
	        ?>
        </td>
        </tr>
        <tr>
        <td align="right" nowrap title="Fone do Respons�vel ">
         <strong>Fone:</strong>
        </td>
        <td align="left">
          <?php
            db_input('foneresp', 10, 1, true, 'text', 2, '', null, null, null, 9);
       	  ?>
        </td>
        </tr>
         <tr>
        <td align="right" nowrap title="CPF do Respons�vel pelo CNPJ">
        <strong>CPF do Respons�vel pelo CNPJ:</strong>
        </td>
        <td align="left">
          <?php
            db_input('cpfrespcnpj', 11, '', true, 'text', 2,
                     " onBlur='js_verificaCGCCPF(this)'
                       onKeyDown='return js_controla_tecla_enter(this,event);'",
                       null, null, null, 11);
          ?>
        </td>
       </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td><fieldset><legend><strong>Sa�de</strong></legend>
      <table>
      <tr>
        <td nowrap title="<?=@$Trh01_numcgm?>">
          <?php
            db_ancora(@$Lrh01_numcgm,"js_pesquisarh01_numcgm(true);",$db_opcao);
          ?>
        </td>
        <td nowrap>
          <?php
            db_input('rh01_numcgm',6,$Irh01_numcgm,true,'text',$db_opcao,"onchange='js_pesquisarh01_numcgm(false);' tabIndex='1'","cgm_saude1");
          ?>
          <?php
            db_input('z01_nome',33,$Iz01_nome,true,'text',3,'',"nome_saude1");
          ?>
        </td>
        <td align="right" nowrap title="ANS" >
         <strong>ANS:</strong>
        </td>
        <td align="left">
          <?php
            db_input('numeroans1',10,'',true,'text',2,'', '', '', '', 6);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh01_numcgm?>">
          <?php
            db_ancora(@$Lrh01_numcgm,"js_pesquisarh01_numcgm_1(true);",$db_opcao);
          ?>
        </td>
        <td nowrap>
          <?php
            db_input('rh01_numcgm',6,$Irh01_numcgm,true,'text',$db_opcao,"onchange='js_pesquisarh01_numcgm_1(false);' tabIndex='2'","cgm_saude2");
          ?>
          <?php
            db_input('z01_nome',33,$Iz01_nome,true,'text',3,'',"nome_saude2");
          ?>
        </td>
        <td align="right" nowrap title="ANS">
         <strong>ANS:</strong>
        </td>
        <td align="left">
          <?php
            db_input('numeroans2',10,'',true,'text',2,'', '', '', '', 6);
          ?>
        </td>
       </tr>
     </table>
    </fieldset>
   </td>
   </tr>
   <tr>
    <td><fieldset><legend><strong>Informa��es Financeiras</strong></legend>
      <table><tr>
        <td nowrap title="<?=@$Trh01_numcgm?>">
          <strong>Buscar Pagamentos Efetuados na Contabilidade:</strong>
        </td>
        <td nowrap>
         <?php
           $arr = array('s' => 'Sim','n'=>'N�o');
           db_select("dadosfinanceiros",$arr,true,$db_opcao,"");
         ?>
        </td>
      </tr>
     </table>
    </fieldset>
   </td>
   </tr>
   <tr>
    <td colspan="2">
      <fieldset>
        <legend><strong>CNPJ</strong></legend>
        <table>
          <tr>
            <td nowrap align="right" title="CNPJ">
              <strong>CNPJ:</strong>
            </td>
            <td>
              <?php

		            $instit = db_getsession("DB_instit");

		            $sSqlUnidades  = "select distinct  o41_cnpj,                                                     ";
		            $sSqlUnidades .= "       case when o41_cnpj = cgc then nomeinst else o41_descr end as nome_fundo ";
		            $sSqlUnidades .= "  from orcunidade                                                              ";
		            $sSqlUnidades .= "       inner join orcorgao  on o41_orgao  = o40_orgao                          ";
		            $sSqlUnidades .= "                           and o40_anousu = o41_anousu                         ";
		            $sSqlUnidades .= "       inner join db_config on codigo     = o41_instit                         ";
		            $sSqlUnidades .= " where o41_instit = {$instit}                                                  ";
		            $sSqlUnidades .= "   and o41_anousu = ".db_getsession("DB_anousu");
		            $result = db_query($sSqlUnidades);
		            db_selectrecord("cnpjpagador", $result, true, @$db_opcao, "", "", "", "", " js_verificaProcessamento(); return js_tipoProcessamento()", "2");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
	  <td colspan="2" align = "center">
      <input  name="gera" id="gera" type="button" value="Gerar DIRF" onclick="return js_emitirDirf();">
        </td>
      </tr>
     </table>
  </form>
</center>
</body>
</html>
<script type="text/javascript">

var sRpc        = 'pes4_rhdirfparametros.RPC.php';
const MENSAGENS = 'recursoshumanos.pessoal.geradirfnovo.'
js_carregaAnoBase();

/**
 * CArrega todos os anos cadastrados na tabela rhdifparametros.
 */
function js_carregaAnoBase() {

  var oAjax = new Ajax.Request(sRpc, {
                                method:'post',
                                parameters:'json={"exec": "getAnos"}',
                                onComplete: function (oAjax){

                                  var oRetorno = eval("("+oAjax.responseText+")");

                                  if (oRetorno.iStatus == 2) {

                                    alert(oRetorno.sMessage.urlDecode());
                                    return false;
                                  }

                                  oRetorno.oDados.each(function(sAno){

                                    var oOption   = document.createElement('option');
                                    oOption.value = sAno;
                                    oOption.text  = sAno;
                                    $('ano_base').appendChild(oOption);
                                  })

                                  js_buscaValor();
                                }
                              });
}

/**
 * Busca os valores a partir do ano base selecionado.
 */
function js_buscaValor() {

  var iAno = $F('ano_base');

  var oAjax = new Ajax.Request(sRpc, {
                                method:'post',
                                parameters:'json={"exec": "getValorAno", "iAno": ' + iAno + '}',
                                onComplete: function (oAjax){

                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  $('valor_minimo').innerHTML = oRetorno.iValor;
                                  $('codigo_arquivo').value   = oRetorno.sCodigoArquivo;

                                  js_verificaProcessamento();
                                }
                              });
}

function js_verificaProcessamento() {
  var oParam = {
    exec           : "verificaProcessamento",
    iAno           : $F('ano_base'),
    sFontePagadora : $F('cnpjpagador')
  }

  new Ajax.Request("pes4_processardirf.RPC.php", {
                                method:'post',
                                parameters:'json=' + Object.toJSON(oParam),
                                onComplete: function (oAjax){

                                  var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

                                  if (oRetorno.iStatus != 2 && oRetorno.lProcessado == false) {
                                    alert( _M( MENSAGENS + "nao_processado") );
                                  }
                                }
                              });
}

$('numerorecibo').maxLength = 12;

function js_pesquisarh01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'func_nome',
                        'func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  } else {
    if (document.form1.cgm_saude1.value != '') {

      js_OpenJanelaIframe('',
                          'func_nome',
                          'func_nome.php?testanome=true&pesquisa_chave='+
                          document.form1.cgm_saude1.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    }else{
      document.form1.nome_saude1.value = '';
    }
  }
}

function js_mostracgm(erro,chave1,chave2) {

  document.form1.nome_saude1.value = chave1;

  if (erro == true) {
    document.form1.cgm_saude1.focus();
    document.form1.cgm_saude1.value = '';
  }
}

function js_mostracgm1(chave1,chave2){

  document.form1.cgm_saude1.value = chave1;
  document.form1.nome_saude1.value = chave2;
  func_nome.hide();
}

function js_pesquisarh01_numcgm_1(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'func_nome',
                        'func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1_1|z01_numcgm|z01_nome','Pesquisa',true);
  } else {
    if (document.form1.cgm_saude2.value != '') {

      js_OpenJanelaIframe('',
                          'func_nome',
                          'func_nome.php?testanome=true&pesquisa_chave='+
                          document.form1.cgm_saude2.value+'&funcao_js=parent.js_mostracgm_1','Pesquisa',false);
    }else{
      document.form1.nome_saude2.value = '';
    }
  }
}

function js_mostracgm_1(erro,chave1,chave2) {

  document.form1.nome_saude2.value = chave1;

  if (erro == true) {
    document.form1.cgm_saude2.focus();
    document.form1.cgm_saude2.value = '';
  }
}

function js_mostracgm1_1(chave1,chave2){

  document.form1.cgm_saude2.value = chave1;
  document.form1.nome_saude2.value = chave2;
  func_nome.hide();
}

function js_tipoProcessamento() {

  var iTipoProcessamento = new Number($('iTipoProcessamento').value);
  if (iTipoProcessamento == 2) {

    parent.document.formaba.geradirf.disabled     = false;
    parent.document.formaba.selecionados.disabled = false;
    CurrentWindow.corpo.iframe_selecionados.js_montaGrid();
    parent.mo_camada('selecionados');
  } else {

    parent.document.formaba.geradirf.disabled     = true;
    parent.document.formaba.selecionados.disabled = true;
    CurrentWindow.corpo.iframe_selecionados.js_montaGrid();
    parent.mo_camada('geradirf');
  }
}

function js_emitirDirf() {

  if ($('ano_base').value == ''){

    alert(_M(MENSAGENS+'ano_base_invalido'));
    return false;
  }

  if ($F('nomeresp').trim().length < 4){

    $('nomeresp').value = $F('nomeresp').trim();
    alert(_M(MENSAGENS+'nome_invalido'));
    return false;
  }

  if($F('cpfrespcnpj').length < 11){

    alert(_M(MENSAGENS+'cpf_invalido'));
    return false;
  }

  if($F('dddresp').length < 2){

    alert(_M(MENSAGENS+'ddd_invalido'));
    return false;
  }

  if($F('foneresp').length < 8){

    alert(_M(MENSAGENS+'fone_invalido'));
    return false;
  }

  if($F('cpfrespcnpj').length < 11){

    alert(_M(MENSAGENS+'cpfresponsavel_invalido'));
    return false;
  }

  var oParam  = new Object();
  oParam.exec = 'gerarDirf';
  oParam.iAno                   = $F('ano_base');
  oParam.iValor                 = $('valor_minimo').textContent.replace(/[^0-9\,]/g, '').replace(',', '.');
  oParam.sCodigoArquivo         = $F('codigo_arquivo');
  oParam.TipoDeclaracao         = $F('oriret');
  oParam.iNumeroRecibo          = $F('numerorecibo');
  oParam.sNomeResponsavel       = tagString(encodeURIComponent($F('nomeresp')));
  oParam.sCpfResponsavelCNPJ    = $F('cpfrespcnpj');
  oParam.sDDDResponsavel        = $F('dddresp');
  oParam.sFoneResponsavel       = $F('foneresp');
  oParam.sCpfResponsavel        = $F('cpfresp');
  oParam.iCcgmSaude             = $F('cgm_saude1');
  oParam.iNumeroANS             = $F('numeroans1');
  oParam.iCcgmSaude2            = $F('cgm_saude2');
  oParam.iNumeroANS2            = $F('numeroans2');
  oParam.lProcessaEmpenho       = $F('dadosfinanceiros')=='s'?true:false;
  oParam.sCnpj                  = $F('cnpjpagador');
  oParam.sAcima6000             = $F('acima6000');
  oParam.aMatriculaSelecionadas = CurrentWindow.corpo.iframe_selecionados.js_retornaMatriculasSelecionados();

  js_divCarregando('Aguarde, Processando...','div_msg', true);

  var oAjax = new Ajax.Request('pes4_processardirf.RPC.php',
                               {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete:js_retornoEmiteDirf
                               });
}

function js_retornoEmiteDirf(oAjax) {

  js_removeObj('div_msg');

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

   var listagem = "tmp/"+oRetorno.arquivo+"#Download Arquivo TXT";
       js_montarlista(listagem,"form1");
  } else {
   alert('Erro ao gerar Arquivo.');
  }
}
</script>
