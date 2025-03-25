<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/JSON.php");
$oJson    = new services_json();
$oRetorno = new stdClass();
$oDaoTipocompra = new cl_pctipocompra();
$oParam   = json_decode(str_replace('\\', '', $_POST["json"]));

$oRetorno->status   = 1;
$oRetorno->message  = '';
$oRetorno->itens    = array();
if (isset($oParam->observacao)) {
    $sObservacao = utf8_decode($oParam->observacao);
}


switch ($oParam->exec) {
    case "validateReportData":

        $clcontratacoesdiretas = new cl_contratacoesdiretas();
        $searchData = [
            "departamento" => $oParam->departamento,
            "categoria" => $oParam->categoria,
            "data_inicial" => $oParam->data_inicial,
            "data_final" => $oParam->data_final,
        ];

        $bRetorno = $clcontratacoesdiretas->getReportDataValidate($searchData);

        if ($bRetorno) {
            $oRetorno->status = 1;
        } else {
            $oRetorno->status = 2;
        }

        break;
}

echo $oJson->encode(DBString::utf8_encode_all($oRetorno));
