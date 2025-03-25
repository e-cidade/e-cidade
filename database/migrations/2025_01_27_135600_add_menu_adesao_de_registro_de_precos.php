<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuAdesaoDeRegistroDePrecos extends Migration
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
            'descricao' => 'Adesão de Registro de Preços',
            'help' => 'Adesão de Registro de Preços',
            'funcao' => 'con4_adesaoregistroprecos001.php',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Adesão de Registro de Preços',
            'libcliente' => 't',
        ]);

        // Insert 'Relatorios' into configuracoes.db_menu
        DB::table('configuracoes.db_menu')->insert([
            'id_item' => '32',
            'id_item_filho' => DB::raw('(select max(id_item) from configuracoes.db_itensmenu)'),
            'menusequencia' => 492,
            'modulo' => 28,
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
