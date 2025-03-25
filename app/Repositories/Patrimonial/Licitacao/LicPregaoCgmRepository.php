<?php

namespace App\Repositories\Patrimonial\Licitacao;

use App\Models\Patrimonial\Licitacao\LicPregaoCgm;
use App\Models\Patrimonial\Licitacao\PccfEditalNum;
use Illuminate\Support\Facades\DB;

class LicPregaoCgmRepository{
    private LicPregaoCgm $model;

    public function __construct()
    {
        $this->model = new LicPregaoCgm();
    }

    public function getModalidadePregao($equipepregao){
        $query = "
            SELECT 
                mapoio.l46_tipo, 
                pregoeiro.l46_tipo 
            FROM
                (
                    SELECT 
                        l46_licpregao, 
                        l46_tipo
                    FROM 
                        licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                    WHERE l46_tipo = 2
                ) AS mapoio
                INNER JOIN (
                    SELECT 
                        l46_licpregao, 
                        l46_tipo
                    FROM 
                        licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                    WHERE l46_tipo = 6
                ) AS pregoeiro ON pregoeiro.l46_licpregao = mapoio.l46_licpregao
            WHERE 
                pregoeiro.l46_licpregao = ?
            LIMIT 1
        ";

        return DB::select($query, [$equipepregao]);
    }

    public function getModalidadeOutros($equipepregao){
        $query = "
            SELECT 
                mapoio.l46_tipo, 
                presidente.l46_tipo, 
                secretario.l46_tipo 
            FROM
                (
                    SELECT 
                        l46_licpregao, 
                        l46_tipo
                    FROM 
                        licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                    WHERE l46_tipo = 2
                ) AS mapoio
                INNER JOIN (
                    SELECT 
                        l46_licpregao, 
                        l46_tipo
                    FROM 
                        licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                    WHERE l46_tipo = 3
                ) AS presidente ON presidente.l46_licpregao = mapoio.l46_licpregao
                INNER JOIN (
                    SELECT 
                        l46_licpregao, 
                        l46_tipo
                    FROM 
                        licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                    WHERE l46_tipo = 4
                ) AS secretario ON secretario.l46_licpregao = presidente.l46_licpregao
                WHERE 
                    secretario.l46_licpregao = ?
                LIMIT 1
        ";

        return DB::select($query, [$equipepregao]);
        
    }
}
