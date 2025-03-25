<?php
namespace App\Services\PcProc;

use App\Repositories\Patrimonial\Compras\PcprocRepository;

class GetProcessoCromprasVinculadosService{
    private PcprocRepository $pcprocRepository;

    public function __construct()
    {
        $this->pcprocRepository = new pcprocRepository();
    }

    public function execute(object $data){
        if(empty($data->l20_codigo)){
            return [
                'status' => 200,
                'message' => 'Nenhum processo vinculado foi encontrado',
                'data' => []
            ];
        }

        $oProcessosVinculados = $this->pcprocRepository->getProcessodeComprasVinculados($data->l20_codigo);

        return [
            'status' => 200,
            'message' => 'Processos vinculados carregados com sucesso!',
            'data' => $oProcessosVinculados
        ];
    }
}
