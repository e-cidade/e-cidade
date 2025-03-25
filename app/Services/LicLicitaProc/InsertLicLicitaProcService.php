<?php

namespace App\Services\LicLicitaProc;

use App\Models\Patrimonial\Licitacao\LicLicitaProc;
use App\Repositories\Patrimonial\Licitacao\LicLicitaProcRepository;

class InsertLicLicitaProcService{
    private LicLicitaProcRepository $liclicitaProcRepository;

    public function __construct()
    {
        $this->liclicitaProcRepository = new LicLicitaProcRepository();
    }

    public function execute(object $data){
        if(empty($data->l34_protprocesso) || empty($data->l20_codigo)){
            return [
                'status'  => 400,
                'message' => 'Por favor informe o processo e o código da licitação',
                'data'    => []
            ];
        }

        $oData = new LicLicitaProc([
            'l34_sequencial'   => $this->liclicitaProcRepository->getNextVal(),
            'l34_protprocesso' => $data->l34_protprocesso,
            'l34_liclicita'    => $data->l20_codigo,
        ]);

        $oResult = $this->liclicitaProcRepository->save($oData);
        if(empty($oResult)){
            return [
                'status' => 400,
                'message' => 'Não foi possível salvar o processo',
                'data' => []
            ];
        }

        return [
            'status'  => 200,
            'message' => 'Processo salva com sucesso',
            'data'    => []
        ];
    }
}
