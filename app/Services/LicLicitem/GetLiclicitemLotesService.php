<?php
namespace App\Services\LicLicitem;

use App\Repositories\Patrimonial\Compras\PcprocRepository;
use App\Services\Patrimonial\Licitacao\LiclicitemLoteService;
use App\Services\Patrimonial\Licitacao\LiclicitemService;
use Illuminate\Database\Capsule\Manager as DB;

class GetLiclicitemLotesService{
    private PcprocRepository $pcprocRepository;
    private LiclicitemService $liclicitemService;
    private LiclicitemLoteService $liclicitemLoteService;

    public function __construct()
    {
        $this->liclicitemService = new LiclicitemService();
    }

    public function execute(object $data){
        if(empty($data->l20_codigo)){
            return [
                'status' => 200,
                'message' => 'Nenhum item foi encontrado!',
                'total' => 0,
                'data' => []
            ];
        }

        $oData = $this->liclicitemService->getItensLotes(
            $data->l20_codigo,
            $data->limit ?? 15,
            $data->offset ?? 0,
            $data->isPaginate ?? false
        );

        return [
            'status' => 200,
            'message' => 'Itens carregados com sucesso!',
            'total' => !empty($oData['total']) ? $oData['total'] : 0,
            'data' => !empty($oData['data']) ? $oData['data'] : []
        ];
    }
}
