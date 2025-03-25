<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuCadastroBasico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE configuracoes.db_menu DISABLE TRIGGER ALL");
        DB::statement("ALTER TABLE configuracoes.db_itensmenu DISABLE TRIGGER ALL");

        // Insert 'Relatorios' item into configuracoes.db_itensmenu
        DB::table('configuracoes.db_itensmenu')->insert([
            'id_item' => DB::raw('(select max(id_item)+1 from configuracoes.db_itensmenu)'),
            'descricao' => 'Cadastro Básico',
            'help' => 'Cadastro Básico',
            'funcao' => 'lic_cadastrobasicosicom.php',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Cadastro Básico',
            'libcliente' => 't',
        ]);

        // Insert 'Relatorios' into configuracoes.db_menu
        DB::table('configuracoes.db_menu')->insert([
            'id_item' => '4001777',
            'id_item_filho' => DB::raw('(select max(id_item) from configuracoes.db_itensmenu)'),
            'menusequencia' => 2,
            'modulo' => 381,
        ]);

        DB::statement("ALTER TABLE configuracoes.db_menu ENABLE TRIGGER ALL");
        DB::statement("ALTER TABLE configuracoes.db_itensmenu ENABLE TRIGGER ALL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
