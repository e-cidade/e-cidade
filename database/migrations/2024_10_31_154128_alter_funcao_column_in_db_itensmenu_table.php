<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFuncaoColumnInDbItensmenuTable extends Migration
{
    public function up()
    {
        Schema::table('configuracoes.db_itensmenu', function (Blueprint $table) {
            $table->text('funcao')->change();
        });
    }

    public function down()
    {
        Schema::table('configuracoes.db_itensmenu', function (Blueprint $table) {
            $table->string('funcao', 100)->change();
        });
    }
}
