<?php

namespace App\Repositories\Patrimonial\Licitacao;
use App\Models\Patrimonial\Licitacao\Licpropostavinc;
use Illuminate\Support\Facades\DB;

class LicpropostavincRepository
{
    private Licpropostavinc $model;

    public function __construct()
    {
        $this->model = new Licpropostavinc();
    }

    public function insert(array $dados): ?Licpropostavinc
    {

        $l223_codigo = $this->model->getNextval();
        $dados['l223_codigo'] = $l223_codigo;
        return $this->model->create($dados);
    }

    public function delete($l223_codigo)
    {
        $sql = "DELETE FROM licpropostavinc WHERE l223_codigo = $l223_codigo";
        return DB::statement($sql);
    }

}

