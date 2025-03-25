<?php
namespace App\Services\LicLicitaLotes;

use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteLiclicitaItemLotesService{
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
            foreach($data->itens as $value){
                $oItemLote = $this->licilicitemloteRepository->getItemByCodigo($value->l04_codigo);
                $this->licilicitemloteRepository->deleteItem($oItemLote);
            }

            $this->updateSeqItensLote($data->l20_codigo);
            DB::commit();
            return [
                'status' => 200,
                'message' => 'Itens removidos com sucesso!',
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

    private function updateSeqItensLote(int $l20_codigo){
        DB::beginTransaction();
        try{
            $oLotes = $this->liclicitalotesRepository->getLotesByLicLicita($l20_codigo);
            foreach($oLotes as $oLote){
                $itens = $this->licilicitemloteRepository->getItemByLote($oLote['l24_codigo']);
                if(empty($itens)){
                    continue;
                }

                $seq = 1;
                foreach($itens as $value){
                    $this->licilicitemloteRepository->updateSeq($value['l04_codigo'], $seq);
                    $seq++;
                }
            }
            DB::commit();
            return [
                'status' => 200,
                'message' => 'Itens removidos com sucesso!',
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
