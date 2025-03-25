<?

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("classes/db_infoambiente_classe.php");
require_once("libs/db_libpessoal.php");

db_postmemory($HTTP_POST_VARS);

$clrhpessoal = new cl_rhpessoal;
$clinfoambiente = new cl_infoambiente;

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;

if (isset($incluir)) {

    db_inicio_transacao();

    $clinfoambiente->rh230_data      = $rh230_data;
    $clinfoambiente->rh230_descricao = $rh230_descricao;
    $clinfoambiente->rh230_instit    = db_getsession('DB_instit');
    $clinfoambiente->incluir($rh230_regist);

    if ($clinfoambiente->erro_status == 0) {

        $sqlerro = true;
        $erro_msg = $clinfoambiente->erro_msg;
    }

    db_fim_transacao($sqlerro);

    $rh230_regist = $clinfoambiente->rh230_regist;
}

?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, strings.js, prototype.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
                <center>
                    <?
                    include("forms/db_frminfoambiente.php");
                    ?>
                </center>
            </td>
        </tr>
    </table>
</body>

</html>
<?
if (isset($incluir)) {

    if ($sqlerro == true) {

        db_msgbox($clinfoambiente->erro_msg);

        if ($clinfoambiente->erro_campo != "") {

            echo "<script> document.form1." . $clinfoambiente->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clinfoambiente->erro_campo . ".focus();</script>";
        }
    } else {

        db_msgbox($clinfoambiente->erro_sql);
        db_redireciona("eso1_infoambiente002.php?liberaaba=true&chavepesquisa={$rh230_regist}");
    }
}
?>