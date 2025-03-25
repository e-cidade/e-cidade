<?php

require_once 'libs/db_stdlib.php';
require_once 'libs/JSON.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';

db_postmemory($_POST);

$oJson = new services_json();
$oParam = json_decode(str_replace('\\', '', $_POST['json']));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->erro = '';

try {
    switch ($oParam->sExecuta) {
        case 'getItensLancados':

            $rsItens = db_query("select * from veicmanutitem inner join veicmanut on veicmanut.ve62_codigo = veicmanutitem.ve63_veicmanut inner join veiccadtiposervico on veiccadtiposervico.ve28_codigo = veicmanut.ve62_veiccadtiposervico left join veicmanutitempcmater on ve64_veicmanutitem = ve63_codigo left join pcmater on ve64_pcmater = pc01_codmater where ve63_veicmanut = {$oParam->codigoManutencao}");
            $oRetorno->dados =  db_utils::getColectionByRecord($rsItens);

            break;
    }
} catch (Exception $e) {
    $oRetorno->erro = urlencode($e->getMessage());
    $oRetorno->status = 2;
}

echo $oJson->encode(DBString::utf8_encode_all($oRetorno));
