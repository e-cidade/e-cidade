<?php
namespace App\Repositories\Patrimonial;

use App\Models\VeicParam;

class VeicParamRepository
{
    private VeicParam $veicParam;

    public function __construct()
    {
        $this->veicParam = new VeicParam();
    }

    public function validarAbastecimentoPorEmpenho()
    {
        $paramentros = $this->veicParam->where('ve50_instit', db_getsession("DB_instit"))->first(['ve50_abastempenho','ve50_datacorte']);

        if ($paramentros->ve50_abastempenho === 1) {
            return $paramentros;
        }
        return false;
    }

    public function getDataCorte(): string
    {
        $paramentros = $this->veicParam->where('ve50_instit', db_getsession("DB_instit"))->first(['ve50_datacorte']);
        return $paramentros->ve50_datacorte;
    }
}
