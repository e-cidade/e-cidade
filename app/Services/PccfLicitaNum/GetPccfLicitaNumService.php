<?php

namespace App\Services\PccfLicitaNum;

use App\Repositories\Patrimonial\Licitacao\PccfLicitaNumRepository;

class GetPccfLicitaNumService{
    private PccfLicitaNumRepository $pccfLicitaNumRepository;

    public function __construct()
    {
        $this->pccfLicitaNumRepository = new PccfLicitaNumRepository();
    }

    public function execute(object $data){
        if(empty($data->l24_instit) || empty($data->l24_anousu)){
            return [
                'status' => 400,
                'message' => 'Por favor, informe o ano e a instituição',
                'data' => []
            ];
        }

        $oData = $this->pccfLicitaNumRepository->getEdital(
            $data->l24_instit,
            $data->l24_anousu
        );

        return [
            'status' => 200,
            'message' => 'Sucesso',
            'data' => [
                'l20_edital' => !empty($oData->l24_numero) ? $oData->l24_numero + 1 : 1
            ]
        ];
    }

}
