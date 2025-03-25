<?php

namespace App\Repositories\Financeiro\Contabilidade;

use App\Domain\Financeiro\Contabilidade\ContaPlanoReduz;
use App\Models\Financeiro\Contabilidade\ConPlanoReduz;
use App\Repositories\Financeiro\Contabilidade\ContaPlanoReduzRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ContaPlanoReduzRepository implements ContaPlanoReduzRepositoryInterface
{
    private ConPlanoReduz $model;

    public function __construct()
    {
        $this->model = new ConPlanoReduz;
    }


    public function saveByContaPlanoReduz(ContaPlanoReduz $dadosContaPlanoReduz): ?ConPlanoReduz
    {

        $dados = [
            "c61_codcon"                  => $dadosContaPlanoReduz->getC61Codcon(),
            "c61_anousu"                  => $dadosContaPlanoReduz->getC61Anousu(),
            "c61_reduz"                   => $dadosContaPlanoReduz->getC61Reduz(),
            "c61_instit"                  => $dadosContaPlanoReduz->getC61Instit(),
            "c61_codigo"                  => $dadosContaPlanoReduz->getC61Codigo(),
            "c61_contrapartida"           => $dadosContaPlanoReduz->getC61Contrapartida(),
            "c61_codtce"                  => $dadosContaPlanoReduz->getC61Codtce(),

        ];

        return $this->model->create($dados);
    }

    public function update(int $sequencialcontaplanoreduz, array $dadosContaplanoreduz): bool
    {

        return DB::table('conplanoreduz')->where('c61_codcon',$sequencialcontaplanoreduz)->update($dadosContaplanoreduz);

    }

    public function delete(int $sequencialcontaplanoreduz): bool
    {
        return DB::table('conplanoreduz') ->where('c61_codcon',$sequencialcontaplanoreduz)->delete();

    }

    public function searchContaPlanoReduzAccounts(): ?Collection
    {
        $result = $this->model
                ->orderBy('c61_reduz', 'desc')
                ->limit(1)
                ->get(['c61_reduz']);

        return $result;
    }

    public function setvalContaPlanoReduzAccounts(int $sequencialcontaplanoreduz): bool
    {

        $result = DB::select("SELECT setval('conplanoreduz_c61_reduz_seq', ?, true) AS new_value", [$sequencialcontaplanoreduz]);

        if (!empty($result) && isset($result[0]->new_value)) {
            return true;
        }

        return false;
    }


}
