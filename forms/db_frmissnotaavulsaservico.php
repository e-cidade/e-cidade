<?php

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clissnotaavulsaservico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q51_sequencial");

#recupera a inscri��o municipal utilizada no registro da nota
$id_nota = $get->q51_sequencial;
$arrayNotas = array();
$sqlPrestador = "select q51_inscr, q51_dtemiss  from issnotaavulsa where q51_sequencial = $id_nota";
$resultPrestador = db_utils::fieldsMemory(db_query($sqlPrestador), 0);
$id_inscricao = $resultPrestador->q51_inscr;
$data_emissao = $resultPrestador->q51_dtemiss;
$d = explode('-', $data_emissao);

#competencia atual e o max de dias que existe no m�s
$ano = $d[0];
$mes = $d[1];
$maxDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

#recupera as notas j� emitidas durante o periodo
$sqlNotas = "select * from issnotaavulsa where q51_inscr = '$id_inscricao' 
and q51_dtemiss >= '$ano-$mes-01' and q51_dtemiss <= '$ano-$mes-$maxDias' order by q51_dtemiss";
$resultNotas = db_query($sqlNotas);

$arrayNotas['soma_irrf'] = 0;
#recupera as informa��es da nota e organiza em um array
for($i = 0; $i < pg_num_rows($resultNotas); $i++) {
    $obj = db_utils::fieldsMemory($resultNotas, $i);
    $id_nota = $obj->q51_sequencial;

    #consulta as informa��es da nota
    $sqlNotasServico = "select * from issnotaavulsaservico where q62_issnotaavulsa = $id_nota";
    $resultNotasServico = db_query($sqlNotasServico);

    #verifica se a nota n�o esta cancelada
    $sqlNotaCancelada = "select * from issnotaavulsacanc where q63_issnotaavulsa = $id_nota";
    $resultNotasCancelada = db_query($sqlNotaCancelada);

    $sqlTomador = "select * from (select cgm.*, null as q02_inscr, q61_numcgm, q53_sequencial, q53_dtservico from issnotaavulsatomador inner join issnotaavulsatomadorcgm on q53_sequencial = q61_issnotaavulsatomador 
    inner join cgm on cgm.z01_numcgm = q61_numcgm where issnotaavulsatomador.q53_issnotaavulsa = $id_nota union select cgm.*, q02_inscr, null as q61_numcgm, q53_sequencial, q53_dtservico from issnotaavulsatomador 
    inner join issnotaavulsatomadorinscr on q53_sequencial = q54_issnotaavulsatomador inner join issbase on 
    q02_inscr = q54_inscr inner join cgm on q02_numcgm = z01_numcgm where issnotaavulsatomador.q53_issnotaavulsa = $id_nota ) as x";

    if(pg_num_rows($resultNotasServico) > 0 && pg_num_rows($resultNotasCancelada) == 0)
    {
        $resultTomador = db_query($sqlTomador);
        $objTomador =  db_utils::fieldsMemory($resultTomador, 0);
        $tomador = strtoupper($objTomador->z01_nome);

        if(strpos($tomador, 'PREFEITURA') !== false){
            $objNotas = db_utils::fieldsMemory($resultNotasServico, 0);
            $arrayNotas['valores'][] = $objNotas->q62_vlruni;
            $arrayNotas['id'][] = $id_nota;
            $arrayNotas['valor'] += $objNotas->q62_vlruni;
            $arrayNotas['deducao'] += $objNotas->q62_deducaoinss;
            $arrayNotas['soma_irrf'] += $objNotas->q62_vlrirrf;
        }
    }
}

#base de calculo - INSS e IRFF
$arrayNotas['soma_inss'] = 0;
$arrayNotas['soma_retido'] = 0;
$arrayNotas['base_irrf'] = 0;

for($i = 0; $i < sizeof($arrayNotas['valores']); $i++){
    $arrayNotas['inss'][] = calcularINSS($arrayNotas['valores'][$i]);
    $arrayNotas['soma_inss'] += $arrayNotas['inss'][$i];
    $calculo = ($arrayNotas['valores'][$i] * 0.20) * 0.11;
    $arrayNotas['inss_carga_passageiro'] += $calculo;
    $arrayNotas['base_irrf'] += $arrayNotas['valores'][$i] * 0.10;

    $arrayNotas['base_irrf_passageiro'][] = ($arrayNotas['valores'][$i] * 0.60) + $arrayNotas['base_irrf_passageiro'][$i-1];
    $arrayNotas['irrf_outros'] =  $arrayNotas['base_irrf_passageiro'][$i];

    $retido = $arrayNotas['base_irrf_passageiro'][$i] - $arrayNotas['inss_carga_passageiro'];
    $percentual = baseCalculoPercentual($retido);
    $fixo = baseCalculoFixo($retido);

    $calculoRetido = $percentual - $fixo;

    $arrayNotas['retido'][] = $calculoRetido;
    $arrayNotas['retido'][$i] = $arrayNotas['retido'][$i] - $arrayNotas['soma_retido'];
    $arrayNotas['soma_retido'] += $arrayNotas['retido'][$i];
}

#processamento conforme regra de calculo estabelecida 
for($i = 0; $i < sizeof($arrayNotas['valores']); $i++){
    $soma_valores = 0;
    $soma_inss = 0;
    $calculo = 0;

    for($z = 0; $z < $i+1; $z++){
        $soma_valores += $arrayNotas['valores'][$z];
        $soma_inss += $arrayNotas['inss'][$z];
    }

    if($soma_inss > 856.46){
        $soma_inss = 856.46;
    }

    $calculo = $soma_valores - $soma_inss;

    $arrayNotas['base_calculo'][] = $calculo;
}

