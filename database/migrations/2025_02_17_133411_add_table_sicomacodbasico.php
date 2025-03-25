<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableSicomacodbasico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sicom.sicomacodbasico', function (Blueprint $table) {
            $table->bigInteger('l228_sequencial')->primary()->default(0);
            $table->string('l228_codremessa', 20)->nullable();
            $table->bigInteger('l228_seqremessa')->nullable();
            $table->bigInteger('l228_usuario')->nullable();
            $table->date('l228_dataenvio')->nullable();
            $table->date('l228_datainicial')->nullable();
            $table->date('l228_datafim')->nullable();

            // $table->integer('sd1_sequencialadesao')->default(0);

            $table->foreign('l228_usuario')
                  ->references('id_usuario')
                  ->on('db_usuarios');
        });

        DB::statement('
            CREATE SEQUENCE IF NOT EXISTS sicomacodbasico_l228_sequencial_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sicom.sicomacodbasico');
        DB::statement('DROP SEQUENCE IF EXISTS sicomacodbasico_l228_sequencial_seq');
    }
}
