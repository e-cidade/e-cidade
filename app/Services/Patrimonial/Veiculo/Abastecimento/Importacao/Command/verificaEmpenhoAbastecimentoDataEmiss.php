<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command;

use Illuminate\Support\Facades\DB;

class verificaEmpenhoAbastecimentoDataEmiss
{
    public function execute(string $codEmp, string $dataMovimentacao): ?array
    {
        $codEmp = explode('/',$codEmp);

        $sql = $this->getEmpenhoAbastecimentoDataEmiss($codEmp[0],$codEmp[1],$dataMovimentacao);

        $result = DB::select(DB::raw($sql));

        return $result;

    }

    private function getEmpenhoAbastecimentoDataEmiss(string $codEmp, int $anoEmp, string $dataMovimentacao):string
    {

        $sql = "
            select * from empempenho where e60_codemp = '{$codEmp}' and e60_anousu = '{$anoEmp}' and empempenho.e60_emiss <= '{$dataMovimentacao}'
        ";

        return $sql;
    }
}
