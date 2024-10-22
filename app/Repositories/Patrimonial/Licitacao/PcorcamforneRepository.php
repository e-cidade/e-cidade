<?php

namespace App\Repositories\Patrimonial\Licitacao;
use cl_pcorcamforne;
use App\Models\Patrimonial\Licitacao\Pcorcamforne;
use Illuminate\Database\Capsule\Manager as DB;

class PcorcamforneRepository
{
    private Pcorcamforne $model;

    public function __construct()
    {
        $this->model = new Pcorcamforne();
    }

    public function getfornecedoreslicitacao($l20_codigo)
    {
        $clpcorcamforne = new cl_pcorcamforne();
        $sql = $clpcorcamforne->queryfornecedores($l20_codigo);
        return DB::select($sql);
    }
}

