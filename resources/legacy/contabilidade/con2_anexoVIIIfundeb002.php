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
include_once "libs/db_sessoes.php";
include_once "libs/db_usuariosonline.php";
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_slip_classe.php");
require_once("classes/db_empresto_classe.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_dadosexercicioanterior_classe.php");
require_once("classes/db_infocomplementaresinstit_classe.php");
include("libs/db_sql.php");
use Mpdf\Mpdf;
use Mpdf\MpdfException;

$clselorcdotacao = new cl_selorcdotacao();
$clinfocomplementaresinstit = new cl_infocomplementaresinstit();
$cldadosexecicioanterior = new cl_dadosexercicioanterior();
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

/**
 * pego todas as instituições;
 */
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);
$rsInstits = $clinfocomplementaresinstit->sql_record($clinfocomplementaresinstit->sql_query(null, "si09_instit, si09_tipoinstit", null, null));

$ainstitunticoes = array();
for ($i = 0; $i < pg_num_rows($rsInstits); $i++) {
    $odadosInstint = db_utils::fieldsMemory($rsInstits, $i);
    $ainstitunticoes[] = $odadosInstint->si09_instit;
}
$iInstituicoes = implode(',', $ainstitunticoes);

$rsTipoinstit = $clinfocomplementaresinstit->sql_record($clinfocomplementaresinstit->sql_query(null, "si09_sequencial, si09_tipoinstit", null, "si09_instit in( {$instits})"));

/**
 * busco o tipo de instituicao
 */
$ainstitunticoes = array();
$aTipoistituicao = array();

for ($i = 0; $i < pg_num_rows($rsTipoinstit); $i++) {
    $odadosInstint = db_utils::fieldsMemory($rsTipoinstit, $i);
    $aTipoistituicao[] = $odadosInstint->si09_tipoinstit;
    $iCont = pg_num_rows($rsTipoinstit);
}

$db_filtro = " o70_instit in({$instits}) ";
$anousu = db_getsession("DB_anousu");

$aFuncoes     = array(12,28);
$aSubFuncoes = array(122, 128, 271, 272, 273, 361, 365, 366, 367, 843, 844);

$aReceitasImpostos = array(
    array('1 - FUNDEB - IMPOSTOS E TRANSFERÊNCIAS DE IMPOSTOS', 'title', array('417515001%','41321010%', '41321020%','41321030%','41321050%','41329990%','41922510%',), "'118','119','1118','1119','15400007','15400000'"),
    array('1.1 - TRANSFERÊNCIAS DE RECURSOS DO FUNDO DE MANUTENÇÃO E DESENVOLVIMENTO DA EDUCAÇÃO BÁSICA E DE VALORIZAÇÃO DOS PROFISSIONAIS DA EDUCAÇÃO - FUNDEB (NR 1.7.5.1.50.0.1 )', 'text', array('417515001%'), "'118','119','1118','1119','15400007','15400000'"),
    array('1.2 - RENDIMENTOS DE APLICAÇÃO FINANCEIRA (NR 1.3.2.1.01.0.X + NR 1.3.2.1.02.0.X + NR 1.3.2.1.03.0.X + NR 1.3.2.1.05.0.X + NR 1.3.2.9.99.0.X)', 'text', array('41321010%', '41321020%','41321030%','41321050%','41329990%'), "'118','119','1118','1119','15400007','15400000'"),
    array('1.3 - RESSARCIMENTO DE RECURSOS DO FUNDEB (NR 1.9.2.2.51.0.X)', 'text', array('41922510%'), "'118','119','1118','1119','15400007','15400000'"),
    array('2 - FUNDEB - COMPLEMENTAÇÃO DA UNIÃO - VAAF', 'title', array('41715510%','41321010%','41321020%','41321030%','41321050%','41329990%','41922510%'), "'15410007','15410000'"),
    array('2.1 - TRANSFERÊNCIAS DE RECURSOS DA COMPLEMENTAÇÃO DA UNIÃO AO FUNDEB VAAF (NR 1.7.1.5.51.0.X)', 'text', array('41715510%'), "'15410007','15410000'"),
    array('2.2 - RENDIMENTOS DE APLICAÇÃO FINANCEIRA (NR 1.3.2.1.01.0.X + NR 1.3.2.1.02.0.X + NR 1.3.2.1.03.0.X +  NR 1.3.2.1.05.0.X + NR 1.3.2.9.99.0.X)', 'text', array('41321010%','41321020%','41321030%','41321050%','41329990%'), "'15410007','15410000'"),
    array('2.3 - RESSARCIMENTO DE RECURSOS DO FUNDEB (NR 1.9.2.2.51.0.X)', 'text', array('41922510%'), "'15410007','15410000'"),
    array('3 - FUNDEB - COMPLEMENTAÇÃO DA UNIÃO - VAAT', 'title', array('417155001%','41321010%','41321020%','41321030%','41321050%','41329990%','41922510%'), "'166','167','1166','1167','15420007','15420000'"),
    array('3.1 - TRANSFERÊNCIAS DE RECURSOS DA COMPLEMENTAÇÃO DA UNIÃO AO FUNDO DE MANUTENÇÃO E DESENVOLVIMENTO DA EDUCAÇÃO BÁSICA E DE VALORIZAÇÃO DOS PROFISSIONAIS DA EDUCAÇÃO - FUNDEB (VAAT) (NR 1.7.1.5.50.0.1)', 'text', array('417155001%'), "'166','167','1166','1167','15420007','15420000'"),
    array('3.2 - RENDIMENTOS DE APLICAÇÃO FINANCEIRA (NR 1.3.2.1.01.0.X + NR 1.3.2.1.02.0.X + NR 1.3.2.1.03.0.X + NR 1.3.2.1.05.0.X + NR 1.3.2.9.99.0.X)', 'text', array('41321010%','41321020%','41321030%','41321050%','41329990%'), "'166','167','1166','1167','15420007','15420000'"),
    array('3.3 - RESSARCIMENTO DE RECURSOS DO FUNDEB (NR 1.9.2.2.51.0.X)', 'text', array('41922510%'), "'166','167','1166','1167','15420007','15420000'"),
    array('4 - FUNDEB - COMPLEMENTAÇÃO DA UNIÃO - VAAR', 'title', array('41715520%','41321010%','41321020%','41321030%','41321050%','41329990%','41922510%'), "'15430000'"),
    array('4.1 - TRANSFERÊNCIAS DE RECURSOS DA COMPLEMENTAÇÃO DA UNIÃO AO FUNDEB VAAR (NR 1.7.1.5.52.0.X )', 'text', array('41715520%'), "'15430000'"),
    array('4.2 - RENDIMENTOS DE APLICAÇÃO FINANCEIRA (NR 1.3.2.1.01.0.X + NR 1.3.2.1.02.0.X +  NR 1.3.2.1.03.0.X + NR 1.3.2.1.05.0.X + NR 1.3.2.9.99.0.X)', 'text', array('41321010%','41321020%','41321030%','41321050%','41329990%'), "'15430000'"),
    array('4.3 - RESSARCIMENTO DE RECURSOS DO FUNDEB (NR 1.9.2.2.51.0.X)', 'text', array('41922510%'), "'15430000'"),
);

$oDadosexecicioanterior = $cldadosexecicioanterior->getDadosExercicioAnterior(db_getsession("DB_anousu"), $instits, "*", "");

function getValorNaturezaReceita($aNaturecaReceita, $aFontes, $anoUsu, $dtIni, $dtFim, $instits)
{
    $nReceitaTotal = 0;
    $sWhereReceita      = " o70_instit in ({$instits}) ";
    $sWhereReceita      .= " and o70_codigo in ( select o15_codigo from orctiporec where o15_codigo in ({$aFontes}) )";
    $rsReceitas = db_receitasaldo(11, 1, 3, true, $sWhereReceita, $anoUsu, $dtIni, $dtFim, false, ' * ', true, 0);
    $aReceitas = db_utils::getColectionByRecord($rsReceitas);
    db_query("drop table if exists work_receita");
    criarWorkReceita($sWhereReceita, array($anoUsu), $dtIni, $dtFim);
    foreach ($aNaturecaReceita as $sNatureza) {
        $aReceitas = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '{$sNatureza}'");
        $nReceita = count($aReceitas) > 0 ? $aReceitas[0]->saldo_arrecadado_acumulado : 0;
        $nReceitaTotal = $nReceitaTotal + $nReceita;
    }

    db_query("drop table if exists work_receita");
    return $nReceitaTotal;
}

function getDevolucaoRecursoFundeb($dtIni, $dtFim, $aInstits)
{
    $clSlip = new cl_slip();
    $nDevolucaoRecursoFundeb = 0;
    $rsSlip = $clSlip->sql_record($clSlip->sql_query_fundeb($dtIni, $dtFim, $aInstits));
    $nDevolucaoRecursoFundeb = db_utils::fieldsMemory($rsSlip, 0)->k17_valor;
    return $nDevolucaoRecursoFundeb;
}

$sWhereDespesa      = " o58_instit in({$instits})";
db_query("drop table if exists work_dotacao");
criaWorkDotacao($sWhereDespesa,array($anousu), $dtini, $dtfim);

function getEmpenhosApagar($aFontes, $dtini, $dtfim, $instits, $tipo)
{
        $clempempenho = new cl_empempenho();
        $sSqlOrder    = "";
        $sCampos      = "o15_codtri, sum(e60_vlremp) as vlremp, sum(vlranu) as vlranu,  sum(vlrpag) as vlrpago, sum(vlrliq) as vlrliq ";
        $sSqlWhere    = " o15_codigo in (".implode(",", $aFontes).") group by 1";
        $aEmpEmpenho  = $clempempenho->getEmpenhosMovimentosPeriodo(db_getsession("DB_anousu"), $dtini, $dtfim, $instits, $sCampos, $sSqlWhere, $sSqlOrder);
        $valorEmpLQDAPagar = 0;
        $valorEmpNaoLQDAPagar = 0;
        foreach($aEmpEmpenho as $oEmp){
            $valorEmpLQDAPagar += ($oEmp->vlrliq - $oEmp->vlrpago);
            $valorEmpNaoLQDAPagar += ($oEmp->vlremp - $oEmp->vlranu - $oEmp->vlrliq);
        }

        if($tipo == 'lqd'){
            return  $valorEmpLQDAPagar;
        }
        return  $valorEmpNaoLQDAPagar;
}

function getRestosaPagarComplementacaoCapital($dtini, $dtfim, $instits)
{
        $clempresto = new cl_empresto();
        $sSqlOrder = "";
        $sCampos = " sum(vlrpag) as vlrpag ";
        $sSqlWhere = " substr(o56_elemento,1,3) = '344' and o15_codigo in ('166', '167','1166', '1167','15420007', '15420000')";
        $aEmpRestos = $clempresto->getRestosPagarRelAnexoVIII(db_getsession("DB_anousu"), $dtini, $dtfim, $instits,  $sCampos, $sSqlWhere, $sSqlOrder);
        $nRPPagoComplementacao = 0;
        foreach($aEmpRestos as $oEmpResto){
            $nRPPagoComplementacao = $oEmpResto->vlrpag + $oEmpResto->vlrpagnproc;
        }
        return $nRPPagoComplementacao;
}

function getPagamentoComplementacaoCapital($dtini, $dtfim, $instits)
{
    $clempempenho = new cl_empempenho();
    $sSqlOrder    = "";
    $sCampos      = "sum(vlrpag) as vlrpago ";
    $sSqlWhere = " substr(o56_elemento, 1, 3) = '344' and o15_codigo in (''15420007')";
    $aEmpEmpenho  = $clempempenho->getEmpenhosMovimentosPeriodo(db_getsession("DB_anousu"), $dtini, $dtfim, $instits, $sCampos, $sSqlWhere, $sSqlOrder);
    $valorEmpPago = 0;
    foreach($aEmpEmpenho as $oEmp){
        $valorEmpPago += $oEmp->vlrpago;
    }
    return  $valorEmpPago;
}

function getRestosaPagarComplementacaoInfantil($dtini, $dtfim, $instits)
{
        $clempresto = new cl_empresto();
        $sSqlOrder = "";
        $sCampos = " sum(vlrpag) as vlrpag ";
        $sSqlWhere = " o58_subfuncao = 365 and o15_codigo in ('166', '167','1166', '1167','15420007', '15420000')";
        $aEmpRestos = $clempresto->getRestosPagarRelAnexoVIII(db_getsession("DB_anousu"), $dtini, $dtfim, $instits,  $sCampos, $sSqlWhere, $sSqlOrder);
        $nRPPagoComplementacao = 0;
        foreach($aEmpRestos as $oEmpResto){
            $nRPPagoComplementacao = $oEmpResto->vlrpag + $oEmpResto->vlrpagnproc;
        }
        return $nRPPagoComplementacao;
}

