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
include("classes/db_aguabase_classe.php");
include("classes/db_aguabasecar_classe.php");
include("classes/db_caracter_classe.php");

$claguabase = new cl_aguabase;
$claguabasecar = new cl_aguabasecar;
$clcaracter = new cl_caracter;

db_postmemory($HTTP_POST_VARS);

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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>

</table>

<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
	<?
	include("forms/db_frmaguabase_alteracao_serie.php");
	?>
    </center>
    </td>
  </tr>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</table>
</body>
</html>
<?

//
// mostra progresso no processamento
//
function termo($msg, $reg=0, $tot=0) {
	$_msg = $msg;
	if($reg > 0 || $tot > 0) {
		$perc = round(($reg/$tot)*100,1);
		$_msg .= " " . $perc . "%";
	} else {
		$_msg .= " ...";
	}
	echo "<script>js_termo('$_msg');</script>";
	flush();
}



if(isset($processa)) {

	if(empty($x01_codrua)) {
		echo "<script>alert('E necessario selecionar um Logradouro!');</script>";
		exit;
	}

	$sql = "
		select  aguabase.x01_matric,
		        aguabase.x01_numero,
		        aguabasecar.x30_codigo,
				caracter.j31_grupo
		from    aguabase
		inner join aguabasecar on aguabasecar.x30_matric = aguabase.x01_matric
		inner join caracter    on caracter.j31_codigo    = aguabasecar.x30_codigo
		inner join cargrup     on cargrup.j32_grupo      = caracter.j31_grupo
		where   aguabase.x01_codrua = $x01_codrua
		and     aguabase.x01_numero between $x99_numero_inicial and $x99_numero_final
		and     caracter.j31_grupo  in (82, 83)";

	$result = pg_query($sql);
	$rows = pg_num_rows($result);

	if($rows == 0) {
		termo("Nenhum registro encontrado com base nos criterios selecionados");
		die();
	}

	//termo("Processando alteracao", 1, $rows);
	//db_criatermometro('termo_alteracao_serie', 'Processamento Concluido com Sucesso');

	db_inicio_transacao();
  $erro_sql = false;
	for($x = 0; $x < $rows; $x++) {
		//termo("Processando alteracao", ($x+1), $rows);
		db_atutermometro($x, $rows, 'termo_alteracao_serie');

		db_fieldsmemory($result, $x);

		$claguabasecar->excluir($x01_matric, $x30_codigo);

		if($j31_grupo == 82) {
			$claguabasecar->incluir($x01_matric, $x99_caresgoto);
		} else {
			$claguabasecar->incluir($x01_matric, $x99_caragua);
		}
	}

	db_fim_transacao($erro_sql);

	//termo("Processamento finalizado com sucesso");

	unset($processa);
}
?>
