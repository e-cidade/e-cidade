<?php

use App\Repositories\Factory\RepositoryFactory;

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once("classes/db_db_usuarios_classe.php");

$cldbusuarios = new cl_db_usuarios();
$oJson    = new Services_JSON();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));
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

$oParam = db_utils::convertToUtf8($oParam, 'UTF-8', 'ISO-8859-1');

try{
    $repository = RepositoryFactory::create('Configuracao\\Protocolo\\', $oParam->exec);
    $data = $repository->handle($oParam);
    foreach($data as $key => $value){
        $oRetorno->$key = db_utils::convertToUtf8($value);
    }
} catch(\Throwable $e){
    $oRetorno->message = urlencode($e->getMessage());
    $oRetorno->status = 2;
}

echo $oJson->encode($oRetorno);

function convertToUf8Decode($data){
    if (is_array($data)) {
        return array_map(function($item) {
            return convertToUf8Decode($item);
        }, $data);
    } elseif (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->$key = convertToUf8Decode($value);
        }
        return $data;
    } elseif (is_string($data)) {
        return utf8_decode($data);
    }

    return $data;
}
