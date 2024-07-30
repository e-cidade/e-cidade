<?php

namespace App\Services\Financeiro\Empenho\Command;
use App\Repositories\Financeiro\Empenho\EmpagetipoRepository;
use App\Repositories\Financeiro\Empenho\EmpagetipoRepositoryInterface;

class InsertEmpagetipoCommand
{
  
    /**
     * @var EmpagetipoRepositoryInterface
     */
    private EmpagetipoRepositoryInterface $empagetipoRepository;

    public function __construct() 
    {
        $this->empagetipoRepository = new EmpagetipoRepository;
    }

    /**
     *
     * @return void
     */
    public function execute($empagetipo)
    {
        $resultEmpagetipo = $this->empagetipoRepository->saveByEmpagetipo($empagetipo);
        if (!$resultEmpagetipo) {
            throw new \Exception("Erro ao inserir dados Empagetipo");
        }

    }

}
