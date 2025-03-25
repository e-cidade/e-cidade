<?php
namespace App\Repositories\Patrimonial\Orcamento;

use App\Models\Patrimonial\Orcamento\OrcElemento;
use App\Models\Patrimonial\Orcamento\OrcParametro;
use App\Repositories\Contracts\Patrimonial\Orcamento\OrcElementoRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class OrcElementoRepository implements OrcElementoRepositoryInterface{
    private OrcElemento $model;

    public function __construct()
    {
        $this->model = new OrcElemento();
    }

    public function getDadosElemento(?int $o56_codele, string $anousu):?object
    {
        return $this->model
            ->where('o56_codele', $o56_codele)
            ->where('o56_anousu', $anousu)
            ->first();
    }

}
