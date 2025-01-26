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
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("classes/db_empresto_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_orcelemento_classe.php");
require_once("classes/db_orcprojativ_classe.php");
require_once("classes/db_orcdotacao_classe.php");
require_once("model/contabilidade/relatorios/ensino/RelatorioReceitaeDespesaEnsino.model.php");
require_once("libs/db_sql.php");
require_once("classes/db_dadosexercicioanterior_classe.php");

use Mpdf\Mpdf;
use Mpdf\MpdfException;
$clempresto = new cl_empresto;
$clrotulo = new rotulocampo;
$clorcelemento = new cl_orcelemento;
$clorcprojativ = new cl_orcprojativ;
$clselorcdotacao = new cl_selorcdotacao();
$oReceitaeDespesaSaude = new RelatorioReceitaeDespesaEnsino();
$cldadosexercicioanterior = new cl_dadosexercicioanterior;

db_postmemory($HTTP_POST_VARS);

$dtini = implode("-", array_reverse(explode("/", $DBtxt21)));
$dtfim = implode("-", array_reverse(explode("/", $DBtxt22)));

$instits = str_replace('-', ', ', $db_selinstit);
$aInstits = explode(",", $instits);
if (count($aInstits) > 1) {
    $oInstit = new Instituicao();
    $oInstit = $oInstit->getDadosPrefeitura();
} else {
    foreach ($aInstits as $iInstit) {
        $oInstit = new Instituicao($iInstit);
    }
}
db_inicio_transacao();

$sWhereReceita      = "o70_instit in ({$instits})";
$oReceitas = db_receitasaldo(11, 1, 3, true, $sWhereReceita, $anousu, $dtini, $dtfim, false, ' * ', true, 0);
$aReceitas = db_utils::getColectionByRecord($oReceitas);

$sWhereDespesa      = " o58_instit in({$instits})";
//Aqui passo o(s) exercicio(s) e a funcao faz o sql para cada exercicio
criaWorkDotacao($sWhereDespesa,array($anousu), $dtini, $dtfim);

$fIPTR = 0;
$fIPTU = 0;
$fIRRF = 0;
$fIRRFO = 0;
$fITBI = 0;
$fISS = 0;
$fFPM = 0;
$fITR = 0;
$fICMS = 0;
$fPARTICMS = 0;
$fIPVA = 0;
$fIPI = 0;
$fMJITR = 0;
$fMJIPTU = 0;
$fMJTIBI = 0;
$fMJISS = 0;
$fMJDA = 0;
$fMJDAIPTU = 0;
$fMJDAITBI = 0;
$fMJDAISS = 0;
$fMJDAITR = 0;

$fRJDAIPTU  = 0;
$fRJDAITBI  = 0;
$fRJDAISS = 0;
$fMJMIPTU = 0;
$fDAIPTU = 0;
$fMJMDAIPTU = 0;
$fMIPTU = 0;
$fJMIPTU = 0;
$fMDAIPTU = 0;
$fJMDAIPTU = 0;

$fMJMITBI = 0;
$fDAITBI = 0;
$fMJDAITBI = 0;
$fMITBI = 0;
$fJMITBI = 0;
$fMDAITBI = 0;
$fJMDAITBI = 0;

$fMJMISS = 0;
$fDAISS = 0;
$fMJMDAISS = 0;
$fMISS = 0;
$fJMISS = 0;
$fMDAISS = 0;
$fJMDAISS = 0;

$fFMISS = 0;
$fFMMJMISS = 0;
$fFMDAISS = 0;
$fFMMJMDAISS = 0;
$fFMMISS = 0;
$fFMJMISS = 0;
$fFMMDAISS = 0;
$fFMJMDAISS = 0;

$fMJMITR = 0;
$fDAITR = 0;
$fMJMDAITR = 0;
$fMITR = 0;
$fJMITR = 0;
$fMDAITR = 0;
$fJMDAITR = 0;
$fISP = 0;
$fISO = 0;

if ($anousu >=2022){
    $aReceitasImpostosIPTU = array(
        array('(-) Dedu��es da Receita do IPTU', 'text', '49%111250%',''),
    );
    $aReceitasImpostosITBI = array(
        array('(-) Dedu��es da Receita do ITBI', 'text', '49%1112530%',''),
    );
    $aReceitasImpostosISS = array(
        array('(-) Dedu��es da Receita do ISS', 'text', '49%111451%',''),
    );
    $aReceitasImpostosIRRF = array(
        array('(-) Dedu��es da Receita do IRRF', 'text', '49%111303%',''),
    );
    $aReceitasImpostosITR = array(
        array('(-) Dedu��es da Receita do ITR ', 'text', '49%1112011%',''),
    );
}
if($anousu>=2022){
    foreach ($aReceitas as $Receitas) {
        //IPTU
        if (strstr($Receitas->o57_fonte, '41112500100000')) $fIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '41112500200000')) $fMJMIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '41112500300000')) $fDAIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '41112500400000')) $fMJMDAIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '41112500500000')) $fMIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '41112500600000')) $fJMIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '41112500700000')) $fMDAIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '41112500800000')) $fJMDAIPTU += $Receitas->saldo_arrecadado;
        //ITBI
        if (strstr($Receitas->o57_fonte, '411125301000000')) $fITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411125302000000')) $fMJMITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411125303000000')) $fDAITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411125304000000')) $fMJDAITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411125305000000')) $fMITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411125306000000')) $fJMITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411125307000000')) $fMDAITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411125308000000')) $fJMDAITBI += $Receitas->saldo_arrecadado;
        //ISS
        if (strstr($Receitas->o57_fonte, '411145111000000')) $fISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145112000000')) $fMJMISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145113000000')) $fDAISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145114000000')) $fMJMDAISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145115000000')) $fMISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145116000000')) $fJMISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145117000000')) $fMDAISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145118000000')) $fJMDAISS += $Receitas->saldo_arrecadado;

        if (strstr($Receitas->o57_fonte, '411145121000000')) $fFMISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145122000000')) $fFMMJMISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145123000000')) $fFMDAISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145124000000')) $fFMMJMDAISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145125000000')) $fFMMISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145126000000')) $fFMJMISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145127000000')) $fFMMDAISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411145128000000')) $fFMJMDAISS += $Receitas->saldo_arrecadado;
        // IRRF
        if (strstr($Receitas->o57_fonte, '411130311000000')) $fIRRF += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411130341000000')) $fIRRFO += $Receitas->saldo_arrecadado;
        // ITR
        if (strstr($Receitas->o57_fonte, '411120111000000')) $fITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411120112000000')) $fMJMITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411120113000000')) $fDAITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411120114000000')) $fMJMDAITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411120115000000')) $fMITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411120116000000')) $fJMITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411120117000000')) $fMDAITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411120118000000')) $fJMDAITR += $Receitas->saldo_arrecadado;
        // FPM
        if (strstr($Receitas->o57_fonte, '417115111000000')) $fFPM += $Receitas->saldo_arrecadado;
        // ISP
        if (strstr($Receitas->o57_fonte, '417115201000000')) $fISP += $Receitas->saldo_arrecadado;
         // ISO
        if (strstr($Receitas->o57_fonte, '411711550100000')) $fISO += $Receitas->saldo_arrecadado;
        // ICMS
        if (strstr($Receitas->o57_fonte, '417195101000000')) $fPARTICMS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '417215001000000')) $fICMS += $Receitas->saldo_arrecadado;
        //IPVA
        if (strstr($Receitas->o57_fonte, '417215101000000')) $fIPVA += $Receitas->saldo_arrecadado;
        // IPI
        if (strstr($Receitas->o57_fonte, '417215201000000')) $fIPI += $Receitas->saldo_arrecadado;

    }
}
else{
    foreach ($aReceitas as $Receitas) {

        if (strstr($Receitas->o57_fonte, '411120111000000')) $fIPTR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180111000000')) $fIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411130311000000')) $fIRRF += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411130341000000')) $fIRRFO += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180141000000')) $fITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180231000000')) $fISS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '417180121000000')) $fFPM += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '417180151000000')) $fITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '417215001000000')) $fICMS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '417280111000000')) $fPARTICMS += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '417280121000000')) $fIPVA += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '417280131000000')) $fIPI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411120112000000')) $fMJITR += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180112000000')) $fMJIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180142000000')) $fMJTIBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180232000000')) $fMJISS += $Receitas->saldo_arrecadado;

        if (strstr($Receitas->o57_fonte, '411180114000000')) $fMJDAIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180144000000')) $fMJDAITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180234000000')) $fMJDAISS += $Receitas->saldo_arrecadado;

        if (strstr($Receitas->o57_fonte, '411180113000000')) $fRJDAIPTU += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180143000000')) $fRJDAITBI += $Receitas->saldo_arrecadado;
        if (strstr($Receitas->o57_fonte, '411180233000000')) $fRJDAISS += $Receitas->saldo_arrecadado;
    }
}
db_query("drop table if exists work_receita");
criarWorkReceita($sWhereReceita, array($anousu), $dtini, $dtfim);

