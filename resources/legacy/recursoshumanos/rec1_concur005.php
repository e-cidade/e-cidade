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
include("classes/db_concur_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clconcur = new cl_concur;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clconcur->alterar($h06_refer);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clconcur->sql_record($clconcur->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmconcur.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($clconcur->erro_status=="0"){
    $clconcur->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clconcur->erro_campo!=""){
      echo "<script> document.form1.".$clconcur->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconcur->erro_campo.".focus();</script>";
    }
  }
}
if(isset($chavepesquisa)){
  echo "
        <script>
          parent.document.formaba.concurcargo.disabled = false;
          CurrentWindow.corpo.iframe_concurcargo.location.href = 'rec1_concurcargo001.php?chavepesquisa=$h06_refer&db_opcaoal=true';
       ";
  if(isset($liberaaba) && $liberaaba=="true"){
    echo "
          CurrentWindow.corpo.mo_camada('concurcargo');
         ";
  }
  echo "
  </script>
       ";
}else{
  echo "
        <script>
       ";
  if(isset($alterar)){
    echo "
          parent.document.formaba.concurcargo.disabled = false;
          CurrentWindow.corpo.iframe_concurcargo.location.href = 'rec1_concurcargo001.php?chavepesquisa=$h06_refer&db_opcaoal=true';
         ";
    if($sqlerro == false){
      echo "
            CurrentWindow.corpo.mo_camada('concurcargo');
           ";
    }
  }else{
    echo "
          parent.document.formaba.concurcargo.disabled = true;
          CurrentWindow.corpo.iframe_concurcargo.location.href = 'rec1_concurcargo001.php';
         ";
  }
  echo "
        </script>
       ";
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","h06_concur",true,1,"h06_concur",true);
</script>
