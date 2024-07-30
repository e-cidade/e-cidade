<?php
global $oErro;

use App\Repositories\Patrimonial\Compras\PcprocRepository;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Services\Patrimonial\Licitacao\LiclicitemLoteService;
use App\Models\Patrimonial\Licitacao\Liclicita;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Services\Patrimonial\Licitacao\LiclicitemService;
use App\Services\Patrimonial\compras\PcprocService;
use App\Services\Patrimonial\Licitacao\ItensLicitacao;
use Illuminate\Database\Capsule\Manager as DB;

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

        case 'getProcessosdecompras';

            $pcprocRepository = new pcprocRepository();
            $rsPcproc = $pcprocRepository->getProcessodeComprasLicitacao();
            $oRetorno->pcproc = $rsPcproc;

        break;

        case 'getProcessosdecomprasVinculados';

            $pcprocRepository = new pcprocRepository();
            $rsPcproc = $pcprocRepository->getProcessodeComprasVinculados($oParam->l20_codigo);
            $oRetorno->pcproc = $rsPcproc;

        break;

        case 'getDadosProcessoCompras':

            $pcprocService = new PcprocService();
            $oRetorno = $pcprocService->getProcessData($oParam->codproc);

        break;

        case 'salvarProcesso':
            $liclicitaRepository = new LiclicitaRepository();
            $liclicitemService = new LiclicitemService();
            $liclicitemLoteService = new LiclicitemLoteService();
            $oLicitacao = $liclicitaRepository->getLicitacao($oParam->l20_codigo);

            foreach ($oParam->aItens as $item){

                try {
                    DB::beginTransaction();
                        $item->l20_codigo = $oParam->l20_codigo;
                        $oLiclicitem = $liclicitemService->salvarItensLicitacao($item);

                        if (empty($oLiclicitem->l21_codigo)) {
                            throw new Exception('Não foi possivel inserir item na tabela Liclicitem ');
                        }

                        if($oLicitacao->l20_tipojulg == 3){
                            $item->l04_liclicitem = $oLiclicitem->l21_codigo;
                            $oLiclicitemLote = $liclicitemLoteService->salvarItensLicitacaoLote($item);
                            if (empty($oLiclicitemLote->l04_codigo)) {
                                throw new Exception('Não foi possivel inserir item na tabela Liclicitemlote ');
                            }
                        }
                    DB::commit();
                    $oRetorno->status = 1;
                    $oRetorno->message = 'Itens Salvo com Sucesso';
                }catch (Exception $e) {
                    DB::rollBack();
                    $oRetorno->status = 2;
                    $oRetorno->message = 'Erro ao salvar itens: ' . $e->getMessage();
                }
            }
        break;

        case 'excluirProcesso':
            $liclicitemService = new LiclicitemService();
            $liclicitaRepository = new LiclicitaRepository();
            $liclicitemLoteService = new LiclicitemLoteService();
            $oLicitacao = $liclicitaRepository->getLicitacao($oParam->l20_codigo);
            DB::beginTransaction();
                $oRetornoExclusao = $liclicitemService->excluirItensLicitacao($oParam);
                if($oLicitacao->l20_tipojulg == 3){
                    $oRetornoExclusaoLotes = $liclicitemLoteService->excluirItensLote($oParam);
                }
            DB::commit();
            $oRetorno->status = 1;
            $oRetorno->message = 'Processo Excluido Salvo com Sucesso!';
        break;
    }
}catch(Exception $e){
    $oRetorno->message = urlencode($e->getMessage());
    $oRetorno->status = 2;
}
echo $oJson->encode($oRetorno);