#calculo final
for($i = 0; $i < sizeof($arrayNotas['base_calculo']); $i++){
    $arrayNotas['retencao_fixo'][] = baseCalculoFixo($arrayNotas['base_calculo'][$i]);
    $arrayNotas['retencao_percentual'][] = baseCalculoPercentual($arrayNotas['base_calculo'][$i]);
    $arrayNotas['retencao_calculada'][] = abs($arrayNotas['retencao_percentual'][$i] - $arrayNotas['retencao_fixo'][$i]);
}

#la�o inicia na segunda posi��o do array, o valor da posi��o zero � deduzido
#no segundo elemento da reten��o e assim por diante
for($i = 1; $i < sizeof($arrayNotas['retencao_calculada']); $i++){
    $arrayNotas['retencao'][] = abs($arrayNotas['retencao_calculada'][$i] - $arrayNotas['retencao_calculada'][$i-1]);
}

for($i = 0; $i < sizeof($arrayNotas['retencao']); $i++){
    $arrayNotas['retencao_total'] += $arrayNotas['retencao'][$i];
}

$arrayNotas['retencao_total'] = floor(($arrayNotas['retencao_total']*100))/100;

#variaveis utilizadas no calculo via js
$retencaoIRRF = $arrayNotas['retencao_total'];
$somaInss = $arrayNotas['soma_inss'];
$somaIrrf = $arrayNotas['soma_irrf'];
$valorTotal = $arrayNotas['valor'];
$baseIRRF = $arrayNotas['base_irrf'];
$baseIRRFPassageiro = $arrayNotas['irrf_outros'];
$inss_carga_passageiro = $arrayNotas['inss_carga_passageiro'];
$soma_retido = $arrayNotas['soma_retido'];

if($somaInss > 856.46){
    $somaInss = 856.46;
}

if(empty($retencaoIRRF)){
    $retencaoIRRF = $arrayNotas['retencao_calculada'][0];
}

function calcularINSS($valorNota){
    return $valorNota * 0.11;
}

function baseCalculoFixo($valorNota){
    $valor = 0;
    switch($valorNota){
        case ($valorNota <= 2112.00):
            $valor = 0;
            break;
        case ($valorNota >= 2112.00) && ($valorNota <= 2826.65):
            $valor = 169.44;
            break;
        case ($valorNota >= 2826.66) && ($valorNota <= 3751.05):
            $valor = 381.44;
            break;
        case ($valorNota >= 3751.06) && ($valorNota <= 4664.68):
            $valor = 662.77;
            break;
        case ($valorNota > 4664.68):
            $valor = 896.00;
            break;
    }

    return $valor;
}

function baseCalculoPercentual($valorNota){
    $valor = 0;
    switch($valorNota){
        case $valorNota <= 2112.00:
            $valor = 0;
            break;
        case ($valorNota >= 2112.01) && ($valorNota <= 2826.65):
            $valor = $valorNota * 0.075;
            break;
        case ($valorNota >= 2826.66) && ($valorNota <= 3751.05):
            $valor = $valorNota * 0.15;
            break;
        case ($valorNota >= 3751.06) && ($valorNota <= 4664.68):
            $valor = $valorNota * 0.2250;
            break;
        case $valorNota > 4664.68:
            $valor = $valorNota * 0.2750;
            break;
    }

    return $valor;
}

//calculo da reten�ao das notas emitidas
$retencao = 0;

#retencao inss
$retencao = ($arrayNotas['valor'] * 0.11); //11%

if (isset($db_opcaoal)) {
    $db_opcao = 33;
    $db_botao = false;
} else if (isset($opcao) && $opcao == "alterar") {
    $db_botao = true;
    $db_opcao = 2;
} else if (isset($opcao) && $opcao == "excluir") {
    $db_opcao = 3;
    $db_botao = true;
} else {
    $db_opcao = 1;
    $db_botao = true;
    if (isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false)) {
        $q62_issnotaavulsa = "";
        $q62_qtd = "";
        $q62_discriminacao = "";
        $q62_vlruni = "";
        $q62_aliquota = "";
        $q62_vlrdeducao = "";
        $q62_vlrtotal = "";
        $q62_vlrbasecalc = "";
        $q62_vlrissqn = "";
        $q62_obs = "";
        $q62_vlrirrf = '';
        $q62_vlrinss = '';
        $q62_tiporetirrf = '';
        $q62_tiporetinss = '';
        $q62_deducaoinss = '';
        $q62_qtddepend = '';
        $q62_baseirrf = '';
        $q62_basecalcaposinss = '';
        $q62_aliquotairrf = '';
        $q62_baseinss = '';
    }
}
$SQLTotLinhas  = "select q62_discriminacao";
$SQLTotLinhas .= "  from issnotaavulsaservico ";
$SQLTotLinhas .= " where q62_issnotaavulsa = {$get->q51_sequencial}";
$rsTotLInhas = pg_query($SQLTotLinhas);
$totlinhas = 0;
$hasServico = false;
if (pg_num_rows($rsTotLInhas) > 0) {
    $hasServico = true;
    for ($i = 0; $i < pg_num_rows($rsTotLInhas); $i++) {

        $oLinha = db_utils::fieldsMemory($rsTotLInhas, $i);
        $totlinhas += db_calculaLinhasTexto22($oLinha->q62_discriminacao);

    }

}

$sSqlHasRecibo = "select q52_numnov from issnotaavulsanumpre ";
$sSqlHasRecibo .= "where q52_issnotaavulsa = {$get->q51_sequencial}";

