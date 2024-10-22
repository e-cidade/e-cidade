<?php

use ECidade\V3\Extension\Document;

require_once("fpdf151/pdf.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

$oPost = db_utils::postMemory($_POST);
$sJoins = "";
$tam = '04';

if(empty($oPost->inscricao) or empty($oPost->ano) or empty($oPost->competencia) ){
  db_redireciona('db_erros.php?fechar=true&db_erro=Favor informar Inscrição, Ano e Competência.');
      exit;
}

$head4 = "RELATÓRIO DE RETENÇÕES";
$head6 = "Código da Inscrição: ".$oPost->inscricao;
$head8 = "Competência: ".$oPost->competencia."/".$oPost->ano;
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);

$pdf->Ln(5);
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(278,05,"NOTAS AVULSAS",0,"C");
$pdf->Ln(5);

$pdf->SetFont('Arial','',7);
$pdf->SetFillColor(235);
$pdf->Cell(25,$tam,"TIPO",1,0,"C",1);
$pdf->Cell(25,$tam,"NOTA",1,0,"C",1);
$pdf->Cell(30,$tam,"VALOR DA NOTA",1,0,"C",1);
$pdf->Cell(30,$tam,"BASE INSS",1,0,"C",1);
$pdf->Cell(20,$tam,"INSS",1,0,"C",1);
$pdf->Cell(30,$tam,"SOMATÓRIO INSS",1,0,"C",1);
$pdf->Cell(30,$tam,"BASE IRRF",1,0,"C",1);
$pdf->Cell(35,$tam,"BASE CALC. APÓS INSS",1,0,"C",1);
$pdf->Cell(30,$tam,"VALOR RETIDO",1,0,"C",1);
$pdf->Cell(23,$tam,"ALÍQ. IRRF (% )",1,1,"C",1);

$sSql = "select case when issnotaavulsaservico.q62_tiporetinss = 'material' then 'carga'
              else issnotaavulsaservico.q62_tiporetinss end as tipo,
              q62_issnotaavulsa as nota,
              q62_vlrtotal as valornota,
              q62_baseinss as baseinss,
              q62_vlrinss as inss,
              q62_baseirrf as baseirrf,
              q62_basecalcaposinss as basecalcaposinss,
              q62_vlrirrf as valorretido,
              q62_aliquotairrf as aliqirrf
          from issnotaavulsaservico
            inner join issnotaavulsa  on  issnotaavulsa.q51_sequencial = issnotaavulsaservico.q62_issnotaavulsa
            inner join issbase  on  issbase.q02_inscr = issnotaavulsa.q51_inscr
          where issbase.q02_inscr = ".$oPost->inscricao."
            and extract(year from issnotaavulsa.q51_data ) = ".$oPost->ano."
            and extract(month from issnotaavulsa.q51_data ) = ".$oPost->competencia;

$rsCreditos = pg_exec($sSql) or die($sSql);
$iQtd = pg_num_rows($rsCreditos);

$SomaInss = '';
$SomaBaseIrrf = '';

if ($rsCreditos && $iQtd > 0) {

  for ($i = 0; $i < $iQtd; $i++) {

    $oCredito = db_utils::fieldsMemory($rsCreditos, $i);
    $SomaInss += $oCredito->inss;
    $SomaBaseIrrf += $oCredito->baseirrf;

    $pdf->SetFont('Arial','',7);
    $pdf->SetFillColor(255);
    $pdf->Cell(25,$tam,$oCredito->tipo,1,0,"C",1);
    $pdf->Cell(25,$tam,$oCredito->nota,1,0,"C",1);
    $pdf->Cell(30,$tam,$oCredito->valornota,1,0,"C",1);
    $pdf->Cell(30,$tam,$oCredito->baseinss,1,0,"C",1);
    $pdf->Cell(20,$tam,$oCredito->inss,1,0,"C",1);
    $pdf->Cell(30,$tam,$SomaInss,1,0,"C",1);
    $pdf->Cell(30,$tam,$SomaBaseIrrf,1,0,"C",1);
    $pdf->Cell(35,$tam,$oCredito->basecalcaposinss,1,0,"C",1);
    $pdf->Cell(30,$tam,$oCredito->valorretido,1,0,"C",1);
    $pdf->Cell(23,$tam,$oCredito->aliqirrf,1,1,"C",1);
  }
}

$pdf->Ln(10);
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(278,05,"TABELA DE RETENÇÕES",0,"C");
$pdf->Ln(5);

$pdf->SetFont('Arial','',7);
$pdf->SetFillColor(255);

$pdf->Cell(28,$tam,"TIPO",1,0,"C",1);
$pdf->Cell(50,$tam,"BASE DE CÁLCULO",1,0,"C",1);
$pdf->Cell(45,$tam,"ALÍQUOTA",1,0,"C",1);
$pdf->Cell(45,$tam,"PARCELA A DEDUZIR (EM R$)",1,0,"C",1);
$pdf->Cell(55,$tam,"BASE DE CÁLCULO PARA INSS",1,0,"C",1);
$pdf->Cell(55,$tam,"BASE DE CÁLCULO PARA IRRF",1,1,"C",1);

