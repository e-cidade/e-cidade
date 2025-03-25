<?

require_once("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
//MODULO: pessoal
$clinforelativasresp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");

if (isset($opcaoal)) {
    $db_opcao = 33;
    $db_botao = false;
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
        $rh234_cpf = "";
        $rh234_orgao = "";
        $rh234_descrorgao = "";
        $rh234_numinscricao = "";
        $rh234_uf = "";
    }
}

if (isset($rh234_regist) && $rh234_regist != "") {
    $oInfoAmbiente  = new cl_infoambiente();
    $sSql           = $oInfoAmbiente->sql_query_file($rh234_regist);
    $rsInfoAmbiente = $oInfoAmbiente->sql_record($sSql);
    db_fieldsmemory($rsInfoAmbiente, 0);
}
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">

    <br>
    <table align="center" border="0" cellspacing="4" cellpadding="0">
        <tr>
            <td nowrap title="<?= @$Trh01_regist ?>">
                <?= @$Lrh01_regist; ?>
            </td>
            <td nowrap colspan='10'>
                <?php
                db_input('rh234_sequencial', 10, $Irh234_sequencial, true, 'hidden', 3, "");
                db_input('rh234_regist', 6, $Irh234_regist, true, 'text', 3, "");
                ?>
            </td>
        </tr>
    </table>

    <table border='0'>
        <tr>
            <td>
                <fieldset>
                    <legend align="left"><b>INFORMAÇÕES RELATIVAS AO RESPONSÁVEL PELOS REGISTROS AMBIENTAIS </b></legend>
                    <table>
                        <tr>
                            <td>
                                <strong>CPF do Responsável: </strong>
                            </td>
                            <td nowrap>
                                <?
                                db_input('rh234_cpf', 10, $rh234_cpf, false, 'text', $db_opcao, "", "", "", "", 11);
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Órgão de Classe </strong>
                            </td>
                            <td nowrap>
                                <?
                                $aOrgao = array(
                                    '0' => 'Selecione',
                                    '1' => '1 - Conselho Regional de Medicina - CRM',
                                    '4' => '4 - Conselho Regional de Engenharia e Agronomia - CREA',
                                    '9' => '9 - Outros'
                                );

                                db_select("rh234_orgao", $aOrgao, true, $db_opcao, "");
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Descrição (sigla) do Órgão: </strong>
                            </td>
                            <td nowrap>
                                <?
                                db_input('rh234_descrorgao', 20, "", false, 'text', $db_opcao, "");
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Número de Inscrição no Órgão de Classe: </strong>
                            </td>
                            <td nowrap>
                                <?
                                db_input('rh234_numinscricao', 14, "", false, 'text', $db_opcao, "");
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Sigla da Unidade da Federação - UF do Órgão de Classe: </strong>
                            </td>
                            <td nowrap>
                                <?
                                $aUf = array(
                                    '0' => 'Selecione',
                                    'AC' => 'AC',
                                    'AL' => 'AL',
                                    'AP' => 'AP',
                                    'AM' => 'AM',
                                    'BA' => 'BA',
                                    'CE' => 'CE',
                                    'DF' => 'DF',
                                    'ES' => 'ES',
                                    'GO' => 'GO',
                                    'MA' => 'MA',
                                    'MT' => 'MT',
                                    'MS' => 'MS',
                                    'MG' => 'MG',
                                    'PA' => 'PA',
                                    'PB' => 'PB',
                                    'PR' => 'PR',
                                    'PE' => 'PE',
                                    'PI' => 'PI',
                                    'RJ' => 'RJ',
                                    'RN' => 'RN',
                                    'RS' => 'RS',
                                    'RO' => 'RO',
                                    'RR' => 'RR',
                                    'SC' => 'SC',
                                    'SP' => 'SP',
                                    'SE' => 'SE',
                                    'TO' => 'TO'
                                );
                                db_select("rh234_uf", $aUf, true, $db_opcao, "");
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="2" align="center">
                <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
                <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?= ($db_opcao == 1 || isset($db_opcaoal) ? "style='visibility:hidden;'" : "") ?>>
            </td>
        </tr>
    </table>
    <table width="90%">
        <tr>
            <td valign="top" align="center" width="90%" heigth="100%">
                <?
                $chavepri = array("rh234_sequencial" => @$rh234_sequencial);
                $cliframe_alterar_excluir->chavepri = $chavepri;
                $cliframe_alterar_excluir->iframe_nome = "ATIVIDADES";
                $sSqlIframe = $clinforelativasresp->sql_query("", "*", "rh234_sequencial", "rh234_regist = {$rh234_regist}");
                $cliframe_alterar_excluir->sql     = $sSqlIframe;
                $cliframe_alterar_excluir->campos  = "rh234_sequencial,rh234_regist,rh234_cpf,rh234_orgao,rh234_descrorgao,rh234_numinscricao,rh234_uf";
                $cliframe_alterar_excluir->legenda = "RESPONSÁVEIS LANÇADOS";
                $cliframe_alterar_excluir->iframe_height = "100%";
                $cliframe_alterar_excluir->iframe_width = "100%";
                $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
                ?>
            </td>
        </tr>
    </table>
    </center>
</form>

<script>
    function js_cancelar() {
        var opcao = document.createElement("input");
        opcao.setAttribute("type", "hidden");
        opcao.setAttribute("name", "novo");
        opcao.setAttribute("value", "true");
        document.form1.appendChild(opcao);
        document.form1.submit();
    }
</script>