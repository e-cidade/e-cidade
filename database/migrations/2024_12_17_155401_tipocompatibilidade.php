<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tipocompatibilidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        // Dados a serem inseridos ou atualizados
        $data = [
            ['sd68_i_codigo' => 1, 'sd68_c_nome' => 'COMP 1'],
            ['sd68_i_codigo' => 2, 'sd68_c_nome' => 'COMP 2'],
            ['sd68_i_codigo' => 3, 'sd68_c_nome' => 'COMP 3'],
            ['sd68_i_codigo' => 4, 'sd68_c_nome' => 'COMP 4'],
            ['sd68_i_codigo' => 5, 'sd68_c_nome' => 'COMPATIBILIDADE OBRIGATÓRIA'],
        ];

        // Utilizando upsert para inserir ou atualizar os dados
        DB::table('sau_tipocompatibilidade')->upsert(
            $data,
            ['sd68_i_codigo'], // Chaves para detectar registros existentes
            ['sd68_c_nome']    // Colunas a serem atualizadas
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Opcional: Remova os registros se necessário ao reverter a migration
        DB::table('sau_tipocompatibilidade')->whereIn('sd68_i_codigo', [1, 2, 3, 4, 5])->delete();
    }
}
