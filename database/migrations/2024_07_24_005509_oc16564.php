<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Oc16564 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadastro.iptufotos', function (Blueprint $table) {
            $table->id();
            $table->boolean('j54_fotoativa')->default('false');
            $table->boolean('j54_principal')->default('false');
            $table->integer('j54_matric');
            $table->string('j54_url')->nullable(false);
            $table->string('j54_nomearquivo')->nullable(false);
            $table->foreign('j54_matric')->references('j01_matric')->on('cadastro.iptubase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadastro.iptufotos');
    }
}
