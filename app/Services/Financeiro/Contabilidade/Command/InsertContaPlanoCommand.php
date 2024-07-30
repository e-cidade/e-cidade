<?php

namespace App\Services\Financeiro\Contabilidade\Command;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoRepository;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoRepositoryInterface;

class InsertContaPlanoCommand
{
  
    /**
     * @var ContaPlanoRepositoryInterface
     */
    private ContaPlanoRepositoryInterface $contaPlanoRepository;

    public function __construct() 
    {
        $this->contaPlanoRepository = new ContaPlanoRepository;
    }

    /**
     *
     * @return void
     */
    public function execute($contaplanonovo)
    {
        $resultContaPlano = $this->contaPlanoRepository->saveByContaPlano($contaplanonovo);
        if (!$resultContaPlano) {
            throw new \Exception("Erro ao inserir dados ConPlano");
        }

    }

    public function searchContaPlanoAccounts()
    {
        $resultContaPlano = $this->contaPlanoRepository->searchContaPlanoAccounts();
        $contaPlano = $resultContaPlano->first();
        
        if ($contaPlano) {
            $c60_codcon = $contaPlano->c60_codcon + 1;
            $this->contaPlanoRepository->setvalContaPlanoAccounts($contaPlano->c60_codcon);
            return $c60_codcon;
        } else {
            throw new \Exception("Erro ao inserir conta bancaria");
        }
    }

    public function searchEstruturalAccounts(int $ultimoEstrut,int $ano)
    {  
        $resultContaPlano = $this->contaPlanoRepository->searchEstruturalAccounts($ultimoEstrut,$ano);
        $contaPlano = $resultContaPlano->first();
        
        if ($contaPlano) {
            $c60_estrut = $contaPlano->c60_estrut;
            return $c60_estrut;
        } else {
            throw new \Exception("Não existe Estrutural na tabela");
            return false;
        }
    }

    public function setvalContaPlanoAccounts(int $sequencialcontaplano)
    {  
        
        $resultContaPlano = $this->contaPlanoRepository->setvalContaPlanoAccounts($sequencialcontaplano);
      
        if ($resultContaPlano) {
            return $resultContaPlano;
        } else {
            return false;
        }
    }

    public function searchNewEstruturalAccounts(int $ultimoEstrut,int $ano)
    {  
        
        $resultContaPlano = $this->contaPlanoRepository->searchNewEstruturalAccounts($ultimoEstrut,$ano);
        $contaPlano = $resultContaPlano->first();
       
        if ($contaPlano) {
            $c60_estrut = $contaPlano->c60_estrut;
            return $c60_estrut;
        } else {
            return false;
        }
    }
 
}
