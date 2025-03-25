<?php
namespace App\Services\LicLicitem;

use App\Services\Patrimonial\Licitacao\LiclicitemLoteService;
use App\Services\Patrimonial\Licitacao\LiclicitemService;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteItensProcessoService{
    private LiclicitemService $liclicitemService;
    private LiclicitemLoteService $liclicitemLoteService;

    public function __construct()
    {
        $this->liclicitemService = new LiclicitemService();
        $this->liclicitemLoteService = new LiclicitemLoteService();
    }

    public function execute(object $data){
        if(empty($data->itens)){
            return [
                'status' => 500,
                'message' => 'Por favor, selecione pelo menos um item!',
                'data' => []
            ];
        }

        DB::beginTransaction();
        try{
            foreach($data->itens as $value){
                if(!empty($value->l04_codigo)){
                    $this->liclicitemLoteService->excluirItemLote($value->l04_codigo);
                }

                $this->liclicitemService->excluirItem($value->l21_codigo);
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
