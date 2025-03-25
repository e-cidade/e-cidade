<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateL03PresencialField extends Migration
{
    public function up()
    {
        // Obter IDs dos registros que devem receber false
        $falseIds = DB::table('cflicita')
            ->select('l03_codigo')
            ->where(function ($query) {
                $query->where('l03_descr', 'ILIKE', '%LEIL%ICO%')
                      ->where('l03_pctipocompratribunal', 54);
            })
            ->orWhere(function ($query) {
                $query->where('l03_descr', 'ILIKE', '%CON%ICO%')
                      ->where('l03_pctipocompratribunal', 50);
            })
            ->orWhere('l03_descr', 'ILIKE', 'DISPENS%NICA%')
            ->orWhere('l03_pctipocompratribunal', 53)
            ->pluck('l03_codigo')
            ->toArray();

        DB::table('cflicita')
            ->whereIn('l03_codigo', $falseIds)
            ->update(['l03_presencial' => false]);

        DB::table('cflicita')
            ->whereNotIn('l03_codigo', $falseIds)
            ->update(['l03_presencial' => true]);
    }

    public function down()
    {
        DB::table('cflicita')
            ->update(['l03_presencial' => null]);
    }
}
