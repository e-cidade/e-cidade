<?php 
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
require_once("libs/db_utils.php");
include("libs/db_liborcamento.php");

$clselorcdotacao = new cl_selorcdotacao();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$iUltimoDiaMes = date("d", mktime(0, 0, 0, $MesReferencia + 1, 0, db_getsession("DB_anousu")));
$sDataInicial = db_getsession("DB_anousu") . "-{$MesReferencia}-01";
$sDataFinal = db_getsession("DB_anousu") . "-{$MesReferencia}-{$iUltimoDiaMes}";

$sWhere = "";

if (isset($filtros) && $filtros != '') {
    
    $clselorcdotacao->setDados($filtros);
    $sql_filtro = $clselorcdotacao->getDados(false);
    $sWhere .= $sql_filtro;
}

switch ($MesReferencia) {
    case "01":
        $sMes = "Janeiro";
        break;
    case "02":
        $sMes = "Fevereiro";
        break;
    case "03":
        $sMes = "Março";
        break;
    case "04":
        $sMes = "Abril";
        break;
    case "05":
        $sMes = "Maio";
        break;
    case "06":
        $sMes = "Junho";
        break;
    case "07":
        $sMes = "Julho";
        break;
    case "08":
        $sMes = "Agosto";
        break;
    case "09":
        $sMes = "Setembro";
        break;
    case "10":
        $sMes = "Outubro";
        break;
    case "11":
        $sMes = "Novembro";
        break;
    case "12":
        $sMes = "Dezembro";
        break;
}

if ($recursos != '') {
    $sWhere .= "and o15_codigo in ({$recursos})";

    $sTipoPasta = $recursos;
} else {
    $sWhere .= '';
    $sTipoPasta = "Todos";
}

