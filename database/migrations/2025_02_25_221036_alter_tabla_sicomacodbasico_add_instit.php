<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablaSicomacodbasicoAddInstit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sicomacodbasico', function (Blueprint $table) {
            if (!Schema::hasColumn('sicomacodbasico', 'l228_instit')) {
                $table->bigInteger('l228_instit')->nullable();
                $table->foreign('l228_instit')
                    ->references('codigo')
                    ->on('db_config')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sicomacodbasico', function (Blueprint $table) {
            if (Schema::hasColumn('sicomacodbasico', 'l228_instit')) {
                $table->dropForeign('l228_instit');
                $table->dropColumn('l228_instit');
            }
        });
    }
}
