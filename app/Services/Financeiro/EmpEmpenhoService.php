<?php
namespace App\Services\Financeiro;

use App\Repositories\Financeiro\EmpEmpenhoRepository;

class EmpEmpenhoService
{
    private EmpEmpenhoRepository $empempenhoRepository;

    public function __construct()
    {
        $this->empempenhoRepository = new EmpEmpenhoRepository();
    }

    /**
     *
     * @param float $valor
     * @param string $empenho
     * @return void
     */
    public function alterarSaldoDisponivel(float $valor, string $empenho): void
    {
        $empenho = explode("/", $empenho);
        $empenhoData = $this->empempenhoRepository->getEmpenho($empenho[0],$empenho[1]);

        $saldoUtilizado = (float)$empenhoData->e60_vlrutilizado + $valor;
        $dadosUpdate = ['e60_vlrutilizado' => $saldoUtilizado];
        $result = $this->empempenhoRepository->atualizarEmpenho((int)$empenhoData->e60_numemp, $dadosUpdate);

        if (!$result) {
            throw new \LogicException("Não foi possível atualizar empenho");
        }
    }
}