if (isset($iTipo) && $iTipo != '') {
    
    if ($iTipo == 1) {
        
        $sWhere .= " and o15_codigo in (102,202,15000002,25000002,148,149,150,151,152) ";
        $sTipoPasta = "Saúde 15% ";

    } elseif ($iTipo == 2) {

        $sWhere .= " and o15_codigo in (101, 201, 15000001,25000001) ";
        $sTipoPasta = "Educação 25% ";

    } elseif ($iTipo == 3) {
        
        $sWhere .= " and o15_codigo in (118,119, 218, 219,166,167,266,267,15400007,25400007,15400000,25400000,15420007,25420007,15420000,25420000) ";
        $sTipoPasta = "Fundeb (70% e 30%) ";

    } elseif ($iTipo == 4) {
        $sWhere .= " and ( o15_codigo not in (101, 102, 118, 119, 202, 201, 218, 219,166,167,266,267,157, 17520000,257, 27520000,116, 17500000,216,27500000,15000002,25000002,15000001,25000001,15400007,25400007,15400000,25400000,15420007,25420007,15420000,25420000, 159, 153, 132, 155, 121, 123, 176, 177, 179, 180, 178, 112, 259, 253, 232, 255, 221, 223, 276, 277, 279, 280, 278, 212,148,149,150,151,152,129, 156, 142, 229, 256, 242,107, 147, 143, 144, 145, 146, 122, 171, 172, 175, 174, 173, 120, 106, 113, 207, 247, 243, 244, 245, 246, 222, 271, 272 ,275 ,274 ,273 ,220 ,206 ,213)) ";
        $sWhere .= " and ( SUBSTRING(o15_codtri,1, 4) not in ('1600', '1601', '1602', '1603', '1604' ,'1605', '1621', '1622', '1631', '1632', '1633', '1634', '1635', '1636', '1659', '2600', '2601', '2602', '2603', '2604', '2605', '2621', '2622', '2631', '2632', '2633', '2634', '2635', '2636', '2659','1660', '1661', '1662', '1665', '1669', '2660', '2661', '2662', '2665', '2669','1541', '1543', '1544', '1550', '1551', '1552', '1553', '1569', '1570', '1571', '1572', '1573', '1574', '1575', '1576', '1599', '2541', '2543', '2544', '2550', '2551', '2552', '2553', '2569', '2570', '2571', '2572', '2573', '2574', '2575', '2576', '2599')) ";
        $sTipoPasta = "Geral ";
    } elseif ($iTipo == 5) {
        
        $sWhere .= " and o15_codigo in (118, 218,166,266,15400007,25400007,15420007,25420007) ";
        $sTipoPasta = "Fundeb 70% ";

    } elseif ($iTipo == 6) {
        
        $sWhere .= " and o15_codigo in (119, 219,167,267, 15400000,25400000,15420000,25420000) ";
        $sTipoPasta = "Fundeb 30% ";

    } elseif ($iTipo == 7) {
        
        $sWhere .= " and o15_codigo in (157, 17520000,257, 27520000) ";
        $sTipoPasta = "Multas de Transito ";

    } elseif ($iTipo == 8) {
        
        $sWhere .= " and o15_codigo in (116, 17500000,216,27500000) ";
        $sTipoPasta = "CIDE ";

    } elseif ($iTipo == 9) {
        
        $sWhere .= " and (( SUBSTRING(o15_codtri,1, 4) in ('1600', '1601', '1602', '1603', '1604', '1605', '1621', '1622', '1631', '1632', '1633', '1634', '1635', '1636', '1659', '2600', '2601', '2602', '2603', '2604', '2605', '2621', '2622', '2631', '2632', '2633', '2634', '2635', '2636', '2659'))  or o15_codigo in ('159', '153', '132', '155', '121', '123', '176', '177', '179', '180', '178', '112', '259', '253', '232', '255', '221', '223', '276', '277', '279', '280', '278', '212 ')) ";
        $sTipoPasta = "Vinculados à Saúde ";

    }elseif ($iTipo == 10) {
        
        $sWhere .= " and (( SUBSTRING(o15_codtri,1, 4) in ('1660', '1661', '1662', '1665', '1669', '2660', '2661', '2662', '2665', '2669'))  or o15_codigo in ('129', '156', '142', '229', '256', '242')) ";
        $sTipoPasta = "Vinculados à Assistencia ";

    }elseif ($iTipo == 11) {
        
        $sWhere .= " and (( SUBSTRING(o15_codtri,1, 4) in ('1541', '1543', '1544', '1550', '1551', '1552', '1553', '1569', '1570', '1571', '1572', '1573', '1574', '1575', '1576', '1599', '2541', '2543', '2544', '2550', '2551', '2552', '2553', '2569', '2570', '2571', '2572', '2573', '2574', '2575', '2576', '2599'))  or o15_codigo in ('107', '147', '143', '144', '145', '146', '122', '171', '172', '175', '174', '173', '120', '106', '113', '207', '247', '243', '244', '245', '246', '222', '271', '272 ','275 ','274 ','273 ','220 ','206 ','213 ')) ";
        $sTipoPasta = "Vinculados à Educação ";

    }
}

$aLinhas = array();

$head3 = "Exame Aritmético";
$head5 = "Mês de Referência: $sMes";
$head7 = "Tipo Recursos: $sTipoPasta";

$aLinhaTitle = array();
array_push(
    $aLinhaTitle,
    'Exame Aritmético',
    'Mês de Referência: '.$sMes,
    'Tipo Recursos: '.$sTipoPasta
);

$aLinhas[] = $aLinhaTitle;

