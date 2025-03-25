<?php

use App\Repositories\Factory\RepositoryFactory;

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once("classes/db_db_usuarios_classe.php");

$cldbusuarios = new cl_db_usuarios();
$oJson    = new Services_JSON();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));

if(empty($oParam)){
    $oParam = (object) $_POST['json'];
}

$oRetorno = new stdClass();

$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->anousu = db_getsession('DB_anousu');
$oRetorno->instit = db_getsession('DB_instit');
$oRetorno->coddepto = db_getsession('DB_coddepto');

$oParam->anousu = db_getsession('DB_anousu');
$oParam->instit = db_getsession('DB_instit');
$oParam->id_usuario = db_getsession('DB_id_usuario');
$oParam->datausu = date('Y-m-d', db_getsession('DB_datausu'));
$oParam->coddepto = db_getsession('DB_coddepto');
$oParam->is_admin = $cldbusuarios->vefica_adm_user(db_getsession('DB_id_usuario')) == "1";
$oParam->is_contass = !empty($cldbusuarios->vefica_user_contas(db_getsession('DB_id_usuario')));

$oParam = db_utils::convertToUtf8($oParam, 'UTF-8', 'ISO-8859-1');

$oParam->files = $_FILES ?: [];

try{
    $repository = RepositoryFactory::create('Patrimonial\\Pareceres\\', $oParam->exec);
    $data = $repository->handle($oParam);
    foreach($data as $key => $value){
        $oRetorno->$key = db_utils::convertToUtf8($value);
    }
} catch(\Throwable $oErro){
    $oRetorno->message = db_utils::convertToUtf8($oErro->getMessage());
    $oRetorno->status = 2;
}

echo $oJson->encode($oRetorno);