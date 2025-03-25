<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMenuCadastroBasicoSicom extends Migration
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

        DB::statement("
            DELETE FROM configuracoes.db_menu 
                WHERE id_item_filho = (
                    SELECT id_item FROM configuracoes.db_itensmenu WHERE funcao = 'lic_cadastrobasicosicom.php'
                )
        ");

        $aIds = DB::table('configuracoes.db_itensmenu')
            ->select('id_item')
            ->where('descricao', 'Gerar SICOM')
            ->pluck('id_item')
            ->toArray();

        if(!empty($aIds)){
            foreach($aIds as $id){
                DB::table('configuracoes.db_menu')->insert([
                    'id_item' => $id,
                    'id_item_filho' => DB::raw('(select max(id_item) from configuracoes.db_itensmenu where funcao = \'lic_cadastrobasicosicom.php\')'),
                    'menusequencia' => DB::raw('(select max(menusequencia) + 1 from configuracoes.db_menu where id_item = ' . $id . ')'),
                    'modulo' => DB::raw('(select max(modulo) from configuracoes.db_menu where id_item = ' . $id . ')'),
                ]);
            }
        }


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
    }
}
