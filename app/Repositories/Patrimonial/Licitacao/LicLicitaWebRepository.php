<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicLicitaWeb;
use App\Repositories\Contracts\Patrimonial\Licitacao\LicLicitaWebRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class LicLicitaWebRepository implements LicLicitaWebRepositoryInterface
{
    private LicLicitaWeb $model;

    public function __construct()
    {
        $this->model = new LicLicitaWeb();
    }

    public function getDadosByFilter(int $l29_liclicita):?array
    {
        $query = $this->model->query();

        $query->where('l29_liclicita', $l29_liclicita);

        return $query->get()->toArray();
    }
}
