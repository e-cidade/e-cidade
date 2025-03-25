<?

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhagente_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST);
$clrhagente = new cl_rhagente;
$clrhagente->rotulo->label("rh233_sequencial");
$clrhagente->rotulo->label("rh233_codigo");
$clrhagente->rotulo->label("rh233_descricao");
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
            <td height="63" align="center" valign="top">
                <table width="35%" border="0" align="center" cellspacing="0">
                    <form name="form2" method="post" action="">

                        <tr>
                            <td width="4%" align="right" nowrap title="<?php echo $Trh233_codigo; ?>">
                                <?php echo $Lrh233_codigo; ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?php db_input("rh233_codigo", 10, $Irh233_codigo, true, "text", 4, "", "chave_rh233_codigo"); ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="4%" align="right" nowrap title="<?php echo $Trh233_descricao; ?>">
                                <?php echo $Lrh233_descricao; ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?php db_input("rh233_descricao", 10, $Irh233_descricao, true, "text", 4, "", "chave_rh233_descricao"); ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center">
                                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                                <input name="limpar" type="reset" id="limpar" value="Limpar">
                                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhagente.hide();">
                            </td>
                        </tr>
                    </form>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <?php

                if (!isset($pesquisa_chave)) {

                    if (isset($campos) == false) {
                        if (file_exists("funcoes/db_func_rhagente.php") == true) {
                            include("funcoes/db_func_rhagente.php");
                        } else {
                            $campos = "rhagente.*";
                        }
                    }
                    if (isset($chave_rh233_codigo) && (trim($chave_rh233_codigo) != "")) {
                        $sql = $clrhagente->sql_query($chave_rh233_codigo, $campos, "rh233_codigo");
                    } else if (isset($chave_rh233_descricao) && (trim($chave_rh233_descricao) != "")) {
                        $sql = $clrhagente->sql_query("", $campos, "rh233_descricao", " rh233_descricao like '$chave_rh233_descricao%' ");
                    } else {
                        $sql = $clrhagente->sql_query("", $campos, "rh233_codigo", "");
                    }
                    db_lovrot($sql, 15, "()", "", $funcao_js);
                } else {
                    if ($pesquisa_chave != null && $pesquisa_chave != "") {
                        $result = $clrhagente->sql_record($clrhagente->sql_query(null, "*", null, "rh233_codigo = '" . $pesquisa_chave . "'"));
                        if ($clrhagente->numrows != 0) {
                            db_fieldsmemory($result, 0);
                            echo "<script>" . $funcao_js . "('$rh233_descricao','$rh233_sequencial',false);</script>";
                        } else {
                            echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
                        }
                    } else {
                        echo "<script>" . $funcao_js . "('',false);</script>";
                    }
                }
                ?>
            </td>
        </tr>
    </table>
</body>

</html>
<?
if (!isset($pesquisa_chave)) {
?>
    <script>
    </script>
<?
}
?>
<script>
    js_tabulacaoforms("form2", "chave_rh233_sequencial", true, 1, "chave_rh233_sequencial", true);
</script>