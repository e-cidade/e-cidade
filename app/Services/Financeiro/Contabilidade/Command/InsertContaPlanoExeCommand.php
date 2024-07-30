<?php

namespace App\Services\Financeiro\Contabilidade\Command;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoExeRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoExeRepositoryInterface;
use App\Services\Financeiro\Contabilidade\Command\InsertContaPlanoExeCommandInterface;
use App\Domain\Financeiro\Contabilidade\ContaPlanoExe;
class InsertContaPlanoExeCommand implements InsertContaPlanoExeCommandInterface
{
  
    /**
     * @var ContaPlanoExeRepositoryInterface
     */
    private ContaPlanoExeRepositoryInterface $contaPlanoExeRepository;

    public function __construct() 
    {
        $this->contaPlanoExeRepository = new ContaPlanoExeRepository;
    }

    /**
     *
     * @return void
     */
    public function execute(ContaPlanoExe $contaplanoexe) : bool
    {
        $resultContaPlanoExe = $this->contaPlanoExeRepository->saveByContaPlanoExe($contaplanoexe);
        if (!$resultContaPlanoExe) {
            throw new \Exception("Erro ao inserir dados ConPlanoExe");
        }
        return true;
    }

    
}