function getPagamentoComplementacaoInfantil($dtini, $dtfim, $instits)
{
    $clempempenho = new cl_empempenho();
    $sSqlOrder    = "";
    $sCampos      = "sum(vlrpag) as vlrpago ";
    $sSqlWhere    = " o58_subfuncao = 365 and o15_codigo in ('166', '167','1166', '1167','15420007', '15420000')";
    $aEmpEmpenho  = $clempempenho->getEmpenhosMovimentosPeriodo(db_getsession("DB_anousu"), $dtini, $dtfim, $instits, $sCampos, $sSqlWhere, $sSqlOrder);
    $valorEmpPago = 0;
    foreach($aEmpEmpenho as $oEmp){
        $valorEmpPago += $oEmp->vlrpago;
    }
    return  $valorEmpPago;
}

function getSaldoPlanoContaFonteFundeb($nFonte, $dtIni, $dtFim, $aInstits)
{
    $where = " c61_instit in ({$aInstits})" ;
    $where .= " and c61_codigo in ( select o15_codigo from orctiporec where o15_codigo in ($nFonte) ) ";
    $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"), $dtIni, $dtFim, false, $where, '111');
    $nTotalAnterior = 0;
    for($x = 0; $x < pg_numrows($result); $x++){
        $oPlanoConta = db_utils::fieldsMemory($result, $x);
        if( ( $oPlanoConta->movimento == "S" )
            && ( ( $oPlanoConta->saldo_anterior + $oPlanoConta->saldo_anterior_debito + $oPlanoConta->saldo_anterior_credito) == 0 ) ) {
            continue;
        }
        if(substr($oPlanoConta->estrutural,1,14) == '00000000000000'){
            if($oPlanoConta->sinal_anterior == "C")
                $nTotalAnterior -= $oPlanoConta->saldo_anterior;
            else {
                $nTotalAnterior += $oPlanoConta->saldo_anterior;
            }
        }
    }
    return $nTotalAnterior;
}

function getRestosSemDisponilibidadeFundeb($aFontes, $dtIni, $dtFim, $aInstits)
{
    $iSaldoRestosAPagarSemDisponibilidade = 0;

    foreach($aFontes as $sFonte){
        db_inicio_transacao();
        $clEmpResto = new cl_empresto();
        $sSqlOrder = "";
        $sCampos = " o15_codtri, sum(vlrpag) as pagorpp, sum(vlrpagnproc) as pagorpnp ";
        $sSqlWhere = " o15_codigo in ($sFonte) group by 1 ";
        $aEmpRestos = $clEmpResto->getRestosPagarFontePeriodo(db_getsession("DB_anousu"), $dtIni, $dtFim, $aInstits, $sCampos, $sSqlWhere, $sSqlOrder);
        $nValorRpPago = 0;
        foreach($aEmpRestos as $oEmpResto){
            $nValorRpPago += $oEmpResto->pagorpp + $oEmpResto->pagorpnp;
        }
        $nTotalAnterior = getSaldoPlanoContaFonteFundeb($sFonte, $dtIni, $dtFim, $aInstits);

        $nSaldo = 0;
        if($nValorRpPago > $nTotalAnterior){
            $nSaldo = $nValorRpPago - $nTotalAnterior ;
        }
        $iSaldoRestosAPagarSemDisponibilidade += $nSaldo;
        db_query("drop table if exists work_pl");
        db_fim_transacao();
    }
    return  $iSaldoRestosAPagarSemDisponibilidade;
}

function getDespesasCusteadosComSuperavit($aFontes, $dtini, $dtfim, $instits)
{
    $clempempenho = new cl_empempenho();
    $sSqlOrder = "";
    $sCampos = " o15_codtri, sum(vlrpag) as vlrpag ";
    $sSqlWhere = " o15_codigo in (".implode(",", $aFontes).") group by 1";
    $dtfim = db_getsession("DB_anousu")."-04-30";
    $aEmpEmpenho = $clempempenho->getDespesasCusteadosComSuperavit(db_getsession("DB_anousu"), $dtini, $dtfim, $instits,  $sCampos, $sSqlWhere, $sSqlOrder);
    $valorEmpPagoSuperavit = 0;
    foreach($aEmpEmpenho as $oEmp){
        $valorEmpPagoSuperavit += $oEmp->vlrpag;
    }
    return  $valorEmpPagoSuperavit;
}

function getDespesasCusteadosComSuperavitPosPrimeiroQuadrimestre($aFontes, $dtini, $dtfim, $instits)
{
    $clempempenho = new cl_empempenho();
    $sSqlOrder = "";
    $sCampos = " o15_codtri, sum(vlrpag) as vlrpag ";
    $sSqlWhere = " o15_codigo in (".implode(",", $aFontes).") group by 1";
    $dtini = db_getsession("DB_anousu")."-05-01";
    $aEmpEmpenho = $clempempenho->getDespesasCusteadosComSuperavit(db_getsession("DB_anousu"), $dtini, $dtfim, $instits,  $sCampos, $sSqlWhere, $sSqlOrder);
    $valorEmpPagoSuperavit = 0;
    foreach($aEmpEmpenho as $oEmp){
        $valorEmpPagoSuperavit += $oEmp->vlrpag;
    }
    return  $valorEmpPagoSuperavit;
}

function getRestoaPagardeExerciciosAnteriores($aFontes, $dtini, $dtfim, $instits)
{

    $sSqlRec = "SELECT DISTINCT o15_codtri FROM orctiporec WHERE o15_codigo in ($aFontes) ORDER BY o15_codtri";
    $result = db_query($sSqlRec);

    $recurso = array();
    for ($i = 0; $i < pg_num_rows($result); $i++) {

        $oRecurso = db_utils::fieldsMemory($result, $i);

        $recurso[] = $oRecurso->o15_codtri;
    }
    foreach ($recurso as $fonte) {
        $sql = "SELECT e91_numemp,e91_vlremp,e91_vlranu,e91_vlrliq,e91_vlrpag,o15_codtri,vlranu,vlrliq,vlrpag,
                        vlrpagnproc,vlranuliq,vlranuliqnaoproc
                FROM
                    (SELECT e91_numemp,e91_anousu,e91_codtipo,e90_descr,o15_descr,o15_codtri,c70_anousu,
                    coalesce(e91_vlremp,0) AS e91_vlremp,
                    coalesce(e91_vlranu,0) AS e91_vlranu,
                    coalesce(e91_vlrliq,0) AS e91_vlrliq,
                    coalesce(e91_vlrpag,0) AS e91_vlrpag,e91_recurso,
                    coalesce(vlranu,0) AS vlranu,
                    coalesce(vlranuliq,0) AS vlranuliq,
                    coalesce(vlranuliqnaoproc,0) AS vlranuliqnaoproc,
                    coalesce(vlrliq,0) AS vlrliq,
                    coalesce(vlrpag,0) AS vlrpag,
                    coalesce(vlrpagnproc,0) AS vlrpagnproc
                        FROM empresto
                        INNER JOIN emprestotipo ON e91_codtipo = e90_codigo
                        INNER JOIN orctiporec ON e91_recurso = o15_codigo
                        LEFT OUTER JOIN
                        (SELECT c75_numemp,c70_anousu,
                            sum(round(CASE WHEN c53_tipo = 11 THEN c70_valor ELSE 0 END,2)) AS vlranu,
                            sum(round(CASE WHEN c71_coddoc = 31 THEN c70_valor ELSE 0 END,2)) AS vlranuliq,
                            sum(round(CASE WHEN c71_coddoc = 32 THEN c70_valor ELSE 0 END,2)) AS vlranuliqnaoproc,
                            sum(round(CASE WHEN c53_tipo = 20 THEN c70_valor ELSE (CASE WHEN c53_tipo = 21 THEN c70_valor*-1 ELSE 0 END) END,2)) AS vlrliq,
                            sum(round(CASE WHEN c71_coddoc = 35 THEN c70_valor ELSE (CASE WHEN c71_coddoc = 36 THEN c70_valor*-1 ELSE 0 END) END,2)) AS vlrpag,
                            sum(round(CASE WHEN c71_coddoc = 37 THEN c70_valor ELSE (CASE WHEN c71_coddoc = 38 THEN c70_valor*-1 ELSE 0 END) END,2)) AS vlrpagnproc
                            FROM conlancamemp
                            INNER JOIN conlancamdoc ON c71_codlan = c75_codlan
                            INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                            INNER JOIN conlancam ON c70_codlan = c75_codlan
                            INNER JOIN empempenho ON e60_numemp = c75_numemp
                            WHERE e60_anousu < " . DB_getsession("DB_anousu") . " AND c75_data BETWEEN '$dtini' AND '$dtfim'
                            AND e60_instit IN (" . db_getsession('DB_instit') . ") GROUP BY c75_numemp,c70_anousu) AS x ON x.c75_numemp = e91_numemp
                            WHERE e91_anousu = " . DB_getsession("DB_anousu") . " ) AS x
                            INNER JOIN empempenho ON e60_numemp = e91_numemp AND e60_instit IN (" . db_getsession('DB_instit') . ")
                            INNER JOIN empelemento ON e64_numemp = e60_numemp
                            INNER JOIN cgm ON z01_numcgm = e60_numcgm
                            INNER JOIN orcdotacao ON o58_coddot = e60_coddot AND o58_anousu = e60_anousu
                            AND o58_instit = e60_instit
                            INNER JOIN orcorgao ON o40_orgao = o58_orgao AND o40_anousu = o58_anousu
                            INNER JOIN orcunidade ON o41_anousu = o58_anousu AND o41_orgao = o58_orgao AND o41_unidade = o58_unidade
                            INNER JOIN orcfuncao ON o52_funcao = orcdotacao.o58_funcao
                            INNER JOIN orcsubfuncao ON o53_subfuncao = orcdotacao.o58_subfuncao
                            INNER JOIN orcprograma ON o54_programa = o58_programa AND o54_anousu = orcdotacao.o58_anousu
                            INNER JOIN orcprojativ ON o55_projativ = o58_projativ AND o55_anousu = orcdotacao.o58_anousu
                            INNER JOIN orcelemento ON o58_codele = o56_codele AND o58_anousu = o56_anousu AND 1=1
                            AND o15_codtri::int4 IN($fonte)
                            AND o15_codtri != ''
                            ORDER BY e91_recurso,e60_anousu,e60_codemp::bigint";

        $resultMovFonte = db_query($sql);


        $aFontes = [];
        for ($iContMov = 0; $iContMov < pg_num_rows($resultMovFonte); $iContMov++) {
            $rsFontes = db_utils::fieldsMemory($resultMovFonte, $iContMov);
            $aFontes[$rsFontes->o15_codtri][] = $rsFontes;
        }

        foreach ($aFontes as $fontes) {
            $oVlrTote91Emp = 0;
            $oVlrTote91Anu = 0;
            $oVlrTote91Liq = 0;
            $oVlrTote91Pag = 0;
            $oVlrTotEmp = 0;
            $oVlrTotLiq = 0;
            $oVlrTotAnu = 0;
            $oVlrTotPag = 0;

            $vlrTotpagnproc = 0;
            $vlrTotanuliq = 0;
            $vlrTotanuliqnaoproc = 0;
            $vlRspExeAnt = new stdClass();

            foreach ($fontes as $item) {

                $oVlrTote91Emp += $item->e91_vlremp;
                $oVlrTote91Anu += $item->e91_vlranu;
                $oVlrTote91Liq += $item->e91_vlrliq;
                $oVlrTote91Pag += $item->e91_vlrpag;
                $oVlrTotLiq += $item->vlrliq;
                $oVlrTotAnu += $item->vlranu;
                $oVlrTotPag += $item->vlrpag;
                $vlrTotpagnproc += $item->vlrpagnproc;
                $vlrTotanuliq += $item->vlranuliq;
                $vlrTotanuliqnaoproc += $item->vlranuliqnaoproc;

                $totLiq = ($oVlrTote91Liq - $oVlrTote91Pag - $oVlrTotPag - $vlrTotanuliq) + ($oVlrTotLiq - $vlrTotpagnproc);

                $totNP = $oVlrTote91Emp - $oVlrTote91Anu - $oVlrTote91Liq - $vlrTotanuliqnaoproc - $oVlrTotLiq;

                $vlRspExeAnt->vlRspExeAnt = $totLiq + $totNP;
                $vlRspExerciciosAnteriores[$item->o15_codtri] = $vlRspExeAnt;
            }
        }

    }
    $vlrDisponibilidade = array();
    $retornoSicom = array();

    foreach ($vlRspExerciciosAnteriores as $fonte => $oDados) {
        $vlrDisponibilidade[$fonte]->VlrDisponibilidade -= round($oDados->vlRspExeAnt, 2);
        if (!$retornoSicom[$fonte]) {
          $retornoSicom[$fonte]->vlrcaixabruta = 0;
          $retornoSicom[$fonte]->VlrroexercicioAnteriores = round($oDados->vlRspExeAnt, 2);
        } else {
          $retornoSicom[$fonte]->VlrroexercicioAnteriores = round($oDados->vlRspExeAnt, 2);
        }
    }
return $retornoSicom;;
}


