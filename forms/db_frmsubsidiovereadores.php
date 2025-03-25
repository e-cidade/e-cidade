<?
//MODULO: sicom
$clsubsidiovereadores->rotulo->label();
$clrotulo = new rotulocampo;
?>

<form name="form1" method="post" action="">

    <fieldset style="width:400px;">

        <legend><strong>Subsidio dos Vereadores</strong></legend>

        <table border="0">
            <tr style="display:none;">
                <td nowrap title="<?php echo $Tsi179_sequencial; ?>">
                    <?php echo $Lsi179_sequencial; ?>
                </td>
                <td>
                    <?php db_input('si179_sequencial', 10, $Isi179_sequencial, true, 'text', 3, "") ?>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Valor do Subsidio:</strong>
                </td>
                <td>
                    <?php db_input('si179_valor', 15, $Isi179_valor, true, 'text', $db_opcao, "") ?>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Percentual Reajuste:</strong>
                </td>
                <td>
                    <?php db_input('si179_percentual', 6, $Isi179_percentual, true, 'text', $db_opcao, "") ?>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Inicio Vigência:</strong>
                </td>
                <td>
                    <?
                    db_inputdata('si179_dataini', @$si179_dataini_dia, @$si179_dataini_mes, @$si179_dataini_ano, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Lei Autorizativa:</strong>
                </td>
                <td>
                    <?php db_input('si179_lei', 8, $Isi179_lei, true, 'text', $db_opcao, "") ?>
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Publicação:</strong>
                </td>
                <td>
                    <?
                    db_inputdata('si179_publicacao', @$si179_publicacao_dia, @$si179_publicacao_mes, @$si179_publicacao_ano, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>

    <br />
    <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

</form>

<script>
    function js_pesquisa() {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_subsidiovereadores', 'func_subsidiovereadores.php?funcao_js=parent.js_preenchepesquisa|si179_sequencial', 'Pesquisa', true);
    }

    function js_preenchepesquisa(chave) {
        db_iframe_subsidiovereadores.hide();
        <?
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }
        ?>
    }
</script>