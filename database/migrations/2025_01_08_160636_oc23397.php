<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Oc23397 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insere os novos campos na rhpessoalmov
        Schema::table('rhpessoalmov', function (Blueprint $table) {
            $table->boolean('rh02_outrovincefetivo')->default(false);
            $table->boolean('rh02_remcargoefetivo')->default(false);
        });

        // Atualiza sequência da 'db_syscampo'
        DB::statement("
                SELECT setval('db_syscampo_codcam_seq', (SELECT max(codcam) FROM db_syscampo));
            ");

        // Insere os novos registros na db_syscampo
        DB::table('db_syscampo')->insert([
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh02_outrovincefetivo',
                'conteudo' => 'bool',
                'descricao' => 'Possui Outro Vínculo Efetivo',
                'valorinicial' => 'f',
                'rotulo' => 'Possui Outro Vínculo Efetivo',
                'tamanho' => 1,
                'nulo' => 'f',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 5,
                'tipoobj' => 'text',
                'rotulorel' => 'Possui Outro Vínculo Efetivo',
            ],
            [
                'codcam' => DB::raw("nextval('db_syscampo_codcam_seq')"),
                'nomecam' => 'rh02_remcargoefetivo',
                'conteudo' => 'bool',
                'descricao' => 'Optou Pela Rem. Cargo Efetivo',
                'valorinicial' => 'f',
                'rotulo' => 'Optou Pela Rem. Cargo Efetivo',
                'tamanho' => 1,
                'nulo' => 'f',
                'maiusculo' => 'f',
                'autocompl' => 'f',
                'aceitatipo' => 5,
                'tipoobj' => 'text',
                'rotulorel' => 'Optou Pela Rem. Cargo Efetivo',
            ],
        ]);

        // Insere os novos registros na db_sysarqcamp
        DB::table('db_sysarqcamp')->insert([
            [
                'codarq' => 1158,
                'codcam' => DB::raw('(SELECT codcam FROM db_syscampo WHERE nomecam = \'rh02_outrovincefetivo\')'),
                'seqarq' => 114,
                'codsequencia' => 0,
            ],
            [
                'codarq' => 1158,
                'codcam' => DB::raw('(SELECT codcam FROM db_syscampo WHERE nomecam = \'rh02_remcargoefetivo\')'),
                'seqarq' => 115,
                'codsequencia' => 0,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove as colunas inseridas na rhpessoalmov
        Schema::table('rhpessoalmov', function (Blueprint $table) {
            $table->dropColumn(['rh02_outrovincefetivo', 'rh02_remcargoefetivo']);
        });

        // Remove os registros criados em db_syscampo
        DB::table('db_syscampo')->whereIn('nomecam', ['rh02_outrovincefetivo', 'rh02_remcargoefetivo'])->delete();

        // Remove os vinculos criados em db_sysarqcamp
        DB::table('db_sysarqcamp')->whereIn('codcam', function ($query) {
            $query->select('codcam')->from('db_syscampo')
                ->whereIn('nomecam', ['rh02_outrovincefetivo', 'rh02_remcargoefetivo']);
        })->delete();
    }
}
