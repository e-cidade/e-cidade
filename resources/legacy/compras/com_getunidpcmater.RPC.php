<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");

db_postmemory($_POST);

$oJson = new services_json();
$oParam = json_decode(str_replace('\\', '', $_POST['json']));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->erro = '';

try {

    $rsUnidade = db_query("select pc01_unid from pcmater WHERE pc01_codmater = {$oParam->pc01_codmater}");
    $oRetorno->pc01_unid = db_utils::fieldsMemory($rsUnidade, 0)->pc01_unid;

} catch (Exception $e) {
    $oRetorno->erro = urlencode($e->getMessage());
    $oRetorno->status = 2;
}

echo $oJson->encode(DBString::utf8_encode_all($oRetorno));
