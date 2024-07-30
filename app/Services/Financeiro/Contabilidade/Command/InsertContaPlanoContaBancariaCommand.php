<?php

namespace App\Services\Financeiro\Contabilidade\Command;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaBancariaRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaBancariaRepositoryInterface;

class InsertContaPlanoContaBancariaCommand
{
  
    /**
     * @var ContaPlanoContaBancariaRepositoryInterface
     */
    private ContaPlanoContaBancariaRepositoryInterface $contaPlanoRepository;

    public function __construct() 
    {
        $this->contaPlanoRepository = new ContaPlanoContaBancariaRepository;
    }

    /**
     *
     * @return void
     */
    public function execute($contaplanonovo)
    {
        $resultContaPlano = $this->contaPlanoRepository->saveByContaPlanoContaBancaria($contaplanonovo);
        if (!$resultContaPlano) {
            throw new \Exception("Erro ao inserir dados ConPlano");
        }

    }
 
}
