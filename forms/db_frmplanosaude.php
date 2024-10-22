<?
//MODULO: pessoal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clplanosaude->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");

?>
<form name="form1" method="post" action="">
    <center>
        <table border="0">
            <tr>
                <td>
                    <?
                    db_input('r75_sequencial', 6, $Ir75_sequencial, true, 'hidden', "3", "")
                    ?>
                    <fieldset>
                        <center>
                            <table border="0" width="63%">
                                <tr>
                                    <td align="right" nowrap title="Digite o Ano / Mes de competência">
                                        <strong>Ano / Mês:</strong>
                                    </td>
                                    <td nowrap>
                                        <?
                                        $r75_anousu = db_anofolha();
                                        db_input('r75_anousu', 4, $Ir75_anousu, true, 'text', 3, '');
                                        ?>
                                        &nbsp;/&nbsp;
                                        <?
                                        $r75_mesusu = db_mesfolha();
                                        db_input('r75_mesusu', 2, $Ir75_mesusu, true, 'text', 3, '');
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" nowrap title="<?= @$Tr75_regist ?>">
                                        <?
                                        db_ancora(@$Lr75_regist, "js_pesquisar75_regist(true);", ($db_opcao == 1 ? "1" : "3"));
                                        ?>
                                    </td>
                                    <td colspan="3" nowrap>
                                        <?
                                        db_input('r75_regist', 8, $Ir75_regist, true, 'text', ($db_opcao == 1 ? "1" : "3"), " onchange='js_pesquisar75_regist(false);' tabIndex='1' ")
                                        ?>
                                        <?
                                        db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, '');
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Tr75_cnpj ?>" align="right">
                                        <?= @$Lr75_cnpj ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('r75_cnpj', 14, $Ir75_cnpj, true, 'text', $db_opcao);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?= @$Tr75_ans ?>" align="right">
                                        <?= @$Lr75_ans ?>
                                    </td>
                                    <td>
                                        <?
                                        db_input('r75_ans', 14, $Ir75_ans, true, 'text', $db_opcao);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                <td nowrap title="<?= @$Tr75_dependente ?>" align="right">
                                        <?= @$Lr75_dependente ?>
                                    </td>
                                    <td>
                                        <?
                                        $aDependente = array("f"=>"NÃO","t"=>"SIM");
                                        db_select('r75_dependente',$aDependente,true,2,"onchange='document.form1.submit();'");
                                        ?>
                                    </td>
                                </tr>
                                    <?
                                        if(isset($r75_dependente) && $r75_dependente == "t"){
                                    ?>
                                <tr>
                                    <td align="right" nowrap title="<?= @$Tr75_numcgm ?>">
                                        <strong>
                                            <?
                                            db_ancora("Dependente: ", "js_pesquisar75_numcgm(true);", ($db_opcao == 1 ? "1" : "3"));
                                            ?>
                                        </strong>
                                    </td>
                                    <td colspan="3" nowrap>
                                        <?
                                        db_input('r75_numcgm', 8, $Ir75_numcgm, true, 'text', ($db_opcao == 1 ? "1" : "3"), "onchange='js_pesquisar75_numcgm(false);' tabIndex='2' ");
                                        ?>
                                        <?
                                        db_input('r75_nomedependente', 60, $Iz01_nome, true, 'text', 3, '');
                                        ?>
                                    </td>
                                </tr>
                                    <?
                                        };
                                    ?>
                                <tr>
                                    <td nowrap title="<?= @$Tr75_valor ?>" align="right">
                                        <?= @$Lr75_valor ?>
                                    </td>
                                    <td nowrap>
                                        <?
                                        db_input('r75_valor', 19, $Ir75_valor, true, 'text', $db_opcao, " tabIndex='3' ");
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan="4" width="100%" align="center">
                    <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?> onclick="return js_enviar();" tabIndex="4">
                    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisar_planosaude();" tabIndex="5" onblur="<?= ($db_opcao == 1 ? 'document.form1.r75_regist.select();' : 'document.form1.novo.select();') ?>">
                    <?
                    if ($db_opcao != 1 && (!isset($db_opcaoal) || (isset($db_opcaoal) && $db_opcaoal != 33))) {
                        echo '<input name="novo" type="button" id="novo" value="Novo" onclick="location.href=\'pes1_planosaude001.php?clicar=clicar&db_opcaoal=' . @$db_opcaoal . '&r75_regist=' . @$r75_regist . '\'"  tabIndex="6" onblur="document.form1.r75_regist.select();">';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" width="100%" height="60%" valign="top" align="center">
                    <?
                    $dbwhere = " r75_anousu=" . $r75_anousu . " and r75_mesusu=" . $r75_mesusu . " and r75_regist =" . @$r75_regist . " and r75_instit = $instit";
                    if (isset($r75_numcgm) && trim($r75_numcgm) != "" && !isset($incluir)) {
                        $dbwhere .= " and r75_numcgm <> " . $r75_numcgm;
                    }
                    $sql = $clplanosaude->sql_query_dados(
                        null,
                        "r75_sequencial, r75_anousu, r75_mesusu, r75_numcgm, r75_nomedependente, r75_valor",
                        "r75_sequencial",
                        $dbwhere
                    );
                    $sCampos  = "r75_sequencial, r75_anousu, r75_mesusu, r75_numcgm, r75_nomedependente, r75_valor";
                    $chavepri = array("r75_sequencial" => $r75_anousu, "r75_anousu" => $r75_anousu, "r75_mesusu" => $r75_mesusu, "r75_regist" => @$r75_regist, "r75_numcgm" => @$r75_numcgm);
                    $cliframe_alterar_excluir->chavepri = $chavepri;
                    $cliframe_alterar_excluir->sql      = $sql;
                    $cliframe_alterar_excluir->campos   = $sCampos;
                    $opcoes_registros = 1;
                    if (isset($db_opcaoal) && $db_opcaoal == 33) {
                        $opcoes_registros = 4;
                    }
                    $cliframe_alterar_excluir->opcoes   = $opcoes_registros;
                    $cliframe_alterar_excluir->legenda  = "";
                    $cliframe_alterar_excluir->iframe_height = "90%";
                    $cliframe_alterar_excluir->iframe_width  = "95%";
                    $cliframe_alterar_excluir->fieldset = false;
                    $cliframe_alterar_excluir->iframe_alterar_excluir(1);

                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" align="center" id="mostrar_dados_formula">
                </td>
            </tr>
        </table>
    </center>
    <?
    db_input('db_opcaoal', 2, 0, true, 'hidden', 3, "");
    ?>
</form>
<script>
    function js_enviar() {

        if (document.form1.r75_regist.value == "") {
            alert("Informe o código do Servidor.");
            document.form1.r75_regist.focus();
            return false;
        } else if (document.form1.r75_numcgm.value == "") {
            alert("Informe o CGM do dependente.");
            document.form1.r75_numcgm.focus();
            return false;
        } else if (document.form1.r75_valor.value == "") {
            alert("Informe o valor do plano de saúde.");
            document.form1.r75_valor.focus();
            return false;
        }

        return true;
    }

    function js_pesquisar75_numcgm(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cgm', 'func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true);
        } else {
            if (document.form1.r75_numcgm.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cgm', 'func_nome.php?testanome=true&pesquisa_chave=' + document.form1.r75_numcgm.value + '&funcao_js=parent.js_mostracgm', 'Pesquisa', false, '0');
            } else {
                document.form1.r75_nomedependente.value = '';
            }
        }
    }

    function js_mostracgm(erro, chave) {
        document.form1.r75_nomedependente.value = chave;
        if (erro == true) {
            document.form1.r75_numcgm.focus();
            document.form1.r75_numcgm.value = '';
        }
    }

    function js_mostracgm1(chave1, chave2) {
        document.form1.r75_numcgm.value = chave1;
        document.form1.r75_nomedependente.value = chave2;
        db_iframe_cgm.hide();
    }

    function js_pesquisar75_regist(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_rhpessoal', 'func_rhpessoal.php?testarescisao=ra&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?= (db_getsession("DB_instit")) ?>', 'Pesquisa', true);
        } else {
            if (document.form1.r75_regist.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_rhpessoal', 'func_rhpessoal.php?testarescisao=ra&pesquisa_chave=' + document.form1.r75_regist.value + '&funcao_js=parent.js_mostrapessoal&instit=<?= (db_getsession("DB_instit")) ?>', 'Pesquisa', false);
            } else {
                document.form1.z01_nome.value = '';
                location.href = "pes1_planosaude001.php?db_opcaoal=<?= (@$db_opcaoal) ?>";
            }
        }
    }

    function js_mostrapessoal(chave, erro) {
        document.form1.z01_nome.value = chave;
        if (erro == true) {
            document.form1.r75_regist.focus();
            document.form1.r75_regist.value = '';
        } else {
            location.href = "pes1_planosaude001.php?db_opcaoal=<?= (@$db_opcaoal) ?>&r75_regist=" + document.form1.r75_regist.value;
        }
    }

    function js_mostrapessoal1(chave1, chave2) {
        document.form1.r75_regist.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_rhpessoal.hide();
        location.href = "pes1_planosaude001.php?db_opcaoal=<?= (@$db_opcaoal) ?>&r75_regist=" + chave1 + "&z01_nome=" + chave2;
    }

    function js_pesquisar_planosaude(mostra) {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_planosaude', 'func_planosaude.php?testarescisao=ra&funcao_js=parent.js_preenchepesquisa|r75_anousu|r75_mesusu|r75_regist|r75_numcgm', 'Pesquisa', true);
    }

    function js_preenchepesquisa(chave, chave1, chave2, chave3) {
        db_iframe_planosaude.hide();
        <?
        echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3+'&db_opcaoal=" . @$db_opcaoal . "&clicar=false'";
        ?>
    }
</script>