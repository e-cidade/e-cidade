<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Models\Patrimonial\Compras\PcPlanoContratacao;
use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Patrimonial\PlanoContratacao\PcPlanoContratacaoRepository;

class GetPlanoDeContratacao implements HandleRepositoryInterface{
    private PcPlanoContratacaoRepository $pcPlanoContratacao;

    public function __construct()
    {
        $this->pcPlanoContratacao = new PcPlanoContratacaoRepository();
    }

    public function handle(object $data)
    {
        return ['planoContratacao' => $this->pcPlanoContratacao->getPlanoContratacaoByCodigo($data->mpc01_codigo)->toArray()];
    }
}
