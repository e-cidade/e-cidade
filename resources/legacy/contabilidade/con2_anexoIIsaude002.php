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
require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
include_once "libs/db_sessoes.php";
include_once "libs/db_usuariosonline.php";
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("libs/db_sql.php");
require_once("model/contabilidade/relatorios/ensino/RelatorioReceitaeDespesaEnsino.model.php");
db_postmemory($HTTP_POST_VARS);

$dtini = implode("-", array_reverse(explode("/", $DBtxt21)));
$dtfim = implode("-", array_reverse(explode("/", $DBtxt22)));

$instits = str_replace('-', ', ', $db_selinstit);
$aInstits = explode(",",$instits);
if(count($aInstits) > 1){
  $oInstit = new Instituicao();
  $oInstit = $oInstit->getDadosPrefeitura();
} else {
  foreach ($aInstits as $iInstit) {
    $oInstit = new Instituicao($iInstit);
  }
}
db_inicio_transacao();
$oReceitaeDespesaSaude = new RelatorioReceitaeDespesaEnsino();
$sWhereDespesa      = " o58_instit in({$instits})";
//Aqui passo o(s) exercicio(s) e a funcao faz o sql para cada exercicio
criaWorkDotacao($sWhereDespesa,array($anousu), $dtini, $dtfim);

/**
 * mPDF
 * @param string $mode              | padrão: BLANK
 * @param mixed $format             | padrão: A4
 * @param float $default_font_size  | padrão: 0
 * @param string $default_font      | padrão: ''
 * @param float $margin_left        | padrão: 15
 * @param float $margin_right       | padrão: 15
 * @param float $margin_top         | padrão: 16
 * @param float $margin_bottom      | padrão: 16
 * @param float $margin_header      | padrão: 9
 * @param float $margin_footer      | padrão: 9
 *
 * Nenhum dos parâmetros é obrigatório
 */

$mPDF = new \Mpdf\Mpdf([
    'mode' => '',
    'format' => 'A4',
    'orientation' => 'L',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 20,
    'margin_bottom' => 15,
    'margin_header' => 5,
    'margin_footer' => 11,
]);
date_default_timezone_set('America/Sao_Paulo');
$dataLocal = date('d/m/Y H:i:s', time());
$header = "
<header>
    <div style=\"font-family:Arial\">
        <div style=\"width:33%;float:left;padding:5px;font-size:10px;\">
            <b><i>{$oInstit->getDescricao()}</i></b><br/>
            <i>{$oInstit->getLogradouro()}, {$oInstit->getNumero()}</i><br/>
            <i>{$oInstit->getMunicipio()} - {$oInstit->getUf()}</i><br/>
            <i>{$oInstit->getTelefone()} - CNPJ: " . db_formatar($oInstit->getCNPJ(), "cnpj") . "</i><br/>
            <i>{$oInstit->getSite()}</i>
        </div>
        <div style=\"width:25%; float:right\" class=\"box\">
            <b>Despesa Saúde (ANEXO V)</b><br/>
            <b>INSTITUIÇÕES:</b> ";
            foreach ($aInstits as $iInstit) {
                $oInstituicao = new Instituicao($iInstit);
                $header .= "(" . trim($oInstituicao->getCodigo()) . ") " . $oInstituicao->getDescricao() . " ";
            }
            $header .= "<br/> <b>PERÍODO:</b> {$DBtxt21} A {$DBtxt22} <br/>
        </div>
    </div>
</header>";

$footer = "
<footer>
    <div style='border-top:1px solid #000;width:100%;font-family:sans-serif;font-size:10px;height:10px;'>
        <div style='text-align:left;font-style:italic;width:90%;float:left;'>
            Financeiro>Contabilidade>Relatórios de Acompanhamento>Despesa Saúde (ANEXO V)
            Emissor: " . db_getsession("DB_login") . " Exerc: " . db_getsession("DB_anousu") . " Data:" . $dataLocal . "
        <div style='text-align:right;float:right;width:10%;'>
            {PAGENO}
        </div>
    </div>
</footer>";