if ($iRestosPagar != 2) {

    if($ordenar == 01) {
        $sSql = "select * from
        ( select coremp.k12_empen, e60_numemp, e60_codemp, e60_emiss, e60_numerol,e60_tipol,
            case when e49_numcgm is null then e60_numcgm
                else e49_numcgm end as
            e60_numcgm,
            k12_codord as e50_codord,
            case when e49_numcgm is null then cgm.z01_nome
                else cgmordem.z01_nome end as z01_nome,
            k12_valor,
            k12_cheque,
            e60_anousu,
            coremp.k12_autent,
            coremp.k12_data,
            k13_conta,
            k13_descr,
            o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_projativ,
            o58_funcao,o58_programa,
            o55_descr,
            o15_codtri,o15_codigo,o15_descr,o15_tipo,
            o56_elemento,
                            o56_descr,
                            e50_data,
            case when e60_anousu < " . db_getsession("DB_anousu") . " then 'RP'
                else 'Emp' end as tipo,
                e50_obs
        from coremp
            inner join empempenho on e60_numemp = k12_empen
                        and e60_instit = " . db_getsession("DB_instit") . "
            inner join orcdotacao on e60_anousu = o58_anousu
                        and e60_coddot = o58_coddot
            inner join orcprojativ on o58_anousu = o55_anousu
                        and o58_projativ = o55_projativ
            inner join orctiporec on o58_codigo = o15_codigo
            inner join orcelemento on o58_codele = o56_codele
            and o58_anousu = o56_anousu
            inner join pagordem on e50_codord = k12_codord
            left join pagordemconta on e50_codord = e49_codord
            inner join corrente on corrente.k12_id = coremp.k12_id
                        and corrente.k12_data=coremp.k12_data
                        and corrente.k12_autent= coremp.k12_autent
            inner join cgm on cgm.z01_numcgm = e60_numcgm
            left join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm
            inner join saltes on saltes.k13_conta = corrente.k12_conta
        where " . $sWhere . " and coremp.k12_data between '" . $sDataInicial . "' and '" . $sDataFinal . "' 
        order by o58_orgao, o58_unidade, o58_subfuncao, o58_funcao, o58_programa, o58_projativ, o56_elemento,e60_codemp) as xxxxx where 1 = 1";
    }else if($ordenar == 02) {
        $sSql = "SELECT *
                FROM
                    (SELECT coremp.k12_empen,e60_numemp,e60_codemp,e60_emiss,e60_numerol,e60_tipol,
                            CASE
                                WHEN e49_numcgm IS NULL THEN e60_numcgm
                                ELSE e49_numcgm
                            END AS e60_numcgm,
                            k12_codord AS e50_codord,
                            CASE
                                WHEN e49_numcgm IS NULL THEN cgm.z01_nome
                                ELSE cgmordem.z01_nome
                            END AS z01_nome,
                            k12_valor,k12_cheque,e60_anousu,coremp.k12_autent,coremp.k12_data,k13_conta,
                            k13_descr,o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_projativ,o58_funcao,
                            o58_programa,o55_descr,o15_codtri,o15_codigo,o15_descr,o15_tipo,o56_elemento,o56_descr,e50_data,
                            CASE
                                WHEN e60_anousu < " . db_getsession("DB_anousu") . " THEN 'RP'
                                ELSE 'Emp'
                            END AS tipo,
                            e50_obs
                    FROM coremp
                    INNER JOIN empempenho ON e60_numemp = k12_empen AND e60_instit = " . db_getsession("DB_instit") . "
                    INNER JOIN orcdotacao ON e60_anousu = o58_anousu AND e60_coddot = o58_coddot
                    INNER JOIN orcprojativ ON o58_anousu = o55_anousu AND o58_projativ = o55_projativ
                    INNER JOIN orctiporec ON o58_codigo = o15_codigo
                    INNER JOIN orcelemento ON o58_codele = o56_codele AND o58_anousu = o56_anousu
                    INNER JOIN pagordem ON e50_codord = k12_codord
                    LEFT JOIN pagordemconta ON e50_codord = e49_codord
                    INNER JOIN corrente ON corrente.k12_id = coremp.k12_id AND corrente.k12_data=coremp.k12_data
                    AND corrente.k12_autent = coremp.k12_autent
                    INNER JOIN cgm ON cgm.z01_numcgm = e60_numcgm
                    LEFT JOIN cgm cgmordem ON cgmordem.z01_numcgm = e49_numcgm
                    INNER JOIN saltes ON saltes.k13_conta = corrente.k12_conta
                    WHERE " . $sWhere . " and coremp.k12_data BETWEEN '" . $sDataInicial . "' and '" . $sDataFinal . "'
                    ORDER BY o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_funcao,o58_programa,o58_projativ,o56_elemento,e60_codemp) AS xxxxx
                WHERE 1 = 1";

    } else if ($ordenar == 03) {

        $sSql = "select * from
        ( select coremp.k12_empen, e60_numemp, e60_codemp, e60_emiss, e60_numerol,e60_tipol,
            case when e49_numcgm is null then e60_numcgm
                else e49_numcgm end as
            e60_numcgm,
            k12_codord as e50_codord,
            case when e49_numcgm is null then cgm.z01_nome
                else cgmordem.z01_nome end as z01_nome,
            k12_valor,
            k12_cheque,
            e60_anousu,
            coremp.k12_autent,
            coremp.k12_data,
            k13_conta,
            k13_descr,
            o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_projativ,
            o58_funcao,o58_programa,
            o55_descr,
            o15_codtri,o15_codigo,o15_descr,o15_tipo,
            o56_elemento,
                            o56_descr,
                            e50_data,
            case when e60_anousu < " . db_getsession("DB_anousu") . " then 'RP'
                else 'Emp' end as tipo,
                e50_obs
        from coremp
            inner join empempenho on e60_numemp = k12_empen
                        and e60_instit = " . db_getsession("DB_instit") . "
            inner join orcdotacao on e60_anousu = o58_anousu
                        and e60_coddot = o58_coddot
            inner join orcprojativ on o58_anousu = o55_anousu
                        and o58_projativ = o55_projativ
            inner join orctiporec on o58_codigo = o15_codigo
            inner join orcelemento on o58_codele = o56_codele
            and o58_anousu = o56_anousu
            inner join pagordem on e50_codord = k12_codord
            left join pagordemconta on e50_codord = e49_codord
            inner join corrente on corrente.k12_id = coremp.k12_id
                        and corrente.k12_data=coremp.k12_data
                        and corrente.k12_autent= coremp.k12_autent
            inner join cgm on cgm.z01_numcgm = e60_numcgm
            left join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm
            inner join saltes on saltes.k13_conta = corrente.k12_conta
        where " . $sWhere . " and coremp.k12_data between '" . $sDataInicial . "' and '" . $sDataFinal . "' 
        order by o58_coddot,o58_orgao, o58_unidade, o58_subfuncao, o58_funcao, o58_programa, o58_projativ, o56_elemento,e60_codemp) as xxxxx where 1 = 1";
        
    }

    $rsResult = db_query($sSql);
    $aDadosAgrupados = array();
    
    for ($iCont = 0; $iCont < pg_num_rows($rsResult); $iCont++) {
    
        $oResult = db_utils::fieldsMemory($rsResult, $iCont);
    
        $sHash = $oResult->e50_codord;
        if (!isset($aDadosAgrupados[$sHash])) {
    
            if ($oResult->tipo == 'Emp') {
                $aDadosAgrupados[$sHash] = $oResult;
            }
    
        } else {
            $aDadosAgrupados[$sHash]->k12_valor += $oResult->k12_valor;
        }
    }
    
    $aLinhas[] = "EMPENHOS DO MÊS";

    $nTotalizador = 0;
    
    foreach ($aDadosAgrupados as $oResult) {
    
        if ($oResult->k12_valor > 0) {
    
            if ($oResult->o58_coddot != $sReduzido || $iCont == 0) {
    
                $aLinhaTitle2 = array();
                array_push(
                    $aLinhaTitle2,
                    'DOTAÇÃO:',
                    'REDUZIDO: '.$oResult->o58_coddot,
                    str_pad($oResult->o58_orgao, 2, "0", STR_PAD_LEFT) . str_pad($oResult->o58_unidade, 3, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o58_funcao, 2, "0", STR_PAD_LEFT) . str_pad($oResult->o58_subfuncao, 3, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o58_programa, 4, "0", STR_PAD_LEFT) . str_pad($oResult->o58_projativ, 4, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o56_elemento, 6, "0", STR_PAD_LEFT) . " - " . $oResult->o56_descr,
                    substr($oResult->o15_codigo.' - '.$oResult->o15_descr,0,55)
                );
    
                $aLinhas[] = $aLinhaTitle2;
    
                $sReduzido = $oResult->o58_coddot;

                $aLinhaTitle3 = array();
                array_push(
                    $aLinhaTitle3,
                    'Empenho', 
                    'Data Emp', 
                    'OP', 
                    'Data Ordem', 
                    'Fornecedor', 
                    'Data Pag',
                    'Valor', 
                    'Num Lic', 
                    'Mod Lic', 
                    'Nº DOC',
                );

                $aLinhas[] = $aLinhaTitle3;
            }
    
            $aLinhaRegister = array();
            array_push(
                $aLinhaRegister,
                $oResult->e60_codemp, 
                implode("/", array_reverse(explode("-", $oResult->e60_emiss))), 
                $oResult->e50_codord, 
                implode("/", array_reverse(explode("-", $oResult->e50_data))), 
                substr($oResult->z01_nome, 0, 50), 
                implode("/", array_reverse(explode("-", $oResult->k12_data))), 
                number_format($oResult->k12_valor, "2", ",", "."), 
                $oResult->e60_numerol, 
                $oResult->e60_tipol
            );
    
            $aLinhas[] = $aLinhaRegister;
    
            if($ExibirHistoricoDoEmpenho == 01){
                $aLinhas[] = 'Histórico: ' . $oResult->e50_obs;
            }
    
            $nTotalizador += $oResult->k12_valor;
        }
    }
    
    $aLinhaTotal = array();
    array_push(
        $aLinhaTotal,
        'Valor Total:', 
        number_format($nTotalizador, "2", ",", ".")
    );

    $aLinhas[] = $aLinhaTotal;
}

