<?php

namespace App\Services\LicLicita;

use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;

class GetLicLicitaService{

    private LiclicitaRepository $liclicitaRepository;

    public function __construct(){
        $this->liclicitaRepository = new LiclicitaRepository();
    }

    public function execute(object $data){
        if(empty($data->l20_codigo)){
            return [
                'status' => 400,
                'message' => 'Por favor, informe a licitação',
                'data' => []
            ];
        }

        $oData = $this->liclicitaRepository->getDispensasInexigibilidadeByCodigo($data->l20_codigo)->toArray();

        if(empty($oData)){
            return [
                'status' => 400,
                'message' => 'Nenhuma numeração encontrado',
                'data' => []
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sucesso',
            'data' => [
                'licitacao' => $oData
            ]
        ];
    }
}
