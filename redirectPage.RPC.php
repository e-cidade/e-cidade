<?php

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");

db_postmemory($_POST);

$oJson             = new services_json();
$oParametro        = json_decode(str_replace('\\', '', $_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->erro  = '';

try {

  switch ($oParametro->exec) {

    case "getIdItemMenu":

        $rsItemMenu = db_query("select * from db_itensmenu where funcao = '{$oParametro->namefile}'");
        if(pg_numrows($rsItemMenu) == 0){
          throw new Exception("Erro: Nome do arquivo informado para realizar o redirecionamento não foi encontrado.");
        }
        $oRetorno->idItemMenu = db_utils::fieldsMemory($rsItemMenu, 0)->id_item;
        break;
  }

} catch (Exception $e) {
  $oRetorno->erro   = $e->getMessage();
  $oRetorno->status = 2;
}

echo $oJson->encode(DBString::utf8_encode_all($oRetorno));
