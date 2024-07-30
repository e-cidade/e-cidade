<?php

namespace App\Services\Financeiro\Empenho;

use App\Domain\Financeiro\Empenho\Factory\EmpagetipoFactory;
use App\Repositories\Financeiro\Empenho\EmpagetipoRepository;
use App\Services\Financeiro\Empenho\EmpagetipoServiceInterface;
use App\Services\Financeiro\Empenho\Command\InsertEmpagetipoCommand;

use stdClass;

class EmpagetipoService implements EmpagetipoServiceInterface
{
    /**
     * @var EmpagetipoRepository
     */
    private EmpagetipoRepository $empagetiporepository;

    public function __construct()
    {
        $this->empagetiporepository = new EmpagetipoRepository();
    }

    public function insertEmpagetipo(stdClass $dadosempagetipo): array
    {

        try {
           
            $empagetipoFactory = new EmpagetipoFactory();
            $empagetiponovo = $empagetipoFactory->createByStdLegacy($dadosempagetipo);

            $insertCommand = new InsertEmpagetipoCommand();
            $result = $insertCommand->execute($empagetiponovo);

            if ($result === false) {
                throw new \Exception("Não foi possivel inserir dados na Empagetipo");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }  

}
