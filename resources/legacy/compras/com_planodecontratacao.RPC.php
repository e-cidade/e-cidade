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

$oJson    = new Services_JSON();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();

$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->anousu = db_getsession('DB_anousu');
$oRetorno->instit = db_getsession('DB_instit');

$oParam->anousu = db_getsession('DB_anousu');
$oParam->instit = db_getsession('DB_instit');
$oParam->id_usuario = db_getsession('DB_id_usuario');
$oParam->datausu = date('Y-m-d', db_getsession('DB_datausu'));
try{
    $repository = RepositoryFactory::create('Patrimonial\\PlanoContratacao\\', $oParam->exec);
    $data = $repository->handle($oParam);
    foreach($data as $key => $value){
        $oRetorno->$key = convertToUtf8($value);
    }
} catch(\Throwable $oErro){
    echo '<pre>';
    print_r($oErro->getMessage());
    exit;

    $oRetorno->message = urlencode($oErro->getMessage());
    $oRetorno->status = 2;
}

echo $oJson->encode($oRetorno);

function convertToUtf8($data) {
    if (is_array($data)) {
        return array_map('convertToUtf8', $data);
    } elseif (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->$key = convertToUtf8($value);
        }
        return $data;
    } elseif (is_string($data)) {
        return mb_convert_encoding($data, 'UTF-8', 'ISO-8859-1');
    }
    return $data; // Caso o dado não seja um array, objeto ou string
}
