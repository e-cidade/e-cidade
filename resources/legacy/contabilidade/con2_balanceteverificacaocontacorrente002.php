<?php

include("libs/db_utils.php");
include("std/DBDate.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("fpdf151/pdf.php");
include("libs/db_libcontabilidade.php");
include("classes/db_contacorrentedetalhe_classe.php");

//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $aFiltros);

$aMeses = array(
    "JANEIRO" => "1", "FEVEREIRO" => "2", "MAR�O" => "3", "ABRIL" => "4", "MAIO" => "5", "JUNHO" => "6",
    "JULHO" => "7", "AGOSTO" => "8", "SETEMBRO" => "9", "OUTUBRO" => "10", "NOVEMBRO" => "11", "DEZEMBRO" => "12"
);

$aContasDesc = array(3 => "CREDOR", 100 => "RECEITA", 101 => "DOTA��O", 102 => "DESPESA", 103 => "FONTE", 106 => "RP");

$aTipoValor = array('beginning_balance', 'period_change_deb', 'period_change_cred', 'ending_balance');

$aIndicadores = array(''  => 'Todos', 'N' => 'N - N�o se aplica', 'F' => 'F - Financeiro', 'P' => 'P - Patrimonial',);

$aInstit = str_replace("-",",",$aFiltros['selinstit']);
$iAnoUsu      = date("Y", db_getsession("DB_datausu"));
$iMes         = (!empty($aFiltros['mes']))     ? $aFiltros['mes'] : '';

$sEstrut_inicial = $aFiltros['estrut_inicial'];

$iUltimoDiaMes = date("d", mktime(0,0,0,$iMes+1,0,db_getsession("DB_anousu")));
$sDataInicial = App\Support\String\DateFormatter::convertDateFormatBRToISO(trim($dataInicial));
$sDataFinal   = App\Support\String\DateFormatter::convertDateFormatBRToISO(trim($dataFinal));

$sInstituicao = ($aFiltros['matriz'] == 'd')   ? " r.c61_instit = $iInstit and " : '';
$sTipoInstit  = !empty($sInstituicao)          ? " limit 1 " : '';
$aRegistros   = array();
$iConta       = "";
$tipoRel      = isset($aFiltros['tipoRel']) ? $aFiltros['tipoRel'] : 1;

try {
    if($sEstrut_inicial != '')
        $where = " c61_instit in ({$aInstit}) and c60_estrut like '$sEstrut_inicial%' " ;
    else
        $where = " c61_instit in ({$aInstit}) " ;

    if ($indicSuperavit !== "") {
        $where .= " and c60_identificadorfinanceiro = '$indicSuperavit'";
    }

    $iAnoUsu = db_getsession("DB_anousu");
    $rsBalancete = db_planocontassaldo_matriz($iAnoUsu,$sDataInicial,$sDataFinal,false,$where);
    $nCC = 0;
    for ($iCont = 0; $iCont < pg_num_rows($rsBalancete); $iCont++) {
        $oBalancete = db_utils::fieldsMemory($rsBalancete, $iCont);

        if ($oBalancete->c61_reduz == 0 && $recurso != 0) {
            continue;
        }

        if ($naturezaSaldo == 1) {
            $aNaturezaSaldo = array(1 => 'D', 2 => 'C');
            $sSql = "SELECT c60_naturezasaldo from conplano where c60_estrut = '{$oBalancete->estrutural}' and c60_anousu = {$iAnoUsu}";
            $iNaturezaSaldo = db_utils::fieldsMemory(db_query($sSql), 0)->c60_naturezasaldo;
        }

        $oNovoResgistro                         = new stdClass;
        $oNovoResgistro->estrutural             = $oBalancete->estrutural;
        $oNovoResgistro->c61_reduz              = $oBalancete->c61_reduz != '0' ?  $oBalancete->c61_reduz : '';
        $oNovoResgistro->c60_descr              = substr($oBalancete->c60_descr,0,70);
        $oNovoResgistro->saldo_anterior         = db_formatar($oBalancete->saldo_anterior,'f');
        $oNovoResgistro->saldo_anterior_debito  = db_formatar($oBalancete->saldo_anterior_debito,'f');
        $oNovoResgistro->saldo_anterior_credito = db_formatar($oBalancete->saldo_anterior_credito,'f');
        $oNovoResgistro->saldo_final            = db_formatar($oBalancete->saldo_final,'f');
        $oNovoResgistro->sinal_anterior         = $oBalancete->sinal_anterior;
        $oNovoResgistro->sinal_final            = $oBalancete->sinal_final;
        $oNovoResgistro->contacorrente          = new stdClass;;

        if($oBalancete->c61_reduz > 0) {
            $sSqlCC = "select c18_contacorrente from conplanocontacorrente where c18_anousu={$iAnoUsu} and c18_codcon = {$oBalancete->c61_codcon}";
            $rsCC = db_query($sSqlCC);
            $nCC = db_utils::fieldsMemory($rsCC, 0)->c18_contacorrente;
            $oNovoResgistro->contacorrente         = getSaldoTotalContaCorrente($iAnoUsu,$oBalancete->c61_reduz,$nCC > 0 ? $nCC : null, null, $oBalancete->c61_instit, $sDataInicial, $sDataFinal);
            $oNovoResgistro->cc                    = $nCC;
            $oNovoResgistro->ccdescricao           = isset($aContasDesc[$nCC]) ? $aContasDesc[$nCC] : $nCC;

            if ($nCC != 103 && $recurso != 0) {
                continue;
            }
            
            if ($nCC == 103){
                $clContaCorrente = new cl_contacorrentedetalhe;
                $aContacorrenteDetalhe = $clContaCorrente->detalhamentoPorFonte($iAnoUsu,$oBalancete->c61_reduz, $aInstit, null, $sDataInicial, $sDataFinal);                                
                
                if ($tipoRel == 2) {
                    $oNovoResgistro->contacorrentedetalhe = $aContacorrenteDetalhe;
                }

                $incluir = true;
                if ($recurso != 0) {
                    $incluir = false;
                    foreach ($aContacorrenteDetalhe as $oCCdetalhe) {                        
                        if ($oCCdetalhe['codtri'] == $recurso) {
                            $incluir = true;
                            break;
                        }
                    }
                }

                if ($incluir && $naturezaSaldo == 1) {                    
                    $incluir = false;
                    foreach ($aContacorrenteDetalhe as $oCCdetalhe) {
                        if ($aNaturezaSaldo[$iNaturezaSaldo] != $oCCdetalhe['sinal_final']) {
                            $incluir = true;
                            break;
                        }
                    }
                }

                if (!$incluir || ($naturezaSaldo == 1 && $oBalancete->saldo_final == 0)){
                    continue;
                }      
            }
        }

        if ($naturezaSaldo == 1) {
            if (($nCC != 103) && ($oBalancete->sinal_final == $aNaturezaSaldo[$iNaturezaSaldo] || $oBalancete->saldo_final == 0)) {
                continue;
            }
        }

        if($oBalancete->saldo_anterior == 0
            && $oBalancete->saldo_anterior_debito == 0
            && $oBalancete->saldo_anterior_credito == 0
            && $oBalancete->saldo_final == 0
            && $oNovoResgistro->contacorrente->nSaldoInicialMes == 0
            && $oNovoResgistro->contacorrente->debito == 0
            && $oNovoResgistro->contacorrente->credito == 0
            && $oNovoResgistro->contacorrente->saldo_final == 0
            && count($oNovoResgistro->contacorrentedetalhe) == 0) {
            continue;
        }
        
        $aRegistros[] = $oNovoResgistro;
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "BALANCETE C/C";
$head3 = "EXERC�CIO: {$iAnoUsu}";
$head4 = "PER�ODO: ".$dataInicial." - ".$dataFinal;
$head5 = "INSTIUI��ES: {$aInstit}";
$head6 = "SUPER�VIT: {$aIndicadores[$indicSuperavit]}";
$head7 = "NATUREZA DE SALDO: ".($naturezaSaldo == 0 ? 'Todos' : 'Invertidas');
$head8 = "RECURSO: ".($recurso == 0 ? 'Todos' : $recurso);
$alt   = 4;
$pdf->SetAutoPageBreak('on',0);
$pdf->line(2,148.5,208,148.5);
$pdf->setfillcolor(235);
$pdf->addpage();

$pdf->setfont("arial", "B", 6);
$pdf->cell(19,$alt,"ESTRUTURAL","B",0,"C",0);
$pdf->cell(70,$alt,"DESCRI��O","B",0,"L",0);
$pdf->cell(10,$alt,"REDUZ","B",0,"C",0);
$pdf->cell(24,$alt,"SALDO ANTERIOR","B",0,"R",0);
$pdf->cell(22,$alt,"D�BITOS","B",0,"R",0);
$pdf->cell(22,$alt,"CR�DITOS","B",0,"R",0);
$pdf->cell(24,$alt,"SALDO FINAL","B",0,"R",0);

$pdf->ln();
$pdf->setfont("arial", "", 6);
//echo "<pre>"; print_r($aRegistros);die();

foreach ($aRegistros as $aRegistro) {

    $pdf->cell(19,$alt,$aRegistro->estrutural,"0",0,"C",0);
    $pdf->cell(70,$alt,$aRegistro->c60_descr,"0",0,"L",0);
    $pdf->cell(10,$alt,$aRegistro->c61_reduz,"0",0,"C",0);
    $pdf->cell(24,$alt,$aRegistro->saldo_anterior."$aRegistro->sinal_anterior","0",0,"R",0);
    $pdf->cell(22,$alt,$aRegistro->saldo_anterior_debito,"0",0,"R",0);
    $pdf->cell(22,$alt,$aRegistro->saldo_anterior_credito,"0",0,"R",0);
    $pdf->cell(24,$alt,$aRegistro->saldo_final."$aRegistro->sinal_final","0",0,"R",0);

    $pdf->ln();
    $diferente = false;
    if($aRegistro->cc > 0
        && (trim($aRegistro->saldo_anterior) != trim($aRegistro->contacorrente->nSaldoInicialMes)
        || trim($aRegistro->saldo_anterior_debito) != trim($aRegistro->contacorrente->debito)
        || trim($aRegistro->saldo_anterior_credito) != trim($aRegistro->contacorrente->credito)
        || trim($aRegistro->saldo_final) != trim($aRegistro->contacorrente->saldo_final))) {
        $diferente = true;
        //echo "<pre>"; print_r($aRegistro);die();
    }
    if($aRegistro->contacorrente->cc > 0) {
        $pdf->cell(19,$alt,"","0",0,"C",1);
        $pdf->cell(70,$alt,($diferente==true)?"S A L D O   CC ":"SALDO CC ",($diferente==true)?"B":"0",0,"R",1);
        $pdf->cell(10, $alt, $aRegistro->ccdescricao, "0", 0, "C", 1);
        $pdf->cell(24, $alt, $aRegistro->contacorrente->nSaldoInicialMes.
        ($aRegistro->contacorrente->nSaldoInicialMes != 0 ? $aRegistro->contacorrente->sinal_ant : ''), "0", 0, "R", 1);
        $pdf->cell(22, $alt, $aRegistro->contacorrente->debito, "0", 0, "R", 1);
        $pdf->cell(22, $alt, $aRegistro->contacorrente->credito, "0", 0, "R", 1);
        $pdf->cell(24, $alt, $aRegistro->contacorrente->saldo_final.
        ($aRegistro->contacorrente->saldo_final != 0 ? $aRegistro->contacorrente->sinal_final : ''), "0", 0, "R", 1);
        $pdf->ln();
        if ($aRegistro->cc == 103 && isset($aRegistro->contacorrentedetalhe) && $tipoRel == 2){            
                foreach($aRegistro->contacorrentedetalhe as $ccDetalhe){
                    $pdf->cell(89,$alt,"","0",0,"R",1);
                    $pdf->cell(10, $alt, $ccDetalhe["codtri"], "0", 0, "C", 1);
                    $pdf->cell(24, $alt, db_formatar($ccDetalhe["saldo_anterior"], 'f').
                    ($ccDetalhe["saldo_anterior"] > 0.001 ? $ccDetalhe["sinal_anterior"] : ''), "0", 0, "R", 1);
                    $pdf->cell(22, $alt, db_formatar($ccDetalhe["valor_debito"], 'f'), "0", 0, "R", 1);
                    $pdf->cell(22, $alt, db_formatar($ccDetalhe["valor_credito"], 'f'), "0", 0, "R", 1);
                    $pdf->cell(24, $alt, db_formatar($ccDetalhe["saldo_final"], 'f').
                    ($ccDetalhe["saldo_final"] > 0.001 ? $ccDetalhe["sinal_final"] : ''), "0", 0, "R", 1);
                    
                    if ($pdf->gety() > ($pdf->h - 20)) {
                        $pdf->addpage();
                    }
                    $pdf->ln();
                }
        }
    }

    if ($pdf->gety() > ($pdf->h - 20)) {
        $pdf->addpage();
    }
}

if ($pdf->gety() > ($pdf->h - 20)) {
    $pdf->addpage();
}

$pdf->Output();

?>
