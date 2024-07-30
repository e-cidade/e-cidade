<?php

namespace App\Services\Financeiro\Tesouraria;

use App\Domain\Financeiro\Tesouraria\Factory\ContaBancariaFactory;
use App\Repositories\Financeiro\Tesouraria\ContaBancariaRepository;
use App\Services\Financeiro\Tesouraria\ContaBancariaServiceInterface;
use App\Services\Financeiro\Tesouraria\Command\InsertContaBancariaCommand;
use App\Services\Financeiro\Tesouraria\Command\UpdateContaBancariaCommand;
use App\Services\Financeiro\Tesouraria\Command\DeleteContaBancariaCommand;

use stdClass;

class ContaBancariaService implements ContaBancariaServiceInterface
{
    /**
     * @var ContaBancariaRepository
     */
    private ContaBancariaRepository $contabancariarepository;

    public function __construct()
    {
        $this->contabancariarepository = new ContaBancariaRepository();
    }

    public function insertContaBancaria(stdClass $dadoscontabancaria): array
    {
        try {
           
            $contabancarianovoFactory = new ContaBancariaFactory();
            $contabancarianovo = $contabancarianovoFactory->createByStdLegacy($dadoscontabancaria);

            $insertCommand = new InsertContaBancariaCommand();
            $result = $insertCommand->execute($contabancarianovo);

            if ($result === false) {
                throw new \Exception("Não foi possivel inserir");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateContaBancaria(stdClass $dadoscontabancaria, stdClass $chavestabelas): array
    {
        try {
           
            $contabancarianovoFactory = new ContaBancariaFactory();
            $contabancarianovo = $contabancarianovoFactory->createByStdLegacy($dadoscontabancaria);

            $updateCommand = new UpdateContaBancariaCommand();
            $result = $updateCommand->execute($contabancarianovo,$chavestabelas);
           
            if ($result === false) {
                throw new \Exception("Não foi possivel atualizar");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteContaBancaria(stdClass $chavestabelas): array
    {
        try {
            
            $updateCommand = new DeleteContaBancariaCommand();
            $result = $updateCommand->execute($chavestabelas);
            
            if ($result === false) {
                throw new \Exception("Não foi possivel excluir conta bancaria { $chavestabelas->c62_reduz }");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function searchSequential()
    {
        try {
           
            $contabancaria = new InsertContaBancariaCommand();
            $contabancarianovo = $contabancaria->searchSequentialAccounts();
        
            return $contabancarianovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function checkAccount(int $sequencial)
    {
        try {
           
            $contabancaria = new InsertContaBancariaCommand();
            $contabancarianovo = $contabancaria->checkAccountExists($sequencial);
        
            return $contabancarianovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function checkGeneral(int $sequencial)
    {
        try {
           
            $contabancaria = new InsertContaBancariaCommand();
            $contabancarianovo = $contabancaria->checkAllTables($sequencial);
        
            return $contabancarianovo;
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }


}
