<?php

namespace App\Services\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\Factory\ContaPlanoContaFactory;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaRepository;
use App\Services\Financeiro\Contabilidade\ContaPlanoContaServiceInterface;
use App\Services\Financeiro\Contabilidade\Command\InsertContaPlanoContaCommand;

use stdClass;

class ContaPlanoContaService implements ContaPlanoContaServiceInterface
{
    /**
     * @var ContaPlanoContaRepository
     */
    private ContaPlanoContaRepository $contaplanorepository;

    public function __construct()
    {
        $this->contaplanorepository = new ContaPlanoContaRepository();
    }

    public function insertContaPlanoConta(stdClass $dadoscontaplanoconta, int $codcon,int $ano): array
    {

        try {
        
            $contaplanoFactory = new ContaPlanoContaFactory();
            $contaplanonovo = $contaplanoFactory->createByStdLegacy($dadoscontaplanoconta,$codcon,$ano);
           
            $insertCommand = new InsertContaPlanoContaCommand();
            $result = $insertCommand->execute($contaplanonovo);
          
            if ($result === false) {
                throw new \Exception("Não foi possivel inserir dados na ConPlanoConta");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }  

}
