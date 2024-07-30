<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoContaCorrente;
use App\Models\Financeiro\Contabilidade\ConPlanoContaCorrente;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoContaCorrenteRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class ContaPlanoContaCorrenteRepository implements ContaPlanoContaCorrenteRepositoryInterface
{
    private ConPlanoContaCorrente $model;
   
    public function __construct()
    {
        $this->model = new ConPlanoContaCorrente;
    }


    public function saveByContaPlanoContaCorrente(ContaPlanoContaCorrente $dadosContaPlano): ?ConPlanoContaCorrente
    { 

        $dados = [
            "c18_codcon"            => $dadosContaPlano->getC18Codcon(),
            "c18_anousu"            => $dadosContaPlano->getC18Anousu(),
            "c18_contacorrente"     => $dadosContaPlano->getC18Contacorrente()
          
        ];
        
        return $this->model->create($dados);
    }

    public function delete(int $sequencialcontaplano): bool
    {
        return DB::table('conplanocontacorrente')->where('c18_codcon',$sequencialcontaplano)->delete();

    }


}
 