<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Oc23396 extends Migration
{
    public function up()
    {
        $this->criarItensMenu();
        $this->criaTabelaSubsidioVereadores();
        $this->criarDadosCamposTabelaSubsidioVereadores();
    }

    public function criarItensMenu()
    {
        DB::statement("SELECT setval('db_itensmenu_id_item_seq', (SELECT MAX(id_item) FROM db_itensmenu))");
        DB::statement("SELECT setval('db_syscampo_codcam_seq', (SELECT MAX(codcam) FROM db_syscampo))");
        DB::statement("SELECT setval('db_sysarquivo_codarq_seq', (SELECT MAX(codarq) FROM db_sysarquivo))");

        DB::table('db_itensmenu')->insert([
            ['id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'), 'descricao' => 'Subsidio dos Vereadores', 'help' => 'Cadastro de Subsidio dos Vereadores', 'funcao' => '', 'itemativo' => true, 'manutencao' => true, 'desctec' => 'Cadastro de Subsidio dos Vereadores', 'libcliente' => true]
        ]);

        DB::table('db_itensmenu')->insert([
            ['id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'), 'descricao' => 'Inclusão', 'help' => 'Inclusão de Subsidio dos Vereadores', 'funcao' => 'sic1_subsidiovereadores001.php', 'itemativo' => true, 'manutencao' => true, 'desctec' => 'Inclusão de Subsidio dos Vereadores', 'libcliente' => true]
        ]);
        DB::table('db_itensmenu')->insert([
            ['id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'), 'descricao' => 'Alteração', 'help' => 'Alteração de Subsidio dos Vereadores', 'funcao' => 'sic1_subsidiovereadores002.php', 'itemativo' => true, 'manutencao' => true, 'desctec' => 'Alteração de Subsidio dos Vereadores', 'libcliente' => true]
        ]);
        DB::table('db_itensmenu')->insert([
            ['id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'), 'descricao' => 'Exclusão', 'help' => 'Exclusão de Subsidio dos Vereadores', 'funcao' => 'sic1_subsidiovereadores003.php', 'itemativo' => true, 'manutencao' => true, 'desctec' => 'Exclusão de Subsidio dos Vereadores', 'libcliente' => true]
        ]);

        DB::table('db_menu')->insert([
            'id_item' => 3000271,
            'id_item_filho' => DB::table('db_itensmenu')
                ->where('help', 'Cadastro de Subsidio dos Vereadores')
                ->value('id_item'),
            'menusequencia' => DB::table('db_menu')
                ->where('id_item', 3000271)
                ->max('menusequencia') + 1,
            'modulo' => 952
        ]);

        $idItemSubsidio = DB::table('db_itensmenu')
            ->where('descricao', 'Subsidio dos Vereadores')
            ->value('id_item');

        $submenus = [
            ['help' => 'Inclusão de Subsidio dos Vereadores', 'menusequencia' => 1],
            ['help' => 'Alteração de Subsidio dos Vereadores', 'menusequencia' => 2],
            ['help' => 'Exclusão de Subsidio dos Vereadores', 'menusequencia' => 3],
        ];

        foreach ($submenus as $submenu) {
            DB::table('db_menu')->insert([
                'id_item' => $idItemSubsidio,
                'id_item_filho' => DB::table('db_itensmenu')
                    ->where('help', $submenu['help'])
                    ->value('id_item'),
                'menusequencia' => $submenu['menusequencia'],
                'modulo' => 952
            ]);
        }
    }

    public function criaTabelaSubsidioVereadores()
    {
        Schema::create('pessoal.subsidiovereadores', function (Blueprint $table) {
            $table->id('si179_sequencial');
            $table->float('si179_valor');
            $table->float('si179_percentual');
            $table->date('si179_dataini')->notNull();
            $table->integer('si179_lei')->notNull();
            $table->date('si179_publicacao')->notNull();
            $table->integer('si179_instit')->notNull();
        });
    }

    public function criarDadosCamposTabelaSubsidioVereadores()
    {
        DB::table('db_sysarquivo')->insert([
            [
                'codarq' => DB::raw("nextval('db_sysarquivo_codarq_seq')"),
                'nomearq' => 'subsidiovereadores',
                'descricao' => 'Cadastro do Subsidio dos Vereadores',
                'sigla' => 'si179',
                'dataincl' => '2025-01-01',
                'rotulo' => 'Subsidio Vereadores',
                'tipotabela' => 0,
                'naolibclass' => 'false',
                'naolibfunc' => 'false',
                'naolibprog' => 'false',
                'naolibform' => 'false'
            ]
        ]);

        $codarq = DB::table('db_sysarquivo')
            ->where('nomearq', 'subsidiovereadores')
            ->value('codarq');

        DB::table('db_sysarqmod')->insert([
            [
                'codmod' => 2008005,
                'codarq' => $codarq
            ]
        ]);


        DB::table('db_syscampo')->insert([
            ['codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"), 'nomecam' => 'si179_sequencial', 'conteudo' => 'int4', 'descricao' => 'Campo sequencial', 'valorinicial' => 0, 'rotulo' => 'Sequencial', 'tamanho' => 10, 'nulo' => false, 'maiusculo' => false, 'autocompl' => true, 'aceitatipo' => 1, 'tipoobj' => 'text', 'rotulorel' => 'Sequencial'],
            ['codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"), 'nomecam' => 'si179_valor', 'conteudo' => 'float', 'descricao' => 'Valor do Subsidio', 'valorinicial' => null, 'rotulo' => 'Valor do Subsidio', 'tamanho' => 12, 'nulo' => false, 'maiusculo' => false, 'autocompl' => false, 'aceitatipo' => 4, 'tipoobj' => 'text', 'rotulorel' => 'Valor'],
            ['codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"), 'nomecam' => 'si179_percentual', 'conteudo' => 'float', 'descricao' => 'Percentual', 'valorinicial' => null, 'rotulo' => 'Percentual', 'tamanho' => 8, 'nulo' => false, 'maiusculo' => false, 'autocompl' => false, 'aceitatipo' => 4, 'tipoobj' => 'text', 'rotulorel' => 'Percentual'],
            ['codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"), 'nomecam' => 'si179_dataini', 'conteudo' => 'date', 'descricao' => 'Data Inicio Vigencia', 'valorinicial' => null, 'rotulo' => 'Inicio Vigencia', 'tamanho' => 10, 'nulo' => false, 'maiusculo' => false, 'autocompl' => false, 'aceitatipo' => 0, 'tipoobj' => 'text', 'rotulorel' => 'Inicio Vigencia'],
            ['codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"), 'nomecam' => 'si179_lei', 'conteudo' => 'int4', 'descricao' => 'Lei Autorizativa', 'valorinicial' => null, 'rotulo' => 'Lei Autorizativa', 'tamanho' => 8, 'nulo' => false, 'maiusculo' => false, 'autocompl' => false, 'aceitatipo' => 4, 'tipoobj' => 'text', 'rotulorel' => 'Lei Autorizativa'],
            ['codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"), 'nomecam' => 'si179_publicacao', 'conteudo' => 'date', 'descricao' => 'Data Publicacao', 'valorinicial' => null, 'rotulo' => 'Data Publicacao', 'tamanho' => 10, 'nulo' => false, 'maiusculo' => false, 'autocompl' => false, 'aceitatipo' => 0, 'tipoobj' => 'text', 'rotulorel' => 'Publicacao'],
            ['codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"), 'nomecam' => 'si179_instit', 'conteudo' => 'int4', 'descricao' => 'Codigo Instituicao', 'valorinicial' => null, 'rotulo' => 'Instituicao', 'tamanho' => 4, 'nulo' => false, 'maiusculo' => false, 'autocompl' => false, 'aceitatipo' => 4, 'tipoobj' => 'text', 'rotulorel' => 'Instituicao'],
        ]);
    }

    public function down()
    {
        $codarq = DB::table('db_sysarquivo')
        ->where('nomearq', 'subsidiovereadores')
        ->value('codarq');
        
        Schema::dropIfExists('pessoal.subsidiovereadores');
        DB::table('db_sysarqmod')->where('codarq',$codarq)->delete();
        DB::table('db_itensmenu')->where('desctec', 'like', '%Subsidio dos Vereadores%')->delete();
        DB::table('db_sysarquivo')->where('nomearq', 'subsidiovereadores')->delete();
        DB::table('db_syscampo')->where('nomecam', 'like', 'si179_%')->delete();
    }
}
