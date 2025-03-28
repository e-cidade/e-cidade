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
include("classes/db_orcimpactomov_classe.php");
include("classes/db_orcimpactomovimp_classe.php");
include("classes/db_orcimpactomovpai_classe.php");
include("classes/db_orcimpacto_classe.php");

$clorcimpactomov = new cl_orcimpactomov;
$clorcimpactomovimp = new cl_orcimpactomovimp;
$clorcimpactomovpai = new cl_orcimpactomovpai;
$clorcimpacto = new cl_orcimpacto;

include("classes/db_orcdotacao_classe.php");
include("classes/db_orcdotacaocontr_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_orcfuncao_classe.php");
include("classes/db_orcsubfuncao_classe.php");
include("classes/db_orcprograma_classe.php");
include("classes/db_orcprojativ_classe.php");
include("classes/db_orcproduto_classe.php");
include("classes/db_orcimpactoperiodo_classe.php");

$clorcprojativ = new cl_orcprojativ;
$clorcdotacao = new cl_orcdotacao;
$clorcdotacaocontr = new cl_orcdotacaocontr;
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clorcfuncao = new cl_orcfuncao;
$clorcsubfuncao = new cl_orcsubfuncao;
$clorcprograma = new cl_orcprograma;
$clorcproduto = new cl_orcproduto;
$clorcimpactoperiodo = new cl_orcimpactoperiodo;


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);



$db_botao = true;
if(empty($o63_codperiodo)){
  $result=$clorcimpactoperiodo->sql_record($clorcimpactoperiodo->sql_query_file(null,"o96_codperiodo as o63_codperiodo,o96_descr||' '||o96_anoini||'-'||o96_anofim","o96_codperiodo desc"));
  $numrows = $clorcimpactoperiodo->numrows;
  if($numrows==0){
    db_msgbox("Cadastre o  per�odo para Impacto Or�ament�rio.");
  }else{
    db_fieldsmemory($result,0);
  }
}

if(isset($incluir) || isset($alterar) || isset($atualizar)){
  $result=$clorcimpactoperiodo->sql_record($clorcimpactoperiodo->sql_query_file($o63_codperiodo,"o96_anoini"));
  db_fieldsmemory($result,0);

  $dbwhere  = " o63_codperiodo  =  $o63_codperiodo and o63_anoexe = $o96_anoini and o63_orgao = $o63_orgao ";
  $dbwhere .= " and o63_unidade = $o63_unidade     and o63_funcao = $o63_funcao and o63_subfuncao=$o63_subfuncao ";
  $dbwhere .= " and o63_programa= $o63_programa    and o63_acao   = $o63_acao";
}

