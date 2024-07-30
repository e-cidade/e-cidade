<?php

namespace App\Services\Financeiro\Tesouraria\Command;
use App\Repositories\Financeiro\Tesouraria\SaltesRepository;
use App\Repositories\Financeiro\Tesouraria\SaltesRepositoryInterface;

class InsertSaltesCommand
{
  
    /**
     * @var SaltesRepositoryInterface
     */
    private SaltesRepositoryInterface $saltesRepository;

    public function __construct(
    ) {
        $this->saltesRepository = new SaltesRepository;
    }

    /**
     *
     * @return void
     */
    public function execute($saltes)
    {
        $resultSaltes = $this->saltesRepository->saveBySaltes($saltes);
        if (!$resultSaltes) {
            throw new \Exception("Erro ao inserir na tabela saltes");
        }
    }

}
