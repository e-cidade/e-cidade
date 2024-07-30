<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_liclicita;
use App\Models\Patrimonial\Licitacao\Liclicita;
use Illuminate\Database\Capsule\Manager as DB;

class LiclicitaRepository
{
    private Liclicita $model;

    public function __construct()
    {
        $this->model = new Liclicita();
    }


    public function getLicitacao($l20_codigo): object
    {
        $clliclicita = new cl_liclicita();
        $sql = $clliclicita->sql_query_file($l20_codigo);
        $rsLicitacao = DB::select($sql);
        return $rsLicitacao[0];
    }
}
