<?php
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/DBTime.php");
require_once("std/DBDate.php");

include("classes/db_veiculos_classe.php");
include("classes/db_condataconf_classe.php");
include("classes/db_veicreativarf_classe.php");

db_app::import("configuracao.DBDepartamento");

// Busca ve�culo
function reativarVeiculo($dados)
{
  $clveiculos = new cl_veiculos;

  if (!isset($dados->ve82_veiculo) || empty($dados->ve82_veiculo)) {
    return buildResponse(2, "� necess�rio informar o c�digo do veiculo.");
  }

  if (!isset($dados->ve82_datareativacao) || empty($dados->ve82_datareativacao)) {
    return buildResponse(2, "� necess�rio informar a data da altera��o.");
  }

  if (!isset($dados->ve82_obs) || empty($dados->ve82_obs)) {
    return buildResponse(2, "� necess�rio informar a observa��o.");
  }

  $sql = $clveiculos->sql_query($dados->ve82_veiculo, "ve01_codigo");
  $result = $clveiculos->sql_record($sql);
  if (!$result) {
    return buildResponse(2, "Ocorreu um erro ao buscar o veculo $dados->ve82_veiculo .");
  }

  $veiculo = db_utils::getCollectionByRecord($result)[0];

  $dataReativacao = convertToDate($dados->ve82_datareativacao);

  // Verifica a data de encerramento do per�odo patrimonial
  $clcondataconf = new cl_condataconf;
  $sqlConf = $clcondataconf->sql_query_file(db_getsession("DB_anousu"), db_getsession("DB_instit"));
  $result = $clcondataconf->sql_record($sqlConf);

  if ($result != false) {
    $config = db_utils::getCollectionByRecord($result)[0];

    $dataEncerramentoPatrimonial = convertToDate($config->c99_datapat);
    if ($dataReativacao <= $dataEncerramentoPatrimonial) {
      return buildResponse(2, "O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.");
    }
  }

  // Inicia transa��o de reativa��o do ve�culo
  db_inicio_transacao();
  
  try {
    
    // Reativa ve�culo
    $clveiculos = new cl_veiculos;

    $clveiculos->ve01_codigo = $veiculo->ve01_codigo;
    $clveiculos->ve01_ativo = 1;
    
    if (!$clveiculos->alterar($veiculo->ve01_codigo)) {
      throw new Exception($clveiculos->erro_msg);
    }

    // Insere registro de reativa��o
    $clveicreativar = new cl_veicreativar();

    $clveicreativar->ve82_veiculo = $veiculo->ve01_codigo;
    $clveicreativar->ve82_datareativacao = $dados->ve82_datareativacao;
    $clveicreativar->ve82_obs = mb_convert_encoding($dados->ve82_obs, 'ISO-8859-1', 'UTF-8');
    $clveicreativar->ve82_usuario = db_getsession("DB_id_usuario");;

    
    if(!$clveicreativar->incluir()) {
      throw new Exception($clveicreativar->erro_msg);
    }

    db_fim_transacao();

    return buildResponse(1, "Ve�culo reativado com sucesso!", []);

  } catch (Exception $erro) {
    db_fim_transacao(true);
    return buildResponse(2, $erro->getMessage());
  }
}


// Fun��o respons�vel por preparar a resposta da requisi��o
function buildResponse($status, $message, $data = [])
{
  $oJson    = new services_json();
  $oRetorno = new stdClass();

  $oRetorno->status = $status;
  $oRetorno->message = mb_convert_encoding($message, 'UTF-8', 'ISO-8859-1');

  foreach ($data as $key => $value) {
    $oRetorno->$key = $value;
  }
  
  return $oJson->encode($oRetorno);
}

// Converter a data para datetime
function convertToDate($dateString)
{
  if (strpos($dateString, '/') !== false) {
    return DateTime::createFromFormat('d/m/Y', $dateString);
  } else if (strpos($dateString, '-') !== false) {
    return DateTime::createFromFormat('Y-m-d', $dateString);
  } else {
    return false;
  }
}

// Executa as fun��es dispon�veis via Ajax
function executar($oParam)
{
  switch ($oParam->exec) {
    case 'reativarVeiculo':
      return reativarVeiculo($oParam->dados);
      break;
    default:
      return buildResponse(2, "Opera��o inv�lida para op��o ($oParam->exec.)");
      break;
  }
}

// Recebe os parametros e executa a opera��o
$oParam = (new services_json())->decode(str_replace("\\", "", $_POST["json"]));
echo executar($oParam);