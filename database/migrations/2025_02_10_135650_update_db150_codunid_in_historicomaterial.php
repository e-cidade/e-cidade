<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDb150CodunidInHistoricomaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("
            UPDATE historicomaterial
            SET db150_codunid = 
                CASE 
                    WHEN REPLACE(db150_coditem::TEXT, db150_pcmater::TEXT, '') = '' THEN NULL 
                    ELSE REPLACE(db150_coditem::TEXT, db150_pcmater::TEXT, '')::INTEGER 
                END
            WHERE db150_codunid IS NULL;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            UPDATE historicomaterial
            SET db150_codunid = NULL
            WHERE db150_coditem != db150_pcmater;
        ");
    }
}
