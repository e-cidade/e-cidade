<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateForeignKeyL227UsuarioWithRawSql extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remover a constraint existente
        DB::statement('ALTER TABLE licitacao.remessasicom DROP CONSTRAINT fk_usuario');

        // Adicionar a nova constraint
        DB::statement('
            ALTER TABLE licitacao.remessasicom 
            ADD CONSTRAINT fk_usuario
            FOREIGN KEY (l227_usuario) 
            REFERENCES configuracoes.db_usuarios(id_usuario) 
            ON DELETE CASCADE
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remover a nova constraint
        DB::statement('ALTER TABLE licitacao.remessasicom DROP CONSTRAINT fk_usuario');

        // Restaurar a constraint original
        DB::statement('
            ALTER TABLE licitacao.remessasicom 
            ADD CONSTRAINT fk_usuario
            FOREIGN KEY (l227_usuario) 
            REFERENCES protocolo.cgm(z01_numcgm) 
            ON DELETE CASCADE
        ');
    }
}