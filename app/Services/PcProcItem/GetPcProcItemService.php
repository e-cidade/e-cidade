<?php
namespace App\Services\PcProcItem;

use App\Repositories\Patrimonial\Compras\PcprocitemRepository;

class GetPcProcItemService {
    private PcprocitemRepository $pcprocitemRepository;

    public function __construct()
    {
        $this->pcprocitemRepository = new pcprocitemRepository();
    }

    public function execute(object $data){
        if(empty($data->codproc) && empty($data->l20_codigo)){
            return [
                'status' => 400,
                'message' => 'Nenhum item foi encontrado!',
                'total' => 0,
                'data' => []
            ];
        }

        $oData = $this->pcprocitemRepository->getItensLicLicitemAndLicitacao(
            $data->codproc,
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
