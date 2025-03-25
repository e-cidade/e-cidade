<?php
namespace App\Services\Cflicita;

use App\Repositories\Patrimonial\Licitacao\CflicitaRepository;

class GetCflicitaService{
    private CflicitaRepository $cflicitaRepository;

    public function __construct()
    {
        $this->cflicitaRepository = new CflicitaRepository();
    }

    public function execute(object $data){
        $l03_instit = $data->instit;
        $l03_pctipocompratribunal = $data->l03_pctipocompratribunal ?? [];

        $result = $this->cflicitaRepository->getDadosByFilter(
            $l03_instit,
            $l03_pctipocompratribunal
        );

        return [
            'status' => 200,
            'message' => 'Tipo de Processo/Modalidade carregados com sucesso!',
            'data' => [
                'tipoprocesso' => $result
            ]
        ];
    }
}
