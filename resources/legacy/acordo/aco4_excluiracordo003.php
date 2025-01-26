<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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
require_once("libs/db_app.utils.php");
require_once("libs/db_libdicionario.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oDaoAcordo = new cl_acordo();
$oDaoAcordo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac17_sequencial");
$clrotulo->label("descrdepto");
$clrotulo->label("ac02_sequencial");
$clrotulo->label("ac08_descricao");
$clrotulo->label("ac50_descricao");
$clrotulo->label("z01_nome");

$iOpcao = 3;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("estilos.css");
  ?>
</head>
<body bgcolor=#CCCCCC>
  <form action="" method="post" class="container">
    <fieldset>
      <legend class='bold'>Exclus�o de Acordo</legend>
      <table class='form-container'>
        <tr>
          <td nowrap ><?=$Lac16_sequencial?></td>
          <td>
            <?
              db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_origem?></td>
          <td>
            <?
             $aValores = array(
                                0 => 'Selecione',
                                1 => 'Processo de Compras',
                                2 => 'Licita��o',
                                3 => 'Manual' ,
                                6 => 'Empenho'
                              );
              db_input('ac16_origem', 10, $Iac16_origem, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?= @$Tac16_tipoorigem ?>">
            <?= @$Lac16_tipoorigem ?>
          </td>
          <td>
            <?
            $aValores = array(
                0 => 'Selecione',
                1 => 'N�o ou dispensa por valor',
                2 => 'Licita��o',
                3 => 'Dispensa ou Inexigibilidade',
                4 => 'Ades�o � ata de registro de pre�os',
                5 => 'Licita��o realizada por outro �rg�o ou entidade',
                6 => 'Dispensa ou Inexigibilidade realizada por outro �rg�o ou entidade',
                7 => 'Licita��o - Regime Diferenciado de Contrata��es P�blicas ? RDC',
                8 => 'Licita��o realizada por consorcio p�blico',
                9 => 'Licita��o realizada por outro ente da federa��o',
            );
            db_select('ac16_tipoorigem', $aValores, true, $db_opcao,
                " onchange='js_validaOrigemTipo();' style='width:100%;'");

            ?>
          </td>
        </tr>
        <tr>
          <td nowrap>Natureza do Contrato:</td>
          <td>
            <?
              db_input('ac16_acordogrupo', 10, $Iac16_acordogrupo, true, 'text', $iOpcao);
              db_input('ac02_descricao', 30, $Iac02_sequencial, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_numeroacordo?></td>
          <td>
            <?
              db_input('ac16_numeroacordo', 10, $Iac16_numeroacordo, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_contratado;?></td>
          <td>
            <?
              db_input('ac16_contratado', 10, $Iac16_contratado, true, 'text', $iOpcao);
              db_input('nomecontratado', 30, $Iz01_nome, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap>
            <label>Depto Respons�vel:</label>
          </td>
          <td>
            <?
              db_input('ac16_deptoresponsavel', 10, $Iac16_deptoresponsavel, true, 'text', $iOpcao);
              db_input('descrdepto', 30, $Idescrdepto, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap>
            <label>Comiss�o:</label>
          </td>
          <td>
            <?
              db_input('ac16_acordocomissao', 10, $Iac16_acordocomissao, true, 'text', $iOpcao);
              db_input('ac08_descricao', 30, $Iac08_descricao, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_lei;?></td>
          <td>
            <?
              db_input('ac16_lei', 50, $Iac16_lei, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_numeroprocesso;?></td>
          <td>
            <?
              db_input('ac16_numeroprocesso', 50, $Iac16_numeroprocesso, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap><?=$Lac16_qtdrenovacao;?></td>
          <td>
            <?
              db_input('ac16_qtdrenovacao', 2, @$Iac16_qtdrenovacao, true, 'text', $iOpcao);
              db_input("ac16_tipounidtempo", 4, $Iac16_tipounidtempo, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td>
             <label>Contrato Emergencial:</label>
          </td>
          <td>
            <?
              $aEmergencial = array("f" => "N�o", "t" => "Sim");
              db_select("ac26_emergencial", $aEmergencial, true, $iOpcao);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap><?=$Lac16_dataassinatura?></td>
          <td>
            <?
              db_inputdata('ac16_dataassinatura', @$ac16_dataassinatura_dia, @$ac16_dataassinatura_mes,
                           @$ac16_dataassinatura_ano, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tac16_datapublicacao?>">
            <?=@$Lac16_datapublicacao?>
          </td>

          <td>
            <?
            db_inputdata('ac16_datapublicacao', @$ac16_datapublicacao_dia, @$ac16_datapublicacao_mes,
                @$ac16_datapublicacao_ano, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>

        <tr>
        	<td>
        		<label>Per�odos por M�s Comercial:</label>
        	</td>
        	<td>
        		<?
        		  $aDivisaoPeriodos = array("false" => "N�O", "true" => "SIM");
        		  db_select("ac16_periodocomercial", $aDivisaoPeriodos, true, $iOpcao);
        		?>
        	</td>
        </tr>
        <tr>
          <td nowrap>
            <label>Categoria:</label>
          </td>
          <td>
            <?
              db_input('ac50_sequencial', 10, $Iac50_descricao, true, 'text', $iOpcao);
              db_input('ac50_descricao', 30, $Iac50_descricao, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tac16_formafornecimento?>">
            <?=@$Lac16_formafornecimento?>
          </td>
          <td>
            <?
            db_input('ac16_formafornecimento', 50, $Iac16_formafornecimento, true, 'text', $iOpcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tac16_veiculodivulgacao?>">
            <?=@$Lac16_veiculodivulgacao?>
          </td>
          <td>
            <?
            db_input('ac16_veiculodivulgacao', 50, $Iac16_veiculodivulgacao, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tac16_formapagamento?>">
            <?=@$Lac16_formapagamento?>
          </td>
          <td>
            <?
            db_textarea('ac16_formapagamento', 3, 48, $Iac16_objeto, true, 'text', $db_opcao, "","","","100");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tac16_cpfsignatariocontratante?>">
            <?=@$Lac16_cpfsignatariocontratante?>
          </td>
          <td>
            <?
            db_input('ac16_cpfsignatariocontratante', 11, $Iac16_cpfsignatariocontratante, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class='separator'>
              <legend>Vig�ncia</legend>
              <table>
                <tr>
                  <td>
                    <label>Inicio:</label>
                  </td>
                  <td>
                    <?
                      db_inputdata('ac16_datainicio', @$ac16_datainicio_dia, @$ac16_datainicio_mes,
                                   @$ac16_datainicio_ano, true, 'text', $iOpcao);
                    ?>
                  </td>
                  <td class="vigencia_final" style="text-indent: 30px;">
                    <label>Fim:</label>
                  </td>
                  <td class="vigencia_final" style="text-align: right;">
                    <?
                      db_inputdata('ac16_datafim', @$ac16_datafim_dia, @$ac16_datafim_mes, @$ac16_datafim_ano, true, 'text', $iOpcao);
                    ?>
                  </td>
                  <td style="text-indent: 50px;">
                    <label>Dias:</label>
                  </td>
                  <td style="text-align: right;">
                    <?
                      db_input('diasvigencia', 10, "", true, 'text', $iOpcao);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Per�odo:</label>
                  </td>
                  <td>
                    <?
                      db_input('ac16_qtdperiodo', 2, @$Iac16_qtdperiodo, true, 'text', $iOpcao);
                    ?>
                  </td>
                  <td colspan="4">
                    <?
                      db_input("ac16_tipounidtempoperiodo", 4, $Iac16_tipounidtempoperiodo, true, 'text', $iOpcao);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class='fieldsetinterno'>
              <legend>
                <b>Dados complementares PNCP</b>
              </legend>
              <table cellpadding="0" border="0" width="100%" class="table-dados-complementares-pncp">
                <tr id="trTipoPagamento">
                  <td 
                    nowrap
                    title="
                      Tipo de pagamento dos dados complementares para o pncp 
                  
                      Campo:ac16_tipopagamento
                    "
                  >
                    <strong>Tipo de Pagamento:</strong>
                  </td>
                  <td>
                    <?php
                      $aTipoPagamento = array("Selecione", "Conforme demanda", "Mensal");
                      db_select('ac16_tipopagamento', $aTipoPagamento, true, $db_opcao, "onchange='js_alteracaoTipoPagamento(this.value)';", "");
                    ?>
                  </td>
                </tr>

                <tr id='trNumeroParcela' style="display: none;">
                  <td 
                    nowrap
                    title="
                      N�mero de parcela dos dados complementares para o pncp 
                  
                      Campo:ac16_numparcela
                    "
                  >
                    <strong>N�mero de Parcela:</strong>
                  </td>
                  <td><?php db_input('ac16_numparcela', 10, true, true, 'int', $db_opcao); ?></td>
                </tr>

                <tr id='trValorParcela' style="display: none;">
                  <td 
                    nowrap
                    title="
                      Valor da parcela dos dados complementares para o pncp 
                  
                      Campo:ac16_vlrparcela
                    "
                  >
                    <strong>Valor da Parcela:</strong>
                  </td>
                  <td><?php db_input('ac16_vlrparcela', 10, 4, true, 'float', $db_opcao); ?></td>
                </tr>

                <tr>
                  <td
                    nowrap
                    title="
                      Url do CIPI dos dados complementares para o pncp 
                  
                      Campo:ac16_urlcipi
                    "
                  >
                    <strong>Url CIPI:</strong>
                  </td>
                  <td><?php db_input('ac16_urlcipi', 50, false, true, 'text', $db_opcao, "", "", "#E6E4F1", "", "14"); ?></td>
                </tr>

                <tr>
                  <td 
                    nowrap 
                    title="
                      Identificador do CIPI para os dados complementares do pncp
                      
                      Campo:ac16_identificadorcipi
                    "
                  >
                    <strong>Identificar CIPI:</strong>
                  </td>
                  <td><?php db_textarea('ac16_identificadorcipi', 3, 52, false, true, 'text', $db_opcao, "", "", "#E6E4F1", "512"); ?></td>
                </tr>

                <tr>
                  <td 
                    nowrap
                    title="
                      Descri��o das informa��es complementares para o pncp 
                  
                      Campo:ac16_infcomplementares
                    "
                  >
                    <strong>Informa��es Complementares:</strong>
                  </td>
                  <td><?php db_textarea('ac16_infcomplementares', 3, 52, false, true, 'text', $db_opcao, "", "", "#E6E4F1", "5120"); ?></td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
	      <tr>
	        <td nowrap colspan="2">
	          <fieldset>
	            <legend><?=@$Lac16_objeto?></legend>
	            <?
	              db_textarea('ac16_objeto', 3, 58, $Iac16_objeto, true, 'text', $iOpcao);
	            ?>
	          </fieldset>
	        </td>
	      </tr>
	      <tr>
	        <td nowrap colspan="2">
	          <fieldset>
	            <legend><?=@$Lac16_resumoobjeto?></legend>
				        <?
				          db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', $iOpcao);
				        ?>
	          </fieldset>
	        </td>
	      </tr>
      </table>
    </fieldset>
    <input id="btnExcluir" type="button" value="Excluir" disabled="disabled" />
    <input id="btnPesquisar" type="button" value="Pesquisar" />
  </form>
</body>
<?
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>

$("ac16_sequencial").addClassName("field-size2");
$("ac16_origem").addClassName("field-size9");
$("ac16_tipoorigem").addClassName("field-size9");
$("ac16_acordogrupo").addClassName("field-size2");
$("ac02_descricao").addClassName("field-size7");
$("ac16_numeroacordo").addClassName("field-size2");
$("ac16_contratado").addClassName("field-size2");
$("nomecontratado").addClassName("field-size7");
$("ac16_deptoresponsavel").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("ac16_acordocomissao").addClassName("field-size2");
$("ac08_descricao").addClassName("field-size7");
$("ac16_lei").addClassName("field-size9");
$("ac16_numeroprocesso").addClassName("field-size9");
$("ac16_qtdrenovacao").addClassName("field-size2");
$("ac16_tipounidtempo").addClassName("field-size2");
$("ac26_emergencial_select_descr").addClassName("field-size2");
$("ac16_dataassinatura").addClassName("field-size2");
$("ac16_datapublicacao").addClassName("field-size2");
$("ac16_periodocomercial_select_descr").addClassName("field-size2");
$("ac50_sequencial").addClassName("field-size2");
$("ac50_descricao").addClassName("field-size7");
$("ac16_datainicio").addClassName("field-size2");
$("ac16_datafim").addClassName("field-size2");
$("diasvigencia").addClassName("field-size2");
$("ac16_qtdperiodo").addClassName("field-size2");
$("ac16_tipounidtempoperiodo").addClassName("field-size9");

$("ac16_objeto").style.width       = "100%";
$("ac16_resumoobjeto").style.width = "100%";

var sCaminhoMensagens = 'patrimonial.contratos.aco4_excluiracordo003.';
var sRpc              = 'con4_contratos.RPC.php';

$('btnPesquisar').observe("click", function() {
  pesquisaAcordos();
});

$('btnExcluir').observe("click", function() {
  removerAcordo();
});

/**
 * Pesquisa os arquivos pass�veis de exclus�o
 */
function pesquisaAcordos() {

  var sUrl = 'func_acordo.php?funcao_js=parent.buscaDadosAcordo|ac16_sequencial&lParaExcluir=true&iTipoFiltro=1&lAtivo=1&lComExecucao=false';
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_acordo',
                       sUrl,
                       'Pesquisar Acordos',
                       true
                     );
}

/**
 * Busca os dados do acordo selecionado na lookup
 *
 * @param integer iAcordo - C�digo do acordo
 */

function buscaDadosAcordo( iAcordo ) {

  db_iframe_acordo.hide();
  limpaCampos();

  var oParametro           = new Object();
      oParametro.exec      = 'getDadosAcordo';
      oParametro.iContrato = iAcordo;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = retornoBuscaDadosAcordo;

  js_divCarregando( _M( sCaminhoMensagens+'buscando_acordo' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorna os dados do acordo para preenchimento do formul�rio
 */
function retornoBuscaDadosAcordo( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );

  if ( oRetorno.status != 1 ) {

    alert( oRetorno.message.urlDecode() );
    return false;
  }

  var aOrigens    = new Array();
      aOrigens[1] = 'Processo de Compras';
      aOrigens[2] = 'Licita��o';
      aOrigens[3] = 'Manual';
      aOrigens[6] = 'Empenho';

  var aPeriodo    = new Array();
      aPeriodo[1] = 'M�s';
      aPeriodo[2] = 'Dia';

  var aRenovacao    = new Array();
      aRenovacao[1] = 'M�s';
      aRenovacao[2] = 'Dias';

  $("ac16_sequencial").value               = oRetorno.contrato.iSequencial;
  $("ac16_origem").value                   = aOrigens[oRetorno.contrato.iOrigem];
  $("ac16_tipoorigem").value                   = oRetorno.contrato.iTipoOrigem;
  $("ac16_acordogrupo").value              = oRetorno.contrato.iGrupo;
  $("ac16_numeroacordo").value                   = oRetorno.contrato.iNumero;
  $("ac16_contratado").value               = oRetorno.contrato.iContratado;
  $("nomecontratado").value                = oRetorno.contrato.sNomeContratado.urlDecode();
  $("ac16_deptoresponsavel").value         = oRetorno.contrato.iDepartamentoResponsavel;
  $("descrdepto").value                    = oRetorno.contrato.sNomeDepartamentoResponsavel.urlDecode();
  $("ac16_acordocomissao").value           = oRetorno.contrato.iComissao;
  $("ac08_descricao").value                = oRetorno.contrato.sNomeComissao.urlDecode();
  $("ac16_lei").value                      = oRetorno.contrato.sLei.urlDecode();
  $("ac16_numeroprocesso").value           = oRetorno.contrato.sNumeroProcesso.urlDecode();
  $("ac16_formafornecimento").value        = oRetorno.contrato.sFormaFornecimento.urlDecode();
  $("ac16_veiculodivulgacao").value        = oRetorno.contrato.sVeiculoDivulgacao.urlDecode();
  $("ac16_formapagamento").value           = oRetorno.contrato.sFormaPagamento.urlDecode();
  $("ac16_cpfsignatariocontratante").value = oRetorno.contrato.sCpfsignatariocontratante.urlDecode();
  $("ac16_qtdrenovacao").value             = oRetorno.contrato.iNumeroRenovacao;
  $("ac16_tipounidtempo").value            = aRenovacao[oRetorno.contrato.iTipoRenovacao];
  $("ac26_emergencial_select_descr").value = 'N�o';

  if ( oRetorno.contrato.lEmergencial ) {
    $("ac26_emergencial_select_descr").value = 'Sim';
  }

  $("ac16_dataassinatura").value                = oRetorno.contrato.dtAssinatura.urlDecode();
  $("ac16_datapublicacao").value                = oRetorno.contrato.dtPublicacao.urlDecode();
  $("ac16_periodocomercial_select_descr").value = 'N�o';

  if ( oRetorno.contrato.lPeriodoComercial ) {
    $("ac16_periodocomercial_select_descr").value = 'Sim';
  }

  $("ac50_sequencial").value           = oRetorno.contrato.iCategoriaAcordo;
  $("ac16_datainicio").value           = oRetorno.contrato.dtInicio.urlDecode();
  $("ac16_datafim").value              = oRetorno.contrato.dtTermino.urlDecode();
  $("diasvigencia").value              = '';
  $("ac16_qtdperiodo").value           = oRetorno.contrato.iQtdPeriodoVigencia;
  $("ac16_tipounidtempoperiodo").value = aPeriodo[oRetorno.contrato.iTipoUnidadeTempoVigencia];
  $("ac16_objeto").value               = oRetorno.contrato.sObjeto.urlDecode();
  $("ac16_resumoobjeto").value         = oRetorno.contrato.sResumoObjeto.urlDecode();

  if (oRetorno.contrato.iTipoPagamento == 2) {
    document.getElementById('trNumeroParcela').style.display = '';
    document.getElementById('trValorParcela').style.display = '';
  }

  document.getElementById('ac16_tipopagamento').value = oRetorno.contrato.iTipoPagamento;
  document.getElementById('ac16_numparcela').value = oRetorno.contrato.iNumeroParcela;
  document.getElementById('ac16_vlrparcela').value = oRetorno.contrato.iValorParcela;
  document.getElementById('ac16_identificadorcipi').value = oRetorno.contrato.sIdentificarCipi;
  document.getElementById('ac16_urlcipi').value = oRetorno.contrato.sUrlCipi;
  document.getElementById('ac16_infcomplementares').value = oRetorno.contrato.sInformacoesComplementares;

  js_alteracaoVigencia(oRetorno.contrato.iVigenciaIndeterminada);

  setTimeout( function() {
                            pesquisaAcordoGrupo( oRetorno.contrato.iGrupo );
                            pesquisaCategoria( oRetorno.contrato.iCategoriaAcordo );
                            $('diasvigencia').value = js_somarDiasVigencia( $("ac16_datainicio").value, $("ac16_datafim").value );
                         }, 300 );


  $('btnExcluir').disabled = false;
}

/**
 * Remove o acordo selecionado ap�s confirma��o
 */
function removerAcordo() {

  var oVariaveisACordo            = new Object();
      oVariaveisACordo.iAcordo    = $("ac16_sequencial").value;
      oVariaveisACordo.sDescricao = $("ac02_descricao").value;

  if ( confirm( _M( sCaminhoMensagens+'confirma_exclusao', oVariaveisACordo ) ) ) {

    var oParametro         = new Object();
        oParametro.exec    = 'excluirAcordo';
        oParametro.iAcordo = $("ac16_sequencial").value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = retornoRemoverAcordo;

    js_divCarregando( _M( sCaminhoMensagens+'excluindo_acordo' ), "msgBox" );
    new Ajax.Request( sRpc, oDadosRequisicao );
  }
}

/**
 * Retorno da remo��o do acordo
 */
function retornoRemoverAcordo( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );

  alert( oRetorno.message.urlDecode() );

  if ( oRetorno.status == 1 ) {

    limpaCampos();
    $('btnExcluir').disabled = true;
  }
}

/**
 * Limpa os campos do formul�rio
 */
function limpaCampos() {

  $("ac16_sequencial").value                    = '';
  $("ac16_origem").value                        = '';
  $("ac16_acordogrupo").value                   = '';
  $("ac02_descricao").value                     = '';
  $("ac16_numeroacordo").value                        = '';
  $("ac16_contratado").value                    = '';
  $("nomecontratado").value                     = '';
  $("ac16_deptoresponsavel").value              = '';
  $("descrdepto").value                         = '';
  $("ac16_acordocomissao").value                = '';
  $("ac08_descricao").value                     = '';
  $("ac16_lei").value                           = '';
  $("ac16_numeroprocesso").value                = '';
  $("ac16_qtdrenovacao").value                  = '';
  $("ac16_tipounidtempo").value                 = '';
  $("ac26_emergencial_select_descr").value      = '';
  $("ac16_dataassinatura").value                = '';
  $("ac16_datapublicacao").value                = '';
  $("ac16_periodocomercial_select_descr").value = '';
  $("ac50_sequencial").value                    = '';
  $("ac50_descricao").value                     = '';
  $("ac16_datainicio").value                    = '';
  $("ac16_datafim").value                       = '';
  $("diasvigencia").value                       = '';
  $("ac16_qtdperiodo").value                    = '';
  $("ac16_tipounidtempoperiodo").value          = '';
  $("ac16_objeto").value                        = '';
  $("ac16_resumoobjeto").value                  = '';
  $("ac16_tipopagamento").value                 = '';
  $("ac16_urlcipi").value                       = '';
  $("ac16_identificadorcipi").value             = '';
  $("ac16_infcomplementares").value             = '';
  $("ac16_tipoorigem").value                    = '';
  $("ac16_formafornecimento").value             = '';
  $("ac16_veiculodivulgacao").value             = '';
  $("ac16_formapagamento").value                = '';
}

/**
 * Pesquisa a descri��o de um acordogrupo
 *
  @param integer iAcordoGrupo - C�digo de acordogrupo
 */
function pesquisaAcordoGrupo( iAcordoGrupo ) {

  var sUrl = 'func_acordogrupo.php?funcao_js=parent.retornoPesquisaAcordoGrupo&pesquisa_chave='+iAcordoGrupo;
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_acordogrupo',
                       sUrl,
                       'Pesquisar Grupos de Acordo',
                       false
                     );
}

/**
 * Retorno da descri��o de acordogrupo
 */
function retornoPesquisaAcordoGrupo() {

  db_iframe_acordogrupo.hide();
  if ( arguments[1] == false ) {
    $("ac02_descricao").value = arguments[0];
  }
}

/**
 * Pesquisa a descri��o da categoria
 *
 * @param integer iCategoria - C�digo da categoria
 */
function pesquisaCategoria( iCategoria ) {

  var sUrl = 'func_acordocategoria.php?funcao_js=parent.retornoPesquisaCategoria&pesquisa_chave='+iCategoria;
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_acordocategoria',
                       sUrl,
                       'Pesquisar Categorias de Acordo',
                       false
                     );
}

/**
 * Retorno da descri��o da categoria
 */
function retornoPesquisaCategoria() {

  db_iframe_acordocategoria.hide();
  if ( arguments[1] == false ) {
    $("ac50_descricao").value = arguments[0];
  }
}

function js_alteracaoVigencia(vigenciaIndeterminada){

  if(vigenciaIndeterminada == "t"){
      document.getElementsByClassName('vigencia_final')[0].style.display = 'none';
      document.getElementsByClassName('vigencia_final')[1].style.display = 'none';
      return;
  }
  document.getElementsByClassName('vigencia_final')[0].style.display = '';
  document.getElementsByClassName('vigencia_final')[1].style.display = '';

}

function js_alteracaoTipoPagamento(tipoPagamento) {

  if(tipoPagamento == 0 || tipoPagamento == 1) {
    document.getElementById('trNumeroParcela').style.display = 'none';
    document.getElementById('ac16_numparcela').value = "";

    document.getElementById('trValorParcela').style.display = 'none';
    document.getElementById('ac16_vlrparcela').value = "";
  } else {
    document.getElementById('trNumeroParcela').style.display = '';
    document.getElementById('trValorParcela').style.display = '';
  }

  return;
}

pesquisaAcordos();
</script>
