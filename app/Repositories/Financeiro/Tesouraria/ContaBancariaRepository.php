<?php

namespace App\Repositories\Financeiro\Tesouraria;

use App\Domain\Financeiro\Tesouraria\ContaBancarias;
use App\Models\Financeiro\Tesouraria\ContaBancaria;
use App\Repositories\Financeiro\Tesouraria\ContaBancariaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Capsule\Manager as DB;

class ContaBancariaRepository implements ContaBancariaRepositoryInterface
{
    private ContaBancaria $model;
   
    public function __construct()
    {
        $this->model = new ContaBancaria;
    }

    public function saveByContaBancaria(ContaBancarias $dadosContaBancaria): ?ContaBancaria
    {
       
        $dados = [
            "db83_descricao"          => $dadosContaBancaria->getDb83Descricao(), 
            "db83_bancoagencia"       => $dadosContaBancaria->getDb83Bancoagencia(),         
            "db83_conta"              => $dadosContaBancaria->getDb83Conta(),
            "db83_dvconta"            => $dadosContaBancaria->getDb83DvConta(), 
            "db83_identificador"      => $dadosContaBancaria->getDb83Identificador(), 
            "db83_codigooperacao"     => $dadosContaBancaria->getDb83Codigooperacao(), 
            "db83_tipoconta"          => $dadosContaBancaria->getDb83Tipoconta(), 
            "db83_contaplano"         => $dadosContaBancaria->getDb83Contaplano(), 
            "db83_convenio"           => $dadosContaBancaria->getDb83Convenio(),
            "db83_tipoaplicacao"      => $dadosContaBancaria->getDb83Tipoaplicacao(), 
            "db83_numconvenio"        => $dadosContaBancaria->getDb83Numconvenio(),
            "db83_nroseqaplicacao"    => $dadosContaBancaria->getDb83Nroseqaplicacao(),
            "db83_codigoopcredito"    => $dadosContaBancaria->getDb83Codigoopcredito(),
    
        ];
        
        return $this->model->create($dados);
    }
   
    public function update(int $sequencialcontabancaria, array $dadosContaBancaria): bool
    {

        return DB::table('contabancaria')->where('db83_sequencial',$sequencialcontabancaria)->update($dadosContaBancaria);

    }

    public function delete(int $sequencialcontabancaria): bool
    {

        return DB::table('contabancaria')->where('db83_sequencial',$sequencialcontabancaria)->delete();

    }

    public function searchSequentialAccounts(): ?Collection
    {
        $result = $this->model
                ->orderBy('db83_sequencial', 'desc')
                ->limit(1)
                ->get(['db83_sequencial']);

        return $result;
    }

    public function checkAccountExists(int $sequencial): ?Collection
    {
        $result = $this->model
                ->where('db83_sequencial', $sequencial)
                ->limit(1)
                ->get(['db83_sequencial']);

        return $result;
    }

    public function checkAllTables(int $sequencial): ?Collection
    {
        $result = $this->model
            ->join('conplanocontabancaria', 'contabancaria.db83_sequencial', '=', 'conplanocontabancaria.c56_contabancaria')
            ->join('conplano', 'conplanocontabancaria.c56_codcon', '=', 'conplano.c60_codcon')
            ->join('conplanoreduz', 'conplanocontabancaria.c56_codcon', '=', 'conplanoreduz.c61_codcon')
            ->join('saltes', 'saltes.k13_reduz', '=', 'conplanoreduz.c61_reduz')
            ->join('conplanoconta', 'conplanoconta.c63_codcon', '=', 'conplanocontabancaria.c56_codcon')
            ->join('empagetipo', 'empagetipo.e83_conta', '=', 'saltes.k13_reduz')
            ->join('conplanoexe', 'conplanoexe.c62_reduz', '=', 'saltes.k13_reduz')
            ->join('conplanocontacorrente', 'conplanocontacorrente.c18_codcon', '=', 'conplanocontabancaria.c56_codcon')
            ->where('contabancaria.db83_sequencial', $sequencial)
            ->limit(1)
            ->get([
                'contabancaria.*',
                'conplanocontabancaria.*',
                'conplano.*',
                'conplanoreduz.*',
                'saltes.*',
                'conplanoconta.*',
                'empagetipo.*',
                'conplanoexe.*',
                'conplanocontacorrente.*'
            ]);
           
        return $result;
    }

}
 