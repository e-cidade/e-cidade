<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\DecretoPregao;
use cl_liclicita;
use App\Models\Patrimonial\Licitacao\Liclicita;
use Illuminate\Support\Facades\DB;

class DecretoPregaoRepository
{
    private DecretoPregao $model;

    public function __construct()
    {
        $this->model = new DecretoPregao();
    }

    public function getTotalDecretoPregao(){
        return $this->model->query()->count();
    }
}
