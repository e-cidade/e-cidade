<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_frminforelativasresp.php");
include("classes/db_infoambiente_classe.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

db_postmemory($HTTP_POST_VARS);

$clrhpessoal         = new cl_rhpessoal;
$clinforelativasresp = new cl_inforelativasresp;
$clinfoambiente      = new cl_infoambiente;
$clagentesnocivos    = new cl_agentesnocivos;

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {

    $sqlerro == false;
    db_inicio_transacao();

    $rhclinforelativasresp->rh234_orgao = $rh234_orgao;
    $rsAgente = $clagentesnocivos->sql_record($clagentesnocivos->sql_query_file(null, "distinct rh232_regist", null, "rh232_agente = 92 and rh232_regist = $rh234_regist"));
    if ($clagentesnocivos->numrows = 0 && ($rhclinforelativasresp->rh234_orgao == '0' || $rhclinforelativasresp->rh234_numinscricao == '' || $rhclinforelativasresp->rh234_uf == '0')) {
        $sqlerro   = true;
        $erro_msg  = "Usuário:\\n";
        $erro_msg .= "  Não há agente nocivo cadastrado como 09.01.001.\\n";
        $erro_msg .= "  Campos: \\n";
        $erro_msg .= "  . Órgão de Classe\\n";
        $erro_msg .= "  . Número de Inscrição no Órgão de Classe\\n";
        $erro_msg .= "  . UF do Órgão de Classe são de preenchimento obrigatório.\\n\\n";
        $erro_msg .= "  Inclusão abortada!";
    }

    if ($sqlerro == false) {
        $clinforelativasresp->incluir($rh234_sequencial);
        $erro_msg = $clinforelativasresp->erro_msg;
        if ($clinforelativasresp->erro_status == 0) {

            $sqlerro = true;
        }
    }

    db_fim_transacao($sqlerro);
} else if (isset($alterar)) {

    if ($sqlerro == false) {

        db_inicio_transacao();

        $clinforelativasresp->alterar($rh234_sequencial);
        $erro_msg = $clinforelativasresp->erro_msg;
        if ($clinforelativasresp->erro_status == 0) {

            $sqlerro = true;
        }
        db_fim_transacao($sqlerro);
    }
} else if (isset($excluir)) {
    if ($sqlerro == false) {

        db_inicio_transacao();
        $clinforelativasresp->excluir($rh234_sequencial);
        $erro_msg = $clinforelativasresp->erro_msg;
        if ($clinforelativasresp->erro_status == 0) {

            $sqlerro = true;
        }
        db_fim_transacao($sqlerro);
    }
} else if (isset($opcao)) {

    $result = $clinforelativasresp->sql_record($clinforelativasresp->sql_query($rh234_sequencial));
    if ($result != false && $clinforelativasresp->numrows > 0) {

        db_fieldsmemory($result, 0);
    }
}

if (isset($alterar) || isset($excluir) || isset($incluir)) {
    db_msgbox($erro_msg);
    if ($clinforelativasresp->erro_campo != "") {
        echo "<script> document.form1." . $clinforelativasresp->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clinforelativasresp->erro_campo . ".focus();</script>";
    }
    db_redireciona("eso1_inforelativasresp001.php?rh234_regist=$rh234_regist");
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

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
                <center>
                    <?
                    include("forms/db_frminforelativasresp.php");
                    ?>
                </center>
            </td>
        </tr>
    </table>
</body>

</html>
<?php
if (isset($alterar) || isset($excluir) || isset($incluir)) {

    db_msgbox($erro_msg);
    if ($clinforelativasresp->erro_campo != "") {

        echo "<script> document.form1." . $clinforelativasresp->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clinforelativasresp->erro_campo . ".focus();</script>";
    }
}
?>