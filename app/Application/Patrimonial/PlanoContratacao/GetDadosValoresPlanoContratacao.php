<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoService;

class GetDadosValoresPlanoContratacao implements HandleRepositoryInterface{
    private PcPlanoContratacaoService $pcPlanoContratacaoService;

    public function __construct()
    {
        $this->pcPlanoContratacaoService = new PcPlanoContratacaoService();
    }

    public function handle(object $data)
    {
        return $this->pcPlanoContratacaoService->getDadosValores(
            $data->mpc01_codigo
        );
    }

}
