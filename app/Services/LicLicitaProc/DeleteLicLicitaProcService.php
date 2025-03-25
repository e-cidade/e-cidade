<?php

namespace App\Services\LicLicitaProc;

use App\Repositories\Patrimonial\Licitacao\LicLicitaProcRepository;

class DeleteLicLicitaProcService{
    private LicLicitaProcRepository $liclicitaProcRepository;

    public function __construct()
    {
        $this->liclicitaProcRepository = new LicLicitaProcRepository();
    }

    public function execute(object $data){
        if(empty($data->l20_codigo)){
            return [
                'status'  => 400,
                'message' => 'Por favor informe o processo e o código da licitação',
                'data'    => []
            ];
        }

        $oLicProc = $this->liclicitaProcRepository->getLicLicitaProcByLicLicita($data->l20_codigo);
        if(!empty($oLicProc)){
            foreach($oLicProc as $value){
                $this->liclicitaProcRepository->removeByCodigo($value->l34_sequencial);
            }
        }

        return [
            'status'  => 200,
            'message' => 'Processo removido com sucesso',
            'data'    => []
        ];
    }
}
