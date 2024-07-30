<?php

namespace App\Services\Patrimonial\Veiculo;

use App\Models\VeicAbastRetirada;
use App\Repositories\Patrimonial\VeicAbastRetiradaRepository;

class VeicAbastRetiradaService
{
    private VeicAbastRetiradaRepository $veicAbastRetiradaRepository;

    public function __construct()
    {
        $this->veicAbastRetiradaRepository = new VeicAbastRetiradaRepository();
    }

    /**
     *
     * @param array $dados
     * @return VeicAbastRetirada|null
     */
    public function insert(int $codigoAbastecimento, int $codigoRetirada):  ?VeicAbastRetirada
    {
        $dados = [];

        $dados['ve73_veicabast'] = $codigoAbastecimento;
        $dados['ve73_veicretirada'] = $codigoRetirada;

        return $this->veicAbastRetiradaRepository->insert($dados);
    }
}
