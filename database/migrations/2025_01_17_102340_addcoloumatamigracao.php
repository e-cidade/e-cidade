<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addcoloumatamigracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            insert into acordocategoria values(5,'Ata de RP');
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
            delete from acordocategoria where ac50_sequencial = 5;
        ";

        DB::unprepared($sql);
    }
}
