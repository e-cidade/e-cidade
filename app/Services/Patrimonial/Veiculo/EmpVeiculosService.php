<?php

namespace App\Services\Patrimonial\Veiculo;

use App\Models\EmpVeiculos;
use App\Repositories\Financeiro\EmpEmpenhoRepository;
use App\Repositories\Patrimonial\EmpVeiculosRepository;

class EmpVeiculosService
{
    private EmpVeiculosRepository $empVeiculosRepository;

    public function __construct()
    {
        $this->empVeiculosRepository = new EmpVeiculosRepository();
    }

    /**
     * @param array $dados
     * @return void
     */
    public function insert(array $dados): ?EmpVeiculos
    {
        $empenho = [];
        $empenho['si05_numemp'] = $this->getCodigoEmpenho($dados['empenho']);
        $empenho['si05_atestado'] = true;
        $empenho['si05_codabast'] = $dados['codigoAbastecimento'];
        $empenho['si05_item_empenho'] = false;

        return $this->empVeiculosRepository->insert($empenho);
    }

    /**
     * @param string $empenho
     * @return integer
     */
    private function getCodigoEmpenho(string $empenho): int
    {
        $numeroEmpArray = explode('/', $empenho);

        $codigoEmpenho = (new EmpEmpenhoRepository())->getCodigoEmpenho($numeroEmpArray[0], $numeroEmpArray[1]);

        return $codigoEmpenho;
    }
}
