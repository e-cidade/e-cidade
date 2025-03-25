<?php

namespace App\Services\LicLicita;

use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;

class GetTribunalService{

    private LiclicitaRepository $liclicitaRepository;

    public function __construct(){
        $this->liclicitaRepository = new LiclicitaRepository();
    }

    public function execute(object $data){

        $result = $this->liclicitaRepository->getTribunal($data->l20_codigo)->toArray();

        return [
            'status' => 200,
            'message' => 'Sucesso',
            'data' => [
                'tribunal' => $result
            ]
        ];
    }
}
