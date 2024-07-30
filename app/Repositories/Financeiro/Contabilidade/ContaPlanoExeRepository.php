<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoExe;
use App\Models\Financeiro\Contabilidade\ConPlanoExe;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoExeRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class ContaPlanoExeRepository implements ContaPlanoExeRepositoryInterface
{
    private ConPlanoExe $model;
   
    public function __construct()
    {
        $this->model = new ConPlanoExe;
    }


    public function saveByContaPlanoExe(ContaPlanoExe $dadosContaPlanoExe): ?ConPlanoExe
    { 
        
        $dados = [
            "c62_anousu"                  => $dadosContaPlanoExe->getC62Anousu(),
            "c62_reduz"                   => $dadosContaPlanoExe->getC62Reduz(),
            "c62_codrec"                  => $dadosContaPlanoExe->getC62Codrec(),
            "c62_vlrcre"                  => $dadosContaPlanoExe->getC62Vlrcre(),
            "c62_vlrdeb"                  => $dadosContaPlanoExe->getC62Vlrdeb()
        
        ];
        
        return $this->model->create($dados);
    }

    public function update(int $sequencialcontaplanoexe, array $dadosContaPlanoExe): bool
    {

        return DB::table('conplanoexe')->where('c62_reduz',$sequencialcontaplanoexe)->update($dadosContaPlanoExe);
    }

    public function delete(int $sequencialcontaplanoexe): bool
    {

        return DB::table('conplanoexe')->where('c62_reduz',$sequencialcontaplanoexe)->delete();

    }


  
}
 