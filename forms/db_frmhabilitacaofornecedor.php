<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
    <center>
        <table border="0" style="width: 80%; align:center;">
            <tr>
                <td>
                    <fieldset>
                        <legend><b>Habilitação de Fornecedores</legend>
                        
                            <table>
                                <tr>
                                    <td>
                                        <b>Licitação:</b>
                                    </td>
                                    <td>
                                        <?
                                        db_input('l206_licitacao',11,1,true,'text',3,'');
                                        db_input('l206_sequencial',11,1,true,'hidden',3,'');
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td> 
                                        <b>Fornecedor:</b>
                                    </td>
                                    <td>
                                    <?
                                        db_select("l206_fornecedor", null, true, 1,"onchange='verificacaoDispensaInexibilidade();'");
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Data Habilitação:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_inputdata('l206_datahab', null, null, null, true, 'text', 1);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Número Certidão INSS:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_input('l206_numcertidaoinss',30,0,true,'text',1,"","","","",30)
                                        ?>
                                    </td>
                                    <td>
                                        <b>Data Emissão:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_inputdata('l206_dataemissaoinss', null, null, null, true, 'text', 1);
                                        ?>
                                    </td>
                                    <td>
                                        <b>Data de Validade:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_inputdata('l206_datavalidadeinss', null, null, null, true, 'text', 1);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Número Certidão FGTS:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_input('l206_numcertidaofgts',30,0,true,'text',1,"","","","",30)
                                        ?>
                                    </td>
                                    <td>
                                        <b>Data Emissão:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_inputdata('l206_dataemissaofgts', null, null, null, true, 'text', 1);
                                        ?>
                                    </td>
                                    <td>
                                        <b>Data de Validade:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_inputdata('l206_datavalidadefgts', null, null, null, true, 'text', 1);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Número Certidão CNDT:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_input('l206_numcertidaocndt',30,0,true,'text',1,"","","","",30)
                                        ?>
                                    </td>
                                    <td>
                                        <b>Data Emissão:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_inputdata('l206_dataemissaocndt', null, null, null, true, 'text', 1);
                                        ?>
                                    </td>
                                    <td>
                                        <b>Data de Validade:</b>
                                    </td>
                                    <td>
                                        <?php
                                        db_inputdata('l206_datavalidadecndt', null, null, null, true, 'text', 1);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                    </fieldset>
                    <center>
                        <input id="db_opcao" style="margin-top:10px;" type='button' value="Incluir" onclick="processar();" />
                        <input style="margin-top:10px;" type='button' value="Pesquisar" onclick="pesquisar();" />
                        <input style="display:none;" id="novo" style="margin-top:10px;" type='button' value="Novo" onclick="novoFornecedor();" />
                        <input style="display:none;" id="cadastrarfornecedor" style="margin-top:10px;" type='button' value="Cadastrar Fornecedor" onclick="redirecionamentoCadastroFornecedor();" />
                    </center>
                </td>
            </tr>
        </table>
        <table border="0" style="width: 80%; align:center;" >
            <tr>
                <td>
                    <fieldset>
                        <legend> Fornecedores Habilitados </legend>
                        <b> 'Para habilitação do fornecedor Pessoa Jurídica, é necessário que o(s) representante(s) legal(is) esteja(m) cadastrado(s). </b> 
                        <div style="margin-top:10px;" id='gridFornecedoresHabilitados'>
                        </div>
                    </fieldset>
                <td>
            </tr>
        </table>
    </center>
