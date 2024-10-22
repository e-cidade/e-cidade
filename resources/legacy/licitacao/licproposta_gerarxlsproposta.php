<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libsys.php");
require_once("std/db_stdClass.php");
require_once("classes/db_pcorcam_classe.php");
include("libs/PHPExcel/Classes/PHPExcel.php");
require_once("classes/db_pcorcamitem_classe.php");

use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Services\Patrimonial\Licitacao\LicpropostaService;
use App\Repositories\Patrimonial\Licitacao\PcorcamforneRepository;

$l20_codigo = $_GET['l20_codigo'];
$fornecedor = $_GET['fornecedor'];
$lote = $_GET['lote'];
$descrforne = $_GET['descrforne'];
$cnpjforne = $_GET['cnpjforne'];


$clpcorcam = new cl_pcorcam();
$clpcorcamitem = new cl_pcorcamitem();
$objPHPExcel = new PHPExcel();

// Acessando as consultas do RPC
$licilicitemRepository = new LicilicitemRepository();
$licpropostaService = new LicpropostaService();
$pcorcamforneRepository = new PcorcamforneRepository();

$itensProposta = $licilicitemRepository->getItensLicitacao($l20_codigo, $fornecedor, $lote);
$itensFornecedor = $pcorcamforneRepository->getfornecedoreslicitacao($l20_codigo);
$criterio = $licpropostaService->getCriterio($l20_codigo);
$criteriodeadjudicacao = $criterio[0]->l20_criterioadjudicacao;
$lote = $itensProposta[0]->l04_descricao;




// Atualizar o cabeçalho da planilha
$sheet = $objPHPExcel->getActiveSheet();

$styleTitulo = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'FFA0A0A0',
        ),
        'endcolor' => array(
            'argb' => 'FFFFFFFF',
        ),
    ),
    'font' => array(
        'size' => 12,
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);

$styleTitulo1 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'font' => array(
        'size' => 10,
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
);

$styleResTitulo1 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'font' => array(
        'size' => 10,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
);

$styleItens2 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startcolor' => array(
            'argb' => 'FFA0A0A0',
        ),
        'endcolor' => array(
            'argb' => 'FFFFFFFF',
        ),
    ),
    'font' => array(
        'size' => 10,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);

// Verificar se o valor de $lote começa com 'LOTE_AUTOITEM'
$mostrarColunaLote = !preg_match('/^LOTE_AUTOITEM/', $lote);

// Iniciar a planilha
$sheet->setCellValue('A1', 'Proposta de Processo de Licitatorio - ' . $itensProposta[0]->l20_numero . '/' . $itensProposta[0]->l20_anousu);
$sheet->setCellValue('A2', "Codigo da Proposta:   " . $itensProposta[0]->l224_codigo);
$sheet->setCellValue('A3', "Objeto: " . utf8_encode($itensProposta[0]->l20_objeto));
$sheet->setCellValue('A4', 'Codigo do Fornecedor:   ' . $fornecedor);
$sheet->setCellValue('A5', 'CPF / CNPJ:   ' . $cnpjforne);
$sheet->setCellValue('A6', 'Nome / Razao Social:   ' .  utf8_encode($descrforne));

// Mesclar as células
$sheet->mergeCells('A1:J1');
$sheet->mergeCells('A2:J2');
$sheet->mergeCells('A3:J3');
$sheet->mergeCells('A4:J4');
$sheet->mergeCells('A5:J5');
$sheet->mergeCells('A6:J6');

// Cabeçalho
$sheet->getStyle('A1:J1')->applyFromArray($styleTitulo);
$sheet->getStyle('A2:A6')->applyFromArray($styleTitulo1);
$sheet->getStyle('B2:B6')->applyFromArray($styleTitulo1);
$sheet->getStyle('C2:C6')->applyFromArray($styleTitulo1);
$sheet->getStyle('D2:D6')->applyFromArray($styleTitulo1);
$sheet->getStyle('E2:E6')->applyFromArray($styleTitulo1);
// Resposta do cabeçalho
$sheet->getStyle('F2:J6')->applyFromArray($styleResTitulo1);

