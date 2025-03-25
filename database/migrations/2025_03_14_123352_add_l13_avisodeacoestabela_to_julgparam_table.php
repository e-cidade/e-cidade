<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddL13AvisodeacoestabelaToJulgparamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('julgparam', function (Blueprint $table) {
            $table->boolean('l13_avisodeacoestabela')->default(false)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('julgparam', function (Blueprint $table) {
            $table->dropColumn('l13_avisodeacoestabela');
        });
    }
}