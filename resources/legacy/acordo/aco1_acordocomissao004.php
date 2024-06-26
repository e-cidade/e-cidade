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
require_once("classes/db_acordocomissao_classe.php");
require_once("classes/db_acordocomissaomembro_classe.php");
require_once("libs/db_utils.php");
require_once("model/AcordoComissao.model.php");

$clacordocomissao = new cl_acordocomissao;
$clacordocomissaomembro = new cl_acordocomissaomembro;
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;

/**
 * Validamos o modulo que est� sendo acessado
 */
switch (db_getsession("DB_modulo")) {

	case 439:
		$lModuloPatrimonial = true;
		break;
  case 8251:
    $lModuloPatrimonial = false;
    break;
	case 480:
    $lModuloPatrimonial = false;
		$lModuloMaterial = true;
		break;
	default:
		$lModuloPatrimonial = false;
}

if(isset($incluir)){

  $sqlerro=false;
  db_inicio_transacao();

  $iCodigoTipoComissao = 1;
  if($lModuloMaterial){
    $iCodigoTipoComissao = 3;
  }
  if ($lModuloPatrimonial) {
    $iCodigoTipoComissao = 2;
  }

  $clacordocomissao->ac08_acordocomissaotipo = $iCodigoTipoComissao;
  $clacordocomissao->ac08_instit = db_getsession("DB_instit");
  $clacordocomissao->incluir($ac08_sequencial);
  if($clacordocomissao->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $clacordocomissao->erro_msg;
  db_fim_transacao($sqlerro);
  $ac08_sequencial= $clacordocomissao->ac08_sequencial;
  $db_opcao = 1;
  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="../../../scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC >
	<?
	include("forms/db_frmacordocomissao.php");
	?>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clacordocomissao->erro_campo!=""){
      echo "<script> document.form1.".$clacordocomissao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clacordocomissao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("aco1_acordocomissao005.php?liberaaba=true&chavepesquisa=$ac08_sequencial");
  }
}
?>
