<?php

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/renderComponents/index.php';

?>

<script type="text/javascript" defer>
    loadComponents(['buttonsSolid', 'dateSimple', 'simpleModal', 'radiosBordered']);
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
</head>

<body bgcolor="#F5FFFB" class="container">
    <form style="margin-top:20px;" name="form1" method="post" action="">
        <!-- Gerar em bloco oculto-->
        <fieldset style="width:90%;display:none;" >
            <legend>Gerar em Bloco</legend>
            <div class="row" style="display: flex; justify-content: center; gap: 20px; margin-top: 5px;">
                <div class="col-3">
                    <?php $component->render('inputs/date/simple', [
                        'id' => 'periodoInicial',
                        'placeholder' => 'Escolha uma data ',
                        'name' => 'periodoInicial',
                        'required' => true,
                        'label' => 'Período:',
                    ]);
                    ?>
                </div>
                <div class="col-3">
                    <?php $component->render('inputs/date/simple', [
                        'id' => 'periodoFinal',
                        'placeholder' => 'Escolha uma data ',
                        'name' => 'periodoFinal',
                        'required' => true,
                        'label' => 'Até:',
                    ]);
                    ?>
                </div>
            </div>
            <div style="display: flex; justify-content: center; gap: 20px; margin-top: 6px;">
                <?php
                $component->render('buttons/solid', [
                    'type' => 'button',
                    'designButton' => 'success',
                    'size' => 'sm',
                    'onclick' => 'aplicarData();',
                    'message' => 'Gerar Arquivos',
                    'value' => 'Gerar Arquivos',
                    'name' => 'btnGerarArquivosPorIntervalo',
                    'id' => 'btnGerarArquivosPorIntervalo',
                ]);
                ?>
            </div>

        </fieldset>
        
         <!-- aplicação de margin top enquanto geração por bloco estiver indisponivel-->
        <fieldset style="width:100%; margin-top:40px;">
            <legend>Filtros</legend>
            <div class="row mb-5">
                <div class="col-3 text-left">
                    <b> Agrupar por: </b>
                    <?php
                    $opcoesAgrupamento = array("nenhum" => "Nenhum", "remessa" => "Remessa", "status" => "Status", "data" => "Data");
                    db_select("agrupamento", $opcoesAgrupamento, true, 1,"onchange='getProcessos();' class='custom-select'");
                    ?>
                </div>
            </div>

        </fieldset>

        <fieldset style="width:100%; margin-top: 5px;">
            <div id='ctnGridProcessos'></div>

        </fieldset>
        <div style="width:90%; display: flex; justify-content: center; gap: 20px; margin-top: 6px;">
            <div class="row">
                <?php
                $component->render('buttons/solid', [
                    'type' => 'button',
                    'designButton' => 'success',
                    'size' => 'sm',
                    'onclick' => "gerarArquivosPorProcessos()",
                    'message' => 'Gerar Arquivos',
                    'value' => 'Gerar Arquivos',
                    'name' => 'btnGerarArquivosPorProcessos',
                    'id' => 'btnGerarArquivosPorProcessos',
                ]);
                ?>
            </div>
        </div>

        <?php $component->render('modais/simpleModal/startModal', [
            'id' => 'modalGeracaoArquivos',
            'size' => 'lg'
        ], true); ?>
        <div class="row">
            <div class="col-10 text-left">
                <h3>Geração dos Arquivos:</h3>
            </div>
            <div class="col-2 d-flex justify-content-start flex-column text-left">
                <b>Remessa:</b>
                <?php
                db_input(
                    'l227_remessa',
                    4,
                    1,
                    true,
                    'text',
                    1,
                    "",
                    '',
                    '',
                    '',
                    null,
                    'form-control'
                );
                ?>
            </div>
            <fieldset style="width: 100%;  margin-top:8px;">
                <div class="row">
                    <div class="col-6 text-left">
                        <fieldset class="p-4" style="width: 100%; height:100%;">
                            <legend>Arquivos</legend>

                            <input type="checkbox" value="IDE" id="ide" class="checkbox-arquivos" />
                            <label for="ide">IDE - Identificação da Remessa</label><br>

                            <input type="checkbox" value="REGLIC" id="reglic" class="checkbox-arquivos" />
                            <label for="reglic">REGLIC - Legislação Municipal para Licitação</label><br>

                            <input type="checkbox" value="ABERLIC" id="aberlic" class="checkbox-arquivos" />
                            <label for="aberlic">ABERLIC - Abertura da Licitação</label><br>

                            <input type="checkbox" value="RESPLIC" id="resplic" class="checkbox-arquivos" />
                            <label for="resplic">RESPLIC - Responsável pela Licitação</label><br>

                            <input type="checkbox" value="PARTLIC" id="partlic" class="checkbox-arquivos" />
                            <label for="partlic">PARTLIC - Participantes da Licitação</label><br>

                            <input type="checkbox" value="HABLIC" id="hablic" class="checkbox-arquivos" />
                            <label for="hablic">HABLIC - Habilitação da Licitação</label><br>

                            <input type="checkbox" value="PARELIC" id="parelic" class="checkbox-arquivos" />
                            <label for="parelic">PARELIC - Parecer da Licitação</label><br>

                            <input type="checkbox" value="JULGLIC" id="julglic" class="checkbox-arquivos" />
                            <label for="julglic">JULGLIC - Julgamento da Licitação</label><br>

                            <input type="checkbox" value="HOMOLIC" id="homolic" class="checkbox-arquivos" />
                            <label for="homolic">HOMOLIC - Homologação da Licitação</label><br>

                            <input type="checkbox" value="DISPENSA" id="dispensa" class="checkbox-arquivos" />
                            <label for="dispensa">DISPENSA - Dispensa ou Inexibilidade </label><br>

                            <input type="checkbox" value="REGADESAO" id="regadesao" class="checkbox-arquivos" />
                            <label for="regadesao">REGADESAO - Adesão a Registro de Preços </label><br>

                            <input type="checkbox" value="CONSID" id="consid" class="checkbox-arquivos" />
                            <label for="consid">CONSID - Considerações</label><br>

                        </fieldset>
                    </div>
                    <div class="col-6 text-left">
                        <fieldset class="p-4" style="width: 100%; height:100%;">
                            <legend>Arquivos Gerados</legend>
                            <div id="divArquivosGerados">
                            </div>
                        </fieldset>
                    </div>

                    <div style="width:90%; display: flex; justify-content: center; gap: 20px; margin-top: 6px;">
                        <div class="row">
                            <?php
                            $component->render('buttons/solid', [
                                'type' => 'button',
                                'designButton' => 'success',
                                'size' => 'sm',
                                'onclick' => 'gerarArquivos();',
                                'message' => 'Processar',
                                'value' => 'Processar',
                                'name' => 'btnProcessar',
                                'id' => 'btnProcessar',
                            ]);
                            ?>
                            <?php
                            $component->render('buttons/solid', [
                                'type' => 'button',
                                'designButton' => 'primary',
                                'size' => 'sm',
                                'onclick' => "desmarcarTodosArquivos()",
                                'message' => 'Limpar Todos',
                                'value' => 'Limpar Todos',
                                'name' => 'btnLimparTodos',
                                'id' => 'btnLimparTodos',
                            ]);
                            ?>
                            <?php
                            $component->render('buttons/solid', [
                                'type' => 'button',
                                'designButton' => 'primary',
                                'size' => 'sm',
                                'onclick' => "marcarTodosArquivos()",
                                'message' => 'Marcar Todos',
                                'value' => 'Marcar Todos',
                                'name' => 'btnMarcarTodos',
                                'id' => 'btnMarcarTodos',
                            ]);
                            ?>
                        </div>
                    </div>

                </div>

            </fieldset>

        </div>

        <div class="row">
            <div class="col-3 text-left">
                <?php $component->render('inputs/date/simple', [
                    'id' => 'l227_dataenvio',
                    'placeholder' => 'Escolha uma data ',
                    'name' => 'dataRemessa',
                    'required' => true,
                    'label' => 'Data de Envio Remessa:',

                ]);
                ?>
            </div>
            <div class="col-9" style="margin-top: 40px;">
                <input type="checkbox" value="declaracao" id="declaracao" />
                <label for="declaracao"><b>Declaro estar ciente de que é de minha inteira responsabilidade prestar as informações corretas ao SICOM dentro dos prazos exigidos, conforme as normas estabelecidas.</b></label>
            </div>
        </div>
        <div style="width:90%; display: flex; justify-content: center; gap: 20px; margin-top: 6px;">
            <div class="row">
                <?php
                $component->render('buttons/solid', [
                    'type' => 'button',
                    'designButton' => 'success',
                    'size' => 'sm',
                    'onclick' => "salvarRemessa()",
                    'message' => 'Salvar',
                    'value' => 'Salvar',
                    'name' => 'btnSalvar',
                    'id' => 'btnSalvar',
                ]);
                ?>
            </div>
        </div>
        <?php $component->render('modais/simpleModal/endModal', [], true); ?>

    </form>
