<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Oc23432 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoal.convenio', function (Blueprint $table) {
            $table->string('r56_posq04', 6)->nullable();
            $table->string('r56_posq05', 6)->nullable();
            $table->string('r56_posq06', 6)->nullable();
            $table->dropColumn(['r56_vq01', 'r56_vq02', 'r56_vq03']);
        });

        // Ajuste 'pessoal.movrel'
        Schema::table('pessoal.movrel', function (Blueprint $table) {
            $table->double('r54_quant4', 15, 8)->default(0);
            $table->double('r54_quant5', 15, 8)->default(0);
            $table->double('r54_quant6', 15, 8)->default(0);
        });

        // Atualiza sequência da 'db_syscampo'
        DB::statement("
            SELECT setval('db_syscampo_codcam_seq', (SELECT max(codcam) FROM db_syscampo));
        ");

        // Insere novos registros na 'db_syscampo'
        DB::table('db_syscampo')->insert([
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'r56_posq04',
                'conteudo' => 'varchar(6)',
                'descricao' => 'Posicao Qtd. Rubrica 1',
                'valorinicial' => null,
                'rotulo' => 'Qtd. Rubrica 1',
                'tamanho' => 6,
                'nulo' => 't',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'qtd. rubrica 1',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'r56_posq05',
                'conteudo' => 'varchar(6)',
                'descricao' => 'Posicao Qtd. Rubrica 2',
                'valorinicial' => null,
                'rotulo' => 'Qtd. Rubrica 2',
                'tamanho' => 6,
                'nulo' => 't',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'qtd. rubrica 2',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'r56_posq06',
                'conteudo' => 'varchar(6)',
                'descricao' => 'Posicao Qtd. Rubrica 3',
                'valorinicial' => null,
                'rotulo' => 'Qtd. Rubrica 3',
                'tamanho' => 6,
                'nulo' => 't',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 1,
                'tipoobj' => 'text',
                'rotulorel' => 'qtd. rubrica 3',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'r54_quant4',
                'conteudo' => 'double precision',
                'descricao' => 'Quantidade da rubrica 1',
                'valorinicial' => 0,
                'rotulo' => 'Qtd. Rubrica 1',
                'tamanho' => 15,
                'nulo' => 'f',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 4,
                'tipoobj' => 'text',
                'rotulorel' => 'Qtd. Rub 1',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'r54_quant5',
                'conteudo' => 'double precision',
                'descricao' => 'Quantidade da rubrica 2',
                'valorinicial' => 0,
                'rotulo' => 'Qtd. Rubrica 2',
                'tamanho' => 15,
                'nulo' => 'f',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 4,
                'tipoobj' => 'text',
                'rotulorel' => 'Qtd. Rub 2',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'r54_quant6',
                'conteudo' => 'double precision',
                'descricao' => 'Quantidade da rubrica 3',
                'valorinicial' => 0,
                'rotulo' => 'Qtd. Rubrica 3',
                'tamanho' => 15,
                'nulo' => 'f',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 4,
                'tipoobj' => 'text',
                'rotulorel' => 'Qtd. Rub 3',
            ],
        ]);

        // Insere novos registros na db_sysarqcamp
        // Antes consulta o valor de codcam na db_syscampo
        $codr56posq04 = DB::table('db_syscampo')
            ->where('nomecam', 'r56_posq04')
            ->value('codcam');

        $codr56posq05 = DB::table('db_syscampo')
            ->where('nomecam', 'r56_posq05')
            ->value('codcam');

        $codr56posq06 = DB::table('db_syscampo')
            ->where('nomecam', 'r56_posq06')
            ->value('codcam');

        $codr54quant4 = DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant4')
            ->value('codcam');

        $codr54quant5 = DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant5')
            ->value('codcam');

        $codr54quant6 = DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant6')
            ->value('codcam');

        DB::table(('db_sysarqcamp'))->insert([
            [
                'codarq' => 542,
                'codcam' => $codr56posq04,
                'seqarq' => 18,
                'codsequencia' => 0
            ],
            [
                'codarq' => 542,
                'codcam' => $codr56posq05,
                'seqarq' => 19,
                'codsequencia' => 0
            ],
            [
                'codarq' => 542,
                'codcam' => $codr56posq06,
                'seqarq' => 20,
                'codsequencia' => 0
            ],
            [
                'codarq' => 566,
                'codcam' => $codr54quant4,
                'seqarq' => 10,
                'codsequencia' => 0
            ],
            [
                'codarq' => 566,
                'codcam' => $codr54quant5,
                'seqarq' => 11,
                'codsequencia' => 0
            ],
            [
                'codarq' => 566,
                'codcam' => $codr54quant6,
                'seqarq' => 12,
                'codsequencia' => 0
            ],
        ]);

        // Atualizar campos existentes em 'db_syscampo'
        DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant1')
            ->update(['descricao' => 'Valor da Rubrica 1', 'rotulo' => 'Vlr. Rubrica 1', 'rotulorel' => 'Vlr. Rub 1']);

        DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant2')
            ->update(['descricao' => 'Valor da Rubrica 2', 'rotulo' => 'Vlr. Rubrica 2', 'rotulorel' => 'Vlr. Rub 2']);

        DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant3')
            ->update(['descricao' => 'Valor da Rubrica 3', 'rotulo' => 'Vlr. Rubrica 3', 'rotulorel' => 'Vlr. Rub 3']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverter alterações na tabela 'pessoal.convenio'
        Schema::table('pessoal.convenio', function (Blueprint $table) {
            $table->dropColumn(['r56_posq04', 'r56_posq05', 'r56_posq06']);
            $table->string('r56_vq01')->nullable();
            $table->string('r56_vq02')->nullable();
            $table->string('r56_vq03')->nullable();
        });

        // Reverter alterações na tabela 'pessoal.movrel'
        Schema::table('pessoal.movrel', function (Blueprint $table) {
            $table->dropColumn(['r54_quant4', 'r54_quant5', 'r54_quant6']);
        });

        // Remover os registros inseridos
        DB::table('db_syscampo')->whereIn('nomecam', [
            'r56_posq04',
            'r56_posq05',
            'r56_posq06',
            'r54_quant4',
            'r54_quant5',
            'r54_quant6',
        ])->delete();

        // Restaurar as descrições originais dos campos
        DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant1')
            ->update(['descricao' => 'Quantidade da rubrica 1', 'rotulo' => 'Rubrica 1', 'rotulorel' => 'Rub 1']);

        DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant2')
            ->update(['descricao' => 'Quantidade da rubrica 2', 'rotulo' => 'Rubrica 2', 'rotulorel' => 'Rub 2']);

        DB::table('db_syscampo')
            ->where('nomecam', 'r54_quant3')
            ->update(['descricao' => 'Quantidade da rubrica 3', 'rotulo' => 'Rubrica 3', 'rotulorel' => 'Rub 3']);
    }
}