/**
 * mPDF
 * @param string $mode              | padr�o: BLANK
 * @param mixed $format             | padr�o: A4
 * @param float $default_font_size  | padr�o: 0
 * @param string $default_font      | padr�o: ''
 * @param float $margin_left        | padr�o: 15
 * @param float $margin_right       | padr�o: 15
 * @param float $margin_top         | padr�o: 16
 * @param float $margin_bottom      | padr�o: 16
 * @param float $margin_header      | padr�o: 9
 * @param float $margin_footer      | padr�o: 9
 *
 * Nenhum dos par�metros � obrigat�rio
 */

$agrupa_estrutural = false;
$encerramento = false;
$where = " c61_instit in ({$instits})";
$where .= " and (c61_codigo = '102' or c61_codigo = '1102' or c61_codigo = '2102' or c61_codigo = '202' or c61_codigo = '15000002' or c61_codigo = '25000002') ";

$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"), ($DBtxt21_ano . '-' . $DBtxt21_mes . '-' . $DBtxt21_dia), $dtfim, false, $where);


$total_anterior    = 0;
$total_debitos  = 0;
$total_creditos = 0;
$total_final    = 0;
$iAjustePcasp   = 0;

if (USE_PCASP) {
    $iAjustePcasp = 8;
}

for ($x = 0; $x < pg_numrows($result); $x++) {
    db_fieldsmemory($result, $x);

    if (($tipo == "S") && ($c61_reduz != 0)) {
        continue;
    }

    if (USE_PCASP) {
    } else {
        if (substr($estrutural, 0, 1) == '3') {
            if (substr($estrutural, 2) + 0 > 0)
                continue;
        }
        if (substr($estrutural, 0, 1) == '4') {
            if (substr($estrutural, 2) + 0 > 0)
                continue;
        }
    }

    if (($movimento == "S") && (($saldo_anterior + $saldo_anterior_debito + $saldo_anterior_credito) == 0)) {
        continue;
    }



    if (substr($estrutural, 1, 14)      == '00000000000000') {

        if ($sinal_anterior == "C")
            $total_anterior -= $saldo_anterior;
        else
            $total_anterior += $saldo_anterior;

        if ($sinal_final == "C")
            $total_final -= $saldo_final;
        else
            $total_final += $saldo_final;

        $total_debitos  += $saldo_anterior_debito;
        $total_creditos += $saldo_anterior_credito;
    }

    $resconta = db_query("select conplanoconta.*
 from conplanoconta
 where c63_codcon = $c61_codcon and
 c63_anousu = " . db_getsession("DB_anousu"));
    if (pg_numrows($resconta) > 0)
        db_fieldsmemory($resconta, 0);

    if ($c61_reduz != 0) {

        $sSis = "";
        if (USE_PCASP) {
            $sSis = $sis;
        } else {

            $resconta = db_query("select c52_descrred
    from conplano
    inner join consistema on c52_codsis = c60_codsis
    where c60_anousu=$anousu and c60_estrut = '$estrutural'");
            db_fieldsmemory($resconta, 0);
            $sSis = $c52_descrred;
        }
    }



    if ($c61_reduz != 0) {

        if ($sinal_final == "C") {
            $saldo_final = $saldo_final * -1;
        }
    }

    if ($c61_reduz != 0) {

        if ($sinal_anterior == "D") {
            $saldo_anterior = $saldo_anterior * -1;
        }

        if ($sinal_final == "D") {
            $saldo_final = $saldo_final * -1;
        }
    }
}
$total_final = $total_anterior + $total_debitos - $total_creditos;

$sInst = '';
foreach ($aInstits as $inst) {
    $sInst .= 'instit_' . $inst . '-';
}
$filtra_despesa = $sInst . "recurso_102-recurso_1102";
$clselorcdotacao->setDados($filtra_despesa);
$sql_filtro = $clselorcdotacao->getDados(false);

function retorna_desdob($elemento, $e64_codele, $clorcelemento)
{
    return pg_query($clorcelemento->sql_query_file(null, null, "o56_elemento as estrutural,o56_descr as descr", null, "o56_codele = $e64_codele and o56_elemento like '$elemento%'"));
}


$resultinst = pg_exec("select codigo,nomeinstabrev from db_config where codigo in (" . $instits . ") ");


$sele_work = ' e60_instit in (' . $instits . ') ';
$sele_work1 = ''; //tipo de recurso
$anoatual = db_getsession("DB_anousu");
$tipofiltro = "�rg�o";
$commovfiltro = "Todos";

$sOpImpressao = 'Sint�tico';

$sql_order = " order by o58_orgao,e60_anousu,e60_codemp::integer";
$sql_where_externo .= "  ";
$sql_where_externo .= ' and e60_anousu < ' . db_getsession("DB_anousu");
$sql_where_externo .= " and " . $sql_filtro;

$sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), $sele_work, $dtini, $dtfim, $sele_work1, $sql_where_externo, "$sql_order ");

$res = $clempresto->sql_record($sqlempresto);

$rows = $clempresto->numrows;

//variaveis agrupamentos
$vnumcgm = null;
$vorgao = null;
$vunidade = null;
$vfuncao = null;
$vsubfuncao = null;
$vprojativ = null;
$velemento = null;
$vdesdobramento = null;
$vrecurso = null;
$vprograma = null;
$vtiporesto = null;


//subtotal
$vorgaosub = 0;
$vunidadesub = 0;
$vfuncaosub = 0;
$vsubfuncaosub = 0;
$vprogramasub = 0;
$vprojativsub = 0;
$velementosub = 0;
$vrecursosub = 0;
$vtiporestosub = 0;
$vnumcgmsub = 0;
$vdesdobramentosub = 0;

$subtotal_rp_n_proc = 0;
$subtotal_rp_proc = 0;
$subtotal_anula_rp_n_proc = 0;
$subtotal_anula_rp_proc = 0;
$subtotal_mov_liquida = 0;
$subtotal_mov_pagmento = 0;
$subtotal_mov_pagnproc = 0;
$subtotal_aliquidar_finais = 0;
$subtotal_liquidados_finais = 0;
$subtotal_geral_finais = 0;


//total
$total_rp_n_proc = 0;
$total_rp_proc = 0;
$total_rp_nproc = 0;

$total_anula_rp_n_proc = 0;
$total_anula_rp_proc = 0;


$total_mov_liquida = 0;
$total_mov_pagmento = 0;
$total_mov_pagnproc = 0;

$total_aliquidar_finais = 0;
$total_liquidados_finais = 0;
$total_geral_finais = 0;
//


$verifica = true;
$estrutura = "";
$projativ = "";
$o55anousu = "";
$vprojativ = "";

if ($clempresto->numrows != 0) {

    for ($x = 0; $x < $rows; $x++) {
        db_fieldsmemory($res, $x);
        $total_rp_proc += ($e91_vlrliq - $e91_vlrpag);
        $total_rp_nproc += round($vlrpagnproc, 2);
        $total_mov_pagmento += ($vlrpag + $vlrpagnproc);
    }
}

/*
saldo de restos < saldo inicial das contas banc�rias -> 0
pago < saldo inicial das contas banc�rias -> 0
se nao : pago - saldo inicial das contas banc�rias
round(((e91_vlremp - e91_vlranu) - e91_vlrliq),2) total_rp_nproc
*/
if (($total_rp_proc + $total_rp_nproc) < $total_anterior || $total_mov_pagmento < $total_anterior) {
    $iRestosAPagar = db_formatar(0, "f");
} else {
    $iRestosAPagar = $total_mov_pagmento - $total_anterior;
}
//

