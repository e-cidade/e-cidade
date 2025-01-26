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

require_once(modification("libs/db_stdlib.php"));
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");
require_once("model/protocolo/AssinaturaDigital.model.php");
$oAssintaraDigital = new AssinaturaDigital();

$clrotulo = new rotulocampo;
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e50_codord");
$clrotulo->label("e50_numliquidacao");
$clrotulo->label("e53_valor");
$clrotulo->label("e81_codmov");
$db_opcao = 1;

db_app::load("scripts.js,
              strings.js,
              prototype.js,
              widgets/DBLancador.widget.js,
              widgets/DBToogle.widget.js,
              estilos.css,
              AjaxRequest.js,
            ");
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" height='18' border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
        <td width="360">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
    </tr>
</table>
<center>
    <table valign="top" marginwidth="0" width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td valign="top" bgcolor="#CCCCCC">
                <form name='form1'>
                    <fieldset>
                        <table>
                            <tr>
                                <td nowrap title="<?= @$Te60_codemp ?>">
                                    <? db_ancora(@$Le60_codemp, "js_pesquisae60_codemp(true);", 1); ?>
                                    <span style="margin-left: 8px;">
                                     <? db_input('e60_codemp', 13, $Ie60_codemp, true, 'text', $db_opcao, " onchange='js_pesquisae60_codemp(false);'", "e60_codemp_ini") ?>
                                      <strong> / </strong>
                                     <? db_input('e60_codemp', 13, $Ie60_codemp, true, 'text', $db_opcao, "", "e60_codemp_fim") ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Te60_numemp ?>">
                                    <? db_ancora(@$Le60_numemp, "js_pesquisae60_numemp(true);", 1); ?>
                                    <span style="margin-left: 46px;">
                                           <? db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', $db_opcao, " onchange='js_pesquisae60_numemp(false);'") ?>
                                      </span>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Te50_numliquidacao ?>">
                                    <?= @$Le50_numliquidacao ?>
                                    <? db_input('e50_numliquidacao', 13, $Ie50_numliquidacao, true, 'text', $db_opcao) ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Te50_codord ?>">
                                    <b><? db_ancora("Ordem:", "js_pesquisae50_codord(true);", 1); ?></b>
                                    <span style="margin-left: 91px;">
                                       <? db_input('e50_codord', 13, $Ie50_codord, true, 'text', $db_opcao, " onchange='js_pesquisae50_codord(false);'", "e50_codord_ini") ?>
                                     <strong> / </strong>
                                     <? db_input('e50_codord', 13, $Ie50_codord, true, 'text', $db_opcao, "", "e50_codord_fim") ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap><?php db_ancora("Movimento", 'js_pesquisaMovimento(true);', 1) ?>
                                <span style="margin-left: 70px;">
                                    <?php db_input('e81_codmov', 13, $Ie82_codmov, true, 'text', $db_opcao, " onchange='js_pesquisaMovimento(false);' ", 'e81_codmov') ?></td>
                                <strong>
                            </tr>
                            <tr>
                                <td align="left">
                                    <b> Per�odo: </b>
                                    <span style="margin-left: 85px;">
                                    <? db_inputdata('dtini', @$dia, @$mes, @$ano, true, 'text', 1, "");
                                    echo " a ";
                                    db_inputdata('dtfim', @$dia, @$mes, @$ano, true, 'text', 1, "");
                                    ?>
                                  </span>
                                </td>
                            </tr>
                            <tr>

                                <td colspan="2">
                                    <div id='ctnLancadorFornecedor'></div>
                                <td>

                            </tr>

                            <!-- Oc18018 -->
                            <tr>
                                <td>
                                    <?
                                    $oFiltroRecursos = new cl_arquivo_auxiliar;
                                    $oFiltroRecursos->cabecalho = "<strong>Recursos</strong>";
                                    $oFiltroRecursos->codigo = "o15_codigo";
                                    $oFiltroRecursos->descr = "o15_descr";
                                    $oFiltroRecursos->nomeobjeto = 'recursos';
                                    $oFiltroRecursos->funcao_js = 'js_mostraRecurso';
                                    $oFiltroRecursos->funcao_js_hide = 'js_mostraRecursoHide';
                                    $oFiltroRecursos->func_arquivo = "func_orctiporec.php";
                                    $oFiltroRecursos->nomeiframe = "db_iframe_orctiporec";
                                    $oFiltroRecursos->vwidth = '500';
                                    $oFiltroRecursos->db_opcao = 2;
                                    $oFiltroRecursos->tipo = 2;
                                    $oFiltroRecursos->top = 0;
                                    $oFiltroRecursos->linhas = 5;
                                    $oFiltroRecursos->nome_botao = 'lancarRecurso';
                                    $oFiltroRecursos->lFuncaoPersonalizada = true;
                                    $oFiltroRecursos->obrigarselecao = false;
                                    $oFiltroRecursos->localjan = '';
                                    $oFiltroRecursos->tamanho_campo_descricao = 40;
                                    $oFiltroRecursos->funcao_gera_formulario();
                                    ?>
                                </td>
                            </tr>
                            <!-- .end Oc18018 -->

                        </table>
                    </fieldset>
                </form>
            </td>
        </tr>
        <tr>
            <td colspan='2' align='center'>
                <input name='pesquisar' type='button' value='Emitir' onclick='js_abre();'>
                <?php
                    if ($oAssintaraDigital->verificaAssituraAtiva()) {
                        echo "<input name='imprimirassinado' type='button' value='Imprimir Com Assinatura Digital' onclick='js_solicitarImpressaoComAssinatura();'>";
                        echo "<input name='solicitar' type='button' value='Solicitar Nova Assinatura Digital' style='margin-left: 5px;' onclick='js_solicitarAssinatura();'>";
                    }
                ?>
            </td>
        </tr>
    </table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>


    //ctnLancadorFornecedor
    var oLancadorFornecedor = new DBLancador('LancadorFornecedor');
    oLancadorFornecedor.setLabelAncora("Fornecedor:");
    oLancadorFornecedor.setTextoFieldset("Fornecedores");
    oLancadorFornecedor.setTituloJanela("Pesquisar Fornecedores");
    oLancadorFornecedor.setNomeInstancia("oLancadorFornecedor");
    oLancadorFornecedor.setParametrosPesquisa("func_nome.php", ["z01_numcgm", "z01_nome"]);
    oLancadorFornecedor.setGridHeight(150);
    oLancadorFornecedor.show($("ctnLancadorFornecedor"));

    function getForncedores() {

        var aFornecedore = [];
        var aSelecionados = oLancadorFornecedor.getRegistros();

        aSelecionados.each(function (oDados, iIndice) {
            aFornecedore.push(oDados.sCodigo);
        });

        return aFornecedore;
    }


    function js_abre() {

        var obj = document.form1;
        var sDtini = obj.dtini.value.split("/");
        var sDtfim = obj.dtfim.value.split("/");
        var e50_codord_ini = obj.e50_codord_ini.value;
        var e50_codord_fim = obj.e50_codord_fim.value;
        var e60_codemp_ini = obj.e60_codemp_ini.value;
        var e60_codemp_fim = obj.e60_codemp_fim.value;
        var e60_numemp = obj.e60_numemp.value;
        var dtini_dia = sDtini[0];
        var dtini_mes = sDtini[1];
        var dtini_ano = sDtini[2];
        var dtfim_dia = sDtfim[0];
        var dtfim_mes = sDtfim[1];
        var dtfim_ano = sDtfim[2];
        var aFornecedores = getForncedores();
        var e50_numliquidacao = obj.e50_numliquidacao.value;

        var query = '';

        if (e50_codord_ini != '') {
            query += "&e50_codord_ini=" + e50_codord_ini;
            if (e50_codord_fim != '') {
                if (Number(e50_codord_fim) < Number(e50_codord_ini)) {
                    alert("Ordem inicial maior que o Ordem:O final. Verifique!");
                    return false;
                }
            }
            query += "&e50_codord_fim=" + e50_codord_fim;
        } else {

            if ((dtini_dia != '') && (dtini_mes != '') && (dtini_ano != '')) {
                query += "&dtini=" + dtini_ano + "X" + dtini_mes + "X" + dtini_dia;
            }
            if ((dtfim_dia != '') && (dtfim_mes != '') && (dtfim_ano != '')) {
                query += "&dtfim=" + dtfim_ano + "X" + dtfim_mes + "X" + dtfim_dia;
            }
        }

        if (e60_codemp_ini != '') {
            query += "&e60_codemp_ini=" + obj.e60_codemp_ini.value;

            if (e60_codemp_fim != '') {
                if (Number(e60_codemp_fim) < Number(e60_codemp_ini)) {
                    alert("Empenho inicial maior que o empenho final. Verifique!");
                    return false;
                }
            }
            query += "&e60_codemp_fim=" + obj.e60_codemp_fim.value;
        }

        if (e60_numemp != '') {
            query += "&e60_numemp=" + e60_numemp;
        }

        if (e50_numliquidacao != '') {
            if (e60_numemp != '' || (e60_codemp_ini != '' && e60_codemp_fim == '')) {
                query += "&e50_numliquidacao=" + e50_numliquidacao;
            } else {
                alert("Indique o Sequencial do Empenho para utilizar o filtro N�mero da Liquida��o.");
                return false;
            }
        }

        if (query == '') {
            alert("Selecione alguma ordem de pagamento ou indique o per�odo!");
        } else {
            var sUrl = 'emp2_emitenotadespesa002.php?aFornecedor=' + aFornecedores + "&recursos=" + js_campo_recebe_valores_recursos() + query;
            jan = window.open(sUrl,
                '',
                'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
            jan.moveTo(0, 0);
        }
    }


    function js_pesquisae60_codemp(mostra) {

        var obj = document.form1;
        var e60_codemp_ini = obj.e60_codemp_ini.value;
        var sUrl1 = 'func_empempenho.php?funcao_js=parent.js_mostracodemp1|e60_codemp';
        var sUrl2 = 'func_empempenho.php?pesquisa_chave=' + e60_codemp_ini + '&funcao_js=parent.js_mostracodemp';

        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', sUrl1, 'Pesquisa', true);
        } else {
            if (e60_codemp_ini != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', sUrl2, 'Pesquisa', false);
            } else {
                obj.e60_codemp_ini.value = '';
            }
        }
    }

    function js_mostracodemp(chave, erro) {
        var obj = document.form1;

        if (erro == true) {
            obj.e50_codemp_ini.focus();
            obj.e50_codemp_ini.value = '';
        }
    }

    function js_mostracodemp1(chave1, x) {
        var obj = document.form1;
        obj.e60_codemp_ini.value = chave1;
        db_iframe_empempenho.hide();
    }

    function js_pesquisae60_numemp(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', 'func_empempenho.php?funcao_js=parent.js_mostranumemp1|e60_numemp', 'Pesquisa', true);
        } else {
            if (document.form1.e60_numemp.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', 'func_empempenho.php?pesquisa_chave=' + document.form1.e60_numemp.value + '&funcao_js=parent.js_mostranumemp', 'Pesquisa', false);
            } else {
                document.form1.e60_numemp.value = '';
            }
        }
    }

    function js_mostranumemp(chave, erro) {
        if (erro == true) {
            document.form1.e50_codemp.focus();
            document.form1.e50_codemp.value = '';
        }
    }

    function js_mostranumemp1(chave1, x) {
        document.form1.e60_numemp.value = chave1;
        db_iframe_empempenho.hide();
    }

    function js_pesquisae50_codord(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_pagordem', 'func_pagordem.php?funcao_js=parent.js_mostracodordem1|e50_codord', 'Pesquisa', true);
        } else {
            if (document.form1.e50_codord_ini.value != '') {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_pagordem', 'func_pagordem.php?pesquisa_chave=' + document.form1.e50_codord_ini.value + '&funcao_js=parent.js_mostracodordem', 'Pesquisa', false);
            } else {
                document.form1.e50_codord_ini.value = '';
            }
        }
    }

    function js_mostracodordem(chave, erro) {
        if (erro == true) {
            document.form1.e50_codord_ini.focus();
            document.form1.e50_codord_ini.value = '';
        }
    }

    function js_mostracodordem1(chave1, x) {
        document.form1.e50_codord_ini.value = chave1;
        db_iframe_pagordem.hide();
    }

    var oDBToogleFornecedores = new DBToogle('fieldset_recursos', false);
    var oDBToogleRecursos = new DBToogle('LancadorFornecedor', false);
    $('tr_inicio_recursos').getElementsByTagName("table")[0].align = 'left';


    function js_solicitarAssinatura() {

        if (!confirm("Esse procedimento invalidar� as assinaturas j� realizadas. Deseja continuar?")) {
            return;
        }
        obj = document.form1;
        if (obj.e81_codmov.value == '' && obj.dtini.value == ''  && obj.dtfim.value == '') {
            alert("Selecione um Movimento ou um Per�odo!");
            return;
        }
        js_divCarregando('Aguarde, Enviando documentos para assinatura','msgbox');
        var oParametros = new Object();
        oParametros.codmov = obj.e81_codmov.value;
        var sDtini = obj.dtini.value.split("/");
        var sDtfim = obj.dtfim.value.split("/");
        var dtini_dia = sDtini[0];
        var dtini_mes = sDtini[1];
        var dtini_ano = sDtini[2];
        var dtfim_dia = sDtfim[0];
        var dtfim_mes = sDtfim[1];
        var dtfim_ano = sDtfim[2];
        if ((dtini_dia != '') && (dtini_mes != '') && (dtini_ano != '')) {
            oParametros.dtini_ano = dtini_ano;
            oParametros.dtini_mes = dtini_mes;
            oParametros.dtini_dia = dtini_dia;
        }
        if ((dtfim_dia != '') && (dtfim_mes != '') && (dtfim_ano != '')) {
            oParametros.dtfim_ano = dtfim_ano;
            oParametros.dtfim_mes = dtfim_mes;
            oParametros.dtfim_dia = dtfim_dia;
        }
        oParametros.sExecuta = 'enviarOrdemaPagamentoAssinaturaLote';
        console.log('aqui')
        var oAjax  = new Ajax.Request(
            'con1_assinaturaDigitalDocumentos.RPC.php',
            {
                method: 'post',
                parameters: 'json='+Object.toJSON(oParametros),
                onComplete: js_retornoSolicitarAssinatura
            }
        );
    }

    function js_retornoSolicitarAssinatura(oRequest){
        js_removeObj('msgbox');
        var oRetorno = eval("("+oRequest.responseText+")");
        if (oRetorno.erro) {
            alert(oRetorno.sMensagem.urlDecode());
            return;
        }
        alert('Documentos enviados para assinatura com sucesso!');
    }

    function js_solicitarImpressaoComAssinatura() {
        obj = document.form1;
        if (obj.e81_codmov.value == '' && obj.dtini.value == '') {
            alert("Selecione um movimento ou um per�do de datas!");
            return;
        }
        var oParametros = new Object();
        oParametros.codmov = obj.e81_codmov.value;
        var sDtini = obj.dtini.value.split("/");
        var sDtfim = obj.dtfim.value.split("/");
        var dtini_dia = sDtini[0];
        var dtini_mes = sDtini[1];
        var dtini_ano = sDtini[2];
        var dtfim_dia = sDtfim[0];
        var dtfim_mes = sDtfim[1];
        var dtfim_ano = sDtfim[2];
        if ((dtini_dia != '') && (dtini_mes != '') && (dtini_ano != '')) {
            oParametros.dtini_ano = dtini_ano;
            oParametros.dtini_mes = dtini_mes;
            oParametros.dtini_dia = dtini_dia;
        }
        if ((dtfim_dia != '') && (dtfim_mes != '') && (dtfim_ano != '')) {
            oParametros.dtfim_ano = dtfim_ano;
            oParametros.dtfim_mes = dtfim_mes;
            oParametros.dtfim_dia = dtfim_dia;
        }

        oParametros.method = 'post';
        oParametros.sExecuta = 'imprimirDocumentoAssinadoOrdemPagamento';
        window.open('con1_assinaturaDigitalDocumentos.RPC.php?json=' + encodeURIComponent(JSON.stringify(oParametros)), "_blank");


    }

    function js_pesquisaMovimento(mostra) {
        if (mostra) {
            if(!$F('e50_codord_ini')){
                alert('Selecione um Ordem de Pagamento!');
                return;
            }

            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_movs',
                'func_empagemovordem.php?e50_codord=' + $F('e50_codord_ini') + '&funcao_js=parent.js_mostramov1|e81_codmov',
                'Pesquisa', true);
        }
    }

    function js_mostramov1(chave,erro){
        $('e81_codmov').value = chave;
        db_iframe_movs.hide();
    }

</script>
