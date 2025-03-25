<?

require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");

$clatividadesdesempenhadas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");

if (isset($db_opcaoal)) {

    $db_opcao = 33;
    $db_botao = true;
} else if (isset($opcao) && $opcao == "alterar") {

    $db_botao = true;
    $db_opcao = 2;
} else if (isset($opcao) && $opcao == "excluir") {

    $db_opcao = 3;
    $db_botao = true;
} else {

    $db_opcao = 1;
    $db_botao = true;

    if (isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false)) {

        $rh231_descricao = "";
    }
}

if (isset($rh231_regist) && $rh231_regist != "") {

    $oDaoAtividades  = db_utils::getDao("atividadesdesempenhadas");
    $sSql           = $oDaoAtividades->sql_query_file($rh231_regist);
    $rsAtividades = $oDaoAtividades->sql_record($sSql);
    db_fieldsmemory($rsAtividades, 0);
}

?>

<form name="form1" method="post" action="">

    <br>
    <table align="center" border="0" cellspacing="4" cellpadding="0">
        <tr>
            <td nowrap title="<?= @$Trh01_regist ?>">
                <?= @$Lrh01_regist; ?>
            </td>
            <td nowrap colspan='10'>
                <?php
                db_input('rh231_regist', 6, $Irh231_regist, true, 'text', 3, " onchange='js_pesquisarh231_regist(false);'");
                ?>
            </td>
        </tr>
    </table>

    <table border='0'>
        <tr>
            <td>
                <fieldset>
                    <legend align="left"><b>ATIVIDADES DESEMPENHADAS </b></legend>
                    <table>
                        <tr>
                            <td nowrap title="<?= @$Trh01_regist ?>">
                                <strong>Descrição das Atividades Desempenhadas: </strong>
                            </td>
                            <td nowrap>
                                <?
                                db_textarea('rh231_descricao', 5, 100, $Irh231_descricao, true, 'text', $db_opcao, "", "", "", 100);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
    <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
</form>