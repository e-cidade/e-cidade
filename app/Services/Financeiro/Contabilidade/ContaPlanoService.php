<?php

namespace App\Services\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\Factory\ContaPlanoFactory;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoRepository;
use App\Services\Financeiro\Contabilidade\ContaPlanoServiceInterface;
use App\Services\Financeiro\Contabilidade\Command\InsertContaPlanoCommand;

use stdClass;

class ContaPlanoService implements ContaPlanoServiceInterface
{
    /**
     * @var ContaPlanoRepository
     */
    private ContaPlanoRepository $contaplanorepository;

    public function __construct()
    {
        $this->contaplanorepository = new ContaPlanoRepository();
    }

    public function insertContaPlano(stdClass $dadoscontaplano): array
    {

        try {
           
            $contaplanoFactory = new ContaPlanoFactory();
            $contaplanonovo = $contaplanoFactory->createByStdLegacy($dadoscontaplano);

            $insertCommand = new InsertContaPlanoCommand();
            $result = $insertCommand->execute($contaplanonovo);

            if ($result === false) {
                throw new \Exception("Não foi possivel inserir dados na ConPlano");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }  

    public function searchContaPlano()
    {
        try {
           
            $contabancaria = new InsertContaPlanoCommand();
            $contaplanonovo = $contabancaria->searchContaPlanoAccounts();
        
            return $contaplanonovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function searchEstrutural(int $ultimoEstrut,int $ano)
    {
        try {
           
            $contabancaria = new InsertContaPlanoCommand();
            $contaplanonovo = $contabancaria->searchEstruturalAccounts($ultimoEstrut,$ano);
        
            return $contaplanonovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function searchNewEstrutural(int $ultimoEstrut,int $ano)
    {
        try {
           
            $contabancaria = new InsertContaPlanoCommand();
            $contaplanonovo = $contabancaria->searchNewEstruturalAccounts($ultimoEstrut,$ano);
        
            return $contaplanonovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function setvalContaPlano($sequencialcontaplano)
    {
        try {
           
            $contabancaria = new InsertContaPlanoCommand();
            $contaplanonovo = $contabancaria->setvalContaPlanoAccounts($sequencialcontaplano);
        
            return $contaplanonovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

}
