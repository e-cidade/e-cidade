<?php
namespace App\Services\PcProc;

use App\Repositories\Patrimonial\Compras\PcprocRepository;

class GetDadosPcProcService{
    private PcprocRepository $pcprocRepository;

    public function __construct()
    {
        $this->pcprocRepository = new pcprocRepository();
    }

    public function execute(object $data){
        if(empty($data->codproc)){
            return [
                'status' => 200,
                'message' => 'Dados carregados com sucesso!',
                'data' => []
            ];
        }

        $oData = $this->pcprocRepository->getDadosProcessoByCodigo($data->codproc, $data->instit)[0];

        return [
            'status' => 200,
            'message' => 'Dados carregados com sucesso!',
            'data' => $oData
        ];

    }
}
