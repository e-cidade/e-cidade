<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2012  DBselller Servicos de Informatica
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
require_once("classes/db_tipcalc_classe.php");
require_once("classes/db_tipcalcexe_classe.php");
$cltipcalc    = new cl_tipcalc;
$cltipcalcexe = new cl_tipcalcexe;

db_postmemory($HTTP_POST_VARS);
  $db_opcao = 33;
  $db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cltipcalcexe->q83_codigo=$q81_codigo;
  $cltipcalcexe->excluir(null,"q83_tipcalc = $q81_codigo");

  if($cltipcalcexe->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cltipcalcexe->erro_msg;
  $cltipcalc->excluir($q81_codigo);
  if($cltipcalc->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cltipcalc->erro_msg;
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $cltipcalc->sql_record($cltipcalc->sql_query($chavepesquisa,"tipcalc.*,tabrec.k02_descr as k02_descrexe,c.k02_descr,q85_descr,a.q92_descr,q89_descr"));
   db_fieldsmemory($result,0);
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
    <center>
    	<?
    	  require_once("forms/db_frmtipcalc.php");
    	?>
    </center>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cltipcalc->erro_campo!=""){
      echo "<script> document.form1.".$cltipcalc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltipcalc->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='iss1_tipcalc003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.tipcalcexe.disabled=false;
         CurrentWindow.corpo.iframe_tipcalcexe.location.href='iss1_tipcalcexe001.php?db_opcaoal=33&q83_tipcalc=".@$q81_codigo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('tipcalcexe');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>
