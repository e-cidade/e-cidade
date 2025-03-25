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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_usuarios_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label("e60_codemp");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <?php
    db_app::load("scripts.js, prototype.js, datagrid.widget.js, messageboard.widget.js, dbtextField.widget.js");
    db_app::load("windowAux.widget.js, strings.js,dbtextFieldData.widget.js");
    db_app::load("classes/infoLancamentoContabil.classe.js");
    db_app::load("grid.style.css, estilos.css");
    ?>
    <style>
        .temdesconto {
            background-color: #D6EDFF
        }
    </style>
</head>
<body bgcolor="#CCCCCC">
<?php


/**
 * OC12145
 * Verificar se o usuario é contass e administrador e liberar menu
 *
 */

$sContass = explode(".", db_getsession("DB_login"));

if (db_getsession("DB_id_usuario") != 1 && db_getsession("DB_administrador") != 1) {
    echo "<br><center><br><H2>Essa rotina apenas poderá ser usada por usuários administradores!</h2></center>";
} else {
?>

<form name='form1'>
    <div class="container">
        <fieldset>
            <legend><b>Alterar Forma de Controle Empenho</b></legend>
            <table>
                <tr>
                    <td align="left" nowrap title="<?= $Te60_numemp ?>">
                        <? db_ancora(@$Le60_codemp, "js_pesquisa_empenho(true, true);", 1); ?>
                    </td>
                    <td nowrap>
                        <input name="e60_codemp" id='e60_codemp' title='<?= $Te60_codemp ?>' size="12" type='text'
                               readonly class="readonly"/>
                        <b>Sequencial:</b> <input name="e60_numemp" id='e60_numemp' type="text" size="10" readonly
                                                  class="readonly"/>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" id='btnVisualizarLancamento' value='Visualizar Lançamentos'>
    </div>
</form>

</body>
</html>
    <div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:400px;
            text-align: left;
            padding:3px;
            z-index:10000;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

    </div>
    <script>
        sUrlRPC = 'emp2_consultaempenho.RPC.php';

        function js_pesquisa_empenho(mostra, vlranu) {
            const url = 'func_empempenho.php?funcao_js=parent.js_mostraempenho1|e60_codemp|e60_anousu|e60_numemp';

            js_OpenJanelaIframe('CurrentWindow.corpo',
                'db_iframe_empempenho',
                url,
                'Pesquisa',
                true);
        }

        function js_mostraempempenho(chave, erro){
        }

        function js_mostraempenho1(chave1, chave2, chave3) {
            document.form1.e60_codemp.value = chave1 + "/" + chave2;
            document.form1.e60_numemp.value = chave1;
            db_iframe_empempenho.hide();
            js_visualizarEmpenhos(chave3);
        }


        function js_visualizarEmpenhos(iEmpenho) {

            $('btnVisualizarLancamento').blur();

            const oWindowLancamentos = new windowAux('wndLancamentos', 'Lancamentos Contábeis', screen.availWidth - 20, '800');
            let sContent = "<div style='text-align:center;padding:2px'>";
            sContent += "<fieldset style='text-align:center'><legend><b>Lançamentos</b></legend>";
            sContent += "<div style='width:100%' id='ctnDataGrid'>";
            sContent += "</div>";
            sContent += "</fieldset>";
            sContent += "<input type='button' accesskey='s' id='btnAlterar' value='Alterar Lançamento' onclick='js_alterarLancamento(" + iEmpenho + ")'> ";
            sContent += "<input type='button' id='btnInfo' value='Informações Empenho'> ";
            sContent += "</div>";
            oWindowLancamentos.setContent(sContent);
            oWindowLancamentos.addEvent('keydown', function (event) {

                if (event.ctrlKey) {

                    switch (event.which) {

                        case 65:

                            $('btnAlterar').click();
                            event.preventDefault();
                            event.stopPropagation();
                            break;

                        case 69:

                            $('btnExcluir').click();
                            event.preventDefault();
                            event.stopPropagation();
                            break;
                    }
                }
            });
            oWindowLancamentos.show(25, 0);

            $('btnInfo').observe('click', function () {
                js_JanelaAutomatica("empempenho", iEmpenho)
            })
            oMessage = new messageBoard('msgboard1',
                'Alteração da forma de controle dos itens do Empenho - ' + $F('e60_codemp'),
                'Selecione os itens que deseja alterar',
                $("windowwndLancamentos_content"));
            oMessage.show();
            oWindowLancamentos.setShutDownFunction(function () {

                oWindowLancamentos.destroy();
            });

            /*
             *Monta a Grid;
             */
            oGridLancamentos = new DBGrid('gridLancamentos');
            oGridLancamentos.nameInstance = 'oGridLancamentos';
            oGridLancamentos.setCheckbox(0);
            oGridLancamentos.setHeight((oWindowLancamentos.getHeight() / 2) - 30);
            oGridLancamentos.setCellWidth(['5%', '30%', '30%', '30%', '5%', '5%']);
            oGridLancamentos.setCellAlign(["left", "left", "center", "center", "center", "center"]);
            oGridLancamentos.setHeader(['Código', "Descrição do Item", 'Tipo', 'Forma de Controle', 'Autorização', 'Sequencial']);
            oGridLancamentos.allowSelectColumns(true);
            oGridLancamentos.aHeaders[5].lDisplayed = false;
            oGridLancamentos.aHeaders[6].lDisplayed = false;
            oGridLancamentos.show($('ctnDataGrid'));
            oGridLancamentos.resizeCols();
            js_getLancamentos(iEmpenho);
        }

        function js_getLancamentos(iEmpenho) {
            var oParam = {};

            oParam.exec = "getItensEmpenho";
            oParam.iCodigoEmpenho = iEmpenho;
            var oAjax = new Ajax.Request(sUrlRPC,
                {
                    method: "post",
                    parameters: 'json=' + Object.toJSON(oParam),
                    onComplete: js_retornoGetLancamentos
                });
        }

        function js_retornoGetLancamentos(oAjax) {
            const oRetorno = JSON.parse(oAjax.responseText);
            const bAlertaContrato = true;
            oGridLancamentos.clearAll(true);
            console.log(oRetorno);
            if (oRetorno.iStatus === 1) {
                for (let i = 0; i < oRetorno.aItensEmpenho.length; i++) {
                    let lBloqueia = false;
                    with (oRetorno.aItensEmpenho[i]) {
                        var aLinha = [];
                        console.log(forma_controle, tipo);
                        aLinha[0] = codigo_material;
                        aLinha[1] = descricao_material.urlDecode().substring(0, 50);
                        aLinha[2] = tipo == 'f' ? 'Material' : 'Serviço/Material Permanente';
                        aLinha[3] = '<select name="e55_servicoquantidade" id="' + e55_autori + '_' + e55_sequen + '" ' + (tipo == 'f' ? 'disabled' : '') + '><option value="t"' + (forma_controle === 't' || tipo == 'f' ? 'selected=selected' : '') + '>Quantidade</option><option value="f" ' + (forma_controle === 'f' && tipo == 't' ? 'selected=selected' : '') + '>Valor</option></select>';
                        aLinha[4] = e55_autori;
                        aLinha[5] = e55_sequen;
                        oGridLancamentos.addRow(aLinha, false, lBloqueia);
                        oGridLancamentos.aRows[i].aCells[1].sEvents += "ondblclick='js_infoLancamento(" + codigo_material + ")'";

                        if (bAlertaContrato && contrato) {
                            alert('O empenho selecionado possui vínculo com o contrato ' + contrato + '/' + contrato_ano + ', a alteração da forma de controle do empenho não altera a forma de controle do item no contrato, é importante verificar com o cliente e o setor patrimonial se será necessário alterar também o controle do item no contrato, pois o sistema não permite anular itens de empenho que possuem a forma de controle diferente do que está no contrato');
                        }
                    }
                }
                oGridLancamentos.renderRows();
            }

            if (oRetorno.aviso !== "") {
                oMessage.setHelp("<span style='color:red'>" + oRetorno.aviso.urlDecode() + "</span>");
                alert(oRetorno.aviso.urlDecode());
            }
        }

        const botao = document.querySelector('#btnAlterar');
        botao.removeEventListener('click', js_alterarLancamento);
        botao.addEventListener('click', js_alterarLancamento);
        
        function js_alterarLancamento(iEmpenho) {
            const aLancamentosSelecionados = oGridLancamentos.getSelection("object");
            if (aLancamentosSelecionados.length > 1) {
                alert('Somente poderá ser alterado um lançamento por vez!');
                return false;
            }

            if (aLancamentosSelecionados.length === 0) {
                alert('Nenhum lançamento selecionado.');
                return false;
            }
            const aLancamento = aLancamentosSelecionados[0];
            const iCodigoDesconto = aLancamento.aCells[6].getValue();
            const oParam = {};
            oParam.exec = 'alterarFormaControle';
            oParam.iAutoriza = aLancamento.aCells[5].getValue();
            oParam.iSequencia = aLancamento.aCells[6].getValue();
            formaControle = document.getElementById(oParam.iAutoriza + '_' + oParam.iSequencia);
            oParam.bFormaControle = formaControle.value;
            //return false;
            js_divCarregando('Aguarde, Alterando forma de controle...', 'msgBox');
            $('btnAlterar').disabled = true;
            try {
                new Ajax.Request(sUrlRPC,
                {
                    method: "post",
                    asynchronous: true, 
                    parameters: { 
                        _: new Date().getTime(), 
                        json:Object.toJSON(oParam)
                    },
                    onComplete: js_retornoAlterarLancamento,
                });
            } catch (error) {
                console.error('Erro inesperado:', error.message);
            }
        }

        function js_retornoAlterarLancamento(oAjax) {
            js_removeObj('msgBox');
            $('btnAlterar').disabled = false;
            var oRetorno = eval("(" + oAjax.responseText + ")");

            if (oRetorno.status == 2) {
                alert(oRetorno.message.urlDecode().replace(/\\n/g, '\n'));
            } else {
                alert('Lançamento Alterado com sucesso!');
            }
        }

        /**
         * Retorna verdadeiro se algum dos registros selecionados tem relação com ContaCorrenteDetalhe
         */
        function verificaRelacaoComContaCorrenteDetalhe(iCodigoEmpenho, iCodigoLancamento) {

            var lResultado = false;
            var oParametros = {
                exec: 'verificaRelacaoComContaCorrenteDetalhe',
                iCodigoEmpenho: iCodigoEmpenho,
                iCodigoLancamento: iCodigoLancamento
            };
            var oCallback = function (oAjax) {
                var oRetorno = eval("(" + oAjax.responseText + ")");

                lResultado = oRetorno.retorno;
            };

            new Ajax.Request(sUrlRPC, {
                asynchronous: false,
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: oCallback
            });

            return lResultado;
        }


        function js_infoLancamento(iLancamento) {
            var oLancamentoInfo = new infoLancamentoContabil(iLancamento, oWindowLancamentos);
        }


        function js_ajuda(sTexto, lShow) {

            if (lShow) {

                el = $('gridgridLancamentos');
                var x = 0;
                var y = el.offsetHeight;
                while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

                    x += el.offsetLeft;
                    y += el.offsetTop;
                    el = el.offsetParent;

                }
                x += el.offsetLeft;
                y += el.offsetTop;
                $('ajudaItem').innerHTML = sTexto;
                $('ajudaItem').style.display = '';
                $('ajudaItem').style.top = y + 10;
                $('ajudaItem').style.left = x;

            } else {
                $('ajudaItem').style.display = 'none';
            }
        }

        $('btnVisualizarLancamento').observe("click", function () {
            js_visualizarEmpenhos($F('e60_numemp'))
        });

    </script>
<?
}
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
