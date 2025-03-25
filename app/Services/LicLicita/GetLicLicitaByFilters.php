<?php

namespace App\Services\LicLicita;

use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;

class GetLicLicitaByFilters{

    private LiclicitaRepository $liclicitaRepository;

    public function __construct(){
        $this->liclicitaRepository = new LiclicitaRepository();
    }

    public function execute(object $data){
        
        $aData = $this->liclicitaRepository->getAllByFilters(
            $data->l20_codigo,
            $data->l20_numero,
            $data->l20_edital,
            $data->l20_codtipocom,
            $data->l20_anousu,
            $data->l20_nroedital,
            $data->l20_datacria,
            $data->l20_objeto,
            $data->l08_sequencial,
            $data->orderable,
            $data->search,
            $data->is_contass,
            $data->limit,
            $data->offset,
            $data->modalidades ?? [],
            $data->l20_instit
        );

        return [
            'status' => 200,
            'message' => 'Sucesso',
            'data' => [
                'total' => $aData['total'],
                'licitacoes' => $aData['data']
            ]
        ];
    }
}
