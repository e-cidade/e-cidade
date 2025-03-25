<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Oc22987 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public.movimentacaodedivida', function (Blueprint $table) {
            $table->boolean('op02_movautomatica')->nullable();
            $table->bigInteger('op02_codigoplanilha')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public.movimentacaodedivida', function (Blueprint $table) {
            $table->dropColumn('op02_movautomatica');
            $table->dropColumn('op02_codigoplanilha');
        });

    }
}
