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
$clpcorcam = new cl_pcorcam();
$objPHPExcel = new PHPExcel;

//Inicio
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

//Iniciando planilha
$sheet->setCellValue('A1', 'pc01_descrmater');
$sheet->setCellValue('B1', 'pc01_complmater');
$sheet->setCellValue('C1', 'pc01_codsubgrupo');
$sheet->setCellValue('D1', 'pc01_servico');
$sheet->setCellValue('E1', 'pc01_data');
$sheet->setCellValue('F1', 'pc01_tabela');
$sheet->setCellValue('G1', 'pc01_taxa');
$sheet->setCellValue('H1', 'pc01_obras');
$sheet->setCellValue('I1', 'pc01_codmaterant');
$sheet->setCellValue('J1', 'pc01_regimobiliario');
$sheet->setCellValue('K1', 'elemento');


//cabeçalho
$sheet->getStyle('A1:K1')->applyFromArray($styleTitulo);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(200);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('k')->setWidth(20);

$nomefile = "Padrao_Materiais_contass.xlsx";

header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=$nomefile");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Pragma: public");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
