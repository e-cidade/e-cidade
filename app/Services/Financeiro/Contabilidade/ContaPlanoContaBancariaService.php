<?php

namespace App\Services\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\Factory\ContaPlanoContaBancariaFactory;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaBancariaRepository;
use App\Services\Financeiro\Contabilidade\ContaPlanoContaBancariaServiceInterface;
use App\Services\Financeiro\Contabilidade\Command\InsertContaPlanoContaBancariaCommand;

use stdClass;

class ContaPlanoContaBancariaService implements ContaPlanoContaBancariaServiceInterface
{
    /**
     * @var ContaPlanoContaBancariaRepository
     */
    private ContaPlanoContaBancariaRepository $contaplanorepository;

    public function __construct()
    {
        $this->contaplanorepository = new ContaPlanoContaBancariaRepository();
    }

    public function insertContaPlanoContaBancaria(stdClass $dadoscontaplano): array
    {

        try {
           
            $contaplanoFactory = new ContaPlanoContaBancariaFactory();
            $contaplanonovo = $contaplanoFactory->createByStdLegacy($dadoscontaplano);

            $insertCommand = new InsertContaPlanoContaBancariaCommand();
            $result = $insertCommand->execute($contaplanonovo);

            if ($result === false) {
                throw new \Exception("Não foi possivel inserir dados na ConPlanoContaBancaria");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }  
    
}
