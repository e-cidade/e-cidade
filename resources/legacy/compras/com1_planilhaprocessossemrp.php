<?php
//ini_set('display_errors','on');
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libsys.php");
require_once("std/db_stdClass.php");
include("libs/PHPExcel/Classes/PHPExcel.php");

$oGet = db_utils::postMemory($_GET);
$objPHPExcel = new PHPExcel;
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
//Inicio
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Solicitacao');
//Iniciando planilha
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Solicitacao');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Data');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Departamento');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Resumo da Solicitacao');
//cabeçalho
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(80);

//ITENS DA Solicitacao
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('Itens da Solicitacao');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Solicitacao');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Seq');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Quantidade');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Servico Qtd.');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Reservado');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Exclusivo');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Unidade');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Cod. Material');
//cabeçalho
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);
$objPHPExcel->getActiveSheet()->setTitle('Processo de Compras');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Solicitacao');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Tipo de Processo');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Criterio de Adjudicacao');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Dispensa por valor');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Numero Dispensa');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Orçamento Sigiloso');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Sub Contratacao');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Dados Complementares');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Amparo Legal');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Categoria Processo');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Modalidade Contratacao');

//cabeçalho
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleTitulo);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);


// Define a primeira planilha como ativa novamente
$objPHPExcel->setActiveSheetIndex(0);
$nomefile = "Padrao_subgrupos_contass.xlsx";

header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=$nomefile");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Pragma: public");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
