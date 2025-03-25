<?php
namespace App\Services\AmparoLegal;

use App\Repositories\Patrimonial\Licitacao\AmparoLegalRepository;

class GetAmparoLegalService{
    private AmparoLegalRepository $amparoLegalService;

    public function __construct()
    {
        $this->amparoLegalService = new AmparoLegalRepository();
    }

    public function execute(object $data){
        if(empty($data->l20_codtipocom)){
            return [
                'status' => 200,
                'message' => 'Código do tribunal não informado',
                'data' => []
            ];
        }

        $result = $this->amparoLegalService->getDadosByFilter(
            $data->l20_codtipocom
        );

        return [
            'status' => 200,
            'message' => 'Amparo legal carregado com sucesso!',
            'data' => [
                'amparolegal' => $result
            ]
        ];
    }
}
