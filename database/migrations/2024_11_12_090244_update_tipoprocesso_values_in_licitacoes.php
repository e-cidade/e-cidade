<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTipoprocessoValuesInLicitacoes extends Migration
{
    /**
     * Executar a migration.
     *
     * @return void
     */
    public function up()
    {
        // SQL bruto para atualizar os valores de l20_tipoprocesso
        DB::unprepared("
            UPDATE licitacao.liclicita
            SET l20_tipoprocesso = CASE 
                WHEN liclicita.l20_tipoprocesso = 101 THEN 1
                WHEN liclicita.l20_tipoprocesso = 100 THEN 2
                WHEN liclicita.l20_tipoprocesso = 102 THEN 3
                WHEN liclicita.l20_tipoprocesso = 103 THEN 4
                WHEN liclicita.l20_tipoprocesso = 0 THEN 
                    CASE 
                        WHEN cflicita.l03_pctipocompratribunal = 101 THEN 1
                        WHEN cflicita.l03_pctipocompratribunal = 100 THEN 2
                        WHEN cflicita.l03_pctipocompratribunal = 102 THEN 3
                        WHEN cflicita.l03_pctipocompratribunal = 103 THEN 4
                        ELSE liclicita.l20_tipoprocesso
                    END
            END
            FROM licitacao.cflicita
            WHERE liclicita.l20_codtipocom = cflicita.l03_codigo
            AND liclicita.l20_tipoprocesso IN (0, 100, 101, 102, 103) 
            AND liclicita.l20_anousu <= 2023;
        ");
    }

    /**
     * Reverter a migration.
     *
     * @return void
     */
    public function down()
    {
        // SQL para reverter a mudança no l20_tipoprocesso
        DB::unprepared("
            UPDATE licitacao.liclicita
            SET l20_tipoprocesso = CASE 
                WHEN liclicita.l20_tipoprocesso = 1 THEN 101
                WHEN liclicita.l20_tipoprocesso = 2 THEN 100
                WHEN liclicita.l20_tipoprocesso = 3 THEN 102
                WHEN liclicita.l20_tipoprocesso = 4 THEN 103
                WHEN liclicita.l20_tipoprocesso = 0 THEN 
                    CASE 
                        WHEN cflicita.l03_pctipocompratribunal = 1 THEN 101
                        WHEN cflicita.l03_pctipocompratribunal = 2 THEN 100
                        WHEN cflicita.l03_pctipocompratribunal = 3 THEN 102
                        WHEN cflicita.l03_pctipocompratribunal = 4 THEN 103
                        ELSE liclicita.l20_tipoprocesso
                    END
            END
            FROM licitacao.cflicita
            WHERE liclicita.l20_codtipocom = cflicita.l03_codigo
            AND liclicita.l20_tipoprocesso IN (1, 2, 3, 4) 
            AND liclicita.l20_anousu <= 2023;
        ");
    }

}
