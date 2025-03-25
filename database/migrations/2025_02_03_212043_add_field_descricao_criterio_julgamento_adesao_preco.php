<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldDescricaoCriterioJulgamentoAdesaoPreco extends Migration
{
    public function up()
    {
        Schema::table('adesaoregprecos', function (Blueprint $table) {
            if (!Schema::hasColumn('adesaoregprecos', 'si06_descrcriterioutilizado')) {
                $table->string('si06_descrcriterioutilizado', 250)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('adesaoregprecos', function (Blueprint $table) {
            if (Schema::hasColumn('adesaoregprecos', 'si06_descrcriterioutilizado')) {
                $table->dropColumn('si06_descrcriterioutilizado');
            }
        });
    }
}
