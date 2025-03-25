<?php

use App\Helpers\StringHelper;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Repositories\Patrimonial\Licitacao\PcorcamforneRepository;
use App\Services\Patrimonial\Licitacao\LicpropostaService;
use App\Services\Patrimonial\Licitacao\LicpropostavincService;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\FaseDeLancesService;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\ReadequarPropostaService;

include("libs/PHPExcel/Classes/PHPExcel.php");

$objPHPExcel = new PHPExcel();

$licitacao = $_GET['licitacao'];
$fornecedor = $_GET['fornecedor'];
$lote = $_GET['lote'];
$descrforne = $_GET['descrforne'];
$cnpj = $_GTE['cnpj'];

$licpropostaService = new LicpropostaService();
$criterio = $licpropostaService->getCriterio($licitacao);

$readequarPropostaService = new ReadequarPropostaService();
$itensProposta = $readequarPropostaService->obterItensDaReadequecaoDeProposta($licitacao, $fornecedor, $lote);

$criteriodeadjudicacao = $criterio[0]->l20_criterioadjudicacao;

$faseDeLancesService = new FaseDeLancesService();
$param = $faseDeLancesService->obterParametros();

$licilicitemRepository = new LicilicitemRepository();
$getTheLowestBidLot = $licilicitemRepository->getTheLowestBidLot($licitacao, $lote, $param->l13_clapercent);
$orcamforne = $getTheLowestBidLot->pc21_orcamforne;

$pcorcamforneRepository = new PcorcamforneRepository();
$fornecedores = $pcorcamforneRepository->getSupplierAndTheirDataFromCgm($orcamforne);

$licpropostavincService = new LicpropostavincService();
$rsProposta = $licpropostavincService->getLicpropostavinc($licitacao, $orcamforne);

$data = [
    'licitacao' => $licitacao,
    'fornecedor' => $fornecedor,
    'lote' => $lote,
    'descrforne' => $descrforne,
    'cnpj' => $cnpj,
    'criteriodeadjudicacao' => $criteriodeadjudicacao
];

$itensProposta = $itensProposta;
$propostaCodigo = $rsProposta->l223_codigo;
$fornecedores = $fornecedore;

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

// Iniciar a planilha
$sheet->setCellValue('A1', 'Proposta de Processo de Licitatorio - ' . $data['licitacao'] . ' - Lote - ' . $data['lote']);
$sheet->setCellValue('A2', "Codigo da Proposta:   " . $propostaCodigo);
$sheet->setCellValue('A3', "Objeto: " . '-');
$sheet->setCellValue('A4', 'Codigo do Fornecedor:   ' . $data['fornecedor']);
$sheet->setCellValue('A5', 'CPF / CNPJ:   ' . $data['cnpj']);
$sheet->setCellValue('A6', 'Nome / Razao Social:   ' .  $data['descrforne']);

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
$sheet->setCellValue('D7', 'Lote');
$sheet->setCellValue('E7', 'Unidade');
$sheet->setCellValue('F7', 'Quantidade');
$sheet->setCellValue('G7', 'Percentual');
$sheet->setCellValue('H7', 'Valor Unitario');
$sheet->setCellValue('I7', 'Valor Total');
$sheet->setCellValue('J7', 'Marca');

// Estilizar o cabeçalho
$sheet->getStyle('A7:J7')->applyFromArray($styleItens2);

// Ajustar a largura das colunas
$sheet->getColumnDimension('C')->setWidth(80); // Coluna de descrição
$sheet->getStyle('C7')->getAlignment()->setWrapText(true);

// Preencher dados
$row = 8;
foreach ($itensProposta as $item) {
    $sheet->setCellValue('A' . $row, strval($item->l21_ordem));
    $sheet->setCellValue('B' . $row, strval($item->pc01_codmater));
    $sheet->setCellValue('C' . $row, $item->pc01_descrmater);
    $sheet->setCellValue('D' . $row, $item->l04_descricao);
    $sheet->setCellValue('E' . $row, $item->unidade);
    $sheet->setCellValue('F' . $row, $item->quantidade);
    $sheet->setCellValue('G' . $row, $item->percentual);
    $sheet->setCellValue('H' . $row, $item->vlr_unitario);
    $sheet->setCellValue('I' . $row, "=F$row*H$row");
    $sheet->setCellValue('J' . $row, $item->marca);

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

$row = 8;
foreach ($itensProposta as $index => $item) {
    // Verificar se o critério é 1 ou 2, e se taxa ou tabela é igual a 1
    if (($data['criteriodeadjudicacao'] == 1 || $data['criteriodeadjudicacao'] == 2) && ($item->pc01_taxa == 1)) {
        // Bloqueia as colunas H e I para edição deste item
        $sheet->getStyle('H' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $sheet->getStyle('I' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
    } else {
        // Caso contrário, desbloqueia para edição
        $sheet->getStyle('H' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle('G' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
    }

    if (($data['criteriodeadjudicacao'] == 1 || $data['criteriodeadjudicacao'] == 2) && ($item->pc01_tabela == 1)) {
        $sheet->getStyle('G' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $sheet->getStyle('H' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
        $sheet->getStyle('I' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);
    }

    $row++;
}

$nomefile = "Cadastro_de_Propostas.xlsx";

header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=$nomefile");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');