/**
 * mPDF
 * @param string $mode              | padrão: BLANK
 * @param mixed $format             | padrão: A4
 * @param float $default_font_size  | padrão: 0
 * @param string $default_font      | padrão: ''
 * @param float $margin_left        | padrão: 15
 * @param float $margin_right       | padrão: 15
 * @param float $margin_top         | padrão: 16
 * @param float $margin_bottom      | padrão: 16
 * @param float $margin_header      | padrão: 9
 * @param float $margin_footer      | padrão: 9
 *
 * Nenhum dos parâmetros é obrigatório
 */


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

$header = " <header>
                <div style=\" height: 120px; font-family:Arial\">
                    <div style=\"width:33%; float:left; padding:5px; font-size:10px;\">
                        <b><i>{$oInstit->getDescricao()}</i></b><br/>
                        <i>{$oInstit->getLogradouro()}, {$oInstit->getNumero()}</i><br/>
                        <i>{$oInstit->getMunicipio()} - {$oInstit->getUf()}</i><br/>
                        <i>{$oInstit->getTelefone()} - CNPJ: " . db_formatar($oInstit->getCNPJ(), "cnpj") . "</i><br/>
                        <i>{$oInstit->getSite()}</i>
                    </div>
                    <div style=\"width:25%; float:right\" class=\"box\">
                        <b>Relatório FUNDEB - Anexo VIII</b><br/>
                        <b>INSTITUIÇÕES:</b> ";
foreach ($aInstits as $iInstit) {
    $oInstituicao = new Instituicao($iInstit);
    $header .= "(" . trim($oInstituicao->getCodigo()) . ") " . $oInstituicao->getDescricao() . " ";
}
$header .= "<br/> <b>PERÍODO:</b> {$DBtxt21} A {$DBtxt22} <br/>
                    </div>
                </div>
            </header>";

$footer = "<footer>
                <div style='border-top:1px solid #000;width:100%;font-family:sans-serif;font-size:10px;height:10px;'>
                    <div style='text-align:left;font-style:italic;width:90%;float:left;'>
                        Financeiro>Contabilidade>Relatórios de Acompanhamento>Fundeb - Anexo VIII
                        Emissor: " . db_getsession("DB_login") . " Exerc: " . db_getsession("DB_anousu") . " Data:" . date("d/m/Y H:i:s", db_getsession("DB_datausu"))  . "
                    <div style='text-align:right;float:right;width:10%;'>
                        {PAGENO}
                    </div>
                </div>
          </footer>";

$mPDF->WriteHTML(file_get_contents('estilos/tab_relatorio.css'), 1);
$mPDF->setHTMLHeader(utf8_encode($header), 'O', true);
$mPDF->setHTMLFooter(utf8_encode($footer), 'O', true);

ob_start();

?>
<html>