if(isset($incluir) || isset($atualizar)){
  $sqlerro=false;
  db_inicio_transacao();

  if(isset($incluir)){
      $clorcimpactomov->sql_record($clorcimpactomov->sql_query_file(null,"o63_codimpmov","",$dbwhere));
      if($clorcimpactomov->numrows>0){
	$sqlerro  = true;
	$erro_msg = "J� existe...";
	$jaexiste=true;
      }
  }

  if($sqlerro == false){
    $clorcimpactomov->o63_anoexe = $o96_anoini;
    $clorcimpactomov->incluir(null);
    $erro_msg = $clorcimpactomov->erro_msg;
    if($clorcimpactomov->erro_status==0){
      $sqlerro=true;
    }else{
      $o63_codimpmov = $clorcimpactomov->o63_codimpmov;
    }
  }

  //---------------------------------------------------------------------------
  //Quando for incluir um novo
    if($sqlerro == false && (isset($incluir) || isset($atualizar) && isset($o86_codimpmovpai))){
      if(isset($incluir)){
         $pai = $o63_codimpmov;
      }else{
	$pai = $o86_codimpmovpai;
      }

      $clorcimpactomovpai->o86_codmovpai = $pai;
      $clorcimpactomovpai->o86_codmovfilho = $o63_codimpmov;
      $clorcimpactomovpai->incluir($pai,$o63_codimpmov);
      $erro_msg = $clorcimpactomovpai->erro_msg;
      if($clorcimpactomovpai->erro_status==0){
	$sqlerro=true;
      }
    }
  //---------------------------------------------------------------------------
  //---------------------------------------------------------------------------
  //Quando for incluir um, a partir de uma previs�o
    if(isset($atualizar)  && isset($o90_codimp)  && $sqlerro == false){
      $clorcimpactomovimp->o68_codimpmov = $o63_codimpmov;
      $clorcimpactomovimp->o68_codimp    = $o90_codimp;
      $clorcimpactomovimp->incluir($o63_codimpmov,$o90_codimp);
      $erro_msg = $clorcimpactomovimp->erro_msg;
      if($clorcimpactomovimp->erro_status==0){
	$sqlerro=true;
      }
    }
  //---------------------------------------------------------------------------
  db_fim_transacao($sqlerro);
  if($sqlerro == false){
     $liberaaba = true;
     $db_opcao = 2;
  }else{
    unset($o63_codimpmov);
    if(isset($atualizar)){///significa que eh de previs�o
      $db_opcao=22;
    }else{
      $db_opcao = 1;
    }
  }
  if(isset($incluir)){
    $tipo = "I";
  }
  $db_botao = true;
}else if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clorcimpactomov->sql_record($clorcimpactomov->sql_query_file(null,"o63_codimpmov","",$dbwhere));
  if($clorcimpactomov->numrows>1){
    $sqlerro  = true;
    $erro_msg = "J� existe...";
    $jaexiste=true;
  }
  if($sqlerro == false){
    $clorcimpactomov->alterar($o63_codimpmov);
    if($clorcimpactomov->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clorcimpactomov->erro_msg;
  }
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}

if(isset($chavepesquisa)|| (isset($chave_nova) && $chave_nova != '')){

   if(isset($chave_nova)){
     $db_opcao = 1;
     $o63_codimpmov='';
     $chavepesquisa = $chave_nova;
   }else{
     $db_opcao = 2;
     $db_botao = true;
   }

   if($tipo == "I"){
      $result = $clorcimpactomov->sql_record($clorcimpactomov->sql_query_compl($chavepesquisa));
      db_fieldsmemory($result,0);
      $o86_codimpmovpai= $o63_codimpmov;
      unset($o63_codimpmov);
   }else if($tipo == "P"){

      $result = $clorcimpacto->sql_record($clorcimpacto->sql_query_compl($chavepesquisa));
      db_fieldsmemory($result,0);
      $o90_codimp = $o90_codimp;
      $o63_orgao = $o90_orgao;
      $o63_unidade = $o90_unidade;
      $o63_funcao = $o90_funcao;
      $o63_subfuncao = $o90_subfuncao;
      $o63_programa = $o90_programa;
      $o63_programatxt = $o90_programatxt;
      $o63_acao = $o90_acao;
      $o63_acaotxt = $o90_acaotxt;
      $o63_produto = $o90_produto;
      $o63_unimed = $o90_unimed;
   }

   if(empty($chave_nova)){
        $db_opcao = 22;
        //$db_botao = false;
   }

   if(isset($chave_nova)){
        unset($o63_codimpmov);
   }
}else if(empty($incluir) && empty($alterar) && empty($atualizar)){
  if(isset($o63_codimpmov) && $o63_codimpmov !=''){
    $db_opcao = 2;
  }else{
    $db_opcao = 1;
  }
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="300" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmorcimpactomov.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($atualizar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
}
}
if(isset($atualizar) || isset($incluir) || (isset($chavepesquisa) && empty($chave_nova) && $tipo == "I" )){

 echo "
  <script>
         parent.document.formaba.orcimpactovalmov.disabled=false;";

	$tipo= '';
	if(isset($o86_codimpmovpai) && $o86_codimpmovpai != ''  && empty($chave_nova)){
	    $tipo= "&tipo=I";
	}else if(isset($o90_codimp) && $o90_codimp != '' && empty($chave_nova)){
	    $tipo ="&tipo=P";
	}

 echo "  CurrentWindow.corpo.iframe_orcimpactovalmov.location.href='orc1_orcimpactovalmov001.php?o64_codimpmov=".@$o63_codimpmov."$tipo';";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('orcimpactovalmov');";
         }
 echo "</script>\n";
}
?>
