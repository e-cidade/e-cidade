<?php

namespace App\Services\Financeiro\Tesouraria\Command;
use App\Repositories\Financeiro\Tesouraria\ContaBancariaRepository;
use App\Repositories\Financeiro\Tesouraria\ContaBancariaRepositoryInterface;

class InsertContaBancariaCommand
{
  
    /**
     * @var ContaBancariaRepositoryInterface
     */
    private ContaBancariaRepositoryInterface $contaBancariaRepository;

    public function __construct(
    ) {
        $this->contaBancariaRepository = new ContaBancariaRepository;
    }

    /**
     *
     * @return void
     */
    public function execute($contabancarianovo)
    {
        $resultContaBancaria = $this->contaBancariaRepository->saveByContaBancaria($contabancarianovo);
        if (!$resultContaBancaria) {
            throw new \Exception("Erro ao inserir conta bancaria");
        }
    }

    public function searchSequentialAccounts()
    {
        $resultContaBancaria = $this->contaBancariaRepository->searchSequentialAccounts();
        $contaBancaria = $resultContaBancaria->first();

        if ($contaBancaria) {
            $db83_sequencial = $contaBancaria->db83_sequencial;
            return $db83_sequencial;
        } else {
            throw new \Exception("Erro ao inserir conta bancaria");
        }
    }

    public function checkAccountExists($sequencial)
    {
        $resultContaBancaria = $this->contaBancariaRepository->checkAccountExists($sequencial);
        $contaBancaria = $resultContaBancaria->first();

        if ($contaBancaria) {
            $db83_sequencial = $contaBancaria->db83_sequencial;
            return $db83_sequencial;
        } else {
            throw new \Exception("Conta Bancaria Não Existe");
        }
    }

    public function checkAllTables($sequencial)
    {
        $resultContaBancaria = $this->contaBancariaRepository->checkAllTables($sequencial);
        $contaBancaria = $resultContaBancaria->first();
        
        if ($contaBancaria) {
            return $contaBancaria;
        } else {
            throw new \Exception("Conta Bancaria Não Existe");
        }
    }

    public function checkRepeated($agencia, $conta, $tipoconta, $fonte,$nroseqaplicacao)
    {
        $resultContaBancaria = $this->contaBancariaRepository->checkRepeated($agencia, $conta, $tipoconta, $fonte, $nroseqaplicacao);
        $contaBancaria = $resultContaBancaria->first();
        
        if ($contaBancaria) {
            return $contaBancaria;
        } else {
            throw new \Exception("Conta Bancaria Não Existe");
        }
    }

}
