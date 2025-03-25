<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Oc23398 extends Migration
{

    public function up()
    {
        Schema::create('sicom.subve102025', function (Blueprint $table) {
            $table->id('si180_sequencial');
            $table->integer('si180_tiporegistro')->notNull();
            $table->integer('si180_codsubsidio')->notNull();
            $table->float('si180_vlrtotalsubsidio');
            $table->float('si180_porcentagemreajuste');
            $table->date('si180_dtinicialsubsidio')->notNull();
            $table->integer('si180_nrleisubsidio')->notNull();
            $table->date('si180_dtpublicacaoleisubsidio')->notNull();
            $table->integer('si180_instit')->notNull();
            $table->integer('si180_mes')->notNull();
        });

        Schema::dropIfExists('flpgo122025');
        Schema::dropIfExists('flpgo112025');
        Schema::dropIfExists('flpgo102025');

        Schema::create('flpgo102025', function (Blueprint $table) {
            $table->bigInteger('si195_sequencial')->default(0)->primary();
            $table->bigInteger('si195_tiporegistro')->nullable();
            $table->bigInteger('si195_codvinculopessoa')->nullable();
            $table->string('si195_regime', 1)->nullable();
            $table->string('si195_indtipopagamento', 1)->nullable();
            $table->string('si195_dsctipopagextra', 150)->nullable();
            $table->string('si195_indsituacaoservidorpensionista', 2)->nullable();
            $table->date('si195_datatransferenciareserva')->nullable();
            $table->bigInteger('si195_indpensionista')->nullable();
            $table->string('si195_nrocpfinstituidor', 11)->nullable();
            $table->date('si195_datobitoinstituidor')->nullable();
            $table->bigInteger('si195_tipodependencia')->nullable();
            $table->string('si195_dscdependencia', 150)->nullable();
            $table->bigInteger('si195_optouafastpreliminar')->nullable();
            $table->date('si195_datfastpreliminar')->nullable();
            $table->date('si195_datconcessaoaposentadoriapensao')->nullable();
            $table->string('si195_dsccargo', 120)->nullable();
            $table->bigInteger('si195_codcargo')->nullable();
            $table->string('si195_sglcargo', 3)->nullable();
            $table->string('si195_dscapo', 3)->nullable();
            $table->bigInteger('si195_natcargo')->nullable();
            $table->string('si195_dscnatcargo', 150)->nullable();
            $table->integer('si195_vinculoefetivoapo')->nullable();
            $table->integer('si195_opcaoremuneracaocargoefetivo')->nullable();
            $table->string('si195_indcessao', 3)->nullable();
            $table->string('si195_dsclotacao', 250)->nullable();
            $table->string('si195_dedicacaoexclusiva', 1)->nullable();
            $table->string('si195_indsalaaula', 1)->nullable();
            $table->bigInteger('si195_vlrcargahorariasemanal')->nullable();
            $table->date('si195_datefetexercicio')->nullable();
            $table->date('si195_datcomissionado')->nullable();
            $table->date('si195_datexclusao')->nullable();
            $table->date('si195_datcomissionadoexclusao')->nullable();
            $table->float('si195_vlrremuneracaobruta')->nullable();
            $table->float('si195_vlrdescontos')->nullable();
            $table->float('si195_vlrremuneracaoliquida')->nullable();
            $table->string('si195_natsaldoliquido', 1)->nullable();
            $table->bigInteger('si195_mes')->nullable();
            $table->bigInteger('si195_inst')->nullable();
        });

        Schema::create('flpgo112025', function (Blueprint $table) {
            $table->bigInteger('si196_sequencial')->default(0)->primary();
            $table->bigInteger('si196_tiporegistro')->nullable();
            $table->string('si196_indtipopagamento', 1)->nullable();
            $table->string('si196_codvinculopessoa', 15)->nullable();
            $table->string('si196_codrubricaremuneracao', 4)->nullable();
            $table->string('si196_desctiporubrica', 150)->nullable();
            $table->double('si196_vlrremuneracaodetalhada')->nullable();
            $table->bigInteger('si196_reg10')->default(0)->nullable();
            $table->bigInteger('si196_mes')->nullable();
            $table->bigInteger('si196_inst')->nullable();
            $table->foreign('si196_reg10')->references('si195_sequencial')->on('flpgo102025');
        });

        Schema::create('flpgo122025', function (Blueprint $table) {
            $table->bigInteger('si197_sequencial')->default(0)->primary();
            $table->bigInteger('si197_tiporegistro')->nullable();
            $table->string('si197_indtipopagamento', 1)->nullable();
            $table->string('si197_codvinculopessoa', 15)->nullable();
            $table->string('si197_codrubricadesconto', 4)->nullable();
            $table->string('si197_desctiporubricadesconto', 150)->nullable();
            $table->double('si197_vlrdescontodetalhado')->nullable();
            $table->bigInteger('si197_reg10')->default(0)->nullable();
            $table->bigInteger('si197_mes')->nullable();
            $table->bigInteger('si197_inst')->nullable();
            $table->foreign('si197_reg10')->references('si195_sequencial')->on('flpgo102025');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sicom.subve102025');
    }
}
