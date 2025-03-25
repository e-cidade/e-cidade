<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMigrationCreateValuesHistoricomaterialitens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            WITH itens_unidades_de_medida_utilizada AS (
                SELECT 
                    pc01_codmater AS cod_mater,
                    unidades_utilizadas.mat_unid
                FROM pcmater
                LEFT JOIN (
                    SELECT 
                        ac20_pcmater AS cod_mater,
                        ac20_matunid::INT AS mat_unid
                    FROM acordoitem
                    
                    UNION ALL
                    
                    SELECT 
                        e55_item AS cod_mater,
                        e55_unid::INT AS mat_unid
                    FROM empautitem
                    WHERE EXISTS (
                        SELECT 1 FROM matunid WHERE m61_codmatunid = e55_unid
                    )

                    UNION ALL

                    SELECT 
                        pc16_codmater AS cod_mater,
                        pc17_unid::INT AS mat_unid
                    FROM solicitempcmater
                    INNER JOIN solicitemunid ON pc17_codigo = pc16_solicitem
                ) AS unidades_utilizadas 
                ON unidades_utilizadas.cod_mater = pc01_codmater
            )

            UPDATE pcmater
            SET pc01_unid = COALESCE(novas_unidades.m61_codmatunid, 301)
            FROM itens_unidades_de_medida_utilizada
            LEFT JOIN matunid AS cod_sicom_da_unidade_utilizada 
                ON itens_unidades_de_medida_utilizada.mat_unid = cod_sicom_da_unidade_utilizada.m61_codmatunid
            LEFT JOIN matunid AS novas_unidades 
                ON cod_sicom_da_unidade_utilizada.m61_codsicom = novas_unidades.m61_codsicom
                AND novas_unidades.m61_codmatunid BETWEEN 300 AND 1000
            WHERE itens_unidades_de_medida_utilizada.cod_mater = pcmater.pc01_codmater
            AND pcmater.pc01_unid IS NULL
        ");

        DB::statement("
            DELETE FROM historicomaterial
        ");

        DB::statement("
            SELECT setval('historicomaterial_db150_sequencial_seq', 1)
        ");

        DB::statement("    
            INSERT INTO historicomaterial
            SELECT 
                nextval('historicomaterial_db150_sequencial_seq') AS db150_sequencial,
                10 AS db150_tiporegistro,
                CONCAT(pc01_codmater, pc01_unid)::BIGINT AS db150_coditem,
                pc01_codmater AS db150_pcmater,
                CONCAT_WS('. ', pc01_descrmater, COALESCE(pc01_complmater, '')) AS db150_dscitem,
                m61_descr AS db150_unidademedida,
                1 AS db150_tipocadastro,
                NULL AS db150_justificativaalteracao,
                TO_CHAR(pc01_data, 'MM')::INT AS db150_mes,
                pc01_data AS db150_data,
                pc01_instit AS db150_instit,
                pc01_unid AS db150_codunid,
                FALSE AS db150_status
            FROM pcmater
            INNER JOIN matunid ON pc01_unid = m61_codmatunid
            WHERE pc01_unid IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
