<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\Liclicitemlote;
use Illuminate\Database\Capsule\Manager as DB;

class LiclicitemLoteRepository
{

    private Liclicitemlote $model;

    public function __construct()
    {
        $this->model = new Liclicitemlote();
    }

    public function insert(array $dados): ?Liclicitemlote
    {
        $l04_codigo = $this->model->getNextval();
        $dados['l04_codigo'] = $l04_codigo;
        return $this->model->create($dados);
    }

    public function delete($l04_liclicitem)
    {
        $sql = "DELETE FROM liclicitemlote WHERE l04_liclicitem = $l04_liclicitem";
        return DB::statement($sql);
    }
}
