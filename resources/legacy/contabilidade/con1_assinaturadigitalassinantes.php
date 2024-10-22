<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_liborcamento.php");
?>
<html>
<head>
    <title>Contass Gestão e Tecnologia Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js,
                  prototype.js,
                  strings.js,
                  arrays.js,
                  windowAux.widget.js,
                  datagrid.widget.js,
                  dbmessageBoard.widget.js,
                  dbcomboBox.widget.js,
                  dbtextField.widget.js,
                  DBInputHora.widget.js,
                  datagrid/plugins/DBOrderRows.plugin.js,
                  datagrid/plugins/DBHint.plugin.js,
                  AjaxRequest.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
</head>
<body class="body-default">
<div class="container">
    <form name="form1">
        <fieldset class="form-container">
            <legend>Assinaturas</legend>
            <table id="ctnParamentro" style="width: 100%; margin-top: 10px;">
                <tr>
                    <td>
                        <?php db_input('db243_codigo', 60, "", true, 'hidden', 1, ""); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="">
                        <b>Usuário: </b>
                    </td>
                    <td nowrap id="ctnCboUsuarios">
                    </td>
                </tr>
                <tr>
                    <td nowrap title="">
                        <b>Instituições: </b>
                    </td>
                    <td nowrap id="ctnCboInstituicoes">
                    </td>
                </tr>
                <tr>
                    <td nowrap title="">
                        <b>Unidades: </b>
                    </td>
                    <td nowrap id="ctnCboUnidades"></td>
                </tr>
                <tr>
                    <td nowrap title="">
                        <b>Cargos: </b>
                    </td>
                    <td nowrap id="ctnCboCargos"></td>
                </tr>
                <tr>
                    <td nowrap title="">
                        <b>Documentos: </b>
                    </td>
                    <td nowrap id="ctnCboDocumentos"></td>
                </tr>
                <tr>
                    <td nowrap title="">
                        <b>Data Início/Fim: </b>
                    </td>
                    <td>
                        <? db_inputdata('db243_data_incio', @$db242_data_incio_dia, @$db242_data_incio_mes, @$db242_data_incio_ano, true,'text', 1, "") ?>
                        <? db_inputdata('db243_data_fim', @$db242_data_fim_dia, @$db242_data_fim_mes, @$db242_data_fim_ano,  true,'text', 1, "" ) ?>
                    </td>
                </tr>
            </table>
            <input style='margin-top: 10px;' id="btnSalvar" type="button" value="Salvar" onclick="salvarAssinantes();"/>
            <input style='margin-top: 10px;' id="" type="button" value="Alterar Vigência" onclick="alterarVigencia();"/>
            <input style='margin-top: 10px;' id="btnExcluir" type="button" value="Excluir" onclick="excluirRegistro();"/>
            <fieldset style='width:900px; margin-top: 10px;'>
                <legend>Assinantes</legend>
                <div id='ctnGridAssinates'></div>
            </fieldset>
        </fieldset>
    </form>
