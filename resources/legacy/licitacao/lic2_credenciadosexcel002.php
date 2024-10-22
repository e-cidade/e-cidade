<?php
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require_once("libs/db_conecta.php");
require_once("libs/PHPExcel/Classes/PHPExcel.php");

/* Consulta para informações da licitação. */
$rsLicitacao = db_query("
select
	l20_edital,
	l20_numero,
	l03_descr,
	l20_dtpubratificacao,
	l20_objeto,
    l20_anousu
from
	liclicita
inner join cflicita on
	l20_codtipocom = l03_codigo
where
	l20_codigo = $l20_codigo;");

$liclicita = db_utils::fieldsMemory($rsLicitacao, 0);

/* Consulta para informações dos itens da licitação. */

$rsItensCredenciados = db_query("
select
	*
from
	liclicitem
inner join pcprocitem on
	pc81_codprocitem = l21_codpcprocitem
inner join pcorcamitemproc on
	pc31_pcprocitem = pc81_codprocitem
inner join itemprecoreferencia on
	si02_itemproccompra = pc31_orcamitem
inner join credenciamento on
	l205_item = l21_codpcprocitem
inner join cgm on
	l205_fornecedor = z01_numcgm
inner join solicitempcmater on
	pc16_solicitem = pc81_solicitem
inner join solicitem on 
	pc11_codigo = pc16_solicitem
inner join pcmater on
	pc16_codmater = pc01_codmater
inner join matunid on
	si02_codunidadeitem = m61_codmatunid
where
	l21_codliclicita = $l20_codigo
order by
    l205_fornecedor,pc11_seq;
");

$liclicita->l20_dtpubratificacao = implode('/', array_reverse(explode('-', $liclicita->l20_dtpubratificacao)));

$styleBold = [
    'font' => [
        'bold' => true,
    ],
];

$styleCabecalho = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => 'ADD8E6'
        )
    ),
    'font' => array(
        'size' => 10,
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);

$styleCelulas = array(
  'borders' => array(
      'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array('argb' => 'FF000000'),
      ),
  ),
  'alignment' => array(
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);


$sheet->setCellValue('A1', 'Processo: ' .  "$liclicita->l20_edital"."/"."$liclicita->l20_anousu");
$sheet->setCellValue('A2', mb_convert_encoding("Data de Ratificação: ",'UTF-8') . "$liclicita->l20_dtpubratificacao");
$sheet->setCellValue('A3', 'Objeto: ' . mb_convert_encoding($liclicita->l20_objeto,'UTF-8'));
$sheet->setCellValue('D1', 'Modalidade: ' ."$liclicita->l20_numero - $liclicita->l03_descr");

$sheet->getStyle('A1:A3')->applyFromArray($styleBold);
$sheet->getStyle('D1')->applyFromArray($styleBold);


// Variável referente ao fornecedor da iteração atual dos itens.
$fornecedoratual;
// Variável referente ao valor total dos itens de determinado fornecedor.
$total = 0;

$linhaAtual = 5;

for ($i = 0; $i < pg_numrows($rsItensCredenciados); $i++) {
    $item = db_utils::fieldsMemory($rsItensCredenciados, $i);

    /* Caso fornecedor da iteração atual ainda não foi emitido no relatório
        gerar cabeçalho com as informações do fornecedor acima das células referente aos itens  .*/

    if ($fornecedoratual != $item->l205_fornecedor) {
        $cpfcnpj = strlen($item->z01_cgccpf) == 14 ? "CNPJ" : "CPF";
        $cabecalho = mb_convert_encoding( "$item->z01_nome - $cpfcnpj: $item->z01_cgccpf - Data do Credenciamento: " . implode('/', array_reverse(explode('-', $item->l205_datacred))),'UTF-8');
        $sheet->setCellValue("A" . ($linhaAtual), $cabecalho);
        $sheet->getStyle("A" . ($linhaAtual))->applyFromArray($styleBold);
        $linhaAtual++;

        $sheet->setCellValue("A" . ($linhaAtual), 'Item');
        $sheet->setCellValue("B" . ($linhaAtual), 'Descricao');
        $sheet->setCellValue("C" . ($linhaAtual), 'Unidade');
        $sheet->setCellValue("D" . ($linhaAtual), 'Quantidade');
        $sheet->setCellValue("E" . ($linhaAtual), 'Valor Unitario');
        $sheet->setCellValue("F" . ($linhaAtual), 'Valor Total');
        $sheet->getStyle("A" . ($linhaAtual))->applyFromArray($styleCabecalho);
        $sheet->getStyle("B" . ($linhaAtual))->applyFromArray($styleCabecalho);
        $sheet->getStyle("C" . ($linhaAtual))->applyFromArray($styleCabecalho);
        $sheet->getStyle("D" . ($linhaAtual))->applyFromArray($styleCabecalho);
        $sheet->getStyle("E" . ($linhaAtual))->applyFromArray($styleCabecalho);
        $sheet->getStyle("F" . ($linhaAtual))->applyFromArray($styleCabecalho);

        $linhaAtual++;

        $fornecedoratual = $item->l205_fornecedor;
        $total = 0;
    }

    /* Calculo para altura da celula conforme o tamanho da descricao  do item */

    $sheet->setCellValue("A" . ($linhaAtual), $item->pc11_seq);
    $sheet->setCellValue("B" . ($linhaAtual), mb_convert_encoding($item->pc01_descrmater,'UTF-8'));
    $sheet->setCellValue("C" . ($linhaAtual), mb_convert_encoding($item->m61_descr,'UTF-8'));
    $sheet->setCellValue("D" . ($linhaAtual), $item->si02_qtditem);
    $sheet->setCellValue("E" . ($linhaAtual), $item->si02_vlprecoreferencia);
    $sheet->setCellValue("F" . ($linhaAtual),$item->si02_vltotalprecoreferencia);

    $sheet->getStyle("A" . ($linhaAtual))->applyFromArray($styleCelulas);
    $sheet->getStyle("B" . ($linhaAtual))->applyFromArray($styleCelulas);
    $sheet->getStyle("C" . ($linhaAtual))->applyFromArray($styleCelulas);
    $sheet->getStyle("D" . ($linhaAtual))->applyFromArray($styleCelulas);
    $sheet->getStyle("E" . ($linhaAtual))->applyFromArray($styleCelulas);
    $sheet->getStyle("F" . ($linhaAtual))->applyFromArray($styleCelulas);

    $sheet->getStyle("E" . ($linhaAtual))->getNumberFormat()->setFormatCode('#,##0.0000');
    $sheet->getStyle("F" . ($linhaAtual))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

    $linhaAtual++;

    $total += $item->si02_vltotalprecoreferencia;
    $proximofornecedor = db_utils::fieldsMemory($rsItensCredenciados, $i + 1)->l205_fornecedor;

    /* Caso fornecedor diferente na próxima posição da iteração 
       inserir célula de valor total.*/

    if ($proximofornecedor != $item->l205_fornecedor) {
        $total = 'Total: R$ ' . number_format($total, 2, ',', '.');
        $sheet->setCellValue("F" . ($linhaAtual), mb_convert_encoding($total,'UTF-8'));
        $sheet->getStyle("F" . ($linhaAtual))->applyFromArray($styleBold);
        $linhaAtual++;
    }
}

$sheet->getStyle('B7:B1000')->getAlignment()->setWrapText(true);

header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=credenciados.xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
