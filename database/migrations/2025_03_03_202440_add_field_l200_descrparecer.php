<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldL200Descrparecer extends Migration
{
    public function up()
    {
        Schema::table('parecerlicitacao', function (Blueprint $table) {
            if (!Schema::hasColumn('parecerlicitacao', 'l200_descrparecer')) {
                $table->string('l200_descrparecer', 250)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('parecerlicitacao', function (Blueprint $table) {
            if (Schema::hasColumn('parecerlicitacao', 'l200_descrparecer')) {
                $table->dropColumn('l200_descrparecer');
            }
        });
    }
}
