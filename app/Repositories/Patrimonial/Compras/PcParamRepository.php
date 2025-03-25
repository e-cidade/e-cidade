<?php
namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\PcParam;
use App\Repositories\Contracts\Patrimonial\Compras\PcParamRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class PcParamRepository implements PcParamRepositoryInterface{
    private PcParam $model;

    public function __construct()
    {
        $this->model = new PcParam();
    }

    public function getDados(?int $instit):?object
    {
        $query = $this->model->query();

        $query->where('pc30_instit', $instit);
        return $query->get()->first();
    }

}
