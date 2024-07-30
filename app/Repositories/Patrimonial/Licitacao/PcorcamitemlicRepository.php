<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_pcorcamitemlic;
use App\Models\Patrimonial\Licitacao\Pcorcamitemlic;
use Illuminate\Database\Capsule\Manager as DB;

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
}
