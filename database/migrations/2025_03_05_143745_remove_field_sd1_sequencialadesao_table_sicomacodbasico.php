<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldSd1SequencialadesaoTableSicomacodbasico extends Migration
{
    public function up()
    {
        Schema::table('sicom.sicomacodbasico', function (Blueprint $table) {
            if (Schema::hasColumn('sicom.sicomacodbasico', 'sd1_sequencialadesao')) {
                $table->dropColumn('sd1_sequencialadesao');
            }
        });
    }

    public function down()
    {
        Schema::table('sicom.sicomacodbasico', function (Blueprint $table) {
            if (!Schema::hasColumn('sicom.sicomacodbasico', 'sd1_sequencialadesao')) {
                $table->integer('sd1_sequencialadesao')->default(0);
            }
        });
    }
}
