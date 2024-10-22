<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Pcdotac;
use Illuminate\Database\Capsule\Manager as DB;

class PcdotacRepository
{
    private Pcdotac $model;

    public function __construct()
    {
        $this->model = new Pcdotac();
    }


    public function update(int $pc13_sequencial, array $dados): bool
    {
        return DB::table('pcdotac')->where('pc13_sequencial',$pc13_sequencial)->update($dados);
    }

    public function insert($dados): Pcdotac
    {
        $pc13_sequencial = $this->model->getNextval();
        $dados['pc13_sequencial'] =  $pc13_sequencial;
        return $this->model->create($dados);
    }

    public function getDotacoesItem(int $pc11_codigo)
    {
        $sql = "select * from pcdotac where pc13_codigo = $pc11_codigo";
        return DB::select($sql);
    }
    public function excluir(int $pc11_codigo): bool
    {
        $sql = "DELETE FROM pcdotac WHERE pc13_codigo IN ($pc11_codigo)";
        return DB::statement($sql);
    }
}
