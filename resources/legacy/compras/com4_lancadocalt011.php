<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label("pc74_codigo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
   location.href = "com4_lancadocalt001.php?chavepesquisa="+document.form1.pc74_codigo.value;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
         <td nowrap title="<?=@$Tpc74_codigo?>" align="right">
           <?
           db_ancora(@ $Lpc74_codigo, "js_pesquisapc74_codigo(true);", 1);
           ?>
         </td>
         <td>
           <?
           db_input('pc74_codigo', 4, $Ipc74_codigo, true, 'text', 1, " onchange='js_pesquisapc74_codigo(false);'");

           ?>
         </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>
  </form>
</table>
<?

 db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisapc74_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcfornecertif','func_pcfornecertifalt.php?funcao_js=parent.js_mostracgm1|pc74_codigo','Pesquisa',true);
  }else{
     if(document.form1.pc74_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcfornecertif','func_pcfornecertifalt.php?pesquisa_chave='+document.form1.pc74_codigo.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
     }
  }
}
function js_mostracgm(erro,chave){
  if(erro==true){
    document.form1.pc74_codigo.focus();
    document.form1.pc74_codigo.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.pc74_codigo.value = chave1;
  db_iframe_pcfornecertif.hide();
}
</script>
