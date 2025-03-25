<?php

use App\Repositories\Patrimonial\Compras\ItemprecoreferenciaRepository;
use App\Repositories\Patrimonial\Compras\PrecoreferenciaRepository;
use App\Repositories\Patrimonial\Compras\PcprocitemRepository;
use App\Repositories\Patrimonial\Compras\PcprocRepository;
use App\Services\Patrimonial\compras\PcitenscotaService;
use Illuminate\Support\Facades\DB;

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
        case 'excluirPrecoReferencia':

            $servicecotaexclusao = new PcitenscotaService();
            $origem = $servicecotaexclusao->getOriginPcproc($oParam->si01_processocompra);

            $itemprecoreferenciaRepository = new ItemprecoreferenciaRepository();
            $precoreferenciaRepository = new PrecoreferenciaRepository();
            $itenCota = $itemprecoreferenciaRepository->getItensCota($oParam->si01_sequencial);

            DB::beginTransaction();

            if($itenCota){
                if ($origem == 1){
                    $rsExcluirItemCota = $servicecotaexclusao->processarExclusaoItensCotaNormal($itenCota,$oParam->si01_processocompra);
                    if (!$rsExcluirItemCota) {
                        throw new Exception("Não foi possível Excluir Item Cota.");
                    }
                }else{
                    $rsExcluirItemCota = $servicecotaexclusao->processarExclusaoItensRegistroPreco($itenCota,$oParam->si01_processocompra);
                    if (!$rsExcluirItemCota) {
                        throw new Exception("Não foi possível Excluir Item Cota Registro de preco.");
                    }
                }
            }

            $executExcluirItemprecoreferencia = $itemprecoreferenciaRepository->excluirItemPrecoReferencia($oParam->si01_sequencial);

            if (!$executExcluirItemprecoreferencia) {
                throw new Exception("Nao foi possivel Excluir Item Preco Referencia");
            }

            $executExcluirPrecoreferencia = $precoreferenciaRepository->excluir($oParam->si01_sequencial);

            if (!$executExcluirItemprecoreferencia) {
                throw new Exception("Nao foi possivel Excluir Preco Referencia");
            }

            DB::commit();
            break;
    }
}catch(Exception $e){
    $oRetorno->message = urlencode($e->getMessage());
    $oRetorno->status = 2;
}
echo $oJson->encode($oRetorno);
