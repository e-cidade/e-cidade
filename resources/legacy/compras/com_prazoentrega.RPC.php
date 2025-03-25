<?php

use App\Repositories\Patrimonial\Compras\PrazoEntregaRepository;
use App\Services\Patrimonial\compras\PrazoEntregaService;
use Illuminate\Support\Facades\DB;


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

        case 'salvarPrazoEntrega':
            $service = new PrazoEntregaService();

                DB::beginTransaction();

                foreach ($oParam->aItens as $item) {

                    $dadosPrazoEntrega = new stdClass();
                    $dadosPrazoEntrega->pc97_descricao = $item->descricao;
                    $dadosPrazoEntrega->pc97_ativo = $item->ativo;

                    $prazoEntrega = $service->salvarPrazoEntrega($dadosPrazoEntrega);


                    if (empty($prazoEntrega)) {
                        throw new Exception('Não foi possível salvar o prazo de entrega.');
                    }
                }

                DB::commit();


            $oRetorno->status = 1;
            $oRetorno->message = 'Salvo com sucesso!';
            break;

        case 'getPrazos':
            $prazoEntregaRepository = new PrazoEntregaRepository();
            $oRetorno->prazo = $prazoEntregaRepository->getprazosfornecedores($oParam->sequencial);



            foreach ($oRetorno->prazo as $prazo) {
                $itemPrazo = new stdClass();
                $itemPrazo->pc97_descricao = utf8_encode($prazo->pc97_descricao);
                $itemPrazo->pc97_ativo = $prazo->pc97_ativo;
                $itensPrazo[] = $itemPrazo;
            }
                $oRetorno->prazo = $itensPrazo;
                break;

        case 'alteraPrazo':

            $service = new PrazoEntregaService();

                DB::beginTransaction();

                foreach ($oParam->aItens as $item) {
                    $prazoAtualizado = $service->alterarPrazoEntrega($item);

                     if (empty($prazoAtualizado)) {
                         throw new Exception('Não foi possível Alterar o prazo de entrega.');
                     }
                }

                DB::commit();


            $oRetorno->status = 1;
            $oRetorno->message = 'Alterado com sucesso!';
            break;

            case 'excluirPrazo':
                $service = new PrazoEntregaService();

                try {
                    // Tenta excluir o prazo
                    $serviceexclusaoPrazo = $service->deletaPrazo($oParam->pc97_sequencial);
                    DB::commit();
                    $oRetorno->status = 1;
                    $oRetorno->message = 'Excluído com sucesso!';
                } catch (Exception $e) {
                    $oRetorno->status = 0;
                    $oRetorno->message = utf8_encode($e->getMessage());
                }
                break;





    }
}catch(Exception $e){
    $oRetorno->message = urlencode($e);
    $oRetorno->status = 2;
}
echo $oJson->encode($oRetorno);
