<?php

namespace App\Services\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\Factory\ContaPlanoReduzFactory;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoReduzRepository;
use App\Services\Financeiro\Contabilidade\ContaPlanoReduzServiceInterface;
use App\Services\Financeiro\Contabilidade\Command\InsertContaPlanoReduzCommand;

use stdClass;

class ContaPlanoReduzService implements ContaPlanoReduzServiceInterface
{
    /**
     * @var ContaPlanoReduzRepository
     */
    private ContaPlanoReduzRepository $contaplanoreduzrepository;

    public function __construct()
    {
        $this->contaplanoreduzrepository = new ContaPlanoReduzRepository();
    }

    public function insertContaPlanoReduz(stdClass $dadoscontaplanoreduzconta): array
    {

        try {
        
            $contaplanoreduzFactory = new ContaPlanoReduzFactory();
            $contaplanoreduznovo = $contaplanoreduzFactory->createByStdLegacy($dadoscontaplanoreduzconta);

            $insertCommand = new InsertContaPlanoReduzCommand();
            $result = $insertCommand->execute($contaplanoreduznovo);

            if ($result === false) {
                throw new \Exception("Não foi possivel inserir dados na ConPlanoReduz");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }  

    public function searchContaPlanoReduz()
    {
        try {
           
            $contaplanoreduz = new InsertContaPlanoReduzCommand();
            $contaplanoreduznovo = $contaplanoreduz->searchContaPlanoReduzAccounts();
        
            return $contaplanoreduznovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function setvalContaPlanoReduz(int $sequencialcontaplanoreduz)
    {
        try {
           
            $contaplanoreduz = new InsertContaPlanoReduzCommand();
            $contaplanoreduznovo = $contaplanoreduz->setvalContaPlanoReduzAccounts($sequencialcontaplanoreduz);
        
            return $contaplanoreduznovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }



    

}
