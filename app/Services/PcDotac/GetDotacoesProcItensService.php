<?php
namespace App\Services\PcParam;

use App\Repositories\Patrimonial\Compras\PcDotacRepository;

class GetDotacoesProcItensService{
    private PcDotacRepository $pcdotacRepository;

    public function __construct()
    {
        $this->pcdotacRepository = new PcDotacRepository();
    }

    public function execute(object $data){
        $oData = $this->pcdotacRepository->getDotacoesProcItens(
            $data->l20_codigo,
            $data->anousu,
            $data->limit ?? 15,
            $data->offset ?? 0,
            $data->isPaginate ?? true
        );

        return [
            'status' => 200,
            'message' => 'Dotações carregados com sucesso!',
            'data' => [
                'total' => !empty($oData['total']) ? $oData['total'] : 0,
                'dotacao' => !empty($oData['data']) ? $oData['data'] : []
            ]
        ];
    }
}
