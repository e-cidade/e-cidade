<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldDb150CodunidHistoricomaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicomaterial', function (Blueprint $table) {
            $table->bigInteger('db150_codunid')->nullable(); 
            $table->foreign('db150_codunid')->references('m61_codmatunid')->on('matunid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historicomaterial', function (Blueprint $table) {
            $table->dropForeign(['db150_codunid']);
            $table->dropColumn('db150_codunid');
        });
    }
}
