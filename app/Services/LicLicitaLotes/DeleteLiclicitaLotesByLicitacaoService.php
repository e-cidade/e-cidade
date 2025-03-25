<?php
namespace App\Services\LicLicitaLotes;

use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteLiclicitaLotesByLicitacaoService{
    private LicLicitaLotesRepository $liclicitalotesRepository;
    private LiclicitemLoteRepository $licilicitemloteRepository;


    public function __construct()
    {
        $this->liclicitalotesRepository = new LicLicitaLotesRepository();
        $this->licilicitemloteRepository = new LiclicitemLoteRepository();
    }


    public function execute(object $data){
        DB::beginTransaction();
        try{
            $lotes = $this->liclicitalotesRepository->getLotes($data->l20_codigo);
            if(!empty($lotes)){
                foreach($lotes as $lote){
                    $oItens = $this->licilicitemloteRepository->getItemByLote($lote['l24_codigo']);
                    if(!empty($oItens)){
                        continue;
                    }
                    
                    $this->liclicitalotesRepository->delete($lote);
                }
            }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Lote removido com sucesso!',
                'data' => [
                    'is_reload' => !empty($oItensLote)
                ]
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
