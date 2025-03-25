<?php

namespace App\Services\PccfEditalNum;

use App\Repositories\Patrimonial\Licitacao\PccfEditalNumRepository;

class GetPccfEditalNumService{
    private PccfEditalNumRepository $pccfEditalNumRepository;

    public function __construct()
    {
        $this->pccfEditalNumRepository = new PccfEditalNumRepository();
    }

    public function execute(object $data){
        if(empty($data->l47_instit) || empty($data->l47_anousu)){
            return [
                'status' => 400,
                'message' => 'Por favor, informe o ano e a instituição',
                'data' => []
            ];
        }

        $oData = $this->pccfEditalNumRepository->getNroEdital(
            $data->l47_instit,
            $data->l47_anousu
        );

        return [
            'status' => 200,
            'message' => 'Sucesso',
            'data' => [
                'l20_nroedital' => !empty($oData->l47_numero) ? $oData->l47_numero + 1 : 1
            ]
        ];
    }

}
