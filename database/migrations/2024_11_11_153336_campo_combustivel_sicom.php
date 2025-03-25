<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CampoCombustivelSicom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("ALTER TABLE veiculos.veiccadcomb ADD COLUMN ve26_combustivelsicom CHAR(2) NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("ALTER TABLE veiculos.veiccadcomb DROP COLUMN ve26_combustivelsicom;");
    }
}
