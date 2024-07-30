<?php

namespace App\Services\Financeiro\Contabilidade\Command;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoReduzRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoReduzRepositoryInterface;

class InsertContaPlanoReduzCommand
{
  
    /**
     * @var ContaPlanoReduzRepositoryInterface
     */
    private ContaPlanoReduzRepositoryInterface $contaPlanoReduzRepository;

    public function __construct() 
    {
        $this->contaPlanoReduzRepository = new ContaPlanoReduzRepository;
    }

    /**
     *
     * @return void
     */
    public function execute($contaplanoreduz)
    {
        $resultContaPlanoReduz = $this->contaPlanoReduzRepository->saveByContaPlanoReduz($contaplanoreduz);
        if (!$resultContaPlanoReduz) {
            throw new \Exception("Erro ao inserir dados ConPlanoReduz");
        }

    }

    public function searchContaPlanoReduzAccounts()
    {  
        
        $resultContaPlano = $this->contaPlanoReduzRepository->searchContaPlanoReduzAccounts();
        $contaPlano = $resultContaPlano->first();
        
        if ($contaPlano) {
            $c61_reduz = $contaPlano->c61_reduz + 1;
            return $c61_reduz;
        } else {
            throw new \Exception("Não existe Estrutural na tabela");
            return false;
        }
    }

    public function setvalContaPlanoReduzAccounts(int $sequencialcontaplanoreduz)
    {  
        
        $resultContaPlano = $this->contaPlanoReduzRepository->setvalContaPlanoReduzAccounts($sequencialcontaplanoreduz);
               
        if ($resultContaPlano) {
            return $resultContaPlano;
        } else {
            throw new \Exception("Não existe Estrutural na tabela");
            return false;
        }
    }
    
}
