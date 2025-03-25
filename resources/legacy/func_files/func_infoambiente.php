<?

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_infoambiente_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clinfoambiente = new cl_infoambiente;

$clinfoambiente->rotulo->label("rh230_regist");

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
                            <td width="4%" align="right" nowrap title="Matricula: ">
                                <b>Matricula: </b>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?
                                db_input("rh230_regist", 10, $Irh230_regist, true, "text", 4, "", "chave_rh230_regist");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <br>
                            <td colspan="2" align="center">
                                <br>
                                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                                <input name="limpar" type="reset" id="limpar" value="Limpar">
                                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_infoambiente.hide();">
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
                        $campos = "infoambiente.rh230_regist, cgm.z01_nome, infoambiente.rh230_data, infoambiente.rh230_descricao, db_config.nomeinst";
                    }
                    if (isset($chave_rh230_regist) && (trim($chave_rh230_regist) != "")) {
                        $sql = $clinfoambiente->sql_query($chave_rh230_regist, $campos, "rh230_regist", "rh230_instit = " . db_getsession('DB_instit'));
                    } else {
                        $sql = $clinfoambiente->sql_query("", $campos, "rh230_regist", "rh230_instit = " . db_getsession('DB_instit'));
                    }
                    db_lovrot($sql, 15, "()", "", $funcao_js);
                } else {
                    if ($pesquisa_chave != null && $pesquisa_chave != "") {
                        $result = $clinfoambiente->sql_record($clinfoambiente->sql_query($pesquisa_chave, $campos, "rh230_regist", "rh230_instit = " . db_getsession('DB_instit')));
                        if ($clinfoambiente->numrows != 0) {
                            db_fieldsmemory($result, 0);
                            echo "<script>" . $funcao_js . "('$rh230_regist',false);</script>";
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