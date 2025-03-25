<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Solicitemregistropreco;
use Illuminate\Support\Facades\DB;
use cl_solicitemregistropreco;
class SolicitemregistroprecoRepository
{
    private Solicitemregistropreco $model;

    public function __construct()
    {
        $this->model = new Solicitemregistropreco();
    }

    public function update(int $pc57_sequencial, array $dados): bool
    {
        return DB::table('solicitemregistropreco')->where('pc57_sequencial',$pc57_sequencial)->update($dados);
    }

    public function updateOnSolicitem(int $pc57_solicitem, array $dados): bool
    {
        return DB::table('solicitemregistropreco')->where('pc57_solicitem',$pc57_solicitem)->update($dados);
    }

    public function insert($dados): Solicitemregistropreco
    {
        $pc57_sequencial = $this->model->getNextval();
        $dados['pc57_sequencial'] =  $pc57_sequencial;
        return $this->model->create($dados);
    }

    public function excluir($pc11_codigo)
    {
        $sql = "DELETE FROM solicitemregistropreco WHERE pc57_solicitem = $pc11_codigo";
        return DB::statement($sql);
    }
}
