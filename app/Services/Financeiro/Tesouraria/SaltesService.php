<?php

namespace App\Services\Financeiro\Tesouraria;

use App\Domain\Financeiro\Tesouraria\Factory\SaltesFactory;
use App\Repositories\Financeiro\Tesouraria\SaltesRepository;
use App\Services\Financeiro\Tesouraria\SaltesServiceInterface;
use App\Services\Financeiro\Tesouraria\Command\InsertSaltesCommand;

use stdClass;

class SaltesService implements SaltesServiceInterface
{
    /**
     * @var SaltesRepository
     */
    private SaltesRepository $saltesrepository;

    public function __construct()
    {
        $this->saltesrepository = new SaltesRepository();
    }

    public function insertSaltes(stdClass $dadossaltes): array
    {
        try {
           
            $saltesFactory = new SaltesFactory();
            $saltes = $saltesFactory->createByStdLegacy($dadossaltes);

            $insertCommand = new InsertSaltesCommand();
            $result = $insertCommand->execute($saltes);

            if ($result === false) {
                throw new \Exception("Não foi possivel inserir");
            }

            return ['status' => true];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

}
