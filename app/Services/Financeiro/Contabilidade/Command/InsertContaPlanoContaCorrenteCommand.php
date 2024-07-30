<?php

namespace App\Services\Financeiro\Contabilidade\Command;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaCorrenteRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaCorrenteRepositoryInterface;

class InsertContaPlanoContaCorrenteCommand
{
  
    /**
     * @var ContaPlanoContaCorrenteRepositoryInterface
     */
    private ContaPlanoContaCorrenteRepositoryInterface $contaPlanoRepository;

    public function __construct() 
    {
        $this->contaPlanoRepository = new ContaPlanoContaCorrenteRepository;
    }

    /**
     *
     * @return void
     */
    public function execute($contaplanonovo)
    {
        $resultContaPlano = $this->contaPlanoRepository->saveByContaPlanoContaCorrente($contaplanonovo);
        if (!$resultContaPlano) {
            throw new \Exception("Erro ao inserir dados ConPlano");
        }

    }

}
