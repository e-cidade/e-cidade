<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddL227InstitToRemessasicomTable extends Migration
{
    public function up()
    {
        Schema::table('licitacao.remessasicom', function (Blueprint $table) {
            $table->integer('l227_instit')->nullable();
        });

        DB::statement("
        UPDATE licitacao.remessasicom
        SET l227_instit = COALESCE(subquery.l20_instit, subquery.si06_instit)
        FROM (
            SELECT r.l227_remessa, 
                   l.l20_instit, 
                   a.si06_instit
            FROM licitacao.remessasicom AS r
            LEFT JOIN liclicita AS l ON l.l20_codigo = r.l227_licitacao
            LEFT JOIN adesaoregprecos AS a ON a.si06_sequencial = r.l227_adesao
        ) AS subquery
        WHERE licitacao.remessasicom.l227_remessa = subquery.l227_remessa
    ");
    
    
    }

    public function down()
    {
        Schema::table('licitacao.remessasicom', function (Blueprint $table) {
            $table->dropColumn('l227_instit');
        });
    }
}
