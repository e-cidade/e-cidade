<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPc01CodmaterantInPcmaterTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE compras.pcmater ALTER COLUMN pc01_codmaterant TYPE BIGINT");
    }

    public function down()
    {
        DB::statement("ALTER TABLE compras.pcmater ALTER COLUMN pc01_codmaterant TYPE INTEGER");
    }
}
