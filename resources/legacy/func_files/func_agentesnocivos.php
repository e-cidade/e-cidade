<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_agentesnocivos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clagentesnocivos = new cl_agentesnocivos;
$clagentesnocivos->rotulo->label("rh232_regist");
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
                            <td width="4%" align="right" nowrap title="<?= $Trh232_sequencial ?>">
                                <?= $Lrh232_sequencial ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?
                                db_input("rh232_sequencial", 10, $Irh232_sequencial, true, "text", 4, "", "chave_rh232_sequencial");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="4%" align="right" nowrap title="<?= $Trh232_regist ?>">
                                <?= $Lrh232_regist ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?
                                db_input("rh232_regist", 40, $Irh232_regist, true, "text", 4, "", "chave_rh232_regist");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                                <input name="limpar" type="reset" id="limpar" value="Limpar">
                                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_agentesnocivos.hide();">
                            </td>
                        </tr>
                    </form>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
                <?
                if (!isset($pesquisa_chave)) {
                    if (isset($campos) == false) {
                        if (file_exists("funcoes/db_func_agentesnocivos.php") == true) {
                            include("funcoes/db_func_agentesnocivos.php");
                        } else {
                            $campos = "agentesnocivos.*";
                        }
                    }
                    if (isset($chave_rh232_sequencial) && (trim($chave_rh232_sequencial) != "")) {
                        $sql = $clagentesnocivos->sql_query($chave_rh232_sequencial, $campos, "rh232_sequencial");
                    } else if (isset($chave_rh232_regist) && (trim($chave_rh232_regist) != "")) {
                        $sql = $clagentesnocivos->sql_query("", $campos, "rh232_regist", " rh232_regist like '$chave_rh232_regist%' ");
                    } else {
                        $sql = $clagentesnocivos->sql_query("", $campos, "rh232_sequencial", "");
                    }
                    db_lovrot($sql, 15, "()", "", $funcao_js);
                } else {
                    if ($pesquisa_chave != null && $pesquisa_chave != "") {
                        $result = $clagentesnocivos->sql_record($clagentesnocivos->sql_query($pesquisa_chave));
                        if ($clagentesnocivos->numrows != 0) {
                            db_fieldsmemory($result, 0);
                            echo "<script>" . $funcao_js . "('$rh232_regist',false);</script>";
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