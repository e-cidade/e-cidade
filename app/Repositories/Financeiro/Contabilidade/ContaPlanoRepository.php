<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlano;
use App\Models\Financeiro\Contabilidade\ConPlano;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Capsule\Manager as DB;

class ContaPlanoRepository implements ContaPlanoRepositoryInterface
{
    private ConPlano $model;
   
    public function __construct()
    {
        $this->model = new ConPlano;
    }


    public function saveByContaPlano(ContaPlano $dadosContaPlano): ?ConPlano
    { 
        
        $dados = [
            "c60_codcon"                  => $dadosContaPlano->getC60Codcon(),
            "c60_anousu"                  => $dadosContaPlano->getC60Anousu(),
            "c60_estrut"                  => $dadosContaPlano->getC60Estrut(),
            "c60_descr"                   => $dadosContaPlano->getC60Descr(),
            "c60_finali"                  => $dadosContaPlano->getC60Finali(),
            "c60_codsis"                  => $dadosContaPlano->getC60Codsis(),
            "c60_codcla"                  => $dadosContaPlano->getC60Codcla(),
            "c60_consistemaconta"         => $dadosContaPlano->getC60Consistemaconta(),
            "c60_identificadorfinanceiro" => $dadosContaPlano->getC60Identificadorfinanceiro(),
            "c60_naturezasaldo"           => $dadosContaPlano->getC60Naturezasaldo(),
            "c60_funcao"                  => $dadosContaPlano->getC60Funcao(),
            "c60_tipolancamento"          => $dadosContaPlano->getC60Tipolancamento(),
            "c60_subtipolancamento"       => $dadosContaPlano->getC60Subtipolancamento(),
            "c60_desdobramneto"           => $dadosContaPlano->getC60Desdobramneto(),
            "c60_nregobrig"               => $dadosContaPlano->getC60Nregobrig(),
            "c60_cgmpessoa"               => $dadosContaPlano->getC60Cgmpessoa(),
            "c60_naturezadareceita"       => $dadosContaPlano->getC60Naturezadareceita(),
            "c60_infcompmsc"              => $dadosContaPlano->getC60Infcompmsc()  
        ];
        
        return $this->model->create($dados);
    }

    public function update(int $sequencialcontaplano, array $dadosContaplano): bool
    {

        return DB::table('conplano')->where('c60_codcon',$sequencialcontaplano)->update($dadosContaplano);
    }

    public function delete(int $sequencialcontaplano): bool
    {

        return DB::table('conplano')->where('c60_codcon',$sequencialcontaplano)->delete();

    }


    public function searchContaPlanoAccounts(): ?Collection
    {
        $result = $this->model
                ->orderBy('c60_codcon', 'desc')
                ->limit(1)
                ->get(['c60_codcon']);

        return $result;
    }

    public function searchEstruturalAccounts(int $ultimoEstrut,int $ano): ?Collection
    {
 
        $result = $this->model
                ->where('c60_estrut', 'like', "$ultimoEstrut%")
                ->where('c60_anousu', $ano)
                ->orderBy('c60_codcon', 'desc')
                ->limit(1)
                ->get(['c60_estrut']);

        return $result;
    }

    public function searchNewEstruturalAccounts(int $ultimoEstrut,int $ano): ?Collection
    {
        
        $result = $this->model
                ->where('c60_estrut', $ultimoEstrut)
                ->where('c60_anousu', $ano)
                ->orderBy('c60_codcon', 'desc')
                ->limit(1)
                ->get(['c60_estrut']);

        return $result;
    }

    public function setvalContaPlanoAccounts(int $sequencialcontaplano): bool
    {

        $result = DB::select("SELECT setval('conplano_c60_codcon_seq', ?, true) AS new_value", [$sequencialcontaplano]);

        if (!empty($result) && isset($result[0]->new_value)) {
            return true;
        }
    
        return false;   
    }

}
 