<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: patrim
include("dbforms/db_classesgenericas.php");
include("classes/db_licitaparam_classe.php");

$cllicitaparam = new cl_licitaparam;
$rsParamLic = $cllicitaparam->sql_record($cllicitaparam->sql_query(null, "*", null, "l12_instit = " . db_getsession('DB_instit')));
db_fieldsmemory($rsParamLic, 0)->l12_validafornecedor_emailtel;

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clpcorcamforne->rotulo->label();
$clpcorcamfornelic->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("l20_codigo");
if (isset($db_opcaoal)) {
    $db_opcao = 33;
    $db_botao = true;
    $op = 1;
} else if (isset($opcao) && $opcao == "alterar") {
    $db_botao = true;
    $db_opcao = 2;
    $op = 1;
} else if (isset($opcao) && $opcao == "excluir") {
    $db_opcao = 3;
    $db_botao = true;
    $op = 1;
    $rsCgm = $clpcorcamfornelic->sql_record($clpcorcamfornelic->sql_query($pc21_orcamforne));
    if ($clpcorcamfornelic->numrows > 0) {

        db_fieldsmemory($rsCgm, 0);
    }
} else {
    $db_opcao = 1;
    if (isset($novo) || isset($verificado) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro == false)) {
        $pc21_orcamforne = "";
        $pc21_numcgm = "";
        $z01_nome = "";
        $pc31_nomeretira = "";
    }
}
?>
<style>
    #pc31_dtretira,
    #pc31_liclicitatipoempresa,
    #pc31_regata,
    #pc31_renunrecurso {
        width: 68px;
    }

    #pc31_liclicitatipoempresadescr,#z01_nome {
        width: 282px;
    }

    #pc31_representante {
        width: 354;
    }

    #pc31_nomeretira {
        width: 373px;
    }

    #pc31_horaretira {
        width: 103px;
    }
