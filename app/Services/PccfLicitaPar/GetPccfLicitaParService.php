<?php

namespace App\Services\PccfLicitaPar;

use App\Repositories\Patrimonial\Licitacao\PccfLicitaParRepository;

class GetPccfLicitaParService{
    private PccfLicitaParRepository $pccfLicitaParRepository;

    public function __construct()
    {
        $this->pccfLicitaParRepository = new PccfLicitaParRepository();
    }

    public function execute(object $data){
        if(empty($data->l25_codcflicita) || empty($data->l25_anousu)){
            return [
                'status' => 400,
                'message' => 'Por favor, informe o ano e o processo',
                'data' => []
            ];
        }

        $oData = $this->pccfLicitaParRepository->getNumeracao(
            $data->l25_anousu,
            $data->l25_codcflicita
        );

        return [
            'status' => 200,
            'message' => 'Sucesso',
            'data' => [
                'l20_numero' => $oData->l25_numero ? $oData->l25_numero + 1 : 1
            ]
        ];
    }

}
