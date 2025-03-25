<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_frmagentesnocivos.php");
include("classes/db_infoambiente_classe.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clrhpessoal = new cl_rhpessoal;
$clagentesnocivos = new cl_agentesnocivos;
$clinfoambiente = new cl_infoambiente;
$db_opcao = 22;
$db_botao = false;
$sqlerro = false;

if (isset($incluir)) {

    if ($sqlerro == false) {

        db_inicio_transacao();
        $clagentesnocivos->rh232_agente = $rh232_agente;
        $clagentesnocivos->rh232_icdexposicao = str_replace(',', '.', $rh232_icdexposicao);
        $clagentesnocivos->rh232_ltolerancia = str_replace(',', '.', $rh232_ltolerancia);
        $clagentesnocivos->rh232_epc = $rh233_epc;
        $clagentesnocivos->rh232_epceficaz = $rh233_epceficaz;
        $clagentesnocivos->rh232_epi = $rh233_epi;
        $clagentesnocivos->rh232_epieficaz = $rh233_epieficaz;
        $clagentesnocivos->rh232_epicertificado = $rh233_epicertificado;
        $clagentesnocivos->rh232_epidescricao = $rh233_epidescricao;
        $clagentesnocivos->rh232_epiporinviabilidade = $rh233_epiporinviabilidade;
        $clagentesnocivos->rh232_epiobscondicoes = $rh233_epiobscondicoes;
        $clagentesnocivos->rh232_epiobsuso = $rh233_epiobsuso;
        $clagentesnocivos->rh232_epiobsprazo = $rh233_epiobsprazo;
        $clagentesnocivos->rh232_obsperiodicidade = $rh233_obsperiodicidade;
        $clagentesnocivos->rh232_obshigienizacao = $rh233_obshigienizacao;
        $clagentesnocivos->incluir($rh232_sequencial);
        $erro_msg = $clagentesnocivos->erro_msg;
        if ($clagentesnocivos->erro_status == 0) {

            $sqlerro = true;
        }
        db_fim_transacao($sqlerro);
    }
} else if (isset($alterar)) {
    if ($sqlerro == false) {

        db_inicio_transacao();
        $clagentesnocivos->rh232_agente = $rh232_agente;
        $clagentesnocivos->rh232_icdexposicao = str_replace(',', '.', $rh232_icdexposicao);
        $clagentesnocivos->rh232_ltolerancia = str_replace(',', '.', $rh232_ltolerancia);
        $clagentesnocivos->rh232_epc = $rh233_epc;
        $clagentesnocivos->rh232_epceficaz = $rh233_epceficaz;
        $clagentesnocivos->rh232_epi = $rh233_epi;
        $clagentesnocivos->rh232_epieficaz = $rh233_epieficaz;
        $clagentesnocivos->rh232_epicertificado = $rh233_epicertificado;
        $clagentesnocivos->rh232_epidescricao = $rh233_epidescricao;
        $clagentesnocivos->rh232_epiporinviabilidade = $rh233_epiporinviabilidade;
        $clagentesnocivos->rh232_epiobscondicoes = $rh233_epiobscondicoes;
        $clagentesnocivos->rh232_epiobsuso = $rh233_epiobsuso;
        $clagentesnocivos->rh232_epiobsprazo = $rh233_epiobsprazo;
        $clagentesnocivos->rh232_obsperiodicidade = $rh233_obsperiodicidade;
        $clagentesnocivos->rh232_obshigienizacao = $rh233_obshigienizacao;
        $clagentesnocivos->alterar($rh232_sequencial);
        $erro_msg = $clagentesnocivos->erro_msg;
        if ($clagentesnocivos->erro_status == 0) {

            $sqlerro = true;
        }
        db_fim_transacao($sqlerro);
    }
} else if (isset($excluir)) {
    if ($sqlerro == false) {

        db_inicio_transacao();
        $clagentesnocivos->excluir($rh232_sequencial);
        $erro_msg = $clagentesnocivos->erro_msg;
        if ($clagentesnocivos->erro_status == 0) {

            $sqlerro = true;
        }
        db_fim_transacao($sqlerro);
    }
} else if (isset($opcao)) {

    $result = $clagentesnocivos->sql_record($clagentesnocivos->sql_query($rh232_sequencial));
    if ($result != false && $clagentesnocivos->numrows > 0) {

        db_fieldsmemory($result, 0);
    }
}

if (isset($alterar) || isset($excluir) || isset($incluir)) {
    db_msgbox($erro_msg);
    if ($clagentesnocivos->erro_campo != "") {
        echo "<script> document.form1." . $clagentesnocivos->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clagentesnocivos->erro_campo . ".focus();</script>";
    }
    db_redireciona("eso1_agentesnocivos001.php?rh232_regist=$rh232_regist");
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
                    include("forms/db_frmagentesnocivos.php");
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
    if ($clagentesnocivos->erro_campo != "") {

        echo "<script> document.form1." . $clagentesnocivos->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clagentesnocivos->erro_campo . ".focus();</script>";
    } else {

        if (isset($alterar) || isset($incluir)) {

            echo "<script> location.href=\"?rh232_regist={$rh232_regist}&opcao=alterar\";</script>";
        }
    }
}
?>