</style>
<form name="form1" method="post" action="" id="form1">
    <center>
        <br>
        <fieldset style="width:700px;">
        <legend><b>Fornecedor</legend>
        <table border="0">
            <tr>
                <td align="right" nowrap title="<?= @$Tl20_codigo ?>">
                    <b>Licita��o :
                    </b>
                </td>
                <td>
                    <?
                    db_input('l12_validafornecedor_emailtel', 10, $Il12_validafornecedor_emailtel, true, 'hidden', 3, "");
                    db_input('solic', 40, "", true, 'hidden', 3);
                    db_input('l20_codigo', 8, $Il20_codigo, true, 'text', 3)
                    ?>
                </td>
                <td align="right" nowrap title="<?= @$Tpc21_codorc ?>">
                    <b>Or�amento:</b>
                </td>
                <td>
                    <?
                    db_input('pc20_codorc', 14, $Ipc21_codorc, true, 'text', 3)
                    ?>
                </td>
            </tr>
            <tr style="display:none;">
                <td align="right" nowrap title="<?= @$Tpc21_orcamforne ?>">
                    <?= @$Lpc21_orcamforne ?>
                </td>
                <td>
                    <?
                    db_input('pc21_orcamforne', 8, $Ipc21_orcamforne, true, 'text', 3)
                    ?>
                </td>
            </tr>
            <tr>
                <td align="right" nowrap title="<?= @$Tpc21_numcgm ?>">
                    <?
                    db_ancora("Fornecedor", "js_pesquisapc21_numcgm(true);", $db_opcao);
                    ?>
                </td>
                <td>
                    <?
                    db_input('pc21_numcgm', 8, $Ipc21_numcgm, true, 'text', $db_opcao, " onchange='js_pesquisapc21_numcgm(false);'")
                    ?>
                    <?
                    db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
                    ?>
                </td>
                <td align="right" nowrap title="<?= @$Tpc21_codorc ?>">
                    <b>CPF/CNPJ:</b>
                </td>
                <td>
                    <?
                    db_input('cpfcnpj', 14, $cpfcnpj, true, 'text', 3)
                    ?>
                </td>
            </tr>
            <tr>
                <td align="right" nowrap title="Representante">
                    <b>Representante:</b>
                </td>
                <td>
                    <?
                    db_input('pc31_representante',51,0,true,'text',$db_opcao,"","","#E6E4F1")
                    ?>
                </td>
                <td align="right" nowrap title="<?= @$Tpc21_codorc ?>">
                    <b>CPF:</b>
                </td>
                <td>
                    <?
                    db_input('pc31_cpf', 11, 1, true, 'text', 1,"","","#E6E4F1")
                    ?>
                </td>
            </tr>
            <tr>
            <tr>
                <td align="right" nowrap title="<?= @$Tpc31_liclicitatipoempresa ?>">
                    <?= @$Lpc31_liclicitatipoempresa ?>
                </td>
                <td>
                    <?
                    $sSqlTipoEmpresas = $oDaoTipoEmpresa->sql_query(null, "*", "l32_sequencial");
                    $rsTipoEmpresa    = $oDaoTipoEmpresa->sql_record($sSqlTipoEmpresas);
                    db_selectrecord("pc31_liclicitatipoempresa", $rsTipoEmpresa, true, $db_opcao);
                    ?>
                </td>
            </tr>
            </tr>
            <tr style="display:none;">
                <td align="right" nowrap title="<?= @$Tl22_dtretira ?>">
                    <?= @$Lpc31_dtretira ?>
                </td>
                <td>
                    <?
                    $pc31_dtretira_dia = date('d', db_getsession("DB_datausu"));
                    $pc31_dtretira_mes = date('m', db_getsession("DB_datausu"));
                    $pc31_dtretira_ano = date('Y', db_getsession("DB_datausu"));
                    db_inputdata("pc31_dtretira", @$pc31_dtretira_dia, @$pc31_dtretira_mes, @$pc31_dtretira_ano, true, 'text', $db_opcao);
                    ?>
                    <?= @$Lpc31_horaretira ?>
                    <?
                    $pc31_horaretira = db_hora();
                    db_input('pc31_horaretira', 8, $Ipc31_horaretira, true, 'text', $db_opcao, ""); ?>
                </td>
            </tr>
            <tr style="display:none;">
                <td align="right" nowrap title="<?= @$Tpc31_nomeretira ?>">
                    <?= @$Lpc31_nomeretira ?>
                </td>
                <td nowrap title="<?= @$Tpc31_nomeretira ?>">
                    <?
                    //        db_input('pc31_nomeretira',50,$Ipc31_nomeretira,true,'text',$db_opcao,"");
                    db_input('pc31_nomeretira', 50, "", true, 'text', $db_opcao, "");
                    ?>
                </td>

            </tr>

            <tr>

            <tr>
                <td nowrap title="<?= @$Tpc31_regata ?>">
                    <?= @$Lpc31_regata ?>
                </td>
                <td>
                    <?
                    $x = array("1" => "Sim", "2" => "Nao");
                    db_select("pc31_regata", $x, true, $db_opcao);
                    ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="<?= @$Tpc31_renunrecurso ?>">
                    <?= @$Lpc31_renunrecurso ?>
                </td>
                <td>
                    <?
                    db_select("pc31_renunrecurso", $x, true, $db_opcao);
                    ?>
                </td>
            </tr>

            <td colspan="4" align="center">
                <?

                $sWhere = "1!=1";
                if (isset($pc20_codorc) && !empty($pc20_codorc)) {
                    $sWhere = "pc22_codorc=" . @$pc20_codorc;
                }
                $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_file(null, "pc22_codorc", "", $sWhere));

                if ($clpcorcamitem->numrows > 0) {

                    if (!empty($pc20_codorc)) {

                        $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_file(null, "pc21_codorc", "", "pc21_codorc=" . @$pc20_codorc));

                        $resultTipocom = $clliclicita->sql_record($clliclicita->getTipocomTribunal($l20_codigo));
                        db_fieldsmemory($resultTipocom, 0)->l03_pctipocompratribunal;

                    }
                    // $result_sugersol = $clpcsugforn->sql_record($clpcsugforn->sql_query_solsugforne(null," z01_numcgm "));
                }
                if ($db_opcao == 1) {
                    $db_botao = true;
                }
                ?>
            
            </td>
            </tr>
        </table>
        </fieldset>
        <div style="margin-top:20px; margin-bottom:20px;">
            <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>
                <?php 
                    if ($clpcorcamforne->numrows > 0) {
                    $tiposcompra = array(102, 103);
                    if (!in_array($l03_pctipocompratribunal, $tiposcompra)) {
                        echo "<input name='lancval' type='button' id='lancval' value='Lan�ar valores'  onclick='CurrentWindow.corpo.document.location.href=\"lic1_orcamlancval001.php?l20_codigo=$l20_codigo&pc20_codorc=$pc20_codorc\"' " . ($db_botao == false ? "disabled" : "") . ">&nbsp";
                    }
                    echo "<input name='gera'    type='submit' id='gera'    value='Gerar relat�rio' onclick='js_gerarel();' " . ($db_botao == false ? "disabled" : "") . ">&nbsp;";
                    echo "<input name='gerarxlsbranco' type='button' id='gerarxlsbranco' value='xls em Branco' onclick='js_gerarxlsbranco()'>&nbsp";
                    }
                ?>
                <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
                <input name="habilitacao" type="button" value="Habilita��o de Fornecedores" onclick="redirecionamentoHabilitacaoFornecedor();">
                <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?= ($db_opcao == 1 || isset($db_opcaoal) ? "style='display:none;'" : "") ?>>

        </div>
        <table>
            <tr>
                <td valign="top" align="center">
                    <?
                    $chavepri = array("pc21_orcamforne" => @$pc21_orcamforne, "pc21_codorc" => @$pc21_codorc);
                    $cliframe_alterar_excluir->chavepri = $chavepri;
                    //$sWhere     = "1=1";
                    if (isset($pc20_codorc) && !empty($pc20_codorc)) {
                        $sWhere = " pc21_codorc=" . @$pc20_codorc;
                    }
                    /*
                     * O campo ed18_i_credenciamento faz parte da tabela escola, mas foi utilizado apenas porque possui o r�tulo 'Credenciamento'
                     * que � utilizado na listagem dos fornecedores lan�ados.
                     *
                     * */
                    $sCampos = "DISTINCT pc21_orcamforne,pc21_codorc,pc21_numcgm,z01_nome,(CASE WHEN l205_datacred IS NOT NULL THEN 'SIM' ELSE 'N�O' END) AS ed18_i_credenciamento";
                    $cliframe_alterar_excluir->sql           = $clpcorcamforne->sql_query_credenciados(null, $sCampos, "", $sWhere);
                    $cliframe_alterar_excluir->campos        = "pc21_orcamforne,pc21_numcgm,z01_nome,ed18_i_credenciamento";
                    $cliframe_alterar_excluir->legenda       = "FORNECEDORES LAN�ADOS";
                    $cliframe_alterar_excluir->iframe_height = "160";
                    $cliframe_alterar_excluir->iframe_width  = "700";
                    $cliframe_alterar_excluir->opcoes        = "3";
                    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
                    ?>
                </td>
            </tr>
        </table>
    </center>
