<?php
require_once 'model/relatorios/Relatorio.php';
require("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']); //
db_postmemory($HTTP_POST_VARS);
$oGet = db_utils::postmemory($_GET);

switch ($oGet->tipoprecoreferencia) {
    case 2:
        $tipoReferencia = " MAX(pc23_vlrun) ";
        break;

    case 3:
        $tipoReferencia = " MIN(pc23_vlrun) ";
        break;

    default:
        $tipoReferencia = " (sum(pc23_vlrun)/count(pc23_orcamforne)) ";
        break;
}


$rsLotes = db_query("select distinct  pc68_sequencial,pc68_nome
                        from
                            pcproc
                        join pcprocitem on
                            pc80_codproc = pc81_codproc
                        left join processocompraloteitem on
                            pc69_pcprocitem = pcprocitem.pc81_codprocitem
                        left join processocompralote on
                            pc68_sequencial = pc69_processocompralote
                        where
                            pc80_codproc = {$codigo_preco}
                            and pc68_sequencial is not null
                            order by pc68_sequencial asc");

$rsResultado = db_query("select pc80_criterioadjudicacao from pcproc where pc80_codproc = {$codigo_preco}");

if (pg_num_rows($rsLotes) == 0) {

    $sSql = "select si01_datacotacao FROM pcproc
    JOIN pcprocitem ON pc80_codproc = pc81_codproc
    JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
    JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
    JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
    JOIN pcorcamforne ON pc21_orcamforne = pc23_orcamforne
    JOIN solicitem ON pc81_solicitem = pc11_codigo
    JOIN solicitempcmater ON pc11_codigo = pc16_solicitem
    JOIN pcmater ON pc16_codmater = pc01_codmater
    JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
    JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
    WHERE pc80_codproc = {$codigo_preco} {$sCondCrit} and pc23_vlrun <> 0";

    $rsResultData = db_query($sSql) or die(pg_last_error());

    $sSql = "
            SELECT *
            FROM itemprecoreferencia
            INNER JOIN precoreferencia ON si01_sequencial = si02_precoreferencia
            INNER JOIN pcorcamitem ON si02_itemproccompra = pc22_orcamitem
            INNER JOIN pcorcamitemproc ON pc31_orcamitem = pc22_orcamitem
            INNER JOIN pcprocitem ON pc31_pcprocitem = pc81_codprocitem
            INNER JOIN solicitem ON pc81_solicitem = pc11_codigo
            WHERE si02_precoreferencia =
                    ( SELECT si01_sequencial
                     FROM precoreferencia
                     WHERE si01_processocompra = {$codigo_preco})
            ORDER BY pc11_seq;

            ";

    $rsResult = db_query($sSql);

            $pc80_criterioadjudicacao = db_utils::fieldsMemory($rsResultado, 0)->pc80_criterioadjudicacao;
            $codigoItem = db_utils::fieldsMemory($rsResult, 0)->si02_coditem;
            //$itemnumero = db_utils::fieldsMemory($rsResult, 0)->si02_itemproccompra;

            $rsItemCota = "
                    SELECT pc11_numero,
                           pc11_reservado,
                           pc11_quant,
                           pc16_codmater
                    FROM pcproc
                    JOIN pcprocitem ON pc80_codproc = pc81_codproc
                    JOIN solicitem ON pc81_solicitem = pc11_codigo
                    JOIN solicitempcmater ON pc11_codigo = pc16_solicitem
                    JOIN pcmater ON pc16_codmater = pc01_codmater
                    WHERE pc80_codproc = {$codigo_preco}
                        AND pc11_reservado = TRUE;
                    ";

            $rsResultItemCota = db_query($rsItemCota) or die(pg_last_error());
            $arrayValores = array();

            for($j=0;$j<pg_num_rows($rsResultItemCota);$j++){
                $valores = db_utils::fieldsMemory($rsResultItemCota, $j);
                $arrayValores[$j][0]=$valores->pc16_codmater;
                $arrayValores[$j][1]=$valores->pc11_quant;
            }
            $quantLinhas = count($arrayValores);


            if($codigoItem==""){

                for ($iCont = 0; $iCont < pg_num_rows($rsResult); $iCont++) {
                    $oResult = db_utils::fieldsMemory($rsResult, $iCont);
                            $sSql = "select
                            pc23_quant,
                            pc11_reservado,
                            pc01_codmater,
                            pc01_tabela,
                            pc01_taxa,
                            m61_codmatunid,
                            pc80_criterioadjudicacao
                        from
                            pcproc
                        join pcprocitem on
                            pc80_codproc = pc81_codproc
                        join solicitem on
                            pc81_solicitem = pc11_codigo
                        join solicitempcmater on
                            pc11_codigo = pc16_solicitem
                        join pcmater on
                            pc16_codmater = pc01_codmater
                        join solicitemunid on
                            pc11_codigo = pc17_codigo
                        join matunid on
                            pc17_unid = m61_codmatunid
                        join pcorcamitemproc on
                            pc81_codprocitem = pc31_pcprocitem
                        join pcorcamitem on
                            pc31_orcamitem = pc22_orcamitem
                        join pcorcamval on
                            pc22_orcamitem = pc23_orcamitem
                        where
                            pc23_orcamitem = $oResult->si02_itemproccompra
                            and (pc23_vlrun <> 0 or  pc23_percentualdesconto <> 0)
                        group by
                            pc23_quant,
                            pc31_pcprocitem,
                            pc11_reservado,
                            pc11_seq,
                            pc01_codmater,
                            pc01_tabela,
                            pc01_taxa,
                            m61_codmatunid,
                            pc80_criterioadjudicacao;
                            ";

                    $rsQueryUpdate = db_query($sSql);
                    $resultadoupdate = db_utils::fieldsMemory($rsQueryUpdate, 0);

                    if($resultadoupdate->pc11_reservado==""){
                        $valor = "f";
                    }else{
                        $valor = $resultadoupdate->pc11_reservado;
                    }

                    $sql = " update itemprecoreferencia set ";
                    $sql .= "si02_coditem = ".$resultadoupdate->pc01_codmater;
                    $sql .= ",si02_qtditem = ".$resultadoupdate->pc23_quant;
                    $sql .= ",si02_codunidadeitem = ".$resultadoupdate->m61_codmatunid;
                    $sql .= ",si02_reservado = '".$valor."'";
                    $sql .= ",si02_tabela = '".$resultadoupdate->pc01_tabela."'";
                    $sql .= ",si02_taxa = '".$resultadoupdate->pc01_taxa."'";
                    $sql .= ",si02_criterioadjudicacao = ".$resultadoupdate->pc80_criterioadjudicacao;
                    $sql .= " where si02_sequencial = ".$oResult->si02_sequencial;

                    $rsUpdateItemPrecoReferencia = db_query($sql);

                }
                $sSql = " SELECT *
                            FROM itemprecoreferencia
                            INNER JOIN precoreferencia ON si01_sequencial = si02_precoreferencia
                            INNER JOIN pcorcamitem ON si02_itemproccompra = pc22_orcamitem
                            INNER JOIN pcorcamitemproc ON pc31_orcamitem = pc22_orcamitem
                            INNER JOIN pcprocitem ON pc31_pcprocitem = pc81_codprocitem
                            INNER JOIN solicitem ON pc81_solicitem = pc11_codigo
                            WHERE si02_precoreferencia =
                                    ( SELECT si01_sequencial
                                     FROM precoreferencia
                                     WHERE si01_processocompra = {$codigo_preco})
                            ORDER BY si02_sequencial;
                            ";

                $rsResult = db_query($sSql);
            }

    header("Content-type: text/plain");
    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=Preco_de_Referencia_PRC_" . $codigo_preco . ".csv");
    header("Pragma: no-cache");

    echo "Preo de Referncia \n";
    echo "Processo de Compra: $codigo_preco \n";
    echo "Data: " . implode("/", array_reverse(explode("-", db_utils::fieldsMemory($rsResultData, 0)->si01_datacotacao))) . " \n";

    if ($pc80_criterioadjudicacao == 2 || $pc80_criterioadjudicacao == 1) {

        echo "SEQ;";
        echo "ITEM;";
        echo "DESCRICAO DO ITEM;";
        echo "VALOR UN;";
        echo "QUANT;";
        echo "UN;";
        echo "PERCENTUAL;";
        echo "TOTAL;\n";
    } else {

        echo "SEQ;";
        echo "ITEM;";
        echo "DESCRICAO DO ITEM;";
        echo "VALOR UN;";
        echo "QUANT;";
        echo "UN;";
        echo "TOTAL;\n";
    }


    $nTotalItens = 0;
    $sqencia = 0;
    $sSqlCasas = "select si01_casasdecimais from precoreferencia where si01_processocompra = {$codigo_preco};";
    $result_casaspreco = db_query($sSqlCasas) or die(pg_last_error());;

    $si01_casasdecimais = db_utils::fieldsMemory($result_casaspreco, 0)->si01_casasdecimais;

    for ($iCont = 0; $iCont < pg_num_rows($rsResult); $iCont++) {

                $oResult = db_utils::fieldsMemory($rsResult, $iCont);

                $sqlUndMedida = "select
                            m61_abrev
                        from
                            matunid
                        where m61_codmatunid = $oResult->si02_codunidadeitem";
                $rsUnidadeMedida = db_query($sqlUndMedida) or die(pg_last_error());
                $oUnidadeMedida = db_utils::fieldsMemory($rsUnidadeMedida,0);

                $sqlDescMater = "
                SELECT CASE
                           WHEN pc01_descrmater=pc01_complmater
                                OR pc01_complmater IS NULL THEN pc01_descrmater
                           ELSE pc01_descrmater||'. '||pc01_complmater
                       END AS pc01_descrmater
                FROM pcmater
                WHERE pc01_codmater = $oResult->si02_coditem";

                $rsDescrmater = db_query($sqlDescMater) or die(pg_last_error());
                $oDescrmater = db_utils::fieldsMemory($rsDescrmater,0);

                $lTotal = $oResult->si02_vltotalprecoreferencia;

                $nTotalItens += $lTotal;

                $oDadosDaLinha = new stdClass();
                $op = 1;

                    for($i=0;$i<$quantLinhas;$i++){

                        if($arrayValores[$i][0]==$oResult->si02_coditem){
                          $valorqtd = $arrayValores[$i][1];
                          $op=2;
                        }
                    }
                   $controle = 0;

                $oDadosDaLinha->seq = $sqencia + 1;
                $oDadosDaLinha->item = $oResult->si02_coditem;

                if ($oResult->pc11_reservado == "t") {
                    $oDadosDaLinha->descricao = '[ME/EPP] - ' . str_replace(';', "", $oDescrmater->pc01_descrmater);
                } else {
                    $oDadosDaLinha->descricao = str_replace(';', "", $oDescrmater->pc01_descrmater);
                }
                if ($oResult->si02_tabela == "t" || $oResult->si02_taxa == "t") {

                    $oDadosDaLinha->valorUnitario = number_format($oResult->si02_vlprecoreferencia, $si01_casasdecimais, ",", ".");
                    $oDadosDaLinha->quantidade = $oResult->si02_qtditem;
                    $oDadosDaLinha->percentual = number_format($oResult->si02_vlpercreferencia, 2) . "%";
                    $oDadosDaLinha->unidadeDeMedida = $oUnidadeMedida->m61_abrev;
                    $oDadosDaLinha->total = number_format($oResult->si02_vltotalprecoreferencia, 2, ",", ".");

                }else{


                    $oDadosDaLinha->valorUnitario = number_format($oResult->si02_vlprecoreferencia, $si01_casasdecimais, ",", ".");
                    $oDadosDaLinha->quantidade = $oResult->si02_qtditem;

                    if ($oResult->si02_vlpercreferencia == 0) {
                        $oDadosDaLinha->percentual = "";
                    } else {
                        $oDadosDaLinha->percentual = number_format($oResult->si02_vlpercreferencia, 2) . "%";
                    }

                    $oDadosDaLinha->unidadeDeMedida = $oUnidadeMedida->m61_abrev;

                    $lTotal = $oResult->si02_vltotalprecoreferencia;

                    $oDadosDaLinha->total = number_format($lTotal, 2, ",", ".");
                }

            $controle++;
            $sqencia++;

            $oDadosDaLinha->percentual = ($oResult->si02_tabela == "t" || $oResult->si02_taxa == "t") ? $oDadosDaLinha->percentual : "-";
            $oDadosDaLinha->valorUnitario = ($oResult->si02_tabela == "t" || $oResult->si02_taxa == "t") ? "-" : "R$ " . $oDadosDaLinha->valorUnitario;

            if ($pc80_criterioadjudicacao == 2 || $pc80_criterioadjudicacao == 1) {
                echo "$oDadosDaLinha->seq;";
                echo "$oDadosDaLinha->item;";
                echo "$oDadosDaLinha->descricao;";
                echo "$oDadosDaLinha->valorUnitario;";
                echo "$oDadosDaLinha->quantidade;";
                echo "$oDadosDaLinha->unidadeDeMedida;";
                echo "$oDadosDaLinha->percentual;";
                echo "R$ $oDadosDaLinha->total;\n";
            } else {
                echo "$oDadosDaLinha->seq;";
                echo "$oDadosDaLinha->item;";
                echo "$oDadosDaLinha->descricao;";
                echo "$oDadosDaLinha->valorUnitario;";
                echo "$oDadosDaLinha->quantidade;";
                echo "$oDadosDaLinha->unidadeDeMedida;";
                echo "R$ $oDadosDaLinha->total;\n";
            }

    }


    //    echo "VALOR TOTAL DOS ITENS;";
    //    echo "R$" . number_format($nTotalItens, 2, ",", ".").";";
    if ($oGet->impjust == 't') {
        echo "JUSTIFICATIVA;\n";
        echo str_replace(array(';', '.', ','), "", db_utils::fieldsMemory($rsResult, 0)->si01_justificativa);
    }
} else {

    $sSql = "select si01_datacotacao FROM pcproc
    JOIN pcprocitem ON pc80_codproc = pc81_codproc
    JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
    JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
    JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
    JOIN pcorcamforne ON pc21_orcamforne = pc23_orcamforne
    JOIN solicitem ON pc81_solicitem = pc11_codigo
    JOIN solicitempcmater ON pc11_codigo = pc16_solicitem
    JOIN pcmater ON pc16_codmater = pc01_codmater
    JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
    JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
    WHERE pc80_codproc = {$codigo_preco} {$sCondCrit} and pc23_vlrun <> 0";

    $rsResultData = db_query($sSql) or die(pg_last_error());

    header("Content-type: text/plain");
    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=Preco_de_Referencia_PRC_" . $codigo_preco . ".csv");
    header("Pragma: no-cache");

    echo "Preo de Referncia \n";
    echo "Processo de Compra: $codigo_preco \n";
    echo "Data: " . implode("/", array_reverse(explode("-", db_utils::fieldsMemory($rsResultData, 0)->si01_datacotacao))) . " \n";

    for ($i = 0; $i < pg_num_rows($rsLotes); $i++) {

        $oLotes = db_utils::fieldsMemory($rsLotes, $i);

            $sSql = "SELECT DISTINCT pc01_servico,
        pc11_codigo,
        pc11_seq,
        pc11_quant,
        pc11_prazo,
        pc11_pgto,
        pc11_resum,
        pc11_just,
        m61_abrev,
        m61_descr,
        pc17_quant,
        pc01_codmater,
        CASE
            WHEN pc01_complmater IS NOT NULL
                AND pc01_complmater != pc01_descrmater THEN pc01_descrmater ||'. '|| pc01_complmater
            ELSE pc01_descrmater
        END AS pc01_descrmater,
        pc01_complmater,
        pc10_numero,
        pc90_numeroprocesso AS processo_administrativo,
        (pc11_quant * pc11_vlrun) AS pc11_valtot,
        m61_usaquant,
        pc69_seq,
        pc11_reservado,
        si02_vlprecoreferencia,
        si02_vltotalprecoreferencia,
        si02_tabela,
        si02_taxa,
        si02_mediapercentual,
        si02_vlpercreferencia,
        si01_justificativa
        FROM pcprocitem
        JOIN solicitem ON pc11_codigo=pc81_solicitem
        JOIN solicita ON pc10_numero = pc11_numero
        JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
        JOIN solicitemunid ON pc17_codigo = pc11_codigo
        JOIN matunid ON pc17_unid = m61_codmatunid
        JOIN pcmater ON pc01_codmater = pc16_codmater
        JOIN pcorcamitemproc ON pc31_pcprocitem=pc81_codprocitem
        JOIN pcorcamitem ON pc22_orcamitem=pc31_orcamitem
        JOIN itemprecoreferencia ON si02_itemproccompra = pc22_orcamitem
        JOIN precoreferencia ON si02_precoreferencia = si01_sequencial
        JOIN pcorcamval ON pc23_orcamitem=pc22_orcamitem
        JOIN pcorcamforne ON pc21_orcamforne=pc23_orcamforne
        JOIN pcorcamjulg ON pc24_orcamforne=pc21_orcamforne
        LEFT JOIN solicitaprotprocesso ON pc90_solicita = pc10_numero
        LEFT JOIN processocompraloteitem ON pc69_pcprocitem = pcprocitem.pc81_codprocitem
        WHERE pc81_codproc={$codigo_preco} and pc69_processocompralote = $oLotes->pc68_sequencial
            AND pc24_pontuacao=1 order by pc11_seq";



        $rsResult = db_query($sSql) or die(pg_last_error());

        if(pg_num_rows($rsResult) > 0){

            echo "$oLotes->pc68_nome;\n";
            echo "ITEM LOTE;";
            echo "CODIGdO;";
            echo "DESCRICAO DO ITEM;";
            echo "VALOR UN;";
            echo "QUANT;";
            echo "UN;";
            echo "TOTAL;\n";
        }

        $nTotalItens = 0;

        for ($iCont = 0; $iCont < pg_num_rows($rsResult); $iCont++) {

            $oResult = db_utils::fieldsMemory($rsResult, $iCont);

            //if($quant_casas == 2){
            $lTotal = $oResult->si02_vltotalprecoreferencia;
            //}else $lTotal = round($oResult->si02_vlprecoreferencia,3) * $oResult->pc11_quant;

            $nTotalItens += $lTotal;

            $oDadosDaLinha = new stdClass();
            $oDadosDaLinha->seq = $iCont + 1;
            $oDadosDaLinha->item = $oResult->pc01_codmater;
            if ($oResult->pc11_reservado == 't') {
                $oDadosDaLinha->descricao = '[ME/EPP] - ' . str_replace(';', "", $oResult->pc01_descrmater);
            } else {
                $oDadosDaLinha->descricao = str_replace(';', "", $oResult->pc01_descrmater);
            }
            $oDadosDaLinha->descricao = str_replace("\n", "", $oDadosDaLinha->descricao);
            $oDadosDaLinha->valorUnitario = number_format($oResult->si02_vlprecoreferencia, $si01_casasdecimais, ",", ".");
            $oDadosDaLinha->quantidade = $oResult->pc11_quant;
            $oDadosDaLinha->unidadeDeMedida = $oResult->m61_abrev;
            $oDadosDaLinha->total = number_format($lTotal, 2, ",", ".");

            $oDadosDaLinha->percentual = ($oResult->si02_tabela == "t" || $oResult->si02_taxa == "t") ? $oDadosDaLinha->percentual : "-";
            $oDadosDaLinha->valorUnitario = ($oResult->si02_tabela == "t" || $oResult->si02_taxa == "t") ? "-" : "R$ " . $oDadosDaLinha->valorUnitario;

            echo "$oDadosDaLinha->seq;";
            echo "$oDadosDaLinha->item;";
            echo "$oDadosDaLinha->descricao;";
            echo "$oDadosDaLinha->valorUnitario;";
            echo "$oDadosDaLinha->quantidade;";
            echo "$oDadosDaLinha->unidadeDeMedida;";
            echo "R$ $oDadosDaLinha->total;\n";
        }
    }

    //    echo "VALOR TOTAL DOS ITENS;";
    //    echo "R$" . number_format($nTotalItens, 2, ",", ".").";";
    if ($oGet->impjust == 's') {
        echo "JUSTIFICATIVA;\n";
        echo str_replace(array(';', '.', ','), "", db_utils::fieldsMemory($rsResult, 0)->si01_justificativa);
    }
}
