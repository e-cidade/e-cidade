<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldZ09StatusHistoricocgm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historicocgm', function (Blueprint $table) {
            if (!Schema::hasColumn('historicocgm', 'z09_status')) {
                $table->boolean('z09_status')->default(false);
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
        Schema::table('historicocgm', function (Blueprint $table) {
            if (Schema::hasColumn('historicocgm', 'z09_status')) {
                $table->dropColumn('z09_status');
            }
        });
    }
}
