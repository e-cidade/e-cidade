<?

$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_nome");

if ($db_opcao == 1) {
    $db_action = "eso1_infoambiente001.php";
    $mostra = 1;
} else if ($db_opcao == 2) {
    $db_action = "eso1_infoambiente002.php";
    $mostra = 3;
} else if ($db_opcao == 3) {
    $db_action = "eso1_infoambiente003.php";
    $mostra = 3;
}

?>

<form name="form1" method="post" action="<?= $db_action ?>">
    <input type="hidden" value="1">
    <table align="center" border="0" cellspacing="4" cellpadding="0">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td nowrap title="<?= @$Trh01_regist ?>">
                <?
                db_ancora(@$Lrh01_regist, 'js_pesquisarh230_regist(true);', ($db_opcao == 1 ? 1 : 3));
                ?>
            </td>
            <td nowrap>
                <?
                db_input('rh230_regist', 6, $Irh230_regist, true, 'text', ($db_opcao == 1 ? 1 : 3), " onchange='js_pesquisarh230_regist(false);'");
                ?>
                <?
                db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
                ?>
            </td>
        </tr>

    </table>
    <table border='0'>
        <tr>
            <td>
                <fieldset>
                    <legend align="left"><b>INFORMAÇÕES DO AMBIENTE </b></legend>
                    <table>
                        <tr>
                            <td nowrap title="<?= @$Trh01_regist ?>">
                                <strong>Data de Início da Exposição: </strong>
                            </td>
                            <td nowrap>
                                <?
                                db_inputdata('rh230_data', @$rh230_data_dia, @$rh230_data_mes, @$rh230_data_ano, true, 'text', $db_opcao);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td nowrap title="<?= @$Trh01_regist ?>">
                                <strong>Descrição do Ambiente: </strong>
                            </td>
                            <td nowrap>
                                <?
                                db_textarea('rh230_descricao', 10, 100, $Irh230_descricao, true, 'text', $db_opcao);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
    <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>

<script>
    function js_pesquisarh230_regist(mostra) {

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_rhpessoal', 'func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome', 'Pesquisa', true, '0');
        } else {
            if (document.form1.rh230_regist.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_rhpessoal', 'func_rhpessoal.php?pesquisa_chave=' + document.form1.rh230_regist.value + '&funcao_js=parent.js_mostrarhpessoal', 'Pesquisa', false, '0');
            } else {
                document.form1.z01_nome.value = '';
            }
        }
    }

    function js_mostrarhpessoal(chave, erro) {

        document.form1.z01_nome.value = chave;
        if (erro == true) {
            document.form1.rh230_regist.focus();
            document.form1.rh230_regist.value = '';
        }
    }

    function js_mostrarhpessoal1(chave1, chave2) {

        document.form1.rh230_regist.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_rhpessoal.hide();
    }

    function js_pesquisa() {
        js_OpenJanelaIframe('', 'db_iframe_infoambiente', 'func_infoambiente.php?funcao_js=parent.js_preenchepesquisa|rh230_regist', 'Pesquisa', true, '0');
    }

    function js_preenchepesquisa(chave) {
        db_iframe_infoambiente.hide();

        <?
        echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        ?>

    }

    js_tabulacaoforms("form1", "rh230_regist", true, 0, "rh230_regist", true);
</script>