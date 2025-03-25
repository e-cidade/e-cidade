<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command;

use Illuminate\Support\Facades\DB;

class GetBaixaVeiculo
{
    /**
     * Undocumented function
     *
     * @param integer $codigoVeiculo
     * @param string $dataBusca
     * @return array|null
     */
    public function execute(int $codigoVeiculo, string $dataBusca): ?array
    {
        $sql =  $this->getBaixa($codigoVeiculo, $dataBusca);

        $result = DB::select( DB::raw($sql));

        return $result;
    }

    private function getBaixa(int $codigoVeiculo, string $data)
    {
        $sql = "select * from veicbaixa where ve04_veiculo = {$codigoVeiculo} and ve04_data >= '{$data}'";
        return $sql;
    }
}
