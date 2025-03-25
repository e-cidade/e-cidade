<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_pcorcamitemlic;
use App\Models\Patrimonial\Licitacao\Pcorcamitemlic;
use Illuminate\Support\Facades\DB;

class PcorcamitemlicRepository
{
    private Pcorcamitemlic $model;

    public function __construct()
    {
        $this->model = new Pcorcamitemlic();
    }
    public function getFornecedoresLicitacao($l20_codigo)
    {
        $clpcorcamitemlic = new cl_pcorcamitemlic();
        $sql = $clpcorcamitemlic->sql_query_fornecedores(null,"*",null,"l20_codigo = ".$l20_codigo);
        return DB::select($sql);
    }

    public function deleteByLiclicita($pc26_liclicitem){
        $sql = "DELETE FROM pcorcamitemlic WHERE pc26_liclicitem = $pc26_liclicitem";
        return DB::statement($sql);
    }

    public function deleteByLicitacao($l20_codigo){
        return $this->model
            ->whereIn('pc26_liclicitem', function($query) use ($l20_codigo){
                $query->select('l21_codigo')
                    ->from('liclicitem')
                    ->where('l21_codliclicita', $l20_codigo);
            })
            ->delete();
    }

}
