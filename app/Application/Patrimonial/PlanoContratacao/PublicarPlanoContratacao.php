<?php

namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoService;

class PublicarPlanoContratacao implements HandleRepositoryInterface {
    private PcPlanoContratacaoService $planoContratacaoService;

    public function __construct()
    {
        $this->planoContratacaoService = new PcPlanoContratacaoService();
    }

    public function handle(object $data)
    {
        $response = $this->planoContratacaoService->publicar(
            $data->mpc01_codigo,
            $data->anousu,
            $data->instit,
            $data->id_usuario,
            $data->datausu
        );
        return $response;
    }

}
