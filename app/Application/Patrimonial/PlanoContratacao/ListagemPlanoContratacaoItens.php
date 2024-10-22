<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoItemService;

class ListagemPlanoContratacaoItens implements HandleRepositoryInterface{
    private PcPlanoContratacaoItemService $planoContratacaoItemService;

    public function __construct(){
        $this->planoContratacaoItemService = new PcPlanoContratacaoItemService();
    }

    public function handle(object $data)
    {
        if(!empty($data->exercicio)){
            $result = $this->planoContratacaoItemService->getAllItensLicLicita(
                $data->exercicio,
                $data->instit,
                $data->mpc01_codigo,
                (!empty($data->limit) ? $data->limit : 20),
                (!empty($data->offset) ? ($data->offset - 1) : 0)
            );
            return [
                'total' => !empty($result['total']) ? $result['total'] : 0,
                'planoContratacaoItens' => !empty($result['data'])? $result['data'] : []
            ];
        }

        $result = $this->planoContratacaoItemService->getAllByExercicioPrevisao(
            $data->mpc01_codigo,
            (!empty($data->limit) ? $data->limit : 20),
            (!empty($data->offset) ? ($data->offset - 1) : 0)
        );

        return [
            'total' => !empty($result['total']) ? $result['total'] : 0,
            'planoContratacaoItens' => !empty($result['data'])? $result['data'] : []
        ];
    }
}
