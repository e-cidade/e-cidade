<?php

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
        case 'getItensProcessodeCompras':

            $pcprocRepository = new pcprocitemrepository();
            $rsItens = $pcprocRepository->getItens($oParam->pc80_codproc);
            $totalgeral = 0;

            foreach ($rsItens as $rsItem) {
                $oItem = new stdClass();
                $totalgeral += $rsItem->si02_vltotalprecoreferencia;
                $oItem->pc11_seq         = $rsItem->pc11_seq;
                $oItem->pc01_codmater    = $rsItem->pc01_codmater;
                $oItem->pc01_descrmater  = utf8_encode($rsItem->pc01_descrmater);
                $oItem->pc01_complmater  = utf8_encode($rsItem->pc01_complmater);
                $oItem->vlrunitario      = $rsItem->si02_vlprecoreferencia == null ? '' : $rsItem->si02_vlprecoreferencia;
                $oItem->total            = $rsItem->si02_vltotalprecoreferencia == null ? '' : $rsItem->si02_vltotalprecoreferencia;
                $oItem->pc11_quant       = $rsItem->pc11_quant;
                $oItem->pc81_codprocitem = $rsItem->pc81_codprocitem;
                $oItem->pc11_reservado   = $rsItem->pc11_reservado;
                $oRetorno->itens[] = $oItem;
            }
            $oRetorno->totalgeral  = db_formatar($totalgeral,'f');
            break;
        case 'getItensCota':
            $pcprocRepository = new pcprocitemrepository();
            $rsItens = $pcprocRepository->getItensCota($oParam->pc80_codproc);
            foreach ($rsItens as $rsIten) {
                $oItemCota = new stdClass();
                $oItemCota->pc11_seq                     = $rsIten->pc11_seq;
                $oItemCota->pc01_codmater                = $rsIten->pc01_codmater;
                $oItemCota->pc01_descrmater              = utf8_encode($rsIten->pc01_descrmater);
                $oItemCota->pc11_quant                   = $rsIten->pc11_quant;
                $oItemCota->vlrunitario                  = $rsIten->si02_vlprecoreferencia == null ? '' : $rsIten->si02_vlprecoreferencia;
                $oItemCota->total                        = $rsIten->si02_vltotalprecoreferencia == null ? '' : $rsIten->si02_vltotalprecoreferencia;
                $oItemCota->pc81_codprocitem             = $rsIten->pc81_codprocitem;
                $oItemCota->pc11_reservado               = $rsIten->pc11_reservado;
                $oItemCota->pc11_exclusivo               = $rsIten->pc11_exclusivo;
                $oItemCota->bloquear                     = $pcprocRepository->verificaCota($rsIten->pc01_codmater,$rsIten->pc11_numero);
                $oRetorno->itens[] = $oItemCota;
            }

            break;
        case 'salvarItensCota':

            $servicecota = new PcitenscotaService();
            $pcprocRepository = new PcprocRepository();
            $origem = $servicecota->getOriginPcproc($oParam->pc80_codproc);
            $oLicitacao = $pcprocRepository->getLicitacaoPcproc($oParam->pc80_codproc);
            if($oLicitacao){
                throw new Exception("Já existe licitação vinculada a este processo de compras.");
            }else{
                if ($origem == 1){
                    DB::beginTransaction();
                    $rsSalvarItemCotaNormal = $servicecota->processarItensCotaNormal($oParam->aItens);
                    if (!$rsSalvarItemCotaNormal) {
                        throw new Exception("Não foi possível salvar Item Cota");
                    }
                    DB::commit();
                }else{
                    //REGISTRO DE PREÇO
                    $rsSalvarItensCotaRP = $servicecota->processarItensCotaRegistrodePreco($oParam->aItens,$oParam->pc80_codproc);
                    if (!$rsSalvarItensCotaRP) {
                        throw new Exception("Não foi possível salvar Item Cota Registro de Preco");
                    }
                }
            }
            break;
        case 'excluirItemCota':

            $servicecotaexclusao = new PcitenscotaService();
            $pcprocRepository = new PcprocRepository();
            $origem = $servicecotaexclusao->getOriginPcproc($oParam->pc80_codproc);
            DB::beginTransaction();

            $oLicitacao = $pcprocRepository->getLicitacaoPcproc($oParam->pc80_codproc);
            if($oLicitacao){
                throw new Exception("Já existe licitação vinculada a este processo de compras.");
            }else{
                if ($origem == 1){
                    $rsExcluirItemCota = $servicecotaexclusao->processarExclusaoItensCotaNormal($oParam->aItens,$oParam->pc80_codproc);
                    if (!$rsExcluirItemCota) {
                        throw new Exception("Não foi possível Excluir Item Cota.");
                    }
                }else{
                    //REGISTRO DE PREÇO
                    $rsExcluirItemCota = $servicecotaexclusao->processarExclusaoItensRegistroPreco($oParam->aItens,$oParam->pc80_codproc);
                    if (!$rsExcluirItemCota) {
                        throw new Exception("Não foi possível Excluir Item Cota Registro de preco.");
                    }
                }
            }
            DB::commit();
            break;
    }
}catch(Exception $e){
    $oRetorno->message = urlencode($e->getMessage());
    $oRetorno->status = 2;
}
echo $oJson->encode($oRetorno);

