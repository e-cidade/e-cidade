<?php

namespace App\Repositories\Patrimonial\Licitacao\Sicom\Ano2025;

use Illuminate\Database\Capsule\Manager as DB;

class ArquivoIdeRepository
{
    public function getDados()
    {
        return DB::select(
            "
            SELECT 
                db21_codigomunicipoestado AS codmunicipio,
                  cgc::varchar cnpjmunicipio,
                si09_tipoinstit AS tipoorgao,
                si09_codorgaotce AS codorgao
            FROM db_config
            LEFT JOIN public.infocomplementaresinstit 
                ON si09_instit = " . db_getsession("DB_instit") . "
            WHERE codigo = " . db_getsession("DB_instit")
        );
    }
}
