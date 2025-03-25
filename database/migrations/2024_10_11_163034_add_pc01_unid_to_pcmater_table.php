<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPc01UnidToPcmaterTable extends Migration
{
    /**
     * Executa a migration.
     *
     * @return void
     */
    public function up()
    {
        // SQL puro para adicionar a coluna pc01_unid e a chave estrangeira no PostgreSQL
        DB::unprepared("
            ALTER TABLE compras.pcmater
            ADD COLUMN pc01_unid INTEGER NULL,
            ADD CONSTRAINT pcmater_pc01_unid_foreign
            FOREIGN KEY (pc01_unid) REFERENCES material.matunid(m61_codmatunid)
            ON DELETE RESTRICT;
        ");
    }

    /**
     * Reverte a migration.
     *
     * @return void
     */
    public function down()
    {
        // SQL puro para remover a chave estrangeira e a coluna no PostgreSQL
        DB::unprepared("
            ALTER TABLE compras.pcmater
            DROP CONSTRAINT IF EXISTS pcmater_pc01_unid_foreign,
            DROP COLUMN IF EXISTS pc01_unid;
        ");
    }
}
