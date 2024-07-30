<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoContaBancaria;
use App\Models\Financeiro\Contabilidade\ConPlanoContaBancaria;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaBancariaRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class ContaPlanoContaBancariaRepository implements ContaPlanoContaBancariaRepositoryInterface
{
    private ConPlanoContaBancaria $model;
   
    public function __construct()
    {
        $this->model = new ConPlanoContaBancaria;
    }


    public function saveByContaPlanoContaBancaria(ContaPlanoContaBancaria $dadosContaPlano): ?ConPlanoContaBancaria
    { 
        
        $dados = [
            "c56_contabancaria"     => $dadosContaPlano->getC56Contabancaria(),
            "c56_codcon"            => $dadosContaPlano->getC56Codcon(),
            "c56_anousu"            => $dadosContaPlano->getC56Anousu()
          
        ];
        
        return $this->model->create($dados);
    }

    public function delete(int $sequencialcontabancaria): bool
    {

        return DB::table('conplanocontabancaria')->where('c56_contabancaria',$sequencialcontabancaria)->delete();

    }

}
 