$rsHasRecibo = pg_query($sSqlHasRecibo);
$hasRecibo = false;

if (pg_num_rows($rsHasRecibo) > 0) {
    $hasRecibo = true;
}

$sSqlAnoFolha = "select max(r11_anousu) as r11_anousu from cfpess";
$oAnoFolha = db_utils::fieldsMemory(db_query($sSqlAnoFolha), 0);

$iCodInstit = intval(db_getsession('DB_instit'));
$iCodAnousu = intval($oAnoFolha->r11_anousu);

$sSQLTabelaIRRF = "
SELECT r33_inic, r33_fim, r33_perc, r33_deduzi
FROM inssirf
WHERE r33_instit = {$iCodInstit}
    AND r33_anousu = {$iCodAnousu}
    AND r33_mesusu = (
      SELECT MAX(r33_mesusu)
      FROM inssirf
      WHERE r33_anousu = {$iCodAnousu}
          AND r33_instit = {$iCodInstit}
          AND r33_codtab = '1'
    )
    AND r33_codtab = '1'
ORDER BY r33_anousu DESC,
         r33_mesusu DESC,
         r33_inic ASC";

$sSQLValorDependente = "SELECT r07_valor
FROM pesdiver
WHERE r07_anousu = {$iCodAnousu}
  AND r07_instit = {$iCodInstit}
  AND r07_mesusu = (
      SELECT MAX(r07_mesusu)
      FROM pesdiver
      WHERE r07_anousu = {$iCodAnousu} AND r07_instit = {$iCodInstit}
    )
    AND r07_codigo = 'D901'
ORDER BY r07_anousu DESC,
         r07_mesusu DESC
LIMIT 1";

$sqlTipoPrestador = "SELECT CASE WHEN char_length(cgm.z01_cgccpf) = 14 THEN 'juridica' ELSE 'fisica' END AS tipopessoa 
FROM issnotaavulsa 
INNER JOIN issbase  ON q02_inscr = q51_inscr 
INNER JOIN cgm ON z01_numcgm = q02_numcgm 
WHERE q51_sequencial = {$get->q51_sequencial}";

$aValoresTabela = db_utils::getCollectionByRecord(db_query($sSQLTabelaIRRF));
$oValorDependente = db_utils::fieldsMemory(db_query($sSQLValorDependente), 0);
$oTipoPrestador = db_utils::fieldsMemory(db_query($sqlTipoPrestador), 0);

if($oTipoPrestador->tipopessoa == 'juridica'){    
    $dbopcaopjpf = $db_opcao;
}else{
    $dbopcaopjpf = $db_opcao;
}

$aTiposRetencoesIRRF = array(
    'nada' => 'Selecione um tipo',
    'passageiros' => 'IRRF Transporte de Passageiros',
    'material' => 'IRRF Transporte de Material',
    'outros' => 'IRRF Outros'
);

$aTiposRetencoesINSS = array(
    'nada' => 'Selecione um tipo',
    'passageiros' => 'Transporte de Passageiros',
    'material' => 'Transporte de Material',
    'outros' => 'Outros'
);

?>

