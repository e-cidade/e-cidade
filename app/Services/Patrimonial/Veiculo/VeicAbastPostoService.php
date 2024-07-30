<?php

namespace App\Services\Patrimonial\Veiculo;

use App\Models\VeicAbastPosto;
use App\Repositories\Patrimonial\VeicAbastPostoRepository;

class VeicAbastPostoService
{
    private VeicAbastPostoRepository $veicAbastPostoRepository;


    public function __construct()
    {
        $this->veicAbastPostoRepository = new VeicAbastPostoRepository();
    }

    public function insert(array $dados):  ?VeicAbastPosto
    {
        $posto = [];

        $posto['ve71_veicabast'] = $dados['codigoAbastecimento'];

        $veicCadPostoService = new VeicCadPostoService();
        $ve71_veiccadposto = $veicCadPostoService->getVeicVeicCadPostoByEmpenho($dados['empenho']);

        $posto['ve71_veiccadposto'] = $ve71_veiccadposto;
        return $this->veicAbastPostoRepository->insert($posto);
    }
}
