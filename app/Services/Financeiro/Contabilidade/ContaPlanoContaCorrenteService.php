<?php

namespace App\Services\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\Factory\ContaPlanoContaCorrenteFactory;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaCorrenteRepository;
use App\Services\Financeiro\Contabilidade\ContaPlanoContaCorrenteServiceInterface;
use App\Services\Financeiro\Contabilidade\Command\InsertContaPlanoContaCorrenteCommand;

use stdClass;

class ContaPlanoContaCorrenteService implements ContaPlanoContaCorrenteServiceInterface
{
    /**
     * @var ContaPlanoContaCorrenteRepository
     */
    private ContaPlanoContaCorrenteRepository $contaplanorepository;

    public function __construct()
    {
        $this->contaplanorepository = new ContaPlanoContaCorrenteRepository();
    }

    public function insertContaPlanoContaCorrente(stdClass $dadoscontaplano): array
    {

        try {
           
            $contaplanoFactory = new ContaPlanoContaCorrenteFactory();
            $contaplanonovo = $contaplanoFactory->createByStdLegacy($dadoscontaplano);
            
            $insertCommand = new InsertContaPlanoContaCorrenteCommand();
            $result = $insertCommand->execute($contaplanonovo);

            if ($result === false) {
                throw new \Exception("Não foi possivel inserir dados na ConPlanoContaCorrente");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }  
    
}
