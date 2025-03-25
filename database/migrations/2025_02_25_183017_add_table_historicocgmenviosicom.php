<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableHistoricocgmenviosicom extends Migration
{
    public function up()
    {
        Schema::create('sicom.historicocgmenviosicom', function (Blueprint $table) {
            $table->bigInteger('z18_sequencial')->primary()->default(0);
            $table->bigInteger('z18_instit')->nullable();
            $table->boolean('z18_statusenvio')->nullable();
            
            $table->bigInteger('z18_cgm')->nullable();
            $table->foreign('z18_cgm')
                  ->references('z01_numcgm')
                  ->on('cgm')
                  ->onDelete('cascade');
        });

        DB::statement('
            CREATE SEQUENCE IF NOT EXISTS historicocgmenviosicom_z18_sequencial_seq
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
        Schema::dropIfExists('sicom.historicocgmenviosicom');
        DB::statement('DROP SEQUENCE IF EXISTS historicocgmenviosicom_z18_sequencial_seq');
    }
}
