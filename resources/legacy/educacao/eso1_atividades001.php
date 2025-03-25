<?

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_atividadesdesempenhadas_classe.php");
include("classes/db_infoambiente_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clrhpessoal = new cl_rhpessoal;
$clinfoambiente = new cl_infoambiente;
$clatividadesdesempenhadas = new cl_atividadesdesempenhadas;
$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if (isset($alterar) || isset($excluir) || isset($incluir)) {

    $sqlerro = false;
}

if (isset($incluir)) {

    if ($sqlerro == false) {

        db_inicio_transacao();

        $clatividadesdesempenhadas->rh231_descricao = $rh231_descricao;
        $clatividadesdesempenhadas->incluir($rh231_regist);
        $erro_msg = $clatividadesdesempenhadas->erro_msg;

        if ($clatividadesdesempenhadas->erro_status == 0) {

            $sqlerro = true;
        }

        db_fim_transacao($sqlerro);
    }
} else if (isset($alterar)) {

    if ($sqlerro == false) {

        db_inicio_transacao();

        $clatividadesdesempenhadas->rh231_descricao = $rh231_descricao;
        $clatividadesdesempenhadas->alterar($rh231_regist);
        $erro_msg = $clatividadesdesempenhadas->erro_msg;

        if ($clatividadesdesempenhadas->erro_status == 0) {

            $sqlerro = true;
        }

        $opcao = "alterar";
        db_fim_transacao($sqlerro);
    }
} else if (isset($excluir)) {

    if ($sqlerro == false) {

        db_inicio_transacao();

        $clatividadesdesempenhadas->excluir($rh231_regist);
        $erro_msg = $clatividadesdesempenhadas->erro_msg;

        if ($clatividadesdesempenhadas->erro_status == 0) {

            $sqlerro = true;
        }

        $opcao = "excluir";
        db_fim_transacao($sqlerro);
    }
} else if (isset($opcao) || isset($rh231_regist)) {

    $result = $clatividadesdesempenhadas->sql_record($clatividadesdesempenhadas->sql_query($rh231_regist));

    if ($result != false && $clatividadesdesempenhadas->numrows > 0) {

        $opcao = "alterar";
        db_fieldsmemory($result, 0);
    }
}

if (isset($alterar) || isset($excluir) || isset($incluir)) {

    db_msgbox($erro_msg);

    if ($clatividadesdesempenhadas->erro_campo != "") {

        echo "<script> document.form1." . $clatividadesdesempenhadas->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clatividadesdesempenhadas->erro_campo . ".focus();</script>";
    }

    db_redireciona("eso1_atividades001.php?rh231_regist=$rh231_regist");
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
                    include("forms/db_frmatividadesdesempenhadas.php");
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
    if ($clatividadesdesempenhadas->erro_campo != "") {

        echo "<script> document.form1." . $clatividadesdesempenhadas->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clatividadesdesempenhadas->erro_campo . ".focus();</script>";
    }
}
?>