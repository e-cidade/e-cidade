<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrationHotfixOC22512 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
        ALTER TABLE licproposta
        DROP CONSTRAINT licproposta_l224_forne_fkey;
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
        ALTER TABLE licproposta
        ADD CONSTRAINT licproposta_l224_forne_fkey
        FOREIGN KEY (l224_forne) REFERENCES pcorcamforne(pc21_orcamforne);
        ";
        DB::unprepared($sql);
    }
}
