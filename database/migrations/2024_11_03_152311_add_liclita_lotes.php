<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLiclitaLotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licitacao.liclicitalotes', function (Blueprint $table) {
            $table->integer('l24_codigo')->default(0)->primary();
            $table->string('l24_pcdesc', 255);
            $table->integer('l24_codliclicita')->default(0);

            $table->foreign('l24_codliclicita')
                  ->references('l20_codigo')
                  ->on('licitacao.liclicita');
        });

        // Criar a sequência liclicitalotes_l24_codigo_seq
        DB::statement('
            CREATE SEQUENCE IF NOT EXISTS liclicitalotes_l24_codigo_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
        ');

        // Adicionar coluna l04_codlilicitalote à tabela liclicitemlote
        Schema::table('licitacao.liclicitemlote', function (Blueprint $table) {
            $table->integer('l04_codlilicitalote')->nullable();

            $table->foreign('l04_codlilicitalote')
                  ->references('l24_codigo')
                  ->on('licitacao.liclicitalotes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('licitacao.liclicitemlote', function (Blueprint $table) {
            $table->dropForeign(['l04_codlilicitalote']);
            $table->dropColumn('l04_codlilicitalote');
        });

        // Remover a tabela liclicitalotes e sequência associada
        Schema::dropIfExists('licitacao.liclicitalotes');
        DB::statement('DROP SEQUENCE IF EXISTS liclicitalotes_l24_codigo_seq');
    }
}
