<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsTelaLicitacoes extends Migration
{
    public function up()
    {
        Schema::table('liclicita', function (Blueprint $table) {
            if (!Schema::hasColumn('liclicita', 'l20_inversaofases')) {
                $table->integer('l20_inversaofases')->nullable();
            }

            if (!Schema::hasColumn('liclicita', 'l20_descrcriterio')) {
                $table->text('l20_descrcriterio')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('liclicita', function (Blueprint $table) {
            if (Schema::hasColumn('liclicita', 'l20_inversaofases')) {
                $table->dropColumn('l20_inversaofases');
            }

            if (Schema::hasColumn('liclicita', 'l20_descrcriterio')) {
                $table->dropColumn('l20_descrcriterio');
            }
        });
    }
}
