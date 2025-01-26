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
require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
include_once "libs/db_sessoes.php";
include_once "libs/db_usuariosonline.php";
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("libs/db_sql.php");
require_once("classes/db_consexecucaoorc_classe.php");
require_once("classes/db_infocomplementaresinstit_classe.php");
use \Mpdf\Mpdf;
use \Mpdf\MpdfException;

db_postmemory($HTTP_POST_VARS);
$oPeriodo = new Periodo($o116_periodo);
$clinfocomplementaresinstit = new cl_infocomplementaresinstit();

$oDataFim = new DBDate("{$anousu}-{$oPeriodo->getMesInicial()}-{$oPeriodo->getDiaFinal()}");
$oDataIni = new DBDate("{$anousu}-{$oPeriodo->getMesInicial()}-{$oPeriodo->getDiaFinal()}");

$iMes = $oDataIni->getMes() != 12 ? ($oDataIni->getMes() - 11) + 12 : $oDataIni->getMes() - 11; //Calcula o mes separado por causa do meses que possuem 31 dias
$oDataIni->modificarIntervalo("-11 month"); //Fa�o isso apenas para saber o ano
$oDataIni = new DBDate($oDataIni->getAno() . "-" . $iMes . "-1"); //Aqui pego o primeiro dia do mes para montar a nova data de inicio
$dtini = $oDataIni->getDate();
$dtfim = $oDataFim->getDate();
$instits = str_replace('-', ', ', $db_selinstit);
$aInstits = explode(",", $instits);
$soma = 0;
if (count($aInstits) > 1) {
    $oInstit = new Instituicao();
    $oInstit = $oInstit->getDadosPrefeitura();
} else {
    foreach ($aInstits as $iInstit) {
        $oInstit = new Instituicao($iInstit);
    }
}

$iRpps = 0;
$iCamara = 0;
foreach ($aInstits as $iInstit) {
    $instit = new Instituicao($iInstit);
    if ($instit->getTipoInstit() == Instituicao::TIPO_INSTIT_RPPS)
        $iRpps = 1;
    if ($instit->getTipoInstit() == Instituicao::TIPO_INSTIT_CAMARA)
        $iCamara = 1;
}

/**
 * pego todas as institui��es OC10823;
 */
$rsInstits = $clinfocomplementaresinstit->sql_record($clinfocomplementaresinstit->sql_query(null, "si09_instit,si09_tipoinstit", null, null));

$ainstitunticoes = array();
for ($i = 0; $i < pg_num_rows($rsInstits); $i++) {
    $odadosInstint = db_utils::fieldsMemory($rsInstits, $i);
    $ainstitunticoes[] = $odadosInstint->si09_instit;
}
$iInstituicoes = implode(',', $ainstitunticoes);

$rsTipoinstit = $clinfocomplementaresinstit->sql_record($clinfocomplementaresinstit->sql_query(null, "si09_sequencial,si09_tipoinstit", null, "si09_instit in( {$instits})"));

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

/**
 * Verifico institu para retornar o percentual Permitido pela Lei Complementar
 */
$iVerifica = null;

if ($iCont == 1 && in_array("1", $aTipoistituicao)) {
    $iVerifica = 1;
} elseif ($iCont >= 1 && !(in_array("1", $aTipoistituicao))) {
    $iVerifica = 2;
} else {
    $iVerifica = 3;
}

/**
 * Adi��o de filtro no relat�rio para buscar valor empenhado l�quido
 * Al�m do liquidado que j� buscava anteriormente
 * */
$valoresperado = $tipoCalculo == 1 ? "empenhado" : "liquidado";
$valorcalculoManual = $tipoCalculo == 1 ? "sum(c233_valorempenhado) as {$valoresperado}" : "sum(c233_valorliquidado) as {$valoresperado}";
$valorcalculo = $tipoCalculo == 1 ? "sum(empenhado) - sum(anulado) as {$valoresperado}" : "sum(liquidado) as {$valoresperado}";

