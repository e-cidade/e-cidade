<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateL20InversaofasesField extends Migration
{
    public function up()
    {
        $aIds = DB::table('cflicita')
            ->select('l03_codigo')
            ->whereNotIn('l03_pctipocompratribunal', [100, 101, 102, 103])
            ->pluck('l03_codigo')
            ->toArray();

        DB::table('liclicita')
            ->where('l20_anousu', 2025)
            ->whereNotIn('l20_codtipocom', $aIds)
            ->update(['l20_inversaofases' => 1]);

        DB::table('liclicita')
            ->where('l20_anousu', 2025)
            ->whereIn('l20_codtipocom', $aIds)
            ->update(['l20_inversaofases' => 0]);
    }

    public function down()
    {
        DB::table('liclicita')
            ->where('l20_anousu', 2025)
            ->update(['l20_inversaofases' => null]);
    }
}
