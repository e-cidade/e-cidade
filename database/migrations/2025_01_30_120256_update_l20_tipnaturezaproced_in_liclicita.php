<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateL20TipnaturezaprocedInLiclicita extends Migration
{
    public function up(): void
    {
        DB::statement("
            UPDATE licitacao.liclicita
            SET l20_tipnaturezaproced = 1
            WHERE l20_leidalicitacao = 1 
              AND (l20_dtpubratificacao > '2024-12-31' OR l20_anousu = 2025) 
              AND l20_dtpubratificacao IS NOT NULL
              AND l20_tipoprocesso IN (3, 4)
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE licitacao.liclicita
            SET l20_tipnaturezaproced = 0
            WHERE l20_leidalicitacao = 1 
              AND (l20_dtpubratificacao > '2024-12-31' OR l20_anousu = 2025) 
              AND l20_dtpubratificacao IS NOT NULL
              AND l20_tipoprocesso IN (3, 4)
        ");
    }
}
