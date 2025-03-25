<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddLancesRelatoriosParametrosToMenusAndItensMenu extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE configuracoes.db_menu DISABLE TRIGGER ALL");
        DB::statement("ALTER TABLE configuracoes.db_itensmenu DISABLE TRIGGER ALL");

        // Insert 'Fase de Lances' item into configuracoes.db_itensmenu
        DB::table('configuracoes.db_itensmenu')->insert([
            'id_item' => DB::raw('(select max(id_item)+1 from configuracoes.db_itensmenu)'),
            'descricao' => 'Fase de Lances',
            'help' => 'Fase de Lances',
            'funcao' => 'web/patrimonial/licitacoes/procedimentos/julgamento-por-lance/fase-de-lances',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Fase de Lances',
            'libcliente' => 't',
        ]);

        // Insert 'Fase de Lances' into configuracoes.db_menu
        DB::table('configuracoes.db_menu')->insert([
            'id_item' => '4001754',
            'id_item_filho' => DB::raw('(select max(id_item) from configuracoes.db_itensmenu)'),
            'menusequencia' => 2,
            'modulo' => 381,
        ]);

        // Insert 'Relatorios' item into configuracoes.db_itensmenu
        DB::table('configuracoes.db_itensmenu')->insert([
            'id_item' => DB::raw('(select max(id_item)+1 from configuracoes.db_itensmenu)'),
            'descricao' => 'Relatorios',
            'help' => 'Relatorios',
            'funcao' => 'web/patrimonial/licitacoes/procedimentos/julgamento-por-lance/relatorios',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Relatorios',
            'libcliente' => 't',
        ]);

        // Insert 'Relatorios' into configuracoes.db_menu
        DB::table('configuracoes.db_menu')->insert([
            'id_item' => '4001754',
            'id_item_filho' => DB::raw('(select max(id_item) from configuracoes.db_itensmenu)'),
            'menusequencia' => 3,
            'modulo' => 381,
        ]);

        // Insert 'Parametros' item into configuracoes.db_itensmenu
        DB::table('configuracoes.db_itensmenu')->insert([
            'id_item' => DB::raw('(select max(id_item)+1 from configuracoes.db_itensmenu)'),
            'descricao' => 'Parametros',
            'help' => 'Parametros',
            'funcao' => 'web/patrimonial/licitacoes/procedimentos/julgamento-por-lance/parametros',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Parametros',
            'libcliente' => 't',
        ]);

        // Insert 'Parametros' into configuracoes.db_menu
        DB::table('configuracoes.db_menu')->insert([
            'id_item' => '4001754',
            'id_item_filho' => DB::raw('(select max(id_item) from configuracoes.db_itensmenu)'),
            'menusequencia' => 4,
            'modulo' => 381,
        ]);

        DB::statement("ALTER TABLE configuracoes.db_menu ENABLE TRIGGER ALL");
        DB::statement("ALTER TABLE configuracoes.db_itensmenu ENABLE TRIGGER ALL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE configuracoes.db_menu DISABLE TRIGGER ALL");
        DB::statement("ALTER TABLE configuracoes.db_itensmenu DISABLE TRIGGER ALL");

        $descriptions = ['Fase de Lances', 'Relatorios', 'Parametros'];

        foreach ($descriptions as $description) {

            $result = DB::table('configuracoes.db_menu')
                ->where('id_item_filho', function($query) use ($description) {
                    $query->select('id_item_filho')
                        ->from('configuracoes.db_menu')
                        ->where('id_item_filho', function($subQuery) use ($description) {
                            $subQuery->select(DB::raw('max(id_item)'))
                                ->from('configuracoes.db_itensmenu')
                                ->where('descricao', $description);
                        })
                        ->limit(1);
                })
                ->orderBy('id_item', 'desc')
                ->first();

            if ($result) {
                DB::table('configuracoes.db_itensmenu')->where('descricao', $description)->delete();
                DB::table('configuracoes.db_menu')->where('id_item_filho', $result->id_item_filho)->delete();
            }
            
        }

        DB::statement("ALTER TABLE configuracoes.db_menu ENABLE TRIGGER ALL");
        DB::statement("ALTER TABLE configuracoes.db_itensmenu ENABLE TRIGGER ALL");
    }
}
