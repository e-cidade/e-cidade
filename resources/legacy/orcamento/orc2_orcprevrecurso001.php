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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("o15_codigo");
$clrotulo->label("o62_codimpger");
$clrotulo->label("o15_descr");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  recurso = document.form1.o15_codigo.value;
  mensal  = document.form1.mensal.value;
  imprime_grafico = document.form1.imprime_grafico.value;
  jan = window.open('orc2_orcprevrecurso002.php?recurso='+recurso+'&mensal='+mensal+'&imprime_grafico='+imprime_grafico,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
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
         <td>
           <?=db_ancora(@$Lo15_codigo,"js_codigo(true);",1); ?>
         </td>
         <td>
         <?
            db_input("o15_codigo",4,$Io15_codigo,true,'text',1,"onchange='js_codigo(false);'");
            db_input("o15_descr",40,$Io15_descr,true,'text',3);
         ?>
         </td>
       </tr>
      <tr >
        <td  align="left"  nowrap ><strong>Tipo :</strong>
        </td>
        <td align="left">
          <?
          $xx = array("s"=>"Bimestral","m"=>"Mensal");
          db_select('mensal',$xx,true,4,"");
          ?>
        </td>
      </tr>
      <tr >
        <td  align="left"  nowrap ><strong>Gr�fico :</strong>
        </td>
        <td align="left">
          <?
          $xx = array("N"=>"N�O","S"=>"SIM");
          db_select('imprime_grafico',$xx,true,4,"");
          ?>
        </td>
      </tr>

      <tr>
        <td align = "center" colspan='2'>
          <input  name="emite2" id="emite2" type="button" value="Emitir Relat�rio" onclick="js_emite();" >
        </td>
      </tr>

  </form>
  </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
    if( document.form1.o15_codigo.value != ''){
      js_OpenJanelaIframe('','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
     }
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave;
  if(erro==true){
    document.form1.o15_codigo.focus();
    document.form1.o15_codigo.value = '';
    return false;
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.o15_codigo.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();

}

</script>