$mPDF->WriteHTML(file_get_contents('estilos/tab_relatorio.css'), 1);
$mPDF->setHTMLHeader(utf8_encode($header), 'O', true);
$mPDF->setHTMLFooter(utf8_encode($footer), 'O', true);

ob_start();

?>

  <html>
  <head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style type="text/css">
        .ritz .waffle a {
            color: inherit;
            font-family: 'Arial';
            font-size: 12px;
            width: 100%;
        }

        .title-relatorio {
            text-align: center;
        }

        .th-despesa {
            height: 20px;
            width: 80%;
            font-family: 'Arial';
            font-size: 12px;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .th-fuction_sub {
            height: 20px;
            background-color: #d8d8d8;
            width: 80%;
            border: 1px SOLID #000000;
            font-family: 'Arial';
            font-size: 12px;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .th-sub_menu {
            height: 20px;
            background-color: #d8d8d8;
            width: 80%;
            border: 1px SOLID #000000;
            font-family: 'Arial';
            font-size: 12px;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .th-valor {
            height: 20px;
            background-color: #d8d8d8;
            width: 20%;
            border-right: 1px SOLID #000000;
            border-top: 1px SOLID #000000;
            border-bottom: 1px SOLID #000000;
            font-family: 'Arial', Calibre;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .footer-row {
            height: 20px;
            background-color: #d8d8d8;
            width: 80%;
            border: 1px SOLID #000000;
            font-family: 'Arial';
            font-size: 12px;
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
            font-size: 12px;
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
            font-size: 12px;
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
            font-size: 12px;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .title-row-valor {
            height: 20px;
            background-color: #d8d8d8;
            width: 20%;
            border-right: 1px SOLID #000000;
            border-bottom: 1px SOLID #d8d8d8;
            font-family: 'Arial', Calibre;
            font-size: 12px;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .subtitle-row-valor {
            height: 20px;
            background-color: #d8d8d8;
            width: 20%;
            border-right: 1px SOLID #000000;
            border-bottom: 1px SOLID #d8d8d8;
            font-family: 'Arial', Calibre;
            font-size: 12px;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .text-row-valor {
            height: 20px;
            background-color: #ffffff;
            width: 20%;
            font-family: 'Arial', Calibre;
            border-right: 1px SOLID #000000;
            font-size: 12px;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .title-row {
            background-color: #d8d8d8;
            color: #000000;
            direction: ltr;
            border-left: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            border-bottom: 1px SOLID #d8d8d8;
            font-family: 'Arial', Calibre;
            font-size: 12px;
            padding: 2px 3px 2px 3px;
            text-align: left;
        }

        .ritz .subtitle-row {
            background-color: #d8d8d8;
            color: #000000;
            direction: ltr;
            border-left: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            border-bottom: 1px SOLID #d8d8d8;
            font-family: 'Arial', Calibre;
            font-size: 12px;
            padding: 2px 3px 2px 30px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .text-row {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            border-left: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            font-family: 'Arial', Calibre;
            font-size: 12px;
            padding: 2px 3px 2px 60px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .text-white-left-row {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            border-left: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            /* border-bottom: 1px SOLID #d8d8d8; */
            font-family: 'Arial', Calibre;
            font-size: 12px;
            padding: 2px 3px 2px 3px;
            text-align: left;
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
    <div class="ritz " >
        <div class="title-relatorio">
            <br/>
            <strong>ANEXO V</strong><br />
            <strong>DEMONSTRATIVO DOS GASTOS EM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE</strong><br />
            <strong>(Art. 198, §2º, III da CR/88, LC 141/2012 e IN 05/2012)</strong><br /><br />
        </div>
        <table class="waffle" width="600px" cellspacing="0" cellpadding="0" style="border: 1px #000" autosize="1">
            <thead>
                <tr>
                    <th id="0C0" style="width:100%"  class="column-headers-background">&nbsp;</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="th-despesa" colspan="8">I - DESPESAS</td>
                </tr>
                <tr>
                    <td class="th-fuction_sub" colspan="8">FUNÇÃO / SUBFUNÇÃO / PROGRAMA</td>
                    <td class="th-valor">VALOR PAGO</td>
                </tr>
                <?php
                $funcaoTotal = 0;
                $fTotalGeral = 0;
                $fProgTotal  = 0;
                $aSubTotal   = array();
                $aSubFuncoes = array(122,128,271,272,273,301,302,303,304,305,306,511,512,843,844);
                $sFuncao     = "'10','28'";
                $aFonte      = array("'15000002','25000002','1102','102','2102','202'");
                foreach ($aSubFuncoes as $iSubFuncao) {
                    $sDescrSubfunao = db_utils::fieldsMemory(db_query("select o53_descr from orcsubfuncao where o53_codtri = '{$iSubFuncao}'"), 0)->o53_descr;
                    if ($anousu > 2022 )
                        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codigo in (".implode(",",$aFonte).") and o58_instit in ($instits) group by 1,2");
                    else
                        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codtri in (".implode(",",$aFonte).") and o58_instit in ($instits) group by 1,2");
                    if (count($aDespesasProgramas) > 0) {
                      foreach ($aDespesasProgramas as $oDespesaPrograma) {
                        $funcaoTotal += $oDespesaPrograma->pago;
                        $fTotalGeral = $funcaoTotal;
                        if (!isset($aSubTotal[$iSubFuncao])) {
                          $aSubTotal[$iSubFuncao] = 0;
                      }
                      $aSubTotal[$iSubFuncao] += $oDespesaPrograma->pago;
                      }
                  }
                }
                ?>
                <tr style='height:20px;'>
                      <td class="th-sub_menu" colspan="8">1 - FUNÇÕES 10 E 28 - IMPOSTOS E TRANSFERÊNCIAS DE IMPOSTOS</td>
                      <td class="footer-total-row-valor">R$ <?=db_formatar($funcaoTotal,"f")?></td>
                </tr>
                <?php
                    foreach ($aSubFuncoes as $iSubFuncao) {
                      $sDescrSubfunao = db_utils::fieldsMemory(db_query("select o53_descr from orcsubfuncao where o53_codtri = '{$iSubFuncao}'"), 0)->o53_descr;
                      if ($anousu > 2022 )
                        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codigo in (".implode(",",$aFonte).") and o58_instit in ($instits) group by 1,2");
                      else
                        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codtri in (".implode(",",$aFonte).") and o58_instit in ($instits) group by 1,2");
                      if (count($aDespesasProgramas) > 0) {

                        ?>
                        <tr style='height:20px;'>
                          <td class="title-row" colspan="8"><?= db_formatar($iSubFuncao, 'subfuncao')." - ".$sDescrSubfunao ?></td>
                          <td class="title-row-valor">R$ <?= db_formatar($aSubTotal[$iSubFuncao], "f") ?></td>
                        </tr>
                        <?php

                        foreach ($aDespesasProgramas as $oDespesaPrograma) {
                          $oPrograma = new Programa($oDespesaPrograma->o58_programa, $oDespesaPrograma->o58_anousu);
                          ?>
                          <tr style='height:20px;'>
                            <td class="text-row" colspan="8"><?php echo db_formatar($oPrograma->getCodigoPrograma(), "programa")." - ".$oPrograma->getDescricao() ?></td>
                            <td class="text-row-valor"><?= db_formatar($oDespesaPrograma->pago, "f") ?></td>
                          </tr>
                          <?php
                          }
                       }
                    }
                ?>
                 <?php
                $funcaoTotal = 0;
                $fProgTotal  = 0;
                $aSubTotal   = array();
                $aSubFuncoes = array(122,128,271,272,273,301,302,303,304,305,306,511,512,843,844);
                $sFuncao     = "'10','28'";
                $aFonte      = array("'15020002','25020002'");
                foreach ($aSubFuncoes as $iSubFuncao) {
                    $sDescrSubfunao = db_utils::fieldsMemory(db_query("select o53_descr from orcsubfuncao where o53_codtri = '{$iSubFuncao}'"), 0)->o53_descr;
                    if ($anousu > 2022 )
                        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codigo in (".implode(",",$aFonte).") and o58_instit in ($instits) group by 1,2");
                    else
                        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codtri in (".implode(",",$aFonte).") and o58_instit in ($instits) group by 1,2");
                    if (count($aDespesasProgramas) > 0) {
                      foreach ($aDespesasProgramas as $oDespesaPrograma) {
                        $funcaoTotal += $oDespesaPrograma->pago;
                        $fTotalGeral = $funcaoTotal;
                        if (!isset($aSubTotal[$iSubFuncao])) {
                          $aSubTotal[$iSubFuncao] = 0;
                      }
                      $aSubTotal[$iSubFuncao] += $oDespesaPrograma->pago;
                      }
                  }
                }
                ?>
                <tr style='height:20px;'>
                  <td class="th-sub_menu" colspan="8">2 - FUNÇÕES 10 E 28 - COMPENSAÇÃO FINANCEIRA DAS PERDAS COM ARRECADAÇÃO DE ICMS- LC N° 194/2022 </td>
                  <td class="footer-total-row-valor">R$ <?=db_formatar($funcaoTotal,"f")?></td>
                </tr>
                <?php
                    foreach ($aSubFuncoes as $iSubFuncao) {
                      $sDescrSubfunao = db_utils::fieldsMemory(db_query("select o53_descr from orcsubfuncao where o53_codtri = '{$iSubFuncao}'"), 0)->o53_descr;
                      if ($anousu > 2022 )
                        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codigo in (".implode(",",$aFonte).") and o58_instit in ($instits) group by 1,2");
                      else
                        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codtri in (".implode(",",$aFonte).") and o58_instit in ($instits) group by 1,2");
                      if (count($aDespesasProgramas) > 0) {

                        ?>
                        <tr style='height:20px;'>
                          <td class="title-row" colspan="8"><?= db_formatar($iSubFuncao, 'subfuncao')." - ".$sDescrSubfunao ?></td>
                          <td class="title-row-valor">R$ <?= db_formatar($aSubTotal[$iSubFuncao], "f") ?></td>
                        </tr>
                        <?php

                        foreach ($aDespesasProgramas as $oDespesaPrograma) {
                          $oPrograma = new Programa($oDespesaPrograma->o58_programa, $oDespesaPrograma->o58_anousu);
                          ?>
                          <tr style='height:20px;'>
                            <td class="text-row" colspan="8"><?php echo db_formatar($oPrograma->getCodigoPrograma(), "programa")." - ".$oPrograma->getDescricao() ?></td>
                            <td class="text-row-valor"></td>
                          </tr>
                          <?php } ?>

                          <tr style='height:20px;'>
                            <td class="text-row" colspan="8"></td>
                            <td class="text-row-valor"></td>
                          </tr>
                          <?php
                       }
                    }
                ?>
      <tr style='height:20px;'>
        <td class="footer-total-row" colspan="8">3 - TOTAL DESPESAS (1 + 2 )</td>
        <td class="footer-total-row-valor">R$ <?=db_formatar($fTotalGeral,"f")?></td>
      </tr>
      </tbody>
      <tbody>
                <tr>
                    <td class="th-despesa" colspan="8">II - TOTAL DA APLICAÇÃO NA SAÚDE</td>
                </tr>
                <tr>
                    <td class="th-fuction_sub" colspan="8">DESCRIÇÃO</td>
                    <td class="th-valor">VALOR</td>
                </tr>
                <tr style='height:20px;'>
                      <td class="text-white-left-row" colspan="8">4- VALOR PAGO</td>
                      <td class="text-row-valor">R$ <?=db_formatar($fTotalGeral,"f")?></td>
                </tr>
                <?php
                $valorLinha5    = 0;
                $valorLinha5_1  = 0;
                $valorLinha5_2  = 0;
                $valorLinha6    = 0;
                $nLiqAPagar101  = 0;

                $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                $aFonte = array("'15000002','25000002','1102','102','2102','202'");
                if($dtfim == $dtfimExercicio){
                    $oReceitaeDespesaSaude->setFontes($aFonte);
                    $oReceitaeDespesaSaude->setDataInicial($dtini);
                    $oReceitaeDespesaSaude->setDataFinal($dtfim);
                    $oReceitaeDespesaSaude->setInstits($instits);
                    $oReceitaeDespesaSaude->setTipo('ambos');
                    $valorLinha5_1 = $oReceitaeDespesaSaude->getEmpenhosApagar();
                }

                $aFonte = array("'15020002','25020002'");
                if($dtfim == $dtfimExercicio){
                    $oReceitaeDespesaSaude->setFontes($aFonte);
                    $oReceitaeDespesaSaude->setDataInicial($dtini);
                    $oReceitaeDespesaSaude->setDataFinal($dtfim);
                    $oReceitaeDespesaSaude->setInstits($instits);
                    $oReceitaeDespesaSaude->setTipo('ambos');
                    $valorLinha5_2 = $oReceitaeDespesaSaude->getEmpenhosApagar();
                }
                $valorLinha5 = $valorLinha5_1 + $valorLinha5_2;
                $valorLinha6 = $valorLinha5 + $fTotalGeral;
                ?>
                <tr style='height:20px;'>
                      <td class="title-row" colspan="8">5 - RESTOS A PAGAR INSCRITOS NO EXERCÍCIO</td>
                      <td class="title-row-valor">R$ <?=db_formatar($valorLinha5,"f")?></td>
                </tr>
                <tr style='height:20px;'>
                      <td class="text-white-left-row" colspan="8">5.1 - IMPOSTO E TRANSFERÊNCIA DE IMPOSTOS</td>
                      <td class="text-row-valor">R$ <?=db_formatar($valorLinha5_1,"f")?></td>
                </tr>
                <tr style='height:20px;'>
                      <td class="text-white-left-row" colspan="8">5.2 - COMPENSAÇÃO FINANCEIRA DAS PERDAS COM ARRECADAÇÃO DE ICMS ? LC Nº 194/2022</td>
                      <td class="text-row-valor">R$ <?=db_formatar($valorLinha5_2,"f")?></td>
                </tr>
                <tr style='height:20px;'>
                      <td class="title-row" colspan="8">6 - SUBTOTAL (4 + 5)</td>
                      <td class="title-row-valor">R$ <?=db_formatar($valorLinha6,"f")?></td>
                </tr>
                <?php
                   $totalLinha7   = 0;
                   $totalLinha7_1 = 0;
                   $totalLinha7_2 = 0;
                   $totalLinha9   = 0;
                   $totalLinha9   = $valorLinha6;
                   $aFonte = array("'15000002','25000002','1102','102','2102','202'");
                   $oReceitaeDespesaSaude->setFontes($aFonte);
                   $oReceitaeDespesaSaude->setDataInicial($dtini);
                   $oReceitaeDespesaSaude->setDataFinal($dtfim);
                   $oReceitaeDespesaSaude->setInstits($instits);
                   $oReceitaeDespesaSaude->setTipo('lqd');
                   $nLiqAPagar101 = $oReceitaeDespesaSaude->getEmpenhosApagar();
                   $oReceitaeDespesaSaude->setTipo('');
                   $nNaoLiqAPagar101 = $oReceitaeDespesaSaude->getEmpenhosApagar();
                   $dadosLinha9      = $oReceitaeDespesaSaude->getLinha10RestosaPagarInscritoSemDis();
                   $aTotalPago101 = $nLiqAPagar101 + $nNaoLiqAPagar101;

                   $dtfimExercicio = db_getsession("DB_anousu")."-12-31";

                   if($dtfim == $dtfimExercicio){
                       $nRPSemDesponibilidade101 = $dadosLinha9['2'];

                       if($nRPSemDesponibilidade101 <= 0){
                           $totalLinha7_1 = $aTotalPago101;
                       }
                       if($nRPSemDesponibilidade101 > 0){
                           $totalLinha7_1 = $nRPSemDesponibilidade101 - $aTotalPago101;
                           if($totalLinha7_1 >=0){
                            $totalLinha7_1 = 0;
                           }
                           $totalLinha7_1 = abs($totalLinha7_1);
                       }
                   }

                   $totalLinha7_2 = 0;
                   $aFonte = array("'15020002','25020002'");
                   $oReceitaeDespesaSaude->setFontes($aFonte);
                   $oReceitaeDespesaSaude->setDataInicial($dtini);
                   $oReceitaeDespesaSaude->setDataFinal($dtfim);
                   $oReceitaeDespesaSaude->setInstits($instits);
                   $oReceitaeDespesaSaude->setTipo('lqd');
                   $nLiqAPagar101 = $oReceitaeDespesaSaude->getEmpenhosApagar();
                   $oReceitaeDespesaSaude->setTipo('');
                   $nNaoLiqAPagar101 = $oReceitaeDespesaSaude->getEmpenhosApagar();
                   $aTotalPago101 = $nLiqAPagar101 + $nNaoLiqAPagar101;

                   $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                   if($dtfim == $dtfimExercicio){
                       $nRPSemDesponibilidade101 = $dadosLinha9['3'];

                       if($nRPSemDesponibilidade101 <= 0){
                          $totalLinha7_2 = $aTotalPago101;
                       }
                       if($nRPSemDesponibilidade101 > 0){
                           $totalLinha7_2 = $nRPSemDesponibilidade101 - $aTotalPago101;
                           if($totalLinha7_2 >=0){
                            $totalLinha7_2 = 0;
                           }
                           $totalLinha7_2 = abs($totalLinha7_2);
                       }
                   }
                   $totalLinha7 = $totalLinha7_1 + $aTotalPago101;
                   $totalLinha9 -= $totalLinha7;
                ?>
                <tr style='height:20px;'>
                      <td class="title-row" colspan="8">7 - RESTOS A PAGAR INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA</td>
                      <td class="title-row-valor">R$ <?=db_formatar($totalLinha7,"f")?></td>
                </tr>
                <tr style='height:20px;'>
                      <td class="text-white-left-row" colspan="8">7.1 - IMPOSTO E TRANSFERÊNCIA DE IMPOSTOS</td>
                      <td class="text-row-valor">R$ <?=db_formatar($totalLinha7_1,"f")?></td>
                </tr>
                <tr style='height:20px;'>
                      <td class="text-white-left-row" colspan="8">7.2 - COMPENSAÇÃO FINANCEIRA DAS PERDAS COM ARRECADAÇÃO DE ICMS ? LC Nº 194/2022</td>
                      <td class="text-row-valor">R$ <?=db_formatar($totalLinha7_2,"f")?></td>
                </tr>
                <?php
                     $totalLinha8   = 0;
                     $totalLinha8_1 = 0;
                     $totalLinha8_2 = 0;
                     $sFonte = ("'102','202','1102', '2102','15000002','25000002'");
                     $where = " c61_instit in ({$instits}) and c61_codigo in ($sFonte) ";
                     $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"), $dtini, $dtfim, false, $where,'',0);
                     for ($x = 0; $x < pg_numrows($result); $x++) {
                         db_fieldsmemory($result, $x);
                         if(substr($estrutural,1,14) == '00000000000000'){
                             if($sinal_anterior == "C")
                                 $total_anterior -= $saldo_anterior;
                             else {
                                 $total_anterior += $saldo_anterior;
                             }
                         }
                     }
                     $nValorRecursoImposto = $total_anterior;
                     $sele_work = " e60_instit in ({$instits}) ";
                     $sele_work1       = " and e91_recurso in ($sFonte)";
                     $sql_where_externo = " where $sele_work ";
                     $sql_order = " order by e91_recurso, e91_numemp ";

                     $clempresto = new cl_empresto;
                     $sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), $sele_work, $dtini, $dtfim, $sele_work1, $sql_where_externo, $sql_order);
                     $res = $clempresto->sql_record($sqlempresto);
                     $rows = $clempresto->numrows;
                     $total_mov_liqui = 0;
                     $total_mov_pagmento = 0;
                     $total_mov_pagnproc = 0;
                     $total_anterior = 0;
                     for ($x = 0; $x < $rows; $x++) {
                         db_fieldsmemory($res, $x);

                         $total_mov_liqui += $e91_vlremp;
                         $total_mov_pagmento += $vlrpag;
                         $total_mov_pagnproc += $vlrpagnproc;
                     }
                     if ($clempresto->numrows == 0) {
                        $total_mov_pagmento = 0;
                        $total_mov_pagnproc = 0;
                        $nValorRecursoImposto = 0;
                     }
                     $totalLinha8_1 = $total_mov_pagmento + $total_mov_pagnproc - $nValorRecursoImposto;
                     $sFonte2 = ("'15020002','25020002'");
                     $where2  = " c61_instit in ({$instits}) and c61_codigo in ($sFonte2) ";
                     $result2 = db_planocontassaldo_matriz(db_getsession("DB_anousu"), $dtini, $dtfim, false, $where2,'',0);

                     for ($x = 0; $x < pg_numrows($result2); $x++) {
                         db_fieldsmemory($result2, $x);
                         if(substr($estrutural,1,14) == '00000000000000'){
                             if($sinal_anterior == "C")
                                 $total_anterior -= $saldo_anterior;
                             else {
                                 $total_anterior += $saldo_anterior;
                             }
                         }
                     }
                     $nValorRecursoImposto = $total_anterior;
                     $sele_work = " e60_instit in ({$instits}) ";
                     $sele_work1       = " and e91_recurso in ($sFonte2)";
                     $sql_where_externo = " where $sele_work ";
                     $sql_order = " order by e91_recurso, e91_numemp ";

                     $clempresto = new cl_empresto;
                     $sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), $sele_work, $dtini, $dtfim, $sele_work1, $sql_where_externo, $sql_order);
                     $res = $clempresto->sql_record($sqlempresto);
                     $rows = $clempresto->numrows;
                     $total_mov_liqui = 0;
                     $total_mov_pagmento = 0;
                     $total_mov_pagnproc = 0;
                     for ($x = 0; $x < $rows; $x++) {
                         db_fieldsmemory($res, $x);
                         $total_mov_liqui += $e91_vlremp;
                         $total_mov_pagmento += $vlrpag;
                         $total_mov_pagnproc += $vlrpagnproc;
                     }
                     if ($clempresto->numrows == 0) {
                        $total_mov_pagmento = 0;
                        $total_mov_pagnproc = 0;
                        $nValorRecursoImposto = 0;
                     }
                     $totalLinha8_2 = $total_mov_pagmento + $total_mov_pagnproc - $nValorRecursoImposto;
                     $totalLinha8 = $totalLinha8_1 + $totalLinha8_2;
                     $totalLinha9 += $totalLinha8;
                ?>
                <tr style='height:20px;'>
                      <td class="title-row" colspan="8">8 - RESTOS A PAGAR DE EXERCÍCIOS ANTERIORES SEM DISPONIBILIDADE DE CAIXA PAGOS NO EXERCÍCIO ATUAL (CONSULTA 932.736)</td>
                      <td class="title-row-valor">R$ <?=db_formatar($totalLinha8,"f")?></td>
                </tr>
                <tr style='height:20px;'>
                      <td class="text-white-left-row" colspan="8">8.1 - IMPOSTO E TRANSFERÊNCIA DE IMPOSTOS</td>
                      <td class="text-row-valor">R$ <?=db_formatar($totalLinha8_1,"f")?></td>
                </tr>
                <tr style='height:20px;'>
                      <td class="text-white-left-row" colspan="8">8.2 - COMPENSAÇÃO FINANCEIRA DAS PERDAS COM ARRECADAÇÃO DE ICMS ? LC Nº 194/2022</td>
                      <td class="text-row-valor">R$ <?=db_formatar($totalLinha8_2,"f")?></td>
                </tr>
                <tr style='height:20px;'>
                      <td class="footer-total-row" colspan="8">9 - TOTAL APLICADO (6 - 7 + 8)</td>
                      <td class="footer-total-row-valor">R$ <?=db_formatar($totalLinha9,"f")?></td>
                </tr>
      </tbody>
    </table>
  </div>


  </body>
  </html>

<?php

$html = ob_get_contents();
ob_end_clean();
db_query("drop table if exists work_dotacao");
db_fim_transacao();
$mPDF->WriteHTML(utf8_encode($html));
$mPDF->Output();



?>
