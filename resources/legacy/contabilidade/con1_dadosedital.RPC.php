<?php
global $oErro;
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

try {

    switch ($oParam->exec) {

        case 'getDadosItenseEditalLote':
                $sql = "SELECT 
                            2 AS tiporegistro,
                            l20_nroedital AS edital,
                            l20_exercicioedital AS exercicioedital,
                            l20_liclocal AS localentrega,
                            l20_localentrega AS localdeentrega,
                            l20_prazoentrega AS datadeentrega,
                            l20_numero,
                            l21_ordem,
                            pc01_descrmater,
                            0 AS garantia
                        FROM liclicita
                        JOIN liclicitem ON l21_codliclicita = l20_codigo
                        INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                        INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                        INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                        INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                        INNER JOIN pcmater ON pc01_codmater = pc16_codmater
                        WHERE l20_codigo = {$oParam->l20_codigo} AND pc11_quant != 0
                        ORDER BY l21_ordem";
            
                $resultRegistro2 = db_query($sql);

                if (pg_numrows($resultRegistro2)) {
                    $dados = pg_fetch_all($resultRegistro2);
                    $oRetorno->edital = $dados;
                } else {
                    $oRetorno->edital = [];
                }
                break;
    }
}catch(Exception $e){
    $oRetorno->message = urlencode($e);
    $oRetorno->status = 2;
}
echo $oJson->encode($oRetorno);
