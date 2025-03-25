<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateL20RecdocumentacaoInLiclicita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            UPDATE liclicita 
            SET l20_recdocumentacao = l20_dataaberproposta
            FROM cflicita  
            WHERE liclicita.l20_codtipocom = cflicita.l03_codigo 
            AND (cflicita.l03_pctipocompratribunal IN (48, 49, 50, 51, 52, 53, 54, 110)  
            AND liclicita.l20_anousu = 2025);
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            UPDATE liclicita 
            SET l20_recdocumentacao = NULL
            WHERE l20_anousu = 2025
            AND l20_codtipocom IN (
                SELECT l03_codigo FROM cflicita
                WHERE l03_pctipocompratribunal IN (48, 49, 50, 51, 52, 53, 54, 110)
            );
        ");

    }
}
