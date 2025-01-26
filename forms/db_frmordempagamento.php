<?php
require_once("model/protocolo/AssinaturaDigital.model.php");

$AssinaturaDigital = new AssinaturaDigital();
?>
<style>
    .divDadosOp {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5
    }

    .fieldsetDadosOp {
        margin: 0 auto
    }
</style>

<form name="form1" method="post">
    <input type="hidden" name="empenho" id="empenho" value="<?= $empenho ?>">
    <input type="hidden" name="fornecedor" id="fornecedor" value="<?= $fornecedor ?>">
    <table border="0">
        <tr>
            <td>
                <fieldset class="fieldsetDadosOp">
                    <legend>Dados da OP</legend>
                    <table>
                        <tr>
                            <td>
                                <?= @$Le60_codemp ?>
                            </td>
                            <td>
                                <?
                                db_input('e50_data', 50, "", true, 'hidden', 3);
                                // db_input('e53_valor', 10, "", true, 'hidden', 3);

                                db_input('e60_codemp', 10, '', true, 'text', 3);
                                db_input('e60_numemp', 10, '', true, 'hidden', 3);
                                ?>
                            </td>
                            <td>
                                <?db_ancora(@$Le50_codord, "pesquisaOrdemPagamento();", $db_opcao);?>
                            </td>
                            <td>
                                <?db_input('e50_codord', 10, '', true, 'text', 3);?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Data da OP:</b>
                            </td>
                            <td>
                                <?
                                db_inputdata("dataLiquidacaoAtual", '', '', '', true, "hidden", 3);
                                db_inputdata("dataLiquidacao", '', '', '', true, "text", 2);
                                ?>
                            </td>
                            <td>
                                <b>Data do estorno da OP:</b>
                            </td>
                            <td>
                                <?
                                db_inputdata("dataEstornoAtual", '', '', '', true, "hidden", 3);
                                db_inputdata("dataEstorno", '', '', '', true, "text", 2);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Valor Total:</b>
                            </td>
                            <td>
                                <?db_input('e53_valor', 10, "", true, 'text', 3);?>
                            </td>
                            <td>
                                <b>Valor Anulado:</b>
                            </td>
                            <td>
                                <?db_input('e53_vlranu', 10, "", true, 'text', 3);?>
                            </td>
                            <td>
                                <b>Valor Pago:</b>
                            </td>
                            <td>
                                <?db_input('e53_vlrpag', 10, "", true, 'text', 3);?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Conta Fornecedor: </strong></td>
                            <td colspan='6'>
                            <?                
                                db_select("e50_contafornecedor",null,true,1,"style='width:100%'");
                            ?>
                            </td>
                        </tr>
                        <!-- OC 12746 -->
                        <tr style="display: none" id="competDespInput">
                            <td>
                                <b>Compet�ncia Despesa: </b>
                            </td>
                            <td>
                                <? db_inputData('e50_compdesp', '', '', '', true, 'text', 1); ?>
                            </td>
                        </tr>
                        <tr>
                            <td nowrap title="<?=$Te50_numcgmordenador?>">
                                <?db_ancora('Ordenador da Liquida��o',"js_pesquisa_liquidacao(true);",$fimperiodocontabil);?>
                            </td>
                            <td>
                                <?
                                    db_input("e50_numcgmordenador",10,$Ie50_numcgmordenador,true,"text",$fimperiodocontabil,"onChange='js_pesquisa_liquidacao(false);'");
                                    db_input("e50_numcgmordenadoranterior",10,$Ie50_numcgmordenadoranterior,true,"hidden");
                                ?>
                            </td>
                            <td colspan="6">
                                <?
                                    db_input("e50_nomeordenador",56,$Ie50_nomeordenador,true,"text",3);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>

        <tr>
            <td nowrap title="<?= @$Te50_obs ?>" colspan="3">
                <fieldset>
                    <legend>
                        <strong>Hist�rico da OP:</strong>
                    </legend>
                    <? db_textarea('historicoOp', 4, 130, $Ie50_obs, true, 'text', 2) ?>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <? include("forms/db_frmliquidaboxreinf.php"); ?>
            </td>
        </tr>
        <?php
        $clorcelemento = new cl_orcelemento();
        $clempautitem  = new cl_empautitem;
        $clempelemento = new cl_empelemento();
        $clempempaut      =    new cl_empempaut;
        $clempautidot  = new cl_empautidot;
        $dados = explode("/", $empenho);
        $instit = db_getsession("DB_instit");

        $result = $clempempaut->sql_record($clempempaut->sql_query(null, "*", "", "e60_codemp = '{$dados[0]}' and e54_instit = {$instit} and e54_anousu = {$dados[1]}"));

        if ($clempempaut->numrows > 0) {
            $oResult = db_utils::fieldsMemory($result, 0);
            $e54_autori = $oResult->e61_autori;
            $e60_numemp = $oResult->e60_numemp;
            $z01cpfcnpj = $oResult->z01_cgccpf;
            $anoUsu = db_getsession("DB_anousu");
            $sWhere = "e56_autori = " . $e54_autori . " and e56_anousu = " . $anoUsu;

            $result = $clempautidot->sql_record($clempautidot->sql_query_dotacao(null, "e56_coddot", null, $sWhere));

            if ($clempautidot->numrows > 0) {
                $oResult = db_utils::fieldsMemory($result, 0);

                $clorcdotacao = new cl_orcdotacao();
                $result = $clorcdotacao->sql_record($clorcdotacao->sql_query($anoUsu, $oResult->e56_coddot, "o56_elemento,o56_codele"));

                if ($clorcdotacao->numrows > 0) {

                    $oResult = db_utils::fieldsMemory($result, 0);
                    $oResult->estrutural = criaContaMae($oResult->o56_elemento . "00");

                    $sWhere = "o56_elemento like '$oResult->estrutural%' and o56_codele <> $oResult->o56_codele and o56_anousu = $anoUsu";
                    $sSql = "select distinct o56_codele,o56_elemento,o56_descr
                                 from empempitem
                                       inner join pcmater on pcmater.pc01_codmater    = empempitem.e62_item
                                       inner join pcmaterele on pcmater.pc01_codmater = pcmaterele.pc07_codmater
                                       left join orcelemento on orcelemento.o56_codele = pcmaterele.pc07_codele
                                                             and orcelemento.o56_anousu = $anoUsu
                                   where o56_elemento like '$oResult->estrutural%'
                                   and e62_numemp = $e60_numemp and o56_anousu = $anoUsu";

                    $result = $clorcelemento->sql_record($sSql);

                    $oResult = db_utils::getCollectionByRecord($result);

                    $numrows =  $clorcelemento->numrows;
                    $aEle = array();

                    foreach ($oResult as $oRow) {
                        $aEle[$oRow->o56_codele] = $oRow->o56_descr;
                        $aCodele[] = $oRow->o56_elemento;
                    }
                    $result = $clempelemento->sql_record($clempelemento->sql_query_file($e60_numemp, null, "e64_codele"));
                    if ($clempelemento->numrows > 0) {
                        $oResult = db_utils::fieldsMemory($result, 0);
                    }
                    if (!isset($e56_codele)) {
                        $e56_codele = $oResult->e64_codele;
                    }
                }
            }
        }
        $opcao = 3;
        $tipodesdobramento = 3;
        if (strlen($z01cpfcnpj) == 11  &&  !(
            $aCodele . substr(0, 3) == '331' || $aCodele . substr(0, 3) == '345' ||
            $aCodele . substr(0, 3) == '346' || $aCodele . substr(0, 7) == '3339018' ||
            $aCodele . substr(0, 7) == '3339019' || $aCodele . substr(0, 7) == '3339014' ||
            $aCodele . substr(0, 7) == '3339008' || $aCodele . substr(0, 7) == '3339059' ||
            $aCodele . substr(0, 7) == '3339046' || $aCodele . substr(0, 7) == '3339048' ||
            $aCodele . substr(0, 7) == '3339049' || $aCodele == '333903602' ||
            $aCodele == '333903603' || $aCodele == '333903607' ||  $aCodele == '333903608' ||
            $aCodele == '333903609' || $aCodele == '333903614' || $aCodele == '333903640'  ||
            $aCodele == '333903641')) {
            $tipodesdobramento = 1;
            $opcao = 1;
        ?>
            <tr>
                <td colspan="4">
                    <? include("forms/db_frmliquidaboxesocial.php"); ?>
                </td>
            </tr>
        <?php    } ?>
        <tr>
            <td colspan="4">
                <?
                db_input('e140_sequencial', 50, "", true, 'hidden', 3);
                db_input('desdobramentoDiaria', 50, "", true, 'hidden', 3);
                db_input('salvarDiaria', 50, "", true, 'hidden', 3);
                include("forms/db_frmliquidaboxdiarias.php");
                ?>
            </td>
        </tr>
    </table>

    <div style="margin-top: 10px;">

        <input name="alterar" type="button" id="db_opcao" value="Alterar" onclick="js_alterar()">
        <? if ($AssinaturaDigital->verificaAssituraAtiva()) { ?>
            <input name="alterar" type="button" id="db_opcao" value="Solicitar Nova Assinatura Digital" onclick="return js_emiteLiquidacaoOrdem(<?= $e50_codord ?>)"  >
        <? } ?>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar OP" onclick="pesquisaOrdemPagamento()">

    </div>
