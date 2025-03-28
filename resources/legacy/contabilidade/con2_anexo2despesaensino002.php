<?
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

require_once("dbforms/db_funcoes.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_sql.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_slip_classe.php");
require_once("classes/db_infocomplementaresinstit_classe.php");
require_once("classes/db_empresto_classe.php");
require_once("classes/db_empempenho_classe.php");
require_once("model/contabilidade/relatorios/ensino/RelatorioReceitaeDespesaEnsino.model.php");
use \Mpdf\Mpdf;
use \Mpdf\MpdfException;

$clselorcdotacao = new cl_selorcdotacao();
$clrotulo = new rotulocampo;

db_postmemory($HTTP_POST_VARS);

$dtini = implode("-", array_reverse(explode("/", $DBtxt21)));
$dtfim = implode("-", array_reverse(explode("/", $DBtxt22)));

$clinfocomplementaresinstit = new cl_infocomplementaresinstit();
$clSlip = new cl_slip();

$instits = str_replace('-', ', ', $db_selinstit);
$aInstits = explode(",", $instits);

if (count($aInstits) > 1) {
    $oInstit = new Instituicao();
    $oInstit = $oInstit->getDadosPrefeitura();
} else {
    foreach ($aInstits as $iInstit) {
        $oInstit = new Instituicao($iInstit);
    }
}
db_inicio_transacao();

/**
 * pego todas as institui��es;
 */
$rsInstits = $clinfocomplementaresinstit->sql_record($clinfocomplementaresinstit->sql_query(null, "si09_instit, si09_tipoinstit", null, null));

$ainstitunticoes = array();
for ($i = 0; $i < pg_num_rows($rsInstits); $i++) {
    $odadosInstint = db_utils::fieldsMemory($rsInstits, $i);
    $ainstitunticoes[] = $odadosInstint->si09_instit;
}
$iInstituicoes = implode(',', $ainstitunticoes);

$rsTipoinstit = $clinfocomplementaresinstit->sql_record($clinfocomplementaresinstit->sql_query(null, "si09_sequencial, si09_tipoinstit", null, "si09_instit in( {$instits})"));

/**
 * busco o tipo de instituicao
 */
$ainstitunticoes = array();
$aTipoistituicao = array();

for ($i = 0; $i < pg_num_rows($rsTipoinstit); $i++) {
    $odadosInstint = db_utils::fieldsMemory($rsTipoinstit, $i);
    $aTipoistituicao[] = $odadosInstint->si09_tipoinstit;
    $iCont = pg_num_rows($rsTipoinstit);
}


$sWhereDespesa      = " o58_instit in({$instits})";
db_query("drop table if exists work_dotacao");
criaWorkDotacao($sWhereDespesa,array($anousu), $dtini, $dtfim);

$sWhereReceita      = "o70_instit in ({$instits})";
$rsReceitas = db_receitasaldo(11, 1, 3, true, $sWhereReceita, $anousu, $dtini, $dtfim, false, ' * ', true, 0);
$aReceitas = db_utils::getColectionByRecord($rsReceitas);
db_query("drop table if exists work_receita");
criarWorkReceita($sWhereReceita, array($anousu), $dtini, $dtfim);


$nTotalReceitasRecebidasFundeb = 0;
$nContribuicaoFundeb = 0;

$nDevolucaoRecursoFundeb = 0;
$rsSlip = $clSlip->sql_record($clSlip->sql_query_fundeb($dtini, $dtfim, $aInstits));
$nDevolucaoRecursoFundeb = db_utils::fieldsMemory($rsSlip, 0)->k17_valor;

$nTransferenciaRecebidaFundeb = 0;

$aTransferenciasRecebidasFundeb = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '417515001%'");

$nTransferenciaRecebidaFundeb = count($aTransferenciasRecebidasFundeb) > 0 ? $aTransferenciasRecebidasFundeb[0]->saldo_arrecadado_acumulado : 0;

$aTotalContribuicaoFundeb = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '495%'");
$nTotalContribuicaoFundeb = count($aTotalContribuicaoFundeb) > 0 ? $aTotalContribuicaoFundeb[0]->saldo_arrecadado_acumulado : 0;

$nTotalReceitasRecebidasFundeb = abs($nDevolucaoRecursoFundeb) + abs($nTransferenciaRecebidaFundeb);
$nResulatadoLiquidoTransfFundeb = $nTotalReceitasRecebidasFundeb-abs($nTotalContribuicaoFundeb);



$fSubTotal = 0;
$aSubFuncoes = array(122,272,271,361,365,366,367,843);
$sFuncao     = "12";
$aFonte      = array("'101','1101','15000001','201','25000001'");
$aFonteFundeb      = array("'118','119','1118','1119','15400007','15400000'");

$oReceitaeDespesaEnsino = new RelatorioReceitaeDespesaEnsino();


/**
 * mPDF
 * @param string $mode | padr�o: BLANK
 * @param mixed $format | padr�o: A4
 * @param float $default_font_size | padr�o: 0
 * @param string $default_font | padr�o: ''
 * @param float $margin_left | padr�o: 15
 * @param float $margin_right | padr�o: 15
 * @param float $margin_top | padr�o: 16
 * @param float $margin_bottom | padr�o: 16
 * @param float $margin_header | padr�o: 9
 * @param float $margin_footer | padr�o: 9
 *
 * Nenhum dos par�metros � obrigat�rio
 */
