<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HotfixOC22512 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $sql = "
        ALTER TABLE licitacao.licproposta
        ADD CONSTRAINT licproposta_l224_forne_fkey
        FOREIGN KEY (l224_forne)
        REFERENCES licitacao.pcorcamfornelic(pc31_orcamforne);
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
            ALTER TABLE licitacao.licproposta
            DROP CONSTRAINT licproposta_l224_forne_fkey;
        ";
        DB::unprepared($sql);
    }
}
