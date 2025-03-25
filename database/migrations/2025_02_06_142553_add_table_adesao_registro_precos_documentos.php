<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableAdesaoRegistroPrecosDocumentos extends Migration
{
    public function up()
    {
        Schema::create('sicom.adesaoregprecosdocumentos', function (Blueprint $table) {
            $table->bigInteger('sd1_sequencial')->primary()->default(0);
            $table->string('sd1_nomearquivo', 100)->nullable();
            $table->string('sd1_tipo', 2)->nullable();
            $table->bigInteger('sd1_liclicita')->nullable();
            $table->binary('sd1_arquivo')->nullable();
            $table->string('sd1_extensao', 10)->nullable();

            $table->integer('sd1_sequencialadesao')->default(0);

            $table->foreign('sd1_sequencialadesao')
                  ->references('si06_sequencial')
                  ->on('sicom.adesaoregprecos');
        });

        DB::statement('
            CREATE SEQUENCE IF NOT EXISTS adesaoregprecosdocumentos_sd1_sequencial_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;
        ');
    }


    public function down()
    {
        Schema::dropIfExists('sicom.adesaoregprecosdocumentos');
        DB::statement('DROP SEQUENCE IF EXISTS adesaoregprecosdocumentos_sd1_sequencial_seq');
    }
}
