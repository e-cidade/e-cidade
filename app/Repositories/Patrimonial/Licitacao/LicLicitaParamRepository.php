<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\AmparoLegal;
use App\Models\Patrimonial\Licitacao\LicLicitaParam;
use App\Repositories\Contracts\Patrimonial\Licitacao\AmparoLegalRepositoryInterface;
use App\Repositories\Contracts\Patrimonial\Licitacao\LicLicitaParamRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class LicLicitaParamRepository implements LicLicitaParamRepositoryInterface
{
    private LicLicitaParam $model;

    public function __construct()
    {
        $this->model = new LicLicitaParam();
    }

    public function getDados(?int $instit):?object
    {
        $query = $this->model->query();

        $query->where('l12_instit', $instit);
        return $query->get()->first();
    }
}
