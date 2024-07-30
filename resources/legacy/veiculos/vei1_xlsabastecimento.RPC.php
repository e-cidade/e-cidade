<?php

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\ImportacaoAbastecimentoService;

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
include("libs/PHPExcel/Classes/PHPExcel.php");
require_once("classes/db_veicretirada_classe.php");
require_once("classes/db_veicdevolucao_classe.php");
require_once("classes/db_veiculos_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_veicmotoristas_classe.php");
require_once("classes/db_veicabast_classe.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_empveiculos_classe.php");
require_once("classes/db_empresto_classe.php");
require_once("classes/db_veiccadposto_classe.php");
require_once("classes/db_veicabastposto_classe.php");
require_once("classes/db_veiccadpostoexterno_classe.php");
require_once("classes/db_veicabastretirada_classe.php");
require_once("classes/db_veicbaixa_classe.php");
require_once("classes/db_veiccentral_classe.php");
require_once("classes/db_veiccadcentral_classe.php");
require_once("classes/db_veiccadcomb_classe.php");
require_once("classes/db_veicparam_classe.php");
require_once("classes/db_veicmanutencaomedida_classe.php");


$oJson             = new services_json();
//$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oParam           = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oErro             = new stdClass();
$oRetorno          = new stdClass();
$oRetorno->status  = 1;

$clveicretirada    = new cl_veicretirada();
$clveiculos        = new cl_veiculos();
$clcgm             = new cl_cgm();
$clveicmotoristas  = new cl_veicmotoristas();
$clveicdevolucao   = new cl_veicdevolucao();
$clveicabast       = new cl_veicabast();
$clempempenho      = new cl_empempenho();
$clempempenho1     = new cl_empempenho();
$clempveiculos     = new cl_empveiculos();
$clempresto        = new cl_empresto();
$clveiccadposto    = new cl_veiccadposto();
$clveicabastposto  = new cl_veicabastposto();
$clveiccadpostoexterno  = new cl_veiccadpostoexterno();
$clveicabastretirada    = new cl_veicabastretirada();
$clveicbaixa            = new cl_veicbaixa();
$clveiccentral          = new cl_veiccentral();
$clveiccadcentral       = new cl_veiccadcentral();
$clveiccadcomb          = new cl_veiccadcomb();
$clveicparam             = new cl_veicparam();
$clveicmanutencaomedida  = new cl_veicmanutencaomedida;



/**
 * matriz de entrada
 */
$what = array(',', '-', '/', chr(13), chr(10), "'");

/**
 * matriz de saida
 */
$by   = array('', '', '');

$resultadoPlanilha = $oParam->valor;
$resultadoEmpenho = $oParam->itensEmpenho;
$dataI = $oParam->dataI;
$dataF = $oParam->dataF;

switch ($oParam->exec) {

    case 'importarv2':
        $service = new ImportacaoAbastecimentoService();
        $result = $service->import($oParam->abastecimentos);
        $oRetorno->status = 1;

        if ($result->status === false) {
        $oRetorno->status = 2;
        $oRetorno->message =  utf8_encode($result->message);
        $oRetorno->errors = $result->errors;
        }

        break;
}
echo $oJson->encode($oRetorno);
