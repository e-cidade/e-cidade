<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_planosaude_classe.php");
include("classes/db_rhpessoal_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$instit                = db_getsession('DB_instit');
$clplanosaude          = new cl_planosaude();
$clrhpessoal           = new cl_rhpessoal();

$db_opcao      = 1;
$db_botao      = true;
$limpar_campos = false;
$lErro         = false;

if (isset($incluir)) {

    db_inicio_transacao();

    $clplanosaude->r75_sequencial = null;
    $clplanosaude->r75_anousu     = $r75_anousu;
    $clplanosaude->r75_mesusu     = $r75_mesusu;
    $clplanosaude->r75_regist     = $r75_regist;
    $clplanosaude->r75_cnpj       = $r75_cnpj;
    $clplanosaude->r75_ans        = str_pad($r75_ans,6,'0',STR_PAD_LEFT);
    $clplanosaude->r75_numcgm     = $r75_numcgm;
    $clplanosaude->r75_nomedependente = $r75_nomedependente;
    $clplanosaude->r75_valor      = $r75_valor;
    $clplanosaude->r75_instit     = $instit;
    $clplanosaude->incluir($r75_sequencial);


    if ($clplanosaude->erro_status == "0") {
        $lErro = true;
    }

    if (!$lErro) {
        $limpar_campos = true;
        if (isset($db_opcaoal) && $db_opcaoal == 22) {
            $clicar = "clicar";
        }
    }

    db_fim_transacao($lErro);
} elseif (isset($alterar)) {

    db_inicio_transacao();

    $clplanosaude->r75_anousu     = $r75_anousu;
    $clplanosaude->r75_mesusu     = $r75_mesusu;
    $clplanosaude->r75_regist     = $r75_regist;
    $clplanosaude->r75_numcgm     = $r75_numcgm;
    $clplanosaude->r75_cnpj       = $r75_cnpj;
    $clplanosaude->r75_ans        = str_pad($r75_ans,6,'0',STR_PAD_LEFT);
    $clplanosaude->r75_nomedependente = $r75_nomedependente;
    $clplanosaude->r75_valor      = $r75_valor;
    $clplanosaude->r75_instit     = $instit;
    $clplanosaude->alterar($r75_sequencial);

    if ($clplanosaude->erro_status == "0") {
        $lErro = true;
    }

    if ($lErro) {
        $db_opcao = "2";
    } else {
        if (isset($db_opcaoal) && $db_opcaoal == 22) {
            $clicar = "clicar";
        }
        $limpar_campos = true;
    }

    db_fim_transacao($lErro);
} else if (isset($excluir)) {

    db_inicio_transacao();

    $numcgm = null;

    if (isset($db_opcaoal) && $db_opcaoal != 33) {
        $numcgm = $r75_numcgm;
    }

    $clplanosaude->excluir($r75_sequencial);
    if ($clplanosaude->erro_status == "0") {
        $lErro = true;
    }

    if ($lErro) {
        $db_opcao = "3";
    } else {
        if (isset($db_opcaoal) && $db_opcaoal == 33) {
            unset($r75_regist, $z01_nome, $clicar);
        } else if (isset($db_opcaoal) && $db_opcaoal == 22) {
            $clicar = "clicar";
        }
        $limpar_campos = true;
    }

    db_fim_transacao($lErro);
} else if (isset($opcao)) {

    $sql = $clplanosaude->sql_query_dados(
        $r75_sequencial,
        "r75_sequencial, r75_anousu, r75_mesusu, r75_regist, z01_nome, r75_cnpj, r75_ans, r75_numcgm, r75_nomedependente, r75_valor",
        "r75_sequencial"
    );

    $rsplanosaude = $clplanosaude->sql_record($sql);

    if ($clplanosaude->numrows > 0) {
        db_fieldsmemory($rsplanosaude, 0);
    }

    if ((isset($opcao) && $opcao == "excluir") || (isset($db_opcaoal) && $db_opcaoal == 33)) {
        $db_opcao = "3";
    } else if ((isset($opcao) && $opcao == "alterar") || (isset($db_opcaoal) && ($db_opcaoal == 22 || $db_opcaoal == 11))) {
        $db_opcao = "2";
    }
} else if ((isset($r75_regist) && trim($r75_regist) != "") || isset($chavepesquisa)) {
    if (isset($chavepesquisa)) {
        $r75_regist = $chavepesquisa2;
    }
    $result_registro = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($r75_regist, "z01_nome"));
    if ($clrhpessoal->numrows > 0) {
        db_fieldsmemory($result_registro, 0);
    }
}
if ($limpar_campos == true) {
    unset($z01_nome02, $r75_numcgm, $r75_valor);
}

?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewContaBancariaServidor.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="<?= (isset($r75_regist) && trim($r75_regist) != "" && isset($r75_numcgm) && trim($r75_numcgm) != "") ? "document.form1.r75_regist.focus();" : "document.form1.r75_numcgm.focus();" ?>
">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
        <tr>
            <td width="360" height="18">&nbsp;</td>
            <td width="263">&nbsp;</td>
            <td width="25">&nbsp;</td>
            <td width="140">&nbsp;</td>
        </tr>
    </table>
    <?
    include("forms/db_frmplanosaude.php");

    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {
    if ($clplanosaude->erro_status == "0" && !isset($sqlerro)) {
        $clplanosaude->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>";
        if ($clplanosaude->erro_campo != "") {
            echo "<script> document.form1." . $clplanosaude->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clplanosaude->erro_campo . ".focus();</script>";
        };
    } else if (isset($sqlerro)) {
        db_msgbox($erro_msg);
    };
};
if (isset($db_opcaoal) && ($db_opcaoal == 22 || $db_opcaoal == 33) && !isset($clicar) && (!isset($sqlerro) || (isset($sqlerro) && $sqlerro == false))) {
    echo "<script> document.form1.pesquisar.click();</script>";
}
?>