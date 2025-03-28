<?
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_cgm_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cliptubase = new cl_iptubase;
$clissbase  = new cl_issbase;
$clcgm      = new cl_cgm;
$db_opcao=1;
$db_botao=1;
$cliptubase->rotulo->label();
$clissbase->rotulo->label("q02_inscr");
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/libJsonJs.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC onLoad="document.form1.elements[0].focus();">


<script>
function js_testacamp(){
  var matri = document.form1.j01_matric.value;
  var inscr = document.form1.q02_inscr.value;
  var numcgm = document.form1.z_numcgm.value;
  if(matri=="" && inscr=="" && numcgm==""){
    alert("Informe um campo para pesquisa!");
    return false;
  }
  return true;
}
</script>



<form class="container" name="form1" method="post" action="div1_divida004.php?pri=true" onSubmit="return js_verifica_campos_digitados();" >
<fieldset>
  <legend> Cadastro de D�vida </legend>
   <table class="form-container">
      <tr>
        <td>
          <?
            db_ancora($Lz01_nome,' js_cgm(true); ',1);
          ?>
        </td>
        <td>
          <?
            db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'","z_numcgm");
            db_input('z01_nome',60,0,true,'text',3,"","z01_nomecgm");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?
            db_ancora($Lj01_matric,' js_matri(true); ',1);
          ?>
        </td>
        <td>
          <?
            db_input('j01_matric',10,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
            db_input('z01_nome',60,0,true,'text',3,"","z01_nomematri");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?
            db_ancora($Lq02_inscr,' js_inscr(true); ',1);
          ?>
        </td>
        <td>
          <?
            db_input('q02_inscr',10,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
            db_input('z01_nome',60,0,true,'text',3,"","z01_nomeinscr");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input style="margin-top: 10px;" type="submit" name="pesquisar" value="Pesquisar" onclick="return js_testacamp();" >
</form>



<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatri|j01_matric|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe3','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){
  document.form1.z01_nomematri.value = chave;
  if(erro==true){
    document.form1.j01_matric.focus();
    document.form1.j01_matric.value = '';
  }
}


function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave;
  if(erro==true){
    document.form1.q02_inscr.focus();
    document.form1.q02_inscr.value = '';
  }
}


function js_cgm(mostra){
  var cgm=document.form1.z_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe2','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe2.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave;
  if(erro==true){
    document.form1.z_numcgm.focus();
    document.form1.z_numcgm.value = '';
  }
}

</script>
<?
if(isset($dado) && $dado=="inscr"){
  db_msgbox("Inscri��o inv�lida.");
}
if(isset($dado) && $dado=="matric"){
  db_msgbox("Matr�cula inv�lida.");
}
if(isset($dado) && $dado=="numcgm"){
  db_msgbox("NUMCGM inv�lido.");
}
?>
<script>

$("z_numcgm").addClassName("field-size2");
$("z01_nomecgm").addClassName("field-size9");
$("j01_matric").addClassName("field-size2");
$("z01_nomematri").addClassName("field-size9");
$("q02_inscr").addClassName("field-size2");
$("z01_nomeinscr").addClassName("field-size9");

</script>
