<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Patrimonial\PlanoContratacao\PcPlanoContratacaoService;

class ListagemPlanoContracoes implements HandleRepositoryInterface{
    private PcPlanoContratacaoService $planoContratacaoService;

    public function __construct()
    {
        $this->planoContratacaoService = new PcPlanoContratacaoService();
    }

    public function handle(object $data)
    {
        $result = $this->planoContratacaoService->getAllByAnoUnidade(
            $data->ano,
            $data->unidade,
            (!empty($data->limit) ? $data->limit : 20),
            (!empty($data->offset) ? ($data->offset - 1) : 0)
        );
        return [
            'total' => !empty($result['total']) ? $result['total'] : 0,
            'planoContratacoes' => !empty($result['data'])? $result['data'] : []
        ];
    }
}