</form>
<script type="text/javascript">

    const oGridFornecedoresHabilitados          = new DBGrid('gridFornecedoresHabilitados');
    oGridFornecedoresHabilitados.nameInstance = 'oGridProcessoCompra';
    oGridFornecedoresHabilitados.setCellWidth( ['15%','15%', '60%', '10%'] );
    oGridFornecedoresHabilitados.setHeader( ['Sequencial','Numcgm','Nome/Razão Social','Opções'] );
    oGridFornecedoresHabilitados.setCellAlign( [ 'center','center','left', 'center'] );
    oGridFornecedoresHabilitados.setHeight(130);
    oGridFornecedoresHabilitados.show($('gridFornecedoresHabilitados'));


    if(document.getElementById('l206_licitacao').value != ""){
        preencheDados(document.getElementById('l206_licitacao').value);
    } else {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicita.php?habilitacaofornecedor=true&funcao_js=parent.preencheDados|l20_codigo','Pesquisa',true);
    }

    function verificacaoDispensaInexibilidade(){

        let oParametro = new Object();
        oParametro.sExecuta = "verificacaoDispensaInexibilidade";
        oParametro.codigoFornecedor = document.getElementById("l206_fornecedor").value;
        oParametro.codigoLicitacao = document.getElementById("l206_licitacao").value;

        let oAjax = new Ajax.Request('lic1_habilitacaofornecedor.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametro),
            onComplete: retornoVerificacaoDispensaInexibilidade

        });

    }

    function retornoVerificacaoDispensaInexibilidade(oAjax){
        let oRetorno = JSON.parse(oAjax.responseText);

        if(oRetorno.bloquearCampos){
            document.getElementById("l206_numcertidaoinss").readOnly = true;
            document.getElementById("l206_numcertidaofgts").readOnly = true;
            document.getElementById("l206_numcertidaocndt").readOnly = true;
            document.getElementById("l206_dataemissaoinss").readOnly = true;
            document.getElementById("l206_dataemissaofgts").readOnly = true;
            document.getElementById("l206_dataemissaofgts").readOnly = true;
            document.getElementById("l206_dataemissaocndt").readOnly = true;
            document.getElementById("l206_datavalidadeinss").readOnly = true;
            document.getElementById("l206_datavalidadefgts").readOnly = true;
            document.getElementById("l206_datavalidadecndt").readOnly = true;
            document.getElementById("l206_numcertidaoinss").style.backgroundColor = "#DEB887";
            document.getElementById("l206_numcertidaofgts").style.backgroundColor = "#DEB887";
            document.getElementById("l206_numcertidaocndt").style.backgroundColor = "#DEB887";
            document.getElementById("l206_dataemissaoinss").style.backgroundColor = "#DEB887";
            document.getElementById("l206_dataemissaofgts").style.backgroundColor = "#DEB887";
            document.getElementById("l206_dataemissaofgts").style.backgroundColor = "#DEB887";
            document.getElementById("l206_dataemissaocndt").style.backgroundColor = "#DEB887";
            document.getElementById("l206_datavalidadeinss").style.backgroundColor = "#DEB887";
            document.getElementById("l206_datavalidadefgts").style.backgroundColor = "#DEB887";
            document.getElementById("l206_datavalidadecndt").style.backgroundColor = "#DEB887";
            return;
        }

        document.getElementById("l206_numcertidaoinss").readOnly = false;
        document.getElementById("l206_numcertidaofgts").readOnly = false;
        document.getElementById("l206_numcertidaocndt").readOnly = false;
        document.getElementById("l206_dataemissaoinss").readOnly = false;
        document.getElementById("l206_dataemissaofgts").readOnly = false;
        document.getElementById("l206_dataemissaofgts").readOnly = false;
        document.getElementById("l206_dataemissaocndt").readOnly = false;
        document.getElementById("l206_datavalidadeinss").readOnly = false;
        document.getElementById("l206_datavalidadefgts").readOnly = false;
        document.getElementById("l206_datavalidadecndt").readOnly = false;
        document.getElementById("l206_numcertidaoinss").style.backgroundColor = "white";
        document.getElementById("l206_numcertidaofgts").style.backgroundColor = "white";
        document.getElementById("l206_numcertidaocndt").style.backgroundColor = "white";
        document.getElementById("l206_dataemissaoinss").style.backgroundColor = "white";
        document.getElementById("l206_dataemissaofgts").style.backgroundColor = "white";
        document.getElementById("l206_dataemissaofgts").style.backgroundColor = "white";
        document.getElementById("l206_dataemissaocndt").style.backgroundColor = "white";
        document.getElementById("l206_datavalidadeinss").style.backgroundColor = "white";
        document.getElementById("l206_datavalidadefgts").style.backgroundColor = "white";
        document.getElementById("l206_datavalidadecndt").style.backgroundColor = "white";
    }    

    function preencheDados(codigoLicitacao) {

        document.form1.l206_licitacao.value = codigoLicitacao;
        if(typeof db_iframe_liclicita !== 'undefined'){
            db_iframe_liclicita.hide();
        }

        let oParametro = new Object();
        oParametro.sExecuta = "pesquisaTodosFornecedores";
        oParametro.codigoLicitacao = codigoLicitacao;

        js_divCarregando('Carregando fornecedores para habilitar...', 'msgBox');

        let oAjax = new Ajax.Request('lic1_habilitacaofornecedor.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametro),
            onComplete: retornoPreencheDados

        });

    }

    function retornoPreencheDados(oAjax){

        js_removeObj('msgBox');
        let oRetorno = JSON.parse(oAjax.responseText);
        document.getElementById('l206_fornecedor').options.length = 0;

        resetarCampos();

        if(oRetorno.fornecedores != null){

            for(i=0; i < oRetorno.fornecedores.length; i++){

                let nomeFornecedor = oRetorno.fornecedores[i].z01_nome;
                let codigoFornecedor = oRetorno.fornecedores[i].pc21_numcgm;

                let option = new Option(nomeFornecedor, codigoFornecedor);
                let select = document.getElementById("l206_fornecedor");
                select.add(option);

            }

        }

        oGridFornecedoresHabilitados.clearAll(true);
        oRetorno.fornecedoresHabilitados.each(function (oItem, iItem) {
            let aLinha = [];
            aLinha.push(oItem.l206_sequencial);
            aLinha.push(oItem.z01_numcgm);
            aLinha.push(oItem.z01_nome);
            aLinha.push(` <button onclick="alteracaoHabilitacao('${oItem.l206_sequencial}','${oItem.z01_nome}','${oItem.z01_numcgm}');" type="button">A</button> <button onclick="exclusaoHabilitacao(${oItem.l206_sequencial},'${oItem.z01_nome}','${oItem.z01_numcgm}');" type="button">E</button> `);
            oGridFornecedoresHabilitados.addRow(aLinha);
        });

        oGridFornecedoresHabilitados.renderRows();
        

        verificacaoDispensaInexibilidade();

    }

    function pesquisaFornecedoresParaHabilitar(){
        let oParametro = new Object();
        oParametro.sExecuta = "pesquisaFornecedoresParaHabilitar";
        oParametro.codigoLicitacao = document.getElementById("l206_licitacao").value;

        js_divCarregando('Carregando fornecedores para habilitar...', 'msgBox');

        let oAjax = new Ajax.Request('lic1_habilitacaofornecedor.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametro),
            onComplete: retornoFornecedoresParaHabilitar

        });
    }

    function retornoFornecedoresParaHabilitar(oAjax){
        js_removeObj('msgBox');
        let oRetorno = JSON.parse(oAjax.responseText);
        document.getElementById('l206_fornecedor').options.length = 0;
        for(i=0; i < oRetorno.fornecedores.length; i++){

            let nomeFornecedor = oRetorno.fornecedores[i].z01_nome;
            let codigoFornecedor = oRetorno.fornecedores[i].pc21_numcgm;

            let option = new Option(nomeFornecedor, codigoFornecedor);
            let select = document.getElementById("l206_fornecedor");
            select.add(option);

        }
    }

    function alteracaoHabilitacao(sequencialHabilitacao,nomeFornecedor,codigoFornecedor){
        
        let oParametro = new Object();
        oParametro.sExecuta = "dadosFornecedorHabilitado";
        oParametro.sequencialHabilitacao = sequencialHabilitacao;
        document.getElementById('l206_sequencial').value = sequencialHabilitacao;
        document.getElementById('db_opcao').value = "Alterar";
        document.getElementById('novo').style.display = "";
        document.getElementById('l206_fornecedor').options.length = 0;
        let option = new Option(nomeFornecedor, codigoFornecedor);
        let select = document.getElementById("l206_fornecedor");
        select.add(option);
        document.getElementById('l206_fornecedor').value = codigoFornecedor;

        js_divCarregando('Carregando dados do forncedor...', 'msgBox');

        let oAjax = new Ajax.Request('lic1_habilitacaofornecedor.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametro),
            onComplete: retornoDadosFornecedorSelecionado

        });

        verificacaoDispensaInexibilidade();
    }

    function exclusaoHabilitacao(sequencialHabilitacao,nomeFornecedor,codigoFornecedor){
        let oParametro = new Object();
        oParametro.sExecuta = "dadosFornecedorHabilitado";
        oParametro.sequencialHabilitacao = sequencialHabilitacao;
        document.getElementById('l206_sequencial').value = sequencialHabilitacao;
        document.getElementById('db_opcao').value = "Excluir";
        document.getElementById('novo').style.display = "";
        document.getElementById('l206_fornecedor').options.length = 0;
        let option = new Option(nomeFornecedor, codigoFornecedor);
        let select = document.getElementById("l206_fornecedor");
        select.add(option);
        document.getElementById('l206_fornecedor').value = codigoFornecedor;

        bloquearCampos();

        js_divCarregando('Carregando dados do forncedor...', 'msgBox');

        let oAjax = new Ajax.Request('lic1_habilitacaofornecedor.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametro),
            onComplete: retornoDadosFornecedorSelecionado

        });
    }

    function retornoDadosFornecedorSelecionado(oAjax){
        js_removeObj('msgBox');
        let oRetorno = JSON.parse(oAjax.responseText);
        document.getElementById('l206_numcertidaoinss').value = oRetorno.fornecedor[0].l206_numcertidaoinss;
        document.getElementById('l206_numcertidaofgts').value = oRetorno.fornecedor[0].l206_numcertidaofgts;
        document.getElementById('l206_numcertidaocndt').value = oRetorno.fornecedor[0].l206_numcertidaocndt;
        document.getElementById('l206_datahab').value = oRetorno.fornecedor[0].l206_datahab;
        document.getElementById('l206_dataemissaoinss').value = oRetorno.fornecedor[0].l206_dataemissaoinss;
        document.getElementById('l206_dataemissaofgts').value = oRetorno.fornecedor[0].l206_dataemissaofgts;
        document.getElementById('l206_dataemissaocndt').value = oRetorno.fornecedor[0].l206_dataemissaocndt;
        document.getElementById('l206_datavalidadeinss').value = oRetorno.fornecedor[0].l206_datavalidadeinss;
        document.getElementById('l206_datavalidadefgts').value = oRetorno.fornecedor[0].l206_datavalidadefgts;
        document.getElementById('l206_datavalidadecndt').value = oRetorno.fornecedor[0].l206_datavalidadecndt;
    }
    
    function processar() {

        let acao = document.getElementById('db_opcao').value;
        let oParametros = new Object();

        if(acao != "Excluir"){
            
            let camposInss = [$('l206_numcertidaoinss').value, $('l206_dataemissaoinss').value, $('l206_datavalidadeinss').value];
            let qntdCamposInssPreenchidos = camposInss.filter(campos => campos == "").length;

            if(qntdCamposInssPreenchidos == 1 || qntdCamposInssPreenchidos == 2){
                return alert("Caso um dos campos referentes ao INSS seja preenchido, os demais campos referentes ao INSS também devem ser preenchidos !");
            }

            let camposFgts = [$('l206_numcertidaofgts').value, $('l206_dataemissaofgts').value, $('l206_datavalidadefgts').value];
            let qntdCamposFgtsPreenchidos = camposFgts.filter(campos => campos == "").length;

            if(qntdCamposFgtsPreenchidos == 1 || qntdCamposFgtsPreenchidos == 2){
                return alert("Caso um dos campos referente ao FGTS seja preenchido, os demais campos referente ao FGTS também devem serem preenchidos !");
            }

            let camposCndt = [$('l206_numcertidaocndt').value, $('l206_dataemissaocndt').value, $('l206_datavalidadecndt').value];
            let qntdCamposCndtPreenchidos = camposCndt.filter(campos => campos == "").length;

            if(qntdCamposCndtPreenchidos == 1 || qntdCamposCndtPreenchidos == 2){
                return alert("Caso um dos campos referente ao CNDT seja preenchido, os demais campos referente ao CNDT também devem serem preenchidos !");
            }
        }

        if(acao == "Incluir"){
            oParametros.l206_numcertidaoinss = document.getElementById('l206_numcertidaoinss').value; 
            oParametros.l206_numcertidaofgts = document.getElementById('l206_numcertidaofgts').value; 
            oParametros.l206_numcertidaocndt = document.getElementById('l206_numcertidaocndt').value; 
            oParametros.l206_datahab = document.getElementById('l206_datahab').value; 
            oParametros.l206_dataemissaoinss = document.getElementById('l206_dataemissaoinss').value; 
            oParametros.l206_dataemissaofgts = document.getElementById('l206_dataemissaofgts').value; 
            oParametros.l206_dataemissaocndt = document.getElementById('l206_dataemissaocndt').value;
            oParametros.l206_datavalidadeinss = document.getElementById('l206_datavalidadeinss').value;
            oParametros.l206_datavalidadefgts = document.getElementById('l206_datavalidadefgts').value; 
            oParametros.l206_datavalidadecndt = document.getElementById('l206_datavalidadecndt').value;
            oParametros.l206_licitacao = document.getElementById('l206_licitacao').value;
            oParametros.l206_forncedor = document.getElementById('l206_fornecedor').value;
            oParametros.sExecuta = "incluirFornecedor";
            js_divCarregando('Incluindo Forncedor...', 'msgBox');
        }

        if(acao == "Alterar"){
            oParametros.l206_numcertidaoinss = document.getElementById('l206_numcertidaoinss').value; 
            oParametros.l206_numcertidaofgts = document.getElementById('l206_numcertidaofgts').value; 
            oParametros.l206_numcertidaocndt = document.getElementById('l206_numcertidaocndt').value; 
            oParametros.l206_datahab = document.getElementById('l206_datahab').value; 
            oParametros.l206_dataemissaoinss = document.getElementById('l206_dataemissaoinss').value; 
            oParametros.l206_dataemissaofgts = document.getElementById('l206_dataemissaofgts').value; 
            oParametros.l206_dataemissaocndt = document.getElementById('l206_dataemissaocndt').value;
            oParametros.l206_datavalidadeinss = document.getElementById('l206_datavalidadeinss').value;
            oParametros.l206_datavalidadefgts = document.getElementById('l206_datavalidadefgts').value; 
            oParametros.l206_datavalidadecndt = document.getElementById('l206_datavalidadecndt').value;
            oParametros.sequencialHabilitacao = document.getElementById('l206_sequencial').value;
            oParametros.l206_forncedor = document.getElementById('l206_fornecedor').value;
            oParametros.sExecuta = "alterarForncedorHabilitado";
            js_divCarregando('Alterando Dados do Forncedor...', 'msgBox');
        }

        if(acao == "Excluir"){
            oParametros.sequencialHabilitacao = document.getElementById('l206_sequencial').value;
            oParametros.sExecuta = "excluirForncedorHabilitado";
            js_divCarregando('Excluindo Dados do Forncedor...', 'msgBox');
        }

        let oAjax = new Ajax.Request('lic1_habilitacaofornecedor.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: retornoProcessamento

        });

    }

    function retornoProcessamento(oAjax) {

        js_removeObj('msgBox');
        let oRetorno = JSON.parse(oAjax.responseText);

        if(oRetorno.habilitacaoCadastroFornecedor){
            document.getElementById("cadastrarfornecedor").style.display = "";
        }

        if(oRetorno.status == "2"){
            return alert(oRetorno.erro);
        }
        
        alert(oRetorno.erro);
        reprocessarTela();

    }

    function reprocessarTela(){
        oGridFornecedoresHabilitados.clearAll(true);
        let oParametro = new Object();
        oParametro.sExecuta = "pesquisaTodosFornecedores";
        oParametro.codigoLicitacao = document.getElementById("l206_licitacao").value;
        resetarCampos();

        js_divCarregando('Carregando fornecedores para habilitar...', 'msgBox');

        let oAjax = new Ajax.Request('lic1_habilitacaofornecedor.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametro),
            onComplete: retornoPreencheDados

        });
    }

    function bloquearCampos(){

        document.getElementById("l206_numcertidaoinss").readOnly = true;
        document.getElementById("l206_numcertidaofgts").readOnly = true;
        document.getElementById("l206_numcertidaocndt").readOnly = true;
        document.getElementById("l206_datahab").readOnly = true;
        document.getElementById("l206_dataemissaoinss").readOnly = true;
        document.getElementById("l206_dataemissaofgts").readOnly = true;
        document.getElementById("l206_dataemissaofgts").readOnly = true;
        document.getElementById("l206_dataemissaocndt").readOnly = true;
        document.getElementById("l206_datavalidadeinss").readOnly = true;
        document.getElementById("l206_datavalidadefgts").readOnly = true;
        document.getElementById("l206_datavalidadecndt").readOnly = true;
        document.getElementById("l206_sequencial").readOnly = true;
        document.getElementById("l206_fornecedor").readOnly = true;

        document.getElementById("l206_numcertidaoinss").style.backgroundColor = "#DEB887";
        document.getElementById("l206_numcertidaofgts").style.backgroundColor = "#DEB887";
        document.getElementById("l206_numcertidaocndt").style.backgroundColor = "#DEB887";
        document.getElementById("l206_datahab").style.backgroundColor = "#DEB887";
        document.getElementById("l206_dataemissaoinss").style.backgroundColor = "#DEB887";
        document.getElementById("l206_dataemissaofgts").style.backgroundColor = "#DEB887";
        document.getElementById("l206_dataemissaofgts").style.backgroundColor = "#DEB887";
        document.getElementById("l206_dataemissaocndt").style.backgroundColor = "#DEB887";
        document.getElementById("l206_datavalidadeinss").style.backgroundColor = "#DEB887";
        document.getElementById("l206_datavalidadefgts").style.backgroundColor = "#DEB887";
        document.getElementById("l206_datavalidadecndt").style.backgroundColor = "#DEB887";
        document.getElementById("l206_sequencial").style.backgroundColor = "#DEB887";
        document.getElementById("l206_fornecedor").style.backgroundColor = "#DEB887";
        
    }

    function novoFornecedor(){
        resetarCampos();
        pesquisaFornecedoresParaHabilitar();
    }

    function resetarCampos(){

        document.getElementById("novo").style.display = "none";
        document.getElementById("cadastrarfornecedor").style.display = "none";
        document.getElementById("db_opcao").value = "Incluir";
        document.getElementById("l206_numcertidaoinss").readOnly = false;
        document.getElementById("l206_numcertidaofgts").readOnly = false;
        document.getElementById("l206_numcertidaocndt").readOnly = false;
        document.getElementById("l206_datahab").readOnly = false;
        document.getElementById("l206_dataemissaoinss").readOnly = false;
        document.getElementById("l206_dataemissaofgts").readOnly = false;
        document.getElementById("l206_dataemissaofgts").readOnly = false;
        document.getElementById("l206_dataemissaocndt").readOnly = false;
        document.getElementById("l206_datavalidadeinss").readOnly = false;
        document.getElementById("l206_datavalidadefgts").readOnly = false;
        document.getElementById("l206_datavalidadecndt").readOnly = false;
        document.getElementById("l206_sequencial").readOnly = false;
        document.getElementById("l206_fornecedor").readOnly = false;
        document.getElementById("l206_numcertidaoinss").style.backgroundColor = "white";
        document.getElementById("l206_numcertidaofgts").style.backgroundColor = "white";
        document.getElementById("l206_numcertidaocndt").style.backgroundColor = "white";
        document.getElementById("l206_datahab").style.backgroundColor = "white";
        document.getElementById("l206_dataemissaoinss").style.backgroundColor = "white";
        document.getElementById("l206_dataemissaofgts").style.backgroundColor = "white";
        document.getElementById("l206_dataemissaofgts").style.backgroundColor = "white";
        document.getElementById("l206_dataemissaocndt").style.backgroundColor = "white";
        document.getElementById("l206_datavalidadeinss").style.backgroundColor = "white";
        document.getElementById("l206_datavalidadefgts").style.backgroundColor = "white";
        document.getElementById("l206_datavalidadecndt").style.backgroundColor = "white";
        document.getElementById("l206_sequencial").style.backgroundColor = "white";
        document.getElementById("l206_fornecedor").style.backgroundColor = "white";
        let codigoLicitacao = document.getElementById("l206_licitacao").value;
        document.getElementById("form1").reset();
        document.getElementById("l206_licitacao").value = codigoLicitacao;

    }

    function pesquisar(){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicita.php?habilitacaofornecedor=true&funcao_js=parent.preencheDados|l20_codigo','Pesquisa',true);
    }

    function redirecionamentoCadastroFornecedor(){

        let oParams = {
            action: `com1_pcforne001.php`,
            iInstitId: top.jQuery('#instituicoes span.active').data('id'),
            iAreaId: 4,
            iModuloId: 381
        }

        let title = 'DB:PATRIMONIAL > Licitações > Cadastros > Fornecedor > Inclusão';

        Desktop.Window.create(title, oParams);

    }

</script>