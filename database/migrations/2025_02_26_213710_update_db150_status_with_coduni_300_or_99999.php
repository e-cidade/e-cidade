<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDb150StatusWithCoduni300Or99999 extends Migration
{
    public function up()
    {
        DB::table('historicomaterial')
            ->where('db150_codunid', '<', '300')
            ->orWhere('db150_codunid', '=', '999999')
            ->update(['db150_status' => true]);
    }

    public function down()
    {
        DB::table('historicomaterial')
            ->where('db150_codunid', '<', '300')
            ->orWhere('db150_codunid', '=', '999999')
            ->update(['db150_status' => false]);
    }
}
