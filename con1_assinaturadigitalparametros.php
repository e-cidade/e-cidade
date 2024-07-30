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
            <legend>Parâmetros</legend>
            <table id="ctnParamentro" style="width: 100%; margin-top: 10px;">
                <tr>
                    <td>
                        <?php db_input('db242_codigo', 60, "", true, 'hidden', 1, ""); ?>
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
                    <td title="assinador_url" style="width: 35%">
                        <strong>Link NextCloud:<strong/>
                    </td>
                    <td>
                        <?php db_input('db242_assinador_url', 60, "", true, 'text', 1, ""); ?>
                    </td>
                </tr>
                <tr>
                    <td title="assinador_token" style="width: 35%">
                        <strong>Token Nextcloud:</strong>
                    </td>
                    <td>
                        <?php db_input('db242_assinador_token', 60, "", true, 'text', 1, ""); ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Ativo:</b></td>
                    <td>
                        <?php
                        $aMostrarEmpenho = array("0" => "Não", "1" => "Sim" );
                        db_select("db242_assinador_ativo", $aMostrarEmpenho, true, 2);
                        ?>
                    </td>
                </tr>
            </table>
            <input id="btnSalvar" type="button" value="Salvar" onclick="salvarParamentros();"/>
            <fieldset style='width:800px; margin-top: 10px;'>
                <legend>Parametros Assinatura Digital</legend>
                <div id='ctnGridParametrosAssinturaDigital'></div>
            </fieldset>
        </fieldset>

    </form>
