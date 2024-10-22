<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoService;

class CreatePlanoContratacao implements HandleRepositoryInterface{
    private PcPlanoContratacaoService $planoContratacaoService;

    public function __construct()
    {
        $this->planoContratacaoService = new PcPlanoContratacaoService();
    }

    public function handle($data)
    {
        $planoContratacao = $this->planoContratacaoService->create(
            $data->mpc01_ano,
            $data->mpc01_uncompradora,
            $data->mpc01_data,
            $data->mpc01_usuario
        );

        return ['planoContratacao' => $planoContratacao->toArray()];
    }
}
