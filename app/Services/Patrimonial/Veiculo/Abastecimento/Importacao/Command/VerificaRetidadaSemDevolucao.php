<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command;

use Illuminate\Database\Capsule\Manager as DB;

class VerificaRetidadaSemDevolucao
{
    /**
     * Undocumented function
     *
     * @param integer $codigoVeiculo
     * @return array|null
     */
    public function execute(int $codigoVeiculo): ?array
    {
        $sql = $this->getRetiradaSemDevolucaoSQL($codigoVeiculo);

        $result = DB::select(DB::raw($sql));

        return $result;
    }

    private function getRetiradaSemDevolucaoSQL(int $codigoVeiculo):string
    {
        $sql = "select * from veicretirada where ve60_codigo not in (select ve61_veicretirada from veicdevolucao) and ve60_veiculo = $codigoVeiculo";

        return $sql;
    }

}
