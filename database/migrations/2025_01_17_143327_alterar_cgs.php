<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterarCgs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ambulatorial.cgs_und', function (Blueprint $table) {
            $table->bigInteger('z01_i_numtfd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ambulatorial.cgs_und', function (Blueprint $table) {
            $table->dropColumn('z01_i_numtfd');
        });
    }
}
