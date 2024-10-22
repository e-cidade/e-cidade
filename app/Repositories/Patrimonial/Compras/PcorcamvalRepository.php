<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Pcorcamval;
use Illuminate\Database\Capsule\Manager as DB;

class PcorcamvalRepository
{
    private Pcorcamval $model;

    public function __construct()
    {
        $this->model = new Pcorcamval();
    }

    public function insert($dados): Pcorcamval
    {
        return $this->model->create($dados);
    }

    public function update(int $pc23_orcamitem, array $dados): bool
    {
        return DB::table('pcorcamval')->where('pc23_orcamitem',$pc23_orcamitem)->where('pc23_orcamforne',$dados->pc23_orcamforne)->update($dados);
    }

    public function excluir(int $pc23_orcamitem): bool
    {
        $sql = "DELETE FROM pcorcamval WHERE pc23_orcamitem IN ($pc23_orcamitem)";
        return DB::statement($sql);
    }
}