</form>
<script>
    function js_gerarel() {

        <? if (isset($solic) && $solic != "") { ?>
            solic = <?= @$solic ?>;
        <? } ?>
        pc20_codorc = document.form1.pc20_codorc.value;
        if (solic == true) {
            jan = window.open('com2_solorc002.php?pc20_codorc=' + pc20_codorc, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        } else {
            jan = window.open('com2_procorc002.php?pc20_codorc=' + pc20_codorc, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        }
        jan.moveTo(0, 0);

    }

    function js_cancelar() {
        var opcao = document.createElement("input");
        opcao.setAttribute("type", "hidden");
        opcao.setAttribute("name", "novo");
        opcao.setAttribute("value", "true");
        document.form1.appendChild(opcao);
        document.form1.submit();
    }

    function js_pesquisapc21_numcgm(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'func_nome', 'func_pcforne.php?infoValidacaoRepresentante=true&orderName=true&funcao_js=parent.js_mostracgm1|pc60_numcgm|z01_nome|z01_telef|z01_email|z01_cgccpf', 'Pesquisa', true);
            return true;
        }
        if (document.form1.pc21_numcgm.value != '') {
            js_OpenJanelaIframe('', 'func_nome', 'func_pcforne.php?infoValidacaoRepresentante=true&orderName=true&pesquisa_chave=' + document.form1.pc21_numcgm.value + '&iParam=true&funcao_js=parent.js_mostracgm', 'Pesquisa', false);
            return true;
        }
        document.form1.z01_nome.value = '';
        document.form1.cpfcnpj.value = '';
    }

    function js_mostracgm(chave, chave2, chave3, chave4,chave5) {

        if($('l12_validafornecedor_emailtel').value == 't'){
            if ((chave3.trim() == '' || chave4.trim() == '')) {
                alert("Usu�rio: Inclus�o abortada. O Fornecedor selecionado n�o possui Email e Telefone no seu cadastro.");
                $('pc21_numcgm').value = '';
                $('z01_nome').value = '';
                return false;
            }
        }

        if (chave2 == true) {
            document.form1.pc21_numcgm.focus();
            document.form1.pc21_numcgm.value = '';
            document.form1.z01_nome.value = chave;
            return false;
        }
        document.form1.cpfcnpj.value = $('l12_validafornecedor_emailtel').value == 't' ? chave5 : chave4;
        document.form1.z01_nome.value = chave2;

        js_verificaFornecedor();
    }

    function js_mostracgm1(pc21_numcgm, z01_nome, z01_telef, z01_email,z01_cgccpf) {
        if ((z01_telef.trim() == '' || z01_email.trim() == '') && $('l12_validafornecedor_emailtel').value == 't') {
            alert("Usu�rio: Inclus�o abortada. O Fornecedor selecionado n�o possui Email e Telefone no seu cadastro.");
            $('pc21_numcgm').value = '';
            return false;
        }
        document.form1.pc21_numcgm.value = pc21_numcgm;
        document.form1.z01_nome.value = z01_nome;
        document.form1.cpfcnpj.value = z01_cgccpf;
        func_nome.hide();

        js_verificaFornecedor();
    }

    function js_pesquisa() {
        js_OpenJanelaIframe('', 'db_iframe_liclicita', 'func_liclicitanovo.php?tipo=1&situacao=0&funcao_js=parent.js_preenchepesquisa|l20_codigo', 'Pesquisa', true);
    }

    function js_preenchepesquisa(chave) {
        db_iframe_liclicita.hide();
        <?
        echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave;";
        ?>
    }

    function js_gerarxlsbranco() {
        let codorcamento = document.getElementById('pc20_codorc').value;
        const jan = window.open('lic1_gerarxlsbranco.php?pc20_codorc=' + codorcamento);
    }

    /**
     * Procura se o fornecedor possui d�bitos em aberto
     */
    function js_verificaFornecedor() {

        var sUrlRPC = 'com4_notificafornecedor.RPC.php';
        var iCgm = document.form1.pc21_numcgm.value;

        js_divCarregando('Aguarde, verificando...', "msgBox");

        var oParam = new Object();
        oParam.sExecucao = 'debitosEmAberto';
        oParam.iNumCgm = iCgm;
        oParam.sLiberacao = "A";

        var oAjax = new Ajax.Request(sUrlRPC, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_oretornofornecedor
        });
    }

    /**
     * Retorno com os d�bitos em aberto e informa��es de configura��o
     */
    function js_oretornofornecedor(oAjax) {

        js_removeObj("msgBox");

        var oRetorno = eval("(" + oAjax.responseText + ")");
    }
    
    function resetarCampos(){
        document.getElementById('pc31_representante').value = "";
        document.getElementById('pc31_cpf').value = ""; 
        document.getElementById('cpfcnpj').value = "";  
        document.getElementById('pc31_regata').value = "1";
        document.getElementById('pc31_renunrecurso').value = "1";
        document.getElementById('pc31_liclicitatipoempresa').value = "1";

    }

    function redirecionamentoHabilitacaoFornecedor(){

        if(document.getElementById('l20_codigo').value == ""){
            return alert("Selecione uma licita��o");
        }

        let oParams = {
            action: `lic1_habilitacaofornecedor.php`,
            iInstitId: top.jQuery('#instituicoes span.active').data('id'),
            iAreaId: 4,
            iModuloId: 381
        }

        let title = 'Procedimentos > Habilita��o de Fornecedores';

        Desktop.Window.create(title, oParams);
        menu.trigger('menu.close');

    }

</script>
