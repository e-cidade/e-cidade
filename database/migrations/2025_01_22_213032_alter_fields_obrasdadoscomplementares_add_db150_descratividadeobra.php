<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFieldsObrasdadoscomplementaresAddDb150Descratividadeobra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('obrasdadoscomplementares', function (Blueprint $table) {
            if (!Schema::hasColumn('obrasdadoscomplementares', 'db150_descratividadeobra')) {
                $table->string('db150_descratividadeobra', 150)->nullable();
            }
        });
        Schema::table('obrasdadoscomplementareslote', function (Blueprint $table) {
            if (!Schema::hasColumn('obrasdadoscomplementareslote', 'db150_descratividadeobra')) {
                $table->string('db150_descratividadeobra', 150)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('obrasdadoscomplementares', function (Blueprint $table) {
            if (Schema::hasColumn('obrasdadoscomplementares', 'db150_descratividadeobra')) {
                $table->dropColumn('db150_descratividadeobra');
            }
        });
        Schema::table('obrasdadoscomplementareslote', function (Blueprint $table) {
            if (Schema::hasColumn('obrasdadoscomplementareslote', 'db150_descratividadeobra')) {
                $table->dropColumn('db150_descratividadeobra');
            }
        });
    }
}
