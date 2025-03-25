<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Oc23718 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aoc102025', function (Blueprint $table) {
            if (!Schema::hasColumn('aoc102025', 'si38_datapublicacao')) {
                $table->date('si38_datapublicacao')->nullable();
            }
            if (!Schema::hasColumn('aoc102025', 'si38_localpublicacao')) {
                $table->string('si38_localpublicacao', 1000)->nullable();
            }
        });

        DB::table('aoc102025')->update([
            'si38_datapublicacao' => DB::raw('si38_datadecreto'),
            'si38_localpublicacao' => 'NO QUADRO DE AVISOS DO MUNICIPIO'
        ]);

        Schema::table('aoc102025', function (Blueprint $table) {
            $table->string('si38_localpublicacao', 1000)->nullable(false)->change();
        });

        Schema::table('orcprojeto', function (Blueprint $table) {
            if (!Schema::hasColumn('orcprojeto', 'o39_datapublicacao')) {
                $table->date('o39_datapublicacao')->nullable();
            }
            if (!Schema::hasColumn('orcprojeto', 'o39_localpublicacao')) {
                $table->string('o39_localpublicacao', 1000)->nullable();
            }
        });

        DB::table('orcprojeto')->update([
            'o39_datapublicacao' => DB::raw('o39_data'),
            'o39_localpublicacao' => 'NO QUADRO DE AVISOS DO MUNICIPIO'
        ]);

        Schema::table('orcprojeto', function (Blueprint $table) {
            $table->string('o39_localpublicacao', 1000)->nullable(false)->change();
        });

        DB::statement("SELECT setval('db_syscampo_codcam_seq', (SELECT max(codcam) FROM db_syscampo));");

        DB::table('db_syscampo')->insert([
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'o39_datapublicacao',
                'conteudo' => 'date',
                'descricao' => 'Data de Publicacao do Decreto',
                'valorinicial' => null,
                'rotulo' => 'Data de Publicacao do Decreto',
                'tamanho' => 10,
                'nulo' => 'f',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'Data de Publicacao do Decreto',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'o39_localpublicacao',
                'conteudo' => 'varchar(1000)',
                'descricao' => 'Local de Publicacao do Decreto',
                'valorinicial' => null,
                'rotulo' => 'Local de Publicacao do Decreto',
                'tamanho' => 1000,
                'nulo' => 'f',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 0,
                'tipoobj' => 'text',
                'rotulorel' => 'Local de Publicacao do Decreto',
            ]
        ]);

        $codDataPublicacao = DB::table('db_syscampo')
            ->where('nomecam', 'o39_datapublicacao')
            ->value('codcam');

        $codLocalPublicacao = DB::table('db_syscampo')
            ->where('nomecam', 'o39_localpublicacao')
            ->value('codcam');

        DB::table(('db_sysarqcamp'))->insert([
            [
                'codarq' => 969,
                'codcam' => $codDataPublicacao,
                'seqarq' => 15,
                'codsequencia' => 0
            ],
            [
                'codarq' => 969,
                'codcam' => $codLocalPublicacao,
                'seqarq' => 16,
                'codsequencia' => 0
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
        $codDataPublicacao = DB::table('db_syscampo')
            ->where('nomecam', 'o39_datapublicacao')
            ->value('codcam');

        $codLocalPublicacao = DB::table('db_syscampo')
            ->where('nomecam', 'o39_localpublicacao')
            ->value('codcam');

        DB::table('db_sysarqcamp')->whereIn('codcam', [$codDataPublicacao, $codLocalPublicacao ])->delete();

        DB::table('db_syscampo')->whereIn('nomecam', ['o39_datapublicacao', 'o39_localpublicacao' ])->delete();

        Schema::table('orcprojeto', function (Blueprint $table) {
            $table->dropColumn('o39_datapublicacao');
        });

        Schema::table('orcprojeto', function (Blueprint $table) {
            $table->dropColumn('o39_localpublicacao');
        });

        Schema::table('aoc102025', function (Blueprint $table) {
            $table->dropColumn('si38_datapublicacao');
        });

        Schema::table('aoc102025', function (Blueprint $table) {
            $table->dropColumn('si38_localpublicacao');
        });
    }
}
