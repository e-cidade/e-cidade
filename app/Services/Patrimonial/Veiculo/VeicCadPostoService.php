<?php

namespace App\Services\Patrimonial\Veiculo;

use App\Repositories\Financeiro\EmpEmpenhoRepository;
use App\Repositories\Patrimonial\VeicCadPostoExternoRepository;
use App\Repositories\Patrimonial\VeicCadPostoRepository;

class VeicCadPostoService
{
    private VeicCadPostoRepository $veicCadPostoResository;


    public function __construct()
    {
        $this->veicCadPostoResository = new VeicCadPostoRepository();
    }

    public function getVeicVeicCadPostoByEmpenho(string $empenho)
    {
        $empenhoArray = explode('/', $empenho);
        $empempenhoRepository = new EmpEmpenhoRepository();
        $empenhoModel = $empempenhoRepository->getEmpenho($empenhoArray[0], $empenhoArray[1], ['e60_numemp','e60_numcgm']);

        if (empty($empenhoModel)) {
            throw new \Exception('Empenho não encontrado');
        }

        $cgm = $empenhoModel->e60_numcgm;

        $veicCadPostoExternoRepo = new VeicCadPostoExternoRepository();

        $veicCadPostoExterno = $veicCadPostoExternoRepo->getFirstByCgm($cgm);

        if (empty($veicCadPostoExterno)) {
          $veicCadPosto =  $this->veicCadPostoResository->insert(13);
          $veicCadPostoExternoRepo->insert($veicCadPosto->ve29_codigo, $cgm);
          return $veicCadPosto->ve29_codigo;
        }

        return $veicCadPostoExterno->veicCadPosto->ve29_codigo;
    }
}