if ($iRestosPagar != 1) {
    if($ordenar == '3') {
        $ordenarcampos = " o58_coddot ";
    } else {
        $ordenarcampos = " z01_nome ";    
        $sSql = "select * from
            ( select coremp.k12_empen, e60_numemp, e60_codemp, e60_emiss, e60_numerol,e60_tipol,
                case when e49_numcgm is null then e60_numcgm
                    else e49_numcgm end as
                e60_numcgm,
                k12_codord as e50_codord,
                case when e49_numcgm is null then cgm.z01_nome
                    else cgmordem.z01_nome end as z01_nome,
                k12_valor,
                k12_cheque,
                e60_anousu,
                coremp.k12_autent,
                coremp.k12_data,
                k13_conta,
                k13_descr,
                o58_coddot,o58_orgao,o58_unidade,o58_subfuncao,o58_projativ,
                o58_funcao,o58_programa,
                o55_descr,
                o15_codtri,o15_descr,o15_tipo,
                o56_elemento,
                                o56_descr,
                                e50_data,
                case when e60_anousu < " . db_getsession("DB_anousu") . " then 'RP'
                    else 'Emp' end as tipo,
                    e50_obs
            from coremp
                inner join empempenho on e60_numemp = k12_empen
                            and e60_instit = " . db_getsession("DB_instit") . "
                inner join orcdotacao on e60_anousu = o58_anousu
                            and e60_coddot = o58_coddot
                inner join orcprojativ on o58_anousu = o55_anousu
                            and o58_projativ = o55_projativ
                inner join orctiporec on o58_codigo = o15_codigo
                inner join orcelemento on o58_codele = o56_codele
                and o58_anousu = o56_anousu
                inner join pagordem on e50_codord = k12_codord
                left join pagordemconta on e50_codord = e49_codord
                inner join corrente on corrente.k12_id = coremp.k12_id
                            and corrente.k12_data=coremp.k12_data
                            and corrente.k12_autent= coremp.k12_autent
                inner join cgm on cgm.z01_numcgm = e60_numcgm
                left join cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm
                inner join saltes on saltes.k13_conta = corrente.k12_conta
            where " . $sWhere . " and coremp.k12_data between '" . $sDataInicial . "' and '" . $sDataFinal . "' 
            order by o58_orgao, o58_unidade, o58_subfuncao, o58_funcao, o58_programa, o58_projativ, o56_elemento) as xxxxx
            where 1 = 1 and tipo = 'RP' order by ". $ordenarcampos ."";
    }
   
    $rsResult = db_query($sSql);

    $aDadosAgrupados = array();

    for ($iCont = 0; $iCont < pg_num_rows($rsResult); $iCont++) {

        $oResult = db_utils::fieldsMemory($rsResult, $iCont);
        $sHash = $oResult->e50_codord;
        if (!isset($aDadosAgrupados[$sHash])) {

            if ($oResult->tipo == 'RP') {
                $aDadosAgrupados[$sHash] = $oResult;
            }

        } else {
            $aDadosAgrupados[$sHash]->k12_valor += $oResult->k12_valor;
        }
    }

    $nTotalizador = 0;

    $aLinhas[] = "RESTOS A PAGAR";

    foreach ($aDadosAgrupados as $oResult) {

        if ($oResult->k12_valor > 0) {

            if ($oResult->o58_coddot != $sReduzido || $iCont == 0) {

                $aLinhaTitle4 = array();
                array_push(
                    $aLinhaTitle4,
                    'DOTAÇÃO:',
                    'REDUZIDO: '.$oResult->o58_coddot,
                    str_pad($oResult->o58_orgao, 2, "0", STR_PAD_LEFT) . str_pad($oResult->o58_unidade, 3, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o58_funcao, 2, "0", STR_PAD_LEFT) . str_pad($oResult->o58_subfuncao, 3, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o58_programa, 4, "0", STR_PAD_LEFT) . str_pad($oResult->o58_projativ, 4, "0", STR_PAD_LEFT) .
                    str_pad($oResult->o56_elemento, 6, "0", STR_PAD_LEFT) . " - " . $oResult->o56_descr,
                    substr($oResult->o15_codtri.' - '.$oResult->o15_descr,0,55)
                );

                $aLinhas[] = $aLinhaTitle4;

                $sReduzido = $oResult->o58_coddot;

                $aLinhaTitle5 = array();
                array_push(
                    $aLinhaTitle5,
                    'Empenho', 
                    'Data Emp', 
                    'OP', 
                    'Data Ordem', 
                    'Fornecedor', 
                    'Data Pag',
                    'Valor', 
                    'Num Lic', 
                    'Mod Lic', 
                    'Nº DOC',
                );

                $aLinhas[] = $aLinhaTitle5;
            }

            $aLinhaRegister2 = array();
            array_push(
                $aLinhaRegister2, 
                $oResult->e60_codemp, 
                implode("/", array_reverse(explode("-", $oResult->e60_emiss))), 
                $oResult->e50_codord, 
                implode("/", array_reverse(explode("-", $oResult->e50_data))),
                substr($oResult->z01_nome, 0, 35),
                implode("/", array_reverse(explode("-", $oResult->k12_data))),
                number_format($oResult->k12_valor, "2", ",", "."),
                $oResult->e60_numerol, 
                $oResult->e60_tipol
            );

            $aLinhas[] = $aLinhaRegister2;

            if($ExibirHistoricoDoEmpenho == 01){
                $aLinhas[] = 'Histórico: ' . substr($oResult->e50_obs, 0, 160);
            }

            $nTotalizador += $oResult->k12_valor;

        }

    }

    $aLinhaTotal2 = array();
    array_push(
        $aLinhaTotal2,
        'Valor Total:', 
        number_format($nTotalizador, "2", ",", ".")
    );

    $aLinhas[] = $aLinhaTotal2;
}