// db_inicio_transacao();
function getDespesasReceitas($iInstituicoes, $dtini, $dtfim, $iRpps)
{
    $fTotalarrecadado = 0;
    $fTransfUniACSCE = 0;
    $fTotalEmenda = 0;
    $fCSACRPPS = 0; //412102907
    $fCSICRPPS = 0; //412102909
    $fCPRPPS = 0; //412102911
    $fRRCSACOPSJ = 0; //412102917
    $fRRCSICOPSJ = 0; //412102918
    $fRRCPPSJ = 0; //412102919
    $fCFRP = 0; //4192210
    $fRARP = 0;

    $db_filtro = " o70_instit in({$iInstituicoes}) ";
    $anousu = db_getsession("DB_anousu");
    $anousu_aux = $anousu - 1;
    $dtfim_aux  = $anousu_aux . '-12-31';

    // DADOS DA RECEITA NO ANO ANTERIOR
    if (temDataImplantacao($dtini)) {
        $oUltimoano = db_utils::getColectionByRecord(db_receitasaldo(11, 1, 3, true, $db_filtro, $anousu - 1, buscarDataImplantacao(), $dtfim_aux, false, ' * ', true, 0));
    } else {
        $oUltimoano = db_receitasaldo(11, 1, 3, true, $db_filtro, $anousu - 1, $dtini, $dtfim_aux, false, ' * ', true, 0);
        $oUltimoano = db_utils::getColectionByRecord($oUltimoano);
    }

    foreach ($oUltimoano as $oDados) {
        if ($oDados->o57_fonte == "413210400000000") {
            $fRARP += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "419990300000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412150211000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412155011000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }
        // die("Estou aqui 147");
        if ($oDados->o57_fonte == "412150100000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412150300000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "410000000000000") {
            $fTotalarrecadado += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "470000000000000") {
            $fTotalarrecadado += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "491100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "495000000000000") {
            $fCSICRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "470000000000000") {
            $fCPRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "419900300000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180311000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180321000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412102919000000") {
            $fRRCPPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "417180800000000") {
            $fCFRP += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "492100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "493100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "496100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "498100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "499100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180100000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180200000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100421000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100422000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100423000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100424000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100431000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100432000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100433000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100434000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100441000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100442000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100443000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100444000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100461000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100462000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100463000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100464000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100471000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100472000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100473000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100474000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100481000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100482000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100483000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100484000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100481000000") {
            $fRRCPPSJ += $oDados->saldo_arrecadado;
        }

        if (substr($oDados->o57_fonte, 0, 4) == "4171" AND $oDados->o70_codigo == "16040000") {
            $fTransfUniACSCE += $oDados->saldo_arrecadado;
        }
    }
    db_query("drop table if exists work_receita");

    // DADOS DA RECEITA NO ANO ATUAL
    if (temDataImplantacao($dtini)) {
        $oAnoatual = getValorReceitaInformado($dtini, buscarDataImplantacao(), $iInstituicoes);

        foreach ($oAnoatual as $oDados) {
            if (substr($oDados->o57_fonte, 0, 2) == "41") {
                $fTotalarrecadado += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 2) == "47") {
                $fTotalarrecadado += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 3) == "491") {
                $fCSACRPPS += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 3) == "492") {
                $fCSACRPPS += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 3) == "493") {
                $fCSACRPPS += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 3) == "496") {
                $fCSACRPPS += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 3) == "498") {
                $fCSACRPPS += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 3) == "499") {
                $fCSACRPPS += $oDados->saldo_arrecadado;
            }

            if (in_array(substr($oDados->o57_fonte, 0, 4), array("4927", "4917", "4923", "4987", "4997"))) {
                $fCSACRPPS += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 3) == "495") {
                $fCSICRPPS += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 2) == "47") {
                $fCPRPPS += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412180100000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            // 17051
            if ($oDados->o57_fonte == "412150100000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412150300000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }
            // 17051 **Fim
            if ($oDados->o57_fonte == "412180200000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100421000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100422000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100423000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100424000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100431000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100432000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100433000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100434000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100441000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100442000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100443000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100444000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100461000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100462000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100463000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100464000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100471000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100472000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100473000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100474000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100481000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100482000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100483000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if ($oDados->o57_fonte == "412100484000000") {
                $fRRCSACOPSJ += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 9) == "412100481") {
                $fRRCPPSJ += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 9) == "412180161") {
                $fRRCPPSJ += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 9) == "412180261") {
                $fRRCPPSJ += $oDados->saldo_arrecadado;
            }

            if (substr($oDados->o57_fonte, 0, 7) == "4171808") {
                $fCFRP += $oDados->saldo_arrecadado;
            }
        }

        $oAnoatual = db_utils::getColectionByRecord(db_receitasaldo(11, 1, 3, true, $db_filtro, $anousu, buscarDataImplantacao(), $dtfim, false, ' * ', true, 0));

    } else {
        $dtini_aux = $anousu . '-01-01';
        $oAnoatual = db_receitasaldo(11, 1, 3, true, $db_filtro, $anousu, $dtini_aux, $dtfim, false, ' * ', true, 0);
        $oAnoatual = db_utils::getColectionByRecord($oAnoatual);
    }

    foreach ($oAnoatual as $oDados) {

        if (substr($oDados->o57_fonte, 0, 4) == "4171" AND $oDados->o70_codigo == "16040000") {
            $fTransfUniACSCE += $oDados->saldo_arrecadado;
        }

        if (substr($oDados->o57_fonte, 0, 4) == "4171") {
            $fTotalEmenda += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "410000000000000") {
            $fTotalarrecadado += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "470000000000000") {
            $fTotalarrecadado += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "491100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "492100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "493100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "496100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "498100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "499100000000000") {
            $fCSACRPPS += $oDados->saldo_arrecadado;
        }

        // 17051
        if ($oDados->o57_fonte == "412150100000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412150300000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }
        // 17051 **Fim

        if ($oDados->o57_fonte == "495000000000000") {
            $fCSICRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "470000000000000") {
            $fCPRPPS += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180100000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180200000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100421000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100422000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100423000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100424000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100431000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100432000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100433000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100434000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100441000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100442000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100443000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100444000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100461000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100462000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100463000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100464000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100471000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100472000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100473000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100474000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100481000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

          // 17051
          if ($oDados->o57_fonte == "419990300000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412150211000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412155011000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }
        // 17051 *Fim

        if ($oDados->o57_fonte == "412100482000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100483000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100484000000") {
            $fRRCSACOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "419900300000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180311000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180321000000") {
            $fRRCSICOPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "413210400000000") {
            $fRARP += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412100481000000") {
            $fRRCPPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180161000000") {
            $fRRCPPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "412180261000000") {
            $fRRCPPSJ += $oDados->saldo_arrecadado;
        }

        if ($oDados->o57_fonte == "417180800000000") {
            $fCFRP += $oDados->saldo_arrecadado;
        }
    }

    db_query("drop table if exists work_receita");

    return array(
        'fTotalReceitasArrecadadas' => $fTotalarrecadado,
        'fTransfUniACSCE' => $fTransfUniACSCE,
        'fTotalEmenda' => $fTotalEmenda,
        'fCSACRPPS' => $fCSACRPPS,
        'fCSICRPPS' => $fCSICRPPS,
        'fCPRPPS' => $fCPRPPS,
        'fRRCSACOPSJ' => $fRRCSACOPSJ,
        'fRRCSICOPSJ' => $fRRCSICOPSJ,
        'fRRCPPSJ' => $fRRCPPSJ,
        'fCFRP' => $fCFRP,
        'fRARP' => $fRARP
    );
}

$aDespesasReceitas = getDespesasReceitas($iInstituicoes, $dtini, $dtfim, $iRpps);

$fTotalReceitasArrecadadas = $aDespesasReceitas['fTotalReceitasArrecadadas'];
$fTransfUniACSCE = $aDespesasReceitas['fTransfUniACSCE'];
$fTotalEmenda = $aDespesasReceitas['fTotalEmenda'];
$fCSACRPPS = $aDespesasReceitas['fCSACRPPS'];
$fCSICRPPS = $aDespesasReceitas['fCSICRPPS'];
$fCPRPPS = $aDespesasReceitas['fCPRPPS'];
$fRRCSACOPSJ = $aDespesasReceitas['fRRCSACOPSJ'];
$fRRCSICOPSJ = $aDespesasReceitas['fRRCSICOPSJ'];
$fRRCPPSJ = $aDespesasReceitas['fRRCPPSJ'];
$fRARP = $aDespesasReceitas['fRARP'];
$fCFRP = 0;
$fCFRPB = 0;

$sWhereDespesa = " o58_instit in({$instits})";
$sWhereReceita = "o70_instit in ({$iInstituicoes})";
//Aqui passo o(s) exercicio(s) e a funcao faz o sql para cada exercicio
if (temDataImplantacao($dtini)) {
    $oNovaData = new DBDate(buscarDataImplantacao());
    criaWorkDotacao($sWhereDespesa, array_keys(DBDate::getMesesNoIntervalo($oNovaData, $oDataFim)), buscarDataImplantacao(), $dtfim);
    //Aqui passo o(s) exercicio(s) e a funcao faz o sql para cada exercicio
    criarWorkReceita($sWhereReceita, array_keys(DBDate::getMesesNoIntervalo($oNovaData, $oDataFim)), buscarDataImplantacao(), $dtfim);
} else {
    criaWorkDotacao($sWhereDespesa, array_keys(DBDate::getMesesNoIntervalo($oDataIni, $oDataFim)), $dtini, $dtfim);
    criarWorkReceita($sWhereReceita, array_keys(DBDate::getMesesNoIntervalo($oDataIni, $oDataFim)), $dtini, $dtfim);
}

/**
 * mPDF
 * @param string $mode | padr�o: BLANK
 * @param mixed $format | padr�o: A4
 * @param float $default_font_size | padr�o: 0
 * @param string $default_font | padr�o: ''
 * @param float $margin_left | padr�o: 15
 * @param float $margin_right | padr�o: 15
 * @param float $margin_top | padr�o: 16
 * @param float $margin_bottom | padr�o: 16
 * @param float $margin_header | padr�o: 9
 * @param float $margin_footer | padr�o: 9
 *
 * Nenhum dos par�metros � obrigat�rio
 */

if ($tipoEmissao == 1) {
    $mPDF = new Mpdf([
        'mode' => '',
        'format' => '',
        'orientation' => '',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 23.5,
        'margin_bottom' => 15,
        'margin_header' => 5,
        'margin_footer' => 11,
    ]);

} else {
    $mPDF = new Mpdf([
        'mode' => '',
        'format' => 'A4',
        'orientation' => 'L',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 23.5,
        'margin_bottom' => 15,
        'margin_header' => 5,
        'margin_footer' => 11,
    ]);
}

if ($tipoEmissao == 1) {
    $valorEsperadoUC = ucfirst($valoresperado);
    $header = <<<HEADER
<header>
  <table style="width:100%;text-align:center;font-family:sans-serif;border-bottom:1px solid #000;padding-bottom:6px;">
    <tr>
      <th>{$oInstit->getDescricao()}</th>
    </tr>
    <tr >
      <th>ANEXO IV - DEMONSTRATIVO DOS GASTOS COM PESSOAL</th>
    </tr>
    <tr>
      <td style="text-align:right;font-size:10px;font-style:oblique;">Per�odo: De {$oDataIni->getDate("d/m/Y")} a {$oDataFim->getDate("d/m/Y")}</td>
    </tr>
  </table>
</header>
HEADER;
} else {
    $header = "
    <header>
    <div style=\"font-family:Arial\">
        <div style=\"width:33%;float:left;padding:5px;font-size:10px;\">
            <b><i>{$oInstit->getDescricao()}</i></b>
            <br/>
            <i>{$oInstit->getLogradouro()}, {$oInstit->getNumero()}</i>
            <br/>
            <i>{$oInstit->getMunicipio()} - {$oInstit->getUf()}</i>
            <br/>
            <i>{$oInstit->getTelefone()} - CNPJ: " . db_formatar($oInstit->getCNPJ(), "cnpj") . "</i>
            <br/>
            <i>{$oInstit->getSite()}</i>
        </div>
        <div style=\"width:25%;float:right\" class=\"box\">
        <b>ANEXO IV - DEMONSTRATIVO DE GASTO COM PESSOAL</b>
        <br/>
    <b>INSTITUI��ES:</b> ";
    foreach ($aInstits as $iInstit) {
        $oInstituicao = new Instituicao($iInstit);
        $header .= "(" . trim($oInstituicao->getCodigo()) . ") " . $oInstituicao->getDescricao() . " ";
    }
    $header .= "
                <br/>
                <b>PER�ODO:</b> {$oDataIni->getDate("d/m/Y")} A {$oDataFim->getDate("d/m/Y")}
                <br/>
                <b>CALCULO:</b> " . strtoupper($valoresperado) . "
          </div>
    </header>";
}

$valorEsperadoUC = ucfirst($valoresperado) . "s";
$footer = "<footer>
<div style='border-top:1px solid #000;width:100%;font-family:sans-serif;font-size:10px;height:10px;'>
    <div style='text-align:left;font-style:italic;width:90%;float:left;'>
        Financeiro>Contabilidade>Relat�rios de Acompanhamento>ANEXO IV - Gasto com Pessoal
        Emissor: " . db_getsession("DB_login") . " Exerc: " . db_getsession("DB_anousu") . " Data:" . date("d/m/Y H:i:s", db_getsession("DB_datausu"))  . "
        C�lculo com base nos valores: {$valorEsperadoUC}</div>

    <div style='text-align:right;float:right;width:10%;'>
        {PAGENO}
    </div>

</div></footer>";


$mPDF->WriteHTML(file_get_contents('estilos/tab_relatorio.css'), 1);
$mPDF->setHTMLHeader(utf8_encode($header), 'O', true);
$mPDF->setHTMLFooter(utf8_encode($footer), 'O', true);

ob_start();
?>

<html>

<head>
    <style type="text/css">
        .ritz .waffle a {
            color: inherit;
        }

        .ritz .waffle .s1 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 0px 3px 0px 3px;
            text-align: left;
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
            padding: 0px 3px 0px 3px;
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
            padding: 0px 3px 0px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s3 {
            background-color: #ffffff;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 0px 3px 0px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s10 {
            background-color: #d8d8d8;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 0px 3px 0px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s6 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 0px 3px 0px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s7 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 0px 3px 0px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s8 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 0px 3px 0px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s5 {
            background-color: #ffffff;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 0px 3px 0px 3px;
            text-align: right;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s0 {
            background-color: #d8d8d8;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            font-weight: bold;
            padding: 0px 3px 0px 3px;
            text-align: center;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .ritz .waffle .s9 {
            background-color: #ffffff;
            border-bottom: 1px SOLID #000000;
            border-right: 1px SOLID #000000;
            color: #000000;
            direction: ltr;
            font-family: 'Calibri', Arial;
            font-size: 11pt;
            padding: 0px 3px 0px 3px;
            text-align: left;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .column-headers-background {
            background-color: #d8d8d8;
        }
    </style>
</head>

<body>
    <div class="ritz grid-container" dir="ltr">
        <br />
        <?php if ($tipoEmissao != 1) { ?>
            <span style="font-size:8px;font-family:Arial">Art. 55, inciso I, al�nea 'a" da LRF</span>
        <? } ?>
        <table class="waffle" cellspacing="0" cellpadding="0" style="border-top: 1px #000">
            <tbody>
                <?php

                        $dataAtual = $dtini;
                        $dataFinal = $dtfim;
                        $subtotalmes = array();
                        $meses = array();
                        $mesesExtenso = array(
                            "01" => 'JAN',
                            "02" => 'FEV',
                            "03" => 'MAR',
                            "04" => 'ABR',
                            "05" => 'MAI',
                            "06" => 'JUN',
                            "07" => 'JUL',
                            "08" => 'AGO',
                            "09" => 'SET',
                            "10" => 'OUT',
                            "11" => 'NOV',
                            "12" => 'DEZ',
                        );

                        // Monta informa��o do ano inteiro
                        if ($valoresperado == 'liquidado') {
                            $mesFinal = date("m", strtotime($dataFinal));
                            $chaveMesDezembro = "DEZ/" . date("Y", strtotime($dataAtual));
                            $janeiro = "01-01-" . date("Y", strtotime($dataAtual));
                            $dezembro = "31-12-" . date("Y", strtotime($dataAtual));

                            if ($dataAtual >= buscarDataImplantacao() or !buscarDataImplantacao()) {

                                foreach ($aInstits as $iInstit) {
                                    $oInstit = new Instituicao($iInstit);

                                    if ($tipoEmissao == 1) {
                                        $chaveMesDezembro = $oInstit->getCodigo();
                                    }

                                    foreach (getDespesaMensal($janeiro, $dezembro, [$oInstit->getCodigo()]) as $data) {
                                        $chave = substr($data->o58_elemento, 0, 7);
                                        $despesa[$chave][$chaveMesDezembro] += $data->empenhado - $data->liquidado;

                                        if (in_array(substr($data->o58_elemento, 1, 8), array("31900101", "31900301", "31900501", "31900502"))) {
                                            $despesaSaldoIntaivosPensionistasProprio[$chave][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                        }
                                    }

                                    foreach (getDespesaMensal($janeiro, $dezembro, [$oInstit->getCodigo()]) as $data) {
                                        $chaveTotal = substr($data->o58_elemento, 0, 7);

                                        if (in_array(substr($data->o58_elemento, 0, 7), array("3319001", "3319003"))
                                            || in_array($data->o58_codigo, array("16040000", "26040000"))
                                            || in_array($data->o58_codigo, array("16050000", "26050000"))) {

                                                $chaveTotal .= $data->o58_codigo;
                                                $chaveTotal .= $data->o58_anousu;
                                        }

                                        $totalDespesa[$chaveTotal][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                        $totalDespesa[$chaveTotal]['o58_fonte'] = $data->o58_codigo;
                                        $totalDespesa[$chaveTotal]['o58_anousu'] = $data->o58_anousu;

                                        if (in_array(substr($data->o58_elemento, 1, 6), array("319001", "319003"))) {
                                            $despesaSaldoIntaivosPensionistasProprio[$chaveToal][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                        }
                                    }

                                    foreach (getDespesaMensalExclusaoSaldoIntaivosPensionistasProprio($janeiro, $dezembro, [$oInstit->getCodigo()], $iRpps, 2) as $data) {
                                        $chave2 = $data->o58_elemento;
                                        $despesaSaldoIntaivosPensionistasProprio[$chave2][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                    }

                                    if ($iRpps) {
                                        foreach (getDespesaMensalExclusaoSaldoIntaivosPensionistasProprioDeduzir($janeiro, $dezembro, [$oInstit->getCodigo()], $iCamara) as $data) {
                                            $chave2 = substr($data->o58_elemento, 0, 7);
                                            $despesa[$chave2][$chaveMesDezembro] -= $data->empenhado - $data->liquidado;
                                        }
                                    }

                                    foreach (getDespesaMensalExclusaoSaldoSentencasJudAnt($janeiro, $dezembro, $dataAtual, [$oInstit->getCodigo()]) as $data) {
                                        $chave2 = $data->o58_elemento;
                                        $despesaSaldoSentencasJudAnt[$chave2][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                    }

                                    foreach (getDespesaMensalSaldoIndenizacaoDemissaoServidores($janeiro, $dezembro, [$oInstit->getCodigo()]) as $data) {
                                        $chave2 = $data->o58_elemento;
                                        $despesaSaldoIndenizacaoDemissaoServidores[$chave2][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                    }

                                    foreach (getDespesaMensalSaldoDespesasAnteriores($janeiro, $dezembro, [$oInstit->getCodigo()], $dtini) as $data) {
                                        $chave2 = $data->o58_elemento;
                                        $despesaSaldoDespesasAnteriores[$chave2][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                    }
                                }

                            } else {
                                foreach ($aInstits as $iInstit) {
                                    $oInstit = new Instituicao($iInstit);
                                    $codigoInstit = $oInstit->getCodigo();

                                    if ($tipoEmissao == 1) {
                                        $chaveMesDezembro = $codigoInstit;
                                    }

                                    foreach (getDespesaMensalInformada($janeiro, $dezembro, [$oInstit->getCodigo()], $janeiro) as $data) {
                                        $chave = substr($data->o58_elemento, 0, 7);
                                        $despesa[$chave][$chaveMesDezembro] += $data->empenhado - $data->liquidado;

                                        if (in_array(substr($data->o58_elemento, 1, 8), array("31900101", "31900301", "31900501", "31900502"))) {
                                            $despesaSaldoIntaivosPensionistasProprio[$chave][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                        }
                                    }
                                }
                            }
                        }

                        while ($dataFinal > $dataAtual) {
                            $chaveMes = $mesesExtenso[date("m", strtotime($dataAtual))] . "/" . date("Y", strtotime($dataAtual));
                            $chaveMesDezembro = "DEZ/" . date("Y", strtotime($dataAtual));
                            $meses[] = $chaveMes;

                            if (!array_key_exists($chaveMes, $subtotalmes))
                                $subtotalmes[$chaveMes] = 0;

                            if ($dataAtual >= buscarDataImplantacao() or !buscarDataImplantacao()) {
                                $inicio = ($dataAtual >= buscarDataImplantacao() or !buscarDataImplantacao()) ? $dataAtual : buscarDataImplantacao();

                                foreach ($aInstits as $iInstit) {
                                    $oInstit = new Instituicao($iInstit);
                                    $codigoInstit = $oInstit->getCodigo();

                                    if ($tipoEmissao == 1) {
                                        $chaveMes = $codigoInstit;
                                        $chaveMesDezembro = $codigoInstit;
                                    }

                                    foreach (getDespesaMensal($inicio, date('Y-m-t', strtotime($inicio)), [$codigoInstit]) as $data) {
                                        $chave = substr($data->o58_elemento, 0, 7);

                                        if (array_key_exists($chaveMes, $despesa[$chave])) {
                                            $despesa[$chave][$chaveMes]   += $data->$valoresperado;
                                            $despesa[$chave]['elemento']   = $data->o58_elemento;
                                            $despesa[$chave]['descricao']  = $data->o56_descr;
                                        } else {
                                            $despesa[$chave][$chaveMes]   = $data->$valoresperado;
                                            $despesa[$chave]['elemento']  = $data->o58_elemento;
                                            $despesa[$chave]['descricao'] = $data->o56_descr;
                                        }

                                        if (in_array(substr($data->o58_elemento, 1, 8), array("31900101", "31900301", "31900501", "31900502"))) {
                                            $despesaSaldoIntaivosPensionistasProprio[$chave][$chaveMes] += $data->empenhado - $data->liquidado;
                                        }

                                        if (date("Y", strtotime($dataFinal)) > date("Y", strtotime($dataFinal))) {
                                            $despesa[$chave][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                            if (in_array(substr($data->o58_elemento, 1, 8), array("31900101", "31900301", "31900501", "31900502"))) {
                                                $despesaSaldoIntaivosPensionistasProprio[$chave][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                            }
                                        }
                                    }

                                    foreach (getDespesaMensal($inicio, date('Y-m-t', strtotime($inicio)), [$codigoInstit]) as $data) {

                                        $chaveTotal = substr($data->o58_elemento, 0, 7);
                                        if (in_array(substr($data->o58_elemento, 0, 7), array("3319001", "3319003"))
                                            || in_array($data->o58_codigo, array("16040000", "26040000"))
                                            || in_array($data->o58_codigo, array("16050000", "26050000"))) {

                                                $chaveTotal .= $data->o58_codigo;
                                                $chaveTotal .= $data->o58_anousu;
                                        }


                                        if (array_key_exists($chaveMes, $totalDespesa[$chaveTotal])) {
                                            $totalDespesa[$chaveTotal][$chaveMes]   += $data->$valoresperado;
                                            $totalDespesa[$chaveTotal]['elemento']   = $data->o58_elemento;
                                            $totalDespesa[$chaveTotal]['descricao']  = $data->o56_descr;
                                            $totalDespesa[$chaveTotal]['o58_fonte'] = $data->o58_codigo;
                                            $totalDespesa[$chaveTotal]['o58_anousu'] = $data->o58_anousu;
                                        } else {
                                            $totalDespesa[$chaveTotal][$chaveMes]   = $data->$valoresperado;
                                            $totalDespesa[$chaveTotal]['elemento']  = $data->o58_elemento;
                                            $totalDespesa[$chaveTotal]['descricao'] = $data->o56_descr;
                                            $totalDespesa[$chaveTotal]['o58_fonte'] = $data->o58_codigo;
                                            $totalDespesa[$chaveTotal]['o58_anousu'] = $data->o58_anousu;
                                        }

                                        if (in_array(substr($data->o58_elemento, 1, 8), array("319001", "319003"))) {
                                            $despesaSaldoIntaivosPensionistasProprio[$chaveTotal][$chaveMes] += $data->empenhado - $data->liquidado;
                                        }

                                        if (date("Y", strtotime($dataFinal)) > date("Y", strtotime($dataFinal))) {
                                            $totalDespesa[$chaveTotal][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                            if (in_array(substr($data->o58_elemento, 1, 8), array("319001", "319003"))) {
                                                $despesaSaldoIntaivosPensionistasProprio[$chaveTotal][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                            }
                                        }
                                    }

                                    if ($iRpps) {
                                        foreach (getDespesaMensalExclusaoSaldoIntaivosPensionistasProprioDeduzir($inicio, date('Y-m-t', strtotime($inicio)), [$codigoInstit], $iCamara) as $data) {
                                            $chave2 = substr($data->o58_elemento, 0, 7);

                                            if (date("Y", strtotime($dataFinal)) >date("Y", strtotime($dataFinal))) {
                                                $despesa[$chave2][$chaveMesDezembro] -= $data->empenhado - $data->liquidado;

                                                if (in_array(substr($data->o58_elemento, 1, 8), array("31900101", "31900301", "31900501", "31900502"))) {
                                                    $despesaSaldoIntaivosPensionistasProprio[$chave2][$chaveMes] -= $data->empenhado - $data->liquidado;
                                                }
                                            }
                                            $despesa[$chave2][$chaveMes] -= $data->$valoresperado;
                                            if (in_array(substr($data->o58_elemento, 1, 8), array("31900101", "31900301", "31900501", "31900502"))) {
                                                $despesaSaldoIntaivosPensionistasProprio[$chave2][$chaveMes] -= $data->$valoresperado;
                                            }
                                        }
                                    }

                                }

                                foreach (getDespesaMensalExclusaoSaldoIntaivosPensionistasProprio($inicio, date('Y-m-t', strtotime($inicio)), $aInstits, $iRpps, 2) as $data) {
                                    $chave2 = $data->o58_elemento;

                                    if (date("Y", strtotime($dataFinal)) > date("Y", strtotime($dataFinal))) {
                                        $despesaSaldoIntaivosPensionistasProprio[$chave2][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                    }
                                    $despesaSaldoIntaivosPensionistasProprio[$chave2][$chaveMes]   += $data->$valoresperado;
                                    $despesaSaldoIntaivosPensionistasProprio[$chave2]['descricao']  = $data->o56_descr;
                                }

                                foreach (getDespesaMensalExclusaoSaldoSentencasJudAnt($dtini, $inicio, date('Y-m-t', strtotime($inicio)), $aInstits) as $data) {
                                    $chave2 = $data->o58_elemento;
                                    if (date("Y", strtotime($dataFinal)) >date("Y", strtotime($dataFinal))) {
                                        $despesaSaldoSentencasJudAnt[$chave2][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                    }
                                    $despesaSaldoSentencasJudAnt[$chave2][$chaveMes]   += $data->$valoresperado;
                                    $despesaSaldoSentencasJudAnt[$chave2]['descricao']  = $data->o56_descr;
                                }
                                $empdez = 0;
                                $liqdez = 0;

                                foreach (getDespesaMensalSaldoIndenizacaoDemissaoServidores($inicio, date('Y-m-t', strtotime($inicio)), $aInstits) as $data) {
                                    $chave2 = $data->o58_elemento;
                                    if (date("Y", strtotime($dataFinal)) >date("Y", strtotime($dataFinal))) {
                                        $despesaSaldoIndenizacaoDemissaoServidores[$chave2][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                    }
                                    $despesaSaldoIndenizacaoDemissaoServidores[$chave2][$chaveMes]   += $data->$valoresperado;
                                    $despesaSaldoIndenizacaoDemissaoServidores[$chave2]['descricao']  = $data->o56_descr;
                                }

                                foreach (getDespesaMensalSaldoDespesasAnteriores($inicio, date('Y-m-t', strtotime($inicio)), $aInstits, $dtini) as $data) {
                                    $chave2 = $data->o58_elemento;
                                    if (date("Y", strtotime($dataFinal)) >date("Y", strtotime($dataFinal))) {
                                        $despesaSaldoDespesasAnteriores[$chave2][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                    }
                                    $despesaSaldoDespesasAnteriores[$chave2][$chaveMes]   += $data->$valoresperado;
                                    $despesaSaldoDespesasAnteriores[$chave2]['descricao']  = $data->o56_descr;
                                }
                            } else {
                                foreach (getDespesaMensalInformada($dataAtual, date('Y-m-t', strtotime($dataAtual)), $aInstits, $dtini) as $data) {
                                    $chave = substr($data->o58_elemento, 0, 7);

                                    if (array_key_exists($chaveMes, $despesa[$chave])) {
                                        $despesa[$chave][$chaveMes]   += $data->$valoresperado;
                                        $despesa[$chave]['descricao'] = $data->o56_descr;
                                    } else {
                                        $despesa[$chave][$chaveMes] = $data->$valoresperado;
                                        $despesa[$chave]['descricao'] = $data->o56_descr;
                                    }

                                    if (in_array(substr($data->o58_elemento, 1, 8), array("31900101", "31900301", "31900501", "31900502"))) {
                                        $despesaSaldoIntaivosPensionistasProprio[$chave][$chaveMes] += $data->$valoresperado;
                                    }

                                    if (date("Y", strtotime($dataFinal)) >date("Y", strtotime($dataFinal))) {
                                        $despesa[$chave][$chaveMesDezembro] += $data->empenhado - $data->liquidado;
                                        if (in_array(substr($data->o58_elemento, 1, 8), array("31900101", "31900301", "31900501", "31900502"))) {
                                            $despesaSaldoIntaivosPensionistasProprio[$chave][$chaveMesDezembro] += $data->$valoresperado;
                                        }
                                    }
                                }
                            }
                            $dataAtual = date('Y-m-01', strtotime($dataAtual . ' +1 month'));
                        }
                ?>
                <?php if ($tipoEmissao == 1) { ?>
                    <tr>
                        <th id="1606692746C0" style="width:463px" class="bdtop bdleft column-headers-background">&nbsp;</th>
                        <th id="1606692746C1" style="width:92px" class="bdtop  column-headers-background">&nbsp;</th>
                        <th id="1606692746C2" style="width:106px" class="bdtop bdright column-headers-background">&nbsp;</th>
                    </tr>
                    <tr style='height:19px; border-top: 1px solid black'>
                        <td class="s0 bdleft" colspan="3">ANEXO IV</td>
                    </tr>
                    <tr style='height:19px;'>
                        <td class="s0 bdleft" colspan="3">Demonstrativo dos Gastos com Pessoal</td>
                    </tr>
                    <tr style='height:19px;'>
                        <td class="s0 bdleft" colspan="3">Inclu�da a Remunera��o dos Agentes Pol�ticos</td>
                    </tr>
                    <tr style='height:19px;'>
                        <td class="s0 bdleft" colspan="3">(Face ao Disposto pela Lei Complementar n�101, de 04/05/2000)</td>
                    </tr>
                    <tr style='height:20px;'>
                        <td class="s1 bdleft" colspan="3">&nbsp;</td>
                    </tr>
                    <tr style='height:19px;'>
                        <td class="s2" colspan="3">&nbsp;</td>
                    </tr>
                    <tr style='height:19px;'>
                        <td class="s1 bdleft" colspan="3">I) DESPESA</td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td class="bdleft bdtop s0">DESPESA COM PESSOAL</td>

                        <? for ($i = 0; $i <= 11; $i++) { ?>
                            <td class="bdleft bdtop s0"><?= $meses[$i] ?></td>
                        <? } ?>
                        <td class="bdleft bdtop s0">TOTAL GERAL</td>
                    </tr>
                    <?php ksort($despesa); ?>
                    <?php ksort($despesa2); ?>
                    <tr>
                        <td class="s3 bdleft bdtop">3.1.00.00.00 - PESSOAL E ENCARGOS SOCIAIS</td>
                        <? for ($i = 0; $i <= 12; $i++) { ?>
                            <td class="bdleft bdtop s6"></td>
                        <? } ?>
                    </tr>

                    <?
                    foreach ($despesa as $elemento => $datas) {
                        $totalConferencia = 0;
                        for ($i = 0; $i <= 11; $i++) {
                            $totalConferencia += $datas[$meses[$i]];
                        }
                        if ($totalConferencia == 0) {
                            unset($despesa[$elemento]);
                        }
                    }
                    ?>
                    <!-- Bloco que preenche dos dados mensais que precisam igualar ao oficial -->
                    <? foreach ($despesa as $elemento => $datas) { ?>
                        <?php $subtotal = 0; ?>
                        <?php if (substr($elemento, 1, 2) == "31") { ?>
                            <tr>
                                <td class="bdleft bdtop"> <?= substr(db_formatar($elemento, "elemento"), 0, 10) . " - " . $despesa[$elemento]['descricao']; ?></td>
                                <? for ($i = 0; $i <= 11; $i++) { ?>
                                    <td class="bdleft bdtop s6">
                                        <?php
                                        if (array_key_exists($meses[$i], $datas)) {
                                            echo db_formatar($datas[$meses[$i]], "f");
                                            $subtotalmes[$meses[$i]] += $datas[$meses[$i]];
                                            $subtotal += $datas[$meses[$i]];
                                        } else {
                                            echo "0,00";
                                        }
                                        ?></td>
                                <? } ?>
                                <td class="bdleft bdtop s6"><?= db_formatar($subtotal, "f"); ?></td>
                            </tr>
                        <? } ?>
                    <? } ?>
                    <!-- Final do Bloco -->
                    <tr>
                        <td class="s3 bdleft bdtop">3.3.00.00.00 - OUTRAS DESPESAS CORRENTES</td>
                        <? for ($i = 0; $i <= 12; $i++) { ?>
                            <td class="bdleft bdtop s6"></td>
                        <? } ?>
                    </tr>

                    <? foreach ($despesa as $elemento => $datas) { ?>
                        <?php $subtotal = 0; ?>
                        <?php if (substr($elemento, 0, 7) == "3339034" || substr($elemento, 0, 7) == "3339004") { ?>
                            <tr>
                                <td class="bdleft bdtop"><?= db_formatar($elemento, "elemento") . " - " . $despesa[$elemento]['descricao']; ?></td>
                                <? for ($i = 0; $i <= 11; $i++) { ?>
                                    <td class="bdleft bdtop s6">
                                        <?php
                                        if (array_key_exists($meses[$i], $datas)) {
                                            echo db_formatar($datas[$meses[$i]], "f");
                                            $subtotalmes[$meses[$i]] += $datas[$meses[$i]];
                                            $subtotal += $datas[$meses[$i]];
                                        } else {
                                            echo "0,00";
                                        }
                                        ?></td>
                                <? } ?>
                                <td class="bdleft bdtop s6"><?= db_formatar($subtotal, "f"); ?></td>
                            </tr>
                        <? } ?>
                    <? } ?>

                <?php } ?>


                <?php
                /**
                 * Para cada instit do sql
                 */
                $i = 1;
                $fTotalDespesas = 0;
                foreach ($aInstits as $iInstit) :

                    $oInstit = new Instituicao($iInstit);
                    if ($tipoEmissao == 1) {

                ?>
                        <tr style='height:19px;'>
                            <td class="s3 bdleft" colspan="2">I-<?= $i++; ?>) DESPESA
                                - <?php echo $oInstit->getDescricao(); ?></td>
                            <td class="s4"></td>
                        </tr>

                        <tr style='height:19px;'>
                            <td class="s3 bdleft" colspan="2">3.1.00.00.00 - PESSOAL E ENCARGOS SOCIAIS</td>
                            <td class="s4"></td>
                        </tr>

                        <?php
                    }

                    $fSubTotal = 0;
                    $aDespesas = array();
                    $aDespesas2 = array();
                    if ($tipoEmissao == 1) {
                        if (temDataImplantacao($dtini)) {
                            foreach (getValorDespesaInformado($oDataIni->getDate("Y-m-d"), buscarDataImplantacao(), '331', $valorcalculoManual, $oInstit) as $oDespesa) {
                                $chave = substr($oDespesa->o58_elemento, 0, 7);
                                if ($oDespesa->$valoresperado <> 0) {
                                    if (array_key_exists($chave, $aDespesas)) {
                                        $aDespesas[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                    } else {
                                        $aDespesas[$chave] = $oDespesa;
                                    }
                                }
                            }

                            foreach (getSaldoDespesa(null, "o58_elemento, o56_descr, {$valorcalculo}", null, "o58_elemento like '331%' and o58_instit = {$oInstit->getCodigo()} group by 1,2") as $oDespesa) {
                                $chave = substr($oDespesa->o58_elemento, 0, 7);
                                if ($oDespesa->$valoresperado <> 0) {
                                    if (array_key_exists($chave, $aDespesas)) {
                                        $aDespesas[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                    } else {
                                        $aDespesas[$chave] = $oDespesa;
                                    }
                                }
                            }
                        } else {
                            foreach (getDespesaMensal($dtini, $dtfim, [$oInstit->getCodigo()]) as $oDespesa) {
                            // foreach (getSaldoDespesa(null, "o58_elemento, o56_descr, o58_anousu, SUM(empenhado) empenhado, SUM(liquidado) liquidado", null, "o58_elemento like '331%' and o58_instit = {$oInstit->getCodigo()} group by 1,2,3") as $oDespesa) {
                                if (substr($oDespesa->o58_elemento,0,3) != "331")
                                    continue;
                                $chave = $oDespesa->o58_elemento;

                                /*
                                if ($valoresperado == 'liquidado') {
                                    if ($oDespesa->o58_anousu == date("Y", strtotime($dtini))) {
                                        $oDespesa->liquidado += $oDespesa->empenhado - $oDespesa->liquidado;
                                    }
                                }
                                */

                                if (array_key_exists($chave, $aDespesas)) {
                                    $aDespesas[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                } else {
                                    $aDespesas[$chave] = $oDespesa;
                                }

                                if ($valoresperado == 'liquidado') {
                                    $aDespesas[$chave]->$valoresperado += $oDespesa->empenhado - $oDespesa->liquidado;
                                }
                                /*
                                if ($oDespesa->$valoresperado <> 0) {
                                    if (array_key_exists($chave, $aDespesas)) {
                                        $aDespesas[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                    } else {
                                        $aDespesas[$chave] = $oDespesa;
                                    }
                                }
                                */
                            }
                        }

                        // Tratando o acumulado do ano anterior
                        if ($valoresperado == 'liquidado') {
                            foreach (getDespesaMensal("01-01-" . date("Y", strtotime($dtini)), "31-12-" . date("Y", strtotime($dtini)), array($oInstit->getCodigo())) as $data) {
                                $chave = str_pad(substr($data->o58_elemento, 0, 7), 13, "0");
                                // if (in_array(substr($data->o58_elemento, 0, 7), array("3319001", "3319003"))) {
                                    // $chave .= $data->o58_codigo;
                                // }
                                if (substr($chave, 1, 2) == "31") {
                                    if ($data->empenhado - $data->liquidado <> 0) {
                                        $aDespesas[$chave]->liquidado += $data->empenhado - $data->liquidado;
                                        $aDespesas[$chave]->o56_descr = $data->o56_descr;
                                        // $aDespesas[$chave]->o58_fonte = $data->o58_codigo;
                                        // $aDespesas[$chave]->o56_anousu = $data->o56_anousu;
                                    }
                                }
                            }
                        }

                        if ($iRpps) {
                            foreach (getDespesaMensalExclusaoSaldoIntaivosPensionistasProprioDeduzir("01-01-" . date("Y", strtotime($dtini)), "31-12-" . date("Y", strtotime($dtini)), $aInstits, $iCamara) as $data) {
                                $chave = str_pad(substr($data->o58_elemento, 0, 7), 13, "0");
                                if (substr($chave, 1, 2) == "31") {
                                    if ($data->empenhado - $data->liquidado <> 0) {
                                        $aDespesas[$chave]->liquidado -= $data->empenhado - $data->liquidado;
                                        $aDespesas[$chave]->o56_descr = $data->o56_descr;
                                    }
                                }
                            }

                            foreach (getDespesaMensalExclusaoSaldoIntaivosPensionistasProprioDeduzir($dtini, "31-12-" . date("Y", strtotime($dtini)), $aInstits, $iCamara) as $data) {
                                $chave = str_pad(substr($data->o58_elemento, 0, 7), 13, "0");
                                if (substr($chave, 1, 2) == "31") {
                                    if ($data->empenhado - $data->liquidado <> 0) {
                                        $aDespesas[$chave]->$valoresperado -= $data->$valoresperado;
                                        $aDespesas[$chave]->o56_descr = $data->o56_descr;
                                    }
                                }
                            }

                            foreach (getDespesaMensalExclusaoSaldoIntaivosPensionistasProprioDeduzir("01-01-" . date("Y", strtotime($dtfim)), $dtfim, $aInstits, $iCamara) as $data) {
                                $chave = str_pad(substr($data->o58_elemento, 0, 7), 13, "0");
                                if (substr($chave, 1, 2) == "31") {
                                    if ($data->empenhado - $data->liquidado <> 0) {
                                        $aDespesas[$chave]->$valoresperado -= $data->$valoresperado;
                                        $aDespesas[$chave]->o56_descr = $data->o56_descr;
                                    }
                                }
                            }
                        }

                        ksort($aDespesas);

                        foreach ($despesa as $elemento => $datas) {
                            $subtotal = 0;
                            if (substr($elemento, 1, 2) == "31") { ?>
                                <tr>
                                    <tr style='height:19px;'>
                                        <td class="s3 bdleft" colspan="2">
                                        <?php
                                                $subtotal += $datas[$oInstit->getCodigo()];
                                                $fSubTotal += $datas[$oInstit->getCodigo()];
                                                if ($subtotal <> 0) {
                                            ?>

                                            <?php echo db_formatar(str_pad($elemento, 15, 0), "elemento") . " - " . $despesa[$elemento]['descricao'];
                                            } ?>
                                        </td>

                                        <td class="s5">

                                            <?php
                                                if ($subtotal <> 0) {
                                                    echo db_formatar($subtotal, "f");
                                                }
                                            ?>
                                        </td>
                                    </tr>
                        <?
                            }
                        }

                        // Final do bloco de copia do mensal
                        // Removi blodo daqui
                        ?>
                        <tr style='height:19px;'>
                            <td class="s3 bdleft" colspan="2">3.3.00.00.00 - OUTRAS DESPESAS CORRENTES</td>
                            <td class="s4"></td>
                        </tr>
                        <?php
                        if (temDataImplantacao($dtini)) {
                            foreach (getValorDespesaInformado($oDataIni->getDate("Y-m-d"), buscarDataImplantacao(), '3339034', $valorcalculoManual, $oInstit) as $oDespesa) {
                                $chave = $oDespesa->o58_elemento;
                                if (array_key_exists($chave, $aDespesas)) {
                                    $aDespesas2[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                } else {
                                    $aDespesas2[$chave] = $oDespesa;
                                }
                            }

                            foreach (getValorDespesaInformado($oDataIni->getDate("Y-m-d"), buscarDataImplantacao(), '3339004', $valorcalculoManual, $oInstit) as $oDespesa) {
                                $chave = $oDespesa->o58_elemento;
                                if (array_key_exists($chave, $aDespesas)) {
                                    $aDespesas2[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                } else {
                                    $aDespesas2[$chave] = $oDespesa;
                                }
                            }

                            foreach (getSaldoDespesa(null, "o58_elemento, o56_descr, SUM({$valoresperado}) {$valoresperado} ", null, "o58_elemento like '3339034%' and o58_instit = {$oInstit->getCodigo()} group by 1,2") as $oDespesa) {
                                $chave = $oDespesa->o58_elemento;
                                if (array_key_exists($chave, $aDespesas)) {
                                    $aDespesas2[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                }
                            }

                            foreach (getSaldoDespesa(null, "o58_elemento, o56_descr, SUM({$valoresperado}) {$valoresperado} ", null, "o58_elemento like '3339004%' and o58_instit = {$oInstit->getCodigo()} group by 1,2") as $oDespesa) {
                                $chave = $oDespesa->o58_elemento;
                                if (array_key_exists($chave, $aDespesas)) {
                                    echo "aadsa";
                                    $aDespesas2[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                }
                            }


                        } else {
                            foreach (getSaldoDespesa(null, "o58_elemento, o56_descr, SUM({$valoresperado}) {$valoresperado} ", null, "o58_elemento like '3339034%' and o58_instit = {$oInstit->getCodigo()} group by 1,2") as $oDespesa) {
                                $chave = $oDespesa->o58_elemento;

                                if ($oDespesa->$valoresperado <> 0) {
                                    if (array_key_exists($chave, $oDespesa)) {
                                        $aDespesas2[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                    } else {
                                        $aDespesas2[$chave] = $oDespesa;
                                    }
                                }
                            }

                            foreach (getSaldoDespesa(null, "o58_elemento, o56_descr, SUM({$valoresperado}) {$valoresperado} ", null, "o58_elemento like '3339004%' and o58_instit = {$oInstit->getCodigo()} group by 1,2") as $oDespesa) {
                                $chave = $oDespesa->o58_elemento;

                                if ($oDespesa->$valoresperado <> 0) {
                                    if (array_key_exists($chave, $oDespesa)) {
                                        $aDespesas2[$chave]->$valoresperado += $oDespesa->$valoresperado;
                                    } else {
                                        $aDespesas2[$chave] = $oDespesa;
                                    }
                                }
                            }
                        }

                        if ($valoresperado == 'liquidado') {
                            foreach (getDespesaMensal("01-01-" . date("Y", strtotime($dtini)), "31-12-" . date("Y", strtotime($dtini)), array($oInstit->getCodigo())) as $data) {
                                $chave = str_pad(substr($data->o58_elemento, 0, 7), 13, "0");
                                if (substr($chave, 0, 7) == "3339034" || substr($chave, 0, 7) == "3339004") {
                                    if ($data->empenhado - $data->liquidado <> 0) {
                                        $aDespesas2[$chave]->liquidado += $data->empenhado - $data->liquidado;
                                        $aDespesas2[$chave]->o56_descr = $data->o56_descr;
                                    }
                                }
                            }
                        }

                        foreach ($aDespesas2 as $oDespesa) :
                            $fSubTotal += $oDespesa->$valoresperado;
                        ?>

                            <tr style='height:19px;'>
                                <td class="s3 bdleft" colspan="2">
                                    <?php echo db_formatar($oDespesa->o58_elemento, "elemento") . " - " . $oDespesa->o56_descr; ?>
                                </td>
                                <td class="s5">
                                    <?php echo db_formatar($oDespesa->$valoresperado, "f"); ?>
                                </td>
                            </tr>
                    <?php endforeach;
                    } else {
                    }
                    ?>
                    <?php if ($tipoEmissao == 1) { ?>
                        <tr style='height:19px;'>
                            <td class="s2 bdleft bdtop" colspan="2">SUB-TOTAL</td>
                            <td class="s6 bdtop">
                                <?php echo db_formatar($fSubTotal, "f");
                                $fTotalDespesas += $fSubTotal; ?>
                            </td>
                        </tr>
                    <? } ?>
                <?php endforeach; ?>


                <tr style='height:19px;'>
                    <? if ($tipoEmissao == 1) { ?>
                        <td class="s3 bdleft" colspan="2">TOTAL DAS DESPESAS COM PESSOAL NO MUNIC�PIO</td>
                        <td class="s5"><?= db_formatar($fTotalDespesas, "f") ?></td>
                    <? } else { ?>
                        <td class="s7 bdleft bdtop" colspan="1">TOTAL DAS DESPESAS COM PESSOAL NO MUNIC�PIO</td>
                        <? $subtotalmesfinal = 0; ?>
                        <? for ($i = 0; $i <= 11; $i++) { ?>
                            <td class="bdleft bdtop s6"><?= db_formatar($subtotalmes[$meses[$i]], "f") ?></td>
                            <? $subtotalmesfinal += $subtotalmes[$meses[$i]] ?>
                        <? } ?>
                        <td class="bdleft bdtop s6"><?= db_formatar($subtotalmesfinal, "f") ?></td>
                    <? } ?>
                </tr>

                <tr style='height:19px;'>
                    <? if ($tipoEmissao == 1) { ?>
                        <td class="s3 bdleft" colspan="2">(-) Inativos e Pensionistas com Fonte de Custeio Pr�prio</td>
                        <td class="s5">
                            <?php
                                $encontrouElemento = 0;

                                foreach ($totalDespesa as $elemento => $datas) {
                                    $subtotal = 0;

                                    if (in_array(substr($elemento, 0, 7), array("3319001", "3319003"))
                                        && in_array($datas['o58_fonte'], array("18000000", "18000001", "18000002", "18010000", "28000000", "28000001", "28000002", "28010000"))) {

                                        $encontrouElemento = 1;

                                        for ($i = 0; $i <= count($aInstits); $i++) {
                                            if (array_key_exists($aInstits[$i], $datas)) {
                                                $fSaldoIntaivosPensionistasProprio += $datas[$aInstits[$i]];
                                            } else {
                                                $fSaldoIntaivosPensionistasProprio += 0;
                                            }
                                        }
                                    }
                                }

                                echo db_formatar($fSaldoIntaivosPensionistasProprio, "f");
                            ?>
                        </td>
                <? } else { ?>
                    <td class="s3 bdleft bdtop" colspan="1">(-) Inativos e Pensionistas com Fonte de Custeio Pr�prio</td>
                    <?php
                        $encontrouElemento = 0;


                        foreach ($totalDespesa as $elemento => $datas) {

                           $subtotal = 0;
                            if ((in_array(substr($elemento, 0, 7), array("3319001", "3319003"))
                                && in_array($datas['o58_fonte'], array("18000000", "18000001", "18000002", "18010000", "28000000", "28000001", "28000002", "28010000")))) {
                                for ($i = 0; $i <= 11; $i++) {
                                    if (array_key_exists($meses[$i], $datas)) {
                                        $encontrouElemento = 1;
                                        $fSaldoIntaivosPensionistasProprio[$i] += $datas[$meses[$i]];
                                    } else {
                                        $fSaldoIntaivosPensionistasProprio[$i] += 0;
                                    }
                                }
                            }
                        }
                        if (!$encontrouElemento) {
                            for ($i = 0; $i <= 12; $i++) {
                                echo "<td class='bdleft bdtop s6'>0,00</td>";
                            }
                        } else {
                            for ($i = 0; $i <= 11; $i++) {
                                echo '<td class="bdleft bdtop s6">';
                                $subtotal += $fSaldoIntaivosPensionistasProprio[$i];
                                echo db_formatar($fSaldoIntaivosPensionistasProprio[$i], "f");
                                echo '</td>';
                            }
                            echo '<td class="bdleft bdtop s6">' . db_formatar($subtotal, "f") . '</td>';
                        }
                    }
            ?>
            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Senten�as Judiciais Anteriores</td>
                    <td class="s5">
                        <?php
                            $encontrouElemento = 0;

                            foreach ($despesaSaldoSentencasJudAnt as $elemento => $datas) {
                                $subtotal = 0;
                                if (in_array(substr($elemento, 0, 7), array("3319091", "3319191", "3319691"))) {
                                    $encontrouElemento = 1;
                                    for ($i = 0; $i <= count($aInstits); $i++) {
                                        if (array_key_exists($aInstits[$i], $datas)) {
                                            $fSaldoSentencasJudAnt += $datas[$aInstits[$i]];;
                                        } else {
                                            $fSaldoSentencasJudAnt += 0;
                                        }
                                    }
                                }
                            }

                            echo db_formatar($fSaldoSentencasJudAnt, "f");
                        ?>
                    </td>
                <? } else { ?>
                    <td class="s3 bdleft bdtop" colspan="1">(-) Senten�as Judiciais Anteriores</td>
                <?php
                    $encontrouElemento = 0;

                    foreach ($despesaSaldoSentencasJudAnt as $elemento => $datas) {
                        $subtotal = 0;
                        if (in_array(substr($elemento, 0, 7), array("3319091", "3319191", "3319691"))) {
                            $encontrouElemento = 1;
                            for ($i = 0; $i <= 11; $i++) {
                                if (array_key_exists($meses[$i], $datas)) {
                                    $fSaldoSentencasJudAnt[$i] += $datas[$meses[$i]];;
                                } else {
                                    $fSaldoSentencasJudAnt[$i] += 0;
                                }
                            }
                        }
                    }
                    if (!$encontrouElemento) {
                        for ($i = 0; $i <= 12; $i++) {
                            echo "<td class='bdleft bdtop s6'>0,00</td>";
                        }
                    } else {
                        for ($i = 0; $i <= 11; $i++) {
                            echo '<td class="bdleft bdtop s6">';
                            $subtotal += $fSaldoSentencasJudAnt[$i];
                            echo db_formatar($fSaldoSentencasJudAnt[$i], "f");
                            echo '</td>';
                        }
                        echo '<td class="bdleft bdtop s6">' . db_formatar($subtotal, "f") . '</td>';
                    }
                }
                ?>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Despesa de Exerc�cios Anteriores</td>
                    <td class="s5">
                        <?php
                        $encontrouElemento = 0;

                        foreach ($despesaSaldoDespesasAnteriores as $elemento => $datas) {
                            $subtotal = 0;
                            if (in_array(substr($elemento, 0, 7), array("3319092", "3319192", "3319692"))) {
                                $encontrouElemento = 1;
                                for ($i = 0; $i <= count($aInstits); $i++) {
                                    if (array_key_exists($aInstits[$i], $datas)) {
                                        $fSaldoDespesasAnteriores += $datas[$aInstits[$i]];
                                    } else {
                                        $fSaldoDespesasAnteriores += 0;
                                    }
                                }
                            }
                        }

                        echo db_formatar($fSaldoDespesasAnteriores, "f");
                        ?>
                    </td>
                <? } else { ?>
                    <td class="s3 bdleft bdtop" colspan="1">(-) Despesa de Exerc�cios Anteriores</td>
                <?
                    $encontrouElemento = 0;

                    foreach ($despesaSaldoDespesasAnteriores as $elemento => $datas) {
                        $subtotal = 0;
                        if (in_array(substr($elemento, 0, 7), array("3319092", "3319192", "3319692"))) {
                            $encontrouElemento = 1;
                            for ($i = 0; $i <= 11; $i++) {
                                if (array_key_exists($meses[$i], $datas)) {
                                    $fSaldoDespesasAnteriores[$i] += $datas[$meses[$i]];
                                } else {
                                    $fSaldoDespesasAnteriores[$i] += 0;
                                }
                            }
                        }
                    }

                    if (!$encontrouElemento) {
                        for ($i = 0; $i <= 12; $i++) {
                            echo "<td class='bdleft bdtop s6'>0,00</td>";
                        }
                    } else {
                        for ($i = 0; $i <= 11; $i++) {
                            echo '<td class="bdleft bdtop s6">';
                            $subtotal += $fSaldoDespesasAnteriores[$i];
                            echo db_formatar($fSaldoDespesasAnteriores[$i], "f");
                            echo '</td>';
                        }
                        echo '<td class="bdleft bdtop s6">' . db_formatar(abs($subtotal), "f") . '</td>';

                    }
                }
                ?>
            </tr>
            <? if ($anousu <= 2018) { ?>
                <tr style='height:19px;'>
                    <? if ($tipoEmissao == 1) { ?>
                        <td class="s3 bdleft" colspan="2">(-) Aposentadorias e Pens�es Custeadas c/Rec.Fonte Tesouro</td>
                        <td class="s5">
                            <?php
                            $fSaldoAposentadoriaPensoesTesouro = 0;
                            foreach ($aInstits as $iInstit) {
                                $oInstit = new Instituicao($iInstit);
                                if ($oInstit->getTipoInstit() == Instituicao::TIPO_INSTIT_PREFEITURA || $oInstit->getTipoInstit() == Instituicao::TIPO_INSTIT_CAMARA) {
                                    if (temDataImplantacao($dtini)) {
                                        $aSaldoEstrut1 = getValorDespesaInformado($oDataIni->getDate("Y-m-d"), buscarDataImplantacao(), '3319692', $valorcalculoManual, $oInstit);
                                        $aSaldoEstrut2 = getValorDespesaInformado($oDataIni->getDate("Y-m-d"), buscarDataImplantacao(), '3319692', $valorcalculoManual, $oInstit);
                                        $aSaldoEstrut3 = getValorDespesaInformado($oDataIni->getDate("Y-m-d"), buscarDataImplantacao(), '3319692', $valorcalculoManual, $oInstit);

                                        $fSaldo1 = ($aSaldoEstrut1[0]->o58_anousu == substr($dtini, 0, 4) && $aSaldoEstrut1[0]->o58_anousu <= 2018) ? $aSaldoEstrut1[0]->$valoresperado : 0;
                                        $fSaldo2 = ($aSaldoEstrut2[0]->o58_anousu == substr($dtini, 0, 4) && $aSaldoEstrut2[0]->o58_anousu <= 2018) ? $aSaldoEstrut2[0]->$valoresperado : 0;
                                        $fSaldo3 = ($aSaldoEstrut3[0]->o58_anousu == substr($dtini, 0, 4) && $aSaldoEstrut3[0]->o58_anousu <= 2018) ? $aSaldoEstrut3[0]->$valoresperado : 0;
                                        $fSaldoAposentadoriaPensoesTesouro += $fSaldo1 + $fSaldo2 + $fSaldo3;

                                        $aSaldoEstrut1 = getSaldoDespesa(null, "o58_anousu, o58_elemento, o56_descr,{$valorcalculo}", null, "o58_elemento like '3319001%' and o58_instit = {$oInstit->getCodigo()} and o58_codigo not in (103, 203, 18000000, 28000000) group by 1,2,3");
                                        $aSaldoEstrut2 = getSaldoDespesa(null, "o58_anousu, o58_elemento, o56_descr, {$valorcalculo}", null, "o58_elemento like '3319003%' and o58_instit = {$oInstit->getCodigo()} and o58_codigo not in (103, 203, 18000000, 28000000) group by 1,2,3");
                                        $aSaldoEstrut3 = getSaldoDespesa(null, "o58_anousu, o58_elemento, o56_descr, {$valorcalculo}", null, "o58_elemento like '3319005%' and o58_instit = {$oInstit->getCodigo()} and o58_codigo not in (103, 203, 18000000, 28000000) group by 1,2,3");
                                    } else {

                                        $aSaldoEstrut1 = getSaldoDespesa(null, "o58_anousu, o58_elemento, o56_descr,{$valorcalculo}", null, "o58_elemento like '3319001%' and o58_instit = {$oInstit->getCodigo()} and o58_codigo not in (103, 203, 18000000, 28000000) group by 1,2,3");
                                        $aSaldoEstrut2 = getSaldoDespesa(null, "o58_anousu, o58_elemento, o56_descr, {$valorcalculo}", null, "o58_elemento like '3319003%' and o58_instit = {$oInstit->getCodigo()} and o58_codigo not in (103, 203, 18000000, 28000000) group by 1,2,3");
                                        $aSaldoEstrut3 = getSaldoDespesa(null, "o58_anousu, o58_elemento, o56_descr, {$valorcalculo}", null, "o58_elemento like '3319005%' and o58_instit = {$oInstit->getCodigo()} and o58_codigo not in (103, 203, 18000000, 28000000) group by 1,2,3");
                                    }
                                    $fSaldo1 = ($aSaldoEstrut1[0]->o58_anousu == substr($dtini, 0, 4) && $aSaldoEstrut1[0]->o58_anousu <= 2018) ? $aSaldoEstrut1[0]->$valoresperado : 0;
                                    $fSaldo2 = ($aSaldoEstrut2[0]->o58_anousu == substr($dtini, 0, 4) && $aSaldoEstrut2[0]->o58_anousu <= 2018) ? $aSaldoEstrut2[0]->$valoresperado : 0;
                                    $fSaldo3 = ($aSaldoEstrut3[0]->o58_anousu == substr($dtini, 0, 4) && $aSaldoEstrut3[0]->o58_anousu <= 2018) ? $aSaldoEstrut3[0]->$valoresperado : 0;
                                    $fSaldoAposentadoriaPensoesTesouro += $fSaldo1 + $fSaldo2 + $fSaldo3;
                                }
                            }
                            echo db_formatar($fSaldoAposentadoriaPensoesTesouro, "f");
                            ?>
                        </td>
                    <? } else { ?>
                        <td class="s3 bdleft bdtop" colspan="1">(-) Aposentadorias e Pens�es Custeadas c/Rec.Fonte Tesouro</td>
                    <?php
                        $encontrouElemento = 0;

                        foreach ($despesa2 as $elemento => $datas) {
                            $subtotal = 0;
                            if (in_array(substr($elemento, 0, 7), array("3319001", "3319003", "3319005"))) {
                                $encontrouElemento = 1;
                                for ($i = 0; $i <= 11; $i++) {
                                    if (array_key_exists($meses[$i], $datas)) {
                                        $fSaldoAposentadoriaPensoesTesouro[$i] += $datas[$meses[$i]];;
                                    } else {
                                        $fSaldoAposentadoriaPensoesTesouro[$i] += 0;
                                    }
                                }
                            }
                        }
                        if (!$encontrouElemento) {
                            for ($i = 0; $i <= 12; $i++) {
                                echo "<td class='bdleft bdtop s6'>0,00</td>";
                            }
                        } else {
                            for ($i = 0; $i <= 11; $i++) {
                                echo '<td class="bdleft bdtop s6">';
                                $subtotal += $fSaldoAposentadoriaPensoesTesouro[$i];
                                echo db_formatar($fSaldoAposentadoriaPensoesTesouro[$i], "f");
                                echo '</td>';
                            }
                            echo '<td class="bdleft bdtop s6">' . db_formatar($subtotal, "f") . '</td>';
                        }
                    }
                    ?>
                </tr>
            <? } ?>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Indeniza��es por Demiss�o e Incentivos � Demiss�o Volunt�ria</td>
                    <td class="s5">
                        <?php
                        $encontrouElemento = 0;

                        foreach ($despesaSaldoIndenizacaoDemissaoServidores as $elemento => $datas) {
                            $subtotal = 0;

                            if (in_array(substr($elemento, 0, 7), array("3319094", "3319194", "3319594", "3319694"))) {
                                $encontrouElemento = 1;
                                for ($i = 0; $i <= count($aInstits); $i++) {
                                    if (array_key_exists($aInstits[$i], $datas)) {
                                        $fSaldoIndenizacaoDemissaoServidores += $datas[$aInstits[$i]];
                                    } else {
                                        $fSaldoIndenizacaoDemissaoServidores += 0;
                                    }
                                }
                            }
                        }

                        echo db_formatar($fSaldoIndenizacaoDemissaoServidores, "f");
                        ?>
                    </td>
                <? } else { ?>
                    <td class="s3 bdleft bdtop" colspan="1">(-) Indeniza��es por Demiss�o e Incentivos � Demiss�o Volunt�ria</td>
                    <?php $encontrouElemento = 0;
                    foreach ($despesaSaldoIndenizacaoDemissaoServidores as $elemento => $datas) { ?>
                        <?php $subtotal = 0; ?>

                        <?php if (in_array(substr($elemento, 0, 7), array("3319094", "3319194", "3319594", "3319694"))) { ?>
                        <?php $encontrouElemento = 1;

                            for ($i = 0; $i <= 11; $i++) {
                                if (array_key_exists($meses[$i], $datas)) {
                                    $fSaldoIndenizacaoDemissaoServidores[$i] += $datas[$meses[$i]];;
                                } else {
                                    if (!array_key_exists($i, $fSaldoIndenizacaoDemissaoServidores)) {
                                        $fSaldoIndenizacaoDemissaoServidores[$i] += 0;
                                    }
                                }
                            }
                        }
                    }

                    if (!$encontrouElemento) {
                        for ($i = 0; $i <= 12; $i++) {
                            echo "<td class='bdleft bdtop s6'>0,00</td>";
                        }
                    } else {
                        for ($i = 0; $i <= 11; $i++) {
                            echo '<td class="bdleft bdtop s6">';


                            if($i==11){

                                   $subtotal += $fSaldoIndenizacaoDemissaoServidores[$i];
                                   echo db_formatar($fSaldoIndenizacaoDemissaoServidores[$i], "f");
                            }
                            else{
                                $subtotal += $fSaldoIndenizacaoDemissaoServidores[$i];

                                echo db_formatar($fSaldoIndenizacaoDemissaoServidores[$i], "f");
                            }
                            echo '</td>';
                        }
                        echo '<td class="bdleft bdtop s6">' . db_formatar($subtotal, "f") . '</td>';
                    }

                }
                ?>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Agentes Comunit�rios de Sa�de e de Combate �s Endemias com Recursos Vinculados (CF, art. 198, � 11)</td>
                    <td class="s5">
                        <?php
                        $encontrouFonte = 0;
                        foreach ($totalDespesa as $elemento => $datas) {
                            $subtotal = 0;

                            if (in_array($datas['o58_fonte'], array("16040000", "26040000"))) {
                                $encontrouFonte = 1;

                                for ($i = 0; $i <= count($aInstits); $i++) {
                                    if (array_key_exists($aInstits[$i], $datas)) {
                                        $fAgenteComunitSaudeRecursosVinculados += $datas[$aInstits[$i]];
                                    } else {
                                        $fAgenteComunitSaudeRecursosVinculados += 0;
                                    }
                                }
                            }
                        }

                        echo db_formatar($fAgenteComunitSaudeRecursosVinculados, "f");
                        ?>
                    </td>
                <? } else { ?>
                    <td class="s3 bdleft bdtop" colspan="1">(-) Agentes Comunit�rios de Sa�de e de Combate �s Endemias com Recursos Vinculados (CF, art. 198, � 11)</td>
                    <?php
                    $encontrouFonte = 0;

                    foreach ($totalDespesa as $elemento => $datas) { ?>
                        <?php $subtotal = 0; ?>

                        <?php if (in_array($datas['o58_fonte'], array("16040000", "26040000"))) { ?>
                        <?php $encontrouFonte = 1;

                            for ($i = 0; $i <= 11; $i++) {
                                if (array_key_exists($meses[$i], $datas)) {
                                    $fAgenteComunitSaudeRecursosVinculados[$i] += $datas[$meses[$i]];
                                } else {
                                    if (!array_key_exists($i, $fAgenteComunitSaudeRecursosVinculados)) {
                                        $fAgenteComunitSaudeRecursosVinculados[$i] += 0;
                                    }
                                }
                            }
                        }
                    }

                    if (!$encontrouFonte) {
                        for ($i = 0; $i <= 12; $i++) {
                            echo "<td class='bdleft bdtop s6'>0,00</td>";
                        }
                    } else {
                        for ($i = 0; $i <= 11; $i++) {
                            echo '<td class="bdleft bdtop s6">';


                            if($i==11){

                                   $subtotal += $fAgenteComunitSaudeRecursosVinculados[$i];
                                   echo db_formatar($fAgenteComunitSaudeRecursosVinculados[$i], "f");
                            }
                            else{
                                $subtotal += $fAgenteComunitSaudeRecursosVinculados[$i];

                                echo db_formatar($fAgenteComunitSaudeRecursosVinculados[$i], "f");
                            }
                            echo '</td>';
                        }
                        echo '<td class="bdleft bdtop s6">' . db_formatar($subtotal, "f") . '</td>';
                    }
                }
                ?>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Parcela dedut�vel referente ao piso salarial do Enfermeiro, T�cnico de Enfermagem, Auxiliar de Enfermagem e Parteira (ADCT, art. 38, � 2�)</td>
                    <td class="s5">
                        <?php
                        $encontrouFonte = 0;

                        foreach ($totalDespesa as $elemento => $datas) {
                            $subtotal = 0;

                            if (in_array($datas['o58_fonte'], array("16050000", "26050000"))) {
                                $encontrouFonte = 1;

                                for ($i = 0; $i <= count($aInstits); $i++) {

                                    if (array_key_exists($aInstits[$i], $datas)) {
                                        $vlrParcelaMes = $datas[$aInstits[$i]];

                                        if ($datas['o58_anousu'] == "2024") {
                                            $vlrParcelaMes = $vlrParcelaMes * 0.9;
                                        }

                                        $fParcelaDedutivelEnfermagem += $vlrParcelaMes;
                                    } else {
                                        $fParcelaDedutivelEnfermagem += 0;
                                    }
                                }
                            }
                        }

                        echo db_formatar($fParcelaDedutivelEnfermagem, "f");

                        ?>
                    </td>
                <? } else { ?>
                    <td class="s3 bdleft bdtop" colspan="1">(-) Parcela dedut�vel referente ao piso salarial do Enfermeiro, T�cnico de Enfermagem, Auxiliar de Enfermagem e Parteira (ADCT, art. 38, � 2�)</td>
                    <?php
                    $encontrouFonte = 0;

                    foreach ($totalDespesa as $elemento => $datas) { ?>
                        <?php $subtotal = 0; ?>

                        <?php if (in_array($datas['o58_fonte'], array("16050000", "26050000"))) { ?>
                        <?php $encontrouFonte = 1;

                            for ($i = 0; $i <= 11; $i++) {
                                if (array_key_exists($meses[$i], $datas)) {
                                    $vlrParcelaMes = $datas[$meses[$i]];

                                    if (strpos($meses[$i],"2024")) {
                                            $vlrParcelaMes = $vlrParcelaMes * 0.9;
                                        }

                                    $fParcelaDedutivelEnfermagem[$i] += round($vlrParcelaMes, 2);
                                } else {
                                    if (!array_key_exists($i, $fParcelaDedutivelEnfermagem)) {
                                        $fParcelaDedutivelEnfermagem[$i] += 0;
                                    }
                                }
                            }
                        }
                    }

                    if (!$encontrouFonte) {
                        for ($i = 0; $i <= 12; $i++) {
                            echo "<td class='bdleft bdtop s6'>0,00</td>";
                        }
                    } else {
                        for ($i = 0; $i <= 11; $i++) {
                            echo '<td class="bdleft bdtop s6">';


                            if($i==11){

                                   $subtotal += $fParcelaDedutivelEnfermagem[$i];
                                   echo db_formatar($fParcelaDedutivelEnfermagem[$i], "f");
                            }
                            else{
                                $subtotal += $fParcelaDedutivelEnfermagem[$i];

                                echo db_formatar($fParcelaDedutivelEnfermagem[$i], "f");
                            }
                            echo '</td>';
                        }
                        echo '<td class="bdleft bdtop s6">' . db_formatar($subtotal, "f") . '</td>';
                    }
                }
                ?>



            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Outras Dedu��es Constitucionais ou Legais</td>
                    <td class="s5">
                        <?php
                        echo db_formatar(0, "f");
                        ?>
                    </td>
                <? } else { ?>
                    <td class="s3 bdleft bdtop" colspan="1">(-) Outras Dedu��es Constitucionais ou Legais</td>
                    <?php
                    $encontrouElemento = 0;

                    if (!$encontrouElemento) {
                        for ($i = 0; $i <= 12; $i++) {
                            echo "<td class='bdleft bdtop s6'>0,00</td>";
                        }
                    } else {
                        for ($i = 0; $i <= 11; $i++) {
                            echo '<td class="bdleft bdtop s6">';
                            echo db_formatar(0, "f");
                            echo '</td>';
                        }
                        echo '<td class="bdleft bdtop s6">' . db_formatar(0, "f") . '</td>';
                    }
                }
                ?>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s7 bdleft" colspan="2">TOTAL DAS DESPESAS COM PESSOAL = BASE DE C�LCULO</td>
                    <td class="s8">
                        <?php
                        $fTotalDespesaPessoal = $fTotalDespesas - ($fSaldoIntaivosPensionistasProprio + $fSaldoSentencasJudAnt + $fSaldoAposentadoriaPensoesTesouro + $fSaldoDespesasAnteriores + $fSaldoIndenizacaoDemissaoServidores + $fAgenteComunitSaudeRecursosVinculados + $fParcelaDedutivelEnfermagem);
                        echo db_formatar($fTotalDespesaPessoal, "f");
                        ?>
                    </td>
                <? } else { ?>
                    <td class="s7 bdleft bdtop" colspan="1">Total da Despesa com Pessoal para Fins de apura��o de Limite</td>
                    <? $subtotalmesfinal = 0; ?>
                    <? for ($i = 0; $i <= 11; $i++) {
                        $fTotalDespesaPessoal = $subtotalmes[$meses[$i]] - ($fSaldoIntaivosPensionistasProprio[$i] + $fSaldoSentencasJudAnt[$i] + $fSaldoAposentadoriaPensoesTesouro[$i] + $fSaldoDespesasAnteriores[$i] + $fSaldoIndenizacaoDemissaoServidores[$i] + $fAgenteComunitSaudeRecursosVinculados[$i] + $fParcelaDedutivelEnfermagem[$i]); ?>
                        <td class="bdleft bdtop s8"><?= db_formatar($fTotalDespesaPessoal, "f") ?></td>
                        <? $subtotalmesfinal += $fTotalDespesaPessoal ?>
                    <? } ?>
                    <td class="bdleft bdtop s8"><?= db_formatar($subtotalmesfinal, "f") ?></td>
                <? } ?>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s1 bdleft" colspan="3">II) RECEITA</td>
                <? } else { ?>
                    <td class="s1 bdleft" colspan="15">II) RECEITA</td>
                <? } ?>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s7 bdleft" colspan="2">Receita Corrente do Munic�pio</td>
                <? } else { ?>
                    <td class="s9 bdleft" colspan="13"><b>RECEITA CORRENTE DO MUNIC�PIO</b></td>
                <? } ?>
                <td class="s8">
                    <?php
                    $fValorManualRCL = getValorManual($codigorelatorio, 1, $oInstit->getCodigo(), $o116_periodo, $iAnousu);
                    $fRCL += $fValorManualRCL == NULL ? $fTotalReceitasArrecadadas : $fValorManualRCL;
                    echo db_formatar($fTotalReceitasArrecadadas, "f");
                    //echo db_formatar($fRCL, "f");
                    ?>
                </td>
            </tr>
            <!--
                      <tr style='height:19px;'>
                        <td class="s3 bdleft" colspan="2">(-) Receita Corrente Intraor�ament�ria</td>
                        <td class="s5">
                          <?php
                            //$aDadosRCI = getSaldoReceita(null, "sum(saldo_arrecadado) as saldo_arrecadado", null, "o57_fonte like '47%'");
                            $fRCI = 0; //count($aDadosRCI) > 0 ? $aDadosRCI[0]->saldo_arrecadado : 0;
                            //echo db_formatar($fRCI, "f");
                            ?>
                        </td>
                      </tr>
                    -->
            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Dedu��es da Receita Corrente (Exceto FUNDEB)</td>
                    <td class="s5">
                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Dedu��es da Receita Corrente (Exceto FUNDEB)</td>
                    <td class="s6">
                    <? } ?>

                    <?php
                    echo db_formatar(abs($fCSACRPPS), "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Dedu��es de Receita para forma��o do FUNDEB</td>
                    <td class="s5">

                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Dedu��es de Receita para forma��o do FUNDEB</td>
                    <td class="s6">
                    <? } ?>

                    <?php
                    echo db_formatar(abs($fCSICRPPS), "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Receitas Corrente Intraor�ament�ria</td>
                    <td class="s5">
                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Receitas Corrente Intraor�ament�ria</td>
                    <td class="s6">
                    <? } ?>

                    <?php
                    echo db_formatar($fCPRPPS, "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Contribui��o dos Servidores para o Sistema Pr�prio de Previd�ncia</td>
                    <td class="s5">
                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Contribui��o dos Servidores para o Sistema Pr�prio de Previd�ncia</td>
                    <td class="s6">
                    <? } ?>


                    <?php

                    echo db_formatar($fRRCSACOPSJ, "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Compensa��o entre Regimes de Previd�ncia</td>
                    <td class="s5">
                <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Compensa��o entre Regimes de Previd�ncia</td>
                    <td class="s6">
                <? } ?>
                    <?php echo db_formatar($fRRCSICOPSJ, "f"); ?>
                </td>
            </tr>

            <? if (db_getsession("DB_anousu") >= 2021) { ?>
                <tr style='height:19px;'>
                    <? if ($tipoEmissao == 1) { ?>
                        <td class="s3 bdleft" colspan="2">(-) Rendimentos de Aplica��es de Recursos Previdenci�rios</td>
                        <td class="s5">
                    <? } else { ?>
                        <td class="s9 bdleft" colspan="13">(-) Rendimentos de Aplica��es de Recursos Previdenci�rios</td>
                        <td class="s6">
                    <? } ?>
                        <?php echo db_formatar($fRARP, "f"); ?>
                    </td>
                </tr>
            <? } ?>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2"><b>RECEITA CORRENTE L�QUIDA</b></td>
                    <td class="s5">
                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13"><b>RECEITA CORRENTE L�QUIDA</b></td>
                    <td class="s6">
                    <? } ?>


                    <?php
                    $fRecCorrLiq = $fTotalReceitasArrecadadas - abs($fCSACRPPS) - abs($fCSICRPPS) - $fCPRPPS - $fRRCSACOPSJ - $fRRCSICOPSJ - $fRARP;
                    echo db_formatar($fRecCorrLiq, "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Transfer�ncias obrigat�rias da Uni�o relativas �s emendas individuais</td>
                    <td class="s5">
                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Transfer�ncias obrigat�rias da Uni�o relativas �s emendas individuais (art. 166-A, � 1o, da CF)</td>
                    <td class="s6">
                    <? } ?>
                    <?php

                    if ($oDataFim->getAno() >= 2020) {
                        if (temDataImplantacao($dtini)) {
                            $aSaldoArrecadadoEmenda = getValorReceitaEmendaInformado($dtini, buscarDataImplantacao(), $iInstituicoes, array(1, 4));
                            $fCFRP += $aSaldoArrecadadoEmenda[0]->arrecadado_emenda_parlamentar;
                            $aSaldoArrecadadoEmenda = getSaldoArrecadadoEmendaParlamentar(buscarDataImplantacao(), $dtfim, array(1, 4));
                            $fCFRP += $aSaldoArrecadadoEmenda[0]->arrecadado_emenda_parlamentar;
                        } else {
                            $aSaldoArrecadadoEmenda = getSaldoArrecadadoEmendaParlamentar($dtini, $dtfim, array(1, 4));
                            $fCFRP += $aSaldoArrecadadoEmenda[0]->arrecadado_emenda_parlamentar;
                        }
                    }

                    echo db_formatar($fCFRP, "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Transfer�ncias obrigat�rias da Uni�o relativas �s emendas de bancada.</td>
                    <td class="s5">
                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Transfer�ncias obrigat�rias da Uni�o relativas �s emendas de bancada.</td>
                    <td class="s6">
                    <? } ?>
                    <?php

                    if ($oDataFim->getAno() >= 2020) {
                        if (temDataImplantacao($dtini)) {
                            $aSaldoArrecadadoEmenda = getValorReceitaEmendaInformado($dtini, buscarDataImplantacao(), $iInstituicoes, array(2));
                            $fCFRPB += $aSaldoArrecadadoEmenda[0]->arrecadado_emenda_parlamentar;
                            $aSaldoArrecadadoEmenda = getSaldoArrecadadoEmendaParlamentar(buscarDataImplantacao(), $dtfim, array(2));
                            $fCFRPB += $aSaldoArrecadadoEmenda[0]->arrecadado_emenda_parlamentar;
                        } else {
                            $aSaldoArrecadadoEmenda = getSaldoArrecadadoEmendaParlamentar($dtini, $dtfim, array(2));
                            $fCFRPB += $aSaldoArrecadadoEmenda[0]->arrecadado_emenda_parlamentar;
                        }
                    }

                    echo db_formatar($fCFRPB, "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Transfer�ncias da Uni�o relativas a remunera��o dos agentes comunit�rios de sa�de e de combate �s endemias (CF, art. 198, �11)</td>
                    <td class="s5">
                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Transfer�ncias da Uni�o relativas a remunera��o dos agentes comunit�rios de sa�de e de combate �s endemias (CF, art. 198, �11)</td>
                    <td class="s6">
                    <? } ?>
                    <?php
                    $fCFRPB += $fTransfUniACSCE;
                    echo db_formatar($fTransfUniACSCE, "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s3 bdleft" colspan="2">(-) Outras Dedu��es Constitucionais ou Legais.</td>
                    <td class="s5">
                    <? } else { ?>
                    <td class="s9 bdleft" colspan="13">(-) Outras Dedu��es Constitucionais ou Legais.</td>
                    <td class="s6">
                    <? } ?>
                    <?php
                    echo db_formatar(0, "f");
                    ?>
                    </td>
            </tr>

            <tr style='height:20px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s7 bdleft" colspan="2">RECEITA CORRENTE L�QUIDA AJUSTADA = BASE DE C�LCULO</td>
                <? } else { ?>
                    <td class="s9 bdleft" colspan="13"><b>RECEITA CORRENTE L�QUIDA AJUSTADA = BASE DE C�LCULO</b></td>
                <? } ?>

                <td class="s8">
                    <?php
                    $fRCLBase = $fRecCorrLiq - $fCFRP - $fCFRPB;
                    echo db_formatar($fRCLBase, "f");
                    ?>
                </td>
            </tr>

            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s1 bdleft" colspan="3">III) PERCENTUAIS MONET�RIOS DE APLICA��O</td>
                <? } else { ?>
                    <td class="s1 bdleft" colspan="14">III) PERCENTUAIS MONET�RIOS DE APLICA��O</td>
                <? } ?>

            </tr>


            <tr style='height:19px;'>
                <? if ($tipoEmissao == 1) { ?>
                    <td class="s1 bdleft">Aplica��o no Exerc�cio</td>
                    <td class="s10"><?php echo number_format((($fTotalDespesaPessoal / $fRCLBase) * 100), 2, ",", "."); ?>%</td>
                    <td class="s10"><?php echo db_formatar($fTotalDespesaPessoal, "f") ?></td>
                <? } else { ?>
                    <td class="s1 bdleft" colspan="12">APLICA��O NO EXERC�CIO</td>
                    <td class="s10"><?php echo number_format((($subtotalmesfinal / $fRCLBase) * 100), 2, ",", "."); ?>%</td>
                    <td class="s10"><?php echo db_formatar($subtotalmesfinal, "f") ?></td>
                <? } ?>
            </tr>
            <?
            if ($iVerifica == 1) :
            ?>
                <tr style='height:20px;'>
                    <? if ($tipoEmissao == 1) { ?>
                        <td class="s9 bdleft">Permitido pela Lei Complementar 101/00</td>
                    <? } else { ?>
                        <td class="s9 bdleft" colspan="12">Permitido pela Lei Complementar 101/00</td>
                    <? } ?>

                    <td class="s6">6%</td>
                    <td class="s6"><?php echo db_formatar($fRCLBase * 0.06, "f") ?></td>
                </tr>
            <?
            elseif ($iVerifica == 2) :
            ?>
                <tr style='height:20px;'>
                    <? if ($tipoEmissao == 1) { ?>
                        <td class="s9 bdleft">Permitido pela Lei Complementar 101/00</td>
                    <? } else { ?>
                        <td class="s9 bdleft" colspan="12">Permitido pela Lei Complementar 101/00</td>
                    <? } ?>
                    <td class="s6">54%</td>
                    <td class="s6"><?php echo db_formatar($fRCLBase * 0.54, "f") ?></td>
                </tr>
            <?
            else :
            ?>
                <tr style='height:20px;'>
                    <? if ($tipoEmissao == 1) { ?>
                        <td class="s9 bdleft">Permitido pela Lei Complementar 101/00</td>
                    <? } else { ?>
                        <td class="s9 bdleft" colspan=12>Permitido pela Lei Complementar 101/00</td>
                    <? } ?>
                    <td class="s6">60%</td>
                    <td class="s6"><?php echo db_formatar($fRCLBase * 0.6, "f") ?></td>
                </tr>
            <?
            endif;
            ?>
            </tbody>
        </table>
    </div>
    <td class="s1" colspan="1">
        <strong>
            <font size="2">
                Conforme ADCT, art. 38, � 2�, em 2024 a parcela dedut�vel referente ao piso salarial do Enfermeiro, T�cnico de Enfermagem, Auxiliar de Enfermagem e Parteira ser� de 90% do valor gasto.
            </font>
        </strong>
    </td>
</body>

</html>

<?php
$html = ob_get_contents();
ob_end_clean();
//echo $html;

$mPDF->WriteHTML(utf8_encode($html));
$mPDF->Output('Dem. Gastos Pessoal - ANEXO IV', 'I');


/* ---- */

db_query("drop table if exists work_dotacao");
db_query("drop table if exists work_receita");

db_fim_transacao();

/**
 * Busca os valores informados na consolida��o de cons�rcios (Contabilidade->Procedimentos->Consolida��o de Cons�rcios)
 * @param DBDate $oDataIni
 * @param DBDate $oDataFim
 * @return int
 * @throws ParameterException
 */
function getConsolidacaoConsorcios(DBDate $oDataIni, DBDate $oDataFim)
{
    $oConsexecucaoorc = new cl_consexecucaoorc();
    $aPeriodo = DBDate::getMesesNoIntervalo($oDataIni, $oDataFim);
    $fTotal = 0;
    foreach ($aPeriodo as $ano => $mes) {
        $sSql = $oConsexecucaoorc->sql_query_file(null, "sum(coalesce(c202_valorliquidado,0)) as c202_valorliquidado", null, "c202_anousu = " . $ano . " and c202_mescompetencia in (" . implode(',', array_keys($mes)) . ")");
        $fTotal += db_utils::fieldsMemory(db_query($sSql), 0)->c202_valorliquidado;
    }

    return $fTotal;
}

/**
 * Busca os valores informados manualmente na aba 'parametros' do relat�rio
 * @param $iCodRelatorio
 * @param $iLinha
 * @param $iInstit
 * @param $iCodPeriodo
 * @param $iAnousu
 * @return array
 */
function getValorManual($iCodRelatorio, $iLinha, $iInstit, $iCodPeriodo, $iAnousu)
{
    $oLinha = new linhaRelatorioContabil($iCodRelatorio, $iLinha, $iInstit);
    $oLinha->setPeriodo($iCodPeriodo);
    $oLinha->setEncode(true);
    $aValores = $oLinha->getValoresColunas(null, null, null, $iAnousu);
    return $aValores[0]->colunas[0]->o117_valor;
}

function getValorDespesaInformado($inicio, $fim, $elemento, $valorcalculo, $oInstit)
{
    $where = " AND ((c233_mes >= " . date('m', strtotime($inicio)) . " AND c233_ano = " . date('Y', strtotime($inicio)) . ") OR (c233_mes <= " . date('m', strtotime($fim)) . " AND c233_ano = " . date('Y', strtotime($fim)) . "))";
    $whereCompetencia = " AND ((c233_competencia IS NOT NULL AND c233_competencia < '{$inicio}') OR (c233_competencia IS NULL)) ";
    $sql = "SELECT o56_elemento as o58_elemento, o56_descr, c233_ano as o58_anousu, {$valorcalculo} FROM despesaexercicioanterior left join orcelemento on substring(c233_elemento::text, 1, 13) = o56_elemento and c233_ano = o56_anousu WHERE c233_elemento like '{$elemento}%' and c233_orgao = {$oInstit->getCodigo()} {$where} {$whereCompetencia} group by 1,2,3";
    return db_utils::getColectionByRecord(db_query($sql));
}

function getValorReceitaInformado($inicio, $fim, $oInstit)
{
    $where = " AND ((c234_mes >= " . date('m', strtotime($inicio)) . " AND c234_ano = " . date('Y', strtotime($inicio)) . ") OR (c234_mes <= " . date('m', strtotime($fim)) . " AND c234_ano = " . date('Y', strtotime($fim)) . "))";
    $sql = "SELECT c234_receita as o57_fonte, c234_valorarrecadado as saldo_arrecadado FROM receitaexercicioanterior WHERE c234_orgao in ({$oInstit})  {$where}";

    return db_utils::getColectionByRecord(db_query($sql));
}


function getValorReceitaEmendaInformado($inicio, $fim, $oInstit, $emenda)
{
    $where = " AND ((c234_mes >= " . date('m', strtotime($inicio)) . " AND c234_ano = " . date('Y', strtotime($inicio)) . ") OR (c234_mes <= " . date('m', strtotime($fim)) . " AND c234_ano = " . date('Y', strtotime($fim)) . "))";
    $where .= " AND c234_tipoemenda IN (" . implode(",", $emenda) . ") ";
    $sql = "SELECT c234_receita as o57_fonte, c234_valorarrecadado as arrecadado_emenda_parlamentar FROM receitaexercicioanterior WHERE c234_orgao in ({$oInstit})  {$where}";

    return db_utils::getColectionByRecord(db_query($sql));
}

// retorna data da implantacao
function buscarDataImplantacao()
{
    $sSQL = "SELECT c90_dataimplantacao FROM conparametro";
    $rsResult = db_query($sSQL);
    return db_utils::fieldsMemory($rsResult, 0)->c90_dataimplantacao;
}

function temDataImplantacao($dtini)
{
    if (buscarDataImplantacao() AND buscarDataImplantacao() > $dtini) {
        return true;
    }
    return false;
}

/**
 * @param $inicio
 * @param $fim
 * @param $instituicao
 * @return stdClass[]
 */
function getDespesaMensal($inicio, $fim, $instituicao)
{
    $ano = date("Y", strtotime($inicio));
    $instituicao = implode(",", $instituicao);

    $sql = "WITH sql_principal AS
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
                    AND w.o58_instit IN ({$instituicao})
                ORDER BY e.o56_elemento)

            SELECT p.o56_elemento AS o58_elemento,
                p.o56_descr AS o56_descr,
                substr(p.dotacaosaldo, 29, 12)::float8 - substr(p.dotacaosaldo, 42, 12)::float8 AS empenhado,
                substr(p.dotacaosaldo, 55, 12)::float8 AS liquidado,
                p.o15_codigo AS o58_codigo,
                p.o58_anousu
            FROM sql_principal p
            WHERE (substr(p.dotacaosaldo, 29, 12)::float8 - substr(p.dotacaosaldo, 42, 12)::float8) + substr(p.dotacaosaldo, 55, 12)::float8 <> 0
            ORDER BY 1";

    return db_utils::getColectionByRecord(db_query($sql));
}

function getDespesaMensalExclusaoSaldoIntaivosPensionistasProprio($inicio, $fim, $instituicao, $iRpps, $iCamara)
{
    $ano = date("Y", strtotime($inicio));
    $instituicao = implode(",", $instituicao);

    $sql = "SELECT * FROM (SELECT c60_estrut o58_elemento,
                                c60_descr o56_descr,
                                COALESCE(SUM(CASE
                                                WHEN c53_tipo = 20 THEN ROUND(c70_valor, 2)
                                                WHEN c53_tipo = 21 THEN ROUND(c70_valor * -(1 :: FLOAT8), 2)
                                                ELSE 0 :: FLOAT8
                                             END), 0 ) AS liquidado,
                                COALESCE(SUM(CASE
                                                WHEN c53_tipo = 10 THEN ROUND(c70_valor, 2)
                                                WHEN c53_tipo = 11 THEN ROUND(c70_valor * -(1 :: FLOAT8), 2)
                                                ELSE 0 :: FLOAT8
                                             END), 0 ) AS empenhado
                            FROM
                                    (SELECT
                                            DISTINCT ON (c70_codlan) c53_tipo,
                                            c60_estrut,
                                            c60_descr,
                                            c70_valor
                                        FROM
                                            conlancamele
                                            INNER JOIN conlancam ON c70_codlan = c67_codlan
                                            INNER JOIN conlancamdoc ON c70_codlan = c71_codlan
                                            INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                                            INNER JOIN conplanoorcamentoanalitica ON c61_codcon = c67_codele
                                            AND c61_anousu = c70_anousu
                                            INNER JOIN conplanoorcamento ON c61_codcon = c60_codcon
                                            AND c61_anousu = c60_anousu
                                            INNER JOIN conlancamemp ON c70_codlan = c75_codlan
                                            INNER JOIN empempenho ON e60_numemp = c75_numemp
                                            INNER JOIN orcdotacao ON e60_coddot = o58_coddot
                                            AND e60_anousu = o58_anousu
                                            INNER JOIN infocomplementaresinstit ON e60_instit = si09_instit
                                        WHERE
                                            c70_anousu = {$ano}
                                            AND e60_instit in ({$instituicao})
                                            AND c61_instit in ({$instituicao})
                                            AND c53_tipo IN (10, 11, 20, 21)
                                            AND c70_data BETWEEN '{$inicio}' AND '{$fim}'
                                            AND o58_codigo IN (103, 203, 18000000, 28000000)
                    ";
    if ($iRpps) {
        $sql .= getCondicaoTipoDespesa($iCamara);
        $sql .= " ) AND (
                        c60_estrut LIKE '331900101%'
                        OR c60_estrut LIKE '331900301%'
                    )) OR ( c60_estrut LIKE '331900501%' OR c60_estrut LIKE '331900502%') )) as w
                GROUP BY c60_estrut, c60_descr";
    } else {
        $sql .= "
                    AND ( c60_estrut LIKE '331900501%' OR c60_estrut LIKE '331900502%') ) as w
                    GROUP BY c60_estrut, c60_descr";
    }
    $sql .= " ) as x";

    return db_utils::getColectionByRecord(db_query($sql));
}

function getDespesaMensalExclusaoSaldoIntaivosPensionistasProprioDeduzir($inicio, $fim, $instituicao, $iCamara)
{


    $ano = date("Y", strtotime($inicio));
    $instituicao = implode(",", $instituicao);

    $sql = "WITH sql_principal AS
                (SELECT DISTINCT ON (c70_codlan) c53_tipo,
                                    c60_estrut,
                                    c60_descr,
                                    c70_valor
                 FROM conlancamele
                 INNER JOIN conlancam ON c70_codlan = c67_codlan
                 INNER JOIN conlancamdoc ON c70_codlan = c71_codlan
                 INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                 INNER JOIN conplanoorcamentoanalitica ON c61_codcon = c67_codele AND c61_anousu = c70_anousu
                 INNER JOIN conplanoorcamento ON c61_codcon = c60_codcon AND c61_anousu = c60_anousu
                 INNER JOIN conlancamemp ON c70_codlan = c75_codlan
                 INNER JOIN empempenho ON e60_numemp = c75_numemp
                 INNER JOIN orcdotacao ON e60_coddot = o58_coddot AND e60_anousu = o58_anousu
                 INNER JOIN infocomplementaresinstit ON e60_instit = si09_instit
                 WHERE c70_anousu = {$ano}
                   AND e60_instit in ({$instituicao})
                   AND c61_instit in ({$instituicao})
                   AND c53_tipo IN (10, 11, 20, 21)
                   AND c70_data BETWEEN '{$inicio}' AND '{$fim}'
                   AND o58_codigo IN (103, 203, 18000000, 28000000)";

    $sql .= getCondicaoTipoDespesaInvertido($iCamara) . " )  ";

    $sql .= " AND (
                c60_estrut LIKE '331900101%'
               OR c60_estrut LIKE '331900301%'
            ))))";
    $sql .= " SELECT c60_estrut AS o58_elemento,
                     c60_descr AS o56_descr,
                     COALESCE(SUM(CASE
                                      WHEN c53_tipo = 20 THEN ROUND(c70_valor, 2)
                                      WHEN c53_tipo = 21 THEN ROUND(c70_valor * -(1 :: FLOAT8), 2)
                                      ELSE 0 :: FLOAT8
                                  END), 0) AS liquidado,
                     COALESCE(SUM(CASE
                                      WHEN c53_tipo = 10 THEN ROUND(c70_valor, 2)
                                      WHEN c53_tipo = 11 THEN ROUND(c70_valor * -(1 :: FLOAT8), 2)
                                      ELSE 0 :: FLOAT8
                                  END), 0) AS empenhado
              FROM sql_principal
              GROUP BY 1, 2";

    return db_utils::getColectionByRecord(db_query($sql));

}


function getDespesaMensalSaldoIndenizacaoDemissaoServidores($inicio, $fim, $instituicao)
{
    $ano = date("Y", strtotime($inicio));
    $instituicao = implode(",", $instituicao);

    $sql = "WITH query_principal AS
                (SELECT DISTINCT ON (c70_codlan) c53_tipo,
                                    c60_estrut,
                                    c60_descr,
                                    c70_valor
                 FROM conlancamele
                 INNER JOIN conlancam ON c70_codlan = c67_codlan
                 INNER JOIN conlancamdoc ON c70_codlan = c71_codlan
                 INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                 INNER JOIN conplanoorcamentoanalitica ON c61_codcon = c67_codele AND c61_anousu = c70_anousu
                 INNER JOIN conplanoorcamento ON c61_codcon = c60_codcon AND c61_anousu = c60_anousu
                 INNER JOIN conlancamemp ON c70_codlan = c75_codlan
                 INNER JOIN empempenho ON e60_numemp = c75_numemp
                 INNER JOIN orcdotacao ON e60_coddot = o58_coddot AND e60_anousu = o58_anousu
                 WHERE c70_anousu = {$ano}
                   AND e60_instit IN ({$instituicao})
                   AND c61_instit IN ({$instituicao})
                   AND c53_tipo IN (10, 20, 21)
                   AND c70_data BETWEEN '{$inicio}' AND '{$fim}'
                   AND substr(c60_estrut, 1, 7) IN ('3319094', '3319194', '3319594', '3319694'))

            SELECT c60_estrut o58_elemento,
                   c60_descr o56_descr,
                   COALESCE(SUM(CASE
                                    WHEN c53_tipo = 20 THEN ROUND(c70_valor, 2)
                                    WHEN c53_tipo = 21 THEN ROUND(c70_valor * -(1 :: FLOAT8), 2)
                                    ELSE 0 :: FLOAT8
                                END), 0) AS liquidado,
                   COALESCE(SUM(CASE
                                    WHEN c53_tipo = 10 THEN ROUND(c70_valor, 2)
                                    WHEN c53_tipo = 11 THEN ROUND(c70_valor * -(1 :: FLOAT8), 2)
                                    ELSE 0 :: FLOAT8
                                END), 0) AS empenhado
            FROM query_principal
            GROUP BY c60_estrut, c60_descr";

    return db_utils::getColectionByRecord(db_query($sql));
}

function getDespesaMensalExclusaoSaldoSentencasJudAnt($inicial, $inicio, $fim, $instituicao)
{
    $ano = date("Y", strtotime($inicio));
    $instituicao = implode(",", $instituicao);

    $sql = "SELECT * FROM (";
    $sql .= "select
            e60_numemp,
            o58_elemento,
            o56_descr,
            COALESCE(
                SUM(
                    CASE
                        WHEN C53_TIPO = 20 THEN ROUND(C70_VALOR, 2) :: FLOAT8
                        WHEN C53_TIPO = 21 THEN ROUND(C70_VALOR * -1, 2) :: FLOAT8
                        ELSE 0 :: FLOAT8
                    END
                ),
                0
            ) AS liquidado,
            COALESCE(
                SUM(
                    CASE
                        WHEN c53_tipo = 10 THEN ROUND(c70_valor, 2)
                        WHEN c53_tipo = 11 THEN ROUND(c70_valor * -(1 :: FLOAT8), 2)
                        ELSE 0 :: FLOAT8
                    END
                ),
                0
            ) AS empenhado
        from
            work_dotacao
            inner join orcelemento on o58_codele = o56_codele
            and o58_anousu = o56_anousu
            inner join empempenho on o58_coddot = e60_coddot
            and o58_anousu = e60_anousu
            inner join conlancamemp on e60_numemp = c75_numemp
            inner join conlancam on c75_codlan = c70_codlan
            inner join conlancamdoc on c71_codlan = c70_codlan
            inner join conhistdoc on c53_coddoc = c71_coddoc
        where
            (o58_elemento like '3319091%' OR
            o58_elemento like '3319191%' OR
            o58_elemento like '3319691%')
            and o58_instit IN ({$instituicao})
            and e60_datasentenca < '{$inicial}'
            and e60_emiss BETWEEN '{$inicio}' AND '{$fim}'
        group by
            1,
            2,
            3";
    $sql .= " ) as x";

    return db_utils::getColectionByRecord(db_query($sql));
}

function getDespesaMensalSaldoDespesasAnteriores($inicio, $fim, $instituicao, $anterior, $elemento = NULL)
{
    if (is_array($instituicao))
        $instituicao = implode(",", $instituicao);

    $sql = "SELECT
                    o58_elemento,
                    o56_descr,
                    SUM (e60_vlrliq) AS liquidado,
                    SUM (e60_vlremp) - SUM(e60_vlranu) AS empenhado
                FROM (
                    SELECT * FROM work_dotacao
                    INNER JOIN orcelemento ON o58_codele = o56_codele AND o58_anousu = o56_anousu
                    INNER JOIN empempenho ON o58_coddot = e60_coddot AND o58_anousu = e60_anousu
                    INNER JOIN pagordem ON e50_numemp = e60_numemp ";

    /* Condi��o para n�o usar a fun��o da db_libcontabilidade, pois ocorre erro em alguns clientes */
    /* Essa fun��o � a base da getDespesaExercAnterior contida db_libcontabilidade */
    if ($elemento)
        $sql .= " WHERE o58_elemento LIKE '{$elemento}' ";
    else
        $sql .= " WHERE (o58_elemento LIKE '3319092%' OR o58_elemento LIKE '3319192%' OR o58_elemento LIKE '3319692%') AND e50_data BETWEEN '{$inicio}' AND '{$fim}' ";

        $sql .= " AND o58_instit IN ({$instituicao}) AND (e60_datasentenca < '{$anterior}' OR e50_compdesp < '{$anterior}')) AS x GROUP BY 1, 2";

    return db_utils::getColectionByRecord(db_query($sql));
}

function getDespesaMensalInformada($inicio, $fim, $instituicao, $competencia)
{
    $ano = date("Y", strtotime($inicio));
    $instituicao = implode(",", $instituicao);

    $where = " AND (c233_mes = " . date('m', strtotime($inicio)) . " AND c233_ano = {$ano}) ";
    $whereCompetencia = " AND ((c233_competencia IS NOT NULL AND c233_competencia < '{$competencia}') OR (c233_competencia IS NULL)) ";
    $sql = "SELECT o56_descr, c233_elemento as o58_elemento, c233_valorempenhado as empenhado, c233_valorliquidado as liquidado FROM despesaexercicioanterior left join orcelemento on substring(c233_elemento::text, 1, 13) = o56_elemento and c233_ano = o56_anousu WHERE c233_orgao IN ({$instituicao}) {$where} {$whereCompetencia}";

    return db_utils::getColectionByRecord(db_query($sql));
}

// REPLICAR NA PUBLICACAO OFICIAL
function getCondicaoTipoDespesa($iCamara)
{
    $sql = " AND ((( si09_tipoinstit = " . Instituicao::TIPO_INSTIT_RPPS . " ";

    $where = " AND (e60_tipodespesa = 1 ";
    if ($iCamara == 1)
        $where .= " AND e60_tipodespesa = 2 ";

    return $sql . $where . " ) ";
}

function getCondicaoTipoDespesaInvertido($iCamara)
{
    $sql = " AND ((( si09_tipoinstit = " . Instituicao::TIPO_INSTIT_RPPS . " ";

    $where = " AND (e60_tipodespesa = 2 ";
    if ($iCamara == 1)
        $where .= " AND e60_tipodespesa = 1 ";

    return $sql . $where . " ) ";
}
?>