</form>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script>
    function js_alterar() {
        js_alterarRetencao();
        var cpfcnpj = "<?php print $z01cpfcnpj; ?>";
        var tipodesdobramento = "<?php print $tipodesdobramento; ?>";
        var opcao = "<?php print $opcao; ?>";

        if (tipodesdobramento == 1  && document.form1.aIncide.value == 1) {
            if (opcao != '3' && !document.form1.ct01_codcategoria.value && cpfcnpj.length == 11 && tipodesdobramento == '1') {
                alert("Campo Categoria do Trabalhador Obrigatorio")
                return false;
            }
            if (opcao != '3' && !document.form1.multiplosvinculos.value && cpfcnpj.length == 11 && tipodesdobramento == '1') {
                alert("Campo Possui m�ltiplos v�nculos Obrigatorio")
                return false;
            }
            if (document.form1.multiplosvinculos.value == 1 && opcao != '3' && !document.form1.contribuicaoPrev.value && cpfcnpj.length == 11 && tipodesdobramento == '1') {
                alert("Campo Indicador de Desconto da Contribui��o Previdenci�ria Obrigatorio")
                return false;
            }
            if (!document.form1.numempresa.value && opcao != '3' && (document.form1.contribuicaoPrev.value == '1' || document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') && cpfcnpj.length == 11 && tipodesdobramento == '1') {
                alert("Campo Empresa que efetuou desconto Obrigatorio")
                return false;
            }
            if (!document.form1.ct01_codcategoriaremuneracao.value && opcao != '3' && (document.form1.contribuicaoPrev.value == '1' || document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') && cpfcnpj.length == 11 && tipodesdobramento == '1') {
                alert("Campo Categoria do trabalhador na qual houve a remunera��o Obrigatorio")
                return false;
            }

            if (!document.form1.valorremuneracao.value && opcao != '3' && (document.form1.contribuicaoPrev.value == '1' || document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') && cpfcnpj.length == 11 && tipodesdobramento == '1') {
                alert("Campo Valor da Remunera��o Obrigatorio")
                return false;
            }
            if (!document.form1.valordesconto.value && opcao != '3' && (document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') && cpfcnpj.length == 11 && tipodesdobramento == '1') {
                alert("Campo Valor do Desconto Obrigatorio")
                return false;
            }
            if (!document.form1.competencia.value && opcao != '3' && (document.form1.contribuicaoPrev.value == '1' || document.form1.contribuicaoPrev.value == '2' || document.form1.contribuicaoPrev.value == '3') && cpfcnpj.length == 11 && tipodesdobramento == '1') {
                alert("Campo Compet�ncia Obrigatorio")
                return false;
            }
        }
        if (document.form1.e50_numcgmordenadoranterior.value && !document.form1.e50_numcgmordenador.value) {
                alert("Campo Ordenador da Liquida��o nao Informado.")
                return false;
        }
        document.getElementById("db_opcao").type = "submit";
    }

    function pesquisaOrdemPagamento() {
        empenho = $('empenho').value;
        $('e60_codemp').value = empenho;

        js_pesquisae50_getordem(empenho);
    }

    function js_pesquisae50_getordem(e60_codemp) {

        js_OpenJanelaIframe(
            '',
            'db_iframe_alteracaoop',
            'func_pagordem.php?chave_e60_codemp=' + e60_codemp + '&funcao_js=parent.js_mostraordem|e50_codord|e50_obs|e50_compdesp|elemento|e50_data|e60_numemp|data_anulacao|e53_valor|e50_retencaoir|e50_naturezabemservico|e53_vlranu|e53_vlrpag|e50_contafornecedor|e50_numcgmordenador',
            'Pesquisa',
            true,
            '0',
            '1'
        );
    }

    // Converter data para o padr�o dd/mm/YYYY
    function converterData(dataInformada) {
        let data = new Date(dataInformada.replaceAll("-", "/"));
        return ("0" + data.getDate()).substr(-2) + "/" + ("0" + (data.getMonth() + 1)).substr(-2) + "/" + data.getFullYear();
    }

    function js_mostraordem(e50_codord, e50_obs, e50_compdesp, elemento, e50_data, e60_numemp, data_anulacao, e53_valor, e50_retencaoir, e50_naturezabemservico, e53_vlranu, e53_vlrpag, e50_contafornecedor,e50_numcgmordenador) {

        $('e60_numemp').value = e60_numemp;
        $('e50_codord').value = e50_codord;
        $('e53_valor').value = e53_valor;

        $('e53_vlranu').value = e53_vlranu;
        $('e53_vlrpag').value = e53_vlrpag;
        $('historicoOp').value = e50_obs;
        $('e50_data').value = e50_data;
        $('e50_numcgmordenador').value = e50_numcgmordenador;
        $('e50_numcgmordenadoranterior').value = e50_numcgmordenador;
        if (e50_numcgmordenador) {
            js_pesquisa_liquidacao(false);
        }
        $('dataLiquidacao').value = converterData(e50_data);
        $('dataLiquidacaoAtual').value = converterData(e50_data);
        if (data_anulacao !== "") {
            $('dataEstornoAtual').value = converterData(data_anulacao);
            $('dataEstorno').value = converterData(data_anulacao);
            $('dataEstorno').readOnly = false;
            $('dataEstorno').style.backgroundColor = 'white'
            $('dtjs_dataEstorno').type = 'button';
        } else {
            $('dataEstorno').value = "";
            $('dataEstorno').readOnly = true;
            $('dataEstorno').style.backgroundColor = '#DEB887'
            $('dtjs_dataEstorno').type = 'hidden';
        }
        $('reinfRetencao').value = e50_retencaoir == "t" ? 'sim' : 'nao';
        $('naturezaCod').value = e50_naturezabemservico;
        $('naturezaDesc').value = '';
        js_pesquisaNatureza(false);
        js_verificaEstabelecimentosInclusos(e50_codord);
        js_validarRetencaoIR();
        js_pesquisaDiaria(e50_codord,e60_numemp);
        $("e50_contafornecedor").innerHTML = ''
        js_carregaContaFornecedor(e50_contafornecedor);

        if (e50_compdesp != '') {
            e50_compdesp = converterData(e50_compdesp);
        }

        aMatrizEntrada = ['3319092', '3319192', '3319592', '3319692'];

        if (aMatrizEntrada.indexOf(elemento) !== -1) {
            $('e50_compdesp').value = e50_compdesp;
            document.getElementById('competDespInput').style.display = "table-row";
        } else {
            document.getElementById('competDespInput').style.display = "none";
        }

        $('reinfRetencao').style.width = "85px";
        $('reinfRetencaoEstabelecimento').style.width = "85px";
        $('fieldsetEstabelecimentos').style = "display: none"
        $('estabelecimentosTableBody').innerHTML = '';
        db_iframe_alteracaoop.hide();
        var tipodesdobramento = "<?php print $tipodesdobramento; ?>";
        if (tipodesdobramento == 1) {
            js_verificaEsocial(e50_codord);
        }
    }

    $('e140_dtautorizacao').size = 8;
    $('e140_dtinicial').size = 8;
    $('e140_dtfinal').size = 8;

    $('e140_vrldiariauni').style.marginLeft = '52px';
    $('e140_vrlhospedagemuni').style.marginLeft = '10px';
    $('e140_vlrtransport').style.marginLeft = '2px';
    $('diariaVlrTotal').style.marginLeft = '50px';
    $('hospedagemVlrTotal').style.marginLeft = '12px';
    $('diariaVlrDespesa').style.marginLeft = '48px';

    $('diariaViajante').disabled = true;
    $('diariaVlrTotal').disabled = true;
    $('hospedagemVlrTotal').disabled = true;
    $('diariaPernoiteVlrTotal').disabled = true;

    $('e140_horainicial').addEventListener('blur', function () {js_validaHora('e140_horainicial')});
    $('e140_horafinal').addEventListener('blur', function () {js_validaHora('e140_horafinal')});

    document.addEventListener("DOMContentLoaded", function() {
        var elementosNoSelect = document.querySelectorAll('input:disabled');
        elementosNoSelect.forEach(function (elemento) {
            elemento.style.color = 'black'
        });
    });

    function js_emiteLiquidacaoOrdem(codordem) {
        if (!confirm("Esse procedimento invalidar� as assinaturas j� realizadas. Deseja continuar?")) {
            return;
        }
        jan = window.open('emp2_emitenotaliq002.php?codordem='+$('e50_codord').getValue()+'&assinar=true', '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
        jan.moveTo(0,0);
    }

    function js_carregaContaFornecedor(iConta){
        var oParam = new Object();
        oParam.method = "getContasFornecedor";
        oParam.iFornecedor = $('fornecedor').value;
        let oAjax = new Ajax.Request("emp4_liquidacao004.php", {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function (oAjax) {
                let iContaPadrao = 0;
                oRetorno = eval("("+oAjax.responseText+")");
                if ($("e50_contafornecedor").length == 0) {
                    opcaoPadrao = document.createElement('option');
                    opcaoPadrao.value = 0;
                    opcaoPadrao.textContent = "Sem conta definida.";
                    $("e50_contafornecedor").appendChild(opcaoPadrao);
                    
                    if (oRetorno.length > 0) {      
                        oRetorno.forEach(function(conta) {
                            const novaConta = document.createElement('option');
                            novaConta.value = conta.pc63_contabanco;
                            if (conta.tipo == 1) {
                                iContaPadrao = conta.pc63_contabanco;
                                novaConta.textContent = conta.conta+' - '+conta.tipoconta+' (Padr�o)';
                            } else {
                                novaConta.textContent = conta.conta+' - '+conta.tipoconta;
                            }
                            $("e50_contafornecedor").appendChild(novaConta);  
                        })
                    }
                    if (iConta == '') {
                        $("e50_contafornecedor").value = iContaPadrao;
                    } else {
                        $("e50_contafornecedor").value = iConta;
                    }
                }
            }
        });
    }

    function js_pesquisa_liquidacao(mostra)
    {
        if(mostra==true){
            js_OpenJanelaIframe('','db_iframe_cgm','func_cgmordenador.php?funcao_js=parent.js_mostraliquidacao1|z01_numcgm|z01_nome|tipo','Pesquisa',true);
        }else{
            if(document.form1.e50_numcgmordenador.value != ''){
                js_OpenJanelaIframe('','db_iframe_cgm','func_cgmordenador.php?pesquisa_chave='+document.form1.e50_numcgmordenador.value+'&funcao_js=parent.js_mostraliquidacao','Pesquisa',false);
            }else{
                document.form1.e50_nomeordenador.value = '';
            }
        }
    }

    function js_mostraliquidacao(erro,chave,chave1)
    {
        if (chave1 == 'JURIDICA') {
            alert("� obrigat�rio que o ordenador seja uma pessoa f�sica.");
            document.form1.e50_numcgmordenador.value = '';
            document.form1.e50_nomeordenador.value   = '';
            document.form1.e50_numcgmordenador.focus();
        } else {
            document.form1.e50_nomeordenador.value = chave;
        }   
        
        if(erro==true){
            document.form1.e50_nomeordenador.value = '';
            document.form1.e50_numcgmordenador.focus();
        }
    }
    
    function js_mostraliquidacao1(chave,chave1,chave2)
    {
        if (chave2 == 'JURIDICA') {
            alert("� obrigat�rio que o ordenador seja uma pessoa f�sica.")
            document.form1.e50_numcgmordenador.value = '';
            document.form1.e50_nomeordenador.value = '';
            return false;
        } else {
            document.form1.e50_numcgmordenador.value = chave;
            document.form1.e50_nomeordenador.value   = chave1;
            db_iframe_cgm.hide();
        }
    }

</script>
