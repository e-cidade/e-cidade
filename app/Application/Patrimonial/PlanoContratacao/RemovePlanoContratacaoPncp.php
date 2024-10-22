<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoService;

class RemovePlanoContratacaoPncp implements HandleRepositoryInterface{
    private PcPlanoContratacaoService $pcPlanoContratacaoService;

    public function __construct()
    {
        $this->pcPlanoContratacaoService = new PcPlanoContratacaoService();
    }

    public function handle(object $data)
    {
        return $this->pcPlanoContratacaoService->delete(
            $data->mpc01_codigo,
            $data->justificativa,
            $data->anousu,
            $data->instit,
            $data->id_usuario,
            $data->datausu
        );
    }

}
