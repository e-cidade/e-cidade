<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_subsidiovereadores_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clsubsidiovereadores = new cl_subsidiovereadores;

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {

    $sqlerro = false;

    db_inicio_transacao();

    if ($sqlerro = false) {

        $result = db_query("select max(si179_dataini) as ultimadata from subsidiovereadores where si179_instit = " . db_getsession("DB_instit"));
        db_fieldsmemory($result, 0);

        if ($si179_dataini <= $ultimadata) {
            db_msgbox("Usuário: \\n\\n A data de inicio da vigência não pode ser igual ou anterior a data da última vigência inserida ($ultimadata)!");
            $sqlerro = true;
        }
    }

    if ($sqlerro == false) {
        $clsubsidiovereadores->si179_instit = db_getsession("DB_instit");
        $clsubsidiovereadores->incluir($si179_sequencial);

        if ($clsubsidiovereadores->erro_status == 0) {
            $sqlerro = true;
        }
    }
    db_fim_transacao();
    $db_opcao = 1;
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
<script>
    js_tabulacaoforms("form1", "si179_sequencial", true, 1, "si179_sequencial", true);
</script>
<?
if (isset($incluir)) {
    if ($clsubsidiovereadores->erro_status == "0") {
        $clsubsidiovereadores->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($clsubsidiovereadores->erro_campo != "") {
            echo "<script> document.form1." . $clsubsidiovereadores->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clsubsidiovereadores->erro_campo . ".focus();</script>";
        };
    } else {
        $clsubsidiovereadores->erro(true, true);
    };
};
?>