<style type="text/css">

    .margin-v10 {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .text-right {
        text-align: right;
    }

    .td-retencao {
        width: 127px;
        font-weight: bold;
    }

    .td-retencao-campos {
        width: 130px;
    }

</style>

<form name="form1" method="post" action="">
    <center>
        <table border="0">
            <tr>
                <td> 
                    <fieldset>
                        <legend><b>Servi�os</b></legend>
                        <table>
                            <?
                            $q62_issnotaavulsa = $get->q51_sequencial;
                            db_input('q62_issnotaavulsa', 10, $Iq62_issnotaavulsa, true, 'hidden', $db_opcao, " onchange='js_pesquisaq62_issnotaavulsa(false);'");
                            db_input('q62_sequencial', 10, $Iq62_sequencial, true, 'hidden', $db_opcao, "");
                            $q62_qtd = 1;
                            db_input('q62_qtd', 10, $Iq62_qtd, true, 'hidden', $db_opcao, "");
                            ?>
                            <tr>
                                <td nowrap title="C�dido do Servi�o">
                                    <strong><? db_ancora("C�digo do Servi�o:", "js_pesquisa_servico(true);", $db_opcao); ?></strong>
                                </td>
                                <td colspan='3'>
                                    <?php
                                    $db121_estrutural = isset($db121_estrutural) ? $db121_estrutural : "";
                                    $db121_descricao = isset($db121_descricao) ? $db121_descricao : "";
                                    db_input("db121_estrutural", 10, "text", TRUE, "text", 3);
                                    db_input("q62_issgruposervico", 10, "text", FALSE, "hidden", 3);
                                    db_input("db121_descricao", 44, "text", TRUE, "text", 3);
                                    db_input("q62_baseirrf", 10, $Iq62_baseirrf, true, "hidden", 3);
                                    db_input("q62_baseinss", 10, $Iq62_baseinss, true, "hidden", 3);
                                    db_input("q62_basecalcaposinss", 10, $Iq62_basecalcaposinss, true, "hidden", 3);
                                    db_input("q62_aliquotairrf", 10, $Iq62_aliquotairrf, true, "hidden", 3);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tq62_discriminacao ?>">
                                    <?= @$Lq62_discriminacao ?>
                                </td>
                                <td colspan='3'>
                                    <?
                                    db_textarea('q62_discriminacao', 2, 57, $Iq62_discriminacao, true, 'text', $db_opcao, "onkeyup=''");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tq62_vlruni ?>">
                                    <strong>Valor da Nota</strong>
                                </td>
                                <td>
                                    <?
                                    db_input('q62_vlruni', 12, $Iq62_vlruni, true, 'text', $db_opcao, "onblur='js_calcula()'");
                                    ?>
                                </td>
                                
                                <td nowrap title="<?= @$Tq62_vlrtotal ?>" style="display:none">
                                    <?= @$Lq62_vlrtotal ?>
                                </td>
                                <td style="display:none">
                                    <?
                                    db_input('q62_vlrtotal', 15, $Iq62_vlrtotal, true, 'text', 3, "")
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tq62_vlrdeducao ?>">
                                    &nbsp;
                                    <?php //=@$Lq62_vlrdeducao ?>
                                </td>
                                <td>
                                    &nbsp;
                                    <?php db_input('q62_vlrdeducao', 12, $Iq62_vlrdeducao, true, 'hidden', $db_opcao, "onblur=\"js_calcula();\"") ?>
                                </td>
                                <td nowrap title="<?= @$Tq62_vlrbasecalc ?>" style="display:none">
                                    <?= @$Lq62_vlrbasecalc ?>
                                </td>
                                <td style="display:none">
                                    <?php db_input('q62_vlrbasecalc', 15, $Iq62_vlrbasecalc, true, 'text', 3, "") ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tq62_aliquota ?>">
                                    <b><?= @$Lq62_aliquota ?> <b>ISSQN %</b>
                                </td>
                                <td>
                                    <?php db_input('q62_aliquota', 12, $Iq62_aliquota, true, 'text', 3, "onblur='js_calcula()'") ?>
                                </td>
                                <td nowrap title="<?= @$Tq62_vlrissqn ?>">
                                    <?= @$Lq62_vlrissqn ?>
                                </td>
                                <td>
                                    <?php db_input('q62_vlrissqn', 15, $Iq62_vlrissqn, true, 'text', 3, "") ?>
                                </td>
                            </tr>
                            <tr>
                               <td class="td-retencao" nowrap title="Reten��es outros INSS">
                                    <strong>Reten��es outros INSS: </strong>
                               </td>
                                <td class="td-retencao-campos">
                                    <?php db_input('q62_deducaoinss', 15, $Iq62_deducaoinss, true, 'text', $db_opcao, '0') ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-retencao" nowrap title="<?= @$Tq62_qtddepend ?>">
                                    <?= @$Lq62_qtddepend ?>
                                </td>
                                <td class="td-retencao-campos">
                                   <?php db_input('q62_qtddepend', 15, $Iq62_qtddepend, true, 'text', $db_opcao, '0') ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-retencao">Tipo de servi�o:</td>
                                <td colspan="3">
                                   <?php db_select('q62_tiporetinss', $aTiposRetencoesINSS, true, 2, "onchange='js_RetencaoINSS();'"); ?>
                               </td>
                            </tr>
                            <!-- INSS -->
                            <tr>
                                <td colspan="4">
                                    <fieldset class="margin-v10">
                                        <legend>INSS</legend>

                                        <table>
                                            <tr>
                                                <td class="td-retencao" nowrap title="<?= @$Tq62_vlrinss ?>">
                                                    <?= @$Lq62_vlrinss ?>
                                                </td>
                                                <td class="td-retencao-campos" colspan="3">
                                                    <?php db_input('q62_vlrinss', 15, $Iq62_vlrinss, true, 'text', $dbopcaopjpf, "")//3 ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                            <!-- IRRF -->
                            <tr>
                                <td colspan="4">
                                    <fieldset class="margin-v10">
                                        <legend>IRRF</legend>
                                        <table>
                                            <tr>
                                                <td class="td-retencao" nowrap title="<?= @$Tq62_vlrirrf ?>">
                                                    <?= @$Lq62_vlrirrf ?>
                                                </td>
                                                <td class="td-retencao-campos" colspan="3">
                                                    <?php db_input('q62_vlrirrf', 15, $Iq62_vlrirrf, true, 'text', $dbopcaopjpf, "")//3 ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tq62_obs ?>">
                                    <?= @$Lq62_obs ?>
                                </td>
                                <td colspan='3'>
                                    <?
                                    db_textarea('q62_obs', 0, 57, $Iq62_obs, true, 'text', $db_opcao, "onkeyup='js_controlatextarea(this.name,200);'");
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            </tr>
            <td colspan="2" align="center">
                <input type='hidden' id='totlinhas' readonly name='totlinhas' value="<?= $totlinhas; ?>">
                <?php
                if($db_opcao == 1 && !$hasServico){
                ?>
                <input type="submit" name="incluir" id="db_opcao" value="Incluir" <?= ($db_botao == false ? "disabled" : "") ?>>
                <?php } ?>

                <?php
                if($db_opcao == 2 || $db_opcao == 22){
                ?>
                <input type="submit" name="alterar" id="db_opcao" value="Alterar" <?= ($db_botao == false ? "disabled" : "") ?>>
                <?php } ?>

                <?php
                if($db_opcao == 3 || $db_opcao == 33){
                ?>
                <input type="submit" name="excluir" id="db_opcao" value="Excluir" <?= ($db_botao == false ? "disabled" : "") ?>>
                <?php } ?>

                <input name="novo" type="button" id="cancelar" value="Novo"
                       onclick="js_cancelar();" <?= ($db_opcao == 1 || isset($db_opcaoal) || $hasServico ? "style='visibility:hidden;'" : "") ?> >
                <input name="recibo" type="submit" <?= ($hasRecibo || !$hasServico ? "disabled" : "") ?> onclick='return js_emiteRecibo(<?= $oPar->q60_notaavulsavlrmin; ?>)'
                       id="recibo" value="Emitir Recibo">
                <input name='notaavulsa' <?= (!$hasServico ? 'style="display:none;"' : '') ?> onclick='return js_verificaNota();' type='submit' id='nota'
                       value='Emitir nota'>
                <?
                $fTotal = 0;
                $sql = "select sum(q62_vlrissqn) as totalissqn,";
                $sql .= "sum(q62_vlruni) as q62_vlrini,";
                $sql .= "sum(q62_vlrdeducao) as q62_vlrdeducao,";
                $sql .= "sum(q62_vlrtotal) as q62_vlrtotal,";
                $sql .= "sum(q62_vlrbasecalc) as q62_vlrbasecalc";
                $sql .= " from issnotaavulsaservico
 									where q62_issnotaavulsa = " . $q62_issnotaavulsa;
                $oTotal = db_utils::fieldsMemory(pg_query($sql), 0);
                $totalissqn = $oTotal->totalissqn;
                ?>
            </td>
            </tr>
        </table>
        <table>
            <tr>
                <td valign="top" align="center">
                    <?
                    $chavepri = array("q62_sequencial" => $get->q51_sequencial);
                    $cliframe_alterar_excluir->chavepri = $chavepri;
                    $cliframe_alterar_excluir->sql = $clissnotaavulsaservico->sql_query_file(null, "*", "q62_sequencial"
                        , "q62_issnotaavulsa=" . $get->q51_sequencial);
                    $cliframe_alterar_excluir->campos = "q62_sequencial,q62_issnotaavulsa,q62_qtd,db121_estrutural, q62_discriminacao,q62_vlruni,q62_aliquota,q62_vlrdeducao,q62_vlrtotal,q62_vlrbasecalc,q62_vlrissqn, q62_vlrirrf, q62_vlrinss";
                    $cliframe_alterar_excluir->legenda = "ITENS LAN�ADOS";
                    $cliframe_alterar_excluir->iframe_height = "160";
                    $cliframe_alterar_excluir->iframe_width = "700";
                    if ($hasRecibo) {

                        $cliframe_alterar_excluir->opcoes = 4;

                    }
                    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
                    ?>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <table class='tab' width='100%'>
                        <tr style='text-align:right'>
                            <th rowspan='2'><B>TOTAIS</b></th>
                            <th><b>Dedu��es</b></th>
                            <th><b>Valor Total</b></th>
                            <th><b>Base C�lculo </b></th>
                            <th><b>valor ISSQN </b></th>
                        </tr>
                        <tr>
                            <td>
                                <?= number_format($oTotal->q62_vlrdeducao, 2, ",", ".") ?>
                            </td>
                            <td>
                                <?= number_format($oTotal->q62_vlrtotal, 2, ",", ".") ?>
                            </td>
                            <td>
                                <?= number_format($oTotal->q62_vlrbasecalc, 2, ",", ".") ?>
                            </td>
                            <td>
                                <input type='' id='vlrrectotal'
                                       readonly style='border:0;background:transparent'
                                       value='<?= number_format($totalissqn, 2, ',', '.'); ?>' name='vlrrectotal'>
                            </td>
                        </tr>
                    </table>
    </center>
</form>
<script type="text/javascript" src="scripts/strings.js"></script>
<script>
    function js_cancelar() {
        var opcao = document.createElement("input");
        opcao.setAttribute("type", "hidden");
        opcao.setAttribute("name", "novo");
        opcao.setAttribute("value", "true");
        document.form1.appendChild(opcao);
        document.form1.submit();
    }

    function js_pesquisaq62_issnotaavulsa(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsaservico', 'db_iframe_issnotaavulsa', 'func_issnotaavulsa.php?funcao_js=parent.js_mostraissnotaavulsa1|q51_sequencial|q51_sequencial', 'Pesquisa', true, '0', '1', '775', '390');
        } else {
            if (document.form1.q62_issnotaavulsa.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsaservico', 'db_iframe_issnotaavulsa', 'func_issnotaavulsa.php?pesquisa_chave=' + document.form1.q62_issnotaavulsa.value + '&funcao_js=parent.js_mostraissnotaavulsa', 'Pesquisa', false);
            } else {
                document.form1.q51_sequencial.value = '';
            }
        }
    }

    function js_mostraissnotaavulsa(chave, erro) {
        document.form1.q51_sequencial.value = chave;
        if (erro == true) {
            document.form1.q62_issnotaavulsa.focus();
            document.form1.q62_issnotaavulsa.value = '';
        }
    }

    function js_mostraissnotaavulsa1(chave1, chave2) {
        document.form1.q62_issnotaavulsa.value = chave1;
        document.form1.q51_sequencial.value = chave2;
        db_iframe_issnotaavulsa.hide();
    }

    function js_setValorTotal() {

        iQtde = new Number(document.getElementById('q62_qtd').value);
        dVlUni = new Number(document.getElementById('q62_vlruni').value);
        dTotal = (iQtde * dVlUni);
        dTotal = js_round(dTotal, 2);
        document.getElementById('q62_vlrtotal').value = dTotal;
    }

    function js_setValorIssqn() {

        dBaseCalc = new Number(document.getElementById('q62_vlrbasecalc').value);
        dAliquota = new Number(document.getElementById('q62_aliquota').value);
        dTotal = (dBaseCalc * (dAliquota / 100));
        dTotal = js_round(dTotal, 2);
        document.getElementById('q62_vlrissqn').value = dTotal;
    }

    function js_setValorBaseCalculo() {

        dDeducoes = new Number(document.getElementById('q62_vlrdeducao').value);
        dVlTotal = new Number(document.getElementById('q62_vlrtotal').value);
        dTotal = (dVlTotal - dDeducoes);
        dTotal = js_round(dTotal, 2);
        document.getElementById('q62_vlrbasecalc').value = dTotal;
    }
    function js_testaDeducao() {

        dDeducao = new Number(document.getElementById('q62_vlrdeducao').value);
        dVlTotal = new Number(document.getElementById('q62_vlrtotal').value);
        if (dDeducao != 0 && (dDeducao > dVlTotal)) {

            document.getElementById('q62_vlrdeducao').value = '';
            alert('Valor da Deducao nao pode ser maior que o valor total');
            document.getElementById('q62_vlrdeducao').focus();
        }
    }

    function js_calcula() {

        js_setValorTotal();
        js_testaDeducao();
        js_setValorBaseCalculo();
        js_setValorIssqn();

    }

    function js_emiteRecibo(valMin) {

        valNota = $F('vlrrectotal').replace(".", '');
        valNota = valNota.replace(",", '.');
        valNota = new Number(valNota);
        valMin = new Number(valMin);

        if ($F('totlinhas') > 40) {

            alert('Total das linhas da descri��o da nota maior que o permitido (40 linhas)');
            return false;

        }
        if (valNota >= valMin) {
            parent.iframe_issnotaavulsa.document.getElementById('db_opcao').disabled = true;
            parent.iframe_issnotaavulsa.document.getElementById('nota').style.display = "";
            parent.iframe_issnotaavulsa.document.getElementById('recibo').disabled = true;

            parent.iframe_issnotaavulsatomador.document.getElementById('db_opcao').disabled = true;
            parent.iframe_issnotaavulsatomador.js_controlaAncora(false);

            return true;
        } else {
            alert('Recibo n�o pode ser emitido.\nValor do imposto menor que o  valor configurado R$' + valMin);
            return false;
        }
    }

    function js_verificaNota() {

        if ($F('totlinhas') > 40) {

            alert('Total das linhas da descri��o da nota maior que o permitido (40 linhas)');
            return false;

        }
    }

    function js_controlatextarea(objt, max) {
        obj = eval('document.form1.' + objt);
        atu = max - obj.value.length;
        if (obj.value.length > max) {
            alert('A mensagem pode ter no m�ximo ' + max + ' caracteres !');
            obj.value = obj.value.substr(0, max);
            obj.select();
            obj.focus();
        }
    }

    function calculaValorDePorcentagem(base, percent) {
        return Number((base * percent) / 100).toFixed(2);
    }

    function calculaPorcentagemDeValor(base, valor) {
        return Number(valor * 100 / base).toFixed(2);
    }

    function getValorINSS() {
        return parseFloat(document.getElementById('q62_vlrinss').value);
    }

    function calculoRetencaoINSS(info, percentual) {
        var novaRetencao = calculaValorDePorcentagem(info.baseCalculo, percentual);
        var valorNota = parseFloat(document.getElementById('q62_vlruni').value);

        //A dedu�ao � feita apenas para a op�ao INSS OUTROS
        if(percentual == 11){
            novaRetencao = baseCalculoOutros(valorNota, info);
        }else if(percentual == 2.2){
            novaRetencao = baseCalculoTransportePassageiro(valorNota, info);
        }else{
            novaRetencao = baseCalculoTransporteCarga(valorNota, info);
        }

        return novaRetencao;
    }

    function getValoresTabelasIRRF() {
        return {
            valorDependente: <?= floatval($oValorDependente->r07_valor) ?>,
            tabelas: <?= json_encode($aValoresTabela) ?>
        };
    }

    function decideDeducaoIRRF(valorBase) {

        valorBase = valorBase < 0 ? 1 : valorBase;
        var jsonValoresIRRF = getValoresTabelasIRRF().tabelas;

        if (!jsonValoresIRRF.length) {
            alert('Imposs�vel realizar c�lculo sem tabelas do IRRF.');
            return null;
        }

        return jsonValoresIRRF.filter(function (obj) {

            var valorIni = Number(obj.r33_inic);
            var valorFim = Number(obj.r33_fim);

            return ((valorBase >= valorIni) && (valorBase <= valorFim));

        }).shift();
    }

    function calculoRetencaoIRRF(info) {

        var valoresIRRF = getValoresTabelasIRRF();

        var baseIRRF = info.valorNota - info.baseIRRF - info.valorINSS;
        baseIRRF -= (valoresIRRF.valorDependente * info.qtdDependentes);
        baseIRRF = baseIRRF.toFixed(2);

        var deducaoIRRF = decideDeducaoIRRF(baseIRRF);

        var valorFinal = calculaValorDePorcentagem(baseIRRF, Number(deducaoIRRF.r33_perc));
        valorFinal -= Number(deducaoIRRF.r33_deduzi).toFixed(2);

        console.log(valoresIRRF);
        console.log(info);
        console.log(baseIRRF);
        console.log(deducaoIRRF);

        return valorFinal;
    }

    function js_RetencaoINSS() {

        var select = document.getElementById('q62_tiporetinss');

        var info = {
            limiteINSS: 856.46,
            baseCalculo: parseFloat(document.getElementById('q62_vlrtotal').value),
            retencoesAntigas: document.getElementById('q62_deducaoinss'),
            retencaoExistente: <?= $retencao ?>,
            baseCalculoProcessado : parseFloat(<?= $retencaoIRRF ?>),
            somaInss :  parseFloat(<?= $somaInss ?>),
            somaIrrf : parseFloat(<?= $somaIrrf ?>),
            baseIrrf :  parseFloat(<?= $baseIRRF ?>),
            baseIrrfPassageiro :  Math.abs(parseFloat(<?= $baseIRRFPassageiro ?>)),
            InssCargaPassageiro :  parseFloat(<?= $inss_carga_passageiro ?>),
            somaRetido :  parseFloat(<?= $soma_retido ?>),
            valorTotal :  parseFloat(<?= $valorTotal ?>)
        };

        var campos = {
            valorFinal: document.getElementById('q62_vlrinss')
        };

        if (!select.value) {
            campos.valorFinal.value = '';
            return;
        }

        if (isNaN(info.baseCalculo)) {
            alert('Defina um valor para a base de c�lculo antes de prosseguir.');
            campos.valorFinal.value = '';
            select.value = 'nada';
            return;
        }
        
        //limpar campos automaticamente
        document.getElementById('q62_vlrinss').value = 0;
        document.getElementById('q62_vlrirrf').value = 0;

        var retencoes = {
            passageiros: calculoRetencaoINSS.bind(null, info, 2.2),
            material: calculoRetencaoINSS.bind(null, info, 1.1),
            outros: calculoRetencaoINSS.bind(null, info, 11),
            nada: function () {
                return 0;
            },
        };

        if (retencoes[select.value]) {
            var calculo = retencoes[select.value]();
            campos.valorFinal.value = Number(calculo).toFixed(2);

        } else {
            select.value = '';
        }
        
        if(select.value !== 'outros'){
            js_RetencaoIRRF();
        }
    }


    function js_RetencaoIRRF() {

        //campo original foi removido, para normatizar utilizamos a op�ao escolhida no INSS
        var select = document.getElementById('q62_tiporetinss');

        var retencoes = {
            passageiros: 60,
            material: 10,
            outros: 0
        };

        if (!select.value || (retencoes[select.value] == undefined)) {
            return;
        }

        var info = {};
        info.valorNota = parseFloat(document.getElementById('q62_vlrtotal').value);
        info.baseIRRF = calculaValorDePorcentagem(info.valorNota, retencoes[select.value]);
        info.valorINSS = getValorINSS();
        info.qtdDependentes = parseInt(document.getElementById('q62_qtddepend').value);

        var campos = {
            valorFinal: document.getElementById('q62_vlrirrf')
        };

        if (isNaN(info.valorINSS)) {
            alert('Defina um valor das reten��es de INSS');
            campos.valorFinal.value = '';
            select.value = 'nada';
            return;
        }

        if (isNaN(info.qtdDependentes)) {
            alert('Defina o n�mero de dependentes.');
            campos.valorFinal.value = '';
            select.value = 'nada';
            return;
        }

        var valoresIRRF = calculoRetencaoIRRF(info);
    }

    document.getElementById('q62_deducaoinss').addEventListener('change', js_RetencaoINSS);
    document.getElementById('q62_qtddepend').addEventListener('change', js_RetencaoIRRF);

    /**
     * Pesquisa Servi�o
     *
     */
    function js_pesquisa_servico(mostra) {

        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_issgruposervico', 'func_issgruposervico.php?tipotributacao=2&funcao_js=parent.js_mostraServico1|q126_sequencial|db121_descricao|q136_valor|db121_estrutural', 'Pesquisa', true);
        } else {

            if (document.form1.db121_estrutural.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_issgruposervico', 'func_issgruposervico.php?tipotributacao=2pesquisa_chave=' + document.form1.db121_estrutural.value + '&funcao_js=parent.js_mostraServico', 'Pesquisa', false);
            } else {
                document.form1.db121_descricao.value = '';
            }
        }
    }

    function js_mostraServico(chave, chave2, erro) {

        document.form1.db121_descricao.value = chave;
        document.form1.q62_aliquota.value = chave2;

        if (erro == true) {

            document.form1.db121_estrutural.focus();
            document.form1.db121_estrutural.value = '';
        }
    }

    function js_mostraServico1(chave1, chave2, chave3, chave4) {

        document.form1.db121_estrutural.value = chave4;
        document.form1.db121_descricao.value = chave2;
        document.form1.q62_aliquota.value = chave3;
        document.form1.q62_issgruposervico.value = chave1;
        js_calcula();
        db_iframe_issgruposervico.hide();
    }

    function baseCalculoFixo(valorNota){
        valor = 0;
            if (valorNota <= 2112.00){
                valor = 0;
            }
            if ((valorNota >= 2112.01) && (valorNota <= 2826.65)){
                valor = 169.44;
            }
            if ((valorNota >= 2826.66) && (valorNota <= 3751.05)){
                valor = 381.44;
            }
            if ((valorNota >= 3751.06) && (valorNota <= 4664.68)){
                valor = 662.77;
            }
            if ((valorNota > 4664.68)){
                valor = 896.00;
            }
        return valor;
    }

    function baseCalculoPercentual(valorNota){
        valor = 0;
        document.getElementById('q62_basecalcaposinss').value = valorNota;

            if (valorNota <= 2112.00){
                valor = 0;
                document.getElementById('q62_aliquotairrf').value = 0;
            }
            if ((valorNota >= 2112.01) && (valorNota <= 2826.65)){
                valor = valorNota * 0.075;
                document.getElementById('q62_aliquotairrf').value = 0.075;
            }
            if ((valorNota >= 2826.66) && (valorNota <= 3751.05)){
                valor = valorNota * 0.15;
                document.getElementById('q62_aliquotairrf').value = 0.15;
            }
            if ((valorNota >= 3751.06) && (valorNota <= 4664.68)){
                valor = valorNota * 0.2250;
                document.getElementById('q62_aliquotairrf').value = 0.2250;
            }
            if (valorNota > 4664.68){
                valor = valorNota * 0.2750;
                document.getElementById('q62_aliquotairrf').value = 0.2750;
            }

        return valor;
    }

    function baseCalculoOutros(valorNota, info){
        var retencaoExistente = info.retencaoExistente;
        var outrasRetencoes = parseFloat(info.retencoesAntigas.value) || 0;
        var inssAtual = valorNota * 0.11;
        var resultado = 0

        if(isNaN(info.valorTotal)){
            info.valorTotal = 0;
        }

        if(isNaN(info.baseCalculoProcessado)){
            info.baseCalculoProcessado = 0;
        }

        document.getElementById("q62_baseirrf").value = valorNota;
        document.getElementById("q62_baseinss").value = inssAtual;

        var valorNotaCompleta = (valorNota + info.valorTotal);
        var inss = inssAtual + info.somaInss;

        if(inss > info.limiteINSS){
            var diferenca = inss - info.limiteINSS;
            inssAtual -= diferenca;
        }

        if(inss > info.limiteINSS){
            inss = info.limiteINSS;
        }

        if(inssAtual > info.limiteINSS){
            inssAtual = info.limiteINSS;
        }

        var calculo = valorNotaCompleta - inss;
        var fixo = baseCalculoFixo(calculo);
        var percentual = baseCalculoPercentual(calculo);

        resultado = percentual - fixo;
        resultado -= info.baseCalculoProcessado;
        resultado = resultado.toFixed(2);

        if(isNaN(resultado)){
            resultado = 0;
        }

        if(outrasRetencoes > 0){
            inssAtual = inssAtual - outrasRetencoes;
            if(inssAtual < 0){
                inssAtual = 0;
            }
        }

        //evitar numero negativo
        if(resultado < 0){
            resultado = 0;
        }

        document.getElementById("q62_vlrirrf").value = resultado;
        return inssAtual;
    }

    function baseCalculoTransportePassageiro(valorNota, info){
        //valores base para calculo
       var baseInssAtual = valorNota * 0.20; //base para calculo inss
       var baseIrrfAtual = valorNota * 0.60; //base para calculo irrf
       var inssAtual = baseInssAtual * 0.11; //calculo do inss

       document.getElementById("q62_baseirrf").value = baseIrrfAtual;
       document.getElementById("q62_baseinss").value = baseInssAtual;

       if(inssAtual > info.limiteINSS){
            inssAtual = info.limiteINSS;
       }

       if(isNaN(info.InssCargaPassageiro)){
            info.InssCargaPassageiro = 0;
       }

       if(isNaN(info.baseIrrfPassageiro)){
            info.baseIrrfPassageiro = 0;
       }

       if(isNaN(info.somaRetido)){
            info.somaRetido = 0;
       }

       //valores referente as outras notas emitidas
       var somaInssTotal = info.InssCargaPassageiro + inssAtual;
       var baseIrrfOutras = info.baseIrrfPassageiro;
       if(somaInssTotal > info.limiteINSS){
            somaInssTotal = info.limiteINSS;
       }

       //formata os valores
       baseIrrfAtual += baseIrrfOutras;
       baseIrrfAtual -= somaInssTotal;

       //realiza o calculo
       var fixo = baseCalculoFixo(baseIrrfAtual);
       var percentual = baseCalculoPercentual(baseIrrfAtual);

       var calculo = parseFloat(percentual - fixo) - info.somaRetido;
       calculo = (Math.round(calculo * 100) / 100).toFixed(2);

       //seta o valor do calculo no input
       document.getElementById("q62_vlrirrf").value = calculo;

       return inssAtual;
    }

    function baseCalculoTransporteCarga(valorNota, info){
       //valores base para calculo
       var baseInssAtual = valorNota * 0.20; //base para calculo inss
       var baseIrrfAtual = valorNota * 0.10; //base para calculo irrf
       var inssAtual = baseInssAtual * 0.11; //calculo do inss

       document.getElementById("q62_baseirrf").value = baseIrrfAtual;
       document.getElementById("q62_baseinss").value = inssAtual;

       if(inssAtual > info.limiteINSS){
            inssAtual = info.limiteINSS;
       }

       if(isNaN(info.InssCargaPassageiro)){
            info.InssCargaPassageiro = 0;
       }

       if(isNaN(info.baseIrrf)){
            info.baseIrrf = 0;
       }

       //valores referente as outras notas emitidas
       var somaInssTotal = info.InssCargaPassageiro + inssAtual;
       var baseIrrfOutras = info.baseIrrf;
       if(somaInssTotal > info.limiteINSS){
            somaInssTotal = info.limiteINSS;
       }

       //formata os valores
       baseIrrfAtual += baseIrrfOutras;
       baseIrrfAtual -= somaInssTotal;

       //realiza o calculo
       var fixo = baseCalculoFixo(baseIrrfAtual);
       var percentual = baseCalculoPercentual(baseIrrfAtual);
       var calculo = parseFloat(percentual - fixo);

       var resultado = info.baseIrrf - calculo;
       calculo = (Math.round(resultado * 100) / 100).toFixed(2);

       //seta o valor do calculo no input
       document.getElementById("q62_vlrirrf").value = calculo;
       
       return inssAtual;
    }

//inicializar com valores pre-definidos
document.getElementById("q62_deducaoinss").value = 0;
document.getElementById("q62_qtddepend").value = 0;

</script>