<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableHistoricosicomenvioAddSeqremessa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicocgmenviosicom', function (Blueprint $table) {
            if (!Schema::hasColumn('historicocgmenviosicom', 'z18_seqremessa')) {
                $table->bigInteger('z18_seqremessa')->nullable();
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
        Schema::table('historicocgmenviosicom', function (Blueprint $table) {
            if (Schema::hasColumn('historicocgmenviosicom', 'z18_seqremessa')) {
                $table->dropColumn('z18_seqremessa');
            }
        });
    }
}
