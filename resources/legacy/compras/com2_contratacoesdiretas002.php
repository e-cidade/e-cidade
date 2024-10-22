<?php
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libdocumento.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $aVariaveis);
db_postmemory($HTTP_SERVER_VARS);

$clcontratacoesdiretas = new cl_contratacoesdiretas();
$aReport = $clcontratacoesdiretas->getReportData($aVariaveis);

$head3 = "CONTRATAÇÕES DIRETAS";

$exercicio = $clcontratacoesdiretas->getExercise($aVariaveis);
$head5 = $exercicio !== null ? "DATA DE EXERCÍCIO: " . $exercicio : "";

$departamento = $clcontratacoesdiretas->getDepartment($aVariaveis);
$head6 = $departamento !== null ? "DEPARTAMENTO(S): " . $departamento : "";

$categoria = $clcontratacoesdiretas->getCategory($aVariaveis);
$head7 = $categoria !== null ? "CATEGORIA(S): " . $categoria : "";

$oPDF = new PDF();
$oPDF->Open();
$oPDF->AliasNbPages();
$oPDF->setfillcolor(235);
$oPDF->addpage();
$oPDF->ln();

// $oPDF->ln();
foreach ($aReport as $key => $data) {
    // Linha 1
    $oPDF->setfont('arial', 'b', 10);
    $oPDF->Cell(20, 5, 'Processo', 1, 0, 'C', true);
    $oPDF->Cell(25, 5, 'Data', 1, 0, 'C', true);
    $oPDF->Cell(20, 5, 'Número', 1, 0, 'C', true);
    $oPDF->Cell(35, 5, 'Modalidade', 1, 0, 'C', true);
    $oPDF->Cell(40, 5, 'Categoria', 1, 0, 'C', true);
    $oPDF->Cell(50, 5, 'Departamento', 1, 1, 'C', true);

    // Linha 2 (vazia)
    
    if (strlen($data->descrdepto) > 24) {
        $lineHeight = 10;
    } else {
        $lineHeight = 5;
    }

    $oPDF->setfont('arial', '', 9); // Fonte menor e sem negrito
    $oPDF->Cell(20, $lineHeight, $data->pc80_codproc, 1, 0, 'C');
    $oPDF->Cell(25, $lineHeight, date('d/m/Y', strtotime($data->pc80_data)), 1, 0, 'C');
    $oPDF->Cell(20, $lineHeight, $data->pc80_numdispensa, 1, 0, 'C');
    $oPDF->Cell(35, $lineHeight, $clcontratacoesdiretas->getTypeContracting($data->pc80_modalidadecontratacao), 1, 0, 'C');
    $oPDF->Cell(40, $lineHeight, $clcontratacoesdiretas->getDescriptionCategoryProcess($data->pc80_categoriaprocesso), 1, 0, 'C');
    $oPDF->MultiCell(50, 5, $data->descrdepto, 1, 'C');

    // Linha 3 (Objeto)
    $oPDF->setfont('arial', 'b', 10);
    $oPDF->Cell(190, 5, 'Objeto', 1, 1, 'C', true);

    // Linha 4 (vazia)`
    $oPDF->setfont('arial', '', 9);
    $oPDF->MultiCell(190, 5, $data->pc80_resumo, 1, 'L');

    // Linha 5
    $oPDF->setfont('arial', 'b', 10);
    $oPDF->Cell(95, 5, 'ID de Envio ao PNCP', 1, 0, 'C', true);
    $oPDF->Cell(95, 5, 'Data do Lançamento', 1, 1, 'C', true);

    // Linha 6 (vazia)
    $oPDF->setfont('arial', '', 9); // Fonte menor e sem negrito
    $oPDF->Cell(95, 5, $data->l213_numerocontrolepncp, 1, 0, 'C');
    $oPDF->Cell(95, 5, $data->l213_dtlancamento, 1, 1, 'C');

    $oPDF->ln(5);
}

$oPDF->Output();
