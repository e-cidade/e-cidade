<?php
namespace App\Services\LicLicitem;

use App\Repositories\Patrimonial\Compras\PcprocRepository;
use App\Services\LicLicitaLotes\DeleteLiclicitaLotesByLicitacaoService;
use App\Services\Patrimonial\Licitacao\LiclicitemLoteService;
use App\Services\Patrimonial\Licitacao\LiclicitemService;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteLiclicitemService{
    private PcprocRepository $pcprocRepository;
    private LiclicitemService $liclicitemService;
    private LiclicitemLoteService $liclicitemLoteService;
    private DeleteLiclicitaLotesByLicitacaoService $deleteLiclicitaLotesByLicitacaoService;

    public function __construct()
    {
        $this->pcprocRepository = new pcprocRepository();
        $this->liclicitemService = new LiclicitemService();
        $this->liclicitemLoteService = new LiclicitemLoteService();
        $this->deleteLiclicitaLotesByLicitacaoService = new DeleteLiclicitaLotesByLicitacaoService();
    }

    public function execute(object $data){
        DB::beginTransaction();
        try{
            $oProcessosVinculados = $this->pcprocRepository->getProcessodeComprasVinculados($data->l20_codigo, $data->pc80_codprocexcluir);

            if(empty($oProcessosVinculados)){
                throw new \Exception("Nenhum processo foi encontrado", 1);
            }

            foreach($oProcessosVinculados as $value){
                $this->liclicitemLoteService->excluirItensLote((object)[
                    'processo' => $value->pc81_codproc,
                    'l20_codigo' => $data->l20_codigo
                ]);

                $this->liclicitemService->excluirItensLicitacao((object)[
                    'processo' => $value->pc81_codproc,
                    'l20_codigo' => $data->l20_codigo
                ]);
            }

            DB::commit();

            $this->deleteLiclicitaLotesByLicitacaoService->execute((object)[
                'l20_codigo' => $data->l20_codigo
            ]);
            
            return [
                'status' => 200,
                'message' => 'Processo removidos com sucesso!',
                'data' => []
            ];
        } catch(\Exception $e){
            DB::rollBack();
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
