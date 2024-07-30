<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command;

use Illuminate\Database\Capsule\Manager as DB;

class verificaEmpenhoInscritoemRestoaPagar
{
    public function execute(string $codEmp): bool
    {
        $codEmp = explode('/',$codEmp);
        $anoAtual = date('Y');
        $anoEmp = $codEmp[1];

        if($anoEmp < $anoAtual){

            $sql = $this->getEmpenhoAbastecimentoRestoaPagar($codEmp[0],$anoEmp);

            $result = DB::select(DB::raw($sql));

            // Retorna true se houver empenho de abastecimento incrito no resto a pagar para o ano especificado
            return !empty($result);
        }

        return true;
    }

    private function getEmpenhoAbastecimentoRestoaPagar(string $codEmp, int $anoEmp):string
    {

        $sql = "
            SELECT *
                FROM empempenho
                inner join empresto on e91_numemp = e60_numemp
                WHERE e60_codemp = '{$codEmp}'
                    AND e60_anousu = {$anoEmp}
        ";

        return $sql;
    }
}
