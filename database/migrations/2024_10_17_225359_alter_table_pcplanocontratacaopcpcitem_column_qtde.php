<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePcplanocontratacaopcpcitemColumnQtde extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            ALTER TABLE compras.pcplanocontratacaopcpcitem
            ALTER COLUMN mpcpc01_qtdd TYPE double precision;
        ";
        DB::unprepared($sql);
    }
}
