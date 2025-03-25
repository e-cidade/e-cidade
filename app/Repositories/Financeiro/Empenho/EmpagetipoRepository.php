<?php

namespace App\Repositories\Financeiro\Empenho;

use App\Domain\Financeiro\Empenho\Empenhopagetipo;
use App\Models\Financeiro\Empenho\Empagetipo;
use App\Repositories\Financeiro\Empenho\EmpagetipoRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EmpagetipoRepository implements EmpagetipoRepositoryInterface
{
    private Empagetipo $model;

    public function __construct()
    {
        $this->model = new Empagetipo;
    }


    public function saveByEmpagetipo(Empenhopagetipo $dadosEmpagetipo): ?Empagetipo
    {

        $dados = [
            "e83_descr"                   => $dadosEmpagetipo->getE83Descr(),
            "e83_conta"                   => $dadosEmpagetipo->getE83Conta(),
            "e83_codmod"                  => $dadosEmpagetipo->getE83Codmod(),
            "e83_convenio"                => $dadosEmpagetipo->getE83Convenio(),
            "e83_sequencia"               => $dadosEmpagetipo->getE83Sequencia(),
            "e83_codigocompromisso"       => $dadosEmpagetipo->getE83Codigocompromisso(),

        ];

        return $this->model->create($dados);
    }

    public function update(int $sequencialempagetipo, array $dadosempagetipo): bool
    {

        return DB::table('empagetipo')->where('e83_conta',$sequencialempagetipo)->update($dadosempagetipo);
    }

    public function delete(int $sequencialempagetipo): bool
    {

        return DB::table('empagetipo')->where('e83_codtipo',$sequencialempagetipo)->delete();
    }

}
