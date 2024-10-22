<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("classes/db_planosaude_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$sWhere   = "";
$sAnd     = "";

$clplanosaude = new cl_planosaude;
$clplanosaude->rotulo->label("r75_anousu");
$clplanosaude->rotulo->label("r75_mesusu");
$clplanosaude->rotulo->label("r75_regist");
$clplanosaude->rotulo->label("r75_numcgm");
$clplanosaude->rotulo->label("z01_nome");

if (!isset($chave_r75_mesusu)) {
    $chave_r75_mesusu = db_mesfolha();
}

if (!isset($chave_r75_anousu)) {
    $chave_r75_anousu = db_anofolha();
}

if (isset($valor_testa_rescisao)) {

    $chave_r75_regist = $valor_testa_rescisao;
    $retorno          = db_alerta_dados_func($testarescisao, $valor_testa_rescisao, db_anofolha(), db_mesfolha());
    if ($retorno != "") {
        db_msgbox($retorno);
    }
}
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="../../../FrontController.php" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="../../../scripts/scripts.js"></script>
    <?
    if (!isset($pesquisa_chave)) {
    ?>
        <script>
            function js_recebe_click(value) {
                obj = document.createElement('input');
                obj.setAttribute('type', 'hidden');
                obj.setAttribute('name', 'funcao_js');
                obj.setAttribute('id', 'funcao_js');
                obj.setAttribute('value', '<?= $funcao_js ?>');
                document.form2.appendChild(obj);

                obj = document.createElement('input');
                obj.setAttribute('type', 'hidden');
                obj.setAttribute('name', 'valor_testa_rescisao');
                obj.setAttribute('id', 'valor_testa_rescisao');
                obj.setAttribute('value', value);
                document.form2.appendChild(obj);

                document.form2.submit();
            }
        </script>
    <?
    }
    ?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
            <td height="63" align="center" valign="top">
                <table width="35%" border="0" align="center" cellspacing="0">
                    <form name="form2" method="post" action="">
                        <tr>
                            <td align="right" nowrap title="Digite o Ano / Mes de competência">
                                <strong>Ano / Mês:</strong>
                            </td>
                            <td nowrap>
                                <?
                                db_input('r75_anousu', 4, $Ir75_anousu, true, 'text', 4, '', "chave_r75_anousu");
                                ?>
                                &nbsp;/&nbsp;
                                <?
                                db_input('r75_mesusu', 2, $Ir75_mesusu, true, 'text', 4, '', "chave_r75_mesusu");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="4%" align="right" nowrap title="<?= $Tr75_regist ?>">
                                <?= $Lr75_regist ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?
                                db_input("r75_regist", 10, $Ir75_regist, true, "text", 4, "", "chave_r75_regist");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="4%" align="right" nowrap title="<?= $Tr75_numcgm ?>">
                                <?= $Lr75_numcgm ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?
                                db_input("r75_numcgm", 10, $Ir75_numcgm, true, "text", 4, "", "chave_r75_numcgm");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                                <input name="limpar" type="reset" id="limpar" value="Limpar">
                                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_planosaude.hide();">
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

                        if (file_exists("funcoes/db_func_planosaude.php") == true) {
                            include("funcoes/db_func_planosaude.php");
                        } else {
                            $campos = "r75_sequencial, r75_anousu, r75_mesusu, r75_regist, z01_nome, r75_cnpj, r75_ans, r75_numcgm, r75_dependente, r75_valor, r75_instit";
                        }
                    }

                    if (isset($chave_r75_anousu) && !empty($chave_r75_anousu)) {

                        $sWhere .= " {$sAnd} planosaude.r75_anousu = {$chave_r75_anousu}";
                        $sAnd    = " and ";
                    }

                    if (isset($chave_r75_mesusu) && !empty($chave_r75_mesusu)) {

                        $sWhere .= " {$sAnd} planosaude.r75_mesusu = {$chave_r75_mesusu}";
                        $sAnd    = " and ";
                    }

                    if (isset($chave_r75_regist) && !empty($chave_r75_regist)) {

                        $sWhere .= " {$sAnd} planosaude.r75_regist = {$chave_r75_regist}";
                        $sAnd    = " and ";
                    }

                    if (isset($chave_r75_numcgm) && !empty($chave_r75_numcgm)) {

                        $sWhere .= " {$sAnd} planosaude.r75_numcgm = {$chave_r75_numcgm}";
                        $sAnd    = " and ";
                    }

                    if (isset($chave_r75_regist) && (trim($chave_r75_regist) != "")) {
                        $sSqlPlanoSaude = $clplanosaude->sql_query_dados(null, $campos, "r75_mesusu", $sWhere);
                    } else if (isset($chave_r75_numcgm) && (trim($chave_r75_numcgm) != "")) {

                        $sWhere     = " r75_numcgm like '{$chave_r75_numcgm}%' {$sAnd} {$sWhere} ";
                        $sSqlPlanoSaude = $clplanosaude->sql_query_dados(null, $campos, "r75_numcgm", $sWhere);
                    } else {

                        $sOrderBy   = "r75_sequencial";
                        $sSqlPlanoSaude = $clplanosaude->sql_query_dados(null, $campos, $sOrderBy, $sWhere);
                    }

                    db_lovrot($sSqlPlanoSaude, 15, "()", "", (isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|r75_regist" : $funcao_js));
                } else {

                    if ($pesquisa_chave != null && $pesquisa_chave != "") {

                        $sSqlPlanoSaude  = $clplanosaude->sql_query_dados(null, $pesquisa_chave);
                        $rsSqlPlanoSaude = $clplanosaude->sql_record($sSqlPlanoSaude);
                        if ($clplanosaude->numrows != 0) {

                            db_fieldsmemory($rsSqlPlanoSaude, 0);

                            echo "<script>" . $funcao_js . "('$r75_numcgm',false);</script>";
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
