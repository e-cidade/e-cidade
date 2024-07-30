<?php

namespace App\Services\Financeiro\Contabilidade\Command;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaRepositoryInterface;

class InsertContaPlanoContaCommand
{
  
    /**
     * @var ContaPlanoRepositoryInterface
     */
    private ContaPlanoContaRepositoryInterface $contaPlanoContaRepository;

    public function __construct() 
    {
        $this->contaPlanoContaRepository = new ContaPlanoContaRepository;
    }

    /**
     *
     * @return void
     */
    public function execute($contaplanonovo)
    {
        $resultContaPlano = $this->contaPlanoContaRepository->saveByContaPlanoConta($contaplanonovo);
        if (!$resultContaPlano) {
            throw new \Exception("Erro ao inserir dados ConPlanoConta");
        }

    }
}
