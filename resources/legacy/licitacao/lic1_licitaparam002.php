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
include("classes/db_licitaparam_classe.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_libdicionario.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cllicitaparam = new cl_licitaparam;

$db_opcao = 22;

$db_botao = false;
if (isset($alterar)) {
    db_inicio_transacao();

  $result = $cllicitaparam->sql_record($cllicitaparam->sql_query(DB_getsession("DB_instit")));
  if ($result == false || $cllicitaparam->numrows == 0) {
    $cllicitaparam->l12_validacadfornecedor = $l12_validacadfornecedor;
    $cllicitaparam->l12_escolherprocesso = $l12_escolherprocesso;
    $cllicitaparam->l12_escolheprotocolo = $l12_escolheprotocolo;
    $cllicitaparam->l12_qtdediasliberacaoweb = $l12_qtdediasliberacaoweb;
    $cllicitaparam->l12_tipoliberacaoweb = $l12_tipoliberacaoweb;
    $cllicitaparam->l12_usuarioadjundica = $l12_usuarioadjundica;
    $cllicitaparam->l12_validacadfornecedor = $l12_validacadfornecedor;
    $cllicitaparam->l12_pncp = $l12_pncp;
    $cllicitaparam->l12_numeracaomanual = $l12_numeracaomanual;
    $cllicitaparam->l12_validafornecedor_emailtel = $l12_validafornecedor_emailtel;
    $cllicitaparam->l12_acessoapipcp = $l12_acessoapipcp;
    $cllicitaparam->l12_adjudicarprocesso = $l12_adjudicarprocesso;
    $cllicitaparam->l12_instit = DB_getsession("DB_instit");
    $cllicitaparam->incluir(DB_getsession("DB_instit"));
  } else {
    $cllicitaparam->l12_validacadfornecedor = $l12_validacadfornecedor;
    $cllicitaparam->l12_escolherprocesso = $l12_escolherprocesso;
    $cllicitaparam->l12_escolheprotocolo = $l12_escolheprotocolo;
    $cllicitaparam->l12_qtdediasliberacaoweb = $l12_qtdediasliberacaoweb;
    $cllicitaparam->l12_tipoliberacaoweb = $l12_tipoliberacaoweb;
    $cllicitaparam->l12_usuarioadjundica = $l12_usuarioadjundica;
    $cllicitaparam->l12_validacadfornecedor = $l12_validacadfornecedor;
    $cllicitaparam->l12_pncp = $l12_pncp;
    $cllicitaparam->l12_numeracaomanual = $l12_numeracaomanual;
    $cllicitaparam->l12_validafornecedor_emailtel = $l12_validafornecedor_emailtel;
    $cllicitaparam->l12_acessoapipcp = $l12_acessoapipcp;
    $cllicitaparam->l12_adjudicarprocesso = $l12_adjudicarprocesso;
    $cllicitaparam->l12_instit = DB_getsession("DB_instit");
    $cllicitaparam->alterar(db_getsession("DB_instit"));
  }
  db_fim_transacao();
}
$db_opcao = 2;

$result = $cllicitaparam->sql_record($cllicitaparam->sql_query(db_getsession("DB_instit")));


if ($result != false && $cllicitaparam->numrows > 0) {
  db_fieldsmemory($result, 0);
} else {
  $l12_tipoliberacaoweb = 1;
}

$db_botao = true;
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
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
        <center>
          <?
          include("forms/db_frmlicitaparam.php");
          ?>
        </center>
      </td>
    </tr>
  </table>
  <?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>

</html>
<?
if (isset($alterar)) {
  if ($cllicitaparam->erro_status == "0") {
    $cllicitaparam->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($cllicitaparam->erro_campo != "") {
      echo "<script> document.form1." . $cllicitaparam->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $cllicitaparam->erro_campo . ".focus();</script>";
    }
  } else {
    $cllicitaparam->erro(true, true);
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>