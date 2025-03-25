<?php

include("libs/db_utils.php");
include("std/DBDate.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("fpdf151/pdf.php");
include("libs/db_liborcamento.php");
include("classes/db_contacorrentedetalhe_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $aFiltros);

$sSqlEmpenhos   = "SELECT rh72_coddot, sum(rh73_valor) AS valor_empenhado FROM ( SELECT rh72_sequencial,                                 ";
$sSqlEmpenhos  .= "                        rh72_coddot,                                                                                  ";
$sSqlEmpenhos  .= "                        rh72_codele,                                                                                  ";
$sSqlEmpenhos  .= "                        o40_descr,                                                                                    ";
$sSqlEmpenhos  .= "                        o41_descr,                                                                                    ";
$sSqlEmpenhos  .= "                        rh72_unidade,                                                                                 ";
$sSqlEmpenhos  .= "                        rh72_orgao,                                                                                   ";
$sSqlEmpenhos  .= "                        rh72_projativ,                                                                                ";
$sSqlEmpenhos  .= "                        rh72_programa,                                                                                ";
$sSqlEmpenhos  .= "                        rh72_funcao,                                                                                  ";
$sSqlEmpenhos  .= "                        rh72_subfuncao,                                                                               ";
$sSqlEmpenhos  .= "                        rh72_anousu,                                                                                  ";
$sSqlEmpenhos  .= "                        rh72_mesusu,                                                                                  ";
$sSqlEmpenhos  .= "                        rh72_recurso,                                                                                 ";
$sSqlEmpenhos  .= "                        rh72_siglaarq,                                                                                ";
$sSqlEmpenhos  .= "                        rh72_concarpeculiar,                                                                          ";
$sSqlEmpenhos  .= "                        rh72_tabprev,                                                                                 ";
$sSqlEmpenhos  .= "                        o56_elemento,                                                                                 ";
$sSqlEmpenhos  .= "                        o56_descr,                                                                                    ";
$sSqlEmpenhos  .= "                        o120_orcreserva,                                                                              ";
$sSqlEmpenhos  .= "                        round(sum(CASE WHEN rh73_pd = 2 THEN rh73_valor *-1 ELSE rh73_valor end), 2) AS rh73_valor    ";
$sSqlEmpenhos  .= "                   FROM rhempenhofolha 							     									             ";
$sSqlEmpenhos  .= "                        INNER JOIN rhempenhofolharhemprubrica  ON rh81_rhempenhofolha = rh72_sequencial               ";
$sSqlEmpenhos  .= "                        INNER JOIN rhempenhofolharubrica       ON rh73_sequencial     = rh81_rhempenhofolharubrica    ";
$sSqlEmpenhos  .= "                        INNER JOIN orcelemento                 ON o56_codele          = rh72_codele                   ";
$sSqlEmpenhos  .= "                                                              AND o56_anousu          = rh72_anousu                   ";
$sSqlEmpenhos  .= "                        INNER JOIN orcorgao                    ON rh72_orgao          = o40_orgao                     ";
$sSqlEmpenhos  .= "                                                              AND rh72_anousu         = o40_anousu                    ";
$sSqlEmpenhos  .= "                        INNER JOIN orcunidade                  ON rh72_orgao          = o41_orgao                     ";
$sSqlEmpenhos  .= "                                                              AND rh72_unidade        = o41_unidade                   ";
$sSqlEmpenhos  .= "                                                              AND rh72_anousu         = o41_anousu                    ";
$sSqlEmpenhos  .= "                        INNER JOIN rhpessoalmov                ON rh73_seqpes         = rh02_seqpes                   ";
$sSqlEmpenhos  .= "                                                              AND rh73_instit         = rh02_instit                   ";
$sSqlEmpenhos  .= "                        LEFT JOIN rhempenhofolhaempenho        ON rh72_sequencial     = rh76_rhempenhofolha           ";
if ($iTipoEmpenho == 2)     {
    $sSqlEmpenhos  .= "                    AND rh76_lota = rh02_lota                                                                     ";
    $sSqlEmpenhos  .= "                    LEFT JOIN orcreservarhempenhofolha     ON rh72_sequencial     = o120_rhempenhofolha           ";
    $sSqlEmpenhos  .= "                    AND rh02_lota = o120_lota                                                                     ";
    $sSqlEmpenhos  .= "                    LEFT JOIN rhlotavinc ON                                                                       ";
    $sSqlEmpenhos  .= "                    	rh02_lota = rh25_codigo                                                                      ";
    $sSqlEmpenhos  .= "                    	AND rh02_anousu = rh25_anousu                                                                ";
    $sSqlEmpenhos  .= "                    	AND rh72_projativ = rh25_projativ                                                            ";
    $sSqlEmpenhos  .= "                    	AND rh72_recurso = rh25_recurso                                                              ";
    $sSqlEmpenhos  .= "                    	AND rh25_codlotavinc = (SELECT rh28_codlotavinc FROM rhlotavincele                           ";
    $sSqlEmpenhos  .= "                    WHERE rh25_codlotavinc = rh28_codlotavinc AND rh72_codele = rh28_codelenov)                   ";
} else {
    $sSqlEmpenhos  .= "                    LEFT JOIN orcreservarhempenhofolha     ON rh72_sequencial     = o120_rhempenhofolha           ";
}
    $sSqlEmpenhos  .= "                  WHERE rh76_rhempenhofolha is null			                                                     ";
    $sSqlEmpenhos  .= "                    AND rh72_tipoempenho = {$iTipo}                                                               ";
    $sSqlEmpenhos  .= "                    AND rh73_instit      = ".db_getsession("DB_instit"). "                                        ";
    $sSqlEmpenhos  .= "                    AND rh73_tiporubrica = 1																	     ";
    $sSqlEmpenhos  .= "                    AND rh72_anousu      = {$iAnoFolha}                                                           ";
    $sSqlEmpenhos  .= "                    AND rh72_mesusu      = {$iMesFolha}                                                           ";
if (isset($iSeqPes)) {
    $sSqlEmpenhos  .= "                    AND rh73_seqpes     = {$iSeqPes}                                                              ";
} else if ($iTipo == 1 && $sSigla != 'r20') {
    $sSqlEmpenhos  .= "                    AND rh72_seqcompl    = {$sSemestre}                                                           ";
}
if ( $iTipo == 2 && $sPrevidencia !== '' ) {
    $sSqlEmpenhos .= "                    AND rh72_tabprev in({$sPrevidencia})                                                           ";
}
    $sSqlEmpenhos  .= "                    AND rh72_siglaarq    = '{$sSigla}'                                                            ";
    $sSqlEmpenhos  .= "                  GROUP BY rh72_sequencial,                                                                       ";
    $sSqlEmpenhos  .= "                           rh72_coddot,                                                                           ";
if ($iTipoEmpenho == 2) {
    $sSqlEmpenhos  .= "                       rh02_lota,                                                                                 ";
    $sSqlEmpenhos  .= "                       rh25_codlotavinc,                                                                          ";
}
$sSqlEmpenhos  .= "                           rh72_codele,                                                                           ";
$sSqlEmpenhos  .= "                           o40_descr,                                                                             ";
$sSqlEmpenhos  .= "                           o41_descr,                                                                             ";
$sSqlEmpenhos  .= "                           rh72_unidade,                                                                          ";
$sSqlEmpenhos  .= "                           rh72_orgao,                                                                            ";
$sSqlEmpenhos  .= "                           rh72_projativ,                                                                         ";
$sSqlEmpenhos  .= "                           rh72_programa,                                                                         ";
$sSqlEmpenhos  .= "                           rh72_funcao,                                                                           ";
$sSqlEmpenhos  .= "                           rh72_subfuncao,                                                                        ";
$sSqlEmpenhos  .= "                           rh72_mesusu,                                                                           ";
$sSqlEmpenhos  .= "                           rh72_anousu,                                                                           ";
$sSqlEmpenhos  .= "                           rh72_recurso,                                                                          ";
$sSqlEmpenhos  .= "                           rh72_siglaarq,                                                                         ";
$sSqlEmpenhos  .= "                           rh72_concarpeculiar,                                                                   ";
$sSqlEmpenhos  .= "                           rh72_tabprev,                                                                          ";
$sSqlEmpenhos  .= "                           o56_elemento,                                                                          ";
$sSqlEmpenhos  .= "                           o56_descr,                                                                             ";
$sSqlEmpenhos  .= "                           o120_orcreserva                                                                        ";
$sSqlEmpenhos  .= "                ORDER BY rh72_recurso,rh72_orgao,rh72_unidade,rh72_projativ,rh72_coddot,rh72_codele ) AS x        ";
$sSqlEmpenhos  .= "        WHERE rh73_valor <> 0 GROUP BY rh72_coddot                                                                ";

$aValorEmpenhado = db_utils::getCollectionByRecord(db_query($sSqlEmpenhos));

$aDotacoes = [];

foreach ($aValorEmpenhado as $oEmpenhado) {
    $rsDotacaoSaldo = db_dotacaosaldo(8, 2, 2, true, "o58_instit=".db_getsession("DB_instit")." and o58_coddot = {$oEmpenhado->rh72_coddot}", db_getsession("DB_anousu"));
    $aDotacoes[$oEmpenhado->rh72_coddot] = db_utils::getCollectionByRecord($rsDotacaoSaldo);
    $aDotacoes[$oEmpenhado->rh72_coddot][0]->valorEmpenhado = $oEmpenhado->valor_empenhado;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "SALDO DAS DOTAÇÕES PARA";
$head3 = "EMPENHO DA FOLHA";
$head5 = "ANO: {$iAnoFolha}";
$head6 = "MES: {$iMesFolha}";
$alt = 4;
$iTotalDotacao = 0;
$iTotalEmpenhado = 0;
$iTotalSuplementar = 0;

$pdf->SetAutoPageBreak('on',0);
$pdf->line(2,148.5,208,148.5);
$pdf->setfillcolor(235);
$pdf->addpage();

$pdf->setfont("arial", "B", 9);
$pdf->cell(195,$alt,"Saldos das Dotações para Empenho da Folha","",0,"C",0);
$pdf->ln();
$pdf->setfont("arial", "b", 7);
$pdf->SetXY(40, 42);
$pdf->cell(19,$alt,"Dotação","LTB",0,"C",0);
$pdf->cell(25,$alt,"Fonte de Recursos","LTB",0,"C",0);
$pdf->cell(30,$alt,"Saldo da Dotação","LTB",0,"C",0);
$pdf->cell(30,$alt,"Valor Empenhado","LTB",0,"C",0);
$pdf->cell(30,$alt,"Valor a Suplementar","LTBR",0,"C",0);

foreach($aDotacoes as $oDotacao){
    $pdf->ln();
    $pdf->setX(40);
    $pdf->cell(19,$alt,$oDotacao[0]->o58_coddot,"LTB",0,"C",0);
    $pdf->cell(25,$alt,$oDotacao[0]->o58_codigo,"LTB",0,"C",0);
    $pdf->cell(30,$alt,db_formatar($oDotacao[0]->atual_menos_reservado, 'f'),"LTB",0,"R",0);
    $pdf->cell(30,$alt,db_formatar($oDotacao[0]->valorEmpenhado, 'f'),"LTB",0,"R",0);
    $iValorSuplementar = $oDotacao[0]->atual_menos_reservado - $oDotacao[0]->valorEmpenhado > 0 ? 0 : abs($oDotacao[0]->atual_menos_reservado - $oDotacao[0]->valorEmpenhado);
    $pdf->cell(30,$alt,db_formatar($iValorSuplementar, 'f'),"LTBR",0,"R",0);
    if ($pdf->gety() > ($pdf->h - 20)) {
        $pdf->addpage();
        $pdf->setXY(40, 40);
        $pdf->cell(19,$alt,"Dotação","LTB",0,"C",0);
        $pdf->cell(25,$alt,"Fonte de Recursos","LTB",0,"C",0);
        $pdf->cell(30,$alt,"Saldo da Dotação","LTB",0,"C",0);
        $pdf->cell(30,$alt,"Valor Empenhado","LTB",0,"C",0);
        $pdf->cell(30,$alt,"Valor a Suplementar","LTBR",0,"C",0);
    }
    $iTotalDotacao += $oDotacao[0]->atual_menos_reservado;
    $iTotalEmpenhado += $oDotacao[0]->valorEmpenhado;
    $iTotalSuplementar += $iValorSuplementar;
}
$pdf->ln();
$pdf->setX(40);
$pdf->cell(44,$alt,"Total","LTB",0,"C",0);
$pdf->cell(30,$alt,db_formatar($iTotalDotacao, 'f'),"LTB",0,"R",0);
$pdf->cell(30,$alt,db_formatar($iTotalEmpenhado, 'f'),"LTB",0,"R",0);
$pdf->cell(30,$alt,db_formatar($iTotalSuplementar, 'f'),"LTBR",0,"R",0);

$pdf->Output();

?>