// Itens
$sheet->setCellValue('A7', 'Ordem');
$sheet->setCellValue('B7', 'Item');
$sheet->setCellValue('C7', 'Descricao');
if ($mostrarColunaLote) {
    $sheet->setCellValue('D7', 'Lote'); // Nova coluna
    $sheet->getColumnDimension('D')->setWidth(20); // Ajuste conforme necessário
} else {
    $sheet->getColumnDimension('D')->setVisible(false); // Esconde a coluna
}
$sheet->setCellValue('E7', 'Unidade');
$sheet->setCellValue('F7', 'Quantidade');
$sheet->setCellValue('G7', 'Percentual');
$sheet->setCellValue('H7', 'Valor Unitario');
$sheet->setCellValue('I7', 'Valor Total');
$sheet->setCellValue('J7', 'Marca'); // Ajustado para coluna J

// Estilizar o cabeçalho
$sheet->getStyle('A7:J7')->applyFromArray($styleItens2);

// Ajustar a largura das colunas
$sheet->getColumnDimension('C')->setWidth(80); // Coluna de descrição
$sheet->getStyle('C7')->getAlignment()->setWrapText(true);

// Preencher dados
$row = 8;
foreach ($itensProposta as $item) {
    $descricao = $item->pc01_descrmater;
    if (!empty($item->pc01_complmater)) {
        $descricao .= " || " . $item->pc01_complmater;
    }

    $sheet->setCellValue('A' . $row, $item->l21_ordem);
    $sheet->setCellValue('B' . $row, $item->pc01_codmater);
    $sheet->setCellValue('C' . $row, utf8_encode($descricao));
    if ($mostrarColunaLote) {
        $sheet->setCellValue('D' . $row, utf8_encode($item->l04_descricao)); // Nova coluna
    }
    $sheet->setCellValue('E' . $row, utf8_encode($item->unidade));
    $sheet->setCellValue('F' . $row, $item->quantidade);
    $sheet->setCellValue('G' . $row, $item->percentual);
    $sheet->setCellValue('H' . $row, $item->vlr_unitario);
    $sheet->setCellValue('I' . $row, "=F$row*H$row");
    $sheet->setCellValue('J' . $row, utf8_encode($item->marca));

    // Formatação personalizada para exibir até 4 casas decimais e deixar vazio se zero
    $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('[<=0]"";0.0000');
    $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('[<=0]"";0.00');

    // Ajustes de largura e quebra de texto
    $sheet->getStyle('C' . $row)->getAlignment()->setWrapText(true);
    $sheet->getRowDimension($row)->setRowHeight(-1);

    $row++;
}

// Aplica bordas a todas as células preenchidas
$sheet->getStyle('A7:J' . ($row - 1))->applyFromArray($styleResTitulo1);

// Ajustar a largura das colunas automaticamente, exceto 'C' (descrição)
foreach (range('A', 'J') as $columnID) {
    if ($columnID !== 'C') {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
}


$sheet->getProtection()->setSheet(true);
$sheet->getProtection()->setPassword('PHPexcel');

// Bloquear todas as células por padrão
$sheet->getStyle('A1:J' . ($row - 1))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);

// Desbloquear as células das colunas F, G, H e J a partir da linha 8
for ($i = 8; $i <= $row; $i++) {
    $sheet->getStyle("F$i")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED); // F deve estar protegida?
    $sheet->getStyle("G$i")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
    $sheet->getStyle("H$i")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
    $sheet->getStyle("J$i")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
}

foreach ($itensProposta as $index => $item) {
    $linha = $index + 8; // Ajuste para começar na linha correta (exemplo: linha 8)

    // Verificar se o critério é 1 ou 2, e se taxa ou tabela é igual a 1
    if (($criteriodeadjudicacao == 1 || $criteriodeadjudicacao == 2) && ($item->pc01_taxa == 1 )) {
        // Bloqueia as colunas H e I para edição deste item
        $sheet->getStyle('H' . $linha)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $sheet->getStyle('I' . $linha)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
    } else {
        // Caso contrário, desbloqueia para edição
        $sheet->getStyle('H' . $linha)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        //$sheet->getStyle('I' . $linha)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle('G' . $linha)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);

    }

    if(($criteriodeadjudicacao == 1 || $criteriodeadjudicacao == 2) && ($item->pc01_tabela == 1 )){
        $sheet->getStyle('G' . $linha)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle('H' . $linha)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $sheet->getStyle('I' . $linha)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
    }
}
if($criteriodeadjudicacao == 1 || $criteriodeadjudicacao == 2){
    $sheet->getColumnDimension('G')->setVisible(true);
}else{
    $sheet->getColumnDimension('G')->setVisible(false);
}


$nomefile = "Cadastro de Propostas" . "." . "xlsx";

header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=$nomefile");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