if (empty($aLinhas)) {
    $msg = 'Não existem registros cadastrados.';
    db_redireciona('db_erros.php?fechar=true&db_erro='.$msg);
}

$filename = 'tmp/examearitimetico.csv';

$csv = fopen($filename, "w");

foreach ($aLinhas as $aLinha) {
    fputcsv($csv, $aLinha, ';');
}

fclose($csv);

$aLinhas[] = [];
unset($aLinhaTitle, $aLinhaTitle2, $aLinhaTitle3, $aLinhaRegister, $aLinhaTotal, $aLinhaTitle4, $aLinhaTitle5, $aLinhaRegister2, $aLinhaTotal2);
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function limparTelaEMostrarDownload() {

            document.body.innerHTML = '';
            document.body.style.backgroundColor = '#cccccc';

            var paragrafo = document.createElement('p');

            paragrafo.innerHTML = "<center><a id='downloadLink' href='tmp/examearitimetico.csv'>Clique no link para baixar o arquivo <b>examearitimetico.csv</b></a></center>";
            document.body.appendChild(paragrafo);

            var downloadLink = document.getElementById('downloadLink');
            downloadLink.style.display = 'inline-block';

            downloadLink.addEventListener('click', function() {
                setTimeout(function() {
                    alert('Download realizado com sucesso!');
                }, 1000);
            });
        }

        limparTelaEMostrarDownload();
    });
</script>