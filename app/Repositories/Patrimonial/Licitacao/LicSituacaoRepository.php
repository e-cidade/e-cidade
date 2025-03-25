<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicSituacao;
use App\Repositories\Contracts\Patrimonial\Licitacao\LicSituacaoRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;

class LicSituacaoRepository implements LicSituacaoRepositoryInterface
{
    private LicSituacao $model;

    public function __construct()
    {
        $this->model = new LicSituacao();
    }

    public function getDados():?array
    {
        $query = $this->model->query();
        return $query->get()->toArray();
    }
}
