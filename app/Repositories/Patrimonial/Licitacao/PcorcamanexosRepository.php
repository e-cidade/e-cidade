<?php

namespace App\Repositories\Patrimonial\Licitacao;
use App\Models\Patrimonial\Licitacao\Pcorcamanexos;
use Illuminate\Support\Facades\DB;

class PcorcamanexosRepository
{
    private Pcorcamanexos $model;

    public function __construct()
    {
        $this->model = new Pcorcamanexos();
    }

    public function insert(array $dados_arquivo)
    {
        return $this->model->insert($dados_arquivo);
    }

    public function getDocumentos($codorc)
    {
        $sql = "SELECT * FROM licitacao.pcorcamanexos WHERE pc98_codorc = $codorc";
        return DB::select($sql);
    }

    public function excluir($sequencial)
    {
        $documento = Pcorcamanexos::find($sequencial);
        return $documento->delete();
    }

    public function getOid($sequencial)
    {
        $sql = "SELECT pc98_anexo FROM licitacao.pcorcamanexos WHERE pc98_sequencial = $sequencial";
        return DB::select($sql); 
    }

    public function getnome($sequencial)
    {
        $sql = "SELECT pc98_nomearquivo FROM licitacao.pcorcamanexos WHERE pc98_sequencial = $sequencial";
        return DB::select($sql); 
    }

    

}

