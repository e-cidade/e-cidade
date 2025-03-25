<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OC23064 extends Migration
{
    const COLUMN_NAME = 'e30_empsolicitadesdobramento';
    const TABLE_NAME = 'empenho.empparametro';
    const DESCR_NAME = 'Altera Desdobramento Emp. Proc. Compras';

    public function up()
    {

        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->boolean(self::COLUMN_NAME)->default(false);
        });

        $this->dataDictionary();
    }

    public function down()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn(self::COLUMN_NAME);
        });

        $columnNameToDelete = self::COLUMN_NAME;
        DB::table('configuracoes.db_sysarqcamp')->where('codcam', DB::raw("(SELECT codcam FROM configuracoes.db_syscampo WHERE nomecam = '$columnNameToDelete')"))->delete();
        DB::table('configuracoes.db_syscampo')->where('nomecam', $columnNameToDelete)->delete();
    }

    /**
     * @return void
     */
    public function dataDictionary(): void
    {
        $columnName = self::COLUMN_NAME;
        $descrName = self::DESCR_NAME;
        DB::table('configuracoes.db_syscampo')->insert([
            'codcam' => DB::raw('(SELECT max(codcam) + 1 FROM configuracoes.db_syscampo)'),
            'nomecam' => "$columnName",
            'conteudo' => 'bool',
            'descricao' => "$descrName",
            'valorinicial' => 'f',
            'rotulo' => "$descrName",
            'tamanho' => 1,
            'nulo' => 'f',
            'maiusculo' => 'f',
            'autocompl' => 'f',
            'aceitatipo' => 5,
            'tipoobj' => 'text',
            'rotulorel' => "$descrName"
        ]);

        $reference = ".";
        $tableName = strpos(self::TABLE_NAME, $reference) === false ? self::TABLE_NAME : substr(self::TABLE_NAME, strpos(self::TABLE_NAME, $reference) + 1);

        DB::table('configuracoes.db_sysarqcamp')->insert([
            'codarq' => DB::raw("(SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq =  '$tableName')"),
            'codcam' => DB::raw("(SELECT codcam FROM configuracoes.db_syscampo WHERE nomecam = '$columnName')"),
            'seqarq' => 22,
            'codsequencia' => 0,
        ]);
    }
}