</body>
<script>

    const sUrlRpc = "web/patrimonial/licitacoes/procedimentos/sicom";
    var oGridProcessos;
    document.addEventListener("DOMContentLoaded", function() {
        oGridProcessos = new DBGrid('gridProcessos');
        oGridProcessos.nameInstance = 'oGridProcessos';
        oGridProcessos.setCheckbox(0);
        oGridProcessos.setCellWidth(['6%', '6%', '22%', '7%', '19%', '10%', '14%', '8%', '6%','0%','0%']);
        oGridProcessos.setHeader(['Seq', 'Processo', 'Modalidade', 'Numeração', 'Objeto', 'Data de Referência', 'Status', 'Prazo', 'Remessa','Adesão','Codigo Status']);
        oGridProcessos.setCellAlign(['center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center','center','center']);
        oGridProcessos.setHeight(220);
        oGridProcessos.show($('ctnGridProcessos'));
        getProcessos();
    });

    function getProcessos() {

        js_divCarregando('Aguarde, consultando os processos...<br>Esse procedimento pode levar algum tempo.', 'msgBox');
        const oAjax = new Ajax.Request(
            `${sUrlRpc}/getProcessos`, {
                method: 'post',
                parameters: {agrupamento: document.getElementById('agrupamento').value},  
                onComplete: retornoGetProcessos,

            }
        );
    }

    function retornoGetProcessos(oAjax) {

        js_removeObj('msgBox');

        const oRetorno = eval('(' + oAjax.responseText + ')');

        if (oAjax.status !== 200) {
            alert(oRetorno.responseMessage);
            oGridProcessos.clearAll(true);
            return;
        }

        let aProcessos = oRetorno.processos;

        oGridProcessos.clearAll(true);
        aProcessos.each(function(oProcesso) {
            const aLinha = [];
            aLinha.push(oProcesso.seq);
            aLinha.push(oProcesso.processo);
            aLinha.push(`<input readonly type='text' title='${oProcesso.modalidade.urlDecode()}' style='text-align: left;width:100%;border: none;' value='${oProcesso.modalidade.urlDecode()}' /> `);
            aLinha.push(oProcesso.numeracao);
            aLinha.push(`<input readonly type='text' title='${oProcesso.objeto.urlDecode()}' style='text-align: left;width:100%;border: none;' value='${oProcesso.objeto.urlDecode()}' /> `);
            aLinha.push(oProcesso.datareferencia);
            oProcesso.status =  oProcesso.status === null ? "-" : oProcesso.status;
            aLinha.push(oProcesso.status);
            let backgroundColor = (Number.isInteger(oProcesso.prazo) && oProcesso.prazo > 5) ? '#e84c4c' : 'white';
            let prazoTexto = Number.isInteger(oProcesso.prazo) ? `${oProcesso.prazo} dia(s)` : oProcesso.prazo;
            aLinha.push(`<input readonly type='text' title='${prazoTexto}' style='text-align: center; width:100%; border: none; background-color: ${backgroundColor};' value='${prazoTexto}' />`);
            oProcesso.remessa =  oProcesso.remessa === null ? "-" : oProcesso.remessa;
            aLinha.push(oProcesso.remessa);
            aLinha.push(oProcesso.adesao);
            aLinha.push(oProcesso.codigostatus);
            oGridProcessos.addRow(aLinha);
        });
        oGridProcessos.renderRows();

    }

    function marcarTodosArquivos() {
        const checkboxes = document.querySelectorAll('.checkbox-arquivos');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function desmarcarTodosArquivos() {
        const checkboxes = document.querySelectorAll('.checkbox-arquivos');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    function gerarArquivosPorProcessos(){

        let processosSelecionados = oGridProcessos.getSelection("object");
        if(processosSelecionados == 0) return alert("Usuário: nenhum processo selecionado.");

        // Validação cadastro inicial
        let aProcessos = new Array();
        processosSelecionados.each(function(aRow) {
            
            oProcesso = new Object();

            const campo = {"true": "l227_adesao","false": "l227_licitacao"};
            let isAdesao = aRow.aCells[10].getValue();

            if(isAdesao == "true") return;

            oProcesso[campo[isAdesao]] = aRow.aCells[1].getValue();
            aProcessos.push(oProcesso);

        });

        let retornoValidacaoCadastroInicial = "";

        const oAjaxValidacaoCadInicial = new Ajax.Request(
            `${sUrlRpc}/validacaoCadastroInicial`, {
                method: 'post',
                asynchronous: false,
                parameters: { processos: JSON.stringify(aProcessos)},  
                onComplete: function(oAjaxValidacaoCadInicial) {
                    const oRetorno = eval('(' + oAjaxValidacaoCadInicial.responseText + ')');
                    if (oAjaxValidacaoCadInicial.status == 500) {
                        retornoValidacaoCadastroInicial = oRetorno.responseMessage;
                    }
                    
                }
            }
        );

        if (retornoValidacaoCadastroInicial != "") {
            return alert(retornoValidacaoCadastroInicial);
        }

        const oAjax = new Ajax.Request(
            `${sUrlRpc}/getCodigoRemessa`, {
                method: 'post',
                onComplete: function(oAjax) {
                    const oRetorno = eval('(' + oAjax.responseText + ')');
                    document.getElementById('l227_remessa').value = oRetorno.remessa;
                }
            }
        );
        openModal('modalGeracaoArquivos');
        document.getElementById('divArquivosGerados').innerHTML = "";
        document.getElementById('declaracao').checked = false;
        document.getElementById('l227_dataenvio').value = "";
        desmarcarTodosArquivos();
    }

    function gerarArquivos() {

        if(document.getElementById('l227_remessa').value == "") return alert("Usuário: preencha o código da remessa.");
        
        // Atribui todos os checkboxes da classe '.checkbox-arquivos' a uma variável
        const checkboxes = [...document.querySelectorAll('.checkbox-arquivos')];

        // Verifica se algum checkbox está selecionado
        const arquivosSelecionados = checkboxes.filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);

        if (arquivosSelecionados.length === 0) return alert('Nenhum arquivo está selecionado!');

        js_divCarregando('Gerando os arquivos...<br>Esse procedimento pode levar algum tempo.', 'msgBox');

        let processosSelecionados = oGridProcessos.getSelection("object");
        let oParametros = new Object();
        let aProcessos = new Array();
        let codigoRemessa = document.getElementById('l227_remessa').value;
        let dataRemessa = document.getElementById('l227_dataenvio').value;

        processosSelecionados.each(function(aRow) {
            
            oProcesso = new Object();

            const campo = {"true": "l227_adesao","false": "l227_licitacao"};
            let isAdesao = aRow.aCells[10].getValue();

            oProcesso[campo[isAdesao]] = aRow.aCells[1].getValue();
            oProcesso.status = aRow.aCells[11].getValue();
            aProcessos.push(oProcesso);

        });

        const oAjax = new Ajax.Request(
            `${sUrlRpc}/gerarArquivos`, {
                method: 'post',
                parameters: { processos: JSON.stringify(aProcessos), remessa: codigoRemessa, dataenvio: dataRemessa, arquivos: JSON.stringify(arquivosSelecionados)},  
                onComplete: retornoGeracaoArquivos,

            }
        );
    }

    function retornoGeracaoArquivos(oAjax) {

        js_removeObj('msgBox');

        const oRetorno = eval('(' + oAjax.responseText + ')');

        if (oAjax.status == 500) {
            return alert(oRetorno.responseMessage);

        }

        let divArquivosGerados = document.getElementById('divArquivosGerados');
        let linksHTML = '';

        oRetorno.urlArquivos.csv.forEach(function(url) {
            linksHTML += `<a target="_blank" href="db_download.php?arquivo=${url}.csv">${url}.csv</a><br>`;
        });

        if(oRetorno.urlArquivos.edital != ""){
            linksHTML += `<a target="_blank" href="db_download.php?arquivo=${oRetorno.urlArquivos.edital}">${oRetorno.urlArquivos.edital}</a><br>`;
        }
        
        linksHTML += `<a target="_blank" href="db_download.php?arquivo=${oRetorno.urlArquivos.zip}">${oRetorno.urlArquivos.nomeZip}</a><br>`;

        divArquivosGerados.innerHTML = linksHTML;
        alert(oRetorno.responseMessage);

    }

    function salvarRemessa(){

        if(!document.getElementById('declaracao').checked){
            return alert('Usuário: é necessário confirmar a declaração.');
        }

        let processosSelecionados = oGridProcessos.getSelection("object");
        let oParametros = new Object();
        let aProcessos = new Array();
        let codigoRemessa = document.getElementById('l227_remessa').value;
        let dataRemessa = document.getElementById('l227_dataenvio').value;

        processosSelecionados.each(function(aRow) {
            
            oProcesso = new Object();

            const campo = {"true": "l227_adesao","false": "l227_licitacao"};
            let isAdesao = aRow.aCells[10].getValue();

            oProcesso[campo[isAdesao]] = aRow.aCells[1].getValue();
            oProcesso.status = aRow.aCells[11].getValue();
            aProcessos.push(oProcesso);

        });

        js_divCarregando('Salvando o controle da remessa...<br>Esse procedimento pode levar algum tempo.', 'msgBox');
        const oAjax = new Ajax.Request(
            `${sUrlRpc}/salvarRemessa`, {
                method: 'post',
                parameters: { processos: JSON.stringify(aProcessos), remessa: codigoRemessa, dataenvio: dataRemessa},  
                onComplete: retornoSalvarRemessa,

            }
        );

    }

    function retornoSalvarRemessa(oAjax) {

        js_removeObj('msgBox');

        const oRetorno = eval('(' + oAjax.responseText + ')');
        alert(oRetorno.responseMessage);

        if (oAjax.status == 200) {
            closeModal('modalGeracaoArquivos');
            getProcessos();
            return;
        }

    }

</script>

</html>
<?php
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
?>