try {
$mPDF = new Mpdf([
    'mode' => '',
    'format' => 'A4',
    'orientation' => 'L',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 20,
    'margin_bottom' => 10,
    'margin_header' => 5,
    'margin_footer' => 11,
]);
// DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
date_default_timezone_set('America/Sao_Paulo');
// CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HOR�RIO DEFINIDO (BRAS�LIA)
$dataLocal = date('d/m/Y H:i:s', time());

$header = " <header>
                <div style=\" height: 120px; font-family:Arial\">
                    <div style=\"width:33%; float:left; padding:5px; font-size:10px;\">
                        <b><i>{$oInstit->getDescricao()}</i></b><br/>
                        <i>{$oInstit->getLogradouro()}, {$oInstit->getNumero()}</i><br/>
                        <i>{$oInstit->getMunicipio()} - {$oInstit->getUf()}</i><br/>
                        <i>{$oInstit->getTelefone()} - CNPJ: " . db_formatar($oInstit->getCNPJ(), "cnpj") . "</i><br/>
                        <i>{$oInstit->getSite()}</i>
                    </div>
                    <div style=\"width:25%; float:right\" class=\"box\">
                        <b>Relat�rio Despesa Ensino - Anexo III</b><br/>
                        <b>INSTITUI��ES:</b> ";
                        foreach ($aInstits as $iInstit) {
                            $oInstituicao = new Instituicao($iInstit);
                            $header .= "(" . trim($oInstituicao->getCodigo()) . ") " . $oInstituicao->getDescricao() . " ";
                        }
                        $header .= "<br/> <b>PER�ODO:</b> {$DBtxt21} A {$DBtxt22} <br/>
                    </div>
                </div>
           </header>";

$footer = "<footer>
                <div style='border-top:1px solid #000;width:100%;font-family:sans-serif;font-size:10px;height:10px;'>
                    <div style='text-align:left;font-style:italic;width:90%;float:left;'>
                        Financeiro>Contabilidade>Relat�rios de Acompanhamento>Receita Despesa Ensino - Anexo III
                        Emissor: " . db_getsession("DB_login") . " Exerc: " . db_getsession("DB_anousu") . " Data:" . $dataLocal  . "
                    <div style='text-align:right;float:right;width:10%;'>
                        {PAGENO}
                    </div>
                </div>
            </footer>";

$mPDF->WriteHTML(file_get_contents('estilos/tab_relatorio.css'), 1);
$mPDF->setHTMLHeader(utf8_encode($header), 'O', true);
$mPDF->setHTMLFooter(utf8_encode($footer), 'O', true);
$mPDF->shrink_tables_to_fit = 1;

ob_start();
?>
<html>
    <head>
        <style type="text/css">
            .ritz .waffle {
                color: inherit;
                font-family: 'Arial';
                font-size: 10px;
                width: 100%;
            }
            .title-relatorio {
                text-align: center;
                padding-top: 50px;
            }
            .tr-table{
                height:20px;
            }
            .body-relatorio {
                width: 100%;
                height: 80%;
            }
            .footer-row {
                height: 20px;
                background-color: #d8d8d8;
                width: 80%;
                border: 1px SOLID #000000;
                font-family: 'Arial';
                font-size: 10px;
                font-weight: bold;
                padding: 2px 3px 2px 3px;
                text-align: right;
                vertical-align: bottom;
                white-space: nowrap;
            }
            .footer-row-valor {
                height: 20px;
                background-color: #d8d8d8;
                width: 20%;
                border-right: 1px SOLID #000000;
                border-top: 1px SOLID #000000;
                border-bottom: 1px SOLID #000000;
                font-family: 'Arial', Calibre;
                font-size: 10px;
                font-weight: bold;
                padding: 2px 3px 2px 3px;
                text-align: right;
                vertical-align: bottom;
                white-space: nowrap;
            }
            .footer-total-row {
                height: 20px;
                background-color: #d8d8d8;
                width: 80%;
                border: 1px SOLID #000000;
                font-family: 'Arial';
                font-size: 10px;
                font-weight: bold;
                padding: 2px 3px 2px 3px;
                text-align: left;
                vertical-align: bottom;
                white-space: nowrap;
            }
            .footer-total-row-valor {
                height: 20px;
                background-color: #d8d8d8;
                width: 20%;
                border-right: 1px SOLID #000000;
                border-top: 1px SOLID #000000;
                border-bottom: 1px SOLID #000000;
                font-family: 'Arial', Calibre;
                font-size: 10px;
                font-weight: bold;
                padding: 2px 3px 2px 3px;
                text-align: right;
                vertical-align: bottom;
                white-space: nowrap;
            }
            .title-row {
                background-color: #ffffff;
                direction: ltr;
                padding: 2px 3px 2px 3px;
                font-size: 10px;
                font-weight: bold;
            }
            .subtitle-2-row {
                background-color: #d8d8d8;
                direction: ltr;
                padding: 2px 3px 2px 3px;
                font-size: 10px;
                border-left: 1px SOLID #000000;
                border-right: 1px SOLID #000000;
                font-weight: bold;
            }
            .subtitle-4-row {
                background-color: #d8d8d8;
                direction: ltr;
                padding: 2px 3px 2px 3px;
                font-size: 10px;
                font-weight: bold;
            }
            .subtitle-3-row {
                background-color: #d8d8d8;
                direction: ltr;
                padding: 2px 3px 2px 3px;
                font-size: 10px;
                border-left: 1px SOLID #000000;
                border-right: 1px SOLID #000000;
                font-weight: bold;
            }
            .subtitle-row {
                background-color: #d8d8d8;
                direction: ltr;
                border: 0.5px SOLID #000000;
                font-size: 10px;
                padding: 2px 3px 2px 3px;
                font-weight: bold;
            }
            .text-row {
                background-color: #ffffff;
                color: #000000;
                direction: ltr;
                font-size: 10px;
                vertical-align: bottom;
                white-space: nowrap;
                padding: 2px 2px 2px 2px;
            }
            .ritz .waffle .clear {
                background-color: #ffffff;
                color: #000000;
                direction: ltr;
                font-size: 10pt;
                padding: 2px 3px 2px 3px;
                white-space: nowrap;
            }
        </style>
    </head>

    <body>
        <div class="ritz ">
            <div class="title-relatorio">
                <strong>Anexo III</strong><br />
                <strong>Demonstrativo dos Gastos com Manuten��o e Desenvolvimento do Ensino</strong><br />
                <strong> (Art. 212 da CR/88; EC n�53/06, Leis 9.394/96, 14.113/2020 e IN 05/2012)</strong><br /><br />
            </div>
            <div class="body-relatorio">
                <table class="waffle" width="600px" cellspacing="0" cellpadding="0" style="border: 1px #000" autosize="1">
                    <tbody>
                        <tr>
                            <td class="title-row" colspan="5">I - DESPESAS</td>
                        </tr>
                        <tr>
                            <td class="subtitle-row" style="width: 300px;">FUN��O / SUBFUN��O/ PROGRAMA</td>
                            <td class="subtitle-row" style="width: 100px; text-align: center;">VALOR PAGO</td>
                            <td class="subtitle-row" style="text-align: center;">VALOR EMPENHADO E N�O LIQUIDADO</td>
                            <td class="subtitle-row" style="text-align: center;">VALOR LIQUIDADO A PAGAR</td>
                            <td class="subtitle-row" style="width: 100px; text-align: center;">TOTAL</td>
                        </tr>
                        <tr>
                            <?if(db_getsession("DB_anousu") > 2022) {?>
                                <td class="subtitle-2-row" colspan="5">1 - EDUCA��O 12 - IMPOSTOS E TRANSFER�NCIAS DE IMPOSTOS </td>
                            <?} else {?>
                                <td class="subtitle-2-row" colspan="5">1 - EDUCA��O 12 - IMPOSTOS E TRANSFER�NCIAS DE IMPOSTOS </td>
                            <? } ?>
                        </tr>
                        <?php
                        /**
                         * @todo loop de cada subfuncao
                         *
                         */
                        $nValorTotalPago = 0;
                        $nValorTotalEmpenhadoENaoLiquidado = 0;
                        $nValorTotalLiquidadoAPagar = 0;
                        $nValorTotalGeral = 0;
                        foreach ($aSubFuncoes as $iSubFuncao) {
                            $oReceitaeDespesaEnsino->setAnousu($anousu);
                            $oReceitaeDespesaEnsino->setSubFuncao($iSubFuncao);
                            $oReceitaeDespesaEnsino->setFuncao($sFuncao);
                            $oReceitaeDespesaEnsino->setFontes($aFonte);
                            $oReceitaeDespesaEnsino->setInstits($instits);
                            $dadosLinha1 = $oReceitaeDespesaEnsino->getLinha1FuncaoeSubfuncao();
                            $sDescrSubfuncao                       = $dadosLinha1['0'];
                            $nValorPagoSubFuncao                   = $dadosLinha1['2'];
                            $nValorEmpenhadoENaoLiquidadoSubFuncao = $dadosLinha1['3'];
                            $nValorLiquidadoAPagarSubFuncao        = $dadosLinha1['4'];
                            $nValorTotalSubFuncao                  = $dadosLinha1['5'];
                            if (count($dadosLinha1['1']) > 0) {
                            ?>
                                <tr>
                                    <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;"><?php echo db_formatar($iSubFuncao, 'subfuncao')." ".$sDescrSubfuncao ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorPagoSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorEmpenhadoENaoLiquidadoSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorLiquidadoAPagarSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php echo db_formatar($nValorTotalSubFuncao, "f"); ?></td>
                                </tr>
                            <?php
                            /**
                             * @todo para cada subfuncao lista os programas
                             */

                            foreach ($dadosLinha1['1'] as $oDespesaPrograma) {
                                $oReceitaeDespesaEnsino->setDespesaPrograma($oDespesaPrograma);
                                $oReceitaeDespesaEnsino->setSubTotal($fSubTotal);
                                $oReceitaeDespesaEnsino->setValorTotalPago($nValorTotalPago);
                                $oReceitaeDespesaEnsino->setValorTotalEmpenhadoENaoLiquidado($nValorTotalEmpenhadoENaoLiquidado);
                                $oReceitaeDespesaEnsino->setValorTotalLiquidadoAPagar($nValorTotalLiquidadoAPagar);
                                $oReceitaeDespesaEnsino->setValorTotalGeral($nValorTotalGeral);
                                $dadoslinha2                  = $oReceitaeDespesaEnsino->getLinha2FuncaoeSubfuncao();
                                $oPrograma                    = $dadoslinha2['0'];
                                $fSubTotal                   += $dadoslinha2['1'];
                                $nValorPago                   = $dadoslinha2['2'];
                                $nValorEmpenhadoENaoLiquidado = $dadoslinha2['3'];
                                $nValorLiquidadoAPagar        = $dadoslinha2['4'];
                                $nValorTotal                  = $dadoslinha2['5'];
                                $nValorTotalPago             += $dadoslinha2['6'];
                                $nValorTotalEmpenhadoENaoLiquidado += $dadoslinha2['7'];
                                $nValorTotalLiquidadoAPagar  += $dadoslinha2['8'];
                                $nValorTotalGeral            += $dadoslinha2['9'];
                               ?>
                                <tr>
                                    <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;"><?php echo db_formatar($oPrograma->getCodigoPrograma(), "programa")." ".$oPrograma->getDescricao(); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorPago, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorEmpenhadoENaoLiquidado, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorLiquidadoAPagar, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php echo db_formatar($nValorTotal, "f"); ?></td>
                                </tr>
                            <?php }
                            ?>
                            <tr>
                                <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;">&nbsp;</td>
                                <td class="text-row"></td>
                                <td class="text-row"></td>
                                <td class="text-row"></td>
                                <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"></td>
                            </tr>
                            <?php
                            }
                        }
                        ?>
                         <tr>
                              <?if(db_getsession("DB_anousu") > 2022) {?>
                                <td class="subtitle-2-row" colspan="5">2 - EDUCA��O 12 - AUX�LIO FINANCEIRO - OUTORGA CR�DITO TRIBUT�RIO ICMS - ART. 5�, INCISO V, EC N� 123/2022  </td>
                            <?} else {?>
                                <td class="subtitle-2-row" colspan="5">2 - EDUCA��O 12 - AUX�LIO FINANCEIRO - OUTORGA CR�DITO TRIBUT�RIO ICMS - ART. 5�, INCISO V, EC N� 123/2022  </td>
                            <? } ?>
                        </tr>
                        <?php
                        /**
                         * @todo loop de cada subfuncao
                         *
                         */
                        $aFonte2      = array("'136','17180000'");
                        $aSubFuncoes2 = array(122,272,271,361,365,366,367,843);
                        $sFuncao2     = "12";
                        foreach ($aSubFuncoes2 as $iSubFuncao) {
                            $oReceitaeDespesaEnsino->setAnousu($anousu);
                            $oReceitaeDespesaEnsino->setSubFuncao($iSubFuncao);
                            $oReceitaeDespesaEnsino->setFuncao($sFuncao2);
                            $oReceitaeDespesaEnsino->setFontes($aFonte2);
                            $oReceitaeDespesaEnsino->setInstits($instits);
                            $dadosLinha1 = $oReceitaeDespesaEnsino->getLinha1FuncaoeSubfuncao();
                            $nValorPagoSubFuncao                   = $dadosLinha1['2'];
                            $nValorEmpenhadoENaoLiquidadoSubFuncao = $dadosLinha1['3'];
                            $nValorLiquidadoAPagarSubFuncao        = $dadosLinha1['4'];
                            $nValorTotalSubFuncao                  = $dadosLinha1['5'];
                            if (count($dadosLinha1['1']) > 0) {
                            ?>
                                <tr>
                                    <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;"><?php echo db_formatar($iSubFuncao, 'subfuncao')." ".$sDescrSubfuncao ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorPagoSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorEmpenhadoENaoLiquidadoSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorLiquidadoAPagarSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php echo db_formatar($nValorTotalSubFuncao, "f"); ?></td>
                                </tr>
                            <?php
                            /**
                             * @todo para cada subfuncao lista os programas
                             */
                            foreach ($dadosLinha1['1'] as $oDespesaPrograma) {
                                $oReceitaeDespesaEnsino->setDespesaPrograma($oDespesaPrograma);
                                $oReceitaeDespesaEnsino->setSubTotal($fSubTotal);
                                $oReceitaeDespesaEnsino->setValorTotalPago($nValorTotalPago);
                                $oReceitaeDespesaEnsino->setValorTotalEmpenhadoENaoLiquidado($nValorTotalEmpenhadoENaoLiquidado);
                                $oReceitaeDespesaEnsino->setValorTotalLiquidadoAPagar($nValorTotalLiquidadoAPagar);
                                $oReceitaeDespesaEnsino->setValorTotalGeral($nValorTotalGeral);
                                $dadoslinha2                  = $oReceitaeDespesaEnsino->getLinha2FuncaoeSubfuncao();
                                $oPrograma                    = $dadoslinha2['0'];
                                $fSubTotal                   += $dadoslinha2['1'];
                                $nValorPago                   = $dadoslinha2['2'];
                                $nValorEmpenhadoENaoLiquidado = $dadoslinha2['3'];
                                $nValorLiquidadoAPagar        = $dadoslinha2['4'];
                                $nValorTotal                  = $dadoslinha2['5'];
                                $nValorTotalPago             += $dadoslinha2['6'];
                                $nValorTotalEmpenhadoENaoLiquidado += $dadoslinha2['7'];
                                $nValorTotalLiquidadoAPagar  += $dadoslinha2['8'];
                                $nValorTotalGeral            += $dadoslinha2['9'];
                                ?>
                                <tr>
                                    <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;"><?php echo db_formatar($dadoslinha2['0']->getCodigoPrograma(), "programa")." ".$dadoslinha2['0']->getDescricao(); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($dadoslinha2['2'], "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($dadoslinha2['3'], "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($dadoslinha2['4'], "f"); ?></td>
                                    <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php echo db_formatar($dadoslinha2['5'], "f"); ?></td>
                                </tr>
                            <?php }
                            ?>
                            <tr>
                                <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;">&nbsp;</td>
                                <td class="text-row"></td>
                                <td class="text-row"></td>
                                <td class="text-row"></td>
                                <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"></td>
                            </tr>
                            <?php
                            }
                        }
                        ?>
                        <tr>
                             <? if(db_getsession("DB_anousu") > 2022) { ?>
                                <td class="subtitle-2-row" colspan="5">3 - EDUCA��O 12 - FUNDEB </td>

                            <?} else { ?>
                                <td class="subtitle-2-row" colspan="5">3 - EDUCA��O 12 - FUNDEB </td>
                            <? } ?>

                        </tr>
                        <?php
                        /**
                         * @todo loop de cada subfuncao
                         *
                         */
                        foreach ($aSubFuncoes as $iSubFuncao) {
                            $oReceitaeDespesaEnsino->setSubFuncao($iSubFuncao);
                            $oReceitaeDespesaEnsino->setFuncao($sFuncao);
                            $oReceitaeDespesaEnsino->setFonteFundeb($aFonteFundeb);
                            $oReceitaeDespesaEnsino->setInstits($instits);
                            $dadoslinha3 =  $oReceitaeDespesaEnsino->getLinha1FuncaoFundeb();
                            $aDespesasProgramas = $dadoslinha3['1'];
                            if (count($aDespesasProgramas) > 0) {
                                $nValorPagoSubFuncao                    = $dadoslinha3['2'];
                                $nValorEmpenhadoENaoLiquidadoSubFuncao  = $dadoslinha3['3'];
                                $nValorLiquidadoAPagarSubFuncao         = $dadoslinha3['4'];
                                $nValorTotalSubFuncao                   = $dadoslinha3['5'];
                            ?>
                                <tr>
                                    <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;"><?php echo db_formatar($iSubFuncao, 'subfuncao')." ".$dadoslinha3['0'] ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorPagoSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorEmpenhadoENaoLiquidadoSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorLiquidadoAPagarSubFuncao, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php echo db_formatar($nValorTotalSubFuncao, "f"); ?></td>
                                </tr>
                            <?php
                            /**
                             * @todo para cada subfuncao lista os programas
                             */
                            foreach ($aDespesasProgramas as $oDespesaPrograma) {
                                $oReceitaeDespesaEnsino->setDespesaPrograma($oDespesaPrograma);
                                $oReceitaeDespesaEnsino->setValorTotalPago($nValorTotalPago);
                                $oReceitaeDespesaEnsino->setValorTotalEmpenhadoENaoLiquidado($nValorTotalEmpenhadoENaoLiquidado);
                                $oReceitaeDespesaEnsino->setValorTotalLiquidadoAPagar($nValorTotalLiquidadoAPagar);
                                $oReceitaeDespesaEnsino->setValorTotalGeral($nValorTotalGeral);
                                $dadoslinha3 =  $oReceitaeDespesaEnsino->getLinha2FuncaoFundeb();

                                $oPrograma                    = $dadoslinha3['0'];
                                $nValorPago                   = $dadoslinha3['1'];
                                $nValorEmpenhadoENaoLiquidado = $dadoslinha3['2'];
                                $nValorLiquidadoAPagar        = $dadoslinha3['3'];
                                $nValorTotal                  = $dadoslinha3['4'];
                                $nValorTotalPago             += $dadoslinha3['5'];
                                $nValorTotalEmpenhadoENaoLiquidado += $dadoslinha3['6'];
                                $nValorTotalLiquidadoAPagar  += $dadoslinha3['7'];
                                $nValorTotalGeral            += $dadoslinha3['8'];
                                ?>
                                <tr>
                                    <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;"><?php echo db_formatar($oPrograma->getCodigoPrograma(), "programa")." ".$oPrograma->getDescricao(); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorPago, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorEmpenhadoENaoLiquidado, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; "><?php echo db_formatar($nValorLiquidadoAPagar, "f"); ?></td>
                                    <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php echo db_formatar($nValorTotal, "f"); ?></td>
                                </tr>
                            <?php }
                            ?>
                                <tr>
                                    <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; width: 300px;">&nbsp;</td>
                                    <td class="text-row"></td>
                                    <td class="text-row"></td>
                                    <td class="text-row"></td>
                                    <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"></td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                        <tr>
                            <td class="subtitle-row" style="width: 300px;">4 - TOTAL DESPESAS (1 + 2 + 3)</td>
                            <td class="subtitle-row" style="text-align: right;"><?php echo db_formatar($nValorTotalPago, "f"); ?></td>
                            <td class="subtitle-row" style="text-align: right;"><?php echo db_formatar($nValorTotalEmpenhadoENaoLiquidado, "f"); ?></td>
                            <td class="subtitle-row" style="text-align: right;"><?php echo db_formatar($nValorTotalLiquidadoAPagar, "f"); ?></td>
                            <td class="subtitle-row" style="width: 100px; text-align: right;"><?php echo db_formatar($nValorTotalGeral, "f"); ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="body-relatorio" style="padding-top: 10px;">
            <table class="waffle" width="600px" cellspacing="0" cellpadding="0" style="border: 1px #000; margin-top: 50px;" autosize="1">
                    <tbody>
                        <tr>
                            <td class="title-row" >II - TOTAL DA APLICA��O NO ENSINO</td>
                        </tr>
                        <tr>
                            <td class="subtitle-row" style="width: 300px; text-align: center;">DESCRI��O</td>
                            <td class="subtitle-row" style="width: 100px; text-align: center;">VALOR</td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000;">5 - VALOR PAGO </td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    $nTotalAplicadoEntrada = 0;
                                    $nTotalAplicadoEntrada = $nValorTotalPago;
                                    echo db_formatar($nValorTotalPago, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000;">6 - RESULTADO L�QUIDO DAS TRANSFER�NCIAS DO FUNDEB</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    $nTotalAplicadoSaida = 0;
                                    $nTotalAplicadoSaida = $nResulatadoLiquidoTransfFundeb;
                                    if($nResulatadoLiquidoTransfFundeb >0){
                                        echo db_formatar(abs($nResulatadoLiquidoTransfFundeb), "f");
                                    }else{
                                        echo "(".db_formatar(abs($nResulatadoLiquidoTransfFundeb), "f")." )";
                                    }

                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000;">7 - DESPESAS CUSTEADAS COM SUPER�VIT DO FUNDEB AT� O PRIMEIRO QUADRIMESTRE - IMPOSTOS E TRANSFER�NCIAS DE IMPOSTOS</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                    $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                    $oReceitaeDespesaEnsino->setInstits($instits);
                                    $nValorCusteadoSuperavit = $oReceitaeDespesaEnsino->getLinha7DespesasCusteadaSuperavitDoFundeb();
                                    $nTotalAplicadoEntrada = $nTotalAplicadoEntrada + $nValorCusteadoSuperavit;
                                    echo db_formatar($nValorCusteadoSuperavit, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000;">8 - RESTOS A PAGAR INSCRITOS NO EXERC�CIO</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                            <?php
                                $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                $oReceitaeDespesaEnsino->setInstits($instits);
                                $dadosLinha8 = $oReceitaeDespesaEnsino->getLinha8RestosaPagarInscritosFonte101();
                                $nTotalAplicadoEntrada = $nTotalAplicadoEntrada + $dadosLinha8;
                                echo db_formatar($dadosLinha8, "f");
                        ?>
                        </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">8.1 - RECURSOS DE IMPOSTOS</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                            <?php
                            $nLiqAPagar101 = 0;
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            if($dtfim == $dtfimExercicio){
                                $oReceitaeDespesaEnsino->setFontes(array("'101','15000001','201','25000001'"));
                                $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                $oReceitaeDespesaEnsino->setInstits($instits);
                                $oReceitaeDespesaEnsino->setTipo('ambos');
                                $nLiqAPagar101 = $oReceitaeDespesaEnsino->getEmpenhosApagar();
                            }
                            echo db_formatar($nLiqAPagar101, "f");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;"> 8.2 - RECUSOS DO AUX�LIO FINANCEIRO - OUTORGA CR�DITO TRIBUT�RIO ICMS - ART. 5�, INCISO V, EC N� 123/2022 </td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                            <?php
                            $nLiqAPagar136 = 0;
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            $aSubFuncoes2 = array(122,272,271,361,365,366,367,843);
                            $sFuncao2     = "12";
                            if($dtfim == $dtfimExercicio){
                                $oReceitaeDespesaEnsino->setFontes(array("'136','17180000'"));
                                $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                $oReceitaeDespesaEnsino->setInstits($instits);
                                $oReceitaeDespesaEnsino->setTipo('ambos');
                                $oReceitaeDespesaEnsino->setFuncao($sFuncao2);
                                $oReceitaeDespesaEnsino->setSubFuncoes($aSubFuncoes2);
                                $nLiqAPagar136 = $oReceitaeDespesaEnsino->getEmpenhosApagarNovo();
                            }
                            echo db_formatar($nLiqAPagar136, "f");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">8.3 - RECURSOS DO FUNDEB</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                            <?php
                            $nLiqAPagar118_119 = 0;
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            if($dtfim == $dtfimExercicio){
                                $oReceitaeDespesaEnsino->setFontes(array("'118','119','1118','1119','15400007','15400000'"));
                                $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                $oReceitaeDespesaEnsino->setInstits($instits);
                                $oReceitaeDespesaEnsino->setTipo('ambos');
                                $nLiqAPagar118_119 = $oReceitaeDespesaEnsino->getEmpenhosApagar();
                            }
                            echo db_formatar($nLiqAPagar118_119, "f");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000;">9 - RESTOS A PAGAR INSCRITOS NO EXERC�CIO SEM DISPONIBILIDADE FINANCEIRA</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                            <?php
                                $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                $oReceitaeDespesaEnsino->setInstits($instits);
                                $dadosLinha9 = $oReceitaeDespesaEnsino->getLinha9RestosaPagarInscritoSemDis();
                                $nRPIncritosSemDesponibilidade101     = $dadosLinha9['0'];
                                $nRPIncritosSemDesponibilidade136     = $dadosLinha9['1'];
                                $nRPIncritosSemDesponibilidade118_119 = $dadosLinha9['2'];
                                $nTotalAplicadoSaida = $nTotalAplicadoSaida + $nRPIncritosSemDesponibilidade101 + $nRPIncritosSemDesponibilidade136 + $nRPIncritosSemDesponibilidade118_119;
                                echo db_formatar($nRPIncritosSemDesponibilidade101 + $nRPIncritosSemDesponibilidade136 + $nRPIncritosSemDesponibilidade118_119, "f");
                        ?>
                        </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">9.1 - RECURSOS DE IMPOSTOS (8.1 - 17.1)</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                            <?php
                            $nRPIncritosSemDesponibilidade101 = 0;
                            $oReceitaeDespesaEnsino->setFontes(array("'101','15000001','201','25000001'"));
                            $oReceitaeDespesaEnsino->setDataInicial($dtini);
                            $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                            $oReceitaeDespesaEnsino->setInstits($instits);
                            $oReceitaeDespesaEnsino->setTipo('lqd');
                            $nLiqAPagar101 = $oReceitaeDespesaEnsino->getEmpenhosApagar();
                            $oReceitaeDespesaEnsino->setTipo('');
                            $nNaoLiqAPagar101 = $oReceitaeDespesaEnsino->getEmpenhosApagar();
                            $aTotalPago101 = $nLiqAPagar101 + $nNaoLiqAPagar101;

                        if($dtfim == $dtfimExercicio){
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            $nRPSemDesponibilidade101 = $dadosLinha9['3'];

                            if($nRPSemDesponibilidade101 <= 0){
                                $nRPIncritosSemDesponibilidade101 = $aTotalPago101;
                            }
                            if($nRPSemDesponibilidade101 > 0){
                                $nRPIncritosSemDesponibilidade101 = $nRPSemDesponibilidade101 - $aTotalPago101;
                                if($nRPIncritosSemDesponibilidade101 >=0){
                                    $nRPIncritosSemDesponibilidade101 = 0;
                                }
                                $nRPIncritosSemDesponibilidade101 = abs($nRPIncritosSemDesponibilidade101);
                            }
                         }
                        ?>
                             <?php echo db_formatar($nRPIncritosSemDesponibilidade101,"f"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;"> 9.2 - RECUSOS DO AUX�LIO FINANCEIRO - OUTORGA CR�DITO TRIBUT�RIO ICMS - ART. 5�, INCISO V, EC N� 123/2022 (8.2 - 17.2)</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                            <?php
                            $nRPIncritosSemDesponibilidade136 = 0;
                            $oReceitaeDespesaEnsino->setFontes(array("'136','17180000'"));
                            $oReceitaeDespesaEnsino->setDataInicial($dtini);
                            $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                            $oReceitaeDespesaEnsino->setInstits($instits);
                            $oReceitaeDespesaEnsino->setTipo('lqd');
                            $nLiqAPagar136 = $oReceitaeDespesaEnsino->getEmpenhosApagar();
                            $oReceitaeDespesaEnsino->setTipo('');
                            $nNaoLiqAPagar136 = $oReceitaeDespesaEnsino->getEmpenhosApagar(array("'136','17180000'"), $dtini, $dtfim, $instits, '');
                            $aTotalPago136 = $nLiqAPagar136 + $nNaoLiqAPagar136;


                        if($dtfim == $dtfimExercicio){
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            $nRPSemDesponibilidade136 = $dadosLinha9['4'];
                            if($nRPSemDesponibilidade136 <= 0){
                                $nRPIncritosSemDesponibilidade136 = $aTotalPago136;
                            }
                            if($nRPSemDesponibilidade136 > 0){
                                $nRPIncritosSemDesponibilidade136 = $nRPSemDesponibilidade136 - $aTotalPago136;
                                if($nRPIncritosSemDesponibilidade136 >=0){
                                    $nRPIncritosSemDesponibilidade136 = 0;
                                }
                                $nRPIncritosSemDesponibilidade136 = abs($nRPIncritosSemDesponibilidade136);
                            }
                         }
                            ?>
                             <?php echo db_formatar($nRPIncritosSemDesponibilidade136,"f"); ?>
                        </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">9.3 - RECURSOS DO FUNDEB (8.2 - 17.2)</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                            <?php
                            $nRPIncritosSemDesponibilidade118_119 = 0;
                            $oReceitaeDespesaEnsino->setFontes(array("'118','119','1118','1119','15400007','15400000'"));
                            $oReceitaeDespesaEnsino->setDataInicial($dtini);
                            $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                            $oReceitaeDespesaEnsino->setInstits($instits);
                            $oReceitaeDespesaEnsino->setTipo('lqd');
                            $nLiqAPagar118_119 = $oReceitaeDespesaEnsino->getEmpenhosApagar();
                            $oReceitaeDespesaEnsino->setTipo('');
                            $nNaoLiqAPagar118_119 = $oReceitaeDespesaEnsino->getEmpenhosApagar();
                            $aTotalPago118_119= $nLiqAPagar118_119 + $nNaoLiqAPagar118_119;


                        if($dtfim == $dtfimExercicio){
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            $nRPSemDesponibilidade118_119 =  $dadosLinha9['5'];

                            if($nRPSemDesponibilidade118_119 <= 0){
                                $nRPIncritosSemDesponibilidade118_119 = $aTotalPago118_119;
                            }
                            if($nRPSemDesponibilidade118_119 > 0){
                                $nRPIncritosSemDesponibilidade118_119 = $nRPSemDesponibilidade118_119 - $aTotalPago118_119;
                                if($nRPIncritosSemDesponibilidade118_119 >=0){
                                    $nRPIncritosSemDesponibilidade118_119 = 0;
                                }
                                $nRPIncritosSemDesponibilidade118_119 = abs($nRPIncritosSemDesponibilidade118_119);
                            }

                         }
                            ?>
                             <?php echo db_formatar($nRPIncritosSemDesponibilidade118_119,"f"); ?>
                        </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000;">10 - RESTOS A PAGAR DE EXERC�CIOS ANTERIORES SEM DISPONIBILIDADE FINANCEIRA PAGOS NO EXERC�CIO ATUAL (CONSULTA 932.736)</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                    $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                    $oReceitaeDespesaEnsino->setInstits($instits);
                                    $nValorRecursoTotal = $oReceitaeDespesaEnsino->getLinha10RestoaPagarSemDis();
                                    $nTotalAplicadoEntrada = $nTotalAplicadoEntrada + $nValorRecursoTotal;
                                    echo db_formatar($nValorRecursoTotal, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">10.1 - RECURSOS DE IMPOSTOS</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    $oReceitaeDespesaEnsino->setFontes(array("'101','1101','15000001'", "'201','25000001'"));
                                    $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                    $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                    $oReceitaeDespesaEnsino->setInstits($instits);
                                    $nValorRecursoImposto = $oReceitaeDespesaEnsino->getRestosSemDisponilibidadeFundeb();
                                    echo db_formatar($nValorRecursoImposto, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">10.2 - RECUSOS DO AUX�LIO FINANCEIRO - OUTORGA CR�DITO TRIBUT�RIO ICMS - ART. 5�, INCISO V, EC N� 123/2022</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    $oReceitaeDespesaEnsino->setFontes(array("'136','17180000'"));
                                    $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                    $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                    $oReceitaeDespesaEnsino->setInstits($instits);
                                    $nValorRecursoImposto = $oReceitaeDespesaEnsino->getRestosSemDisponilibidadeFundeb(array("'136','17180000'"), $dtini, $dtfim, $instits);
                                    echo db_formatar($nValorRecursoFundeb, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">10.3 - RECURSOS DO FUNDEB</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    $oReceitaeDespesaEnsino->setFontes(array("'118','119','15400007','15400000','1118','1119'", "'218','219','25400007','25400000'"));
                                    $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                    $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                    $oReceitaeDespesaEnsino->setInstits($instits);
                                    $nValorRecursoImposto = $oReceitaeDespesaEnsino->getRestosSemDisponilibidadeFundeb();
                                    echo db_formatar($nValorRecursoFundeb, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000;">11 - CANCELAMENTO, NO EXERC�CIO, DE RESTOS A PAGAR INSCRITOS COM DISPONIBILIDADE FINANCEIRA</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    $oReceitaeDespesaEnsino->setDataInicial($dtini);
                                    $oReceitaeDespesaEnsino->setDataFinal($dtfim);
                                    $oReceitaeDespesaEnsino->setInstits($instits);
                                    $dadosLinha11 = $oReceitaeDespesaEnsino->getLinha11CancelamentodeRestoaPagar();
                                    $nValorRecursoTotal101 = $dadosLinha11['0'];
                                    $nValorRecursoTotal136 = $dadosLinha11['1'];
                                    $nValorRecursoTotal118 = $dadosLinha11['2'];
                                    $nTotalAplicadoSaida = $nTotalAplicadoSaida + $nValorRecursoTotal101 + $nValorRecursoTotal136 + $nValorRecursoTotal118;
                                    echo db_formatar($nValorRecursoTotal101 + $nValorRecursoTotal136 + $nValorRecursoTotal118, "f");

                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">11.1 - RECURSOS DE IMPOSTOS</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    echo db_formatar($nValorRecursoTotal101, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">11.2 - RECUSOS DO AUX�LIO FINANCEIRO - OUTORGA CR�DITO TRIBUT�RIO ICMS - ART. 5�, INCISO V, EC N� 123/2022 </td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    echo db_formatar($nValorRecursoTotal136, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: left; border-left: 1px SOLID #000000; padding-left: 20px;">11.3 - RECURSOS DO FUNDEB</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php
                                    echo db_formatar($nValorRecursoTotal118, "f");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="subtitle-row" style="width: 300px;">12 - TOTAL APLICADO ((5 + 7 + 8 + 10) - ( 6 + 9 + 11))</td>
                            <td class="subtitle-row" style="width: 100px; text-align: right;">
                                <?php
                                    echo db_formatar($nTotalAplicadoEntrada - $nTotalAplicadoSaida, "f");
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <table class="waffle" width="600px" cellspacing="0" cellpadding="0" style="border: 1px #000; margin-top: 50px;" autosize="1">
                    <tbody>
                        <tr>
                            <td class="title-row" colspan="5">IV - RESULTADO L�QUIDO DAS TRANSFER�NCIAS DO FUNDEB</td>
                        </tr>
                        <tr>
                            <td class="subtitle-row" style="width: 300px; text-align: center;">DESCRI��O</td>
                            <td class="subtitle-row" style="width: 100px; text-align: center;">VALOR</td>
                        </tr>
                        <tr>
                            <td class="subtitle-4-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px;">18 - RECEITAS RECEBIDAS DO FUNDEB NO EXERC�CIO</td>
                            <td class="subtitle-4-row" style="text-align: right; border-right: 1px SOLID #000000;">
                                <?php echo db_formatar(abs($nTotalReceitasRecebidasFundeb), "f");?>
                            </td>
                        </tr>
                        <tr>
                           <td class='text-row' style='text-align: lefth; border-left: 1px SOLID #000000; width: 300px; padding-left: 20px;'>18.1 - TRANSFER�NCIAS DE RECURSOS DO FUNDEB (NR 1.7.5.1.50.0.1)</td>
                           <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php echo db_formatar($nTransferenciaRecebidaFundeb, "f"); ?></td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px; padding-left: 20px;">18.2 - DEVOLU��O DE RECURSOS DO FUNDEB, RECEBIDOS EM ATRASOS, PARA AS CONTAS DE ORIGEM DOS RECURSOS (CONSULTA 1.047.710)</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php echo db_formatar($nDevolucaoRecursoFundeb, "f"); ?></td>
                        </tr>
                        <tr>
                            <td class="subtitle-4-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px;">19 - CONTRIBUI��O AO FUNDEB (LEI N� 14.113/2020) </td>
                            <td class="subtitle-4-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php
                                echo db_formatar(abs($nTotalContribuicaoFundeb), "f");
                            ?></td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px; padding-left: 20px;">19.1 - COTA-PARTE FPM</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php
                                $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '495171151%'");
                                $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;
                                $nContribuicaoFundeb += abs($nReceita);
                                echo db_formatar(abs($nReceita), "f"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px; padding-left: 20px;">19.2 - COTA-PARTE ICMS</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php
                                $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '495172150%'");
                                $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;
                                $nContribuicaoFundeb += abs($nReceita);
                                echo db_formatar(abs($nReceita), "f"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px; padding-left: 20px;">19.3 - COTA-PARTE IPI - EXPORTA��O</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php
                                $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '495172152%'");
                                $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;
                                $nContribuicaoFundeb += abs($nReceita);
                                echo db_formatar(abs($nReceita), "f"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px; padding-left: 20px;">19.4 - COTA-PARTE ITR</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php
                                $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '495171152%'");
                                $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;
                                $nContribuicaoFundeb += abs($nReceita);
                                echo db_formatar(abs($nReceita), "f"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px; padding-left: 20px;">19.5 - COTA-PARTE IPVA</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php
                                $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '495172151%'");
                                $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;
                                $nContribuicaoFundeb += abs($nReceita);
                                echo db_formatar(abs($nReceita), "f"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-row" style="text-align: lefth; border-left: 1px SOLID #000000; width: 300px; padding-left: 20px;">19.6 - AUX�LIO FINANCEIRO - OUTORGA CR�DITO TRIBUT�RIO ICMS - ART. 5�, INCISO V, EC N�123/2022 - PRINCIPAL</td>
                            <td class="text-row" style="text-align: right; border-right: 1px SOLID #000000;"><?php
                                $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49517196101%'");
                                $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;
                                $nContribuicaoFundeb += abs($nReceita);
                                echo db_formatar(abs($nReceita), "f"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="subtitle-row" style="width: 300px;">20 - TOTAL DO RESULTADO L�QUIDO DAS TRANSFER�NCIAS DO FUNDEB ( 18 - 19 )</td>
                            <td class="subtitle-row" style="width: 100px; text-align: right;">
                                <?php
                                    $resultadoLiquido =  $nTotalReceitasRecebidasFundeb - $nContribuicaoFundeb;
                                    if($resultadoLiquido > 0) {
                                        echo db_formatar(abs($resultadoLiquido), "f");
                                    }else{
                                        echo "(".db_formatar(abs($resultadoLiquido), "f")." )";
                                    }

                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>

<?php

$html = ob_get_contents();
ob_end_clean();
//echo $html;

$mPDF->WriteHTML(utf8_encode($html));
$mPDF->Output();
} catch (MpdfException $e) {
    db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}
/* ---- */


db_query("drop table if exists work_dotacao");
db_query("drop table if exists work_receita");

db_fim_transacao();