</div>
</body>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
<script>
    var aCargos = [ 'Outros', 'Ordenador Despesa', 'Ordenador Liquidação', 'Ordenador Pagamento', 'Contador', 'Tesoureiro', 'Gestor', 'Controle Interno' ];
    var aDocumentos = [ 'Empenho', 'Liquidação',  'Pagamento', 'Movimento Extra/Slip' ];
    var sRpc = 'con1_assinaturadigital.RPC.php';

    var oGridAssinates          = new DBGrid('gridAssinates');
    oGridAssinates.nameInstance = 'oGridAssinates';
    oGridAssinates.setCellWidth( ['7%', '10%', '30%', '23%', '11%', '13%'] );
    oGridAssinates.setHeader( ['Sel.',  'Usuarios', 'Unidade', 'Início/Fim', 'Documentos', 'Cargo'] );
    oGridAssinates.setCellAlign( ['center', 'center', 'center', 'center', 'center', 'center' ] );
    oGridAssinates.setHeight(130);
    oGridAssinates.show($("ctnGridAssinates"));

    oCboIntituicao = new DBComboBox("cboInstituicao", "oCboInstituicao", null, "820px");
    oCboIntituicao.addItem("", "Selecione");
    oCboIntituicao.addEvent("onChange", "js_getUnidades()");
    oCboIntituicao.show($('ctnCboInstituicoes'));

    oCboUsuarios = new DBComboBox("cboUsuarios", "oCboUsuarios", null, "820px");
    oCboUsuarios.addItem("", "Selecione");
    oCboUsuarios.addEvent("onChange", "js_getAssinantesSalvos()");
    oCboUsuarios.show($('ctnCboUsuarios'));

    oCboUnidades = new DBComboBox("cboUnidades", "oCboUnidades", null, "820px");
    oCboUnidades.setMultiple(true);
    oCboUnidades.show($('ctnCboUnidades'));

    oCboCargo = new DBComboBox("cboCargo", "oCboCargo", null, "820px");
    oCboCargo.setMultiple(true);
    oCboCargo.show($('ctnCboCargos'));

    oCboDocumento = new DBComboBox("cboDocumento", "oCboDocumento", null, "820px");
    oCboDocumento.setMultiple(true);
    oCboDocumento.show($('ctnCboDocumentos'));

    function formatDate(date) {
        const aDate = date.split('-');
        return aDate[2]+"/"+aDate[1]+"/"+aDate[0];
    }

    function formatDateDB(date) {
        const aDate = date.split('/');
        return aDate[0]+"/"+aDate[1]+"/"+aDate[2];
    }

    function formatDateForSave(data) {
        var parts = data.split('/');
        var dateFormated = `${parts[2]}-${parts[1]}-${parts[0]}`;
        return dateFormated;
    }

    init = function() {
        js_divCarregando('Aguarde, carregando os dados!', 'msgBox');
        js_getLoadForm();
    };

    function js_getLoadForm(){

        var oParametros = new Object();
        oParametros.sExecuta = "getLoadForm";
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function(oResponse) {
                var oRetorno = eval("(" + oResponse.responseText + ")");

                oRetorno.aIntituicoes.each(function(oInstituicao, iSeq) {
                    oCboIntituicao.addItem(oInstituicao.codigo, oInstituicao.nomeinst.urlDecode());
                });

                oRetorno.aUsuarios.each(function(oUsuario, iSeq) {
                    oCboUsuarios.addItem(oUsuario.id_usuario, oUsuario.z01_nome +" Usuário: "+oUsuario.login);
                });

                oRetorno.aCargos.each(function(oCargo, iSeq) {
                    oCboCargo.addItem(iSeq, oCargo.urlDecode());
                });

                oRetorno.aDocumentos.each(function(oDocumento, iSeq) {
                    oCboDocumento.addItem(iSeq,  oDocumento);
                })
                js_removeObj('msgBox');
            }
        });
    }

    function js_validaCampos() {
        if(empty(oCboIntituicao.getValue())){
            alert("Selecione uma instituição!");
            return false;
        }
        if(empty(oCboUnidades.getValue())){
            alert("Selecione uma Unidade!");
            return false;
        }
        if(empty(oCboCargo.getValue())){
            alert("Selecione um cargo!");
            return false;
        }
        if(empty(oCboDocumento.getValue())){
            alert("Selecione um documento!");
            return false;
        }
        return true;
    }
    /**
     * Salda os dados enviando para o RPC
     */
    function salvarAssinantes() {

        if(js_validaCampos()){

            var oParametros = {};
            oParametros.sExecuta = 'salvarAssinantes';

            oParametros.oIntituicao = oCboIntituicao.getValue();
            oParametros.oUsuario    = oCboUsuarios.getValue();
            oParametros.aUnidades   = oCboUnidades.getValue();
            oParametros.aCargos     = oCboCargo.getValue();
            oParametros.aDocumentos = oCboDocumento.getValue();
            oParametros.dataIncio   = document.form1.db243_data_incio.value;
            oParametros.dataFinal   = document.form1.db243_data_fim.value;

            if(oParametros.dataIncio == '' || oParametros.dataFinal == '') {

                alert('Necessário informar Data Início/Fim.');

            } else {

                js_divCarregando('Aguarde, salvando os dados!', 'msgBox');

                var oAjaxRequest = new AjaxRequest(sRpc, oParametros, retornoSalvar);
                oAjaxRequest.execute();
            }
        }
    }

    function alterarVigencia() {

        var checkboxes = document.querySelectorAll('.class_check');
        var marcados = [];

        var dataIncio = formatDateDB(document.getElementById('db243_data_incio').value);
        var dataFinal = formatDateDB(document.getElementById('db243_data_fim').value);

        if(dataIncio == '/undefined/undefined' || dataFinal == '/undefined/undefined') {
            alert('Para alterar a vigência, é necessário informar Data Início/Fim.');
            return;
        }

        js_divCarregando('Aguarde, alterando vigência!', 'msgBox');

        checkboxes.forEach(function(checkbox) {

            if (checkbox.checked) {

                marcados.push(checkbox.id);

                var oParametros = {};
                oParametros.sExecuta = 'getAssinante';
                oParametros.db243_codigo = checkbox.id;
            
                var oAjax = new Ajax.Request(sRpc, {
                    method: 'post',
                    parameters: 'json=' + Object.toJSON(oParametros),
                    onComplete: function(oResponse) {

                        var objResponse = JSON.parse(oResponse.responseText);
                        var oAssinante = objResponse.oAssinante;

                        var oParametrosAlter = {};

                        oParametrosAlter.sExecuta     = 'alterarAssinante';
                        oParametrosAlter.iIntituicao  = oAssinante.db243_instit;
                        oParametrosAlter.iUsuario     = oAssinante.db243_usuario;
                        oParametrosAlter.iUnidade     = oAssinante.db243_unidade;
                        oParametrosAlter.iOrgao       = oAssinante.db243_orgao;
                        oParametrosAlter.iCargo       = oAssinante.db243_cargo;
                        oParametrosAlter.iDocumento   = oAssinante.db243_documento;
                        oParametrosAlter.dataIncio    = "'" + formatDateForSave(dataIncio) + "'";
                        oParametrosAlter.dataFinal    = "'" + formatDateForSave(dataFinal) + "'";
                        oParametrosAlter.db243_codigo = oAssinante.db243_codigo;
                        
                        var oAjaxRequest = new AjaxRequest(sRpc, oParametrosAlter, retornoSalvarVigencia);
                        oAjaxRequest.execute();
                    }
                });
            }
        });

        if(marcados.length === 0) {
            alert('É necessário selecionar o(s) registro(s) para a alteração.')
            js_removeObj('msgBox');
        }
    }

    function retornoSalvar(oRetorno, lErro) {
        js_removeObj('msgBox');
        if (oRetorno.iStatus == 1) {
            alert("Dados salvos com sucesso !");
        } else {
            alert(oRetorno.sMensagem);
        }
        js_limparAssinates();
        js_getAssinantesSalvos();
    }

    function salvarAlteracao() {

        js_divCarregando('Aguarde, salvando os dados!', 'msgBox');
        var oParametros = {};
        oParametros.sExecuta = 'alterarAssinante';

        oParametros.iIntituicao = oCboIntituicao.getValue();
        oParametros.iUsuario = oCboUsuarios.getValue();
        oParametros.iUnidade = oCboUnidades.getValue();
        oParametros.iCargo = oCboCargo.getValue();
        oParametros.iDocumento = oCboDocumento.getValue();
        oParametros.dataIncio = formatDateDB(document.form1.db243_data_incio.value);
        oParametros.dataFinal = formatDateDB(document.form1.db243_data_fim.value);
        oParametros.db243_codigo = document.form1.db243_codigo.value;

        var oAjaxRequest = new AjaxRequest(sRpc, oParametros, retornoSalvarAlteracao);
        oAjaxRequest.execute();
    }

    function retornoSalvarVigencia(oRetorno, lErro) {
        js_removeObj('msgBox');

        js_getAssinantesSalvos();

        if (oRetorno.iStatus == 1) {
            alert("Dados salvos com sucesso !");
        } else {
            alert(oRetorno.sMensagem);
        }
    }

    function retornoSalvarAlteracao(oRetorno, lErro) {
        js_removeObj('msgBox');
        if (oRetorno.iStatus == 1) {
            alert("Dados salvos com sucesso !");
            js_limparAssinates();
            js_getAssinantesSalvos();
        } else {
            alert(oRetorno.sMensagem);
        }
    }

    function js_excluirAssinante(db243_codigo) {
        var oParametros = {};
        oParametros.sExecuta        = 'excluirAssinante';
        oParametros.db243_codigo   = db243_codigo;
        var oAjaxRequest = new AjaxRequest(sRpc, oParametros, retornoExcluir);
        oAjaxRequest.execute();
    }

    function excluirRegistro() {

        var checkboxes = document.querySelectorAll('.class_check');
        var marcados = [];

        checkboxes.forEach(function(checkbox) {

            if (checkbox.checked) {
                marcados.push(checkbox.id);
                js_excluirAssinante(checkbox.id);
            }
        });

        if(marcados.length === 0) {
            alert('É necessário selecionar o(s) registro(s) para a exclusão.')
            js_removeObj('msgBox');
        }
    }

    function retornoExcluir(oRetorno, lErro) {
        js_removeObj('msgBox');
        if (oRetorno.iStatus == 1) {
            alert("Dados Excluído com sucesso!");
        } else {
            alert(oRetorno.sMensagem);
        }
        js_getAssinantesSalvos();
    }

    function js_getAssinantesSalvos(){
        js_divCarregando('Aguarde, carregando os dados!', 'msgBox');
        iUsuario = oCboUsuarios.getValue();
        if(empty(oCboUsuarios.getValue())){
            alert("Selecione um usuário!");
            return false;
        }
        oGridAssinates.clearAll(true);
        var oParametros = new Object();
        oParametros.sExecuta = 'getAssinantes';
        oParametros.iUsuario = iUsuario;
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function(oResponse) {

                oGridAssinates.clearAll(true);

                var oRetorno = eval("(" + oResponse.responseText + ")");
                oRetorno.aAssinantes.each(function(oAssinante, iSeq) {
                    var aLinha = [];
                    aLinha.push("<input type='checkbox' class='class_check' id='"+oAssinante.db243_codigo+"' />");
                    aLinha.push(oAssinante.login);
                    aLinha.push(oAssinante.db243_orgao.toString().padStart(2, '0')+oAssinante.db243_unidade.toString().padStart(3, '0')+" "+oAssinante.o41_descr);
                    aLinha.push(formatDate(oAssinante.db243_data_inicio.urlDecode())+' até '+formatDate(oAssinante.db243_data_final.urlDecode()));
                    aLinha.push(aDocumentos[oAssinante.db243_documento]);
                    aLinha.push(aCargos[oAssinante.db243_cargo]);
                    oGridAssinates.addRow(aLinha);
                });
                oGridAssinates.renderRows();
                js_removeObj('msgBox');
            }
        });
    }

    function js_getUnidades(iIntit){
        js_divCarregando('Aguarde, Carregando os dados!', 'msgBox');
        iIntituicao = oCboIntituicao.getValue();
        if(iIntit){
            iIntituicao = iIntit;
        }

        if(empty(iIntituicao)){
            alert("Selecione um Instituição!");
            return false;
        }
        oCboUnidades.clearItens();
        var oParametros = new Object();
        oParametros.sExecuta = 'getUnidades';
        oParametros.iIntituicao = iIntituicao;
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function(oResponse) {
                var oRetorno = eval("(" + oResponse.responseText + ")");
                oRetorno.aUnidades.each(function(oUnidade, iSeq) {
                    oCboUnidades.addItem(oUnidade.o41_orgao + "-" + oUnidade.o41_unidade, oUnidade.o41_orgao.toString().padStart(2, '0') + "." + oUnidade.o41_unidade.toString().padStart(3, '0') + " " + oUnidade.o41_descr.urlDecode());
                });
                js_removeObj('msgBox');
            }
        });

    }

    function js_alterarParametro(iAssinante) {
        js_divCarregando('Aguarde, carregando os dados!', 'msgBox');
        var oParametros = {};
        oParametros.sExecuta        = 'getAssinante';
        oParametros.db243_codigo    = iAssinante;
        var oAjaxRequest = new AjaxRequest(sRpc, oParametros, retornoAlterar);
        oAjaxRequest.execute();
    }

    function retornoAlterar(oResponse, lErro) {
        js_removeObj('msgBox');
        if (oResponse.iStatus == 1) {
            var oAssinante = oResponse.oAssinante;
            js_getUnidades(oAssinante.db243_instit);
            oCboIntituicao.setValue(oAssinante.db243_instit);
            oCboUnidades.setMultiple(false);
            oCboUnidades.setValue(oAssinante.db243_orgao+"-"+oAssinante.db243_unidade);
            oCboCargo.setMultiple(false);
            oCboCargo.setValue(oAssinante.db243_cargo);
            oCboDocumento.setMultiple(false);
            oCboDocumento.setValue(oAssinante.db243_documento);
            document.form1.db243_codigo.value = oAssinante.db243_codigo;
            document.form1.db243_data_incio.value = formatDate(oAssinante.db243_data_inicio);
            document.form1.db243_data_fim.value = formatDate(oAssinante.db243_data_final);
            document.form1.btnAlterar.type = 'button';
            document.form1.btnSalvar.type = 'hidden';

        } else {
            alert(oResponse.sMensagem);
        }
    }

    function  js_limparAssinates(){
        oCboUnidades.setValue('');
        oCboCargo.setValue('');
        oCboDocumento.setValue('');
        oCboUnidades.setMultiple(true);
        oCboCargo.setMultiple(true);
        oCboDocumento.setMultiple(true);
    }

    init();
</script>

