<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::table('public.licpregaocgm', function (Blueprint $table) {
            $table->string('l46_descricaonaturezacargo', 50)->nullable();
        });

        $sSql = "UPDATE licitacao.liclicita AS l1
            SET l20_descrcriterio = CASE 
    WHEN l1.l20_tipliticacao = 1 THEN 'Menor preço'
    WHEN l1.l20_tipliticacao = 2 THEN 'Maior desconto'
    WHEN l1.l20_tipliticacao = 4 THEN 'Técnico e preço'
    WHEN l1.l20_tipliticacao = 5 THEN 'Maior lance'
    WHEN l1.l20_tipliticacao = 6 THEN 'Maior retorno econômico'
    WHEN l1.l20_tipliticacao = 7 THEN 'Não se aplica'
    WHEN l1.l20_tipliticacao = 8 THEN 'Melhor técnica'
    WHEN l1.l20_tipliticacao = 9 THEN 'Conteúdo artístico'
            END
            FROM licitacao.liclicita AS l2
            INNER JOIN cflicita ON cflicita.l03_codigo = l2.l20_codtipocom
            LEFT JOIN homologacaoadjudica ON homologacaoadjudica.l202_licitacao = l2.l20_codigo
            WHERE l03_pctipocompratribunal IN (48,49,50,51,52,53,54,110)
            AND (l2.l20_anousu = 2025 
                OR (l2.l20_dtpublic IS NOT NULL AND l2.l20_dtpublic > '2024-12-31') 
                OR (l202_datahomologacao IS NOT NULL AND l202_datahomologacao > '2024-12-31'))";

        DB::unprepared($sSql);


    }

    public function down()
    {
        Schema::table('public.licpregaocgm', function (Blueprint $table) {
            $table->dropColumn('l46_descricaonaturezacargo');
        });

        DB::statement("UPDATE licitacao.liclicita 
        SET l20_descrcriterio = NULL 
        WHERE l20_codigo IN (
            SELECT liclicita.l20_codigo FROM licitacao.liclicita
            INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
            LEFT JOIN homologacaoadjudica ON homologacaoadjudica.l202_licitacao = liclicita.l20_codigo
            WHERE l03_pctipocompratribunal IN (48,49,50,51,52,53,54,110)
            AND (l20_anousu = 2025 
                OR (l20_dtpublic IS NOT NULL AND l20_dtpublic > '2024-12-31') 
                OR (l202_datahomologacao IS NOT NULL AND l202_datahomologacao > '2024-12-31'))
        )");
    }
};
