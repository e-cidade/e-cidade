<?php
include("fpdf151/pdf.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $aFiltros);

function calculaReceita($sNatureza, $aFontes)
{
  foreach ($aFontes as $oFonte) {
    if (substr($sNatureza, 0, strlen($oFonte)) == $oFonte) {
      return true;
    }
  }
  return false;
}

function getDadosReceita($iAno, $sDataInicial1, $sDataFinal1, $aInstituicoes, $aFontesReceita)
{
  $iTotalReceita = 0;
  $rsDadosReceita = db_receitasaldo(11, 2, 3, true,  " o70_instit in (" . implode(',', $aInstituicoes) . ") ", $iAno, $sDataInicial1, $sDataFinal1, false, ' * ', true, 0);

  for ($i = 0; $i < pg_num_rows($rsDadosReceita); $i++) {
    $oDadosReceita = db_utils::fieldsMemory($rsDadosReceita, $i);
    if (calculaReceita($oDadosReceita->o57_fonte, $aFontesReceita)) {
      $iTotalReceita += $oDadosReceita->saldo_arrecadado;
    }
  }
  db_query("drop table if exists work_receita");
  return $iTotalReceita;
}

function getDadosDespesa($ano, $inicio, $fim, $aInstint)
{
  $sSql = "SELECT 
                sum(substr(p.dotacaosaldo, 55, 12)::float8) AS total_mes
            FROM
                (SELECT w.o58_coddot,
                        w.o58_codele,
                        w.o58_projativ,
                        w.o58_anousu,
                        w.o58_instit,
                        e.o56_elemento,
                        e.o56_descr,
                        e.o56_orcado,
                        orctiporec.o15_codigo,
                        fc_dotacaosaldo({$ano}, o58_coddot, 2, '{$inicio}', '{$fim}') AS dotacaosaldo
                FROM orcdotacao w
                INNER JOIN orcelemento e ON w.o58_codele = e.o56_codele AND e.o56_anousu = w.o58_anousu AND e.o56_orcado IS TRUE
                INNER JOIN orcprojativ ope ON w.o58_projativ = ope.o55_projativ AND ope.o55_anousu = w.o58_anousu
                INNER JOIN orctiporec ON orctiporec.o15_codigo = w.o58_codigo
                WHERE w.o58_anousu = {$ano}
                    AND w.o58_instit IN (" . implode(',', $aInstint) . ")
                    and substr(e.o56_elemento, 1, 2) = '33') as p
            WHERE (substr(p.dotacaosaldo, 29, 12)::float8 - substr(p.dotacaosaldo, 42, 12)::float8) + substr(p.dotacaosaldo, 55, 12)::float8 <> 0";
  return db_utils::fieldsMemory(db_query($sSql), 0)->total_mes;
}

$clempresto = new cl_empresto;

$aTabelaDados[1][0]  = "";
$aTabelaDados[2][0]  = "";
$aTabelaDados[3][0]  = "";
$aTabelaDados[4][0]  = "";
$aTabelaDados[5][0]  = "";
$aTabelaDados[6][0]  = "";
$aTabelaDados[7][0]  = "";
$aTabelaDados[8][0]  = "";
$aTabelaDados[9][0]  = "";
$aTabelaDados[10][0] = "";
$aTabelaDados[11][0] = "";
$aTabelaDados[12][0] = "";
$aTabelaDados[13][0] = " Total";
$aTabelaDados[14][0] = " Percentual Apurado";

$iAnoUsu = db_getsession('DB_anousu');
$iExercicioAnt = $iAnoUsu - 1;
$aInstituicoes = DB::table('db_config')->pluck('codigo')->toArray();
$aFontesReceita = array('41', '47', '4911', '4921', '4931', '4951', '4961', '4981', '4991', '4917', '4927', '4937', '4957', '4967', '4987', '4997');
$sSelect = "sum(e91_vlremp) - sum(e91_vlranu) - sum(e91_vlrliq) as rp_n_proc, sum(vlranuliqnaoproc) as rp_n_proc_anulado";

Carbon::setLocale('pt_BR');
$data = Carbon::create($iAnousu, $iPeriodo, 1);
$sDataPeriodoInicial = Carbon::createFromFormat('Y-m-d', $data->subMonths(12)->format('Y-m-d'));
$sPeriodoInicio = $data->addMonth()->format('d/m/Y');
$sPeriodoFim = $data->addMonths(11)->endOfMonth()->format('d/m/Y');

$rsRestoAPagar = $clempresto->sql_record($clempresto->sql_rp_novo($iAnoUsu, " e60_instit in (" . implode(',', $aInstituicoes) . ") ", "{$iAnoUsu}-01-01", $data->endOfMonth()->format('Y-m-d'), '', "and e60_anousu = {$iExercicioAnt} and o56_elemento like '33%' ", 'group by empempenho.e60_anousu ', $sSelect));
$oRestoAPagar = db_utils::fieldsMemory($rsRestoAPagar, 0);
for ($i = 1; $i <= 12; $i++) {
  $sData = Carbon::createFromFormat('Y-m-d', $sDataPeriodoInicial->addMonth()->format('Y-m-d'));
  $sDataInicial = $sData->format('Y-m-d');
  $sDataFinal = $sData->endOfMonth()->format('Y-m-d');
  $iAno = $sData->year;
  if ($sData->month == 12) {
    $aTabelaDados[$i][1] = (getDadosDespesa($iAno, $sDataInicial, $sDataFinal, $aInstituicoes) + $oRestoAPagar->rp_n_proc) - $oRestoAPagar->rp_n_proc_anulado;
  } else {
    $aTabelaDados[$i][1] = getDadosDespesa($iAno, $sDataInicial, $sDataFinal, $aInstituicoes);
  }
  $aTabelaDados[$i][2] = getDadosReceita($iAno, $sDataInicial, $sDataFinal, $aInstituicoes, $aFontesReceita);
  $aTabelaDados[$i][0] = mb_convert_encoding(ucfirst($sData->monthName), 'ISO-8859-1', 'UTF-8') . '/' . $iAno;
  $aTabelaDados[13][1] += $aTabelaDados[$i][1];
  $aTabelaDados[13][2] += $aTabelaDados[$i][2];
}

$head3 = "Relação entre a Despesa Corrente e Receita Corrente";
$head5 = "PERIODO: {$sPeriodoInicio} A {$sPeriodoFim}";
$alt   = 5;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak('on', 0);
$pdf->line(2, 148.5, 208, 148.5);
$pdf->setfillcolor(235);
$pdf->addpage();

$pdf->ln();
$pdf->setfont("arial", "B", 8);
$pdf->setX(35);
$pdf->cell(65, $alt, "Despesa Corrente X Receita Corrente", "RTLB", 0, "C", 0);
$pdf->cell(40, $alt, "Despesas Correntes", "RTLB", 0, "C", 0);
$pdf->cell(40, $alt, "Receitas Correntes", "RTLB", 0, "C", 0);
$pdf->ln();
$pdf->setX(35);

for ($i = 1; $i <= count($aTabelaDados); $i++) {
  if ($i == count($aTabelaDados) || $i == count($aTabelaDados) - 1) {
    $pdf->setfont("arial", "B", 8);
  } else {
    $pdf->setfont("arial", "", 8);
  }
  $pdf->cell(65, $alt, $aTabelaDados[$i][0], "RLB", 0, "L", 0);
  if ($i == count($aTabelaDados)) {
    $iPercentual = ($aTabelaDados[13][1] / $aTabelaDados[13][2]) * 100;
    $pdf->cell(80, $alt, db_formatar($iPercentual, 'f') . "%", "RLB", 0, "R", 0);
  } else {
    $pdf->cell(40, $alt, db_formatar($aTabelaDados[$i][1], 'f'), "RLB", 0, "R", 0);
    $pdf->cell(40, $alt, db_formatar($aTabelaDados[$i][2], 'f'), "RLB", 0, "R", 0);
  }
  $pdf->ln();
  $pdf->setX(35);
}
$pdf->Output();