</div>
</body>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>
    var sRpc = 'con1_assinaturadigital.RPC.php';

    var oGridParametrosAssinturaDigital          = new DBGrid('gridParametrosAssinturaDigital');
    oGridParametrosAssinturaDigital.nameInstance = 'oGridParametrosAssinturaDigital';
     oGridParametrosAssinturaDigital.setCellWidth( [ '40%', '30%', '10%', '10%', '10%' ] );
    oGridParametrosAssinturaDigital.setHeader( [  'Instituição', 'Url', 'Token', 'Ativo',  'Ação' ] );
    oGridParametrosAssinturaDigital.setCellAlign( [ 'left', 'center', 'center', 'center', 'center' ] );
    oGridParametrosAssinturaDigital.setHeight(130);
    oGridParametrosAssinturaDigital.show($("ctnGridParametrosAssinturaDigital"));


    init = function() {
        js_getParametrosSalvos();
        js_getInstituicoes();
    };

    function js_getInstituicoes(){
        var oParametros = new Object();
        oParametros.sExecuta = "getInstituicoes";
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function(oResponse) {
                var oRetorno = eval("(" + oResponse.responseText + ")");
                oCboIntituicao = new DBComboBox("cboInstituicao", "oCboInstituicao", null, "415px");
                oCboIntituicao.addItem("", "Selecione");
                oCboIntituicao.show($('ctnCboInstituicoes'));
                oRetorno.aIntituicoes.each(function(oInstituicao, iSeq) {
                    oCboIntituicao.addItem(oInstituicao.codigo, oInstituicao.nomeinst.urlDecode());
                });
            }
        });

    }

    function js_getParametrosSalvos(){
        oGridParametrosAssinturaDigital.clearAll(true);
        var oParametros = new Object();
        oParametros.sExecuta = 'getParametrosAssinaturaDigital';
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: function(oResponse) {
                var oRetorno = eval("(" + oResponse.responseText + ")");
                oRetorno.aParamentrosAssintura.each(function(oParamentrosAssintura, iSeq) {
                    var aLinha = [];
                    aLinha.push(oParamentrosAssintura.nomeinst.urlDecode());
                    aLinha.push(oParamentrosAssintura.db242_assinador_url.urlDecode());
                    aLinha.push(oParamentrosAssintura.db242_assinador_token.urlDecode());
                    aLinha.push(oParamentrosAssintura.db242_assinador_ativo ? 'ATIVO' : 'BLOQUEADO');
                    aLinha.push("<input style='margin-right: 5px; background: red;' type='button' onclick=js_excluirParametro(\'" + oParamentrosAssintura.db242_codigo + "\') value='E'/>" +
                        "<input type='button' style='background: blue; color: white;' onclick=js_getParametro(\'" + oParamentrosAssintura.db242_codigo + "\') value='A'/>")

                    oGridParametrosAssinturaDigital.addRow(aLinha);
                });
                oGridParametrosAssinturaDigital.renderRows();
            }
        });

    }

    function js_validaCampos() {
        if (empty($('db242_assinador_url').value)) {
            alert('Informe uma URL.');
            return false;
        }
        if (empty($('db242_assinador_token').value)) {
            alert('Informe uma TOKEN.');
            return false;
        }
        if (empty(oCboIntituicao.getValue())) {
            alert('Informe uma TOKEN.');
            return false;
        }
        return true;
    }
    /**
     * Salda os dados enviando para o RPC
     */
    function salvarParamentros() {
        if(js_validaCampos()){
            if(!empty(document.form1.db242_codigo.value)){
                js_alterarParametro();
            }else {
                js_divCarregando('Aguarde, salvando os dados!', 'msgBox');
                var oParametros = {};
                oParametros.sExecuta = 'salvarParamentros';
                oParametros.assinador_url = document.form1.db242_assinador_url.value;
                oParametros.assinador_token = document.form1.db242_assinador_token.value;
                oParametros.institucao = oCboIntituicao.getValue();
                oParametros.assinador_ativo = document.form1.db242_assinador_ativo.value;

                var oAjaxRequest = new AjaxRequest(sRpc, oParametros, retornoSalvar);
                oAjaxRequest.execute();
            }
            js_limparParametro();
            js_getParametrosSalvos();
        }
    }

    function retornoSalvar(oRetorno, lErro) {
        js_removeObj('msgBox');
        if (oRetorno.iStatus == 1) {
            alert("Dados salvos com sucesso !");
        } else {
            alert(oRetorno.sMensagem);
        }
    }

    function js_excluirParametro(db242_codigo) {
        var oParametros = {};
        oParametros.sExecuta        = 'excluirParamentros';
        oParametros.db242_codigo   = db242_codigo;
        var oAjaxRequest = new AjaxRequest(sRpc, oParametros, retornoExcluir);
        oAjaxRequest.execute();
    }

    function retornoExcluir(oRetorno, lErro) {
        js_removeObj('msgBox');
        if (oRetorno.iStatus == 1) {
            alert("Dados Excluído com sucesso!");
        } else {
            alert(oRetorno.sMensagem);
        }
        js_getParametrosSalvos();
    }

    function js_getParametro(db242_codigo) {
        js_limparParametro();
        var obj = new Object();
        obj.sExecuta = 'getParamentros';
        obj.db242_codigo = db242_codigo;
        var oAjax = new Ajax.Request(sRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(obj),
            onComplete: function(oResponse) {
                var oRetorno = eval("(" + oResponse.responseText + ")");
                oCboIntituicao.setValue(oRetorno.oParametro.db242_instit);
                document.form1.db242_codigo.value           = oRetorno.oParametro.db242_codigo;
                document.form1.db242_assinador_url.value    = oRetorno.oParametro.db242_assinador_url.urlDecode();
                document.form1.db242_assinador_token.value  = oRetorno.oParametro.db242_assinador_token.urlDecode();
                document.form1.db242_assinador_ativo.value  = oRetorno.oParametro.db242_assinador_ativo ? 1 : 0;
            }
        });
    }

    function js_alterarParametro() {
        js_divCarregando('Aguarde, alterando os dados!', 'msgBox');
        var oParametros = {};
        oParametros.sExecuta        = 'alterarParamentro';
        oParametros.codigo          = document.form1.db242_codigo.value;
        oParametros.assinador_url   = document.form1.db242_assinador_url.value;
        oParametros.assinador_token = document.form1.db242_assinador_token.value;
        oParametros.institucao      = oCboIntituicao.getValue();
        oParametros.assinador_ativo = document.form1.db242_assinador_ativo.value;

        var oAjaxRequest = new AjaxRequest(sRpc, oParametros, retornoAlterar);
        oAjaxRequest.execute();
    }

    function retornoAlterar(oRetorno, lErro) {
        js_removeObj('msgBox');
        if (oRetorno.iStatus == 1) {
            alert("Dados Alterados com sucesso!");
        } else {
            alert(oRetorno.sMensagem);
        }
    }

    function js_limparParametro(){
        document.form1.db242_codigo.value = '';
        document.form1.db242_assinador_url.value   = '';
        document.form1.db242_assinador_token.value = '';
        oCboIntituicao.setValue('');
    }
    init();
</script>

