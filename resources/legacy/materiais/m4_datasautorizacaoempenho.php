<?php

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/renderComponents/index.php';

?>

<script type="text/javascript" defer>
  loadComponents(['buttonsSolid','dateSimple']);
</script>

<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load('scripts.js,
                  prototype.js,
                  strings.js,
                  arrays.js,
                  windowAux.widget.js,
                  datagrid.widget.js,
                  dbmessageBoard.widget.js,
                  dbcomboBox.widget.js,
                  dbtextField.widget.js,
                  dbtextFieldData.widget.js,
                  DBInputHora.widget.js,
                  datagrid/plugins/DBOrderRows.plugin.js,
                  datagrid/plugins/DBHint.plugin.js');

    db_app::load('estilos.bootstrap.css');
    db_app::load('estilos.css');

    ?>
    <style>
        body{width:70%;margin-left:auto;margin-right:auto}form{margin-top:20px}.label{display:none!important}#btnProcessar{margin-top:1px}#btnAplicar{margin-top:12px}.form-control{font-size:13px}
    </style>
</head>

<body bgcolor="#cccccc">


<form name="form1" method="post" action="" >
    <fieldset style="width:80%;">
        <legend>Autorizações de Empenho</legend>                
        <div class="row" style="margin-top:12px;">
            <div style="width: auto !important;">
                <?php
                    db_ancora('Código de: ', 'pesquisaAutorizacaoInicial(true);', 1);
                ?>
            </div>
            <div class="col-2 ">
                <?php
                    db_input(
                        'iCodigoEmpenhoInicial',
                        4,
                        1,
                        true,
                        'text',
                        1,
                        "onchange='pesquisaAutorizacaoInicial(false)'",
                        '',
                        '',
                        '',
                        null,
                        'form-control'
                    );
                ?>
            </div>
            <div  style="width: auto !important;">
                <?php db_ancora('a', 'pesquisaAutorizacaoFinal(true);', 1); ?>
            </div>
            <div class="col-2">
                <?php
                    db_input(
                        'iCodigoEmpenhoFinal',
                        4,
                        1,
                        true,
                        'text',
                        1,
                        "onchange='pesquisaAutorizacaoFinal(false)'",
                        '',
                        '',
                        '',
                        null,
                        'form-control'
                    );
                ?>
            </div>
            <div>
                <?php
                    $component->render('buttons/solid', [
                    'type' => 'button',
                    'designButton' => 'success',
                    'size' => 'sm',
                    'onclick' => 'js_getAutorizacoes();',
                    'message' => 'Processar',
                    'value' => 'Processar',
                    'name' => 'btnProcessar',
                    'id' => 'btnProcessar',
                    ]);
                ?>

            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <b>Atualizar para:</b>
            <?php $component->render('inputs/date/simple', [
                'id' => 'atualizarDataPara',
                'placeholder' => 'Escolha uma data ',
                'name' => 'atualizarDataPara',
                'required' => true,
                'label' => 'Atualizar para:',
            ]);
            ?>
            </div>
            <div class="col-2">
            <?php
                $component->render('buttons/solid', [
                'type' => 'button',
                'designButton' => 'success',
                'size' => 'sm',
                'onclick' => 'aplicarData();',
                'message' => 'Aplicar',
                'value' => 'Aplicar',
                'name' => 'btnAplicar',
                'id' => 'btnAplicar',
                ]);
                ?>
            </div>
        </div>
        <div class="row">
        <fieldset style='width:100%;'>
            <div id='ctnGridAutorizacoes'></div>
        </fieldset>
    </div>

    </fieldset>
    <div style="width: 80%;">
    <center>
    <?php
        $component->render('buttons/solid', [
            'type' => 'button',
            'designButton' => 'success',
            'size' => 'sm',
            'onclick' => 'atualizarData();',
            'message' => 'Atualizar',
            'value' => 'Atualizar',
            'name' => 'btnAtualizar',
            'id' => 'btnAtualizar',
        ]);
    ?>
    </center>
    </div>

</form>


</body>
<script>
    const oGridAutorizacoes          = new DBGrid('gridAutorizacoes');
    oGridAutorizacoes.nameInstance = 'oGridAutorizacoes';
    oGridAutorizacoes.setCheckbox(0);
    oGridAutorizacoes.setCellWidth( [ '10%', '65%', '20%'] );
    oGridAutorizacoes.setHeader( [ 'Código', 'Resumo', 'Data'] );
    oGridAutorizacoes.setCellAlign( [ 'center', 'center', 'center'] );
    oGridAutorizacoes.setHeight(220);
    oGridAutorizacoes.show($('ctnGridAutorizacoes'));
    let aOrcamentos = [];
    let sInicialFinal = '';

    const sUrlRpc = "web/configuracao/configuracao/procedimentos/manutencao-de-dados/manutencao-lancamentos-patrimonial/controle-de-datas/autorizacao-de-empenho/empautoriza";

    function pesquisaAutorizacaoInicial(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empautoriza','func_empautoriza.php?funcao_js=parent.preencheCodigoEmpenhoInicialPorAncora|e54_autori','Pesquisa',true);
            return;
        }

        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empautoriza','func_empautoriza.php?pesquisa_chave='+document.form1.iCodigoEmpenhoInicial.value+'&funcao_js=parent.preencheCodigoEmpenhoInicialManualmente','Pesquisa',false);
        return;
        
    }


    function pesquisaAutorizacaoFinal(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empautoriza','func_empautoriza.php?funcao_js=parent.preencheCodigoEmpenhoFinalPorAncora|e54_autori','Pesquisa',true);
            return;
        }

        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empautoriza','func_empautoriza.php?pesquisa_chave='+document.form1.iCodigoEmpenhoFinal.value+'&funcao_js=parent.preencheCodigoEmpenhoFinalManualmente','Pesquisa',false);
        return;
        
    }

    function preencheCodigoEmpenhoInicialPorAncora(codigoEmpenho,erro){
        document.getElementById('iCodigoEmpenhoInicial').value = codigoEmpenho;
        db_iframe_empautoriza.hide();
    }

    function preencheCodigoEmpenhoInicialManualmente(codigoEmpenho,erro){
        if(erro){
            document.getElementById('iCodigoEmpenhoInicial').value = "";
            return;
        }
    }

    function preencheCodigoEmpenhoFinalPorAncora(codigoEmpenho,erro){
        document.getElementById('iCodigoEmpenhoFinal').value = codigoEmpenho;
        db_iframe_empautoriza.hide();
    }

    function preencheCodigoEmpenhoFinalManualmente(codigoEmpenho,erro){
        if(erro == true){
            document.getElementById('iCodigoEmpenhoFinal').value = "";
            return;
        }
    }

    function js_getAutorizacoes() {
        iCodigoEmpenhoInicial = document.getElementById('iCodigoEmpenhoInicial').value;
        iCodigoEmpenhoFinal = document.getElementById('iCodigoEmpenhoFinal').value;

        if(iCodigoEmpenhoFinal == ""){
            iCodigoEmpenhoFinal = iCodigoEmpenhoInicial;
        }

        if((iCodigoEmpenhoInicial == "" && iCodigoEmpenhoFinal == "") || iCodigoEmpenhoInicial == ""){
            return alert("Usuário: preencha os códigos das autorizacões de empenho.");
        }

        js_divCarregando('Aguarde, consultando as autorizações...<br>Esse procedimento pode levar algum tempo.', 'msgBox');

        const oAjax = new Ajax.Request(

            `${sUrlRpc}/getByPrimaryKeyRange/${iCodigoEmpenhoInicial}/${iCodigoEmpenhoFinal}`,
            {
                method: 'get',
                onComplete: js_retornoAutorizacoes
            });
    }

    function js_retornoAutorizacoes(oAjax){

        js_removeObj('msgBox');
        
        const oRetorno = eval('(' + oAjax.responseText + ')');

        if (oAjax.status !== 200) {
            alert(oRetorno.responseMessage);
            oGridAutorizacoes.clearAll(true);
            return;
        }

        aAutorizacoes = oRetorno.autorizacoes;

        oGridAutorizacoes.clearAll(true);
        aAutorizacoes.each(function (oAutorizacao, iAutorizacao) {
            const aLinha = [];
            aLinha.push(oAutorizacao.e54_autori);
            aLinha.push(oAutorizacao.e54_resumo.urlDecode());
            const sDBDataFormatada = oAutorizacao.e54_emiss.split('-').reverse().join('/');
            const sDBData = oAutorizacao.e54_emiss.length ? sDBDataFormatada : '';
            const iData = new DBTextFieldData(
                'oDBTextFieldData' + iAutorizacao,
                'oDBTextFieldData' + iAutorizacao,
                sDBData,
                10
            ).toInnerHtml();
            aLinha.push(iData);
            oGridAutorizacoes.addRow(aLinha);
        });
        oGridAutorizacoes.renderRows();

    }

    function aplicarData() {
        if (oGridAutorizacoes.aRows.length == 0) {
            alert('Nenhuma Autorização de Empenho listada para atualizar data!');
            return;
        }

        const atualizarDataPara = $F('atualizarDataPara').split("-").reverse().join("/");

        if (atualizarDataPara.length === 0) {
            alert('Insira uma data para atualizar as autorizações!');
            return;
        }

        if (!oGridAutorizacoes.getSelection('array').length) {
            alert('Nenhum autorização selecionada para atualizar data!');
            return;
        }

        const iLinhas = oGridAutorizacoes.aRows.length;

        for (let i = 0; i < iLinhas; i++) {
            if (oGridAutorizacoes.aRows[i].isSelected) {
                let oCheckGrid = document.getElementById(
                    oGridAutorizacoes.aRows[i].aCells[3].getId()
                ).firstChild;

                oCheckGrid.value = atualizarDataPara;
            }
        }
    }

    function atualizarData(){
        let autorizacoesSelecionadas = oGridAutorizacoes.getSelection();
        if(autorizacoesSelecionadas.length == 0){
            return alert('Selecione uma autorização para atualizar!');
        }

        let dados = {};

        autorizacoesSelecionadas.forEach(autorizacao => {
            dados[autorizacao[1]] = autorizacao[3];
        });

        js_divCarregando('Aguarde, atualizando as datas de Autorizações de Empenho...<br>Esse procedimento pode levar algum tempo.', 'msgBox');


        const oAjax = new Ajax.Request(
            `${sUrlRpc}/updateDateByIds`,
            {
                method: 'post', 
                parameters: { dados: JSON.stringify(dados) }, 
                onComplete: retornoAtualizacao,

            }
        );

    }

    function retornoAtualizacao(oAjax){
        js_removeObj('msgBox');
        const oRetorno = eval('(' + oAjax.responseText + ')');
        alert(oRetorno.responseMessage);
        if(oAjax.status == 200) limparCampos();
    }

    function limparCampos(){
        document.getElementById('iCodigoEmpenhoInicial').value = "";
        document.getElementById('iCodigoEmpenhoFinal').value = "";
        document.getElementById('atualizarDataPara').value = "";
        oGridAutorizacoes.clearAll(true);
    }

</script>

</html>
<?php
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
?>
