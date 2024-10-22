<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once(modification("dbforms/db_funcoes.php"));
require_once("classes/db_orcdotacao_classe.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_orcsuplemval_classe.php");
require_once("classes/db_orcsuplementacaoparametro_classe.php");
require_once("classes/db_orcparametro_classe.php");

db_postmemory($_POST);

$oJson = new services_json();
$oParam = json_decode(str_replace('\\', '', $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->erro = '';
$anoSessao = $oParam->anoOrigem;
$institSessao = db_getsession("DB_instit");

$cl_orcdotacao = new cl_orcdotacao;
$cl_empempenho = new cl_empempenho;
$cl_orcsuplemval = new cl_orcsuplemval;
$cl_orcsuplementacaoparametro = new cl_orcsuplementacaoparametro;

$campos = "row_number() over(order by o58_orgao, o58_unidade, o58_funcao, o58_subfuncao, o58_programa, o58_projativ, o56_elemento, o58_codigo) as new_seq_dot, 
           o58_coddot as dot_antiga,
           o58_instit, o58_orgao, o58_unidade, o58_funcao, o58_subfuncao, o58_programa, o58_projativ, o56_elemento, o58_codigo, o58_codele, o58_valor, o58_localizadorgastos";
$orderBy = "o58_orgao, o58_unidade, o58_funcao, o58_subfuncao, o58_programa, o58_projativ, o56_elemento, o58_codigo";
$sqlOrcDot = $cl_orcdotacao->sql_query_ele($anoSessao, null, $campos, $orderBy);
$sqlOrcDot = str_replace("and db_config.codigo = {$institSessao}", '', $sqlOrcDot);
$rsOrcdot = $cl_orcdotacao->sql_record($sqlOrcDot);
$aDot = pg_fetch_all($rsOrcdot);

$rsDotEmp = $cl_empempenho->sql_record(
    $cl_empempenho->sql_query_buscaempenhos(
        null,
        "e60_coddot",
        null,
        "e60_anousu = " . $anoSessao)
);

$rsDotSuplem = $cl_orcsuplemval->sql_record(
    $cl_orcsuplemval->sql_query_file(
        null,
        $anoSessao,
        null,
        "o47_coddot")
);

if (pg_num_rows($rsOrcdot) == 0) {
    $sMessage = "Nenhuma dotação encontrada para o exercício!";
    $oRetorno->status = 2;
    $oRetorno->message = urlencode($sMessage);
    exit(json_encode($oRetorno));
}

if ((pg_num_rows($rsDotEmp) > 0) || (pg_num_rows($rsDotSuplem) > 0)) {

    $sMessage = "Dotação com movimentos! Procedimento abortado.";
    $oRetorno->status = 2;
    $oRetorno->message = urlencode($sMessage);
    exit(json_encode($oRetorno));
}

$sql = $cl_orcsuplementacaoparametro->sql_query_file($anoSessao);
$rsOrcSuplemParametro = $cl_orcsuplementacaoparametro->sql_record($sql);
$aOrcSuplemParametro = pg_fetch_assoc($rsOrcSuplemParametro);

if ($aOrcSuplemParametro['o134_orcamentoaprovado'] == 't') {
    $sMessage = "O procedimento não pode ser realizado, o orçamento já está aprovado!";
    $oRetorno->status = 2;
    $oRetorno->message = urlencode($sMessage);
    exit(json_encode($oRetorno));
}

db_inicio_transacao();
$error_trans = false;

$cl_orcdotacao->excluir($anoSessao);
if ($cl_orcdotacao->erro_status == 0) {

    $oRetorno->status = 2;
    $sMessage = "<br>Erro[1] - Exclusão Abortada.<br>";
    $sMessage .= str_replace("\\n", "", $cl_orcdotacao->erro_msg);
    $oRetorno->message = urlencode($sMessage);
    $error_trans = true;
    exit(json_encode($oRetorno));
}

for ($i = 0; $i < pg_num_rows($rsOrcdot); $i++) {

    $cl_orcdotacao->o58_anousu = $anoSessao;
    $cl_orcdotacao->o58_coddot = $aDot[$i]['new_seq_dot'];
    $cl_orcdotacao->o58_orgao = $aDot[$i]['o58_orgao'];
    $cl_orcdotacao->o58_unidade = $aDot[$i]['o58_unidade'];
    $cl_orcdotacao->o58_funcao = $aDot[$i]['o58_funcao'];
    $cl_orcdotacao->o58_subfuncao = $aDot[$i]['o58_subfuncao'];
    $cl_orcdotacao->o58_programa = $aDot[$i]['o58_programa'];
    $cl_orcdotacao->o58_projativ = $aDot[$i]['o58_projativ'];
    $cl_orcdotacao->o58_codele = $aDot[$i]['o58_codele'];
    $cl_orcdotacao->o58_codigo = $aDot[$i]['o58_codigo'];
    $cl_orcdotacao->o58_valor = $aDot[$i]['o58_valor'];
    $cl_orcdotacao->o58_instit = $aDot[$i]['o58_instit'];
    $cl_orcdotacao->o58_concarpeculiar = '000';
    $cl_orcdotacao->o58_localizadorgastos = $aDot[$i]['o58_localizadorgastos'];

    $cl_orcdotacao->incluir($cl_orcdotacao->o58_anousu, $cl_orcdotacao->o58_coddot);
    if ($cl_orcdotacao->erro_status == 0) {

        $oRetorno->status = 2;
        $error_trans = true;
        $sMessage = "<br>Erro[2] - Inclusão Abortada.<br>";
        $sMessage .= str_replace("\\n", "\n", $cl_orcdotacao->erro_msg);
        $oRetorno->message = urlencode($sMessage);
        exit(json_encode($oRetorno));
    }

}

$sqlOrcParamDot = $cl_orcdotacao->sql_query_file($anoSessao, null, "max(o58_coddot)");
$rsOrcdotParam = $cl_orcdotacao->sql_record($sqlOrcParamDot);
$aDotOrcParam = pg_fetch_all($rsOrcdotParam);

$cl_orcparametro = new cl_orcparametro;

$cl_orcparametro->o50_anousu = $anoSessao;
$cl_orcparametro->o50_coddot = $aDotOrcParam[0]['max'];
$cl_orcparametro->alterar($cl_orcparametro->o50_anousu);

db_fim_transacao($error_trans);

echo $oJson->encode($oRetorno);