<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuGerarSicomPrestacaoContas extends Migration
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

        DB::table('configuracoes.db_menu')->insert([
            'id_item' => '8987',
            'id_item_filho' => DB::raw('(select max(id_item) from configuracoes.db_itensmenu where funcao = \'lic_cadastrobasicosicom.php\')'),
            'menusequencia' => 461,
            'modulo' => 2000018,
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
