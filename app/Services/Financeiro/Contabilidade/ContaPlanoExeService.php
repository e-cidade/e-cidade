<?php

namespace App\Services\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\Factory\ContaPlanoExeFactory;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoExeRepository;
use App\Services\Financeiro\Contabilidade\ContaPlanoExeServiceInterface;
use App\Services\Financeiro\Contabilidade\Command\InsertContaPlanoExeCommand;

use stdClass;

class ContaPlanoExeService implements ContaPlanoExeServiceInterface
{
    /**
     * @var ContaPlanoExeRepository
     */
    private ContaPlanoExeRepository $contaplanoexerepository;

    public function __construct()
    {
        $this->contaplanoexerepository = new ContaPlanoExeRepository();
    }

    public function insertContaPlanoExe(stdClass $dadoscontaplanoexeconta): array
    {

        try {
        
            $contaplanoexeFactory = new ContaPlanoExeFactory();
            $contaplanoexenovo = $contaplanoexeFactory->createByStdLegacy($dadoscontaplanoexeconta);

            $insertCommand = new InsertContaPlanoExeCommand();
            $result = $insertCommand->execute($contaplanoexenovo);

            if ($result === false) {
                throw new \Exception("Não foi possivel inserir dados na ConPlanoExe");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }  

}