$pdf->Cell(28,$tam,"Passageiros",1,0,"C",1);
$pdf->Cell(50,$tam,"Até 2.112,00",1,0,"C",1);
$pdf->Cell(45,$tam,"zero",1,0,"C",1);
$pdf->Cell(45,$tam,"zero",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"60% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Passageiros",1,0,"C",1);
$pdf->Cell(50,$tam,"De 2.112,00 até 2826,65",1,0,"C",1);
$pdf->Cell(45,$tam,"7,5%",1,0,"C",1);
$pdf->Cell(45,$tam,"158,40",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"60% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Passageiros",1,0,"C",1);
$pdf->Cell(50,$tam,"De 2826,66 até 3.751,05",1,0,"C",1);
$pdf->Cell(45,$tam,"15%",1,0,"C",1);
$pdf->Cell(45,$tam,"370,4",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"60% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Passageiros",1,0,"C",1);
$pdf->Cell(50,$tam,"De 3.751,06 até 4.664,68",1,0,"C",1);
$pdf->Cell(45,$tam,"22,50%",1,0,"C",1);
$pdf->Cell(45,$tam,"651,73",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"60% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Passageiros",1,0,"C",1);
$pdf->Cell(50,$tam,"Acima de 4.664,68",1,0,"C",1);
$pdf->Cell(45,$tam,"27,50%",1,0,"C",1);
$pdf->Cell(45,$tam,"884,96",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"60% do valor da nota",1,1,"C",1);

$pdf->Ln(2);

$pdf->Cell(28,$tam,"TIPO",1,0,"C",1);
$pdf->Cell(50,$tam,"BASE DE CÁLCULO",1,0,"C",1);
$pdf->Cell(45,$tam,"ALÍQUOTA",1,0,"C",1);
$pdf->Cell(45,$tam,"PARCELA A DEDUZIR (EM R$)",1,0,"C",1);
$pdf->Cell(55,$tam,"BASE DE CÁLCULO PARA INSS",1,0,"C",1);
$pdf->Cell(55,$tam,"BASE DE CÁLCULO PARA IRRF",1,1,"C",1);

$pdf->Cell(28,$tam,"Carga",1,0,"C",1);
$pdf->Cell(50,$tam,"Até 2.112,00",1,0,"C",1);
$pdf->Cell(45,$tam,"zero",1,0,"C",1);
$pdf->Cell(45,$tam,"zero",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"10% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Carga",1,0,"C",1);
$pdf->Cell(50,$tam,"De 2.112,00 até 2826,65",1,0,"C",1);
$pdf->Cell(45,$tam,"7,5%",1,0,"C",1);
$pdf->Cell(45,$tam,"158,40",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"10% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Carga",1,0,"C",1);
$pdf->Cell(50,$tam,"De 2826,66 até 3.751,05",1,0,"C",1);
$pdf->Cell(45,$tam,"15%",1,0,"C",1);
$pdf->Cell(45,$tam,"370,4",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"10% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Carga",1,0,"C",1);
$pdf->Cell(50,$tam,"De 3.751,06 até 4.664,68",1,0,"C",1);
$pdf->Cell(45,$tam,"22,50%",1,0,"C",1);
$pdf->Cell(45,$tam,"651,73",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"10% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Carga",1,0,"C",1);
$pdf->Cell(50,$tam,"Acima de 4.664,68",1,0,"C",1);
$pdf->Cell(45,$tam,"27,50%",1,0,"C",1);
$pdf->Cell(45,$tam,"884,96",1,0,"C",1);
$pdf->Cell(55,$tam,"20% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"10% do valor da nota",1,1,"C",1);

$pdf->Ln(2);

$pdf->Cell(28,$tam,"TIPO",1,0,"C",1);
$pdf->Cell(50,$tam,"BASE DE CÁLCULO",1,0,"C",1);
$pdf->Cell(45,$tam,"ALÍQUOTA",1,0,"C",1);
$pdf->Cell(45,$tam,"PARCELA A DEDUZIR (EM R$)",1,0,"C",1);
$pdf->Cell(55,$tam,"BASE DE CÁLCULO PARA INSS",1,0,"C",1);
$pdf->Cell(55,$tam,"BASE DE CÁLCULO PARA IRRF",1,1,"C",1);

$pdf->Cell(28,$tam,"Outros",1,0,"C",1);
$pdf->Cell(50,$tam,"Até 2.112,00",1,0,"C",1);
$pdf->Cell(45,$tam,"zero",1,0,"C",1);
$pdf->Cell(45,$tam,"zero",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Outros",1,0,"C",1);
$pdf->Cell(50,$tam,"De 2.112,00 até 2826,65",1,0,"C",1);
$pdf->Cell(45,$tam,"7,5%",1,0,"C",1);
$pdf->Cell(45,$tam,"158,40",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Outros",1,0,"C",1);
$pdf->Cell(50,$tam,"De 2826,66 até 3.751,05",1,0,"C",1);
$pdf->Cell(45,$tam,"15%",1,0,"C",1);
$pdf->Cell(45,$tam,"370,4",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Outros",1,0,"C",1);
$pdf->Cell(50,$tam,"De 3.751,06 até 4.664,68",1,0,"C",1);
$pdf->Cell(45,$tam,"22,50%",1,0,"C",1);
$pdf->Cell(45,$tam,"651,73",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,1,"C",1);

$pdf->Cell(28,$tam,"Outros",1,0,"C",1);
$pdf->Cell(50,$tam,"Acima de 4.664,68",1,0,"C",1);
$pdf->Cell(45,$tam,"27,50%",1,0,"C",1);
$pdf->Cell(45,$tam,"884,96",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,0,"C",1);
$pdf->Cell(55,$tam,"100% do valor da nota",1,1,"C",1);

$pdf->output();
