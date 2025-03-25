<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Oc19622 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SELECT setval('db_itensmenu_id_item_seq', (SELECT MAX(id_item) FROM db_itensmenu))");
        DB::statement("SELECT setval('db_syscampo_codcam_seq', (SELECT MAX(codcam) FROM db_syscampo))");
        DB::statement("SELECT setval('db_sysarquivo_codarq_seq', (SELECT MAX(codarq) FROM db_sysarquivo))");

        //ADICINOAR Menu Sst
        DB::table('db_itensmenu')->insert([
            [
                'id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'),
                'descricao' => 'SST',
                'help' => 'Menu SST',
                'funcao' => '',
                'itemativo' => 1,
                'manutencao' => 1,
                'desctec' => 'Menu SST',
                'libcliente' => 't',
            ]
        ]);

        DB::table('db_menu')->insert([
            [
                'id_item' => 29,
                'id_item_filho' => DB::raw('(SELECT MAX(id_item) FROM db_itensmenu)'),
                'menusequencia' => DB::raw('(SELECT MAX(menusequencia)+1 FROM db_menu WHERE id_item = 29 AND modulo = 10216)'),
                'modulo' => DB::raw("(SELECT id_item FROM db_modulos WHERE descr_modulo LIKE '%eSocial%')")
            ]
        ]);

        //ADICIONAR SUBMENU Condicoes Ambientais
        DB::table('db_itensmenu')->insert([
            'id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'),
            'descricao' => 'Condições Ambientais',
            'help' => 'Condições Ambientais - SST',
            'funcao' => '',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Condições Ambientais - SST',
            'libcliente' => 't',
        ]);

        DB::table('db_menu')->insert([
            'id_item' => DB::raw("(SELECT id_item FROM db_itensmenu WHERE help LIKE '%Menu SST%')"),
            'id_item_filho' => DB::raw('(SELECT MAX(id_item) FROM db_itensmenu)'),
            'menusequencia' => 1,
            'modulo' => DB::raw("(SELECT id_item FROM db_modulos WHERE descr_modulo LIKE '%eSocial%')"),
        ]);

        //ADICIONAR SUBMENU Inclusao AO MENU Condicoes Ambientais
        DB::table('db_itensmenu')->insert([
            'id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'),
            'descricao' => 'Inclusão',
            'help' => 'Inclusão Condições Ambientais',
            'funcao' => 'eso1_condicoesambientais001.php',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Inclusão Condições Ambientais',
            'libcliente' => 't',
        ]);

        DB::table('db_menu')->insert([
            'id_item' => DB::raw("(SELECT id_item FROM db_itensmenu WHERE help LIKE '%Condições Ambientais - SST%')"),
            'id_item_filho' => DB::raw('(SELECT MAX(id_item) FROM db_itensmenu)'),
            'menusequencia' => 1,
            'modulo' => DB::raw("(SELECT id_item FROM db_modulos WHERE descr_modulo LIKE '%eSocial%')"),
        ]);

        //ADICIONAR SUBMENU Alteracao AO MENU Condicoes Ambientais
        DB::table('db_itensmenu')->insert([
            'id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'),
            'descricao' => 'Alteração',
            'help' => 'Alteração Condições Ambientais',
            'funcao' => 'eso1_condicoesambientais002.php',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Alteração Condições Ambientais',
            'libcliente' => 't',
        ]);

        DB::table('db_menu')->insert([
            'id_item' => DB::raw("(SELECT id_item FROM db_itensmenu WHERE help LIKE '%Condições Ambientais - SST%')"),
            'id_item_filho' => DB::raw('(SELECT MAX(id_item) FROM db_itensmenu)'),
            'menusequencia' => 2,
            'modulo' => DB::raw("(SELECT id_item FROM db_modulos WHERE descr_modulo LIKE '%eSocial%')"),
        ]);

        //ADICIONAR SUBMENU Exclusao AO MENU Condicoes Ambientais
        DB::table('db_itensmenu')->insert([
            'id_item' => DB::raw('(SELECT MAX(id_item)+1 FROM db_itensmenu)'),
            'descricao' => 'Exclusão',
            'help' => 'Exclusão Condições Ambientais',
            'funcao' => 'eso1_condicoesambientais003.php',
            'itemativo' => 1,
            'manutencao' => 1,
            'desctec' => 'Exclusão Condições Ambientais',
            'libcliente' => 't',
        ]);

        DB::table('db_menu')->insert([
            'id_item' => DB::raw("(SELECT id_item FROM db_itensmenu WHERE help LIKE '%Condições Ambientais - SST%')"),
            'id_item_filho' => DB::raw('(SELECT MAX(id_item) FROM db_itensmenu)'),
            'menusequencia' => 3,
            'modulo' => DB::raw("(SELECT id_item FROM db_modulos WHERE descr_modulo LIKE '%eSocial%')"),
        ]);

        //CRIAR TABELAS
        //TABELA infoambiente
        Schema::create('pessoal.infoambiente', function ($table) {
            $table->integer('rh230_regist')->default(0);
            $table->date('rh230_data')->nullable();
            $table->text('rh230_descricao')->nullable();
            $table->integer('rh230_instit')->notNull();

            $table->primary(['rh230_regist']);

            $table->foreign('rh230_regist')
                ->references('rh01_regist')
                ->on('rhpessoal')
                ->onDelete('cascade');
        });

        DB::table('db_syscampo')->insert([
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh230_regist',
                'conteudo' => 'int4',
                'descricao' => 'Matricula do servidor',
                'valorinicial' => 0,
                'rotulo' => 'Matricula',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'Matricula',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh230_data',
                'conteudo' => 'date',
                'descricao' => 'Data de inicio',
                'valorinicial' => '',
                'rotulo' => 'Data de Inicio',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'date',
                'rotulorel' => 'Data de Inicio',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh230_descricao',
                'conteudo' => 'text',
                'descricao' => 'Descrição do ambiente',
                'valorinicial' => '',
                'rotulo' => 'Descrição do Ambiente',
                'tamanho' => 100,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Descrição do Ambiente',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh230_instit',
                'conteudo' => 'int4',
                'descricao' => 'Instituição',
                'valorinicial' => '',
                'rotulo' => 'Cód. Instituição',
                'tamanho' => 100,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'int',
                'rotulorel' => 'Cód. Instituição',
            ]
        ]);

        //TABELA atividadesdesempenhadas
        Schema::create('pessoal.atividadesdesempenhadas', function ($table) {
            $table->integer('rh231_regist')->default(0);
            $table->text('rh231_descricao');

            $table->primary(['rh231_regist']);

            $table->foreign('rh231_regist')
                ->references('rh01_regist')
                ->on('rhpessoal')
                ->onDelete('cascade');
        });

        DB::table('db_syscampo')->insert([
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh231_regist',
                'conteudo' => 'int4',
                'descricao' => 'Matricula do servidor',
                'valorinicial' => 0,
                'rotulo' => 'Matricula',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'Matricula',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh231_descricao',
                'conteudo' => 'text',
                'descricao' => 'Descrição das atividades desempenhadas',
                'valorinicial' => '',
                'rotulo' => 'Descricao',
                'tamanho' => 100,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Descrição',
            ]
        ]);

        //TABELA agentesnocivos
        Schema::create('pessoal.agentesnocivos', function ($table) {
            $table->integer('rh232_sequencial');
            $table->integer('rh232_regist');
            $table->integer('rh232_agente');
            $table->char('rh232_tipoavaliacao', 1)->nullable();
            $table->decimal('rh232_icdexposicao', 10, 2)->nullable();
            $table->decimal('rh232_ltolerancia', 10, 2)->nullable();
            $table->integer('rh232_unidade')->nullable();
            $table->string('rh232_tecnicamed', 40)->nullable();
            $table->integer('rh232_epc')->nullable();
            $table->char('rh232_epceficaz', 1)->default('0');
            $table->integer('rh232_epi')->nullable();
            $table->char('rh232_epieficaz', 1)->default(0);
            $table->text('rh232_epicertificado')->nullable();
            $table->text('rh232_epidescricao')->nullable();
            $table->char('rh232_epiporinviabilidade', 1)->default('0');
            $table->char('rh232_epiobscondicoes', 1)->default('0');
            $table->char('rh232_epiobsuso', 1)->default('0');
            $table->char('rh232_epiobsprazo', 1)->default('0');
            $table->char('rh232_obsperiodicidade', 1)->default('0');
            $table->char('rh232_obshigienizacao', 1)->default('0');

            $table->primary(['rh232_regist']);

            $table->foreign('rh232_regist')
                ->references('rh01_regist')
                ->on('rhpessoal')
                ->onDelete('cascade');
        });

        DB::statement('
            CREATE SEQUENCE pessoal.agentesnocivos_rh232_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1
        ');

        DB::table('db_syscampo')->insert([
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_sequencial',
                'conteudo' => 'int4',
                'descricao' => 'Sequencial',
                'valorinicial' => 0,
                'rotulo' => 'Sequencial',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'Sequencial',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_regist',
                'conteudo' => 'int4',
                'descricao' => 'Matricula do servidor',
                'valorinicial' => 0,
                'rotulo' => 'Matricula',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'Matricula',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_agente',
                'conteudo' => 'int4',
                'descricao' => 'Agente Nocivo',
                'valorinicial' => '',
                'rotulo' => 'Agente Nocivo',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Agente Nocivo',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_tipoavaliacao',
                'conteudo' => 'char',
                'descricao' => 'Tipo Avaliacao',
                'valorinicial' => '',
                'rotulo' => 'Tipo Avaliacao',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Tipo Avaliacao',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_icdexposicao',
                'conteudo' => 'varchar(10)',
                'descricao' => 'I.C.D. da Exposição',
                'valorinicial' => '',
                'rotulo' => 'I.C.D. da Exposição',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'I.C.D. da Exposição',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_ltolerancia',
                'conteudo' => 'varchar(30)',
                'descricao' => 'Limite de Tolerância',
                'valorinicial' => '',
                'rotulo' => 'Limite de Tolerância',
                'tamanho' => 30,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Limite de Tolerância',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_unidade',
                'conteudo' => 'int4',
                'descricao' => 'Unidade de Medida',
                'valorinicial' => '',
                'rotulo' => 'Unidade de Medida',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Unidade de Medida',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_tecnicamed',
                'conteudo' => 'varchar(40)',
                'descricao' => 'Técnica de Medição',
                'valorinicial' => '',
                'rotulo' => 'Técnica de Medição',
                'tamanho' => 255,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Técnica de Medição',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epc',
                'conteudo' => 'int4',
                'descricao' => 'Utiliza EPC',
                'valorinicial' => '',
                'rotulo' => 'Utiliza EPC',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Utiliza EPC',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epceficaz',
                'conteudo' => 'char(1)',
                'descricao' => 'Eficacia EPC',
                'valorinicial' => '',
                'rotulo' => 'Eficacia EPC',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Eficacia EPC',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epi',
                'conteudo' => 'int4',
                'descricao' => 'Utiliza EPI',
                'valorinicial' => '',
                'rotulo' => 'Utiliza EPI',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Utiliza EPI',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epieficaz',
                'conteudo' => 'char(1)',
                'descricao' => 'Eficacia EPI',
                'valorinicial' => '',
                'rotulo' => 'Eficacia EPI',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Eficacia EPI',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epicertificado',
                'conteudo' => 'text',
                'descricao' => 'Certificado EPI',
                'valorinicial' => '',
                'rotulo' => 'Certificado EPI',
                'tamanho' => 255,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Certificado EPI',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epidescricao',
                'conteudo' => 'text',
                'descricao' => 'Descrição do EPI',
                'valorinicial' => '',
                'rotulo' => 'Descrição do EPI',
                'tamanho' => 100,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Descrição do EPI',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epiporinviabilidade',
                'conteudo' => 'char(1)',
                'descricao' => 'EPI por inviabilidade',
                'valorinicial' => '',
                'rotulo' => 'EPI por inviabilidade',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'EPI por inviabilidade',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epiobscondicoes',
                'conteudo' => 'char(1)',
                'descricao' => 'Observadas as condições',
                'valorinicial' => '',
                'rotulo' => 'Observadas as condições',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Observadas as condições',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epiobsuso',
                'conteudo' => 'char(1)',
                'descricao' => 'Observado uso',
                'valorinicial' => '',
                'rotulo' => 'Observado uso',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Observado uso',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_epiobsprazo',
                'conteudo' => 'char(1)',
                'descricao' => 'Observado prazo',
                'valorinicial' => '',
                'rotulo' => 'Observado prazo',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Observado prazo',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_obsperiodicidade',
                'conteudo' => 'char(1)',
                'descricao' => 'Observada a periodicidade',
                'valorinicial' => '',
                'rotulo' => 'Observada a periodicidade',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Observada a periodicidade',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh232_obshigienizacao',
                'conteudo' => 'char(1)',
                'descricao' => 'Observada higienização',
                'valorinicial' => '',
                'rotulo' => 'Observada higienização',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Observada higienização',
            ]
        ]);

        Schema::create('pessoal.rhagente', function ($table) {
            $table->integer('rh233_sequencial')->primary();
            $table->string('rh233_codigo', 16);
            $table->string('rh233_descricao', 200);
        });

        DB::statement('
            CREATE SEQUENCE pessoal.rhagente_rh233_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
        ');

        DB::table('db_sysarquivo')->insert([
            'codarq' => DB::raw("nextval('db_sysarquivo_codarq_seq')"),
            'nomearq' => 'rhagente',
            'descricao' => 'Agentes Nocivos',
            'sigla' => 'rh233',
            'dataincl' => '2023-08-01',
            'rotulo' => 'Agentes Nocivos',
            'tipotabela' => 2,
            'naolibclass' => false,
            'naolibfunc' => false,
            'naolibprog' => false,
            'naolibform' => false,
        ]);

        DB::table('db_syscampo')->insert([
            [
                'codcam' => DB::raw("NEXTVAL('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh233_sequencial',
                'conteudo' => 'int4',
                'descricao' => 'Sequencial rhagente',
                'valorinicial' => 0,
                'rotulo' => 'Sequencial',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'Sequencial',
            ],
            [
                'codcam' => DB::raw("NEXTVAL('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh233_codigo',
                'conteudo' => 'varchar(16)',
                'descricao' => 'Codigo Agente',
                'valorinicial' => null,
                'rotulo' => 'Agente Nocivo',
                'tamanho' => 16,
                'nulo' => false,
                'maiusculo' => true,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Agente Nocivo',
            ],
            [
                'codcam' => DB::raw("NEXTVAL('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh233_descricao',
                'conteudo' => 'varchar(200)',
                'descricao' => 'Descricao Agente',
                'valorinicial' => null,
                'rotulo' => 'Descricao',
                'tamanho' => 200,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Descricao',
            ]
        ]);

        DB::table('db_sysarqcamp')->insert([
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'rhagente')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh233_sequencial')->value('codcam'),
                'seqarq' => 1,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'rhagente')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh233_codigo')->value('codcam'),
                'seqarq' => 2,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'rhagente')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh233_descricao')->value('codcam'),
                'seqarq' => 3,
                'codsequencia' => 0,
            ]
        ]);

        DB::table('pessoal.rhagente')->insert([
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.01.001', 'rh233_descricao' => 'Arsênio e seus compostos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.02.001', 'rh233_descricao' => 'Asbestos (ou amianto)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.03.001', 'rh233_descricao' => 'Benzeno e seus compostos tóxicos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.03.002', 'rh233_descricao' => 'Estireno (vinilbenzeno)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.04.001', 'rh233_descricao' => 'Berílio e seus compostos tóxico'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.05.001', 'rh233_descricao' => 'Bromo e seus compostos tóxicos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.06.001', 'rh233_descricao' => 'Cádmio e seus compostos tóxicos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.07.001', 'rh233_descricao' => 'Carvão mineral e seus derivados'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.08.001', 'rh233_descricao' => 'Chumbo e seus compostos tóxicos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.09.001', 'rh233_descricao' => 'Cloro e seus compostos tóxicos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.09.002', 'rh233_descricao' => 'Metileno-ortocloroanilina, MOCA®'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.09.003', 'rh233_descricao' => 'Bis (cloro metil) éter'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.09.004', 'rh233_descricao' => 'Biscloroetileter'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.09.005', 'rh233_descricao' => 'Clorambucil'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.09.006', 'rh233_descricao' => 'Cloropreno'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.10.001', 'rh233_descricao' => 'Cromo e seus compostos tóxicos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.11.001', 'rh233_descricao' => 'Dissulfeto de carbono'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.12.001', 'rh233_descricao' => 'Fósforo e seus compostos tóxicos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.13.001', 'rh233_descricao' => 'Iodo'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.14.001', 'rh233_descricao' => 'Manganês e seus compostos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.15.001', 'rh233_descricao' => 'Mercúrio e seus compostos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.16.001', 'rh233_descricao' => 'Níquel e seus compostos tóxicos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.17.001', 'rh233_descricao' => 'Petróleo, xisto betuminoso, gás natural e seus derivados'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.18.001', 'rh233_descricao' => 'Sílica livre'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.001', 'rh233_descricao' => 'Butadieno-estireno'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.002', 'rh233_descricao' => 'Acrilonitrila'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.003', 'rh233_descricao' => '1-3-butadieno'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.004', 'rh233_descricao' => 'Mercaptanos (tióis)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.005', 'rh233_descricao' => 'n-hexano'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.006', 'rh233_descricao' => 'Diisocianato de tolueno (TDI)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.007', 'rh233_descricao' => 'Aminas aromáticas'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.008', 'rh233_descricao' => 'Aminobifenila (4-aminodifenil)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.009', 'rh233_descricao' => 'Auramina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.010', 'rh233_descricao' => 'Azatioprina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.011', 'rh233_descricao' => '1-4-butanodiol'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.012', 'rh233_descricao' => 'Dimetanosulfonato (MIRELAN)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.013', 'rh233_descricao' => 'Ciclofosfamida'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.014', 'rh233_descricao' => 'Dietiletil-bestrol'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.015', 'rh233_descricao' => 'Acronitrila'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.016', 'rh233_descricao' => 'Nitronaftilamina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.017', 'rh233_descricao' => '4-dimetil-aminoazobenzeno'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.018', 'rh233_descricao' => 'Benzopireno'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.019', 'rh233_descricao' => 'Beta-pbiscloromeropiolactona (beta-propiolactona)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.021', 'rh233_descricao' => 'Dianizidina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.022', 'rh233_descricao' => 'Dietilsulfato'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.023', 'rh233_descricao' => 'Dimetilsulfato'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.024', 'rh233_descricao' => 'Etilenoamina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.025', 'rh233_descricao' => 'Etilenotiureia'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.026', 'rh233_descricao' => 'Fenacetina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.027', 'rh233_descricao' => 'Iodeto de metila'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.028', 'rh233_descricao' => 'Etilnitrosureia'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.029', 'rh233_descricao' => 'Nitrosamina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.030', 'rh233_descricao' => 'Ortotoluidina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.031', 'rh233_descricao' => 'Oximetalona (oxime-talona)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.032', 'rh233_descricao' => 'Procarbazina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.033', 'rh233_descricao' => 'Propanosultona'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.034', 'rh233_descricao' => 'Óxido de etileno'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.035', 'rh233_descricao' => 'Estilbenzeno'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.036', 'rh233_descricao' => 'Creosoto'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.038', 'rh233_descricao' => 'Benzidina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.039', 'rh233_descricao' => 'Betanaftilamina'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.040', 'rh233_descricao' => '1-cloro-2,4-nitrodifenil'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '01.19.041', 'rh233_descricao' => '3-poxipro-pano'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.001', 'rh233_descricao' => 'Ruído'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.002', 'rh233_descricao' => 'Vibrações localizadas (mão-braço)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.003', 'rh233_descricao' => 'Vibração de corpo inteiro (aceleração resultante de exposição normalizada - aren)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.004', 'rh233_descricao' => 'Vibração de corpo inteiro (Valor da Dose de Vibração Resultante - VDVR)'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.005', 'rh233_descricao' => 'Trabalhos com perfuratrizes e marteletes pneumáticos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.006', 'rh233_descricao' => 'Radiações ionizantes'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.007', 'rh233_descricao' => 'Extração e beneficiamento de minerais radioativos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.008', 'rh233_descricao' => 'Atividades em minerações com exposição ao radônio'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.009', 'rh233_descricao' => 'Realização de manutenção e supervisão em unidades de extração, tratamento e beneficiamento de minerais radioativos com exposição às radiações ionizantes'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.010', 'rh233_descricao' => 'Operações com reatores nucleares ou com fontes radioativas'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.011', 'rh233_descricao' => 'Trabalhos realizados com exposição aos raios Alfa, Beta, Gama e X, aos nêutrons e às substâncias radioativas para fins industriais, terapêuticos e diagnósticos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.012', 'rh233_descricao' => 'Fabricação e manipulação de produtos radioativos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.013', 'rh233_descricao' => 'Pesquisas e estudos com radiações ionizantes em laboratórios'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.014', 'rh233_descricao' => 'Trabalhos com exposição ao calor acima dos limites de tolerância estabelecidos na NR-15, da Portaria 3.214/1978'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.015', 'rh233_descricao' => 'Pressão atmosférica anormal'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.016', 'rh233_descricao' => 'Trabalhos em caixões ou câmaras hiperbáricas'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.017', 'rh233_descricao' => 'Trabalhos em tubulões ou túneis sob ar comprimido'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '02.01.018', 'rh233_descricao' => 'Operações de mergulho com o uso de escafandros ou outros equipamentos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '03.01.001', 'rh233_descricao' => 'Trabalhos em estabelecimentos de saúde com contato com pacientes portadores de doenças infectocontagiosas ou com manuseio de materiais contaminados'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '03.01.002', 'rh233_descricao' => 'Trabalhos com animais infectados para tratamento ou para o preparo de soro, vacinas e outros produtos'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '03.01.003', 'rh233_descricao' => 'Trabalhos em laboratórios de autópsia, de anatomia e anátomo-histologia'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '03.01.004', 'rh233_descricao' => 'Trabalho de exumação de corpos e manipulação de resíduos de animais deteriorados'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '03.01.005', 'rh233_descricao' => 'Trabalhos em galerias, fossas e tranques de esgoto'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '03.01.006', 'rh233_descricao' => 'Esvaziamento de biodigestores'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '03.01.007', 'rh233_descricao' => 'Coleta e industrialização do lixo'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '04.01.001', 'rh233_descricao' => 'Mineração subterrânea cujas atividades sejam exercidas afastadas das frentes de produção'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '04.01.002', 'rh233_descricao' => 'Trabalhos em atividades permanentes no subsolo de minerações subterrâneas em frente de produção'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '05.01.001', 'rh233_descricao' => 'Agentes nocivos não constantes no Anexo IV do Decreto 3.048/1999 e incluídos por força de decisão judicial ou administrativa'],
            ['rh233_sequencial' => DB::raw("nextval('rhagente_rh233_sequencial_seq')"), 'rh233_codigo' => '09.01.001', 'rh233_descricao' => 'Ausência de agente nocivo ou de atividades previstas no Anexo IV do Decreto 3.048/199']
        ]);

        //TABELA inforelativasresp
        Schema::create('pessoal.inforelativasresp', function ($table) {
            $table->bigInteger('rh234_sequencial');
            $table->integer('rh234_regist')->notNullable();
            $table->string('rh234_cpf', 11)->nullable();
            $table->integer('rh234_orgao')->nullable();
            $table->text('rh234_descrorgao')->nullable();
            $table->integer('rh234_numinscricao')->nullable();
            $table->string('rh234_uf', 2)->nullable();

            $table->primary(['rh234_regist']);

            $table->foreign('rh234_regist')
                ->references('rh01_regist')
                ->on('rhpessoal')
                ->onDelete('cascade');
        });

        DB::statement("
            CREATE SEQUENCE IF NOT EXISTS pessoal.inforelativasresp_rh234_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
        ");

        DB::table('db_syscampo')->insert([
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh234_sequencial',
                'conteudo' => 'int4',
                'descricao' => 'Sequencial',
                'valorinicial' => 0,
                'rotulo' => 'Sequencial',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'Sequencial',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh234_regist',
                'conteudo' => 'int4',
                'descricao' => 'Matricula do servidor',
                'valorinicial' => 0,
                'rotulo' => 'Matricula',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'Matricula',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh234_cpf',
                'conteudo' => 'varchar(11)',
                'descricao' => 'CPF',
                'valorinicial' => '',
                'rotulo' => 'CPF',
                'tamanho' => 11,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'CPF',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh234_orgao',
                'conteudo' => 'int4',
                'descricao' => 'Órgão',
                'valorinicial' => '',
                'rotulo' => 'Órgão',
                'tamanho' => 1,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Órgão',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh234_descrorgao',
                'conteudo' => 'text',
                'descricao' => 'Descrição órgão',
                'valorinicial' => '',
                'rotulo' => 'Descrição órgão',
                'tamanho' => 10,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Descrição órgão',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh234_numinscricao',
                'conteudo' => 'int8',
                'descricao' => 'Numero inscrição',
                'valorinicial' => '',
                'rotulo' => 'Numero inscrição',
                'tamanho' => 14,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Numero inscrição',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh234_uf',
                'conteudo' => 'varchar(2)',
                'descricao' => 'UF',
                'valorinicial' => '',
                'rotulo' => 'UF',
                'tamanho' => 255,
                'nulo' => false,
                'maiusculo' => false,
                'autocompl' => false,
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'UF',
            ]
        ]);

        //ADICIONAR DADOS db_sysarquivo
        DB::table('db_sysarquivo')->insert([
            [
                'codarq' => DB::raw("nextval('db_sysarquivo_codarq_seq')"),
                'nomearq' => 'infoambiente',
                'descricao' => 'Informações do Ambiente - SST',
                'sigla' => 'rh230',
                'dataincl' => '2023-08-01',
                'rotulo' => 'Informações do Ambiente - SST',
                'tipotabela' => 2,
                'naolibclass' => false,
                'naolibfunc' => false,
                'naolibprog' => false,
                'naolibform' => false,
            ],
            [
                'codarq' => DB::raw("nextval('db_sysarquivo_codarq_seq')"),
                'nomearq' => 'atividadesdesempenhadas',
                'descricao' => 'Atividades Desempenhadas - SST',
                'sigla' => 'rh231',
                'dataincl' => '2023-08-01',
                'rotulo' => 'Atividades Desempenhadas - SST',
                'tipotabela' => 2,
                'naolibclass' => false,
                'naolibfunc' => false,
                'naolibprog' => false,
                'naolibform' => false,
            ],
            [
                'codarq' => DB::raw("nextval('db_sysarquivo_codarq_seq')"),
                'nomearq' => 'agentesnocivos',
                'descricao' => 'Agente Nocivos - SST',
                'sigla' => 'rh232',
                'dataincl' => '2023-08-01',
                'rotulo' => 'Agente Nocivos - SST',
                'tipotabela' => 2,
                'naolibclass' => false,
                'naolibfunc' => false,
                'naolibprog' => false,
                'naolibform' => false,
            ],
            [
                'codarq' => DB::raw("nextval('db_sysarquivo_codarq_seq')"),
                'nomearq' => 'inforelativasresp',
                'descricao' => 'Informações Relativas ao Responsável Pelos Registros Ambientais - SST',
                'sigla' => 'rh234',
                'dataincl' => '2023-08-01',
                'rotulo' => 'Info Rel. ao Resp Pelos Registros Ambientais - SST',
                'tipotabela' => 2,
                'naolibclass' => false,
                'naolibfunc' => false,
                'naolibprog' => false,
                'naolibform' => false,
            ]
        ]);

        //ADICIONAR VINCULOS db_syscampo COM db_sysarquivo
        DB::table('db_sysarqcamp')->insert([
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'infoambiente')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh230_regist')->value('codcam'),
                'seqarq' => 1,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'infoambiente')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh230_data')->value('codcam'),
                'seqarq' => 2,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'infoambiente')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh230_descricao')->value('codcam'),
                'seqarq' => 3,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'infoambiente')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh230_instit')->value('codcam'),
                'seqarq' => 4,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'atividadesdesempenhadas')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh231_regist')->value('codcam'),
                'seqarq' => 1,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'atividadesdesempenhadas')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh231_descricao')->value('codcam'),
                'seqarq' => 2,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_sequencial')->value('codcam'),
                'seqarq' => 1,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_regist')->value('codcam'),
                'seqarq' => 2,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_agente')->value('codcam'),
                'seqarq' => 3,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_tipoavaliacao')->value('codcam'),
                'seqarq' => 4,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_icdexposicao')->value('codcam'),
                'seqarq' => 5,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_ltolerancia')->value('codcam'),
                'seqarq' => 6,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_unidade')->value('codcam'),
                'seqarq' => 7,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_tecnicamed')->value('codcam'),
                'seqarq' => 8,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epc')->value('codcam'),
                'seqarq' => 9,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epceficaz')->value('codcam'),
                'seqarq' => 10,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epi')->value('codcam'),
                'seqarq' => 11,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epieficaz')->value('codcam'),
                'seqarq' => 12,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epicertificado')->value('codcam'),
                'seqarq' => 13,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epidescricao')->value('codcam'),
                'seqarq' => 14,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epiporinviabilidade')->value('codcam'),
                'seqarq' => 15,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epiobscondicoes')->value('codcam'),
                'seqarq' => 16,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epiobsuso')->value('codcam'),
                'seqarq' => 17,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_epiobsprazo')->value('codcam'),
                'seqarq' => 18,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_obsperiodicidade')->value('codcam'),
                'seqarq' => 19,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'agentesnocivos')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh232_obshigienizacao')->value('codcam'),
                'seqarq' => 20,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'inforelativasresp')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh234_sequencial')->value('codcam'),
                'seqarq' => 1,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'inforelativasresp')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh234_regist')->value('codcam'),
                'seqarq' => 2,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'inforelativasresp')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh234_cpf')->value('codcam'),
                'seqarq' => 3,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'inforelativasresp')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh234_orgao')->value('codcam'),
                'seqarq' => 4,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'inforelativasresp')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh234_descrorgao')->value('codcam'),
                'seqarq' => 5,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'inforelativasresp')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh234_numinscricao')->value('codcam'),
                'seqarq' => 6,
                'codsequencia' => 0,
            ],
            [
                'codarq' => DB::table('db_sysarquivo')->where('nomearq', 'inforelativasresp')->value('codarq'),
                'codcam' => DB::table('db_syscampo')->where('nomecam', 'rh234_uf')->value('codcam'),
                'seqarq' => 7,
                'codsequencia' => 0,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TABLE IF EXISTS pessoal.rhagente');
        DB::statement('DROP TABLE IF EXISTS pessoal.inforelativasresp');
        DB::statement('DROP TABLE IF EXISTS pessoal.agentesnocivos');
        DB::statement('DROP TABLE IF EXISTS pessoal.atividadesdesempenhadas');
        DB::statement('DROP TABLE IF EXISTS pessoal.infoambiente');

        DB::statement('DROP SEQUENCE IF EXISTS agentesnocivos_rh232_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS rhagente_rh233_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS inforelativasresp_rh234_sequencial_seq');

        DB::statement('DELETE FROM db_sysarqcamp WHERE codarq IN (SELECT codarq FROM db_sysarquivo WHERE nomearq = "rhagente")');
        DB::statement('DELETE FROM db_sysarqcamp WHERE codarq IN (SELECT codarq FROM db_sysarquivo WHERE nomearq = "infoambiente")');
        DB::statement('DELETE FROM db_sysarqcamp WHERE codarq IN (SELECT codarq FROM db_sysarquivo WHERE nomearq = "atividadesdesempenhadas")');
        DB::statement('DELETE FROM db_sysarqcamp WHERE codarq IN (SELECT codarq FROM db_sysarquivo WHERE nomearq = "agentesnocivos")');
        DB::statement('DELETE FROM db_sysarqcamp WHERE codarq IN (SELECT codarq FROM db_sysarquivo WHERE nomearq = "inforelativasresp")');

        DB::statement('DELETE FROM db_sysarquivo WHERE nomearq IN ("infoambiente", "atividadesdesempenhadas", "agentesnocivos", "inforelativasresp", "rhagente")');

        DB::statement('DELETE FROM db_syscampo WHERE nomecam LIKE "%rh230%"');
        DB::statement('DELETE FROM db_syscampo WHERE nomecam LIKE "%rh231%"');
        DB::statement('DELETE FROM db_syscampo WHERE nomecam LIKE "%rh232%"');
        DB::statement('DELETE FROM db_syscampo WHERE nomecam LIKE "%rh233%"');
        DB::statement('DELETE FROM db_syscampo WHERE nomecam LIKE "%rh234%"');

        DB::statement('DELETE FROM db_menu WHERE id_item IN (SELECT id_item FROM db_itensmenu WHERE help LIKE "%Menu SST%")');
        DB::statement('DELETE FROM db_menu WHERE id_item IN (SELECT id_item FROM db_itensmenu WHERE help LIKE "%Condições Ambientais%")');

        DB::statement('DELETE FROM db_itensmenu WHERE help IN ("Menu SST", "Condições Ambientais - SST", "Inclusão Condições Ambientais", "Alteração Condições Ambientais", "Exclusão Condições Ambientais")');
    }
}
