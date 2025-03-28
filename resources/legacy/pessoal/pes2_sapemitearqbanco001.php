<?php
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
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
include_once("classes/db_rharqbanco_classe.php");
include_once("classes/db_rhcontasrec_classe.php");

db_postmemory($HTTP_POST_VARS);

$clrhcontasrec = new cl_rhcontasrec;
$clrharqbanco  = new cl_rharqbanco;
$clrotulo      = new rotulocampo;

$clrharqbanco->rotulo->label();
$clrotulo->label('rh34_codarq');
$clrotulo->label('rh34_descr');
$clrotulo->label('db90_descr');
$clrotulo->label('rh41_codigo');
$clrotulo->label('o15_descr');

if(isset($emite2)){

  db_inicio_transacao();
  $sqlerro         = false;
  $clrharqbanco->alterar($rh34_codarq);
  $rh34_sequencial += 1;
  db_fim_transacao($sqlerro);
}else if(isset($rh34_codarq)){

  $result = $clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));
  if($clrharqbanco->numrows > 0){

    db_fieldsmemory($result,0);
    $rh34_sequencial += 1;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript">
function js_valores(){
  if(document.form1.rh34_codarq.value==""){

    alert('C�digo do arquivo n�o informado!');
    document.form1.rh34_codarq.focus();
  }else if(document.form1.datagera_dia.value == "" || document.form1.datagera_mes.value == "" || document.form1.datagera_ano.value == ""){

    alert("Data da gera��o do arquivo n�o informada");
    document.form1.datagera_dia.select();
  }else if(document.form1.datadeposit_dia.value == "" || document.form1.datadeposit_mes.value == "" || document.form1.datadeposit_ano.value == ""){

    alert("Data de dep�sito n�o informada");
    document.form1.datadeposit_dia.select();
  }else{
    return true;
  }

  return false;
}

function js_emite(){

  js_controlarodape(true);
  qry  = 'rh34_codarq='+document.form1.rh34_codarq.value;
  qry += '&datadeposit='+document.form1.datadeposit_ano.value+'-'+document.form1.datadeposit_mes.value+'-'+document.form1.datadeposit_dia.value;
  qry += '&datagera='+document.form1.datagera_ano.value+'-'+document.form1.datagera_mes.value+'-'+document.form1.datagera_dia.value;
  qry += '&codban='+document.form1.rh34_codban.value;
  qry += "&recursos="+document.form1.rh41_codigo.value;
  qry += "&rh41_codigo="+document.form1.rh41_codigo.value;
  if(document.form1.layout){
    qry += '&layout='+document.form1.layout.value;
  }

  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_geraarqbanco','pes2_sapemitearqbanco002.php?'+qry,'Gerando Arquivo',false);
}

function js_detectaarquivo(arquivo,pdf){

  js_controlarodape(false);
  CurrentWindow.corpo.db_iframe_geraarqbanco.hide();
  listagem  = arquivo+"#Download arquivo TXT (pagamento eletr�nico)|";
  listagem += pdf+"#Download relat�rio";
  js_montarlista(listagem,"form1");
}

function js_erro(msg){

  js_controlarodape(false);
  CurrentWindow.corpo.db_iframe_geraarqbanco.hide();
  alert(msg);
}

function js_fechaiframe(){
  db_iframe_geraarqbanco.hide();
}

function js_controlarodape(mostra){

  if(mostra == true){

    document.form1.rodape.value = parent.bstatus.document.getElementById('st').innerHTML;
    parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    parent.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" border="0">
  <form name="form1" method="post" action="">
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Data da Gera��o:</strong></td>
    <td colspan="3">
    <?
      if((!isset($datagera_dia) || (isset($datagera_dia) && trim($datagera_dia) == "")) &&
         (!isset($datagera_mes) || (isset($datagera_mes) && trim($datagera_mes) == "")) &&
         (!isset($datagera_ano) || (isset($datagera_ano) && trim($datagera_ano) == ""))){

        $datagera_dia=date('d',db_getsession('DB_datausu'));
        $datagera_mes=date('m',db_getsession('DB_datausu'));
        $datagera_ano=date('Y',db_getsession('DB_datausu'));
      }

      db_inputdata('datagera',@$datagera_dia,@$datagera_mes,@$datagera_ano,true,'text',1,"");
    ?>
    </td>
  </tr>
  <tr>
    <td><strong>Data do Dep�sito:</strong></td>
    <td colspan="3">
      <?
        if((!isset($datadeposit_dia) || (isset($datadeposit_dia) &&
           trim($datadeposit_dia) == ""))                        &&
           (!isset($datadeposit_mes) || (isset($datadeposit_mes) &&
           trim($datadeposit_mes) == ""))                        &&
           (!isset($datadeposit_ano) || (isset($datadeposit_ano) &&
            trim($datadeposit_ano) == ""))){

          $datadeposit_dia = "";
          $datadeposit_mes = "";
          $datadeposit_ano = "";
        }

        db_inputdata('datadeposit',@$datadeposit_dia,@$datadeposit_mes,@$datadeposit_ano,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td align="left" nowrap title="<?=@$Trh34_codarq?>">
      <?db_ancora(@$Lrh34_codarq,"js_pesquisa(true);",1);?>
    </td>
    <td align="left" nowrap colspan="3">
      <?
        db_input("rh34_codarq",6,@$Irh34_codarq,true,"text",4,"onchange='js_pesquisa(false);'");
        db_input("rh34_descr",40,@$Irh34_descr,true,"text",3);
        db_input("rodape",40,0,true,"hidden",3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_codban?>">
      <?
        db_ancora(@$Lrh34_codban,"js_pesquisarh34_codban(true);",1);
      ?>
    </td>
    <td colspan="3">
      <?
        db_input('rh34_codban',6,$Irh34_codban,true,'text',1," onchange='js_pesquisarh34_codban(false);'");
        db_input('db90_descr',40,$Idb90_descr,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh41_codigo?>">
      <?
      db_ancora(@$Lrh41_codigo,"js_pesquisarh41_codigo(true);",1);
      ?>
    </td>
    <td colspan="3">
      <?
      db_input('rh41_codigo',6,$Irh41_codigo,true,'text',1," onchange='js_pesquisarh41_codigo(false);'")
      ?>
      <?
      db_input('o15_descr',40,$Io15_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_agencia?>">
      <?=@$Lrh34_agencia?>
    </td>
    <td>
      <?
        if(isset($rh41_codigo) && $rh41_codigo+0 > 0){

          $sql_rec = $clrhcontasrec->sql_query_contas("","","","","*","rh41_codigo"," rh41_codigo = $rh41_codigo and rh41_instit = ".db_getsession('DB_instit'));
          $res_rec = db_query($sql_rec);
          if( pg_numrows($res_rec) > 0 ){

            db_fieldsmemory($res_rec,0);
            $rh34_agencia   = $c63_agencia;
            $rh34_dvagencia = $c63_dvagencia;
            $rh34_conta     = db_formatar($c63_conta,'s','0',8,'e',0);
            $rh34_dvconta   = $c63_dvconta;
          }
        }

        db_input('rh34_agencia',5,$Irh34_agencia,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvagencia?>" align="right">
      <?=@$Lrh34_dvagencia?>
    </td>
    <td>
      <?
        db_input('rh34_dvagencia',2,$Irh34_dvagencia,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_conta?>">
      <?=@$Lrh34_conta?>
    </td>
    <td>
      <?
        db_input('rh34_conta',15,$Irh34_conta,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvconta?>" align="right">
      <?=@$Lrh34_dvconta?>
    </td>
    <td>
      <?
        db_input('rh34_dvconta',2,$Irh34_dvconta,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_convenio?>">
      <?=@$Lrh34_convenio?>
    </td>
    <td >
      <?
        db_input('rh34_convenio',15,$Irh34_convenio,true,'text',1,"")
      ?>
    </td>
    <?if(isset($rh34_codban) && $rh34_codban == "104"){?>
    <td align="right">
      <strong>Layout:</strong>
    </td>
    <td>
      <?
        $arr_layout = array(
                             "18"=>"SIACC",
                             "3"=>"SICOV"
                           );
        db_select("layout", $arr_layout, true, 1, "");
      ?>
    </td>
    <?}?>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_sequencial?>">
      <?=@$Lrh34_sequencial?>
    </td>
    <td colspan="3">
      <?
        db_input('rh34_sequencial',15,$Irh34_sequencial,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align = "center">
      <input name="emite2" id="emite2" type="submit" value="Processar" onclick="return js_valores();" >
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">
function js_pesquisarh41_codigo(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhcontasrec','func_rhcontasrec.php?funcao_js=parent.js_mostrarhcontasrec1|rh41_codigo|o15_descr','Pesquisa',true);
  }else{

    if(document.form1.rh41_codigo.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhcontasrec','func_rhcontasrec.php?pesquisa_chave='+document.form1.rh41_codigo.value+'&funcao_js=parent.js_mostrarhcontasrec','Pesquisa',false);
    }else{
      document.form1.o15_descr.value = '';
    }
  }
}

function js_mostrarhcontasrec(chave,erro){

  document.form1.o15_descr.value = chave;
  if(erro==true){

    document.form1.rh41_codigo.focus();
    document.form1.rh41_codigo.value = '';
  }
  document.form1.submit();
}

function js_mostrarhcontasrec1(chave1,chave2){

  document.form1.rh41_codigo.value = chave1;
  document.form1.o15_descr.value   = chave2;
  db_iframe_rhcontasrec.hide();
  document.form1.submit();
}

function js_pesquisa(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rharqbanco','func_rharqbanco.php?funcao_js=parent.js_mostra1|rh34_codarq|rh34_descr','Pesquisa',true);
  }else{

    if(document.form1.rh34_codarq.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rharqbanco','func_rharqbanco.php?pesquisa_chave='+document.form1.rh34_codarq.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }else{

      document.form1.rh34_codarq.value = '';
      document.form1.rh34_descr.value  = '';
      location.href = 'pes2_sapemitebb200lay001.php';
    }
  }
}

function js_mostra(chave,erro){

  if(erro==true){

    document.form1.rh34_descr.value  = chave;
    document.form1.rh34_codarq.value = '';
    document.form1.rh34_codarq.focus();
    location.href = 'pes2_sapemitebb200lay001.php';
  }else{
    document.form1.submit();
  }
}

function js_mostra1(chave1,chave2){

  document.form1.rh34_codarq.value = chave1;
  document.form1.submit();
  db_iframe_rharqbanco.hide();
}

function js_pesquisarh34_codban(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{

    if(document.form1.rh34_codban.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
    }else{
      document.form1.db90_descr.value = '';
    }
  }
}

function js_mostradb_bancos(chave,erro){

  document.form1.db90_descr.value = chave;
  if(erro==true){

    document.form1.rh34_codban.focus();
    document.form1.rh34_codban.value = '';
  }
}

function js_mostradb_bancos1(chave1,chave2){

  document.form1.rh34_codban.value = chave1;
  document.form1.db90_descr.value  = chave2;
  db_iframe_db_bancos.hide();
}
</script>
<?
if(isset($emite2)){

  if($clrharqbanco->erro_status=="0"){

    $clrharqbanco->erro(true,false);
    $db_botao = true;
    if($clrharqbanco->erro_campo!=""){

      echo "<script> document.form1.".$clrharqbanco->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrharqbanco->erro_campo.".focus();</script>";
    }
  }else{
    echo "<script>js_emite();</script>";
  }
}
?>