try {
$mPDF = new Mpdf([
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

$header = <<<HEADER
<header>
  <table style="width:100%;text-align:center;font-family:sans-serif;border-bottom:1px solid #000;padding-bottom:6px;">
    <tr>
      <th>{$oInstit->getDescricao()}</th>
    </tr>
    <tr>
      <th>ANEXO IV</th>
    </tr>
    <tr>
      <td style="text-align:right;font-size:10px;font-style:oblique;">Per�odo: De {$DBtxt21} a {$DBtxt22}</td>
    </tr>
  </table>
</header>
HEADER;

$footer = <<<FOOTER
<div style='border-top:1px solid #000;width:100%;text-align:right;font-family:sans-serif;font-size:10px;height:10px;'>
  {PAGENO}
</div>
FOOTER;


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
        }

        .ritz .waffle .s0 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 14pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s1 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 12pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s2 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-style: italic;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s3 {
            background-color: #ffffff;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 10pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s4 {
            background-color: #ffffff;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s5 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 10pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s6 {
            background-color: #ffffff;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s7 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 10pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s8 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s9 {
            background-color: #ffffff;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s10 {
            background-color: #d8d8d8;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 10pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s11 {
            background-color: #d8d8d8;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s12 {
            background-color: #d8d8d8;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s13 {
            background-color: #d8d8d8;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s14 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s15 {
            background-color: #ffffff;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s16 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 10pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s17 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s18 {
            background-color: #ffffff;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s19 {
            background-color: #ffffff;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s20 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s21 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s22 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Arial';
            font-size: 10pt;
            padding: 2px 3px 2px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s23 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s24 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s25 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 2px 3px 2px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }
    </style>

</head>

<body>

    <div class="ritz grid-container" dir="ltr">
        <table class="waffle" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th id="0C0" style="width:40px" class="column-headers-background">&nbsp;</th>
                    <th id="0C1" style="width:100px" class="column-headers-background">&nbsp;</th>
                    <th id="0C2" style="width:100px" class="column-headers-background">&nbsp;</th>
                    <th id="0C3" style="width:100px" class="column-headers-background">&nbsp;</th>
                    <th id="0C4" style="width:100px" class="column-headers-background">&nbsp;</th>
                    <th id="0C5" style="width:100px" class="column-headers-background">&nbsp;</th>
                    <th id="0C6" style="width:100px" class="column-headers-background">&nbsp;</th>
                    <th id="0C7" style="width:100px" class="column-headers-background">&nbsp;</th>
                    <th id="0C8" style="width:100px" class="column-headers-background">&nbsp;</th>
                    <th id="0C9" style="width:100px" class="column-headers-background">&nbsp;</th>
                </tr>
            </thead>
            <?php if (db_getsession('DB_anousu') >= 2022) : ?>
                <tbody>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft bdtop" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s4 bdleft" colspan="10">Demonstrativo das Receitas e Despesas com A��es e Servi�os P�blicos de Sa�de</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s4 bdleft" colspan="10">(Art. 198, �2�, III da CF, LC 141/2012 e IN 05/2012)</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s5 bdleft" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">1 - RECEITA DE IMPOSTOS</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">1.1 - Receita resultante do Imposto sobre a Propriedade Predial e Territorial Urbana (IPTU) </td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11125001</td>
                        <td class="s12" colspan="6"> Imposto sobre a Propriedade Predial e Territorial Urbana -  Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11125002</td>
                        <td class="s6" colspan="6"> Imposto Sobre a Propriedade Predial e Territorial Urbana -  Multas e Juros de Mora </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJMIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11125003</td>
                        <td class="s12" colspan="6"> Imposto sobre a Propriedade Predial e Territorial Urbana -   D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fDAIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11125004</td>
                        <td class="s6" colspan="6"> Imposto Sobre a Propriedade Predial e Territorial Urbana -   Multas e Juros de Mora da D�vida Ativa </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJMDAIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11125005</td>
                        <td class="s12" colspan="6"> Imposto sobre a Propriedade Predial e Territorial Urbana -    Multas</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11125006</td>
                        <td class="s6" colspan="6"> Imposto Sobre a Propriedade Predial e Territorial Urbana -    Juros de Mora </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fJMIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11125007</td>
                        <td class="s12" colspan="6"> Imposto sobre a Propriedade Predial e Territorial Urbana -    Multas da D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMDAIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11125008</td>
                        <td class="s6" colspan="6"> Imposto Sobre a Propriedade Predial e Territorial Urbana -    Juros de Mora da D�vida Ativa</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fJMDAIPTU, "f");
                            ?>
                        </td>
                    </tr>
                   <?php
                $nTotalReceitaImpostos = 0;
                foreach ($aReceitasImpostosIPTU as $receita) {
                    echo "<tr style='height:20px;'>";
                    echo "<td class='s6 bdleft' colspan='8'>{$receita[0]}</td>";
                    echo "<td class='s9' colspan='2'>";
                    $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%1112500%'");
                    $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;

                    $nTotalReceitaDeducao = 0;
                    if($receita[3] != ''){
                        $aReceitasDeducao = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%1112500%'");
                        $nTotalReceitaDeducao = count($aReceitasDeducao) > 0 ? $aReceitasDeducao[0]->saldo_arrecadado_acumulado : 0;
                    }
                    $nTotalReceita = $nReceita + $nTotalReceitaDeducao;
                    $receita[1] == 'subtitle' ? $nTotalReceitaImpostos += $nTotalReceita : $nTotalReceitaImpostos += 0;
                    if ($receita[1] == 'title') {
                        echo "";
                    } else {
                        $totalIPTU = $nTotalReceita;
                        echo db_formatar(abs($nTotalReceita), "f");
                    }
                    echo "    </td>";
                    echo " </tr>";
                }
                ?>


                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2"></td>
                    </tr>

                    <!-- ITBI -->
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">1.2 - Receita resultante do Imposto sobre Transmiss�o Inter Vivos (ITBI)</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11125301</td>
                        <td class="s12" colspan="6"> Impostos sobre Transmiss�o Inter Vivos de Bens Im�veis e de Direitos Reais sobre Im�veis -  Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11125302</td>
                        <td class="s6" colspan="6"> Imposto sobre Transmiss�o Inter Vivos de Bens Im�veis e de Direitos Reais sobre Im�veis -  Multas e Juros de Mora </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJMITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11125303</td>
                        <td class="s12" colspan="6"> Impostos sobre Transmiss�o Inter Vivos de Bens Im�veis e de Direitos Reais sobre Im�veis -   D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fDAITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11125304</td>
                        <td class="s6" colspan="6"> Imposto sobre Transmiss�o Inter Vivos de Bens Im�veis e de Direitos Reais sobre Im�veis -   Multas e Juros de Mora da D�vida Ativa </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJDAITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11125305</td>
                        <td class="s12" colspan="6"> Impostos sobre Transmiss�o Inter Vivos de Bens Im�veis e de Direitos Reais sobre Im�veis -   Multas</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11125306</td>
                        <td class="s6" colspan="6"> Imposto sobre Transmiss�o Inter Vivos de Bens Im�veis e de Direitos Reais sobre Im�veis -   Juros de Mora </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fJMITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11125307</td>
                        <td class="s12" colspan="6"> Impostos sobre Transmiss�o Inter Vivos de Bens Im�veis e de Direitos Reais sobre Im�veis -   Multas da D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMDAITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11125308</td>
                        <td class="s6" colspan="6"> Imposto sobre Transmiss�o Inter Vivos de Bens Im�veis e de Direitos Reais sobre Im�veis -  Juros de Mora da D�vida Ativa </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fJMDAITBI, "f");
                            ?>
                        </td>
                    </tr>
                <?php
                $nTotalReceitaImpostos = 0;
                foreach ($aReceitasImpostosITBI as $receita) {
                    echo "<tr style='height:20px;'>";
                    echo "<td class='s6 bdleft' colspan='8'>{$receita[0]}</td>";
                    echo "    <td class='s9' colspan='2'>";
                    $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%1112530%'");
                    $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;

                    $nTotalReceitaDeducao = 0;
                    if($receita[3] != ''){
                        $aReceitasDeducao = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%1112530%'");
                        $nTotalReceitaDeducao = count($aReceitasDeducao) > 0 ? $aReceitasDeducao[0]->saldo_arrecadado_acumulado : 0;
                    }
                    $nTotalReceita = $nReceita + $nTotalReceitaDeducao;
                    $receita[1] == 'subtitle' ? $nTotalReceitaImpostos += $nTotalReceita : $nTotalReceitaImpostos += 0;
                    if ($receita[1] == 'title') {
                        echo "";
                    } else {
                        $totalIBI=$nTotalReceita;
                        echo db_formatar(abs($nTotalReceita), "f");
                    }
                    echo "    </td>";
                    echo " </tr>";
                }
                ?>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2"></td>
                    </tr>

                <!-- ISS -->
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">1.3 -  Receita resultante do Imposto sobre Servi�os de Qualquer Natureza (ISS) </td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11145111</td>
                        <td class="s12" colspan="6">  Imposto sobre Servi�os de Qualquer Natureza  -     Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11145112</td>
                        <td class="s6" colspan="6">  Imposto sobre Servi�os de Qualquer Natureza  -     Multas e Juros de Mora</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJMISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11145113</td>
                        <td class="s12" colspan="6">  Imposto sobre Servi�os de Qualquer Natureza  -     D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11145114</td>
                        <td class="s6" colspan="6">  Imposto sobraassdase Servi�os de Qualquer Natureza  -     Multas e Juros de Mora da D�vida Ativa</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJMDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11145115</td>
                        <td class="s12" colspan="6">  Imposto sobre Servi�os de Qualquer Natureza  -    Multas </td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11145116</td>
                        <td class="s6" colspan="6">  Imposto sobre Servi�os de Qualquer Natureza  -    Juros de Mora </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fJMISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11145117</td>
                        <td class="s12" colspan="6">  Imposto sobre Servi�os de Qualquer Natureza  -    Multas da D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11145118</td>
                        <td class="s6" colspan="6">  Imposto sobre Servi�os de Qualquer Natureza  -    Juros de Mora da D�vida Ativa</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fJMDAISS, "f");
                            ?>
                        </td>
                    </tr>

                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11145121</td>
                        <td class="s12" colspan="6">  Adicional ISS -  Fundo Municipal de Combate � Pobreza -  Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fFMISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11145122</td>
                        <td class="s6" colspan="6"> Adicional ISS -  Fundo Municipal de Combate � Pobreza -  Multas e Juros de Mora</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fFMMJMISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11145123</td>
                        <td class="s12" colspan="6"> Adicional ISS -  Fundo Municipal de Combate � Pobreza -  D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fFMDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11145124</td>
                        <td class="s6" colspan="6"> Adicional ISS -  Fundo Municipal de Combate � Pobreza -  Multas e Juros de Mora da D�vida Ativa</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fFMMJMDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11145125</td>
                        <td class="s12" colspan="6"> Adicional ISS -  Fundo Municipal de Combate � Pobreza -  Multas </td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fFMMISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11145126</td>
                        <td class="s6" colspan="6"> Adicional ISS -  Fundo Municipal de Combate � Pobreza -  Juros de Mora </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fFMJMISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11145127</td>
                        <td class="s12" colspan="6"> Adicional ISS -  Fundo Municipal de Combate � Pobreza -  Multas da D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fFMMDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11145128</td>
                        <td class="s6" colspan="6"> Adicional ISS -  Fundo Municipal de Combate � Pobreza -  Juros de Mora da D�vida Ativa</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fFMJMDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <?php
                $nTotalReceitaImpostos = 0;
                foreach ($aReceitasImpostosISS as $receita) {
                    echo "<tr style='height:20px;'>";
                    echo "<td class='s6 bdleft' colspan='8'>{$receita[0]}</td>";
                    echo "    <td class='s9' colspan='2'>";
                    $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%111451%'");
                    $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;

                    $nTotalReceitaDeducao = 0;
                    if($receita[3] != ''){
                        $aReceitasDeducao = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%111451%'");
                        $nTotalReceitaDeducao = count($aReceitasDeducao) > 0 ? $aReceitasDeducao[0]->saldo_arrecadado_acumulado : 0;
                    }
                    $nTotalReceita = $nReceita + $nTotalReceitaDeducao;
                    $receita[1] == 'subtitle' ? $nTotalReceitaImpostos += $nTotalReceita : $nTotalReceitaImpostos += 0;
                    if ($receita[1] == 'title') {
                        echo "";
                    } else {
                        $totalISS = $nTotalReceita;
                        echo db_formatar(abs($nTotalReceita), "f");
                    }
                    echo "    </td>";
                    echo " </tr>";
                }
                ?>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <!-- IRRF -->
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">1.4 -   Receita resultante do Imposto de Renda Retido na Fonte (IRRF) </td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11130311</td>
                        <td class="s12" colspan="6">   Imposto sobre a Renda  -   Retido na Fonte - Trabalho  -   Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fIRRF, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11130341</td>
                        <td class="s6" colspan="6">  Imposto sobre a Renda  -   Retido na Fonte - Trabalho  -    Outros Rendimentos     Principal</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fIRRFO, "f");
                            ?>
                        </td>
                    </tr>
                    <?php
                $nTotalReceitaImpostos = 0;
                foreach ($aReceitasImpostosIRRF as $receita) {
                    echo "<tr style='height:20px;'>";
                    echo "<td class='s6 bdleft' colspan='8'>{$receita[0]}</td>";
                    echo "    <td class='s9' colspan='2'>";
                    $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%111303%'");
                    $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;

                    $nTotalReceitaDeducao = 0;
                    if($receita[3] != ''){
                        $aReceitasDeducao = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%111303%'");
                        $nTotalReceitaDeducao = count($aReceitasDeducao) > 0 ? $aReceitasDeducao[0]->saldo_arrecadado_acumulado : 0;
                    }
                    $nTotalReceita = $nReceita + $nTotalReceitaDeducao;
                    $receita[1] == 'subtitle' ? $nTotalReceitaImpostos += $nTotalReceita : $nTotalReceitaImpostos += 0;
                    if ($receita[1] == 'title') {
                        echo "";
                    } else {
                        $totalIRRF = $nTotalReceita;
                        echo db_formatar(abs($nTotalReceita), "f");
                    }
                    echo "    </td>";
                    echo " </tr>";
                }
                ?>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                     <!-- ITR -->
                     <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">1.5 -   Receita resultante do Imposto Territorial Rural (ITR) (CF, ART 153, �4�, inciso III)</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11120111</td>
                        <td class="s12" colspan="6">  Imposto sobre a Propriedade Territorial Rural  -   Munic�pios Conveniados  -  Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120112</td>
                        <td class="s6" colspan="6">  Imposto sobre a Propriedade Territorial Rural  -   Munic�pios Conveniados  -  Multas e Juros de Mora </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJMITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11120113</td>
                        <td class="s12" colspan="6">  Imposto sobre a Propriedade Territorial Rural  -   Munic�pios Conveniados  -   D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fDAITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120114</td>
                        <td class="s6" colspan="6">  Imposto sobre a Propriedade Territorial Rural  -   Munic�pios Conveniados  -   Multas e Juros de Mora da D�vida Ativa </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJMDAITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11120115</td>
                        <td class="s12" colspan="6">  Imposto sobre a Propriedade Territorial Rural  -   Munic�pios Conveniados  -   Multas</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120116</td>
                        <td class="s6" colspan="6">  Imposto sobre a Propriedade Territorial Rural  -   Munic�pios Conveniados  -   Juros de Mora </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fJMITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11120117</td>
                        <td class="s12" colspan="6">  Imposto sobre a Propriedade Territorial Rural  -   Munic�pios Conveniados  -   Multas da D�vida Ativa</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMDAITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120118</td>
                        <td class="s6" colspan="6">  Imposto sobre a Propriedade Territorial Rural  -   Munic�pios Conveniados  -  Juros de Mora da D�vida Ativa </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fJMDAITR, "f");
                            ?>
                        </td>
                    </tr>
                    <?php
                $nTotalReceitaImpostos = 0;
                foreach ($aReceitasImpostosITR as $receita) {
                    echo "<tr style='height:20px;'>";
                    echo "<td class='s6 bdleft' colspan='8'>{$receita[0]}</td>";
                    echo "    <td class='s9' colspan='2'>";
                    $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%1112011%'");
                    $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;

                    $nTotalReceitaDeducao = 0;
                    if($receita[3] != ''){
                        $aReceitasDeducao = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '49%1112011%'");
                        $nTotalReceitaDeducao = count($aReceitasDeducao) > 0 ? $aReceitasDeducao[0]->saldo_arrecadado_acumulado : 0;
                    }
                    $nTotalReceita = $nReceita + $nTotalReceitaDeducao;
                    $receita[1] == 'subtitle' ? $nTotalReceitaImpostos += $nTotalReceita : $nTotalReceitaImpostos += 0;
                    if ($receita[1] == 'title') {
                        echo "";
                    } else {
                        $totalITR = $nTotalReceita;
                        echo db_formatar(abs($nTotalReceita), "f");
                    }
                    echo "    </td>";
                    echo " </tr>";
                }
                ?>
                     <tr style='height:20px;'>
                     <td class="s20 bdleft" colspan="8"><b>Subtotal</b></td>
                        <td class="s21" colspan="2"> <?php
                                                    $fSubTotalImposto = array_sum(array($fITR, $fMJMITR, $fDAITR, $fMJMDAITR, $fMITR, $fJMITR,$fMDAITR,$fJMDAITR,$fIRRF,$fIRRFO,$fISS, $fMJMISS, $fDAISS, $fMJMDAISS, $fMISS, $fJMISS,$fMDAISS,$fJMDAISS,$fFMISS,$fFMMJMISS,$fFMDAISS,$fFMMJMDAISS,$fFMMISS,$fFMJMISS,$fFMMDAISS,$fFMJMDAISS,$fITBI, $fMJMITBI, $fDAITBI, $fMJDAITBI, $fMITB, $fJMITBI,$fMDAITBI,$fJMDAITBI,$fIPTU, $fMJMIPTU, $fDAIPTU, $fMJMDAIPTU, $fMIPTU, $fJMIPTU,$fMDAIPTU,$fJMDAIPTU));
                                                    echo db_formatar($fSubTotalImposto-(abs($totalIPTU)+abs($totalITBI)+abs($totalISS)+abs($totalIRRF)+abs($totalITR)), "f");
                                                    $fSubTotalImposto = $fSubTotalImposto-(abs($totalIPTU)+abs($totalITBI)+abs($totalISS)+abs($totalIRRF)+abs($totalITR));
                                                   ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                     <!-- Cota -->
                     <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">2 - RECEITA DE TRANSFER�NCIAS CONSTITUCIONAIS E LEGAIS</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17115111</td>
                        <td class="s12" colspan="6">  Cota  -  Parte do Fundo de Participa��o dos Munic�pios  -	 Cota Mensal  -	 Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fFPM, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17115201</td>
                        <td class="s6" colspan="6">  Cota  -  Parte do Imposto Sobre a Propriedade Territorial Rural  -  Principal </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fISP, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17115501</td>
                        <td class="s12" colspan="6">  Cota  -   Parte do Imposto Sobre Opera��es de Cr�dito, C�mbio e Seguro, ou Relativas a T�tulos ou Valores Mobili�rios  -	 Comercializa��o do Ouro  -	 Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fISO, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17195101</td>
                        <td class="s6" colspan="6">   Transfer�ncia Financeira do ICMS 	 Desonera��o  -	 LC N� 87/96  -	 Principal</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fPARTICMS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17215001</td>
                        <td class="s12" colspan="6">  Cota  -   Parte do ICMS  -  Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fICMS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17215101</td>
                        <td class="s6" colspan="6">  Cota  -   Parte do IPVA  -	 Principal</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fIPVA, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17215201</td>
                        <td class="s12" colspan="6">  Cota  -   Parte do IPI  -	 Munic�pios  -	 Principal</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fIPI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s20 bdleft" colspan="8"><b>Subtotal</b></td>
                        <td class="s21" colspan="2"> <?php
                                                    $fSubTotalOutrasCorrentes = array_sum(array($fIPI,$fIPVA,$fICMS,$fPARTICMS,$fISO,$fISP,$fFPM));
                                                    echo db_formatar($fSubTotalOutrasCorrentes, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2"></td>
                    </tr>

                    <tr style='height:20px;'>
                        <td class="s6 bdleft bdbottom" colspan="8">&nbsp;</td>
                        <td class="s3 bdbottom" colspan="2"></td>
                    </tr>

                    <tr style='height:20px;'>
                        <td class="s20 bdleft" colspan="8">Total das Receitas (A)</td>
                        <td class="s21" colspan="2"><?php $fTotalReceitas = ($fSubTotalImposto + $fSubTotalOutrasCorrentes) - (abs($fTotalDeducoes));
                                                    echo db_formatar($fTotalReceitas, "f"); ?> </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr style='height:20px;'>
                        <td class="" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s24" colspan="10">Resumo da Aplica��o das A��es e Servi�os P�blicos de Sa�de</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s25" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s23 bdleft" colspan="7">Exerc�cio Atual</td>
                        <td class="s23" colspan="2">Valor</td>
                        <td class="s23" colspan="2">Percentual</td>
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
                     //Linha 1,2,3,4
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
                     @pg_query("drop table work_pl");
                     //Linha 5 e 6
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
                     $valorLinha6  = $valorLinha5 + $fTotalGeral;
                     //Linha 7
                     $totalLinha7   = 0;
                     $totalLinha7_1 = 0;
                     $totalLinha7_2 = 0;
                     $totalLinha9   = 0;
                     $totalLinha9   = $valorLinha6;
                     $totalLinha7_1 = 0;
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
                     $totalLinha7 = $totalLinha7_1 + $totalLinha7_2;

                     $totalLinha9 -= $totalLinha7;

                     // Linha 8
                     $totalLinha8   = 0;
                     $totalLinha8_1 = 0;
                     $totalLinha8_2 = 0;
                     $total_anterior = 0;
                     $estrutural     = 0;
                     $sFonte = ("'102','202','1102', '2102','15000002','25000002'");
                     $whereLinha8 = " c61_instit in ({$instits}) and c61_codigo in ($sFonte) ";
                     $resultLinha8 = db_planocontassaldo_matriz(db_getsession("DB_anousu"), $dtini, $dtfim, false, $whereLinha8,'',0);

                     for ($x = 0; $x < pg_numrows($resultLinha8); $x++) {
                         db_fieldsmemory($resultLinha8, $x);

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
                     //R$ 36.418.405,76
                     $totalLinha8_1 = $total_mov_pagmento + $total_mov_pagnproc - $nValorRecursoImposto;
                     $sFonte2 = ("'15020002','25020002'");
                     $where2  = " c61_instit in ({$instits}) and c61_codigo in ($sFonte2) ";
                     @pg_query("drop table work_pl");
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
                        <td class="s6 bdleft" colspan="7">B - Aplica��o Devida (art. 7� da LC n� 141/2012)</td>
                        <td class="s9" colspan="2"><?php echo db_formatar($fTotalReceitas * 0.15, "f"); ?></td>
                        <td class="s9" colspan="2">15,00%</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft " colspan="7">C - Valor da Aplica��o</td>
                        <td class="s9" colspan="2"><?php echo db_formatar($totalLinha9, "f"); ?></td>
                        <td class="s9" colspan="2"><?php echo db_formatar(($totalLinha9 * 100) / $fTotalReceitas, "f");?>%</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s20 bdleft " colspan="7">D - Diferen�a entre o Valor Aplicado e o Limite Constitucional (D = C - B)</td>
                        <td class="s21" colspan="2"><?php echo db_formatar($totalLinha9 - ($fTotalReceitas * 0.15), "f"); ?></td>
                        <td class="s21" colspan="2"><?php echo db_formatar((($totalLinha9 * 100) / $fTotalReceitas) - 15, "f"); ?>%</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s5 bdleft" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s23 bdleft" colspan="8">Res�duo de Exerc�cio Anterior</td>
                        <td class="s23" colspan="2">Valor</td>
                    </tr>
                    <?php
                           //Linha 1,2,3,4
                     $funcaoTotal = 0;
                     $fProgTotal  = 0;
                     $aSubTotal   = array();
                     $aSubFuncoes = array(122,128,271,272,273,301,302,303,304,305,306,511,512,843,844);
                     $sFuncao     = "'10','28'";
                     $aFonte      = array("'15000002','25000002','1102','102','15020002','25020002','2102','202'");
                     foreach ($aSubFuncoes as $iSubFuncao) {
                         $sDescrSubfunao = db_utils::fieldsMemory(db_query("select o53_descr from orcsubfuncao where o53_codtri = '{$iSubFuncao}'"), 0)->o53_descr;
                         if ($anousu > 2022 )
                             $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codigo in (".implode(",",$aFonte).") and o58_instit in ($instits) and substr(work_dotacao.o58_elemento,4,2) = '96' group by 1,2");
                         else
                             $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao in ({$sFuncao}) and o58_subfuncao in ({$iSubFuncao}) and o15_codtri in (".implode(",",$aFonte).") and o58_instit in ($instits) and substr(work_dotacao.o58_elemento,4,2) = '96' group by 1,2");
                         if (count($aDespesasProgramas) > 0) {
                           foreach ($aDespesasProgramas as $oDespesaPrograma) {
                             $funcaoTotal += $oDespesaPrograma->pago;
                             $fTotalGeral2 = $funcaoTotal;
                             if (!isset($aSubTotal[$iSubFuncao])) {
                               $aSubTotal[$iSubFuncao] = 0;
                           }
                           $aSubTotal[$iSubFuncao] += $oDespesaPrograma->pago;
                           }
                       }
                     }

                     $anoexercicio = db_getsession('DB_anousu');
                     $result = $cldadosexercicioanterior->sql_record($cldadosexercicioanterior->sql_query(null, "c235_valornaoaplicsaude",null," c235_anousu = {$anoexercicio} "));

                     if ($cldadosexercicioanterior->numrows != 0) {
                         db_fieldsmemory($result, 0);
                     }
                    ?>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">E - Valor n�o Aplicado de Exerc�cio Anterior</td>
                        <td class="s9" colspan="2"><?php echo db_formatar($c235_valornaoaplicsaude, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">F - Aplica��o no Exerc�cio Atual Referente ao Res�duo de Exerc�cio Anterior</td>
                        <td class="s9" colspan="2"><?php echo db_formatar($fTotalGeral2, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s20 bdleft " colspan="8">G - Diferen�a (F - E) </td>
                        <td class="s21" colspan="2"><?php echo db_formatar($fTotalGeral2 - $c235_valornaoaplicsaude, "f"); ?></td>
                    </tr>
                </tbody>
            <?php endif; ?>
            <?php if (db_getsession('DB_anousu') < 2018) : ?>
                <tbody>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft bdtop" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s4 bdleft" colspan="10">DEMONSTRATIVO DA APLICA��O EM A��ES E SERVI�OS P�BLICOS DE SA�DE</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s4 bdleft" colspan="10">(ART. 198, � 2.�, III, DA CF/88)</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s5 bdleft" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">01 - Receitas </td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">A - Impostos:</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120101</td>
                        <td class="s6" colspan="6">Imposto s/a Propriedade Territorial Rural - Munic.Conv.</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosIPTR = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '411120101%'");
                            $fIPTR = count($aDadosIPTR) > 0 ? $aDadosIPTR[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fIPTR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11120200</td>
                        <td class="s12" colspan="6">Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU</td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosIPTU = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4111202%'");
                            $fIPTU = count($aDadosIPTU) > 0 ? $aDadosIPTU[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120431</td>
                        <td class="s6" colspan="6">Imposto de Renda Retido nas Fontes - IRRF (Trab.Assalariado)</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosIRRF = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '411120431%'");
                            $fIRRF = count($aDadosIRRF) > 0 ? $aDadosIRRF[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fIRRF, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11120434</td>
                        <td class="s12" colspan="6">Imposto de Renda Retido nas Fontes - IRRF (Outros Rendimentos)</td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosIRRFO = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '411120434%'");
                            $fIRRFO = count($aDadosIRRFO) > 0 ? $aDadosIRRFO[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fIRRFO, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120800</td>
                        <td class="s6" colspan="6">Imposto sobre Transmiss�o Inter-Vivos de Bens Im�veis - ITBI</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosITBI = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4111208%'");
                            $fITBI = count($aDadosITBI) > 0 ? $aDadosITBI[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11130500</td>
                        <td class="s12" colspan="6">Imposto sobre Servi�o de Qualquer Natureza - ISS</td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosISS = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4111305%'");
                            $fISS = count($aDadosISS) > 0 ? $aDadosISS[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fISS, "f");
                            ?>
                        </td>
                    </tr>

                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">Subtotal</td>
                        <td class="s3" colspan="2"><?php $fSubTotalImposto = array_sum(array($fIPTR, $fIPTU, $fIRRF, $fIRRFO, $fITBI, $fISS));
                                                    echo db_formatar($fSubTotalImposto, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">B - Transfer�ncias Correntes:</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17210102</td>
                        <td class="s6" colspan="6">Cota-parte do FPM </td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosFPM = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '417210102%'");
                            $fFPM = count($aDadosFPM) > 0 ? $aDadosFPM[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fFPM, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17210105</td>
                        <td class="s12" colspan="6">Transfer�ncia do ITR</td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosITR = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '417210105%'");
                            $fITR = count($aDadosITR) > 0 ? $aDadosITR[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17213600</td>
                        <td class="s6" colspan="6">Transfer�ncia Financ. - Lei Comp.n. 87/96 - ICMS Exp.</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosICMS = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4172136%'");
                            $fICMS = count($aDadosICMS) > 0 ? $aDadosICMS[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fICMS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17220101</td>
                        <td class="s12" colspan="6">Participa��o no ICMS</td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosPARTICMS = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '417220101%'");
                            $fPARTICMS = count($aDadosPARTICMS) > 0 ? $aDadosPARTICMS[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fPARTICMS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17220102</td>
                        <td class="s6" colspan="6">Imposto sobre IPVA</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosIPVA = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '417220102%'");
                            $fIPVA = count($aDadosIPVA) > 0 ? $aDadosIPVA[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fIPVA, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17220104</td>
                        <td class="s12" colspan="6">Cota-parte do Imposto sobre Produtos Industrializados - IPI</td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosIPI = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '417220104%'");
                            $fIPI = count($aDadosIPI) > 0 ? $aDadosIPI[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fIPI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">Subtotal</td>
                        <td class="s3" colspan="2"><?php $fSubTotalCorrentes = array_sum(array($fFPM, $fFPM1DEZ, $fFPM1JUL, $fITR, $fICMS, $fPARTICMS, $fIPVA, $fIPI));
                                                    echo db_formatar($fSubTotalCorrentes, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2">

                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">C - Outras Receitas Correntes</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">19110801</td>
                        <td class="s6" colspan="6">Multas e Juros de Mora do ITR-Munic.Conv.</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosMJITR = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '419110801%'");
                            $fMJITR = count($aDadosMJITR) > 0 ? $aDadosMJITR[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">19113800</td>
                        <td class="s12" colspan="6">Multas e Juros de Mora do IPTU </td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosMJIPTU = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4191138%'");
                            $fMJIPTU = count($aDadosMJITR) > 0 ? $aDadosMJIPTU[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">19113900</td>
                        <td class="s6" colspan="6">Multas e Juros de Mora do ITBI</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosMJITBI = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4191139%'");
                            $fMJTIBI = count($aDadosMJITBI) > 0 ? $aDadosMJITBI[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJTIBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">19114000</td>
                        <td class="s12" colspan="6"> Multas e Juros de Mora do ISS </td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosMJISS = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4191140%'");
                            $fMJISS = count($aDadosMJISS) > 0 ? $aDadosMJISS[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">19130800</td>
                        <td class="s6" colspan="6">Multas e Juros de Mora da D�vida Ativa do ITR-Munic.Conv.</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosMJDA = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4191308%'");
                            $fMJDA = count($aDadosMJDA) > 0 ? $aDadosMJDA[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJDA, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">19131100</td>
                        <td class="s12" colspan="6">Multas e Juros de Mora da D�vida Ativa do IPTU </td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosMJDAIPTU = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4191311%'");
                            $fMJDAIPTU = count($aDadosMJDAIPTU) > 0 ? $aDadosMJDAIPTU[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJDAIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">19131200</td>
                        <td class="s6" colspan="6">Multas e Juros de Mora da D�vida Ativa do ITBI </td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosMJDAITBI = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4191312%'");
                            $fMJDAITBI = count($aDadosMJDAITBI) > 0 ? $aDadosMJDAITBI[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJDAITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">19131300</td>
                        <td class="s12" colspan="6">Multas e Juros de Mora da D�vida Ativa do ISS </td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosMJDAISS = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4191313%'");
                            $fMJDAISS = count($aDadosMJDAISS) > 0 ? $aDadosMJDAISS[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">19310400</td>
                        <td class="s6" colspan="6">Receita da D�vida Ativa do ITR - Munic.Conveniado</td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosRDAITR = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4193104%'");
                            $fMJDAITR = count($aDadosRDAITR) > 0 ? $aDadosRDAITR[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fMJDAITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">19311100</td>
                        <td class="s12" colspan="6">Receita da D�vida Ativa do IPTU </td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosRDAIPTU = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4193111%'");
                            $fRJDAIPTU = count($aDadosRDAIPTU) > 0 ? $aDadosRDAIPTU[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fRJDAIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">19311200</td>
                        <td class="s6" colspan="6">Receita da D�vida Ativa do ITBI </td>
                        <td class="s9" colspan="2">
                            <?php
                            $aDadosRDAITBI = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4193112%'");
                            $fRJDAITBI = count($aDadosRDAITBI) > 0 ? $aDadosRDAITBI[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fRJDAITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">19311300</td>
                        <td class="s12" colspan="6">Receita da D�vida Ativa do ISS </td>
                        <td class="s13" colspan="2">
                            <?php
                            $aDadosRDAISS = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '4193113%'");
                            $fRJDAISS = count($aDadosRDAISS) > 0 ? $aDadosRDAISS[0]->saldo_arrecadado_acumulado : 0;
                            echo db_formatar($fRJDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">Subtotal</td>
                        <td class="s3" colspan="2"> <?php
                                                    $fSubTotalOutrasCorrentes = array_sum(array($fMJITR, $fMJIPTU, $fMJTIBI, $fMJISS, $fMJDA, $fMJDAIPTU, $fMJDAITBI, $fMJDAISS, $fMJDAITR, $fRJDAIPTU, $fRJDAITBI, $fRJDAISS));
                                                    echo db_formatar($fSubTotalOutrasCorrentes, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2">

                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">D - Dedu��es das Receitas (exceto FUNDEB)</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <?php
                    $fTotalDeducoes = 0;
                    $aDadoDeducao = getSaldoReceita(null, "o57_fonte,o57_descr,saldo_arrecadado", null, "o57_fonte like '498%'");
                    foreach ($aDadoDeducao as $oDeducao) {
                    ?>
                        <tr style='height:20px;'>
                            <td class="s7 bdleft"></td>
                            <td class="s14"><?php echo db_formatar($oDeducao->o57_fonte, "receita"); ?></td>
                            <td class="s6" colspan="6"><?= $oDeducao->o57_descr; ?></td>
                            <td class="s15" colspan="2">
                                <?php
                                $fTotalDeducoes += $oDeducao->saldo_arrecadado;
                                echo db_formatar($oDeducao->saldo_arrecadado, "f");
                                ?>
                            </td>
                        </tr>
                    <?php }
                    $aDadoDeducao = getSaldoReceita(null, "o57_fonte,o57_descr,saldo_arrecadado", null, "o57_fonte like '499%'");
                    foreach ($aDadoDeducao as $oDeducao) {
                    ?>
                        <tr style='height:20px;'>
                            <td class="s7 bdleft"></td>
                            <td class="s14"><?php echo db_formatar($oDeducao->o57_fonte, "receita"); ?></td>
                            <td class="s6" colspan="6"><?= $oDeducao->o57_descr; ?></td>
                            <td class="s15" colspan="2">
                                <?php
                                $fTotalDeducoes += $oDeducao->saldo_arrecadado;
                                echo db_formatar($oDeducao->saldo_arrecadado, "f");
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">Subtotal</td>
                        <td class="s3" colspan="2"><?= db_formatar($fTotalDeducoes, "f") ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft bdbottom" colspan="8">&nbsp;</td>
                        <td class="s3 bdbottom" colspan="2"></td>
                    </tr>

                    <tr style='height:20px;'>
                        <td class="s20 bdleft" colspan="8">02 - Total das Receitas (A+B+C-D)</td>
                        <td class="s21" colspan="2"><?php $fTotalReceitas = ($fSubTotalImposto + $fSubTotalCorrentes + $fSubTotalOutrasCorrentes) - $fTotalDeducoes;
                                                    echo db_formatar($fTotalReceitas, "f"); ?> </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s20 bdleft" colspan="8">03 - Valor legal de Aplica��o em A��es e Servi�os de Sa�de</td>
                        <td class="s21" colspan="2"><?php echo db_formatar($fTotalReceitas * 0.15, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <?php
                        db_query("drop table if exists work_receita");
                        db_fim_transacao();
                        $fTotalAnexoII = getTotalAnexoIISaude($instits, $dtini, $dtfim, $anousu);
                        ?>
                        <td class="s20 bdleft" dir="ltr" colspan="8">04 - Aplica��o no exerc�cio</td>
                        <td class="s21" colspan="2"><?= db_formatar($fTotalAnexoII, "f") ?></td>
                    </tr>

                    <tr style='height:20px;'>
                        <?php
                        db_query("drop table if exists work_receita");
                        db_fim_transacao();
                        $fTotalAnexoII = getTotalAnexoIISaude($instits, $dtini, $dtfim, $anousu);
                        ?>
                        <td class="s20 bdleft" dir="ltr" colspan="8">05 - Restos a pagar pagos inscritos sem disponibilidade - (Consulta 932.736/2015)</td>
                        <td class="s21" colspan="2"><?= db_formatar($iRestosAPagar, "f") ?></td>
                    </tr>



                    <tr style='height:20px;'>
                        <?php
                        db_query("drop table if exists work_receita");
                        db_fim_transacao();
                        $fTotalAnexoII = getTotalAnexoIISaude($instits, $dtini, $dtfim, $anousu);
                        ?>
                        <td class="s20 bdleft" dir="ltr" colspan="8">06 - Aplica��o total no exerc�cio (Total do Anexo II-B) = <?php echo db_formatar(((($fTotalAnexoII + $iRestosAPagar) / ($fTotalReceitas * 0.15)) * 0.15) * 100, "f"); ?>%</td>
                        <td class="s21" colspan="2"><?= db_formatar(($fTotalAnexoII + $iRestosAPagar), "f") ?></td>
                    </tr>

                </tbody>
            <?php endif; ?>
            <?php  if ((db_getsession('DB_anousu') >= 2018 ) and  (db_getsession('DB_anousu') <= 2021)) : ?>
                <tbody>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft bdtop" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s4 bdleft" colspan="10">DEMONSTRATIVO DA APLICA��O EM A��ES E SERVI�OS P�BLICOS DE SA�DE</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s4 bdleft" colspan="10">(ART. 198, � 2.�, III, DA CF/88)</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s5 bdleft" colspan="10">&nbsp;</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">01 - Receitas </td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">A - Impostos:</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120111</td>
                        <td class="s6" colspan="6">Imposto s/a Propriedade Territorial Rural - Munic.Conv.</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fIPTR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11180111</td>
                        <td class="s12" colspan="6">Imposto sobre a Propriedade Predial e Territorial Urbana - IPTU</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11130311</td>
                        <td class="s6" colspan="6">Imposto de Renda Retido nas Fontes - IRRF (Trab.Assalariado)</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fIRRF, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11130341</td>
                        <td class="s12" colspan="6">Imposto de Renda Retido nas Fontes - IRRF (Outros Rendimentos)</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fIRRFO, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11180141</td>
                        <td class="s6" colspan="6">Imposto sobre Transmiss�o Inter-Vivos de Bens Im�veis - ITBI</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11180231</td>
                        <td class="s12" colspan="6">Imposto sobre Servi�o de Qualquer Natureza - ISS</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fISS, "f");
                            ?>
                        </td>
                    </tr>

                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">Subtotal</td>
                        <td class="s3" colspan="2"><?php $fSubTotalImposto = array_sum(array($fIPTR, $fIPTU, $fIRRF, $fIRRFO, $fITBI, $fISS));
                                                    echo db_formatar($fSubTotalImposto, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">B - Transfer�ncias Correntes:</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17180121</td>
                        <td class="s6" colspan="6">Cota-parte do FPM </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fFPM, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17180151</td>
                        <td class="s12" colspan="6">Transfer�ncia do ITR</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17180611</td>
                        <td class="s6" colspan="6">Transfer�ncia Financ. - Lei Comp.n. 87/96 - ICMS Exp.</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fICMS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17280111</td>
                        <td class="s12" colspan="6">Participa��o no ICMS</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fPARTICMS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">17280121</td>
                        <td class="s6" colspan="6">Imposto sobre IPVA</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fIPVA, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">17280131</td>
                        <td class="s12" colspan="6">Cota-parte do Imposto sobre Produtos Industrializados - IPI</td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fIPI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">Subtotal</td>
                        <td class="s3" colspan="2"><?php $fSubTotalCorrentes = array_sum(array($fFPM, $fFPM1DEZ, $fFPM1JUL, $fITR, $fICMS, $fPARTICMS, $fIPVA, $fIPI));
                                                    echo db_formatar($fSubTotalCorrentes, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2">

                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">C - Outras Receitas Correntes</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11120112</td>
                        <td class="s6" colspan="6">Multas e Juros de Mora do ITR-Munic.Conv.</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJITR, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11180112</td>
                        <td class="s12" colspan="6">Multas e Juros de Mora do IPTU </td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMJIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11180142</td>
                        <td class="s6" colspan="6">Multas e Juros de Mora do ITBI</td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJTIBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11180232</td>
                        <td class="s12" colspan="6"> Multas e Juros de Mora do ISS </td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMJISS, "f");
                            ?>
                        </td>
                    </tr>
                    <!--         <tr style='height:20px;'>
          <td class="s7 bdleft"></td>
          <td class="s8">-</td>
          <td class="s6"  colspan="6">Multas e Juros de Mora da D�vida Ativa do ITR-Munic.Conv.</td>
          <td class="s9" colspan="2">
            <?php
                echo db_formatar($fMJDA, "f");
            ?>
          </td>
        </tr> -->
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11180114</td>
                        <td class="s12" colspan="6">Multas e Juros de Mora da D�vida Ativa do IPTU </td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMJDAIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11180144</td>
                        <td class="s6" colspan="6">Multas e Juros de Mora da D�vida Ativa do ITBI </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fMJDAITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11180234</td>
                        <td class="s12" colspan="6">Multas e Juros de Mora da D�vida Ativa do ISS </td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fMJDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <!--         <tr style='height:20px;'>
          <td class="s7 bdleft"></td>
          <td class="s8">-</td>
          <td class="s6"  colspan="6">Receita da D�vida Ativa do ITR - Munic.Conveniado</td>
          <td class="s9" colspan="2">
            <?php
                echo db_formatar($fMJDAITR, "f");
            ?>
          </td>
        </tr> -->
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11180113</td>
                        <td class="s12" colspan="6">Receita da D�vida Ativa do IPTU </td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fRJDAIPTU, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s7 bdleft"></td>
                        <td class="s8">11180143</td>
                        <td class="s6" colspan="6">Receita da D�vida Ativa do ITBI </td>
                        <td class="s9" colspan="2">
                            <?php
                            echo db_formatar($fRJDAITBI, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s10 bdleft"></td>
                        <td class="s11">11180233</td>
                        <td class="s12" colspan="6">Receita da D�vida Ativa do ISS </td>
                        <td class="s13" colspan="2">
                            <?php
                            echo db_formatar($fRJDAISS, "f");
                            ?>
                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">Subtotal</td>
                        <td class="s3" colspan="2"> <?php
                                                    $fSubTotalOutrasCorrentes = array_sum(array($fMJITR, $fMJIPTU, $fMJTIBI, $fMJISS, $fMJDA, $fMJDAIPTU, $fMJDAITBI, $fMJDAISS, $fMJDAITR, $fRJDAIPTU, $fRJDAITBI, $fRJDAISS));
                                                    echo db_formatar($fSubTotalOutrasCorrentes, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s3 bdleft" colspan="8">&nbsp;</td>
                        <td class="s3" colspan="2">

                        </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">D - Dedu��es das Receitas (exceto FUNDEB)</td>
                        <td class="s3" colspan="2"></td>
                    </tr>
                    <?php
                    $fTotalDeducoes = 0;
                    $aDadoDeducao = getSaldoReceita(null, "o57_fonte,o57_descr,saldo_arrecadado", null, "o57_fonte like '491111%'");
                    foreach ($aDadoDeducao as $oDeducao) {
                    ?>
                        <tr style='height:20px;'>
                            <td class="s7 bdleft"></td>
                            <td class="s14"><?php echo db_formatar($oDeducao->o57_fonte, "receita"); ?></td>
                            <td class="s6" colspan="6"><?= $oDeducao->o57_descr; ?></td>
                            <td class="s15" colspan="2">
                                <?php
                                $fTotalDeducoes += $oDeducao->saldo_arrecadado;
                                echo db_formatar($oDeducao->saldo_arrecadado, "f");
                                ?>
                            </td>
                        </tr>
                    <?php }

                    $aDadoDeducao = getSaldoReceita(null, "o57_fonte,o57_descr,saldo_arrecadado", null, "o57_fonte like '492111%'");
                    foreach ($aDadoDeducao as $oDeducao) {
                    ?>
                        <tr style='height:20px;'>
                            <td class="s7 bdleft"></td>
                            <td class="s14"><?php echo db_formatar($oDeducao->o57_fonte, "receita"); ?></td>
                            <td class="s6" colspan="6"><?= $oDeducao->o57_descr; ?></td>
                            <td class="s15" colspan="2">
                                <?php
                                $fTotalDeducoes += $oDeducao->saldo_arrecadado;
                                echo db_formatar($oDeducao->saldo_arrecadado, "f");
                                ?>
                            </td>
                        </tr>
                    <?php }

                    $aDadoDeducao = getSaldoReceita(null, "o57_fonte,o57_descr,saldo_arrecadado", null, "o57_fonte like '493111%'");
                    foreach ($aDadoDeducao as $oDeducao) {
                    ?>
                        <tr style='height:20px;'>
                            <td class="s7 bdleft"></td>
                            <td class="s14"><?php echo db_formatar($oDeducao->o57_fonte, "receita"); ?></td>
                            <td class="s6" colspan="6"><?= $oDeducao->o57_descr; ?></td>
                            <td class="s15" colspan="2">
                                <?php
                                $fTotalDeducoes += $oDeducao->saldo_arrecadado;
                                echo db_formatar($oDeducao->saldo_arrecadado, "f");
                                ?>
                            </td>
                        </tr>
                    <?php }

                    $aDadoDeducao = getSaldoReceita(null, "o57_fonte,o57_descr,saldo_arrecadado", null, "o57_fonte like '498111%'");
                    foreach ($aDadoDeducao as $oDeducao) {
                    ?>
                        <tr style='height:20px;'>
                            <td class="s7 bdleft"></td>
                            <td class="s14"><?php echo db_formatar($oDeducao->o57_fonte, "receita"); ?></td>
                            <td class="s6" colspan="6"><?= $oDeducao->o57_descr; ?></td>
                            <td class="s15" colspan="2">
                                <?php
                                $fTotalDeducoes += $oDeducao->saldo_arrecadado;
                                echo db_formatar($oDeducao->saldo_arrecadado, "f");
                                ?>
                            </td>
                        </tr>
                    <?php }
                    $aDadoDeducao = getSaldoReceita(null, "o57_fonte,o57_descr,saldo_arrecadado", null, "o57_fonte like '499111%'");
                    foreach ($aDadoDeducao as $oDeducao) {
                    ?>
                        <tr style='height:20px;'>
                            <td class="s7 bdleft"></td>
                            <td class="s14"><?php echo db_formatar($oDeducao->o57_fonte, "receita"); ?></td>
                            <td class="s6" colspan="6"><?= $oDeducao->o57_descr; ?></td>
                            <td class="s15" colspan="2">
                                <?php
                                $fTotalDeducoes += $oDeducao->saldo_arrecadado;
                                echo db_formatar($oDeducao->saldo_arrecadado, "f");
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft" colspan="8">Subtotal</td>
                        <td class="s3" colspan="2"><?= db_formatar($fTotalDeducoes, "f") ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s6 bdleft bdbottom" colspan="8">&nbsp;</td>
                        <td class="s3 bdbottom" colspan="2"></td>
                    </tr>

                    <tr style='height:20px;'>
                        <td class="s20 bdleft" colspan="8">02 - Total das Receitas (A+B+C-D)</td>
                        <td class="s21" colspan="2"><?php $fTotalReceitas = ($fSubTotalImposto + $fSubTotalCorrentes + $fSubTotalOutrasCorrentes) - (abs($fTotalDeducoes));
                                                    echo db_formatar($fTotalReceitas, "f"); ?> </td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s20 bdleft" colspan="8">03 - Valor legal de Aplica��o em A��es e Servi�os de Sa�de</td>
                        <td class="s21" colspan="2"><?php echo db_formatar($fTotalReceitas * 0.15, "f"); ?></td>
                    </tr>
                    <tr style='height:20px;'>
                        <?php
                        db_query("drop table if exists work_receita");
                        db_fim_transacao();
                        $fTotalAnexoII = getTotalAnexoIISaude($instits, $dtini, $dtfim, $anousu);
                        ?>
                        <td class="s20 bdleft" dir="ltr" colspan="8">04 - Aplica��o no exerc�cio</td>
                        <td class="s21" colspan="2"><?= db_formatar($fTotalAnexoII, "f") ?></td>
                    </tr>

                    <tr style='height:20px;'>
                        <?php
                        db_query("drop table if exists work_receita");
                        db_fim_transacao();
                        $fTotalAnexoII = getTotalAnexoIISaude($instits, $dtini, $dtfim, $anousu);
                        ?>
                        <td class="s20 bdleft" dir="ltr" colspan="8">05 - Restos a pagar pagos inscritos sem disponibilidade - (Consulta 932.736/2015)</td>
                        <td class="s21" colspan="2"><?= db_formatar($iRestosAPagar, "f") ?></td>
                    </tr>



                    <tr style='height:20px;'>
                        <?php
                        db_query("drop table if exists work_receita");
                        db_fim_transacao();
                        $fTotalAnexoII = getTotalAnexoIISaude($instits, $dtini, $dtfim, $anousu);
                        ?>
                        <td class="s20 bdleft" dir="ltr" colspan="8">06 - Aplica��o total no exerc�cio (Total do Anexo II-B) = <?php echo db_formatar(((($fTotalAnexoII + $iRestosAPagar) / ($fTotalReceitas * 0.15)) * 0.15) * 100, "f"); ?>%</td>
                        <td class="s21" colspan="2"><?= db_formatar(($fTotalAnexoII + $iRestosAPagar), "f") ?></td>
                    </tr>

                </tbody>
            <?php endif; ?>
        </table>
    </div>

</body>

</html>

<?php

$html = ob_get_contents();
ob_end_clean();
$mPDF->WriteHTML(utf8_encode($html));
$mPDF->Output('Anexo I - Sa�de', 'I');
} catch (MpdfException $e) {
    db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}
?>
