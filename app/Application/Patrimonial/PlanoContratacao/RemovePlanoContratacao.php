<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoService;

class RemovePlanoContratacao implements HandleRepositoryInterface{
    private PcPlanoContratacaoService $pcPlanoContratacaoService;

    public function __construct()
    {
        $this->pcPlanoContratacaoService = new PcPlanoContratacaoService();
    }

    public function handle(object $data)
    {
        return $this->pcPlanoContratacaoService->deletePlanoContratacao($data->mpc01_codigo);
    }

}
