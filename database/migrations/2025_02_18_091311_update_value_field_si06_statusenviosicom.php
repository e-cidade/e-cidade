<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateValueFieldSi06Statusenviosicom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('adesaoregprecos')
            ->where('si06_anomodadm', '>=', 2025)
            ->where(DB::raw("CAST(si06_dataadesao AS DATE)"), '>', '2024-12-31')
            ->where('si06_statusenviosicom', null )
            ->update(['si06_statusenviosicom' => 4]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('adesaoregprecos')
            ->where('si06_anomodadm', '>=', 2025)
            ->where(DB::raw("CAST(si06_dataadesao AS DATE)"), '>', '2024-12-31')
            ->update(['si06_statusenviosicom' => null]);
    }
}
