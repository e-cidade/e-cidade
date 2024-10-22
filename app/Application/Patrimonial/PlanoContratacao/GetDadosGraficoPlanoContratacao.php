<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoService;

class GetDadosGraficoPlanoContratacao implements HandleRepositoryInterface{
    private PcPlanoContratacaoService $pcPlanoContratacaoService;

    public function __construct()
    {
        $this->pcPlanoContratacaoService = new PcPlanoContratacaoService();
    }

    public function handle(object $data)
    {
        return $this->pcPlanoContratacaoService->getDadosGrafico(
            $data->mpc01_codigo
        );
    }

}
