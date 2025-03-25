<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_subsidiovereadores_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clsubsidiovereadores = new cl_subsidiovereadores;

$db_botao = false;
$db_opcao = 33;

if (isset($excluir)) {

    db_inicio_transacao();

    $db_opcao = 3;

    $clsubsidiovereadores->excluir($si179_sequencial);

    db_fim_transacao();
} else if (isset($chavepesquisa)) {
    $db_opcao = 3;
    $result = $clsubsidiovereadores->sql_record($clsubsidiovereadores->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);
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

<body class="body-default">
    <div class="container">
        <table width="790" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
                    <center>
                        <?
                        include("forms/db_frmsubsidiovereadores.php");
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
if (isset($excluir)) {
    if ($clsubsidiovereadores->erro_status == "0") {
        $clsubsidiovereadores->erro(true, false);
    } else {
        $clsubsidiovereadores->erro(true, true);
    }
}
if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
    js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>