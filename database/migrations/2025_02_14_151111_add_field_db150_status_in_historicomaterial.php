<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldDb150StatusInHistoricomaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicomaterial', function (Blueprint $table) {
            if (!Schema::hasColumn('historicomaterial', 'db150_status')) {
                $table->boolean('db150_status')->default(false);
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
        Schema::table('historicomaterial', function (Blueprint $table) {
            if (Schema::hasColumn('historicomaterial', 'db150_status')) {
                $table->dropColumn('db150_status');
            }
        });
    }
}
