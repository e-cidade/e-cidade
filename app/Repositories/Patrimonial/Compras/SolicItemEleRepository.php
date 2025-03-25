<?php
namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\SolicItemEle;
use App\Repositories\Contracts\Patrimonial\Compras\SolicItemEleRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class SolicItemEleRepository implements SolicItemEleRepositoryInterface{
    private SolicItemEle $model;

    public function __construct()
    {
        $this->model = new SolicItemEle();
    }

    public function getDadosCodEle(int $pc18_solicitem):?object
    {
        return $this->model
        ->where('pc18_solicitem', $pc18_solicitem)
        ->first();
    }

}
