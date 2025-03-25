<?php
namespace App\Services\LicLicitaLotes;

use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteLicLicitaLotesService{
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
            $oLote = $this->liclicitalotesRepository->getLoteByCodigo($data->lote);
            $oItensLote = $this->licilicitemloteRepository->getItemByDescricao($oLote->l24_codigo);
            if(!empty($oItensLote)){
                foreach($oItensLote as $value){
                    $oItemLote = $this->licilicitemloteRepository->getItemByCodigo($value['l04_codigo']);
                    $this->licilicitemloteRepository->deleteItem($oItemLote);
                }
            }

            $this->liclicitalotesRepository->delete($oLote);
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
