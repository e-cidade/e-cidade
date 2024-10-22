<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDbmenuPlanodecontratacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            update configuracoes.db_itensmenu set funcao ='com_planodecontratacao001.php' where funcao='pc_planodecontratacao001.php';
        ";

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "
            update configuracoes.db_itensmenu set funcao ='pc_planodecontratacao001.php' where funcao='com_planodecontratacao001.php';
        ";

        DB::unprepared($sql);
    }
}
