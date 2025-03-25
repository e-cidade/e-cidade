<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePcorcamanexos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            CREATE TABLE licitacao.pcorcamanexos (
            pc98_sequencial   SERIAL PRIMARY KEY,
            pc98_codorc       INT NOT NULL,
            pc98_nomearquivo varchar,
            pc98_anexo        OID,
            CONSTRAINT fk_pc98_codorc FOREIGN KEY (pc98_codorc) REFERENCES pcorcam (pc20_codorc));
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
        DB::unprepared("DROP TABLE IF EXISTS licitacao.pcorcamanexos");
    }
}
