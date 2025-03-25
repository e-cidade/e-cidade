<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancete152025Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists('balancete152025');
        
        Schema::create('balancete152025', function (Blueprint $table) {
            $table->bigInteger('si182_sequencial')->default(0)->primary();
            $table->bigInteger('si182_tiporegistro')->default(0);
            $table->bigInteger('si182_contacontabil')->default(0);
            $table->string('si182_codfundo', 8)->default('00000000');
            $table->string('si182_atributosf', 1);
            $table->double('si182_saldoinicialsf')->default(0);
            $table->string('si182_naturezasaldoinicialsf', 1);
            $table->double('si182_totaldebitossf')->default(0);
            $table->double('si182_totalcreditossf')->default(0);
            $table->double('si182_saldofinalsf')->default(0);
            $table->string('si182_naturezasaldofinalsf', 1);
            $table->bigInteger('si182_mes')->default(0);
            $table->bigInteger('si182_instit')->nullable()->default(0);
            $table->bigInteger('si182_reg10');

            $table->foreign('si182_reg10')
                  ->references('si177_sequencial')
                  ->on('balancete102025');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balancete152025');
    }
}