<head>
    <style type="text/css">

        .center-text {
            text-align: center;
            vertical-align: middle;
        }

        .title-relatorio {
            text-align: center;
            padding: 0;
        }

        .ritz .waffle a {
            color: inherit;
        }

        .ritz .waffle .s6 {
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s42 {
            border-right: none;
            border-bottom: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: middle;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s33 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: top;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s35 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
        }

        .ritz .waffle .s4 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s3 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 8pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s20 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: right;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s9 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: normal;
            overflow: hidden;
            word-wrap: break-word;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s36 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #808080;
            background-color: #cccccc;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s53 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: middle;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s18 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: top;
            white-space: normal;
            overflow: hidden;
            word-wrap: break-word;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s11 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 8pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s14 {
            border-left: none;
            border-right: none;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s34 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: top;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s50 {
            border-left: none;
            border-right: none;
            border-bottom: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s0 {
            background-color: #ffffff;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 8pt;
            vertical-align: top;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s29 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: normal;
            overflow: hidden;
            word-wrap: break-word;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s52 {
            background-color: #ffffff;
            text-align: center;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s19 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s26 {
            border-bottom: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s45 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: normal;
            overflow: hidden;
            word-wrap: break-word;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s24 {
            border-left: none;
            border-right: none;
            border-bottom: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s30 {
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s8 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 8pt;
            vertical-align: bottom;
            white-space: normal;
            overflow: hidden;
            word-wrap: break-word;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s27 {
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'docs-Calibri', Arial;
            font-size: 8pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s2 {
            border-bottom: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: top;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s38 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: right;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: top;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s37 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #808080;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s23 {
            border-right: none;
            border-bottom: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s28 {
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s10 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: right;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s22 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: right;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s43 {
            border-left: none;
            border-bottom: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: middle;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s25 {
            border-left: none;
            border-bottom: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s46 {
            border-bottom: 1px SOLID #808080;
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: right;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: top;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s41 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s13 {
            border-right: none;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s31 {
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: right;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s21 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s12 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s40 {
            border-bottom: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s47 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #808080;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s7 {
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: right;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s17 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 8pt;
            vertical-align: top;
            white-space: normal;
            overflow: hidden;
            word-wrap: break-word;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s39 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'docs-Calibri', Arial;
            font-size: 8pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s44 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: middle;
            white-space: normal;
            overflow: hidden;
            word-wrap: break-word;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s15 {
            border-left: none;
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s48 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #808080;
            background-color: #cccccc;
            text-align: right;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s32 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            color: #000000;
            font-family: 'Arial';
            font-size: 8pt;
            vertical-align: top;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s49 {
            border-right: none;
            border-bottom: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: center;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s16 {
            background-color: #cccccc;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s51 {
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            background-color: #cccccc;
            text-align: right;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s1 {
            background-color: #ffffff;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 10pt;
            vertical-align: top;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .ritz .waffle .s5 {
            border-right: 1px SOLID #000000;
            background-color: #ffffff;
            text-align: left;
            font-weight: bold;
            color: #000000;
            font-family: 'Arial';
            font-size: 8pt;
            vertical-align: bottom;
            white-space: nowrap;
            direction: ltr;
            padding: 2px 3px 2px 3px;
        }

        .body-relatorio {
            width: 100%;
            height: 80%;
        }

        .no-break {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>
    <div class="ritz grid-container" dir="ltr">
        <div class="title-relatorio"><br />
            <strong>Anexo VIII</strong><br />
            <strong>Fundo Manutenção e Desenvolvimento da Educação Básica e de Valorização Dos Profissionais da Educação - FUNDEB</strong><br />
            <strong> (Art. 212 - A DA CR/88, LEIS 9.394/96, 14.113/2020 E IN 02/2021) </strong><br />
        </div>
        <div class="body-relatorio">
            <table class="waffle no-grid" cellspacing="0" cellpadding="0">
                <tr style="height: 20px">
                    <td class="s0" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                    <td class="s1" dir="ltr"></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s0" dir="ltr"></td>
                    <td class="s2" dir="ltr">I - RECEITAS DO FUNDEB RECEBIDAS NO EXERCÍCIO</td>
                    <td class="s2" dir="ltr"></td>
                    <td class="s2" dir="ltr"></td>
                    <td class="s2" dir="ltr"></td>
                    <td class="s2" dir="ltr"></td>
                    <td class="s2" dir="ltr"></td>
                    <td class="s2" dir="ltr"></td>
                    <td class="s2" dir="ltr"></td>
                    <td class="s2" dir="ltr"></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s3" dir="ltr"></td>
                    <td class="s4" dir="ltr" colspan="8">NATUREZA DA RECEITA</td>
                    <td class="s4" dir="ltr">VALOR</td>
                </tr>
                <?php
                $nTotalReceitaRecurso = 0;
                foreach ($aReceitasImpostos as $receita) {
                    echo "<tr style='height: 20px'>";
                    echo " <td class='s5' dir='ltr'></td>";
                    if ($receita[1] == 'title') {
                        echo " <td class='s6' dir='ltr' colspan='8'>{$receita[0]}</td>";
                        echo " <td class='s7' dir='ltr'> ";
                    }else{
                        echo " <td class='s9' dir='ltr' colspan='8'>{$receita[0]}</td>";
                        echo " <td class='s10' dir='ltr'> ";
                    }
                    $nReceita = getValorNaturezaReceita($receita[2], $receita[3], $anousu, $dtini, $dtfim, $instits);
                    if ($receita[1] == 'title') {
                        $nTotalReceitaRecurso += $nReceita;
                    }
                    echo db_formatar(abs($nReceita), "f");
                    echo " </td>";
                    echo "</tr>";
                }
                ?>
                <tr style="height: 20px">
                    <td class="s5" dir="ltr"></td>
                    <td class="s21" dir="ltr" colspan="8">5 - TOTAL ( 1 + 2 + 3 + 4)</td>
                    <td class="s22" dir="ltr">
                        <?php echo db_formatar($nTotalReceitaRecurso, "f"); ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="no-break">
            <table class="waffle no-grid" cellspacing="0" cellpadding="0">
                <tr style="height: 20px">
                    <td class="s27">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s0" dir="ltr"></td>
                    <td class="s2" dir="ltr" colspan="9">II - DESPESAS COM RECURSOS DO FUNDEB</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s3" dir="ltr"></td>
                    <td class="s4" dir="ltr"  colspan="4" rowspan="2"  style="text-align: center; vertical-align: middle;">FUNÇÃO / SUBFUNÇÃO / PROGRAMA</td>
                    <td class="s29" dir="ltr" colspan="5">DESPESAS CUSTEADAS COM RECURSOS FUNDEB RECEBIDAS NO EXERCÍCIO</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s3" dir="ltr"></td>
                    <td class="s4" dir="ltr">IMPOSTOS E<br> TRANSFERÊNCIAS<br> DE IMPOSTOS</td>
                    <td class="s4" dir="ltr">VAAF</td>
                    <td class="s4" dir="ltr">VAAT</td>
                    <td class="s4" dir="ltr">VAAR</td>
                    <td class="s4" dir="ltr">TOTAL</td>
                </tr>
                <?php
                    $pagoFuncao15400007_15400000 = 0;
                    $pagoFuncao15410007_15410000 = 0;
                    $pagoFuncao15420007_15420000 = 0;
                    $pagoFuncao15430000          = 0;
                    foreach($aFuncoes as $iFuncao){
                        $aSubFuncoes = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o15_codigo in (".implode(",",array("'15400007','15400000','15410007','15410000','15420007','15420000','15430000'")).") and o58_instit in ($instits) group by 1,2 order by 1");

                        foreach ($aSubFuncoes as $oSubFuncao) {
                            $sDescrSubfuncao = db_utils::fieldsMemory(db_query("select o53_descr from orcsubfuncao where o53_codtri = '{$oSubFuncao->o58_subfuncao}'"), 0)->o53_descr;
                            $aSubFuncao15400007_15400000 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15400007','15400000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                            $nValorPagoSubFuncao15400007_15400000 = count( $aSubFuncao15400007_15400000) > 0 ? $aSubFuncao15400007_15400000[0]->pago : 0;
                            $TotalFuncao15400007_15400000 += $nValorPagoSubFuncao15400007_15400000;
                            $aSubFuncao15410007_15410000 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15410007','15410000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                            $nValorPagoSubFuncao15410007_15410000 = count( $aSubFuncao15410007_15410000) > 0 ? $aSubFuncao15410007_15410000[0]->pago : 0;
                            $TotalFuncao15410007_15410000 += $nValorPagoSubFuncao15410007_15410000;
                            $aSubFuncao15420007_15420000 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15420007','15420000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                            $nValorPagoSubFuncao15420007_15420000 = count( $aSubFuncao15420007_15420000) > 0 ? $aSubFuncao15420007_15420000[0]->pago : 0;
                            $TotalFuncao15420007_15420000 += $nValorPagoSubFuncao15420007_15420000;
                            $aSubFuncao15430000 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15430000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                            $nValorPagoSubFuncao15430000 = count( $aSubFuncao15430000) > 0 ? $aSubFuncao15430000[0]->pago : 0;
                            $TotalFuncao15430000          += $nValorPagoSubFuncao15430000;
                        }
                        if ($aSubFuncoes) {
                            echo "<tr style='height: 20px'>";
                            echo " <td class='s11' dir='ltr'></td>";
                            echo " <td class='s30' dir='ltr' colspan='4'>FUNÇÕES 12 E 28</td>";
                            echo " <td class='s31' dir='ltr'>";echo db_formatar($TotalFuncao15400007_15400000, "f"); echo " </td>";
                            echo " <td class='s31' dir='ltr'>";echo db_formatar($TotalFuncao15410007_15410000, "f"); echo " </td>";
                            echo " <td class='s31' dir='ltr'>";echo db_formatar($TotalFuncao15420007_15420000, "f"); echo " </td>";
                            echo " <td class='s31' dir='ltr'>";echo db_formatar($TotalFuncao15430000, "f"); echo " </td>";
                            echo " <td class='s31' dir='ltr'>";echo db_formatar($TotalFuncao15400007_15400000 + $TotalFuncao15410007_15410000 + $TotalFuncao15420007_15420000 + $TotalFuncao15430000, "f"); echo " </td>";
                        }
                        foreach ($aSubFuncoes as $oSubFuncao) {
                            $sDescrSubfuncao = db_utils::fieldsMemory(db_query("select o53_descr from orcsubfuncao where o53_codtri = '{$oSubFuncao->o58_subfuncao}'"), 0)->o53_descr;

                            $aSubFuncao15400007_15400000 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15400007','15400000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                            $nValorPagoSubFuncao15400007_15400000 = count( $aSubFuncao15400007_15400000) > 0 ? $aSubFuncao15400007_15400000[0]->pago : 0;
                            $pagoFuncao15400007_15400000 += $nValorPagoSubFuncao15400007_15400000;
                            $aSubFuncao15410007_15410000 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15410007','15410000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                            $nValorPagoSubFuncao15410007_15410000 = count( $aSubFuncao15410007_15410000) > 0 ? $aSubFuncao15410007_15410000[0]->pago : 0;
                            $pagoFuncao15410007_15410000 += $nValorPagoSubFuncao15410007_15410000;
                            $aSubFuncao15420007_15420000 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15420007','15420000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                            $nValorPagoSubFuncao15420007_15420000 = count( $aSubFuncao15420007_15420000) > 0 ? $aSubFuncao15420007_15420000[0]->pago : 0;
                            $pagoFuncao15420007_15420000 += $nValorPagoSubFuncao15420007_15420000;
                            $aSubFuncao15430000 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15430000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                            $nValorPagoSubFuncao15430000 = count( $aSubFuncao15430000) > 0 ? $aSubFuncao15430000[0]->pago : 0;
                            $pagoFuncao15430000          += $nValorPagoSubFuncao15430000;

                            if ($nValorPagoSubFuncao15400007_15400000 + $nValorPagoSubFuncao15410007_15410000 + $nValorPagoSubFuncao15420007_15420000 + $nValorPagoSubFuncao15430000 > 0) {

                                echo "<tr style='height: 20px'> ";
                                echo " <td class='s8' dir='ltr'></td>";
                                echo " <td class='s9' dir='ltr' colspan='4'>{$oSubFuncao->o58_subfuncao} - {$sDescrSubfuncao}</td>";
                                echo " <td class='s10' dir='ltr'>";
                                echo db_formatar($nValorPagoSubFuncao15400007_15400000, "f"); echo " </td>";
                                echo " <td class='s10' dir='ltr'>";
                                echo    db_formatar($nValorPagoSubFuncao15410007_15410000, "f"); echo " </td>";
                                echo " <td class='s10' dir='ltr'>";
                                echo    db_formatar($nValorPagoSubFuncao15420007_15420000, "f"); echo " </td>";
                                echo " <td class='s10' dir='ltr'>";
                                echo    db_formatar($nValorPagoSubFuncao15430000, "f"); echo " </td>";
                                echo " <td class='s10' dir='ltr'>";
                                echo    db_formatar($nValorPagoSubFuncao15400007_15400000 + $nValorPagoSubFuncao15410007_15410000 + $nValorPagoSubFuncao15420007_15420000 + $nValorPagoSubFuncao15430000, "f");
                                echo " </td>";
                                echo "</tr>";

                                $aDespesasProgramas = getSaldoDespesa(null, "o58_programa, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o58_instit in ($instits) group by 1,2");
                                foreach ($aDespesasProgramas as $oDespesaPrograma) {
                                    $oPrograma = new Programa($oDespesaPrograma->o58_programa, $oDespesaPrograma->o58_anousu);

                                    $aPrograma15400007_15400000 = getSaldoDespesa(null, "o15_codtri, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o58_programa in ({$oDespesaPrograma->o58_programa}) and o15_codigo in (".implode(",",array("'15400007','15400000'")).") and o58_instit in ($instits) group by 1,2");
                                    $nValorPagoFonte15400007_15400000 = count( $aPrograma15400007_15400000) > 0 ? $aPrograma15400007_15400000[0]->pago : 0;
                                    $aFonte15410007_15410000 = getSaldoDespesa(null, "o15_codtri, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o58_programa in ({$oDespesaPrograma->o58_programa}) and o15_codigo in (".implode(",",array("'15410007','15410000'")).") and o58_instit in ($instits) group by 1,2");
                                    $nValorPagoFonte15410007_15410000 = count( $aFonte15410007_15410000) > 0 ? $aFonte15410007_15410000[0]->pago : 0;
                                    $aFonte15420007_15420000 = getSaldoDespesa(null, "o15_codtri, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o58_programa in ({$oDespesaPrograma->o58_programa}) and o15_codigo in (".implode(",",array("'15420007','15420000'")).") and o58_instit in ($instits) group by 1,2");
                                    $nValorPagoFonte15420007_15420000 = count( $aFonte15420007_15420000) > 0 ? $aFonte15420007_15420000[0]->pago : 0;
                                    $aFonte15430000  = getSaldoDespesa(null, "o15_codtri, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o58_programa in ({$oDespesaPrograma->o58_programa}) and o15_codigo in (".implode(",",array("'15430000'")).") and o58_instit in ($instits) group by 1,2");
                                    $nValorPagoFonte15430000  = count( $aFonte15430000 ) > 0 ? $aFonte15430000 [0]->pago : 0;

                                    if ($nValorPagoFonte15400007_15400000 + $nValorPagoFonte15410007_15410000 + $nValorPagoFonte15420007_15420000 + $nValorPagoFonte15430000 > 0 ) {

                                        echo "<tr style='height: 20px'>";
                                        echo " <td class='s8' dir='ltr'></td>";
                                        echo " <td class='s9' dir='ltr' colspan='4' style='padding-left: 10px;'> ";
                                        echo db_formatar($oPrograma->getCodigoPrograma(), "programa")." ".$oPrograma->getDescricao(); echo " </td>";
                                        echo " <td class='s10' dir='ltr'>";  echo db_formatar($nValorPagoFonte15400007_15400000, "f"); echo " </td>";
                                        echo " <td class='s10' dir='ltr'>";  echo db_formatar($nValorPagoFonte15410007_15410000, "f"); echo " </td>";
                                        echo " <td class='s10' dir='ltr'>";  echo db_formatar($nValorPagoFonte15420007_15420000, "f"); echo " </td>";
                                        echo " <td class='s10' dir='ltr'>";  echo db_formatar($nValorPagoFonte15430000, "f"); echo " </td>";
                                        echo " <td class='s10' dir='ltr'>";; echo " </td>";
                                        echo "</tr>";
                                     }
                                }
                            }
                        }
                    }
                ?>
                <tr style="height: 20px">
                    <td class="s5" dir="ltr"></td>
                    <td class="s21" dir="ltr" colspan="4" style="border-top: 1px SOLID #000000;">6 - SUBTOTAL VALOR PAGO</td>
                    <td class="s22" dir="ltr" style="border-top: 1px SOLID #000000;" ><?php echo db_formatar($pagoFuncao15400007_15400000, "f"); ?></td>
                    <td class="s22" dir="ltr" style="border-top: 1px SOLID #000000;" ><?php echo db_formatar($pagoFuncao15410007_15410000, "f"); ?></td>
                    <td class="s22" dir="ltr" style="border-top: 1px SOLID #000000;" ><?php echo db_formatar($pagoFuncao15420007_15420000, "f"); ?></td>
                    <td class="s22" dir="ltr" style="border-top: 1px SOLID #000000;" ><?php echo db_formatar($pagoFuncao15430000         , "f"); ?></td>
                    <td class="s22" dir="ltr" style="border-top: 1px SOLID #000000;" ><?php echo db_formatar($pagoFuncao15400007_15400000 + $pagoFuncao15410007_15410000 + $pagoFuncao15420007_15420000 + $pagoFuncao15430000, "f"); ?></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s0"></td>
                    <td class="s2" colspan="9"></td>
                </tr>

                <tr style="height: 20px">
                    <td class="s32" dir="ltr"></td>
                    <td class="s33" dir="ltr" colspan="4">7 - RESTOS A PAGAR PROCESSADOS DO EXERCÍCIO</td>
                    <?php
                        $nLiqAPagar15400007_15400000 = 0;
                        $nLiqAPagar15410007_15410000 = 0;
                        $nLiqAPagar15420007_15420000 = 0;
                        $nLiqAPagar15430000 = 0;
                        $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                        if($dtfim == $dtfimExercicio){
                            $nLiqAPagar15400007_15400000 = getEmpenhosApagar(array("'15400007','15400000'"), $dtini, $dtfim, $instits, 'lqd');
                            $nLiqAPagar15410007_15410000 = getEmpenhosApagar(array("'15410007','15410000'"), $dtini, $dtfim, $instits, 'lqd');
                            $nLiqAPagar15420007_15420000 = getEmpenhosApagar(array("'15420007','15420000'"), $dtini, $dtfim, $instits, 'lqd');
                            $nLiqAPagar15430000          = getEmpenhosApagar(array("'15430000'"), $dtini, $dtfim, $instits, 'lqd');
                            $nLiqAPagarTotal             = $nLiqAPagar15400007_15400000 + $nLiqAPagar15410007_15410000 + $nLiqAPagar15420007_15420000 + $nLiqAPagar15430000;
                        }
                        echo "<td class='s10' dir='ltr' >";
                        echo db_formatar($nLiqAPagar15400007_15400000, "f");
                        echo "</td>";
                        echo "<td class='s10' dir='ltr'>";
                        echo db_formatar($nLiqAPagar15410007_15410000, "f");
                        echo "</td>";
                        echo "<td class='s10' dir='ltr'>";
                        echo db_formatar($nLiqAPagar15420007_15420000, "f");
                        echo "</td>";
                        echo "<td class='s10' dir='ltr'>";
                        echo db_formatar($nLiqAPagar15430000, "f");
                        echo "</td>";
                        echo "<td class='s10' dir='ltr'>";
                        echo db_formatar($nLiqAPagarTotal, "f");
                        echo "</td>";
                    ?>

                </tr>
                <tr style="height: 20px">
                    <td class="s32" dir="ltr"></td>
                    <td class="s34" dir="ltr" colspan="4">8 - RESTOS A PAGAR NÃO PROCESSADOS DO EXERCÍCIO</td>
                    <?php
                        $nNaoLiqAPagar15400007_15400000 = 0;
                        $nNaoLiqAPagar15410007_15410000 = 0;
                        $nNaoLiqAPagar15420007_15420000 = 0;
                        $nNaoLiqAPagar15430000 = 0;
                        $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                        if($dtfim == $dtfimExercicio){
                            $nNaoLiqAPagar15400007_15400000 = getEmpenhosApagar(array("'15400007','15400000'"), $dtini, $dtfim, $instits, '');
                            $nNaoLiqAPagar15410007_15410000 = getEmpenhosApagar(array("'15410007','15410000'"), $dtini, $dtfim, $instits, '');
                            $nNaoLiqAPagar15420007_15420000 = getEmpenhosApagar(array("'15420007','15420000'"), $dtini, $dtfim, $instits, '');
                            $nNaoLiqAPagar15430000 = getEmpenhosApagar(array("'15430000'"), $dtini, $dtfim, $instits, '');
                            $nNaoLiqAPagarTotal             = $nNaoLiqAPagar15400007_15400000 + $nNaoLiqAPagar15410007_15410000 + $nNaoLiqAPagar15420007_15420000 + $nNaoLiqAPagar15430000;
                        }
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nNaoLiqAPagar15400007_15400000, "f");
                        echo "</td>";
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nNaoLiqAPagar15410007_15410000, "f");
                        echo "</td>";
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nNaoLiqAPagar15420007_15420000, "f");
                        echo "</td>";
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nNaoLiqAPagar15430000, "f");
                        echo "</td>";
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nNaoLiqAPagarTotal, "f");
                        echo "</td>";
                        $aTotalPago15400007_15400000 = $nLiqAPagar15400007_15400000 + $nNaoLiqAPagar15400007_15400000;
                        $aTotalPago15410007_15410000 = $nLiqAPagar15410007_15410000 + $nNaoLiqAPagar15410007_15410000;
                        $aTotalPago15420007_15420000 = $nLiqAPagar15420007_15420000 + $nNaoLiqAPagar15420007_15420000;
                        $aTotalPago15430000          = $nLiqAPagar15430000 + $nNaoLiqAPagar15430000;
                        $aTotalPago                  = $nLiqAPagarTotal + $nNaoLiqAPagarTotal;
                    ?>
                </tr>
                <tr style="height: 20px">
                    <td class="s5" dir="ltr"></td>
                    <td class="s21" dir="ltr" colspan="4">9 - SUBTOTAL RESTOS A PAGAR (7 + 8)</td>
                    <?php
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($aTotalPago15400007_15400000,"f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($aTotalPago15410007_15410000,"f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($aTotalPago15420007_15420000,"f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($aTotalPago15430000,"f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($aTotalPago,"f");
                        echo "</td>";
                    ?>
                </tr>
                <tr style="height: 20px">
                    <td class="s5" dir="ltr"></td>
                    <td class="s21" dir="ltr" colspan="4">10 - TOTAL (6 + 9)</td>
                    <?php
                        echo "<td class='s22' dir='ltr'>";
                        $nTotalPagoItem10Fonte15400007_15400000 = $pagoFuncao15400007_15400000 + $aTotalPago15400007_15400000;
                        echo db_formatar($nTotalPagoItem10Fonte15400007_15400000, "f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        $nTotalPagoItem10Fonte15410007_15410000 = $pagoFuncao15410007_15410000 + $aTotalPago15410007_15410000;
                        echo db_formatar($nTotalPagoItem10Fonte15410007_15410000, "f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        $nTotalPagoItem10Fonte15420007_15420000 = $pagoFuncao15420007_15420000 + $aTotalPago15420007_15420000;
                        echo db_formatar($nTotalPagoItem10Fonte15420007_15420000, "f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        $nTotalPagoItem10Fonte15430000 = $pagoFuncao15430000          + $aTotalPago15430000;
                        echo db_formatar($nTotalPagoItem10Fonte15430000, "f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        $nTotalPago = $aTotalPago + $pagoFuncao15400007_15400000 + $pagoFuncao15410007_15410000 + $pagoFuncao15420007_15420000 + $pagoFuncao15430000;
                        echo db_formatar($nTotalPago, "f");
                        echo "</td>";
                    ?>
                </tr>
                <tr style="height: 20px">
                    <td class="s0"></td>
                    <td class="s40"></td>
                    <td class="s40"></td>
                    <td class="s40"></td>
                    <td class="s40"></td>
                    <td class="s40"></td>
                    <td class="s40"></td>
                    <td class="s40"></td>
                    <td class="s40"></td>
                    <td class="s2"></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="4">11 - RESTOS A PAGAR INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA</td>
                    <?php
                    $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                    if($dtfim == $dtfimExercicio){
                        $rpexerciciosanteriores15400007_15400000   = 0;
                        $RPdeExerciciosAnteriores15400007_15400000 = getRestoaPagardeExerciciosAnteriores("'118','119','15400007','15400000'", $dtini, $dtfim, $instits);

                        foreach ($RPdeExerciciosAnteriores15400007_15400000 as $restos) {
                            $rpexerciciosanteriores15400007_15400000 +=  $restos->VlrroexercicioAnteriores;
                        }

                        $rpexerciciosanteriores15410007_15410000   = 0;
                        $RPdeExerciciosAnteriores15410007_15410000 = getRestoaPagardeExerciciosAnteriores("'15410007','15410000'", $dtini, $dtfim, $instits);

                        foreach ($RPdeExerciciosAnteriores15410007_15410000 as $restos) {
                            $rpexerciciosanteriores15410007_15410000 +=  $restos->VlrroexercicioAnteriores;
                        }

                        $rpexerciciosanteriores15420007_15420000   = 0;
                        $RPdeExerciciosAnteriores15420007_15420000 = getRestoaPagardeExerciciosAnteriores("'15420007','15420000'", $dtini, $dtfim, $instits);

                        foreach ($RPdeExerciciosAnteriores15420007_15420000 as $restos) {
                            $rpexerciciosanteriores15420007_15420000 +=  $restos->VlrroexercicioAnteriores;
                        }

                        $rpexerciciosanteriores15430000  = 0;
                        $RPdeExerciciosAnteriores15430000= getRestoaPagardeExerciciosAnteriores("'15430000'", $dtini, $dtfim, $instits);

                        foreach ($RPdeExerciciosAnteriores15430000 as $restos) {
                            $rpexerciciosanteriores15430000 +=  $restos->VlrroexercicioAnteriores;
                        }

                        $nRPIncritosSemDesponibilidade15410007_15410000 = 0;
                        $nRPIncritosSemDesponibilidade15420007_15420000 = 0;
                        $nRPIncritosSemDesponibilidade15400007_15400000 = 0;
                        $nRPIncritosSemDesponibilidade15430000 = 0;
                        $nRPIncritosSemDesponibilidadeTotal = 0;


                        $nSaldoFonteAno15400007_15400000 = getSaldoPlanoContaFonte("'15400007','15400000'", $dtini, $dtfim, $instits);

                        $nRPSemDesponibilidade15400007_15400000 = $aTotalPago15400007_15400000 - $nSaldoFonteAno15400007_15400000;
                        $nRPSemDesponibilidade15410007_15410000 = $aTotalPago15410007_15410000 - $nSaldoFonteAno15400007_15400000;
                        $nRPSemDesponibilidade15420007_15420000 = $aTotalPago15420007_15420000 - $nSaldoFonteAno15400007_15400000;
                        $nRPSemDesponibilidade15430000          = $aTotalPago15430000 - $nSaldoFonteAno15400007_15400000;

                        $nRPIncritosSemDesponibilidadeTotal = $aTotalPago15400007_15400000 + $aTotalPago15410007_15410000 + $aTotalPago15420007_15420000 + $aTotalPago15430000 - $nSaldoFonteAno15400007_15400000;

                            if($nRPIncritosSemDesponibilidadeTotal > 0){

                                $nSaldoFonteAno15400007_15400000 = getSaldoPlanoContaFonte("'15400007','15400000'", $dtini, $dtfim, $instits);
                                $nSaldoFonteAno15410007_15410000 = getSaldoPlanoContaFonte("'15410007','15410000'", $dtini, $dtfim, $instits);
                                $nSaldoFonteAno15420007_15420000 = getSaldoPlanoContaFonte("'15420007','15420000'", $dtini, $dtfim, $instits);
                                $nSaldoFonteAno15430000          = getSaldoPlanoContaFonte("'15430000'", $dtini, $dtfim, $instits);

                                $nRPSemDesponibilidade15400007_15400000 = $aTotalPago15400007_15400000 - $nSaldoFonteAno15400007_15400000;
                                $nRPSemDesponibilidade15410007_15410000 = $aTotalPago15410007_15410000 - $nSaldoFonteAno15410007_15410000;
                                $nRPSemDesponibilidade15420007_15420000 = $aTotalPago15420007_15420000 - $nSaldoFonteAno15420007_15420000;
                                $nRPSemDesponibilidade15430000          = $aTotalPago15430000 - $nSaldoFonteAno15430000;
                            }

                            if($nRPSemDesponibilidade15400007_15400000 > 0){
                                $nRPIncritosSemDesponibilidade15400007_15400000 = $nRPSemDesponibilidade15400007_15400000;
                            }
                            if($nRPSemDesponibilidade15410007_15410000 > 0){
                                $nRPIncritosSemDesponibilidade15410007_15410000 = $nRPSemDesponibilidade15410007_15410000;
                            }
                            if($nRPSemDesponibilidade15420007_15420000 > 0){
                                $nRPIncritosSemDesponibilidade15420007_15420000 = $nRPSemDesponibilidade15420007_15420000;
                            }
                            if($nRPSemDesponibilidade15430000 > 0){
                                $nRPIncritosSemDesponibilidade15430000 = $nRPSemDesponibilidade15430000;
                            }

                        $totalLInha11 = $nRPIncritosSemDesponibilidade15400007_15400000 + $nRPIncritosSemDesponibilidade15430000 + $nRPIncritosSemDesponibilidade15410007_15410000 + $nRPIncritosSemDesponibilidade15420007_15420000 + $rpexerciciosanteriores15400007_15400000 + $rpexerciciosanteriores15410007_15410000 + $rpexerciciosanteriores15420007_15420000 + $rpexerciciosanteriores15430000 ;
                        }
                    ?>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nRPIncritosSemDesponibilidade15400007_15400000 + $rpexerciciosanteriores15400007_15400000,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nRPIncritosSemDesponibilidade15410007_15410000 + $rpexerciciosanteriores15410007_15410000,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nRPIncritosSemDesponibilidade15420007_15420000 + $rpexerciciosanteriores15420007_15420000,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nRPIncritosSemDesponibilidade15430000 + $rpexerciciosanteriores15430000,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($totalLInha11,"f"); ?></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s8" dir="ltr"></td>
                    <td class="s45" dir="ltr" colspan="4">12 - RESTOS A PAGAR DE EXERCÍCIOS ANTERIORES SEM DISPONIBILIDADE
                        FINANCEIRA PAGOS NO EXERCÍCIO ATUAL (CONSULTA 932736)</td>
                    <?php

                        $nRPNPAnteriorSemDispFonte15400007_15400000 = getRestosSemDisponilibidadeFundeb(array("'15400007','15400000'"), $dtini, $dtfim, $instits);
                        $nRPNPAnteriorSemDispFonte15410007_15410000 = 0;//getRestosSemDisponilibidade(array("'15400000'"), $dtini, $dtfim, $instits);
                        $nRPNPAnteriorSemDispFonte15420007_15420000 = getRestosSemDisponilibidadeFundeb(array("'15420007','15420000'"), $dtini, $dtfim, $instits);
                        $nRPNPAnteriorSemDispFonte15430000 = getRestosSemDisponilibidadeFundeb(array("'15430000'"), $dtini, $dtfim, $instits);
                        $totalLinha12 = $nRPNPAnteriorSemDispFonte15400007_15400000 + $nRPNPAnteriorSemDispFonte15430000 + $nRPNPAnteriorSemDispFonte15420007_15420000 + $nRPNPAnteriorSemDispFonte15410007_15410000;
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nRPNPAnteriorSemDispFonte15400007_15400000,"f");
                        echo "</td>";
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nRPNPAnteriorSemDispFonte15410007_15410000,"f");
                        echo "</td>";
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nRPNPAnteriorSemDispFonte15420007_15420000,"f");
                        echo "</td>";
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($nRPNPAnteriorSemDispFonte15430000,"f");
                        echo "</td>";
                        echo "<td class='s38' dir='ltr'>";
                        echo db_formatar($totalLinha12,"f");
                        echo "</td>";
                    ?>
                </tr>
                <tr style="height: 20px">
                    <td class="s5" dir="ltr"></td>
                    <td class="s21" dir="ltr" colspan="4">13 - TOTAL (10 - 11 + 12)</td>
                    <?php
                        $nTotalPagoItem13Fonte15400007_15400000 =  ($nTotalPagoItem10Fonte15400007_15400000 - $nRPIncritosSemDesponibilidade15400007_15400000 - $rpexerciciosanteriores15400007_15400000) + ($nRPNPAnteriorSemDispFonte15400007_15400000);
                        $nTotalPagoItem13Fonte15410007_15410000 =  ($nTotalPagoItem10Fonte15410007_15410000 - $nRPIncritosSemDesponibilidade15410007_15410000 - $rpexerciciosanteriores15410007_15410000) + ($nRPNPAnteriorSemDispFonte15410007_15410000);
                        $nTotalPagoItem13Fonte15420007_15420000 =  ($nTotalPagoItem10Fonte15420007_15420000 - $nRPIncritosSemDesponibilidade15420007_15420000 - $rpexerciciosanteriores15420007_15420000) + ($nRPNPAnteriorSemDispFonte15420007_15420000);
                        $nTotalPagoItem13Fonte15430000          =  ($nTotalPagoItem10Fonte15430000 - $nRPIncritosSemDesponibilidade15430000) + ($nRPNPAnteriorSemDispFonte15430000);
                        $totallinha13                           = $nTotalPago - $totalLInha11 + $totalLinha12;
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($nTotalPagoItem13Fonte15400007_15400000,"f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($nTotalPagoItem13Fonte15410007_15410000,"f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($nTotalPagoItem13Fonte15420007_15420000,"f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($nTotalPagoItem13Fonte15430000,"f");
                        echo "</td>";
                        echo "<td class='s22' dir='ltr'>";
                        echo db_formatar($totallinha13,"f");
                        echo "</td>";
                    ?>
                </tr>
            </table>
        </div>
        <div  style="page-break-before: always;">
            <table class="waffle no-grid" cellspacing="0" cellpadding="0">
                <tr style="height: 20px">
                    <td class="s27">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                    <td class="s28">&nbsp;</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s0" dir="ltr"></td>
                    <td class="s2" dir="ltr" colspan="9">III - BASE DE CÁLCULO DOS INDICADORES - Art. 212-A, inciso XI e § 3º - Constituição Federal</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s3" dir="ltr"></td>
                    <td class="s4" dir="ltr" colspan="6" style="text-align: center; vertical-align: middle;">DESCRIÇÃO</td>
                    <td class="s4" dir="ltr">TOTAL DAS DESPESAS<br>CUSTEADAS<br>COM FUNDEB - VAAT<br>APLICADAS EM <br> DESPESA DE CAPITAL </td>
                    <td class="s4" dir="ltr">TOTAL DAS DESPESAS<br>CUSTEADAS<br>COM FUNDEB - VAAT<br>APLICADAS NA <br> EDUCAÇÃO INFANTIL</td>
                    <td class="s4" dir="ltr">TOTAL DAS DESPESAS<br>DO FUNDEB<br>COMPROFISSIONAIS DA<br>EDUCAÇÃO <br> BÁSICA </td>
                </tr>
                <?php
                foreach($aFuncoes as $iFuncao){
                     $aSubFuncoes = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o15_codigo in (".implode(",",array("'15420007'")).") and o58_instit in ($instits) and o58_elemento like '344%' group by 1,2 order by 1");
                     foreach ($aSubFuncoes as $oSubFuncao) {

                         $aSubFuncao15420007 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15420007'")).") and o58_instit in ($instits) and o58_elemento like '344%' group by 1,2 order by 1");
                         $nValorPagoSubFuncao15420007= count( $aSubFuncao15420007) > 0 ? $aSubFuncao15420007[0]->pago : 0;
                         $nValorLinha14_15420007 += $nValorPagoSubFuncao15420007;

                     }

                     $aSubFuncoes = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao = 365 and  o15_codigo in (".implode(",",array("'15420007'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                     foreach ($aSubFuncoes as $oSubFuncao) {

                         $aSubFuncao15420007 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao = 365 and o15_codigo in (".implode(",",array("'15420007'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                         $nValorPagoSubFuncao15420007= count( $aSubFuncao15420007) > 0 ? $aSubFuncao15420007[0]->pago : 0;
                         $nValorLinha14_2_15420007 += $nValorPagoSubFuncao15420007;

                     }

                     $aSubFuncoes = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o15_codigo in (".implode(",",array("'15400007','15420007'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                     foreach ($aSubFuncoes as $oSubFuncao) {

                        $aSubFuncao15420007 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'15400007','15420007'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                        $nValorPagoSubFuncao15420007= count( $aSubFuncao15420007) > 0 ? $aSubFuncao15420007[0]->pago : 0;
                        $nValorLinha14_3_15420007 += $nValorPagoSubFuncao15420007;

                    }
                }
                ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">14 - VALOR PAGO</td>
                    <td class="s10" dir="ltr" ><?php echo db_formatar($nValorLinha14_15420007, "f");?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorLinha14_2_15420007, "f");?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorLinha14_3_15420007, "f");?></td>
                </tr>
                <?php
                      $nLiqAPagarLinha15_1 = 0;
                      $nLiqAPagarLinha15_2 = 0;
                      $nLiqAPagarLinha15_3 = 0;
                      $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                      if($dtfim == $dtfimExercicio){
                          $nLiqAPagarLinha15_1 = getEmpenhosApagar(array("'15420007'"), $dtini, $dtfim, $instits, 'lqd');
                          $nLiqAPagarLinha15_2 = getEmpenhosApagar(array("'15420007'"), $dtini, $dtfim, $instits, 'lqd');
                          $nLiqAPagarLinha15_3 = getEmpenhosApagar(array("'15400007','15420007'"), $dtini, $dtfim, $instits, 'lqd');
                      }

                      $nNaoLiqAPagarLinha15_1 = 0;
                      $nNaoLiqAPagarLinha15_2 = 0;
                      $nNaoLiqAPagarLinha15_3 = 0;
                      $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                      if($dtfim == $dtfimExercicio){
                        $nNaoLiqAPagarLinha15_1 = getEmpenhosApagar(array("'15420007'"), $dtini, $dtfim, $instits, '');
                        $nNaoLiqAPagarLinha15_2 = getEmpenhosApagar(array("'15420007'"), $dtini, $dtfim, $instits, '');
                        $nNaoLiqAPagarLinha15_3 = getEmpenhosApagar(array("'15400007','15420007'"), $dtini, $dtfim, $instits, '');
                      }
                      $Linha15_1 = $nLiqAPagarLinha15_1 + $nNaoLiqAPagarLinha15_1;
                      $Linha15_2 = $nLiqAPagarLinha15_2 + $nNaoLiqAPagarLinha15_2;
                      $Linha15_3 = $nLiqAPagarLinha15_3 + $nNaoLiqAPagarLinha15_3;

                ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">15 - RESTOS A PAGAR (PROCESSADOS E NÃO PROCESSADOS) DO EXERCÍCIO</td>
                    <td class="s10" dir="ltr" ><?php echo db_formatar($Linha15_1, "f");?></td>
                    <td class="s10" dir="ltr" ><?php echo db_formatar($Linha15_2, "f");?></td>
                    <td class="s10" dir="ltr" ><?php echo db_formatar($Linha15_3, "f");?></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">16 - (-) RESTOS A PAGAR INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA</td>
                        <?php
                            $nLiqAPagarLinha16_1 = 0;
                            $nLiqAPagarLinha16_2 = 0;
                            $nLiqAPagarLinha16_3 = 0;
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            if($dtfim == $dtfimExercicio){
                                $nLiqAPagarLinha16_1 = getEmpenhosApagar(array("'15420007'"), $dtini, $dtfim, $instits, 'lqd');
                                $nLiqAPagarLinha16_2 = getEmpenhosApagar(array("'15420007'"), $dtini, $dtfim, $instits, 'lqd');
                                $nLiqAPagarLinha16_3 = getEmpenhosApagar(array("'15400007','15420007'"), $dtini, $dtfim, $instits, 'lqd');
                            }
                            $nNaoLiqAPagarLinha16_1 = 0;
                            $nNaoLiqAPagarLinha16_2 = 0;
                            $nNaoLiqAPagarLinha16_3 = 0;
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            if($dtfim == $dtfimExercicio){
                                $nNaoLiqAPagarLinha16_1 = getEmpenhosApagar(array("'15420007'"), $dtini, $dtfim, $instits, '');
                                $nNaoLiqAPagarLinha16_2 = getEmpenhosApagar(array("'15420007'"), $dtini, $dtfim, $instits, '');
                                $nNaoLiqAPagarLinha16_3 = getEmpenhosApagar(array("'15400007','15420007'"), $dtini, $dtfim, $instits, '');
                            }
                            $aTotalPagoLinha16_1 = $nLiqAPagarLinha16_1 + $nNaoLiqAPagarLinha16_1;
                            $aTotalPagoLinha16_2 = $nLiqAPagarLinha16_2 + $nNaoLiqAPagarLinha16_2;
                            $aTotalPagoLinha16_3 = $nLiqAPagarLinha16_3 + $nNaoLiqAPagarLinha16_3;

                            $nRPIncritosSemDesponibilidadeLinha16_2 = 0;
                            $nRPIncritosSemDesponibilidadeLinha16_3 = 0;
                            $nRPIncritosSemDesponibilidadeLinha16_1 = 0;
                            $nRPIncritosSemDesponibilidadeTotal = 0;
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            if($dtfim == $dtfimExercicio){

                                 $nSaldoFonteAno118 = getSaldoPlanoContaFonte("'118','1118','15400007','119','1119','15400000','166','1166','15420007','167','1167','15420000'", $dtini, $dtfim, $instits);

                                 $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                                 $nRPSemDesponibilidadeLinha16_1 = $aTotalPagoLinha16_1 - $nSaldoFonteAno118;
                                 $nRPSemDesponibilidadeLinha16_2 = $aTotalPagoLinha16_2 - $nSaldoFonteAno118;
                                 $nRPSemDesponibilidadeLinha16_3 = $aTotalPagoLinha16_3 - $nSaldoFonteAno118;

                                 $nRPIncritosSemDesponibilidadeTotal = $aTotalPagoLinha16_1 + $aTotalPagoLinha16_2 + $aTotalPagoLinha16_3 + $aTotalPago167 - $nSaldoFonteAno118;

                                 if($nRPIncritosSemDesponibilidadeTotal > 0){

                                     $nSaldoFonteAnoLinha16_1 = getSaldoPlanoContaFonte("'15420007'", $dtini, $dtfim, $instits);
                                     $nSaldoFonteAnoLinha16_2 = getSaldoPlanoContaFonte("'15420007'", $dtini, $dtfim, $instits);
                                     $nSaldoFonteAnoLinha16_3 = getSaldoPlanoContaFonte("'15400007','15420007'", $dtini, $dtfim, $instits);


                                     $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                                     $nRPSemDesponibilidadeLinha16_1 = $aTotalPagoLinha16_1 - $nSaldoFonteAnoLinha16_1;
                                     $nRPSemDesponibilidadeLinha16_2 = $aTotalPagoLinha16_2 - $nSaldoFonteAnoLinha16_2;
                                     $nRPSemDesponibilidadeLinha16_3 = $aTotalPagoLinha16_3 - $nSaldoFonteAnoLinha16_3;
                                 }

                                 if($nRPSemDesponibilidadeLinha16_1 > 0){
                                     $nRPIncritosSemDesponibilidadeLinha16_1 = $nRPSemDesponibilidadeLinha16_1;
                                 }
                                 if($nRPSemDesponibilidadeLinha16_2 > 0){
                                     $nRPIncritosSemDesponibilidadeLinha16_2 = $nRPSemDesponibilidadeLinha16_2;
                                 }
                                 if($nRPSemDesponibilidadeLinha16_3 > 0){
                                     $nRPIncritosSemDesponibilidadeLinha16_3 = $nRPSemDesponibilidadeLinha16_3 + $rpexerciciosanteriores15400007_15400000;
                                 }

                            }

                            echo "<td class='s10' dir='ltr'>"; echo db_formatar($nRPIncritosSemDesponibilidadeLinha16_1,"f"); echo "</td>";
                            echo "<td class='s10' dir='ltr'>"; echo db_formatar($nRPIncritosSemDesponibilidadeLinha16_2,"f"); echo "</td>";
                            echo "<td class='s10' dir='ltr'>"; echo db_formatar($nRPIncritosSemDesponibilidadeLinha16_3,"f"); echo "</td>";

                        ?>
                </tr>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">17 - RESTOS A PAGAR DE EXERCÍCIOS ANTERIORES SEM DISPONIBILIDADE DE CAIXA<br>PAGOS NO EXERCÍCIO ATUAL (CONSULTA 932.736)</td>
                        <?php
                            $nRPNPAnteriorSemDispFonteLinha17_1 = getRestosSemDisponilibidadeFundeb(array("'15420007'"), $dtini, $dtfim, $instits);
                            $nRPNPAnteriorSemDispFonteLinha17_2 = getRestosSemDisponilibidadeFundeb(array("'15420007'"), $dtini, $dtfim, $instits);
                            $nRPNPAnteriorSemDispFonteLinha17_3 = getRestosSemDisponilibidadeFundeb(array("'15400007','15420007'"), $dtini, $dtfim, $instits);
                            echo "<td class='s10' dir='ltr'>"; echo db_formatar($nRPNPAnteriorSemDispFonteLinha17_1,"f"); echo "</td>";
                            echo "<td class='s10' dir='ltr'>"; echo db_formatar($nRPNPAnteriorSemDispFonteLinha17_2,"f"); echo "</td>";
                            echo "<td class='s10' dir='ltr'>"; echo db_formatar($nRPNPAnteriorSemDispFonteLinha17_3,"f"); echo "</td>";

                        ?>
                </tr>
                <tr style="height: 20px">
                    <td class="s5" dir="ltr"></td>
                    <td class="s21" dir="ltr" colspan="6">18 - TOTAL (14 + 15 - 16 + 17)</td>
                    <td class="s22" dir="ltr"><?php echo db_formatar($nValorLinha14_15420007 + $Linha15_1 - $nRPIncritosSemDesponibilidadeLinha16_1 + $nRPNPAnteriorSemDispFonteLinha17_1,"f"); ?></td>
                    <td class="s22" dir="ltr"><?php echo db_formatar($nValorLinha14_2_15420007 + $Linha15_2 - $nRPIncritosSemDesponibilidadeLinha16_2 + $nRPNPAnteriorSemDispFonteLinha17_2,"f"); ?></td>
                    <td class="s22" dir="ltr"><?php echo db_formatar($nValorLinha14_3_15420007 + $Linha15_3 - $nRPIncritosSemDesponibilidadeLinha16_3  + $nRPNPAnteriorSemDispFonteLinha17_3,"f"); ?></td>
                </tr>
            </table>
        </div>
        <div class="">
            <table class="waffle no-grid" cellspacing="0" cellpadding="0">
                <tr style="height: 20px">
                    <td class="s0" dir="ltr"></td>
                    <td class="s2" dir="ltr" colspan="9">IV - INDICADORES - Art. 212-A, inciso XI e § 3º - Constituição Federal</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s3" dir="ltr"></td>
                    <td class="s4" dir="ltr" colspan="6">DESCRIÇÃO</td>
                    <td class="s4" dir="ltr">VALOR EXIGIDO</td>
                    <td class="s4" dir="ltr">VALOR APLICADO</td>
                    <td class="s4" dir="ltr">% APLICADO</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">19 - MÍNIMO DE 70% DO FUNDEB NA REMUNERAÇÃO DOS PROFISSIONAIS DA EDUCAÇÃO BÁSICA </td>
                        <?php
                            foreach($aFuncoes as $iFuncao){
                                $aSubFuncoes = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o15_codigo in (".implode(",",array("'118','119','166','167','1118','1119','1166','1167','15400007','15400000','15420007','15420000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                                foreach ($aSubFuncoes as $oSubFuncao) {
                                    $aSubFuncao118 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'118','1118','15400007'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                                    $nValorPagoSubFuncao118 = count( $aSubFuncao118) > 0 ? $aSubFuncao118[0]->pago : 0;
                                    $pagoFuncao118 += $nValorPagoSubFuncao118;
                                    $aSubFuncao119 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'119','1119','15400000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                                    $nValorPagoSubFuncao119 = count( $aSubFuncao119) > 0 ? $aSubFuncao119[0]->pago : 0;
                                    $pagoFuncao119 += $nValorPagoSubFuncao119;
                                    $aSubFuncao166 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'166','1166','15420007'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                                    $nValorPagoSubFuncao166 = count( $aSubFuncao166) > 0 ? $aSubFuncao166[0]->pago : 0;
                                    $pagoFuncao166 += $nValorPagoSubFuncao166;
                                    $aSubFuncao167 = getSaldoDespesa(null, "o58_subfuncao, o58_anousu, coalesce(sum(pago),0) as pago ", null, "o58_funcao = {$iFuncao} and o58_subfuncao in ({$oSubFuncao->o58_subfuncao}) and o15_codigo in (".implode(",",array("'167','1167','15420000'")).") and o58_instit in ($instits) group by 1,2 order by 1");
                                    $nValorPagoSubFuncao167 = count( $aSubFuncao167) > 0 ? $aSubFuncao167[0]->pago : 0;
                                    $pagoFuncao167 += $nValorPagoSubFuncao167;
                                }
                            }

                            $nLiqAPagar118 = 0;
                            $nLiqAPagar119 = 0;
                            $nLiqAPagar166 = 0;
                            $nLiqAPagar167 = 0;
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            if($dtfim == $dtfimExercicio){
                                $nLiqAPagar118 = getEmpenhosApagar(array("'118','1118','15400007'"), $dtini, $dtfim, $instits, 'lqd');
                                $nLiqAPagar119 = getEmpenhosApagar(array("'119','1119','15400000'"), $dtini, $dtfim, $instits, 'lqd');
                                $nLiqAPagar166 = getEmpenhosApagar(array("'166','1166','15420007'"), $dtini, $dtfim, $instits, 'lqd');
                                $nLiqAPagar167 = getEmpenhosApagar(array("'167','1167','15420000'"), $dtini, $dtfim, $instits, 'lqd');

                            }

                            $nNaoLiqAPagar118 = 0;
                            $nNaoLiqAPagar119 = 0;
                            $nNaoLiqAPagar166 = 0;
                            $nNaoLiqAPagar167 = 0;
                            $dtfimExercicio = db_getsession("DB_anousu")."-12-31";
                            if($dtfim == $dtfimExercicio){
                                $nNaoLiqAPagar118 = getEmpenhosApagar(array("'118','1118','15400007'"), $dtini, $dtfim, $instits, '');
                                $nNaoLiqAPagar119 = getEmpenhosApagar(array("'119','1119','15400000'"), $dtini, $dtfim, $instits, '');
                                $nNaoLiqAPagar166 = getEmpenhosApagar(array("'166','1166','15420007'"), $dtini, $dtfim, $instits, '');
                                $nNaoLiqAPagar167 = getEmpenhosApagar(array("'167','1167','15420000'"), $dtini, $dtfim, $instits, '');

                            }

                            $aTotalPago118 = $nLiqAPagar118 + $nNaoLiqAPagar118;
                            $aTotalPago119 = $nLiqAPagar119 + $nNaoLiqAPagar119;
                            $aTotalPago166 = $nLiqAPagar166 + $nNaoLiqAPagar166;
                            $aTotalPago167 = $nLiqAPagar167 + $nNaoLiqAPagar167;

                            $nTotalPagoItem10Fonte118 = $pagoFuncao118 + $aTotalPago118;
                            $nTotalPagoItem10Fonte119 = $pagoFuncao119 + $aTotalPago119;
                            $nTotalPagoItem10Fonte166 = $pagoFuncao166 + $aTotalPago166;
                            $nTotalPagoItem10Fonte167 = $pagoFuncao167 + $aTotalPago167;

                            $nTotalPagoItem13Fonte118 =  ($nTotalPagoItem10Fonte118 - $nRPIncritosSemDesponibilidade118) + ($nRPNPAnteriorSemDispFonte118);
                            $nTotalPagoItem13Fonte119 =  ($nTotalPagoItem10Fonte119 - $nRPIncritosSemDesponibilidade119) + ($nRPNPAnteriorSemDispFonte119);
                            $nTotalPagoItem13Fonte166 =  ($nTotalPagoItem10Fonte166 - $nRPIncritosSemDesponibilidade166) + ($nRPNPAnteriorSemDispFonte166);
                            $nTotalPagoItem13Fonte167 =  ($nTotalPagoItem10Fonte167 - $nRPIncritosSemDesponibilidade167) + ($nRPNPAnteriorSemDispFonte167);

                            $nTotalAplicadoRemuProfEducBasica = $nTotalPagoItem13Fonte118 + $nTotalPagoItem13Fonte166 - $rpexerciciosanteriores15400007_15400000;

                            $nDevolucaoFundeb = getDevolucaoRecursoFundeb($dtini, $dtfim, $instits);

                            $aReceitasImpostos = array(
                                array('1 - FUNDEB - IMPOSTOS E TRANSFERÊNCIAS DE IMPOSTOS', 'title', array('417515001%','41321010%', '41321020%','41321030%','41321050%','41329990%','41922510%',), "'118','119','1118','1119','15400007','15400000'"),
                                array('1.1 - TRANSFERÊNCIAS DE RECURSOS DO FUNDO DE MANUTENÇÃO E DESENVOLVIMENTO DA EDUCAÇÃO BÁSICA E DE VALORIZAÇÃO DOS PROFISSIONAIS DA EDUCAÇÃO - FUNDEB (NR 1.7.5.1.50.0.1 )', 'text', array('417515001%'), "'118','119','1118','1119','15400007','15400000'"),
                                array('1.2 - RENDIMENTOS DE APLICAÇÃO FINANCEIRA (NR 1.3.2.1.01.0.X + NR 1.3.2.1.02.0.X + NR 1.3.2.1.03.0.X + NR 1.3.2.1.05.0.X + NR 1.3.2.9.99.0.X)', 'text', array('41321010%', '41321020%','41321030%','41321050%','41329990%'), "'118','119','1118','1119','15400007','15400000'"),
                                array('1.3 - RESSARCIMENTO DE RECURSOS DO FUNDEB (NR 1.9.2.2.51.0.X)', 'text', array('41922510%'), "'118','119','1118','1119','15400007','15400000'"),
                                array('3 - FUNDEB - COMPLEMENTAÇÃO DA UNIÃO - VAAT', 'title', array('417155001%','41321010%','41321020%','41321030%','41321050%','41329990%','41922510%'), "'166','167','1166','1167','15420007','15420000'"),
                                array('3.1 - TRANSFERÊNCIAS DE RECURSOS DA COMPLEMENTAÇÃO DA UNIÃO AO FUNDO DE MANUTENÇÃO E DESENVOLVIMENTO DA EDUCAÇÃO BÁSICA E DE VALORIZAÇÃO DOS PROFISSIONAIS DA EDUCAÇÃO - FUNDEB (VAAT) (NR 1.7.1.5.50.0.1)', 'text', array('417155001%'), "'166','167','1166','1167','15420007','15420000'"),
                                array('3.2 - RENDIMENTOS DE APLICAÇÃO FINANCEIRA (NR 1.3.2.1.01.0.X + NR 1.3.2.1.02.0.X + NR 1.3.2.1.03.0.X + NR 1.3.2.1.05.0.X + NR 1.3.2.9.99.0.X)', 'text', array('41321010%','41321020%','41321030%','41321050%','41329990%'), "'166','167','1166','1167','15420007','15420000'"),
                                array('3.3 - RESSARCIMENTO DE RECURSOS DO FUNDEB (NR 1.9.2.2.51.0.X)', 'text', array('41922510%'), "'166','167','1166','1167','15420007','15420000'"),
                             );
                             foreach ($aReceitasImpostos as $receita) {
                                if ($receita[1] == 'title') {
                                    $nReceita = getValorNaturezaReceita($receita[2], $receita[3], $anousu, $dtini, $dtfim, $instits);
                                    $nTotalReceitaRecurso1_3 += $nReceita;
                                }
                             }
                            $nReceitaTotalFundeb = $nTotalReceitaRecurso1_3 - $nDevolucaoFundeb;
                            echo "<td class='s10' dir='ltr'>";echo db_formatar($nReceitaTotalFundeb * 0.70,"f"); echo "</td>";
                            echo "<td class='s10' dir='ltr'>";echo db_formatar($nValorLinha14_3_15420007 + $Linha15_3 - $nRPIncritosSemDesponibilidadeLinha16_3 + $nRPNPAnteriorSemDispFonteLinha17_3,"f"); echo "</td>";
                            echo "<td class='s10' dir='ltr'>"; echo db_formatar(((($nValorLinha14_3_15420007 + $Linha15_3 - $nRPIncritosSemDesponibilidadeLinha16_3 + $nRPNPAnteriorSemDispFonteLinha17_3)) / $nReceitaTotalFundeb )*100,"f")."%"; echo "</td>";
                        ?>
                </tr>

                <?php
                    $nFundebComplementacaoAplicadoEmpenho = getPagamentoComplementacaoInfantil($dtini, $dtfim, $instits);
                    $nFundebComplementacaoAplicadoRestosaPagar = getRestosaPagarComplementacaoInfantil($dtini, $dtfim, $instits);
                    $nTotalAplicadoItem20 = $nFundebComplementacaoAplicadoEmpenho + $nFundebComplementacaoAplicadoRestosaPagar;

                    $aReceitasImpostos = array(
                        array('3 - FUNDEB - COMPLEMENTAÇÃO DA UNIÃO - VAAT', 'title', array('417155001%','41321010%','41321020%','41321030%','41321050%','41329990%','41922510%'), "'166','167','1166','1167','15420007','15420000'"),
                        array('3.1 - TRANSFERÊNCIAS DE RECURSOS DA COMPLEMENTAÇÃO DA UNIÃO AO FUNDO DE MANUTENÇÃO E DESENVOLVIMENTO DA EDUCAÇÃO BÁSICA E DE VALORIZAÇÃO DOS PROFISSIONAIS DA EDUCAÇÃO - FUNDEB (VAAT) (NR 1.7.1.5.50.0.1)', 'text', array('417155001%'), "'166','167','1166','1167','15420007','15420000'"),
                        array('3.2 - RENDIMENTOS DE APLICAÇÃO FINANCEIRA (NR 1.3.2.1.01.0.X + NR 1.3.2.1.02.0.X + NR 1.3.2.1.03.0.X + NR 1.3.2.1.05.0.X + NR 1.3.2.9.99.0.X)', 'text', array('41321010%','41321020%','41321030%','41321050%','41329990%'), "'166','167','1166','1167','15420007','15420000'"),
                        array('3.3 - RESSARCIMENTO DE RECURSOS DO FUNDEB (NR 1.9.2.2.51.0.X)', 'text', array('41922510%'), "'166','167','1166','1167','15420007','15420000'"),
                     );
                     foreach ($aReceitasImpostos as $receita) {
                        if ($receita[1] == 'title') {
                            $nReceita = getValorNaturezaReceita($receita[2], $receita[3], $anousu, $dtini, $dtfim, $instits);
                            $nTotalReceitaRecursovaat += $nReceita;
                        }
                     }

                ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">20 - PERCENTUAL DE 50% DA COMPLEMENTAÇÃO DA UNIÃO AO FUNDEB - VAAT NA EDUCAÇÃO INFANTIL </td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nTotalReceitaRecursovaat * 0.50,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nTotalAplicadoItem20,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nTotalAplicadoItem20 * 100 / $nTotalReceitaRecursovaat,"f")."%"; ?></td>
                </tr>
                <?php
                     $nFundebComplementacaoAplicadoEmpenho = getPagamentoComplementacaoCapital($dtini, $dtfim, $instits);
                     $nFundebComplementacaoAplicadoRestosaPagar = getRestosaPagarComplementacaoCapital($dtini, $dtfim, $instits);
                     $TotalAplicadoItem21 = $nFundebComplementacaoAplicadoEmpenho+$nFundebComplementacaoAplicadoRestosaPagar;
                ?>
                  <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s19" dir="ltr" colspan="6">21 - MÍNIMO DE 15% DA COMPLEMENTAÇÃO DA UNIÃO AO FUNDEB - VAAT EM DESPESAS DE CAPITAL</td>
                    <td class="s20" dir="ltr"><?php echo db_formatar($nTotalReceitaRecursovaat * 0.15,"f"); ?></td>
                    <td class="s20" dir="ltr"><?php echo db_formatar($TotalAplicadoItem21,"f"); ?></td>
                    <td class="s20" dir="ltr"><?php echo db_formatar($TotalAplicadoItem21 * 100 / $nTotalReceitaRecursovaat,"f")."%"; ?></td>
                </tr>

                <tr style="height: 20px">
                    <td class="s27"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s0" dir="ltr"></td>
                    <td class="s2" dir="ltr" colspan="9">V - INDICADOR - Art.25, § 3º - Lei nº 14.113, de 2020 - (Máximo de 10% de Superávit)</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s3" dir="ltr"></td>
                    <td class="s4" dir="ltr" colspan="6">DESCRIÇÃO</td>
                    <td class="s4" dir="ltr">VALOR MÁXIMO PERMITIDO</td>
                    <td class="s4" dir="ltr">VALOR NÃO APLICADO</td>
                    <td class="s4" dir="ltr">% NÃO APLICADO</td>
                </tr>
                <?php
                     $nFundebImpostosTransferencias = getValorNaturezaReceita(array('413210101%', '413210501%', '417515001%'), "'118','119','1118','1119','15400007','15400000'", $anousu, $dtini, $dtfim, $instits);
                     $nRestosaPagar = $nTotalPagoItem13Fonte118 + $nTotalPagoItem13Fonte119;
                     $nFundebItem171 = $nFundebImpostosTransferencias - $nDevolucaoFundeb - $nRestosaPagar;

                     $nFundebComplementacao = getValorNaturezaReceita(array('413210101%', '413210501%', '417155001%'), "'166','167','1166','1167','15420007','15420000'", $anousu, $dtini, $dtfim, $instits);
                     $nRestosaPagarFonte166_167 = $nTotalPagoItem13Fonte166 + $nTotalPagoItem13Fonte167;
                     $nFundebItem172 = $nFundebComplementacao - $nRestosaPagarFonte166_167;

                     $linha22_1 = $nTotalReceitaRecurso * 0.10;
                     $linha22_1 = $linha22_1 > 0 ? $linha22_1 : 0 ;
                     $linha22_2 = $nTotalReceitaRecurso - $totallinha13;
                     $linha22_2 = $linha22_2 > 0 ? $linha22_2 : 0 ;
                     $linha22_3 = ($linha22_2 / $nTotalReceitaRecurso) * 100;
                     $linha22_3 = $linha22_3 > 0 ? $linha22_3 : 0 ;
                ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s19" dir="ltr" colspan="6">22 - TOTAL DA RECEITA RECEBIDA E NÃO APLICADA NO EXERCÍCIO </td>
                    <td class="s20" dir="ltr" ><?php echo db_formatar($linha22_1, "f");?></td>
                    <td class="s20" dir="ltr" ><?php echo db_formatar($linha22_2, "f");?></td>
                    <td class="s20" dir="ltr" ><?php echo db_formatar($linha22_3,"f")."%";?></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s27"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                    <td class="s28"></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s0" dir="ltr"></td>
                    <td class="s2" dir="ltr" colspan="9">VI - INDICADOR - Art.25, § 3º - Lei nº 14.113, de 2020 - (Aplicação do Superávit de Exercício Anterior)</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s3" dir="ltr"></td>
                    <td class="s4" dir="ltr" colspan="6" style="text-align: center; vertical-align: middle;">DESCRIÇÃO</td>
                    <td class="s4" dir="ltr">FUNDEB - IMPOSTOS E <br>TRANSFERÊNCIAS DE <br>IMPOSTOS</td>
                    <td class="s4" dir="ltr">FUNDEB - <br>COMPLEMENTAÇÃO DA <br>UNIÃO (VAAF + VAAT + <br>VAAR)</td>
                    <td class="s4" dir="ltr">TOTAL DAS <br>DESPESAS <br>CUSTEADAS COM <br>SUPERÁVIT DO FUNDEB</td>
                </tr>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s28" dir="ltr" colspan="5">23 - VALOR DE SUPERÁVIT PERMITIDO NO EXERCICIO ANTERIOR</td>
                    <td class="s12"></td>
                    <td class="s10" dir="ltr"></td>
                    <td class="s10" dir="ltr"></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($oDadosexecicioanterior->c235_superavit_fundeb_permitido,"f"); ?></td>
                </tr>
                <?php
                    $nValorNaoAplicadoFundebImpostosTransfAnoAnterior = $oDadosexecicioanterior->c235_naoaplicfundebimposttransf;
                    $nValorNaoAplicadoFundebComplementacaoAnoAnterior = $oDadosexecicioanterior->c235_naoaplicfundebcompl;
                ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">24 - VALOR NÃO APLICADO NO EXERCÍCIO ANTERIOR</td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorNaoAplicadoFundebImpostosTransfAnoAnterior,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorNaoAplicadoFundebComplementacaoAnoAnterior,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorNaoAplicadoFundebImpostosTransfAnoAnterior+$nValorNaoAplicadoFundebComplementacaoAnoAnterior,"f"); ?></td>
                </tr>
                <?php
                        $nValorAPlicadoSuperavit218_219 = getDespesasCusteadosComSuperavit(array("'218','219','25400007','25400000'"), $dtini, $dtfim, $instits);
                        $nValorAPlicadoSuperavit266_267 = getDespesasCusteadosComSuperavit(array("'266','267','25420007','25420007'"), $dtini, $dtfim, $instits);
                ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">25 - VALOR DE SUPERÁVIT APLICADO ATÉ O PRIMEIRO QUADRIMESTRE</td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorAPlicadoSuperavit218_219,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorAPlicadoSuperavit266_267,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorAPlicadoSuperavit218_219 + $nValorAPlicadoSuperavit266_267,"f"); ?></td>
                </tr>

                <?php
                        $nValorAPlicadoSuperavitPosPrimQuad218_219 = getDespesasCusteadosComSuperavitPosPrimeiroQuadrimestre(array("'218','219','25400007','25400000'"), $dtini, $dtfim, $instits);
                        $nValorAPlicadoSuperavitPosPrimQuad266_267 = getDespesasCusteadosComSuperavitPosPrimeiroQuadrimestre(array("'266','267','25420007','25420007'"), $dtini, $dtfim, $instits);
                     ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">26 - VALOR APLICADO APÓS O PRIMEIRO QUADRIMESTRE </td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorAPlicadoSuperavitPosPrimQuad218_219,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorAPlicadoSuperavitPosPrimQuad266_267,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($nValorAPlicadoSuperavitPosPrimQuad218_219 + $nValorAPlicadoSuperavitPosPrimQuad266_267,"f"); ?></td>
                </tr>
                <?php
                    $linha27_1 = $nValorNaoAplicadoFundebImpostosTransfAnoAnterior  - $nValorAPlicadoSuperavit218_219 - $nValorAPlicadoSuperavitPosPrimQuad218_219;
                    $linha27_2 = $nValorNaoAplicadoFundebComplementacaoAnoAnterior  - $nValorAPlicadoSuperavit266_267 - $nValorAPlicadoSuperavitPosPrimQuad266_267;
                    $linha27_3 = ($nValorNaoAplicadoFundebImpostosTransfAnoAnterior - $nValorAPlicadoSuperavit218_219 - $nValorAPlicadoSuperavitPosPrimQuad218_219) + ($nValorNaoAplicadoFundebComplementacaoAnoAnterior - $nValorAPlicadoSuperavit266_267 - $nValorAPlicadoSuperavitPosPrimQuad266_267);
                    $linha27_1 = $linha27_1 >= 0 ? $linha27_1 : 0;
                    $linha27_2 = $linha27_2 >= 0 ? $linha27_2 : 0;
                    $linha27_3 = $linha27_3 >= 0 ? $linha27_3 : 0;

                    $linha29_1 = $nValorNaoAplicadoFundebImpostosTransfAnoAnterior - $nValorAPlicadoSuperavit218_219;
                    $linha29_2 = $nValorNaoAplicadoFundebComplementacaoAnoAnterior - $nValorAPlicadoSuperavit266_267;
                    $linha29_3 = ($nValorNaoAplicadoFundebImpostosTransfAnoAnterior - $nValorAPlicadoSuperavit218_219) + ($nValorNaoAplicadoFundebComplementacaoAnoAnterior - $nValorAPlicadoSuperavit266_267);

                    $linha29_1 = $linha29_1 >= 0 ? $linha29_1 : 0;
                    $linha29_2 = $linha29_2 >= 0 ? $linha29_2 : 0;
                    $linha29_3 = $linha29_3 >= 0 ? $linha29_3 : 0;
                ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">27 - VALOR TOTAL DE SUPERÁVIT NÃO APLICADO ATÉ O FINAL DO EXERCÍCIO</td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($linha27_1,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($linha27_2,"f"); ?></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($linha27_3,"f"); ?></td>
                </tr>
                <?php
                    $linha28_1 = $nValorAPlicadoSuperavit218_219 > $nValorNaoAplicadoFundebImpostosTransfAnoAnterior ? $nValorNaoAplicadoFundebImpostosTransfAnoAnterior : $nValorAPlicadoSuperavit218_219;
                  ?>
                <tr style="height: 20px">
                    <td class="s11" dir="ltr"></td>
                    <td class="s12" dir="ltr" colspan="6">28 - VALOR APLICADO ATÉ O PRIMEIRO QUADRIMESTRE QUE INTEGRARÁ O LIMITE CONSTITUCIONAL</td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($linha28_1,"f"); ?></td>
                    <td class="s10" dir="ltr"></td>
                    <td class="s10" dir="ltr"><?php echo db_formatar($linha28_1,"f"); ?></td>
                </tr>
                <tr style="height: 20px">
                    <td class="s5" dir="ltr"></td>
                    <td class="s21" dir="ltr" colspan="6">29 - VALOR DE SUPERÁVIT PERMITIDO NO EXERCÍCIO ANTERIOR NÃO APLICADO NO EXERCÍCIO ATUAL (L24 - L25)</td>
                    <td class="s22" dir="ltr"><?php echo db_formatar($linha29_1,"f"); ?></td>
                    <td class="s22" dir="ltr"><?php echo db_formatar($linha29_2,"f"); ?></td>
                    <td class="s22" dir="ltr"><?php echo db_formatar($linha29_3,"f"); ?></td>
                </tr>

            </table>
        </div>
</body>
</html>
<?php

$html = ob_get_contents();
ob_end_clean();
$mPDF->WriteHTML(utf8_encode($html));
$mPDF->Output();
db_query("drop table if exists work_dotacao");
db_fim_transacao();
} catch (MpdfException $e) {
    db_redireciona('db_erros.php?fechar=true&db_erro='.$e->getMessage());
}



?>
