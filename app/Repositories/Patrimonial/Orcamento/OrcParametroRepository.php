<?php
namespace App\Repositories\Patrimonial\Orcamento;

use App\Models\Patrimonial\Orcamento\OrcParametro;
use App\Repositories\Contracts\Patrimonial\Orcamento\OrcParametroRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class OrcParametroRepository implements OrcParametroRepositoryInterface{
    private OrcParametro $model;

    public function __construct()
    {
        $this->model = new OrcParametro();
    }

    public function getDados(?int $anousu):?object
    {
        $query = $this->model->query();

        $query->where('o50_anousu', $anousu);
        return $query->get()->first();
    }

}
