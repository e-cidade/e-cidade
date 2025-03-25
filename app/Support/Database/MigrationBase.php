<?php

namespace App\Support\Database;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrationBase extends Migration
{
    public function execute(string $sql): bool
    {
        return DB::unprepared($sql);
    }
}
