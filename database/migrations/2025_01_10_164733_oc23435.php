<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Oc23435 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aberlic102025', function (Blueprint $table) {
            $table->bigInteger('si46_sequencial')->default(0);
            $table->bigInteger('si46_tiporegistro')->default(0);
            $table->bigInteger('si46_tipocadastro')->default(0);
            $table->string('si46_codorgaoresp', 2);
            $table->string('si46_codunidadesubresp', 8);
            $table->bigInteger('si46_exerciciolicitacao')->default(0);
            $table->string('si46_nroprocessolicitatorio', 12);
            $table->bigInteger('si46_codmodalidadelicitacao')->default(0);
            $table->bigInteger('si46_nroedital')->default(0);
            $table->bigInteger('si46_exercicioedital')->default(0);
            $table->bigInteger('si46_naturezaprocedimento')->default(0);
            $table->date('si46_dtabertura');
            $table->date('si46_dteditalconvite');
            $table->date('si46_dtpublicacaoeditaldo')->nullable();
            $table->date('si46_dtpublicacaoeditalveiculo1')->nullable();
            $table->string('si46_veiculo1publicacao', 50)->nullable();
            $table->date('si46_dtpublicacaoeditalveiculo2')->nullable();
            $table->string('si46_veiculo2publicacao', 50)->nullable();
            $table->date('si46_dtrecebimentodoc');
            $table->bigInteger('si46_tipolicitacao')->nullable();
            $table->bigInteger('si46_naturezaobjeto')->nullable();
            $table->text('si46_objeto');
            $table->bigInteger('si46_regimeexecucaoobras')->default(0)->nullable();
            $table->bigInteger('si46_nroconvidado')->default(0)->nullable();
            $table->string('si46_clausulaprorrogacao', 250)->nullable();
            $table->bigInteger('si46_unidademedidaprazoexecucao')->default(0);
            $table->bigInteger('si46_prazoexecucao')->default(0);
            $table->string('si46_formapagamento', 80);
            $table->string('si46_criterioaceitabilidade', 80)->nullable();
            $table->bigInteger('si46_criterioadjudicacao')->default(0);
            $table->bigInteger('si46_processoporlote')->default(0);
            $table->bigInteger('si46_criteriodesempate')->default(0);
            $table->bigInteger('si46_destinacaoexclusiva')->default(0);
            $table->bigInteger('si46_subcontratacao')->default(0);
            $table->bigInteger('si46_limitecontratacao')->default(0);
            $table->bigInteger('si46_mes')->default(0);
            $table->bigInteger('si46_instit')->nullable();
            $table->integer('si46_leidalicitacao')->nullable();
            $table->date('si46_dtpulicacaopncp')->nullable();
            $table->string('si46_linkpncp', 255)->nullable();
            $table->date('si46_dtpulicacaoedital')->nullable();
            $table->string('si46_linkedital', 255)->nullable();
            $table->integer('si46_diariooficialdivulgacao')->nullable();
            $table->integer('si46_mododisputa')->nullable();
            $table->bigInteger('si46_codunidadesubedital')->nullable();
            $table->primary('si46_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE aberlic102025_si46_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aberlic102025 ALTER COLUMN si46_sequencial SET DEFAULT nextval(\'aberlic102025_si46_sequencial_seq\');');

        Schema::create('aberlic112025', function (Blueprint $table) {
            $table->bigInteger('si47_sequencial')->default(0)->primary();
            $table->bigInteger('si47_tiporegistro')->default(0);
            $table->string('si47_codorgaoresp', 2);
            $table->string('si47_codunidadesubresp', 8);
            $table->bigInteger('si47_exerciciolicitacao')->default(0);
            $table->string('si47_nroprocessolicitatorio', 12);
            $table->bigInteger('si47_nrolote')->default(0);
            $table->string('si47_dsclote', 250);
            $table->bigInteger('si47_reg10')->default(0);
            $table->bigInteger('si47_mes')->default(0);
            $table->bigInteger('si47_instit')->nullable()->default(0);

            $table->foreign('si47_reg10')->references('si46_sequencial')->on('aberlic102025')->onDelete('cascade');
        });
        DB::statement('
            CREATE SEQUENCE aberlic112025_si47_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aberlic112025 ALTER COLUMN si47_sequencial SET DEFAULT nextval(\'aberlic112025_si47_sequencial_seq\');');

        Schema::create('aberlic122025', function (Blueprint $table) {
            $table->bigInteger('si48_sequencial')->default(0)->primary();
            $table->bigInteger('si48_tiporegistro')->default(0);
            $table->string('si48_codorgaoresp', 2);
            $table->string('si48_codunidadesubresp', 8);
            $table->bigInteger('si48_exerciciolicitacao')->default(0);
            $table->string('si48_nroprocessolicitatorio', 12);
            $table->bigInteger('si48_coditem')->default(0);
            $table->bigInteger('si48_nroitem')->default(0);
            $table->bigInteger('si48_reg10')->default(0);
            $table->bigInteger('si48_mes')->default(0);
            $table->bigInteger('si48_instit')->nullable()->default(0);

            $table->foreign('si48_reg10')->references('si46_sequencial')->on('aberlic102025')->onDelete('cascade');
        });
        DB::statement('
            CREATE SEQUENCE aberlic122025_si48_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aberlic122025 ALTER COLUMN si48_sequencial SET DEFAULT nextval(\'aberlic122025_si48_sequencial_seq\');');

        Schema::create('aberlic132025', function (Blueprint $table) {
            $table->bigInteger('si49_sequencial')->default(0)->primary();
            $table->bigInteger('si49_tiporegistro')->default(0);
            $table->string('si49_codorgaoresp', 2);
            $table->string('si49_codunidadesubresp', 8);
            $table->bigInteger('si49_exerciciolicitacao')->default(0);
            $table->string('si49_nroprocessolicitatorio', 12);
            $table->bigInteger('si49_nrolote')->default(0);
            $table->bigInteger('si49_coditem')->default(0);
            $table->bigInteger('si49_mes')->default(0);
            $table->bigInteger('si49_reg10')->default(0);
            $table->bigInteger('si49_instit')->nullable()->default(0);

            $table->foreign('si49_reg10')->references('si46_sequencial')->on('aberlic102025')->onDelete('cascade');
        });
        DB::statement('
            CREATE SEQUENCE aberlic132025_si49_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aberlic132025 ALTER COLUMN si49_sequencial SET DEFAULT nextval(\'aberlic132025_si49_sequencial_seq\');');

        Schema::create('aberlic142025', function (Blueprint $table) {
            $table->bigInteger('si50_sequencial')->default(0)->primary();
            $table->bigInteger('si50_tiporegistro')->default(0);
            $table->string('si50_codorgaoresp', 2);
            $table->string('si50_codunidadesubresp', 8);
            $table->bigInteger('si50_exerciciolicitacao')->default(0);
            $table->string('si50_nroprocessolicitatorio', 12);
            $table->bigInteger('si50_nrolote')->nullable()->default(0);
            $table->bigInteger('si50_coditem')->default(0);
            $table->date('si50_dtcotacao');
            $table->float('si50_vlrefpercentual', 4)->default(0);
            $table->double('si50_vlcotprecosunitario')->default(0);
            $table->double('si50_quantidade')->default(0);
            $table->double('si50_vlminalienbens')->default(0);
            $table->bigInteger('si50_mes')->default(0);
            $table->bigInteger('si50_reg10')->default(0);
            $table->bigInteger('si50_instit')->nullable()->default(0);

            $table->foreign('si50_reg10')->references('si46_sequencial')->on('aberlic102025')->onDelete('cascade');
        });
        DB::statement('
            CREATE SEQUENCE aberlic142025_si50_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aberlic142025 ALTER COLUMN si50_sequencial SET DEFAULT nextval(\'aberlic142025_si50_sequencial_seq\');');

        Schema::create('aberlic152025', function (Blueprint $table) {
            $table->bigInteger('si51_sequencial')->default(0)->primary();
            $table->bigInteger('si51_tiporegistro')->default(0);
            $table->string('si51_codorgaoresp', 2);
            $table->string('si51_codunidadesubresp', 8);
            $table->bigInteger('si51_exerciciolicitacao')->default(0);
            $table->string('si51_nroprocessolicitatorio', 12);
            $table->bigInteger('si51_nrolote')->nullable()->default(0);
            $table->bigInteger('si51_coditem')->default(0);
            $table->double('si51_vlitem')->default(0);
            $table->bigInteger('si51_mes')->default(0);
            $table->bigInteger('si51_reg10')->default(0);
            $table->bigInteger('si51_instit')->nullable()->default(0);

            $table->foreign('si51_reg10')->references('si46_sequencial')->on('aberlic102025')->onDelete('cascade');
        });
        DB::statement('
            CREATE SEQUENCE aberlic152025_si51_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aberlic152025 ALTER COLUMN si51_sequencial SET DEFAULT nextval(\'aberlic152025_si51_sequencial_seq\');');

        Schema::create('aberlic162025', function (Blueprint $table) {
            $table->bigInteger('si52_sequencial')->default(0)->primary();
            $table->bigInteger('si52_tiporegistro')->default(0);
            $table->string('si52_codorgaoresp', 2);
            $table->string('si52_codunidadesubresp', 8);
            $table->bigInteger('si52_exerciciolicitacao')->default(0);
            $table->string('si52_nroprocessolicitatorio', 12);
            $table->string('si52_codorgao', 2);
            $table->string('si52_codunidadesub', 8);
            $table->string('si52_codfuncao', 2);
            $table->string('si52_codsubfuncao', 3);
            $table->string('si52_codprograma', 4);
            $table->string('si52_idacao', 4);
            $table->string('si52_idsubacao', 4)->nullable();
            $table->bigInteger('si52_naturezadespesa')->default(0);
            $table->bigInteger('si52_codfontrecursos')->default(0);
            $table->double('si52_vlrecurso')->default(0);
            $table->bigInteger('si52_mes')->default(0);
            $table->bigInteger('si52_reg10')->default(0);
            $table->bigInteger('si52_instit')->nullable()->default(0);

            $table->foreign('si52_reg10')->references('si46_sequencial')->on('aberlic102025')->onDelete('cascade');
        });
        DB::statement('
            CREATE SEQUENCE aberlic162025_si52_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aberlic162025 ALTER COLUMN si52_sequencial SET DEFAULT nextval(\'aberlic162025_si52_sequencial_seq\');');

        Schema::create('aex102025', function (Blueprint $table) {
            $table->bigInteger('si130_sequencial')->default(0);
            $table->bigInteger('si130_tiporegistro')->default(0);
            $table->bigInteger('si130_codext')->default(0);
            $table->bigInteger('si130_codfontrecursos')->default(0);
            $table->bigInteger('si130_nroop')->default(0);
            $table->string('si130_codunidadesub', 8);
            $table->date('si130_dtpagamento');
            $table->bigInteger('si130_nroanulacaoop')->default(0);
            $table->date('si130_dtanulacaoop');
            $table->float('si130_vlanulacaoop')->default(0);
            $table->bigInteger('si130_mes')->default(0);
            $table->bigInteger('si130_instit')->nullable();
            $table->primary('si130_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE aex102025_si130_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aex102025 ALTER COLUMN si130_sequencial SET DEFAULT nextval(\'aex102025_si130_sequencial_seq\');');

        Schema::create('afast102025', function (Blueprint $table) {
            $table->bigInteger('si199_sequencial');
            $table->integer('si199_tiporegistro')->default(0);
            $table->integer('si199_codvinculopessoa')->default(0);
            $table->bigInteger('si199_codafastamento')->default(0);
            $table->date('si199_dtinicioafastamento');
            $table->date('si199_dtretornoafastamento');
            $table->integer('si199_tipoafastamento')->default(0);
            $table->string('si199_dscoutrosafastamentos', 500)->nullable();
            $table->integer('si199_mes')->default(0);
            $table->integer('si199_inst')->nullable();
            $table->primary('si199_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE afast102025_si199_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE afast102025 ALTER COLUMN si199_sequencial SET DEFAULT nextval(\'afast102025_si199_sequencial_seq\');');

        Schema::create('afast202025', function (Blueprint $table) {
            $table->bigInteger('si200_sequencial');
            $table->integer('si200_tiporegistro')->default(0);
            $table->integer('si200_codvinculopessoa')->default(0);
            $table->bigInteger('si200_codafastamento')->default(0);
            $table->date('si200_dtterminoafastamento');
            $table->integer('si200_mes')->default(0);
            $table->integer('si200_inst')->default(0)->nullable();
            $table->primary('si200_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE afast202025_si200_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE afast202025 ALTER COLUMN si200_sequencial SET DEFAULT nextval(\'afast202025_si200_sequencial_seq\');');

        Schema::create('afast302025', function (Blueprint $table) {
            $table->bigInteger('si201_sequencial');
            $table->integer('si201_tiporegistro')->default(0);
            $table->integer('si201_codvinculopessoa')->default(0);
            $table->bigInteger('si201_codafastamento')->default(0);
            $table->date('si201_dtretornoafastamento');
            $table->integer('si201_mes')->default(0);
            $table->integer('si201_inst')->default(0)->nullable();
            $table->primary('si201_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE afast302025_si201_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE afast302025 ALTER COLUMN si201_sequencial SET DEFAULT nextval(\'afast302025_si201_sequencial_seq\');');

        Schema::create('alq102025', function (Blueprint $table) {
            $table->bigInteger('si121_sequencial')->default(0);
            $table->bigInteger('si121_tiporegistro')->default(0);
            $table->bigInteger('si121_codreduzido')->default(0);
            $table->string('si121_codorgao', 2);
            $table->string('si121_codunidadesub', 8);
            $table->bigInteger('si121_nroempenho')->default(0);
            $table->date('si121_dtempenho');
            $table->date('si121_dtliquidacao');
            $table->bigInteger('si121_nroliquidacao')->default(0);
            $table->date('si121_dtanulacaoliq');
            $table->bigInteger('si121_nroliquidacaoanl')->default(0);
            $table->bigInteger('si121_tpliquidacao')->default(0);
            $table->string('si121_justificativaanulacao', 500);
            $table->float('si121_vlanulado')->default(0);
            $table->bigInteger('si121_mes')->default(0);
            $table->bigInteger('si121_instit')->default(0)->nullable();
            $table->primary('si121_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE alq102025_si121_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE alq102025 ALTER COLUMN si121_sequencial SET DEFAULT nextval(\'alq102025_si121_sequencial_seq\');');

        Schema::create('alq112025', function (Blueprint $table) {
            $table->bigInteger('si122_sequencial')->default(0)->primary();
            $table->bigInteger('si122_tiporegistro')->default(0);
            $table->bigInteger('si122_codreduzido')->default(0);
            $table->bigInteger('si122_codfontrecursos');
            $table->float('si122_valoranuladofonte')->default(0);
            $table->bigInteger('si122_mes')->default(0);
            $table->bigInteger('si122_reg10');
            $table->bigInteger('si122_instit')->nullable()->default(0);

            $table->foreign('si122_reg10')->references('si121_sequencial')->on('alq102025');
        });
        DB::statement('CREATE INDEX alq112025_si122_reg10_index ON alq112025 USING btree (si122_reg10);');
        DB::statement('
            CREATE SEQUENCE alq112025_si122_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE alq112025 ALTER COLUMN si122_sequencial SET DEFAULT nextval(\'alq112025_si122_sequencial_seq\');');

        Schema::create('alq122025', function (Blueprint $table) {
            $table->bigInteger('si123_sequencial')->default(0)->primary();
            $table->bigInteger('si123_tiporegistro')->default(0);
            $table->bigInteger('si123_codreduzido')->default(0);
            $table->string('si123_mescompetencia', 2);
            $table->bigInteger('si123_exerciciocompetencia')->default(0);
            $table->float('si123_vlanuladodspexerant')->default(0);
            $table->bigInteger('si123_mes')->default(0);
            $table->bigInteger('si123_reg10');
            $table->bigInteger('si123_instit')->nullable()->default(0);

            $table->foreign('si123_reg10')->references('si121_sequencial')->on('alq102025');
        });
        DB::statement('CREATE INDEX alq122025_si123_reg10_index ON alq122025 USING btree (si123_reg10);');
        DB::statement('
            CREATE SEQUENCE alq122025_si123_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE alq122025 ALTER COLUMN si123_sequencial SET DEFAULT nextval(\'alq122025_si123_sequencial_seq\');');

        Schema::create('anl102025', function (Blueprint $table) {
            $table->bigInteger('si110_sequencial')->default(0);
            $table->bigInteger('si110_tiporegistro')->default(0);
            $table->string('si110_codorgao', 2);
            $table->string('si110_codunidadesub', 8);
            $table->bigInteger('si110_nroempenho')->default(0);
            $table->date('si110_dtempenho');
            $table->date('si110_dtanulacao');
            $table->bigInteger('si110_nroanulacao')->default(0);
            $table->bigInteger('si110_tipoanulacao')->default(0);
            $table->string('si110_especanulacaoempenho', 200);
            $table->float('si110_vlanulacao')->default(0);
            $table->bigInteger('si110_mes')->default(0);
            $table->bigInteger('si110_instit')->default(0)->nullable();
            $table->primary('si110_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE anl102025_si110_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE anl102025 ALTER COLUMN si110_sequencial SET DEFAULT nextval(\'anl102025_si110_sequencial_seq\');');

        Schema::create('anl112025', function (Blueprint $table) {
            $table->bigInteger('si111_sequencial')->default(0)->primary();
            $table->bigInteger('si111_tiporegistro')->default(0);
            $table->string('si111_codunidadesub', 8);
            $table->bigInteger('si111_nroempenho')->default(0);
            $table->bigInteger('si111_nroanulacao')->default(0);
            $table->bigInteger('si111_codfontrecursos')->default(0);
            $table->double('si111_vlanulacaofonte')->default(0);
            $table->bigInteger('si111_mes')->default(0);
            $table->bigInteger('si111_reg10')->default(0);
            $table->bigInteger('si111_instit')->nullable()->default(0);
            $table->foreign('si111_reg10')
                ->references('si110_sequencial')
                ->on('anl102025')
                ->onDelete('cascade')
                ->name('anl112025_reg10_fk');
        });
        DB::statement('CREATE INDEX anl112025_si111_reg10_index ON anl112025 USING btree (si111_reg10);');
        DB::statement('
            CREATE SEQUENCE anl112025_si111_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE anl112025 ALTER COLUMN si111_sequencial SET DEFAULT nextval(\'anl112025_si111_sequencial_seq\');');

        Schema::create('aob102025', function (Blueprint $table) {
            $table->bigInteger('si141_sequencial')->default(0);
            $table->bigInteger('si141_tiporegistro')->default(0);
            $table->bigInteger('si141_codreduzido')->default(0);
            $table->string('si141_codorgao', 2);
            $table->string('si141_codunidadesub', 8);
            $table->bigInteger('si141_nrolancamento')->default(0);
            $table->date('si141_dtlancamento');
            $table->bigInteger('si141_tipolancamento')->default(0);
            $table->bigInteger('si141_nroanulacaolancamento')->default(0);
            $table->date('si141_dtanulacaolancamento');
            $table->bigInteger('si141_nroempenho')->default(0);
            $table->date('si141_dtempenho');
            $table->bigInteger('si141_nroliquidacao')->nullable();
            $table->date('si141_dtliquidacao')->nullable();
            $table->float('si141_valoranulacaolancamento')->default(0);
            $table->bigInteger('si141_mes')->default(0);
            $table->bigInteger('si141_instit')->default(0)->nullable();
            $table->primary('si141_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE aob102025_si141_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aob102025 ALTER COLUMN si141_sequencial SET DEFAULT nextval(\'aob102025_si141_sequencial_seq\');');

        Schema::create('aob112025', function (Blueprint $table) {
            $table->bigInteger('si142_sequencial')->default(0)->primary();
            $table->bigInteger('si142_tiporegistro')->default(0);
            $table->bigInteger('si142_codreduzido')->default(0);
            $table->bigInteger('si142_codfontrecursos')->default(0);
            $table->double('si142_valoranulacaofonte')->default(0);
            $table->bigInteger('si142_mes')->default(0);
            $table->bigInteger('si142_reg10')->default(0);
            $table->bigInteger('si142_instit')->nullable()->default(0);

            $table->foreign('si142_reg10')
                ->references('si141_sequencial')
                ->on('aob102025')
                ->onDelete('cascade')
                ->name('aob112025_reg10_fk');
        });
        DB::statement('CREATE INDEX aob112025_si142_reg10_index ON aob112025 USING btree (si142_reg10);');
        DB::statement('
            CREATE SEQUENCE aob112025_si142_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aob112025 ALTER COLUMN si142_sequencial SET DEFAULT nextval(\'aob112025_si142_sequencial_seq\');');

        Schema::create('aoc102025', function (Blueprint $table) {
            $table->bigInteger('si38_sequencial')->default(0);
            $table->bigInteger('si38_tiporegistro')->default(0);
            $table->string('si38_codorgao', 2);
            $table->string('si38_nrodecreto', 8)->default(0);
            $table->date('si38_datadecreto');
            $table->bigInteger('si38_mes')->default(0);
            $table->bigInteger('si38_instit')->default(0)->nullable();
            $table->primary('si38_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE aoc102025_si38_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aoc102025 ALTER COLUMN si38_sequencial SET DEFAULT nextval(\'aoc102025_si38_sequencial_seq\');');

        Schema::create('aoc112025', function (Blueprint $table) {
            $table->bigInteger('si39_sequencial')->default(0)->primary();
            $table->bigInteger('si39_tiporegistro')->default(0);
            $table->bigInteger('si39_codreduzidodecreto')->default(0);
            $table->string('si39_nrodecreto', 8)->default(0);
            $table->bigInteger('si39_tipodecretoalteracao')->default(0);
            $table->double('si39_valoraberto')->default(0);
            $table->bigInteger('si39_mes')->default(0);
            $table->bigInteger('si39_reg10')->default(0);
            $table->bigInteger('si39_instit')->nullable()->default(0);
            $table->text('si39_justificativa')->nullable();

            $table->foreign('si39_reg10')
                ->references('si38_sequencial')
                ->on('aoc102025')
                ->onDelete('cascade')
                ->name('aoc112025_reg10_fk');
        });
        DB::statement('CREATE INDEX aoc112025_si39_reg10_index ON aoc112025 USING btree (si39_reg10);');
        DB::statement('
            CREATE SEQUENCE aoc112025_si39_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aoc112025 ALTER COLUMN si39_sequencial SET DEFAULT nextval(\'aoc112025_si39_sequencial_seq\');');

        Schema::create('aoc122025', function (Blueprint $table) {
            $table->bigInteger('si40_sequencial')->default(0)->primary();
            $table->bigInteger('si40_tiporegistro')->default(0);
            $table->bigInteger('si40_codreduzidodecreto')->default(0);
            $table->string('si40_nroleialteracao', 6);
            $table->date('si40_dataleialteracao')->nullable();
            $table->string('si40_tpleiorigdecreto', 4);
            $table->bigInteger('si40_tipoleialteracao')->nullable()->default(0);
            $table->double('si40_valorabertolei')->nullable();
            $table->bigInteger('si40_mes')->default(0);
            $table->bigInteger('si40_reg10')->default(0);
            $table->bigInteger('si40_instit')->nullable()->default(0);

            $table->foreign('si40_reg10')
                ->references('si38_sequencial')
                ->on('aoc102025')
                ->onDelete('cascade')
                ->name('aoc122025_reg10_fk');
        });
        DB::statement('CREATE INDEX aoc122025_si40_reg10_index ON aoc122025 USING btree (si40_reg10);');
        DB::statement('
            CREATE SEQUENCE aoc122025_si40_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aoc122025 ALTER COLUMN si40_sequencial SET DEFAULT nextval(\'aoc122025_si40_sequencial_seq\');');

        Schema::create('aoc132025', function (Blueprint $table) {
            $table->bigInteger('si41_sequencial')->default(0)->primary();
            $table->bigInteger('si41_tiporegistro')->default(0);
            $table->bigInteger('si41_codreduzidodecreto')->default(0);
            $table->string('si41_origemrecalteracao', 2);
            $table->double('si41_valorabertoorigem')->default(0);
            $table->bigInteger('si41_mes')->default(0);
            $table->bigInteger('si41_reg10')->default(0);
            $table->bigInteger('si41_instit')->nullable()->default(0);

            $table->foreign('si41_reg10')
                ->references('si38_sequencial')
                ->on('aoc102025')
                ->onDelete('cascade')
                ->name('aoc132025_reg10_fk');
        });
        DB::statement('CREATE INDEX aoc132025_si41_reg10_index ON aoc132025 USING btree (si41_reg10);');
        DB::statement('
            CREATE SEQUENCE aoc132025_si41_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aoc132025 ALTER COLUMN si41_sequencial SET DEFAULT nextval(\'aoc132025_si41_sequencial_seq\');');

        Schema::create('aoc142025', function (Blueprint $table) {
            $table->bigInteger('si42_sequencial')->default(0)->primary();
            $table->bigInteger('si42_tiporegistro')->default(0);
            $table->bigInteger('si42_codreduzidodecreto')->default(0);
            $table->string('si42_origemrecalteracao', 2);
            $table->bigInteger('si42_codorigem')->nullable()->default(0);
            $table->string('si42_codorgao', 2);
            $table->string('si42_codunidadesub', 8);
            $table->string('si42_codfuncao', 2);
            $table->string('si42_codsubfuncao', 3);
            $table->string('si42_codprograma', 4);
            $table->string('si42_idacao', 4);
            $table->string('si42_idsubacao', 4)->nullable();
            $table->bigInteger('si42_naturezadespesa')->default(0);
            $table->bigInteger('si42_codfontrecursos')->default(0);
            $table->double('si42_vlacrescimo')->default(0);
            $table->bigInteger('si42_mes')->default(0);
            $table->bigInteger('si42_reg10')->default(0);
            $table->bigInteger('si42_instit')->nullable()->default(0);
            $table->string('si42_nrocontratoop', 30)->nullable();
            $table->date('si42_dataassinaturacontratoop')->nullable();

            $table->foreign('si42_reg10')
                ->references('si38_sequencial')
                ->on('aoc102025')
                ->onDelete('cascade')
                ->name('aoc142025_reg10_fk');
        });
        DB::statement('CREATE INDEX aoc142025_si42_reg10_index ON aoc142025 USING btree (si42_reg10);');
        DB::statement('
            CREATE SEQUENCE aoc142025_si42_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aoc142025 ALTER COLUMN si42_sequencial SET DEFAULT nextval(\'aoc142025_si42_sequencial_seq\');');

        Schema::create('aoc152025', function (Blueprint $table) {
            $table->bigInteger('si194_sequencial')->default(0)->primary();
            $table->bigInteger('si194_tiporegistro')->default(0);
            $table->bigInteger('si194_codreduzidodecreto')->default(0);
            $table->string('si194_origemrecalteracao', 2);
            $table->bigInteger('si194_codorigem')->default(0);
            $table->string('si194_codorgao', 2);
            $table->string('si194_codunidadesub', 8);
            $table->string('si194_codfuncao', 2);
            $table->string('si194_codsubfuncao', 3);
            $table->string('si194_codprograma', 4);
            $table->string('si194_idacao', 4);
            $table->string('si194_idsubacao', 4)->nullable()->default(null);
            $table->bigInteger('si194_naturezadespesa')->default(0);
            $table->bigInteger('si194_codfontrecursos')->default(0);
            $table->double('si194_vlreducao')->default(0);
            $table->bigInteger('si194_mes')->default(0);
            $table->bigInteger('si194_reg10')->default(0);
            $table->bigInteger('si194_instit')->default(0);

            $table->foreign('si194_reg10')
                ->references('si38_sequencial')
                ->on('aoc102025')
                ->onDelete('cascade')
                ->name('aoc152025_reg10_fk');
        });
        DB::statement('CREATE INDEX aoc152025_si194_reg10_index ON aoc152025 USING btree (si194_reg10);');
        DB::statement('
            CREATE SEQUENCE aoc152025_si194_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aoc152025 ALTER COLUMN si194_sequencial SET DEFAULT nextval(\'aoc152025_si194_sequencial_seq\');');

        Schema::create('aop102025', function (Blueprint $table) {
            $table->bigInteger('si137_sequencial')->default(0);
            $table->bigInteger('si137_tiporegistro')->default(0);
            $table->bigInteger('si137_codreduzido')->default(0);
            $table->string('si137_codorgao', 2);
            $table->string('si137_codunidadesub', 8);
            $table->bigInteger('si137_nroop')->default(0);
            $table->date('si137_dtpagamento');
            $table->bigInteger('si137_nroanulacaoop')->default(0);
            $table->date('si137_dtanulacaoop');
            $table->string('si137_justificativaanulacao', 500);
            $table->float('si137_vlanulacaoop')->default(0);
            $table->bigInteger('si137_mes')->default(0);
            $table->bigInteger('si137_instit')->default(0)->nullable();
            $table->primary('si137_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE aop102025_si137_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aop102025 ALTER COLUMN si137_sequencial SET DEFAULT nextval(\'aop102025_si137_sequencial_seq\');');

        Schema::create('aop112025', function (Blueprint $table) {
            $table->bigInteger('si138_sequencial')->default(0)->primary();
            $table->bigInteger('si138_tiporegistro')->default(0);
            $table->bigInteger('si138_codreduzido')->default(0);
            $table->bigInteger('si138_tipopagamento')->default(0);
            $table->bigInteger('si138_nroempenho')->default(0);
            $table->date('si138_dtempenho');
            $table->bigInteger('si138_nroliquidacao')->nullable()->default(0);
            $table->date('si138_dtliquidacao')->nullable();
            $table->bigInteger('si138_codfontrecursos')->default(0);
            $table->double('si138_valoranulacaofonte')->default(0);
            $table->bigInteger('si138_mes')->default(0);
            $table->bigInteger('si138_reg10')->default(0);
            $table->bigInteger('si138_instit')->nullable()->default(0);

            $table->foreign('si138_reg10')->references('si137_sequencial')->on('aop102025');
        });
        DB::statement('
            CREATE SEQUENCE aop112025_si138_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aop112025 ALTER COLUMN si138_sequencial SET DEFAULT nextval(\'aop112025_si138_sequencial_seq\');');

        Schema::create('arc102025', function (Blueprint $table) {
            $table->bigInteger('si28_sequencial')->default(0)->primary();
            $table->bigInteger('si28_tiporegistro')->default(0);
            $table->bigInteger('si28_codcorrecao')->default(0);
            $table->string('si28_codorgao', 2);
            $table->bigInteger('si28_ededucaodereceita')->default(0);
            $table->bigInteger('si28_identificadordeducaorecreduzida')->nullable();
            $table->bigInteger('si28_naturezareceitareduzida')->default(0);
            $table->bigInteger('si28_identificadordeducaorecacrescida')->nullable();
            $table->bigInteger('si28_naturezareceitaacrescida')->default(0);
            $table->float('si28_vlreduzidoacrescido', 8, 2)->default(0);
            $table->bigInteger('si28_mes')->default(0);
            $table->bigInteger('si28_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE arc102025_si28_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE arc102025 ALTER COLUMN si28_sequencial SET DEFAULT nextval(\'arc102025_si28_sequencial_seq\');');

        Schema::create('arc202025', function (Blueprint $table) {
            $table->bigInteger('si31_sequencial')->default(0)->primary();
            $table->bigInteger('si31_tiporegistro')->default(0);
            $table->string('si31_codorgao', 2);
            $table->bigInteger('si31_codestorno')->default(0);
            $table->bigInteger('si31_ededucaodereceita')->default(0);
            $table->bigInteger('si31_identificadordeducao')->nullable();
            $table->bigInteger('si31_naturezareceitaestornada')->default(0);
            $table->float('si31_vlestornado', 8, 2)->default(0);
            $table->bigInteger('si31_mes')->default(0);
            $table->bigInteger('si31_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE arc202025_si31_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE arc202025 ALTER COLUMN si31_sequencial SET DEFAULT nextval(\'arc202025_si31_sequencial_seq\');');

        Schema::create('balancete102025', function (Blueprint $table) {
            $table->bigInteger('si177_sequencial')->default(0)->primary();
            $table->bigInteger('si177_tiporegistro')->default(0);
            $table->bigInteger('si177_contacontaabil')->default(0);
            $table->string('si177_codfundo', 8)->default('00000000');
            $table->float('si177_saldoinicial', 8, 2)->default(0);
            $table->string('si177_naturezasaldoinicial', 1);
            $table->float('si177_totaldebitos', 8, 2)->default(0);
            $table->float('si177_totalcreditos', 8, 2)->default(0);
            $table->float('si177_saldofinal', 8, 2)->default(0);
            $table->string('si177_naturezasaldofinal', 1);
            $table->bigInteger('si177_mes')->default(0);
            $table->bigInteger('si177_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE balancete102025_si177_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete102025 ALTER COLUMN si177_sequencial SET DEFAULT nextval(\'balancete102025_si177_sequencial_seq\');');

        Schema::create('bodcasp102025', function (Blueprint $table) {
            $table->integer('si201_sequencial')->default(0)->primary();
            $table->integer('si201_tiporegistro')->default(0);
            $table->integer('si201_faserecorcamentaria')->default(0);
            $table->float('si201_vlrectributaria', 8, 2)->default(0);
            $table->float('si201_vlreccontribuicoes', 8, 2)->default(0);
            $table->float('si201_vlrecpatrimonial', 8, 2)->default(0);
            $table->float('si201_vlrecagropecuaria', 8, 2)->default(0);
            $table->float('si201_vlrecindustrial', 8, 2)->default(0);
            $table->float('si201_vlrecservicos', 8, 2)->default(0);
            $table->float('si201_vltransfcorrentes', 8, 2)->default(0);
            $table->float('si201_vloutrasreccorrentes', 8, 2)->default(0);
            $table->float('si201_vloperacoescredito', 8, 2)->default(0);
            $table->float('si201_vlalienacaobens', 8, 2)->default(0);
            $table->float('si201_vlamortemprestimo', 8, 2)->default(0);
            $table->float('si201_vltransfcapital', 8, 2)->default(0);
            $table->float('si201_vloutrasreccapital', 8, 2)->default(0);
            $table->float('si201_vlrecarrecadaxeant', 8, 2)->default(0);
            $table->float('si201_vlopcredrefintermob', 8, 2)->default(0);
            $table->float('si201_vlopcredrefintcontrat', 8, 2)->default(0);
            $table->float('si201_vlopcredrefextmob', 8, 2)->default(0);
            $table->float('si201_vlopcredrefextcontrat', 8, 2)->default(0);
            $table->float('si201_vldeficit', 8, 2)->default(0);
            $table->float('si201_vltotalquadroreceita', 8, 2)->nullable()->default(0);
            $table->integer('si201_ano')->default(0);
            $table->integer('si201_periodo')->default(0);
            $table->integer('si201_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bodcasp102025_si201_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bodcasp102025 ALTER COLUMN si201_sequencial SET DEFAULT nextval(\'bodcasp102025_si201_sequencial_seq\');');

        Schema::create('bodcasp202025', function (Blueprint $table) {
            $table->integer('si202_sequencial')->default(0)->primary();
            $table->integer('si202_tiporegistro')->default(0);
            $table->integer('si202_faserecorcamentaria')->default(0);
            $table->float('si202_vlsaldoexeantsupfin', 8, 2)->default(0);
            $table->float('si202_vlsaldoexeantrecredad', 8, 2)->default(0);
            $table->float('si202_vltotalsaldoexeant', 8, 2)->nullable()->default(0);
            $table->integer('si202_anousu')->default(0);
            $table->integer('si202_periodo')->default(0);
            $table->integer('si202_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bodcasp202025_si202_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bodcasp202025 ALTER COLUMN si202_sequencial SET DEFAULT nextval(\'bodcasp202025_si202_sequencial_seq\');');

        Schema::create('bodcasp302025', function (Blueprint $table) {
            $table->integer('si203_sequencial')->default(0)->primary();
            $table->integer('si203_tiporegistro')->default(0);
            $table->integer('si203_fasedespesaorca')->default(0);
            $table->float('si203_vlpessoalencarsoci', 8, 2)->default(0);
            $table->float('si203_vljurosencardividas', 8, 2)->default(0);
            $table->float('si203_vloutrasdespcorren', 8, 2)->default(0);
            $table->float('si203_vlinvestimentos', 8, 2)->default(0);
            $table->float('si203_vlinverfinanceira', 8, 2)->default(0);
            $table->float('si203_vlamortizadivida', 8, 2)->default(0);
            $table->float('si203_vlreservacontingen', 8, 2)->default(0);
            $table->float('si203_vlreservarpps', 8, 2)->default(0);
            $table->float('si203_vlamortizadiviintermob', 8, 2)->default(0);
            $table->float('si203_vlamortizaoutrasdivinter', 8, 2)->default(0);
            $table->float('si203_vlamortizadivextmob', 8, 2)->default(0);
            $table->float('si203_vlamortizaoutrasdivext', 8, 2)->default(0);
            $table->float('si203_vlsuperavit', 8, 2)->default(0);
            $table->float('si203_vltotalquadrodespesa', 8, 2)->nullable()->default(0);
            $table->integer('si203_anousu')->default(0);
            $table->integer('si203_periodo')->default(0);
            $table->integer('si203_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bodcasp302025_si203_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bodcasp302025 ALTER COLUMN si203_sequencial SET DEFAULT nextval(\'bodcasp302025_si203_sequencial_seq\');');

        Schema::create('bodcasp402025', function (Blueprint $table) {
            $table->integer('si204_sequencial')->default(0)->primary();
            $table->integer('si204_tiporegistro')->default(0);
            $table->integer('si204_faserestospagarnaoproc')->default(0);
            $table->float('si204_vlrspnaoprocpessoalencarsociais', 8, 2)->default(0);
            $table->float('si204_vlrspnaoprocjurosencardividas', 8, 2)->default(0);
            $table->float('si204_vlrspnaoprocoutrasdespcorrentes', 8, 2)->default(0);
            $table->float('si204_vlrspnaoprocinvestimentos', 8, 2)->default(0);
            $table->float('si204_vlrspnaoprocinverfinanceira', 8, 2)->default(0);
            $table->float('si204_vlrspnaoprocamortizadivida', 8, 2)->default(0);
            $table->float('si204_vltotalexecurspnaoprocessado', 8, 2)->nullable()->default(0);
            $table->integer('si204_ano')->default(0);
            $table->integer('si204_periodo')->default(0);
            $table->integer('si204_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bodcasp402025_si204_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bodcasp402025 ALTER COLUMN si204_sequencial SET DEFAULT nextval(\'bodcasp402025_si204_sequencial_seq\');');

        Schema::create('bodcasp502025', function (Blueprint $table) {
            $table->integer('si205_sequencial')->default(0)->primary();
            $table->integer('si205_tiporegistro')->default(0);
            $table->integer('si205_faserestospagarprocnaoliqui')->default(0);
            $table->float('si205_vlrspprocliqpessoalencarsoc', 8, 2)->default(0);
            $table->float('si205_vlrspprocliqjurosencardiv', 8, 2)->default(0);
            $table->float('si205_vlrspprocliqoutrasdespcorrentes', 8, 2)->default(0);
            $table->float('si205_vlrspprocesliqinv', 8, 2)->default(0);
            $table->float('si205_vlrspprocliqinverfinan', 8, 2)->default(0);
            $table->float('si205_vlrspprocliqamortizadivida', 8, 2)->default(0);
            $table->float('si205_vltotalexecrspprocnaoproceli', 8, 2)->nullable()->default(0);
            $table->integer('si205_ano')->default(0);
            $table->integer('si205_periodo')->default(0);
            $table->integer('si205_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bodcasp502025_si205_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bodcasp502025 ALTER COLUMN si205_sequencial SET DEFAULT nextval(\'bodcasp502025_si205_sequencial_seq\');');

        Schema::create('bfdcasp102025', function (Blueprint $table) {
            $table->integer('si206_sequencial')->default(0)->primary();
            $table->integer('si206_tiporegistro')->default(0);
            $table->integer('si206_exercicio')->default(0);
            $table->float('si206_vlrecorcamenrecurord', 8, 2)->default(0);
            $table->float('si206_vlrecorcamenrecinceduc', 8, 2)->default(0);
            $table->float('si206_vlrecorcamenrecurvincusaude', 8, 2)->default(0);
            $table->float('si206_vlrecorcamenrecurvincurpps', 8, 2)->default(0);
            $table->float('si206_vlrecorcamenrecurvincuassistsoc', 8, 2)->default(0);
            $table->float('si206_vlrecorcamenoutrasdestrecursos', 8, 2)->default(0);
            $table->float('si206_vltransfinanexecuorcamentaria', 8, 2)->default(0);
            $table->float('si206_vltransfinanindepenexecuorc', 8, 2)->default(0);
            $table->float('si206_vltransfinanreceaportesrpps', 8, 2)->default(0);
            $table->float('si206_vlincrirspnaoprocessado', 8, 2)->default(0);
            $table->float('si206_vlincrirspprocessado', 8, 2)->default(0);
            $table->float('si206_vldeporestituvinculados', 8, 2)->default(0);
            $table->float('si206_vloutrosrecextraorcamentario', 8, 2)->default(0);
            $table->float('si206_vlsaldoexeranteriorcaixaequicaixa', 8, 2)->default(0);
            $table->float('si206_vlsaldoexerantdeporestvinc', 8, 2)->default(0);
            $table->float('si206_vltotalingresso', 8, 2)->nullable()->default(0);
            $table->integer('si206_ano')->default(0);
            $table->integer('si206_periodo')->default(0);
            $table->integer('si206_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bfdcasp102025_si206_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bfdcasp102025 ALTER COLUMN si206_sequencial SET DEFAULT nextval(\'bfdcasp102025_si206_sequencial_seq\');');

        Schema::create('bfdcasp202025', function (Blueprint $table) {
            $table->integer('si207_sequencial')->default(0)->primary();
            $table->integer('si207_tiporegistro')->default(0);
            $table->integer('si207_exercicio')->default(0);
            $table->float('si207_vldesporcamenrecurordinarios', 8, 2)->default(0);
            $table->float('si207_vldesporcamenrecurvincueducacao', 8, 2)->default(0);
            $table->float('si207_vldesporcamenrecurvincusaude', 8, 2)->default(0);
            $table->float('si207_vldesporcamenrecurvincurpps', 8, 2)->default(0);
            $table->float('si207_vldesporcamenrecurvincuassistsoc', 8, 2)->default(0);
            $table->float('si207_vloutrasdesporcamendestrecursos', 8, 2)->default(0);
            $table->float('si207_vltransfinanconcexecorcamentaria', 8, 2)->default(0);
            $table->float('si207_vltransfinanconcindepenexecorc', 8, 2)->default(0);
            $table->float('si207_vltransfinanconcaportesrecurpps', 8, 2)->default(0);
            $table->float('si207_vlpagrspnaoprocessado', 8, 2)->default(0);
            $table->float('si207_vlpagrspprocessado', 8, 2)->default(0);
            $table->float('si207_vldeposrestvinculados', 8, 2)->default(0);
            $table->float('si207_vloutrospagextraorcamentarios', 8, 2)->default(0);
            $table->float('si207_vlsaldoexeratualcaixaequicaixa', 8, 2)->default(0);
            $table->float('si207_vlsaldoexeratualdeporestvinc', 8, 2)->default(0);
            $table->float('si207_vltotaldispendios', 8, 2)->nullable()->default(0);
            $table->integer('si207_ano')->default(0);
            $table->integer('si207_periodo')->default(0);
            $table->integer('si207_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bfdcasp202025_si207_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bfdcasp202025 ALTER COLUMN si207_sequencial SET DEFAULT nextval(\'bfdcasp202025_si207_sequencial_seq\');');

        Schema::create('bpdcasp102025', function (Blueprint $table) {
            $table->integer('si208_sequencial')->default(0)->primary();
            $table->integer('si208_tiporegistro')->default(0);
            $table->float('si208_vlativocircucaixaequicaixa', 8, 2)->default(0);
            $table->float('si208_vlativocircucredicurtoprazo', 8, 2)->default(0);
            $table->float('si208_vlativocircuinvestapliccurtoprazo', 8, 2)->default(0);
            $table->float('si208_vlativocircuestoques', 8, 2)->default(0);
            $table->float('si208_vlativocircuvpdantecipada', 8, 2)->default(0);
            $table->float('si208_vlativonaocircuinvestimentos', 8, 2)->default(0);
            $table->float('si208_vlativonaocircuimobilizado', 8, 2)->default(0);
            $table->float('si208_vlativonaocircuintagivel', 8, 2)->default(0);
            $table->float('si208_vltotalativo', 8, 2)->nullable()->default(0);
            $table->integer('si208_ano')->default(0);
            $table->integer('si208_periodo')->default(0);
            $table->integer('si208_institu')->default(0);
            $table->float('si208_vlativocircudemaiscredicurtoprazo', 8, 2)->default(0);
            $table->float('si208_vlativonaocircumantidovenda', 8, 2)->default(0);
            $table->float('si208_vlativonaocircurlp', 8, 2)->default(0);
            $table->float('si208_vlativocircuativobio', 8, 2)->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bpdcasp102025_si208_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bpdcasp102025 ALTER COLUMN si208_sequencial SET DEFAULT nextval(\'bpdcasp102025_si208_sequencial_seq\');');

        Schema::create('bpdcasp202025', function (Blueprint $table) {
            $table->integer('si209_sequencial')->default(0)->primary();
            $table->integer('si209_tiporegistro')->default(0);
            $table->integer('si209_exercicio')->default(0);
            $table->float('si209_vlpassivcircultrabprevicurtoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassivcirculemprefinancurtoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassivocirculafornecedcurtoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassicircuobrigfiscacurtoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassivocirculaobrigacoutrosentes', 8, 2)->default(0);
            $table->float('si209_vlpassivocirculaprovisoecurtoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassicircudemaiobrigcurtoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassinaocircutrabprevilongoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassnaocircemprfinalongpraz', 8, 2)->default(0);
            $table->float('si209_vlpassivnaocirculforneclongoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassnaocircobrifisclongpraz', 8, 2)->default(0);
            $table->float('si209_vlpassivnaocirculprovislongoprazo', 8, 2)->default(0);
            $table->float('si209_vlpassnaocircdemaobrilongpraz', 8, 2)->default(0);
            $table->float('si209_vlpassivonaocircularesuldiferido', 8, 2)->default(0);
            $table->float('si209_vlpatriliquidocapitalsocial', 8, 2)->default(0);
            $table->float('si209_vlpatriliquidoadianfuturocapital', 8, 2)->default(0);
            $table->float('si209_vlpatriliquidoreservacapital', 8, 2)->default(0);
            $table->float('si209_vlpatriliquidoajustavaliacao', 8, 2)->default(0);
            $table->float('si209_vlpatriliquidoreservalucr', 8, 2)->default(0);
            $table->float('si209_vlpatriliquidoreservamaiores', 8, 2)->default(0);
            $table->float('si209_vlpatriliquidoresultexercicio', 8, 2)->default(0);
            $table->integer('si209_ano')->default(0);
            $table->integer('si209_periodo')->default(0);
            $table->integer('si209_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bpdcasp202025_si209_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bpdcasp202025 ALTER COLUMN si209_sequencial SET DEFAULT nextval(\'bpdcasp202025_si209_sequencial_seq\');');

        Schema::create('bpdcasp302025', function (Blueprint $table) {
            $table->integer('si210_sequencial')->default(0)->primary();
            $table->integer('si210_tiporegistro')->default(0);
            $table->integer('si210_exercicio')->default(0);
            $table->float('si210_vlativofinanceiro', 8, 2)->default(0);
            $table->float('si210_vlativopermanente', 8, 2)->default(0);
            $table->float('si210_vltotalativofinanceiropermanente', 8, 2)->nullable()->default(0);
            $table->integer('si210_ano')->default(0);
            $table->integer('si210_periodo')->default(0);
            $table->integer('si210_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bpdcasp302025_si210_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bpdcasp302025 ALTER COLUMN si210_sequencial SET DEFAULT nextval(\'bpdcasp302025_si210_sequencial_seq\');');

        Schema::create('bpdcasp402025', function (Blueprint $table) {
            $table->integer('si211_sequencial')->default(0)->primary();
            $table->integer('si211_tiporegistro')->default(0);
            $table->integer('si211_exercicio')->default(0);
            $table->float('si211_vlpassivofinanceiro', 8, 2)->default(0);
            $table->float('si211_vlpassivopermanente', 8, 2)->default(0);
            $table->float('si211_vltotalpassivofinanceiropermanente', 8, 2)->nullable()->default(0);
            $table->integer('si211_ano')->default(0);
            $table->integer('si211_periodo')->default(0);
            $table->integer('si211_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bpdcasp402025_si211_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bpdcasp402025 ALTER COLUMN si211_sequencial SET DEFAULT nextval(\'bpdcasp402025_si211_sequencial_seq\');');

        Schema::create('bpdcasp502025', function (Blueprint $table) {
            $table->integer('si212_sequencial')->default(0)->primary();
            $table->integer('si212_tiporegistro')->default(0);
            $table->integer('si212_exercicio')->default(0);
            $table->float('si212_vlsaldopatrimonial', 8, 2)->nullable()->default(0);
            $table->integer('si212_ano')->default(0);
            $table->integer('si212_periodo')->default(0);
            $table->integer('si212_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bpdcasp502025_si212_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bpdcasp502025 ALTER COLUMN si212_sequencial SET DEFAULT nextval(\'bpdcasp502025_si212_sequencial_seq\');');

        Schema::create('bpdcasp602025', function (Blueprint $table) {
            $table->integer('si213_sequencial')->default(0)->primary();
            $table->integer('si213_tiporegistro')->default(0);
            $table->integer('si213_exercicio')->default(0);
            $table->float('si213_vlatospotenativosgarancontrarecebi', 8, 2)->default(0);
            $table->float('si213_vlatospotenativodirconveoutroinstr', 8, 2)->default(0);
            $table->float('si213_vlatospotenativosdireitoscontratua', 8, 2)->default(0);
            $table->float('si213_vlatospotenativosoutrosatos', 8, 2)->default(0);
            $table->float('si213_vlatospotenpassivgarancontraconced', 8, 2)->default(0);
            $table->float('si213_vlatospotepassobriconvoutrinst', 8, 2)->default(0);
            $table->float('si213_vlatospotenpassivoobrigacocontratu', 8, 2)->default(0);
            $table->float('si213_vlatospotenpassivooutrosatos', 8, 2)->nullable()->default(0);
            $table->integer('si213_ano')->default(0);
            $table->integer('si213_periodo')->default(0);
            $table->integer('si213_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bpdcasp602025_si213_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bpdcasp602025 ALTER COLUMN si213_sequencial SET DEFAULT nextval(\'bpdcasp602025_si213_sequencial_seq\');');

        Schema::create('bpdcasp702025', function (Blueprint $table) {
            $table->integer('si214_sequencial')->default(0)->primary();
            $table->integer('si214_tiporegistro')->default(0);
            $table->integer('si214_exercicio')->default(0);
            $table->float('si214_vltotalsupdef', 8, 2)->nullable()->default(0);
            $table->integer('si214_ano')->default(0);
            $table->integer('si214_periodo')->default(0);
            $table->integer('si214_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bpdcasp702025_si214_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bpdcasp702025 ALTER COLUMN si214_sequencial SET DEFAULT nextval(\'bpdcasp702025_si214_sequencial_seq\');');

        Schema::create('bpdcasp712025', function (Blueprint $table) {
            $table->integer('si215_sequencial')->default(0)->primary();
            $table->integer('si215_tiporegistro')->default(0);
            $table->integer('si215_exercicio')->default(0);
            $table->integer('si215_codfontrecursos')->default(0);
            $table->float('si215_vlsaldofonte', 8, 2)->nullable()->default(0);
            $table->integer('si215_ano')->default(0);
            $table->integer('si215_periodo')->default(0);
            $table->integer('si215_institu')->default(0);
            $table->integer('si215_codfontrecursos24')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE bpdcasp712025_si215_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE bpdcasp712025 ALTER COLUMN si215_sequencial SET DEFAULT nextval(\'bpdcasp712025_si215_sequencial_seq\');');

        Schema::create('cadobras102025', function (Blueprint $table) {
            $table->bigInteger('si198_sequencial')->nullable()->primary();
            $table->bigInteger('si198_tiporegistro')->nullable();
            $table->string('si198_codorgaoresp', 3)->nullable();
            $table->bigInteger('si198_codobra')->nullable();
            $table->bigInteger('si198_tiporesponsavel')->nullable();
            $table->bigInteger('si198_tipodocumento')->nullable();
            $table->string('si198_nrodocumento', 14)->nullable();
            $table->bigInteger('si198_tiporegistroconselho')->nullable();
            $table->string('si198_dscoutroconselho', 40)->nullable();
            $table->string('si198_nroregistroconseprof', 10)->nullable();
            $table->bigInteger('si198_numrt')->default(0)->nullable();
            $table->date('si198_dtinicioatividadeseng')->nullable();
            $table->bigInteger('si198_tipovinculo')->nullable();
            $table->bigInteger('si198_mes')->nullable();
            $table->integer('si198_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE cadobras102025_si198_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cadobras102025 ALTER COLUMN si198_sequencial SET DEFAULT nextval(\'cadobras102025_si198_sequencial_seq\');');

        Schema::create('cadobras202025', function (Blueprint $table) {
            $table->bigInteger('si199_sequencial')->nullable()->primary();
            $table->bigInteger('si199_tiporegistro')->nullable();
            $table->string('si199_codorgaoresp', 3)->nullable();
            $table->bigInteger('si199_codobra')->nullable();
            $table->bigInteger('si199_situacaodaobra')->nullable();
            $table->date('si199_dtsituacao')->nullable();
            $table->string('si199_veiculopublicacao', 50)->nullable();
            $table->date('si199_dtpublicacao')->nullable();
            $table->string('si199_descsituacao', 500)->nullable();
            $table->bigInteger('si199_mes')->nullable();
            $table->integer('si199_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE cadobras202025_si199_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cadobras202025 ALTER COLUMN si199_sequencial SET DEFAULT nextval(\'cadobras202025_si199_sequencial_seq\');');

        Schema::create('cadobras212025', function (Blueprint $table) {
            $table->bigInteger('si200_sequencial')->nullable()->primary();
            $table->bigInteger('si200_tiporegistro')->nullable();
            $table->string('si200_codorgaoresp', 3)->nullable();
            $table->bigInteger('si200_codobra')->nullable();
            $table->date('si200_dtparalisacao')->nullable();
            $table->bigInteger('si200_motivoparalisacap')->nullable();
            $table->string('si200_descoutrosparalisacao', 150)->nullable();
            $table->date('si200_dtretomada')->nullable();
            $table->bigInteger('si200_mes')->nullable();
            $table->integer('si200_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE cadobras212025_si200_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cadobras212025 ALTER COLUMN si200_sequencial SET DEFAULT nextval(\'cadobras212025_si200_sequencial_seq\');');

        Schema::create('cadobras302025', function (Blueprint $table) {
            $table->bigInteger('si201_sequencial')->nullable()->primary();
            $table->bigInteger('si201_tiporegistro')->nullable();
            $table->string('si201_codorgaoresp', 3)->nullable();
            $table->bigInteger('si201_codobra')->nullable();
            $table->bigInteger('si201_tipomedicao')->nullable();
            $table->string('si201_descoutrostiposmed', 500)->nullable();
            $table->string('si201_nummedicao', 20)->nullable();
            $table->string('si201_descmedicao', 500)->nullable();
            $table->date('si201_dtiniciomedicao')->nullable();
            $table->date('si201_dtfimmedicao')->nullable();
            $table->date('si201_dtmedicao')->nullable();
            $table->float('si201_valormedicao', 8, 2)->nullable();
            $table->bigInteger('si201_mes')->nullable();
            $table->string('si201_pdf', 25)->nullable();
            $table->integer('si201_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE cadobras302025_si201_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cadobras302025 ALTER COLUMN si201_sequencial SET DEFAULT nextval(\'cadobras302025_si201_sequencial_seq\');');

        Schema::create('caixa102025', function (Blueprint $table) {
            $table->bigInteger('si103_sequencial')->default(0)->primary();
            $table->bigInteger('si103_tiporegistro')->default(0);
            $table->string('si103_codorgao', 2);
            $table->float('si103_vlsaldoinicial', 8, 2)->default(0);
            $table->float('si103_vlsaldofinal', 8, 2)->default(0);
            $table->bigInteger('si103_mes')->default(0);
            $table->bigInteger('si103_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE caixa102025_si103_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE caixa102025 ALTER COLUMN si103_sequencial SET DEFAULT nextval(\'caixa102025_si103_sequencial_seq\');');

        Schema::create('conge102025', function (Blueprint $table) {
            $table->bigInteger('si182_sequencial')->default(0)->primary();
            $table->bigInteger('si182_tiporegistro')->default(0);
            $table->bigInteger('si182_codconvenioconge')->default(0);
            $table->string('si182_codorgao', 2);
            $table->string('si182_codunidadesub', 8);
            $table->string('si182_nroconvenioconge', 30);
            $table->string('si182_dscinstrumento', 50);
            $table->date('si182_dataassinaturaconge');
            $table->date('si182_datapublicconge');
            $table->string('si182_nrocpfrespconge', 11);
            $table->string('si182_dsccargorespconge', 50);
            $table->string('si182_objetoconvenioconge', 500);
            $table->date('si182_datainiciovigenciaconge');
            $table->date('si182_datafinalvigenciaconge');
            $table->bigInteger('si182_formarepasse');
            $table->bigInteger('si182_tipodocumentoincentivador')->nullable();
            $table->string('si182_nrodocumentoincentivador', 14)->nullable();
            $table->bigInteger('si182_quantparcelas')->nullable();
            $table->float('si182_vltotalconvenioconge', 8, 2)->default(0);
            $table->float('si182_vlcontrapartidaconge', 8, 2)->default(0);
            $table->bigInteger('si182_tipodocumentobeneficiario')->default(0);
            $table->string('si182_nrodocumentobeneficiario', 14)->default('0');
            $table->bigInteger('si182_mes')->default(0);
            $table->bigInteger('si182_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE conge102025_si182_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conge102025 ALTER COLUMN si182_sequencial SET DEFAULT nextval(\'conge102025_si182_sequencial_seq\');');

        Schema::create('conge202025', function (Blueprint $table) {
            $table->bigInteger('si183_sequencial')->default(0)->primary();
            $table->bigInteger('si183_tiporegistro')->default(0);
            $table->string('si183_codorgao', 2);
            $table->string('si183_codunidadesub', 8);
            $table->string('si183_nroconvenioconge', 30);
            $table->date('si183_dataassinaturaconvoriginalconge');
            $table->bigInteger('si183_nroseqtermoaditivoconge')->default(0);
            $table->string('si183_dscalteracaoconge', 500)->default('0');
            $table->date('si183_dataassinaturatermoaditivoconge');
            $table->date('si183_datafinalvigenciaconge');
            $table->float('si183_valoratualizadoconvenioconge', 8, 2);
            $table->float('si183_valoratualizadocontrapartidaconge', 8, 2);
            $table->bigInteger('si183_mes')->default(0);
            $table->bigInteger('si183_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE conge202025_si183_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conge202025 ALTER COLUMN si183_sequencial SET DEFAULT nextval(\'conge202025_si183_sequencial_seq\');');

        Schema::create('conge302025', function (Blueprint $table) {
            $table->bigInteger('si184_sequencial')->default(0)->primary();
            $table->bigInteger('si184_tiporegistro')->default(0);
            $table->string('si184_codorgao', 2);
            $table->string('si184_codunidadesub', 8);
            $table->string('si184_nroconvenioconge', 30);
            $table->date('si184_dataassinaturaconvoriginalconge');
            $table->bigInteger('si184_numeroparcela')->default(0);
            $table->bigInteger('si184_datarepasseconge')->default(0);
            $table->float('si184_vlrepassadoconge', 8, 2);
            $table->string('si184_banco', 3)->default('0');
            $table->string('si184_agencia', 6)->default('0');
            $table->string('si184_digitoverificadoragencia', 2)->default('0');
            $table->string('si184_contabancaria', 12)->default('0');
            $table->string('si184_digitoverificadorcontabancaria', 2)->default('0');
            $table->bigInteger('si184_tipodocumentotitularconta')->default(0);
            $table->string('si184_nrodocumentotitularconta', 14)->default('0');
            $table->date('si184_prazoprestacontas');
            $table->bigInteger('si184_mes')->default(0);
            $table->bigInteger('si184_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE conge302025_si184_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conge302025 ALTER COLUMN si184_sequencial SET DEFAULT nextval(\'conge302025_si184_sequencial_seq\');');

        Schema::create('conge402025', function (Blueprint $table) {
            $table->bigInteger('si237_sequencial')->default(0)->primary();;
            $table->integer('si237_tiporegistro')->default(0);
            $table->string('si237_codorgao', 2);
            $table->string('si237_codunidadesub', 8);
            $table->string('si237_nroconvenioconge', 30);
            $table->date('si237_dataassinaturaconvoriginalconge');
            $table->bigInteger('si237_datarepasseconge')->default(0);
            $table->bigInteger('si237_prestacaocontasparcela');
            $table->date('si237_dataprestacontasparcela')->nullable();
            $table->bigInteger('si237_prestacaocontas')->nullable();
            $table->date('si237_datacienfatos')->nullable();
            $table->bigInteger('si237_prorrogprazo')->default(0);
            $table->date('si237_dataprorrogprazo')->nullable();
            $table->string('si237_nrocpfrespprestconge', 11)->nullable();
            $table->string('si237_dsccargorespprestconge', 50)->nullable();
            $table->integer('si237_mes')->default(0);
            $table->bigInteger('si237_instit')->default(0)->nullable();
        });
        DB::statement('
            CREATE SEQUENCE conge402025_si237_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conge402025 ALTER COLUMN si237_sequencial SET DEFAULT nextval(\'conge402025_si237_sequencial_seq\');');

        Schema::create('conge502025', function (Blueprint $table) {
            $table->bigInteger('si238_sequencial')->default(0);
            $table->integer('si238_tiporegistro')->default(0);
            $table->string('si238_codorgao', 2);
            $table->string('si238_codunidadesub', 8);
            $table->string('si238_nroconvenioconge', 30);
            $table->date('si238_dataassinaturaconvoriginalconge');
            $table->string('si238_dscmedidaadministrativa', 500);
            $table->date('si238_datainiciomedida');
            $table->date('si238_datafinalmedida');
            $table->bigInteger('si238_adocaomedidasadmin')->default(0);
            $table->string('si238_nrocpfrespmedidaconge', 11);
            $table->string('si238_dsccargorespmedidaconge', 50);
            $table->integer('si238_mes')->default(0);
            $table->bigInteger('si238_instit')->default(0)->nullable();
        });
        DB::statement('
            CREATE SEQUENCE conge502025_si238_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conge502025 ALTER COLUMN si238_sequencial SET DEFAULT nextval(\'conge502025_si238_sequencial_seq\');');

        Schema::create('consid102025', function (Blueprint $table) {
            $table->bigInteger('si158_sequencial');
            $table->integer('si158_tiporegistro')->default(0);
            $table->string('si158_codarquivo', 20);
            $table->integer('si158_exercicioreferenciaconsid')->nullable()->default(0);
            $table->string('si158_mesreferenciaconsid', 2)->nullable();
            $table->text('si158_consideracoes');
            $table->integer('si158_mes')->nullable();
            $table->integer('si158_instit')->nullable();
            $table->primary('si158_sequencial');
        });
        DB::statement('CREATE SEQUENCE consid102025_si158_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');

        DB::statement('ALTER TABLE consid102025 ALTER COLUMN si158_sequencial SET DEFAULT nextval(\'consid102025_si158_sequencial_seq\');');

        Schema::create('consor102025', function (Blueprint $table) {
            $table->bigInteger('si16_sequencial');
            $table->integer('si16_tiporegistro')->default(0);
            $table->string('si16_codorgao', 2);
            $table->string('si16_cnpjconsorcio', 14);
            $table->string('si16_areaatuacao', 2);
            $table->string('si16_descareaatuacao', 150)->nullable();
            $table->integer('si16_mes')->default(0);
            $table->integer('si16_instit')->nullable();

            $table->primary('si16_sequencial');
        });
        DB::statement('CREATE SEQUENCE consor102025_si16_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE consor102025 ALTER COLUMN si16_sequencial SET DEFAULT nextval(\'consor102025_si16_sequencial_seq\');');

        Schema::create('consor202025', function (Blueprint $table) {
            $table->bigInteger('si17_sequencial');
            $table->integer('si17_tiporegistro')->default(0);
            $table->string('si17_codorgao', 2);
            $table->string('si17_cnpjconsorcio', 14);
            $table->integer('si17_codfontrecursos')->default(0);
            $table->float('si17_vltransfrateio')->default(0);
            $table->integer('si17_prestcontas')->default(0);
            $table->integer('si17_mes')->default(0);
            $table->integer('si17_instit')->nullable();
            $table->text('si17_codacompanhamento')->nullable();

            $table->primary('si17_sequencial');
        });
        DB::statement('CREATE SEQUENCE consor202025_si17_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE consor202025 ALTER COLUMN si17_sequencial SET DEFAULT nextval(\'consor202025_si17_sequencial_seq\');');

        Schema::create('consor302025', function (Blueprint $table) {
            $table->bigInteger('si18_sequencial');
            $table->integer('si18_tiporegistro')->default(0);
            $table->string('si18_cnpjconsorcio', 14);
            $table->string('si18_mesreferencia', 2);
            $table->string('si18_codfuncao', 2);
            $table->string('si18_codsubfuncao', 3);
            $table->integer('si18_naturezadespesa')->default(0);
            $table->string('si18_subelemento', 2);
            $table->integer('si18_codfontrecursos')->default(0);
            $table->float('si18_vlempenhadofonte')->default(0);
            $table->float('si18_vlanulacaoempenhofonte')->default(0);
            $table->float('si18_vlliquidadofonte')->default(0);
            $table->float('si18_vlanulacaoliquidacaofonte')->default(0);
            $table->float('si18_vlpagofonte')->default(0);
            $table->float('si18_vlanulacaopagamentofonte')->default(0);
            $table->integer('si18_mes')->default(0);
            $table->integer('si18_instit')->nullable();
            $table->text('si18_codacompanhamento')->nullable();

            $table->primary('si18_sequencial');
        });
        DB::statement('CREATE SEQUENCE consor302025_si18_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE consor302025 ALTER COLUMN si18_sequencial SET DEFAULT nextval(\'consor302025_si18_sequencial_seq\');');

        Schema::create('consor402025', function (Blueprint $table) {
            $table->bigInteger('si19_sequencial');
            $table->integer('si19_tiporegistro')->default(0);
            $table->string('si19_cnpjconsorcio', 14);
            $table->integer('si19_codfontrecursos')->default(0);
            $table->float('si19_vldispcaixa')->default(0);
            $table->integer('si19_mes')->default(0);
            $table->integer('si19_instit')->nullable();

            $table->primary('si19_sequencial');
        });
        DB::statement('CREATE SEQUENCE consor402025_si19_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE consor402025 ALTER COLUMN si19_sequencial SET DEFAULT nextval(\'consor402025_si19_sequencial_seq\');');

        Schema::create('consor502025', function (Blueprint $table) {
            $table->bigInteger('si20_sequencial');
            $table->integer('si20_tiporegistro')->default(0);
            $table->string('si20_codorgao', 2);
            $table->string('si20_cnpjconsorcio', 14);
            $table->integer('si20_tipoencerramento')->default(0);
            $table->date('si20_dtencerramento');
            $table->integer('si20_mes')->default(0);
            $table->integer('si20_instit')->nullable();

            $table->primary('si20_sequencial');
        });
        DB::statement('CREATE SEQUENCE consor502025_si20_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE consor502025 ALTER COLUMN si20_sequencial SET DEFAULT nextval(\'consor502025_si20_sequencial_seq\');');

        Schema::create('contratos102025', function (Blueprint $table) {
            $table->bigInteger('si83_sequencial');
            $table->integer('si83_tiporegistro')->default(0);
            $table->integer('si83_tipocadastro')->nullable();
            $table->integer('si83_codcontrato')->default(0);
            $table->string('si83_codorgao', 2);
            $table->string('si83_codunidadesub', 8);
            $table->integer('si83_nrocontrato')->default(0);
            $table->integer('si83_exerciciocontrato')->default(0);
            $table->date('si83_dataassinatura');
            $table->integer('si83_contdeclicitacao')->default(0);
            $table->string('si83_cnpjorgaoentresp', 14)->nullable();
            $table->string('si83_codunidadesubresp', 8)->nullable();
            $table->string('si83_nroprocesso', 12)->nullable();
            $table->integer('si83_exercicioprocesso')->nullable();
            $table->integer('si83_tipoprocesso')->nullable();
            $table->integer('si83_naturezaobjeto')->default(0);
            $table->text('si83_objetocontrato');
            $table->date('si83_datainiciovigencia');
            $table->integer('si83_vigenciaindeterminada')->nullable();
            $table->date('si83_datafinalvigencia')->nullable();
            $table->float('si83_vlcontrato')->default(0);
            $table->string('si83_formafornecimento', 50)->nullable();
            $table->string('si83_formapagamento', 100)->nullable();
            $table->integer('si83_indcriterioreajuste')->nullable();
            $table->date('si83_databasereajuste')->nullable();
            $table->string('si83_periodicidadereajuste', 2)->nullable();
            $table->string('si83_tipocriterioreajuste', 2)->nullable();
            $table->text('si83_dscreajuste')->nullable();
            $table->string('si83_indiceunicoreajuste', 2)->nullable();
            $table->text('si83_dscindice')->nullable();
            $table->integer('si83_unidadedemedidaprazoexec')->nullable();
            $table->integer('si83_prazoexecucao')->nullable();
            $table->string('si83_multarescisoria', 100)->nullable();
            $table->string('si83_justificativarescisoria', 200)->nullable();
            $table->integer('si83_instit')->nullable();

            $table->primary('si83_sequencial');
        });
        DB::statement('CREATE SEQUENCE contratos102025_si83_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE contratos102025 ALTER COLUMN si83_sequencial SET DEFAULT nextval(\'contratos102025_si83_sequencial_seq\');');

        Schema::create('contratos112025', function (Blueprint $table) {
            $table->bigInteger('si84_sequencial');
            $table->integer('si84_tiporegistro')->default(0);
            $table->integer('si84_codcontrato')->default(0);
            $table->integer('si84_coditem')->default(0);
            $table->integer('si84_tipomaterial')->nullable();
            $table->string('si84_coditemsinapi', 15)->nullable();
            $table->string('si84_coditemsimcro', 15)->nullable();
            $table->string('si84_descoutrosmateriais', 250)->nullable();
            $table->integer('si84_itemplanilha')->nullable();
            $table->float('si84_quantidadeitem')->default(0);
            $table->float('si84_valorunitarioitem')->default(0);
            $table->integer('si84_mes')->default(0);
            $table->integer('si84_reg10')->default(0);
            $table->integer('si84_instit')->nullable();
            $table->integer('si84_nrolote')->nullable();

            $table->primary('si84_sequencial');
        });
        DB::statement('CREATE SEQUENCE contratos112025_si84_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE contratos112025 ALTER COLUMN si84_sequencial SET DEFAULT nextval(\'contratos112025_si84_sequencial_seq\');');

        Schema::create('contratos122025', function (Blueprint $table) {
            $table->bigInteger('si85_sequencial');
            $table->integer('si85_tiporegistro')->default(0);
            $table->integer('si85_codcontrato')->default(0);
            $table->string('si85_codorgao', 2);
            $table->string('si85_codunidadesub', 8);
            $table->string('si85_codfuncao', 2);
            $table->string('si85_codsubfuncao', 3);
            $table->string('si85_codprograma', 4);
            $table->string('si85_idacao', 4);
            $table->string('si85_idsubacao', 4)->nullable();
            $table->integer('si85_naturezadespesa')->default(0);
            $table->integer('si85_codfontrecursos')->default(0);
            $table->float('si85_vlrecurso')->default(0);
            $table->integer('si85_mes')->default(0);
            $table->integer('si85_reg10')->default(0);
            $table->integer('si85_instit')->nullable();

            $table->primary('si85_sequencial');
        });
        DB::statement('CREATE SEQUENCE contratos122025_si85_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE contratos122025 ALTER COLUMN si85_sequencial SET DEFAULT nextval(\'contratos122025_si85_sequencial_seq\');');
        DB::statement('CREATE INDEX contratos122025_si85_reg10_index ON contratos122025 USING btree (si85_reg10);');

        Schema::create('contratos132025', function (Blueprint $table) {
            $table->bigInteger('si86_sequencial');
            $table->integer('si86_tiporegistro')->default(0);
            $table->integer('si86_codcontrato')->default(0);
            $table->integer('si86_tipodocumento')->default(0);
            $table->string('si86_nrodocumento', 14);
            $table->integer('si86_tipodocrepresentante')->nullable();
            $table->string('si86_nrodocrepresentantelegal', 14);
            $table->integer('si86_mes')->default(0);
            $table->integer('si86_reg10')->default(0);
            $table->integer('si86_instit')->nullable();

            $table->primary('si86_sequencial');
        });
        DB::statement('CREATE SEQUENCE contratos132025_si86_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE contratos132025 ALTER COLUMN si86_sequencial SET DEFAULT nextval(\'contratos132025_si86_sequencial_seq\');');
        DB::statement('CREATE INDEX contratos132025_si86_reg10_index ON contratos132025 USING btree (si86_reg10);');


        Schema::create('contratos202025', function (Blueprint $table) {
            $table->bigInteger('si87_sequencial');
            $table->integer('si87_tiporegistro')->default(0);
            $table->integer('si87_codaditivo')->default(0);
            $table->string('si87_codorgao', 2);
            $table->string('si87_codunidadesub', 8);
            $table->string('si87_codunidadesubatual', 8)->nullable();
            $table->string('si87_nrocontrato', 14)->default(0);
            $table->string('si87_nroseqtermoaditivo', 2);
            $table->date('si87_dtassinaturatermoaditivo');
            $table->integer('si87_tipoalteracaovalor')->default(0);
            $table->string('si87_tipotermoaditivo', 2);
            $table->string('si87_dscalteracao', 250)->nullable();
            $table->date('si87_novadatatermino')->nullable();
            $table->float('si87_percentualreajuste')->nullable();
            $table->integer('si87_criterioreajuste')->nullable();
            $table->string('si87_descricaoindice', 300)->nullable();
            $table->integer('si87_indiceunicoreajuste')->nullable();
            $table->string('si87_descricaoreajuste', 300)->nullable();
            $table->float('si87_valoraditivo')->default(0);
            $table->date('si87_datapublicacao');
            $table->string('si87_veiculodivulgacao', 50);
            $table->integer('si87_mes')->default(0);
            $table->integer('si87_instit')->nullable();
            $table->integer('si87_exerciciocontrato')->nullable();

            $table->primary('si87_sequencial');
        });
        DB::statement('CREATE SEQUENCE contratos202025_si87_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE contratos202025 ALTER COLUMN si87_sequencial SET DEFAULT nextval(\'contratos202025_si87_sequencial_seq\');');

        Schema::create('contratos212025', function (Blueprint $table) {
            $table->bigInteger('si88_sequencial');
            $table->integer('si88_tiporegistro')->default(0);
            $table->integer('si88_codaditivo')->default(0);
            $table->integer('si88_coditem')->default(0);
            $table->integer('si88_tipomaterial')->nullable();
            $table->string('si88_coditemsinapi', 15)->nullable();
            $table->string('si88_coditemsimcro', 15)->nullable();
            $table->string('si88_descoutrosmateriais', 250)->nullable();
            $table->integer('si88_itemplanilha')->nullable();
            $table->integer('si88_tipoalteracaoitem')->default(0);
            $table->float('si88_quantacrescdecresc')->default(0);
            $table->float('si88_valorunitarioitem')->default(0);
            $table->integer('si88_mes')->default(0);
            $table->integer('si88_reg20')->default(0);
            $table->integer('si88_instit')->nullable();
            $table->integer('si88_nrolote')->nullable();

            $table->primary('si88_sequencial');
        });
        DB::statement('CREATE SEQUENCE contratos212025_si88_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE contratos212025 ALTER COLUMN si88_sequencial SET DEFAULT nextval(\'contratos212025_si88_sequencial_seq\');');

        Schema::create('contratos302025', function (Blueprint $table) {
            $table->bigInteger('si89_sequencial');
            $table->integer('si89_tiporegistro')->default(0);
            $table->string('si89_codorgao', 2);
            $table->string('si89_codunidadesub', 8);
            $table->string('si89_codunidadesubatual', 8)->nullable();
            $table->string('si89_nrocontrato', 14)->default(0);
            $table->string('si89_tipoapostila', 2);
            $table->integer('si89_nroseqapostila')->default(0);
            $table->date('si89_dataapostila');
            $table->integer('si89_tipoalteracaoapostila')->default(0);
            $table->string('si89_dscalteracao', 250);
            $table->float('si89_percentualreajuste')->nullable();
            $table->integer('si89_criterioreajuste')->nullable();
            $table->string('si89_descricaoindice', 300)->nullable();
            $table->integer('si89_indiceunicoreajuste')->nullable();
            $table->string('si89_dscreajuste', 300)->nullable();
            $table->float('si89_valorapostila')->default(0);
            $table->integer('si89_mes')->default(0);
            $table->integer('si89_instit')->nullable();
            $table->integer('si89_exerciciocontrato')->nullable();

            $table->primary('si89_sequencial');
        });
        DB::statement('CREATE SEQUENCE contratos302025_si89_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE contratos302025 ALTER COLUMN si89_sequencial SET DEFAULT nextval(\'contratos302025_si89_sequencial_seq\');');

        Schema::create('contratos402025', function (Blueprint $table) {
            $table->bigInteger('si91_sequencial');
            $table->integer('si91_tiporegistro')->default(0);
            $table->string('si91_codorgao', 2);
            $table->string('si91_codunidadesub', 8)->nullable();
            $table->string('si91_codunidadesubatual', 8)->nullable();
            $table->string('si91_nrocontrato', 14)->default(0);
            $table->date('si91_datarescisao');
            $table->float('si91_valorcancelamentocontrato')->default(0);
            $table->integer('si91_mes')->default(0);
            $table->integer('si91_instit')->nullable();
            $table->integer('si91_exerciciocontrato')->nullable();

            $table->primary('si91_sequencial');
        });
        DB::statement('CREATE SEQUENCE contratos402025_si91_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;');
        DB::statement('ALTER TABLE contratos402025 ALTER COLUMN si91_sequencial SET DEFAULT nextval(\'contratos402025_si91_sequencial_seq\');');

        Schema::create('conv102025', function (Blueprint $table) {
            $table->bigInteger('si92_sequencial')->default(0);
            $table->bigInteger('si92_tiporegistro')->default(0);
            $table->bigInteger('si92_codconvenio')->default(0);
            $table->string('si92_codorgao', 2);
            $table->string('si92_nroconvenio', 30);
            $table->date('si92_dataassinatura');
            $table->string('si92_objetoconvenio', 500);
            $table->date('si92_datainiciovigencia');
            $table->date('si92_datafinalvigencia');
            $table->bigInteger('si92_codfontrecursos')->default(0);
            $table->double('si92_vlconvenio', 15, 2)->default(0);
            $table->double('si92_vlcontrapartida', 15, 2)->default(0);
            $table->bigInteger('si92_mes')->default(0);
            $table->bigInteger('si92_instit')->nullable();
            $table->primary('si92_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE conv102025_si92_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conv102025 ALTER COLUMN si92_sequencial SET DEFAULT nextval(\'conv102025_si92_sequencial_seq\');');

        Schema::create('conv202025', function (Blueprint $table) {
            $table->bigInteger('si94_sequencial')->default(0);
            $table->bigInteger('si94_tiporegistro')->default(0);
            $table->string('si94_codorgao', 2);
            $table->string('si94_nroconvenio', 30);
            $table->date('si94_dtassinaturaconvoriginal');
            $table->string('si94_nroseqtermoaditivo', 2);
            $table->string('si94_codconvaditivo', 20);
            $table->string('si94_dscalteracao', 500);
            $table->date('si94_dtassinaturatermoaditivo');
            $table->date('si94_datafinalvigencia');
            $table->double('si94_valoratualizadoconvenio', 15, 2)->default(0);
            $table->double('si94_valoratualizadocontrapartida', 15, 2)->default(0);
            $table->bigInteger('si94_mes')->default(0);
            $table->bigInteger('si94_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE conv202025_si94_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conv202025 ALTER COLUMN si94_sequencial SET DEFAULT nextval(\'conv202025_si94_sequencial_seq\');');

        Schema::create('conv212025', function (Blueprint $table) {
            $table->bigInteger('si232_sequencial')->default(0);
            $table->bigInteger('si232_tiporegistro')->default(0);
            $table->string('si232_codconvaditivo', 20);
            $table->string('si232_tipotermoaditivo', 2);
            $table->string('si232_dsctipotermoaditivo', 250)->nullable();
            $table->bigInteger('si232_mes')->default(0);
            $table->bigInteger('si232_instint')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE conv212025_si232_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conv212025 ALTER COLUMN si232_sequencial SET DEFAULT nextval(\'conv212025_si232_sequencial_seq\');');

        Schema::create('conv302025', function (Blueprint $table) {
            $table->bigInteger('si203_sequencial')->default(0);
            $table->bigInteger('si203_tiporegistro')->default(0);
            $table->bigInteger('si203_codreceita')->default(0);
            $table->string('si203_codorgao', 2);
            $table->bigInteger('si203_naturezareceita')->default(0);
            $table->bigInteger('si203_codfontrecursos')->default(0);
            $table->double('si203_vlprevisao', 15, 2)->default(0);
            $table->bigInteger('si203_mes')->default(0);
            $table->bigInteger('si203_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE conv302025_si203_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conv302025 ALTER COLUMN si203_sequencial SET DEFAULT nextval(\'conv302025_si203_sequencial_seq\');');

        Schema::create('conv312025', function (Blueprint $table) {
            $table->bigInteger('si204_sequencial')->default(0);
            $table->bigInteger('si204_tiporegistro')->default(0);
            $table->bigInteger('si204_codreceita')->default(0);
            $table->bigInteger('si204_prevorcamentoassin')->default(0);
            $table->string('si204_nroconvenio', 30)->nullable();
            $table->date('si204_dataassinatura')->nullable();
            $table->double('si204_vlprevisaoconvenio', 15, 2)->default(0);
            $table->bigInteger('si204_mes')->default(0);
            $table->bigInteger('si204_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE conv312025_si204_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE conv312025 ALTER COLUMN si204_sequencial SET DEFAULT nextval(\'conv312025_si204_sequencial_seq\');');

        Schema::create('cronem102025', function (Blueprint $table) {
            $table->bigInteger('si170_sequencial')->default(0);
            $table->bigInteger('si170_tiporegistro')->default(0);
            $table->string('si170_codorgao', 2)->default(0);
            $table->string('si170_codunidadesub', 8)->default(0);
            $table->bigInteger('si170_grupodespesa')->default(0);
            $table->double('si170_vldotmensal', 15, 2)->default(0);
            $table->bigInteger('si170_instit')->nullable();
            $table->bigInteger('si170_mes')->nullable();
            $table->primary('si170_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE cronem102025_si170_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cronem102025 ALTER COLUMN si170_sequencial SET DEFAULT nextval(\'cronem102025_si170_sequencial_seq\');');

        Schema::create('ctb102025', function (Blueprint $table) {
            $table->bigInteger('si95_sequencial')->default(0);
            $table->bigInteger('si95_tiporegistro')->default(0);
            $table->bigInteger('si95_codctb')->default(0);
            $table->string('si95_codorgao', 2);
            $table->string('si95_banco', 3);
            $table->string('si95_agencia', 6);
            $table->string('si95_digitoverificadoragencia', 2)->nullable();
            $table->bigInteger('si95_contabancaria')->default(0);
            $table->string('si95_digitoverificadorcontabancaria', 2);
            $table->string('si95_tipoconta', 2);
            $table->bigInteger('si95_nroseqaplicacao')->nullable();
            $table->string('si95_desccontabancaria', 50);
            $table->bigInteger('si95_contaconvenio')->default(0);
            $table->string('si95_nroconvenio', 30)->nullable();
            $table->date('si95_dataassinaturaconvenio')->nullable();
            $table->bigInteger('si95_mes')->default(0);
            $table->bigInteger('si95_instit')->nullable();
            $table->primary('si95_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE ctb102025_si95_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ctb102025 ALTER COLUMN si95_sequencial SET DEFAULT nextval(\'ctb102025_si95_sequencial_seq\');');

        Schema::create('ctb202025', function (Blueprint $table) {
            $table->bigInteger('si96_sequencial')->default(0);
            $table->bigInteger('si96_tiporegistro')->default(0);
            $table->string('si96_codorgao', 2);
            $table->bigInteger('si96_codctb')->default(0);
            $table->bigInteger('si96_codfontrecursos')->default(0);
            $table->double('si96_vlsaldoinicialfonte', 15, 2)->default(0);
            $table->double('si96_vlsaldofinalfonte', 15, 2)->default(0);
            $table->bigInteger('si96_mes')->default(0);
            $table->bigInteger('si96_instit')->nullable();
            $table->bigInteger('si96_saldocec')->nullable();
            $table->primary('si96_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE ctb202025_si96_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ctb202025 ALTER COLUMN si96_sequencial SET DEFAULT nextval(\'ctb202025_si96_sequencial_seq\');');

        Schema::create('ctb302025', function (Blueprint $table) {
            $table->bigInteger('si99_sequencial')->default(0);
            $table->bigInteger('si99_tiporegistro')->default(0);
            $table->string('si99_codorgao', 2);
            $table->bigInteger('si99_codagentearrecadador')->default(0);
            $table->string('si99_cnpjagentearrecadador', 14);
            $table->double('si99_vlsaldoinicial', 15, 2)->default(0);
            $table->double('si99_vlsaldofinal', 15, 2)->default(0);
            $table->bigInteger('si99_mes')->default(0);
            $table->bigInteger('si99_instit')->nullable();
            $table->primary('si99_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE ctb302025_si99_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ctb302025 ALTER COLUMN si99_sequencial SET DEFAULT nextval(\'ctb302025_si99_sequencial_seq\');');

        Schema::create('ctb402025', function (Blueprint $table) {
            $table->bigInteger('si101_sequencial')->default(0);
            $table->bigInteger('si101_tiporegistro')->default(0);
            $table->string('si101_codorgao', 2);
            $table->bigInteger('si101_codctb')->default(0);
            $table->string('si101_desccontabancaria', 50);
            $table->string('si101_nroconvenio', 30)->nullable();
            $table->date('si101_dataassinaturaconvenio')->nullable();
            $table->bigInteger('si101_mes')->default(0);
            $table->bigInteger('si101_instit')->nullable();
            $table->primary('si101_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE ctb402025_si101_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ctb402025 ALTER COLUMN si101_sequencial SET DEFAULT nextval(\'ctb402025_si101_sequencial_seq\');');

        Schema::create('ctb502025', function (Blueprint $table) {
            $table->bigInteger('si102_sequencial')->default(0);
            $table->bigInteger('si102_tiporegistro')->default(0);
            $table->bigInteger('si102_codctb')->default(0);
            $table->string('si102_codorgao', 2);
            $table->string('si102_banco', 3);
            $table->string('si102_agencia', 6);
            $table->string('si102_digitoverificadoragencia', 2)->nullable();
            $table->bigInteger('si102_contabancaria')->default(0);
            $table->string('si102_digitoverificadorcontabancaria', 2);
            $table->string('si102_tipoconta', 2);
            $table->bigInteger('si102_nroseqaplicacao')->nullable();
            $table->string('si102_desccontabancaria', 50);
            $table->bigInteger('si102_contaconvenio')->default(0);
            $table->string('si102_nroconvenio', 30)->nullable();
            $table->date('si102_dataassinaturaconvenio')->nullable();
            $table->bigInteger('si102_mes')->default(0);
            $table->bigInteger('si102_instit')->nullable();
            $table->primary('si102_sequencial');
        });
        DB::statement('
            CREATE SEQUENCE ctb502025_si102_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ctb502025 ALTER COLUMN si102_sequencial SET DEFAULT nextval(\'ctb502025_si102_sequencial_seq\');');

        Schema::create('cute102025', function (Blueprint $table) {
            $table->bigInteger('si199_sequencial')->default(0)->primary();
            $table->bigInteger('si199_tiporegistro')->default(0);
            $table->string('si199_tipoconta', 2);
            $table->bigInteger('si199_codctb')->default(0);
            $table->string('si199_codorgao', 2);
            $table->bigInteger('si199_banco')->default(0);
            $table->string('si199_agencia', 6);
            $table->string('si199_digitoverificadoragencia', 2)->nullable();
            $table->bigInteger('si199_contabancaria')->default(0);
            $table->string('si199_digitoverificadorcontabancaria', 2);
            $table->string('si199_desccontabancaria', 50);
            $table->bigInteger('si199_mes')->default(0);
            $table->bigInteger('si199_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE cute102025_si199_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cute102025 ALTER COLUMN si199_sequencial SET DEFAULT nextval(\'cute102025_si199_sequencial_seq\');');

        Schema::create('cute202025', function (Blueprint $table) {
            $table->bigInteger('si200_sequencial')->default(0)->primary();
            $table->bigInteger('si200_tiporegistro')->default(0);
            $table->string('si200_codorgao', 2);
            $table->bigInteger('si200_codctb')->default(0);
            $table->bigInteger('si200_codfontrecursos')->default(0);
            $table->double('si200_vlsaldoinicialfonte', 15, 2)->default(0);
            $table->double('si200_vlsaldofinalfonte', 15, 2)->default(0);
            $table->bigInteger('si200_mes')->default(0);
            $table->bigInteger('si200_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE cute202025_si200_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cute202025 ALTER COLUMN si200_sequencial SET DEFAULT nextval(\'cute202025_si200_sequencial_seq\');');

        Schema::create('cute302025', function (Blueprint $table) {
            $table->bigInteger('si202_sequencial')->default(0)->primary();
            $table->bigInteger('si202_tiporegistro')->default(0);
            $table->string('si202_codorgao', 2);
            $table->bigInteger('si202_codctb')->default(0);
            $table->string('si202_situacaoconta', 1);
            $table->date('si202_datasituacao');
            $table->bigInteger('si202_mes')->default(0);
            $table->bigInteger('si202_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE cute302025_si202_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cute302025 ALTER COLUMN si202_sequencial SET DEFAULT nextval(\'cute302025_si202_sequencial_seq\');');

        Schema::create('cvc102025', function (Blueprint $table) {
            $table->bigInteger('si146_sequencial')->default(0)->primary();
            $table->bigInteger('si146_tiporegistro')->default(0);
            $table->string('si146_codorgao', 2);
            $table->string('si146_codveiculo', 10);
            $table->string('si146_tpveiculo', 2);
            $table->string('si146_subtipoveiculo', 2);
            $table->string('si146_descveiculo', 100);
            $table->string('si146_marca', 50);
            $table->string('si146_modelo', 50);
            $table->bigInteger('si146_ano')->default(0);
            $table->string('si146_placa', 8)->nullable();
            $table->string('si146_chassi', 30)->nullable();
            $table->bigInteger('si146_numerorenavam')->nullable()->default(0);
            $table->string('si146_nroserie', 20)->nullable();
            $table->string('si146_situacao', 2);
            $table->bigInteger('si146_tipodocumento')->nullable()->default(0);
            $table->string('si146_nrodocumento', 14)->nullable();
            $table->string('si146_tpdeslocamento', 2);
            $table->bigInteger('si146_mes')->default(0);
            $table->bigInteger('si146_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE cvc102025_si146_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cvc102025 ALTER COLUMN si146_sequencial SET DEFAULT nextval(\'cvc102025_si146_sequencial_seq\');');

        Schema::create('cvc202025', function (Blueprint $table) {
            $table->bigInteger('si147_sequencial')->default(0)->primary();
            $table->bigInteger('si147_tiporegistro')->default(0);
            $table->string('si147_codorgao', 2);
            $table->string('si147_codveiculo', 10);
            $table->bigInteger('si147_origemgasto')->default(0);
            $table->string('si147_codunidadesubempenho', 8)->nullable();
            $table->bigInteger('si147_nroempenho')->nullable()->default(0);
            $table->date('si147_dtempenho')->nullable();
            $table->bigInteger('si147_marcacaoinicial')->default(0);
            $table->bigInteger('si147_marcacaofinal')->default(0);
            $table->string('si147_tipogasto', 2);
            $table->double('si147_qtdeutilizada', 15, 2)->default(0);
            $table->double('si147_vlgasto', 15, 2)->default(0);
            $table->string('si147_dscpecasservicos', 50)->nullable();
            $table->bigInteger('si147_mes')->default(0);
            $table->bigInteger('si147_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE cvc202025_si147_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cvc202025 ALTER COLUMN si147_sequencial SET DEFAULT nextval(\'cvc202025_si147_sequencial_seq\');');

        Schema::create('cvc302025', function (Blueprint $table) {
            $table->bigInteger('si148_sequencial')->default(0)->primary();
            $table->bigInteger('si148_tiporegistro')->default(0);
            $table->string('si148_codorgao', 2);
            $table->string('si148_codveiculo', 10);
            $table->string('si148_nomeestabelecimento', 250);
            $table->string('si148_localidade', 250);
            $table->bigInteger('si148_qtdediasrodados')->default(0);
            $table->double('si148_distanciaestabelecimento', 15, 2)->default(0);
            $table->bigInteger('si148_numeropassageiros')->default(0);
            $table->string('si148_turnos', 2);
            $table->bigInteger('si148_mes')->default(0);
            $table->bigInteger('si148_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE cvc302025_si148_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cvc302025 ALTER COLUMN si148_sequencial SET DEFAULT nextval(\'cvc302025_si148_sequencial_seq\');');

        Schema::create('cvc402025', function (Blueprint $table) {
            $table->bigInteger('si150_sequencial')->default(0)->primary();
            $table->bigInteger('si150_tiporegistro')->default(0);
            $table->string('si150_codorgao', 2);
            $table->string('si150_codveiculo', 10);
            $table->string('si150_placaatual', 7)->nullable();
            $table->bigInteger('si150_mes')->default(0);
            $table->bigInteger('si150_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE cvc402025_si150_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cvc402025 ALTER COLUMN si150_sequencial SET DEFAULT nextval(\'cvc402025_si150_sequencial_seq\');');

        Schema::create('cvc502025', function (Blueprint $table) {
            $table->bigInteger('si149_sequencial')->default(0)->primary();
            $table->bigInteger('si149_tiporegistro')->default(0);
            $table->string('si149_codorgao', 2);
            $table->string('si149_codveiculo', 10);
            $table->bigInteger('si149_situacaoveiculoequip')->nullable();
            $table->bigInteger('si149_tipobaixa')->default(0);
            $table->string('si149_descbaixa', 50)->nullable();
            $table->date('si149_dtbaixa');
            $table->bigInteger('si149_mes')->default(0);
            $table->bigInteger('si149_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE cvc502025_si149_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE cvc502025 ALTER COLUMN si149_sequencial SET DEFAULT nextval(\'cvc502025_si149_sequencial_seq\');');

        Schema::create('dclrf102025', function (Blueprint $table) {
            $table->bigInteger('si157_sequencial')->default(0)->primary();
            $table->bigInteger('si157_tiporegistro')->default(0);
            $table->string('si157_codorgao', 2);
            $table->double('si157_vlsaldoatualconcgarantiainterna', 15, 2)->default(0);
            $table->double('si157_vlsaldoatualconcgarantia', 15, 2)->default(0);
            $table->double('si157_vlsaldoatualcontragarantiainterna', 15, 2)->default(0);
            $table->double('si157_vlsaldoatualcontragarantiaexterna', 15, 2)->default(0);
            $table->string('si157_medidascorretivas', 4000)->nullable();
            $table->double('si157_recalieninvpermanente', 15, 2)->default(0);
            $table->double('si157_vldotinicialincentcontrib', 15, 2)->default(0);
            $table->double('si157_vldotatualizadaincentcontrib', 15, 2)->default(0);
            $table->double('si157_vlempenhadoicentcontrib', 15, 2)->default(0);
            $table->double('si157_vldotinicialincentinstfinanc', 15, 2)->default(0);
            $table->double('si157_vldotatualizadaincentinstfinanc', 15, 2)->default(0);
            $table->double('si157_vlempenhadoincentinstfinanc', 15, 2)->default(0);
            $table->double('si157_vlliqincentcontrib', 15, 2)->default(0);
            $table->double('si157_vlliqincentinstfinanc', 15, 2)->default(0);
            $table->double('si157_vlirpnpincentcontrib', 15, 2)->default(0);
            $table->double('si157_vlirpnpincentinstfinanc', 15, 2)->default(0);
            $table->double('si157_vlapropiacaodepositosjudiciais', 15, 2)->default(0);
            $table->double('si157_vlajustesrelativosrpps', 15, 2)->default(0);
            $table->double('si157_vloutrosajustes', 15, 2)->default(0);
            $table->bigInteger('si157_metarrecada')->default(0);
            $table->bigInteger('si157_mes')->default(0);
            $table->bigInteger('si157_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dclrf102025_si157_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dclrf102025 ALTER COLUMN si157_sequencial SET DEFAULT nextval(\'dclrf102025_si157_sequencial_seq\');');

        Schema::create('dclrf112025', function (Blueprint $table) {
            $table->bigInteger('si205_sequencial')->default(0)->primary();
            $table->bigInteger('si205_tiporegistro')->default(0);
            $table->bigInteger('si205_medidasadotadas')->default(0);
            $table->string('si205_dscmedidasadotadas', 4000)->nullable();
            $table->bigInteger('si205_reg10')->default(0);
            $table->bigInteger('si205_mes')->default(0);
            $table->bigInteger('si205_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dclrf112025_si205_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dclrf112025 ALTER COLUMN si205_sequencial SET DEFAULT nextval(\'dclrf112025_si205_sequencial_seq\');');

        Schema::create('dclrf202025', function (Blueprint $table) {
            $table->bigInteger('si191_sequencial')->default(0)->primary();
            $table->bigInteger('si191_tiporegistro')->default(0);
            $table->bigInteger('si191_contopcredito')->default(0);
            $table->string('si191_dsccontopcredito', 1000)->nullable();
            $table->bigInteger('si191_realizopcredito')->default(0);
            $table->bigInteger('si191_tiporealizopcreditocapta')->nullable()->default(0);
            $table->bigInteger('si191_tiporealizopcreditoreceb')->nullable()->default(0);
            $table->bigInteger('si191_tiporealizopcreditoassundir')->nullable()->default(0);
            $table->bigInteger('si191_tiporealizopcreditoassunobg')->nullable()->default(0);
            $table->bigInteger('si191_reg10')->default(0);
            $table->bigInteger('si191_mes')->default(0);
            $table->bigInteger('si191_instit')->default(0);
            $table->string('si191_dscnumeroinst', 3)->nullable();
        });
        DB::statement('
            CREATE SEQUENCE dclrf202025_si191_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dclrf202025 ALTER COLUMN si191_sequencial SET DEFAULT nextval(\'dclrf202025_si191_sequencial_seq\');');

        Schema::create('dclrf302025', function (Blueprint $table) {
            $table->bigInteger('si192_sequencial')->primary();
            $table->bigInteger('si192_tiporegistro');
            $table->bigInteger('si192_publiclrf');
            $table->date('si192_dtpublicacaorelatoriolrf')->nullable();
            $table->string('si192_localpublicacao', 1000)->nullable();
            $table->bigInteger('si192_tpbimestre')->nullable();
            $table->bigInteger('si192_exerciciotpbimestre')->nullable();
            $table->bigInteger('si192_reg10')->default(0);
            $table->bigInteger('si192_mes')->default(0);
            $table->bigInteger('si192_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dclrf302025_si192_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dclrf302025 ALTER COLUMN si192_sequencial SET DEFAULT nextval(\'dclrf302025_si192_sequencial_seq\');');

        Schema::create('dclrf402025', function (Blueprint $table) {
            $table->bigInteger('si193_sequencial')->primary();
            $table->bigInteger('si193_tiporegistro');
            $table->bigInteger('si193_publicrgf');
            $table->date('si193_dtpublicacaorgf')->nullable();
            $table->string('si193_localpublicacaorgf', 1000)->nullable();
            $table->bigInteger('si193_tpperiodo')->nullable();
            $table->bigInteger('si193_exerciciotpperiodo')->nullable();
            $table->bigInteger('si193_reg10')->default(0);
            $table->bigInteger('si193_mes')->default(0);
            $table->bigInteger('si193_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dclrf402025_si193_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dclrf402025 ALTER COLUMN si193_sequencial SET DEFAULT nextval(\'dclrf402025_si193_sequencial_seq\');');

        Schema::create('ddc102025', function (Blueprint $table) {
            $table->bigInteger('si153_sequencial')->default(0)->primary();
            $table->bigInteger('si153_tiporegistro')->default(0);
            $table->string('si153_codorgao', 2);
            $table->string('si153_nrocontratodivida', 30);
            $table->date('si153_dtassinatura');
            $table->string('si153_nroleiautorizacao', 6)->nullable();
            $table->date('si153_dtleiautorizacao')->nullable();
            $table->string('si153_objetocontratodivida', 1000);
            $table->string('si153_especificacaocontratodivida', 500);
            $table->bigInteger('si153_mes')->default(0);
            $table->bigInteger('si153_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE ddc102025_si153_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ddc102025 ALTER COLUMN si153_sequencial SET DEFAULT nextval(\'ddc102025_si153_sequencial_seq\');');

        Schema::create('ddc202025', function (Blueprint $table) {
            $table->bigInteger('si154_sequencial')->default(0)->primary();
            $table->bigInteger('si154_tiporegistro')->default(0);
            $table->string('si154_codorgao', 2);
            $table->string('si154_nrocontratodivida', 30)->default('0');
            $table->date('si154_dtassinatura');
            $table->string('si154_tipolancamento', 2);
            $table->string('si154_subtipo', 1)->nullable();
            $table->bigInteger('si154_tipodocumentocredor')->default(0);
            $table->string('si154_nrodocumentocredor', 14);
            $table->string('si154_justificativacancelamento', 500)->nullable();
            $table->double('si154_vlsaldoanterior', 15, 2)->default(0);
            $table->double('si154_vlcontratacao', 15, 2)->default(0);
            $table->double('si154_vlamortizacao', 15, 2)->default(0);
            $table->double('si154_vlcancelamento', 15, 2)->default(0);
            $table->double('si154_vlencampacao', 15, 2)->default(0);
            $table->double('si154_vlatualizacao', 15, 2)->default(0);
            $table->double('si154_vlsaldoatual', 15, 2)->default(0);
            $table->bigInteger('si154_mes')->default(0);
            $table->bigInteger('si154_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE ddc202025_si154_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ddc202025 ALTER COLUMN si154_sequencial SET DEFAULT nextval(\'ddc202025_si154_sequencial_seq\');');

        Schema::create('ddc302025', function (Blueprint $table) {
            $table->bigInteger('si178_sequencial')->default(0)->primary();
            $table->bigInteger('si178_tiporegistro')->default(0);
            $table->string('si178_codorgao', 2);
            $table->bigInteger('si178_passivoatuarial')->default(0);
            $table->double('si178_vlsaldoanterior', 15, 2)->default(0);
            $table->double('si178_vlsaldoatual', 15, 2)->nullable();
            $table->bigInteger('si178_mes')->default(0);
            $table->bigInteger('si178_instit')->nullable()->default(0);
        });
        DB::statement('
            CREATE SEQUENCE ddc302025_si178_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ddc302025 ALTER COLUMN si178_sequencial SET DEFAULT nextval(\'ddc302025_si178_sequencial_seq\');');

        Schema::create('dfcdcasp1002025', function (Blueprint $table) {
            $table->bigInteger('si228_sequencial')->default(0)->primary();
            $table->bigInteger('si228_tiporegistro')->default(0);
            $table->double('si228_vlgeracaoliquidaequivalentecaixa', 15, 2)->nullable()->default(0);
            $table->bigInteger('si228_anousu')->default(0);
            $table->bigInteger('si228_periodo')->default(0);
            $table->bigInteger('si228_mes')->default(0);
            $table->bigInteger('si228_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp1002025_si228_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp1002025 ALTER COLUMN si228_sequencial SET DEFAULT nextval(\'dfcdcasp1002025_si228_sequencial_seq\');');

        Schema::create('dfcdcasp102025', function (Blueprint $table) {
            $table->bigInteger('si219_sequencial')->default(0)->primary();
            $table->bigInteger('si219_tiporegistro')->default(0);
            $table->double('si219_vlreceitaderivadaoriginaria', 15, 2)->default(0);
            $table->double('si219_vltranscorrenterecebida', 15, 2)->nullable()->default(0);
            $table->double('si219_vloutrosingressosoperacionais', 15, 2)->nullable()->default(0);
            $table->double('si219_vltotalingressosativoperacionais', 15, 2)->nullable()->default(0);
            $table->bigInteger('si219_anousu')->default(0);
            $table->bigInteger('si219_periodo')->default(0);
            $table->bigInteger('si219_instit')->default(0);
            $table->double('si219_vlreceitatributaria', 15, 2)->default(0);
            $table->double('si219_vlreceitacontribuicao', 15, 2)->default(0);
            $table->double('si219_vlreceitapatrimonial', 15, 2)->default(0);
            $table->double('si219_vlreceitaagropecuaria', 15, 2)->default(0);
            $table->double('si219_vlreceitaindustrial', 15, 2)->default(0);
            $table->double('si219_vlreceitaservicos', 15, 2)->default(0);
            $table->double('si219_vlremuneracaodisponibilidade', 15, 2)->default(0);
            $table->double('si219_vloutrasreceitas', 15, 2)->default(0);
            $table->double('si219_vltransferenciarecebidas', 15, 2)->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp102025_si219_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp102025 ALTER COLUMN si219_sequencial SET DEFAULT nextval(\'dfcdcasp102025_si219_sequencial_seq\');');

        Schema::create('dfcdcasp1102025', function (Blueprint $table) {
            $table->bigInteger('si229_sequencial')->default(0)->primary();
            $table->bigInteger('si229_tiporegistro')->default(0);
            $table->double('si229_vlcaixaequivalentecaixainicial', 15, 2)->default(0);
            $table->double('si229_vlcaixaequivalentecaixafinal', 15, 2)->nullable()->default(0);
            $table->bigInteger('si229_anousu')->default(0);
            $table->bigInteger('si229_periodo')->default(0);
            $table->bigInteger('si229_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp1102025_si229_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp1102025 ALTER COLUMN si229_sequencial SET DEFAULT nextval(\'dfcdcasp1102025_si229_sequencial_seq\');');

        Schema::create('dfcdcasp202025', function (Blueprint $table) {
            $table->bigInteger('si220_sequencial')->default(0)->primary();
            $table->bigInteger('si220_tiporegistro')->default(0);
            $table->double('si220_vldesembolsopessoaldespesas', 15, 2)->default(0);
            $table->double('si220_vldesembolsojurosencargdivida', 15, 2)->default(0);
            $table->double('si220_vldesembolsotransfconcedidas', 15, 2)->default(0);
            $table->double('si220_vloutrosdesembolsos', 15, 2)->default(0);
            $table->double('si220_vltotaldesembolsosativoperacionais', 15, 2)->nullable()->default(0);
            $table->bigInteger('si220_anousu')->default(0);
            $table->bigInteger('si220_periodo')->default(0);
            $table->bigInteger('si220_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp202025_si220_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp202025 ALTER COLUMN si220_sequencial SET DEFAULT nextval(\'dfcdcasp202025_si220_sequencial_seq\');');

        Schema::create('dfcdcasp302025', function (Blueprint $table) {
            $table->bigInteger('si221_sequencial')->default(0)->primary();
            $table->bigInteger('si221_tiporegistro')->default(0);
            $table->double('si221_vlfluxocaixaliquidooperacional', 15, 2)->nullable()->default(0);
            $table->bigInteger('si221_anousu')->default(0);
            $table->bigInteger('si221_periodo')->default(0);
            $table->bigInteger('si221_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp302025_si221_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp302025 ALTER COLUMN si221_sequencial SET DEFAULT nextval(\'dfcdcasp302025_si221_sequencial_seq\');');

        Schema::create('dfcdcasp402025', function (Blueprint $table) {
            $table->bigInteger('si222_sequencial')->default(0)->primary();
            $table->bigInteger('si222_tiporegistro')->default(0);
            $table->double('si222_vlalienacaobens', 15, 2)->default(0);
            $table->double('si222_vlamortizacaoemprestimoconcedido', 15, 2)->default(0);
            $table->double('si222_vloutrosingressos', 15, 2)->default(0);
            $table->double('si222_vltotalingressosatividainvestiment', 15, 2)->nullable()->default(0);
            $table->bigInteger('si222_anousu')->default(0);
            $table->bigInteger('si222_periodo')->default(0);
            $table->bigInteger('si222_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp402025_si222_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp402025 ALTER COLUMN si222_sequencial SET DEFAULT nextval(\'dfcdcasp402025_si222_sequencial_seq\');');

        Schema::create('dfcdcasp502025', function (Blueprint $table) {
            $table->bigInteger('si223_sequencial')->default(0)->primary();
            $table->bigInteger('si223_tiporegistro')->default(0);
            $table->double('si223_vlreceitafiscal', 15, 2)->nullable()->default(0);
            $table->double('si223_vlreceitaoutros', 15, 2)->nullable()->default(0);
            $table->double('si223_vltotalreceita', 15, 2)->nullable()->default(0);
            $table->bigInteger('si223_anousu')->default(0);
            $table->bigInteger('si223_periodo')->default(0);
            $table->bigInteger('si223_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp502025_si223_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp502025 ALTER COLUMN si223_sequencial SET DEFAULT nextval(\'dfcdcasp502025_si223_sequencial_seq\');');

        Schema::create('dfcdcasp602025', function (Blueprint $table) {
            $table->bigInteger('si224_sequencial')->default(0)->primary();
            $table->bigInteger('si224_tiporegistro')->default(0);
            $table->double('si224_vlfluxocaixaliquidoinvestimento', 15, 2)->nullable()->default(0);
            $table->bigInteger('si224_anousu')->default(0);
            $table->bigInteger('si224_periodo')->default(0);
            $table->bigInteger('si224_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp602025_si224_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp602025 ALTER COLUMN si224_sequencial SET DEFAULT nextval(\'dfcdcasp602025_si224_sequencial_seq\');');

        Schema::create('dfcdcasp702025', function (Blueprint $table) {
            $table->bigInteger('si225_sequencial')->default(0)->primary();
            $table->bigInteger('si225_tiporegistro')->default(0);
            $table->double('si225_vloperacoescredito', 15, 2)->nullable()->default(0);
            $table->double('si225_vlintegralizacaodependentes', 15, 2)->nullable()->default(0);
            $table->double('si225_vltranscapitalrecebida', 15, 2)->nullable()->default(0);
            $table->double('si225_vloutrosingressosfinanciamento', 15, 2)->nullable()->default(0);
            $table->double('si225_vltotalingressoatividafinanciament', 15, 2)->nullable()->default(0);
            $table->bigInteger('si225_anousu')->default(0);
            $table->bigInteger('si225_periodo')->default(0);
            $table->bigInteger('si225_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp702025_si225_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp702025 ALTER COLUMN si225_sequencial SET DEFAULT nextval(\'dfcdcasp702025_si225_sequencial_seq\');');

        Schema::create('dfcdcasp802025', function (Blueprint $table) {
            $table->bigInteger('si226_sequencial')->default(0)->primary();
            $table->bigInteger('si226_tiporegistro')->default(0);
            $table->double('si226_vlamortizacaorefinanciamento', 15, 2)->nullable()->default(0);
            $table->double('si226_vloutrosdesembolsosfinanciamento', 15, 2)->nullable()->default(0);
            $table->double('si226_vltotaldesembolsoatividafinanciame', 15, 2)->nullable()->default(0);
            $table->bigInteger('si226_anousu')->default(0);
            $table->bigInteger('si226_periodo')->default(0);
            $table->bigInteger('si226_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp802025_si226_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp802025 ALTER COLUMN si226_sequencial SET DEFAULT nextval(\'dfcdcasp802025_si226_sequencial_seq\');');

        Schema::create('dfcdcasp902025', function (Blueprint $table) {
            $table->bigInteger('si227_sequencial')->default(0)->primary();
            $table->bigInteger('si227_tiporegistro')->default(0);
            $table->double('si227_vlfluxocaixafinanciamento', 15, 2)->nullable()->default(0);
            $table->bigInteger('si227_anousu')->default(0);
            $table->bigInteger('si227_periodo')->default(0);
            $table->bigInteger('si227_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dfcdcasp902025_si227_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dfcdcasp902025 ALTER COLUMN si227_sequencial SET DEFAULT nextval(\'dfcdcasp902025_si227_sequencial_seq\');');

        Schema::create('dipr102025', function (Blueprint $table) {
            $table->bigInteger('si230_sequencial')->default(0)->primary();
            $table->bigInteger('si230_tiporegistro')->default(0);
            $table->bigInteger('si230_tipocadastro')->default(0);
            $table->bigInteger('si230_segregacaomassa')->default(0);
            $table->bigInteger('si230_benefcustesouro')->default(0);
            $table->bigInteger('si230_atonormativo')->default(0);
            $table->bigInteger('si230_mes')->default(0);
            $table->bigInteger('si230_instit')->default(0);
            $table->bigInteger('si230_nroatonormasegremassa')->nullable();
            $table->date('si230_dtatonormasegremassa')->nullable();
            $table->bigInteger('si230_planodefatuarial')->nullable();
            $table->bigInteger('si230_atonormplanodefat')->nullable();
            $table->date('si230_dtatoplanodefat')->nullable();
            $table->date('si230_dtatonormativo')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE dipr102025_si230_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dipr102025 ALTER COLUMN si230_sequencial SET DEFAULT nextval(\'dipr102025_si230_sequencial_seq\');');

        Schema::create('dipr202025', function (Blueprint $table) {
            $table->bigInteger('si231_sequencial')->default(0)->primary();
            $table->bigInteger('si231_tiporegistro')->default(0);
            $table->string('si231_codorgao', 2);
            $table->bigInteger('si231_tipobasecalculo')->default(0);
            $table->bigInteger('si231_mescompetencia')->default(0);
            $table->bigInteger('si231_exerciciocompetencia')->default(0);
            $table->bigInteger('si231_tipofundo')->default(0);
            $table->double('si231_remuneracaobrutafolhapag', 15, 2)->default(0);
            $table->bigInteger('si231_tipobasecalculocontrprevidencia')->default(0);
            $table->bigInteger('si231_tipobasecalculocontrseg')->default(0);
            $table->double('si231_valorbasecalculocontr', 15, 2)->default(0);
            $table->bigInteger('si231_tipocontribuicao')->default(0);
            $table->double('si231_aliquota', 15, 2)->default(0);
            $table->double('si231_valorcontribdevida', 15, 2)->default(0);
            $table->bigInteger('si231_mes')->default(0);
            $table->bigInteger('si231_instit')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dipr202025_si231_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dipr202025 ALTER COLUMN si231_sequencial SET DEFAULT nextval(\'dipr202025_si231_sequencial_seq\');');

        Schema::create('dipr302025', function (Blueprint $table) {
            $table->bigInteger('si232_sequencial')->default(0)->primary();
            $table->bigInteger('si232_tiporegistro')->default(0);
            $table->string('si232_codorgao', 2);
            $table->bigInteger('si232_mescompetencia')->default(0);
            $table->bigInteger('si232_exerciciocompetencia')->default(0);
            $table->bigInteger('si232_tipofundo')->default(0);
            $table->bigInteger('si232_tiporepasse')->default(0);
            $table->bigInteger('si232_tipocontripatronal')->default(0);
            $table->bigInteger('si232_tipocontrisegurado')->default(0);
            $table->bigInteger('si232_tipocontribuicao')->default(0);
            $table->date('si232_datarepasse');
            $table->date('si232_datavencirepasse');
            $table->double('si232_valororiginal', 15, 2)->default(0);
            $table->double('si232_valororiginalrepassado', 15, 2)->default(0);
            $table->bigInteger('si232_mes')->default(0);
            $table->bigInteger('si232_instit')->default(0);
            $table->decimal('si232_valorjuros', 15, 2)->nullable();
            $table->decimal('si232_valormulta', 15, 2)->nullable();
            $table->decimal('si232_valoratualizacaomonetaria', 15, 2)->nullable();
            $table->decimal('si232_valortotaldeducoes', 15, 2)->nullable();
        });
        DB::statement('
            CREATE SEQUENCE dipr302025_si232_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dipr302025 ALTER COLUMN si232_sequencial SET DEFAULT nextval(\'dipr302025_si232_sequencial_seq\');');

        Schema::create('dipr402025', function (Blueprint $table) {
            $table->bigInteger('si233_sequencial')->default(0)->primary();
            $table->bigInteger('si233_tiporegistro')->default(0);
            $table->string('si233_codorgao', 2);
            $table->bigInteger('si233_mescompetencia')->default(0);
            $table->bigInteger('si233_exerciciocompetencia')->default(0);
            $table->bigInteger('si233_tipofundo')->default(0);
            $table->bigInteger('si233_tiporepasse')->default(0);
            $table->bigInteger('si233_tipocontripatronal')->default(0);
            $table->bigInteger('si233_tipocontrisegurado')->default(0);
            $table->bigInteger('si233_tipocontribuicao')->default(0);
            $table->bigInteger('si233_tipodeducao')->default(0);
            $table->text('si233_dsctiposdeducoes');
            $table->double('si233_valordeducao', 15, 2)->default(0);
            $table->bigInteger('si233_mes')->default(0);
            $table->bigInteger('si233_instit')->default(0);
            $table->date('si233_datarepasse')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE dipr402025_si233_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dipr402025 ALTER COLUMN si233_sequencial SET DEFAULT nextval(\'dipr402025_si233_sequencial_seq\');');

        Schema::create('dipr502025', function (Blueprint $table) {
            $table->bigInteger('si234_sequencial')->default(0)->primary();
            $table->bigInteger('si234_tiporegistro')->default(0);
            $table->string('si234_codorgao', 2);
            $table->bigInteger('si234_mescompetencia')->default(0);
            $table->bigInteger('si234_exerciciocompetencia')->default(0);
            $table->bigInteger('si234_tipofundo')->default(0);
            $table->bigInteger('si234_tipoaportetransf')->default(0);
            $table->text('si234_dscoutrosaportestransf');
            $table->double('si234_valoraportetransf', 15, 2)->default(0);
            $table->bigInteger('si234_mes')->default(0);
            $table->bigInteger('si234_instit')->default(0);
            $table->date('si234_datarepasse')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE dipr502025_si234_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dipr502025 ALTER COLUMN si234_sequencial SET DEFAULT nextval(\'dipr502025_si234_sequencial_seq\');');

        Schema::create('dispensa102025', function (Blueprint $table) {
            $table->bigInteger('si74_sequencial')->default(0)->primary();
            $table->bigInteger('si74_tiporegistro');
            $table->string('si74_codorgaoresp', 2);
            $table->string('si74_codunidadesubresp', 8);
            $table->bigInteger('si74_exercicioprocesso');
            $table->string('si74_nroprocesso', 12);
            $table->bigInteger('si74_tipoprocesso');
            $table->date('si74_dtabertura');
            $table->bigInteger('si74_naturezaobjeto');
            $table->string('si74_objeto', 1000);
            $table->string('si74_justificativa', 250);
            $table->string('si74_razao', 250);
            $table->date('si74_dtpublicacaotermoratificacao');
            $table->string('si74_veiculopublicacao', 50);
            $table->bigInteger('si74_processoporlote');
            $table->bigInteger('si74_mes')->nullable();
            $table->bigInteger('si74_instit')->nullable();
            $table->bigInteger('si74_tipocadastro')->nullable();
            $table->bigInteger('si74_leidalicitacao')->nullable();
            $table->string('si74_codunidadesubedital', 8)->nullable();
            $table->bigInteger('si74_tipocriterio')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE dispensa102025_si74_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dispensa102025 ALTER COLUMN si74_sequencial SET DEFAULT nextval(\'dispensa102025_si74_sequencial_seq\');');

        Schema::create('dispensa182025', function (Blueprint $table) {
            $table->bigInteger('si82_sequencial')->default(0)->primary();
            $table->bigInteger('si82_tiporegistro')->default(0);
            $table->string('si82_codorgaoresp', 2);
            $table->string('si82_codunidadesubresp', 8);
            $table->bigInteger('si82_exercicioprocesso')->default(0);
            $table->string('si82_nroprocesso', 12);
            $table->bigInteger('si82_tipoprocesso')->default(0);
            $table->bigInteger('si82_tipodocumento')->default(0);
            $table->string('si82_nrodocumento', 14);
            $table->date('si82_datacredenciamento');
            $table->bigInteger('si82_nrolote')->nullable();
            $table->bigInteger('si82_coditem')->default(0);
            $table->string('si82_nroinscricaoestadual', 30)->nullable();
            $table->string('si82_ufinscricaoestadual', 2)->nullable();
            $table->string('si82_nrocertidaoregularidadeinss', 30)->nullable();
            $table->date('si82_dataemissaocertidaoregularidadeinss')->nullable();
            $table->date('si82_dtvalidadecertidaoregularidadeinss')->nullable();
            $table->string('si82_nrocertidaoregularidadefgts', 30)->nullable();
            $table->date('si82_dtemissaocertidaoregularidadefgts')->nullable();
            $table->date('si82_dtvalidadecertidaoregularidadefgts')->nullable();
            $table->string('si82_nrocndt', 30)->nullable();
            $table->date('si82_dtemissaocndt')->nullable();
            $table->date('si82_dtvalidadecndt')->nullable();
            $table->bigInteger('si82_mes')->default(0);
            $table->bigInteger('si82_reg10')->default(0);
            $table->bigInteger('si82_instit')->nullable();
        });

        DB::statement('
            CREATE SEQUENCE dispensa182025_si82_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dispensa182025 ALTER COLUMN si82_sequencial SET DEFAULT nextval(\'dispensa182025_si82_sequencial_seq\');');
        DB::statement('CREATE INDEX dispensa182025_si82_reg10_index ON dispensa182025 USING btree (si82_reg10);');


        Schema::create('dispensa302025', function (Blueprint $table) {
            $table->bigInteger('si203_sequencial')->default(0)->primary();
            $table->bigInteger('si203_tiporegistro');
            $table->bigInteger('si203_codorgaoresp');
            $table->string('si203_codunidadesubresp', 8);
            $table->bigInteger('si203_exercicioprocesso');
            $table->string('si203_nroprocesso', 16);
            $table->bigInteger('si203_tipoprocesso');
            $table->bigInteger('si203_tipodocumento');
            $table->string('si203_nrodocumento', 14);
            $table->bigInteger('si203_nrolote')->nullable();
            $table->bigInteger('si203_coditem')->nullable();
            $table->bigInteger('si203_percdesconto')->default(0);
            $table->bigInteger('si203_mes')->default(0);
            $table->bigInteger('si203_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE dispensa302025_si203_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dispensa302025 ALTER COLUMN si203_sequencial SET DEFAULT nextval(\'dispensa302025_si203_sequencial_seq\');');

        Schema::create('dispensa402025', function (Blueprint $table) {
            $table->bigInteger('si204_sequencial')->default(0)->primary();
            $table->bigInteger('si204_tiporegistro');
            $table->bigInteger('si204_codorgaoresp');
            $table->string('si204_codunidadesubresp', 8);
            $table->bigInteger('si204_exercicioprocesso');
            $table->string('si204_nroprocesso', 16);
            $table->bigInteger('si204_tipoprocesso');
            $table->bigInteger('si204_tipodocumento');
            $table->string('si204_nrodocumento', 14);
            $table->bigInteger('si204_nrolote')->nullable();
            $table->bigInteger('si204_coditem')->nullable();
            $table->bigInteger('si204_perctaxaadm')->default(0);
            $table->bigInteger('si204_mes')->default(0);
            $table->bigInteger('si204_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE dispensa402025_si204_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dispensa402025 ALTER COLUMN si204_sequencial SET DEFAULT nextval(\'dispensa402025_si204_sequencial_seq\');');

        Schema::create('dvpdcasp102025', function (Blueprint $table) {
            $table->bigInteger('si216_sequencial')->default(0)->primary();
            $table->bigInteger('si216_tiporegistro');
            $table->float('si216_vlimpostos', 8, 2)->default(0);
            $table->float('si216_vlcontribuicoes', 8, 2)->default(0);
            $table->float('si216_vlexploracovendasdireitos', 8, 2)->default(0);
            $table->float('si216_vlvariacoesaumentativasfinanceiras', 8, 2)->default(0);
            $table->float('si216_vltransfdelegacoesrecebidas', 8, 2)->default(0);
            $table->float('si216_vlvalorizacaoativodesincorpassivo', 8, 2)->default(0);
            $table->float('si216_vloutrasvariacoespatriaumentativas', 8, 2)->default(0);
            $table->float('si216_vltotalvpaumentativas', 8, 2)->nullable();
            $table->integer('si216_ano')->default(0);
            $table->integer('si216_periodo')->default(0);
            $table->integer('si216_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dvpdcasp102025_si216_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dvpdcasp102025 ALTER COLUMN si216_sequencial SET DEFAULT nextval(\'dvpdcasp102025_si216_sequencial_seq\');');

        Schema::create('dvpdcasp202025', function (Blueprint $table) {
            $table->bigInteger('si217_sequencial')->default(0)->primary();
            $table->bigInteger('si217_tiporegistro');
            $table->float('si217_vldiminutivapessoaencargos', 8, 2)->default(0);
            $table->float('si217_vlprevassistenciais', 8, 2)->default(0);
            $table->float('si217_vlservicoscapitalfixo', 8, 2)->default(0);
            $table->float('si217_vldiminutivavariacoesfinanceiras', 8, 2)->default(0);
            $table->float('si217_vltransfconcedidas', 8, 2)->default(0);
            $table->float('si217_vldesvaloativoincorpopassivo', 8, 2)->default(0);
            $table->float('si217_vltributarias', 8, 2)->default(0);
            $table->float('si217_vlmercadoriavendidoservicos', 8, 2)->default(0);
            $table->float('si217_vloutrasvariacoespatridiminutivas', 8, 2)->default(0);
            $table->float('si217_vltotalvpdiminutivas', 8, 2)->nullable();
            $table->integer('si217_ano')->default(0);
            $table->integer('si217_periodo')->default(0);
            $table->integer('si217_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dvpdcasp202025_si217_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dvpdcasp202025 ALTER COLUMN si217_sequencial SET DEFAULT nextval(\'dvpdcasp202025_si217_sequencial_seq\');');

        Schema::create('dvpdcasp302025', function (Blueprint $table) {
            $table->bigInteger('si218_sequencial')->default(0)->primary();
            $table->bigInteger('si218_tiporegistro');
            $table->float('si218_vlresultadopatrimonialperiodo', 8, 2)->nullable();
            $table->integer('si218_ano')->default(0);
            $table->integer('si218_periodo')->default(0);
            $table->integer('si218_institu')->default(0);
        });
        DB::statement('
            CREATE SEQUENCE dvpdcasp302025_si218_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE dvpdcasp302025 ALTER COLUMN si218_sequencial SET DEFAULT nextval(\'dvpdcasp302025_si218_sequencial_seq\');');

        Schema::create('emp102025', function (Blueprint $table) {
            $table->bigInteger('si106_sequencial')->default(0)->primary();
            $table->bigInteger('si106_tiporegistro');
            $table->string('si106_codorgao', 2);
            $table->string('si106_codunidadesub', 8);
            $table->string('si106_codfuncao', 2);
            $table->string('si106_codsubfuncao', 3);
            $table->string('si106_codprograma', 4);
            $table->string('si106_idacao', 4);
            $table->string('si106_idsubacao', 4)->nullable();
            $table->bigInteger('si106_naturezadespesa')->default(0);
            $table->string('si106_subelemento', 2);
            $table->bigInteger('si106_nroempenho')->default(0);
            $table->date('si106_dtempenho');
            $table->bigInteger('si106_modalidadeempenho')->default(0);
            $table->string('si106_tpempenho', 2);
            $table->float('si106_vlbruto', 8, 2)->default(0);
            $table->string('si106_especificacaoempenho', 500);
            $table->bigInteger('si106_despdeccontrato')->default(0);
            $table->string('si106_codorgaorespcontrato', 2)->nullable();
            $table->string('si106_codunidadesubrespcontrato', 8)->nullable();
            $table->string('si106_nrocontrato', 14)->nullable();
            $table->string('si106_nrosequencialtermoaditivo', 2)->nullable();
            $table->bigInteger('si106_despdecconvenio')->default(0);
            $table->string('si106_nroconvenio', 30)->nullable();
            $table->date('si106_dataassinaturaconvenio')->nullable();
            $table->bigInteger('si106_despdecconvenioconge')->default(0);
            $table->string('si106_nroconvenioconge', 30)->nullable();
            $table->date('si106_dataassinaturaconvenioconge')->nullable();
            $table->bigInteger('si106_despdeclicitacao')->default(0);
            $table->string('si106_numdocumentoorgao', 14)->nullable();
            $table->string('si106_codunidadesubresplicit', 8)->nullable();
            $table->string('si106_nroprocessolicitatorio', 12)->nullable();
            $table->bigInteger('si106_exercicioprocessolicitatorio')->default(0)->nullable();
            $table->bigInteger('si106_tipoprocesso')->default(0)->nullable();
            $table->string('si106_cpfordenador', 11);
            $table->bigInteger('si106_mes')->default(0);
            $table->bigInteger('si106_instit')->nullable();
            $table->integer('si106_exerciciocontrato')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE emp102025_si106_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE emp102025 ALTER COLUMN si106_sequencial SET DEFAULT nextval(\'emp102025_si106_sequencial_seq\');');

        Schema::create('emp112025', function (Blueprint $table) {
            $table->bigInteger('si107_sequencial')->default(0)->primary();
            $table->bigInteger('si107_tiporegistro');
            $table->string('si107_codunidadesub', 8);
            $table->bigInteger('si107_nroempenho')->default(0);
            $table->bigInteger('si107_codfontrecursos')->default(0);
            $table->float('si107_valorfonte', 8, 2)->default(0);
            $table->bigInteger('si107_mes')->default(0);
            $table->bigInteger('si107_reg10')->default(0);
            $table->bigInteger('si107_instit')->nullable();
            $table->string('si107_codco', 4)->nullable();
        });
        DB::statement('
            CREATE SEQUENCE emp112025_si107_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE emp112025 ALTER COLUMN si107_sequencial SET DEFAULT nextval(\'emp112025_si107_sequencial_seq\');');
        DB::statement('CREATE INDEX emp112025_si107_reg10_index ON emp112025 USING btree (si107_reg10);');

        Schema::create('emp122025', function (Blueprint $table) {
            $table->bigInteger('si108_sequencial')->default(0)->primary();
            $table->bigInteger('si108_tiporegistro');
            $table->string('si108_codunidadesub', 8);
            $table->bigInteger('si108_nroempenho')->default(0);
            $table->bigInteger('si108_tipodocumento')->default(0);
            $table->string('si108_nrodocumento', 14);
            $table->bigInteger('si108_mes')->default(0);
            $table->bigInteger('si108_reg10')->default(0);
            $table->bigInteger('si108_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE emp122025_si108_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE emp122025 ALTER COLUMN si108_sequencial SET DEFAULT nextval(\'emp122025_si108_sequencial_seq\');');
        DB::statement('CREATE INDEX emp122025_si108_reg10_index ON emp122025 USING btree (si108_reg10);');

        Schema::create('emp202025', function (Blueprint $table) {
            $table->bigInteger('si109_sequencial')->default(0)->primary();
            $table->bigInteger('si109_tiporegistro');
            $table->string('si109_codorgao', 2);
            $table->string('si109_codunidadesub', 8);
            $table->bigInteger('si109_nroempenho')->default(0);
            $table->date('si109_dtempenho');
            $table->bigInteger('si109_nroreforco')->default(0);
            $table->date('si109_dtreforco');
            $table->bigInteger('si109_codfontrecursos')->default(0);
            $table->float('si109_vlreforco', 8, 2)->default(0);
            $table->bigInteger('si109_mes')->default(0);
            $table->bigInteger('si109_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE emp202025_si109_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE emp202025 ALTER COLUMN si109_sequencial SET DEFAULT nextval(\'emp202025_si109_sequencial_seq\');');

        Schema::create('emp302025', function (Blueprint $table) {
            $table->bigInteger('si206_sequencial')->default(0)->primary();
            $table->bigInteger('si206_tiporegistro');
            $table->string('si206_codorgao', 2);
            $table->string('si206_codunidadesub', 8);
            $table->bigInteger('si206_nroempenho')->default(0);
            $table->date('si206_dtempenho');
            $table->string('si206_codorgaorespcontrato', 2)->nullable();
            $table->string('si206_codunidadesubrespcontrato', 8)->nullable();
            $table->bigInteger('si206_nrocontrato')->nullable();
            $table->bigInteger('si206_nrosequencialtermoaditivo')->nullable();
            $table->string('si206_nroconvenio', 30)->nullable();
            $table->date('si206_dtassinaturaconvenio')->nullable();
            $table->string('si206_nroconvenioconge', 30)->nullable();
            $table->date('si206_dtassinaturaconge')->nullable();
            $table->bigInteger('si206_mes')->default(0);
            $table->bigInteger('si206_instit')->nullable();
            $table->bigInteger('si206_exerciciocontrato')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE emp302025_si206_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE emp302025 ALTER COLUMN si206_sequencial SET DEFAULT nextval(\'emp302025_si206_sequencial_seq\');');

        Schema::create('exeobras102025', function (Blueprint $table) {
            $table->bigInteger('si197_sequencial')->nullable();
            $table->bigInteger('si197_tiporegistro')->nullable();
            $table->string('si197_codorgao', 3)->nullable();
            $table->string('si197_codunidadesub', 8)->nullable();
            $table->bigInteger('si197_nrocontrato')->nullable();
            $table->bigInteger('si197_tipodocumento')->nullable();
            $table->string('si197_numerodocumento', 14)->nullable();
            $table->bigInteger('si197_exerciciocontrato')->nullable();
            $table->bigInteger('si197_contdeclicitacao')->nullable();
            $table->bigInteger('si197_exerciciolicitacao')->nullable();
            $table->bigInteger('si197_nroprocessolicitatorio')->nullable();
            $table->bigInteger('si197_codunidadesubresp')->nullable();
            $table->bigInteger('si197_nrolote')->nullable();
            $table->bigInteger('si197_codobra')->nullable();
            $table->text('si197_objeto')->nullable();
            $table->text('si197_linkobra')->nullable();
            $table->bigInteger('si197_mes')->nullable();
            $table->bigInteger('si197_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE exeobras102025_si197_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE exeobras102025 ALTER COLUMN si197_sequencial SET DEFAULT nextval(\'exeobras102025_si197_sequencial_seq\');');

        Schema::create('exeobras202025', function (Blueprint $table) {
            $table->bigInteger('si204_sequencial')->nullable();
            $table->bigInteger('si204_tiporegistro')->nullable();
            $table->string('si204_codorgao', 3)->nullable();
            $table->string('si204_codunidadesub', 8)->nullable();
            $table->bigInteger('si204_nrocontrato')->nullable();
            $table->bigInteger('si204_exerciciocontrato')->nullable();
            $table->bigInteger('si204_contdeclicitacao')->nullable();
            $table->bigInteger('si204_exercicioprocesso')->nullable();
            $table->string('si204_nroprocesso', 12)->nullable();
            $table->string('si204_codunidadesubresp', 8)->nullable();
            $table->bigInteger('si204_tipoprocesso')->nullable();
            $table->bigInteger('si204_codobra')->nullable();
            $table->text('si204_objeto')->nullable();
            $table->text('si204_linkobra')->nullable();
            $table->bigInteger('si204_mes')->nullable();
            $table->bigInteger('si204_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE exeobras202025_si204_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE exeobras202025 ALTER COLUMN si204_sequencial SET DEFAULT nextval(\'exeobras202025_si204_sequencial_seq\');');

        Schema::create('ext102025', function (Blueprint $table) {
            $table->bigInteger('si124_sequencial')->default(0)->primary();
            $table->bigInteger('si124_tiporegistro');
            $table->bigInteger('si124_codext')->default(0);
            $table->string('si124_codorgao', 2);
            $table->string('si124_tipolancamento', 2);
            $table->string('si124_subtipo', 4);
            $table->string('si124_desdobrasubtipo', 4)->nullable();
            $table->string('si124_descextraorc', 50);
            $table->bigInteger('si124_mes')->default(0);
            $table->bigInteger('si124_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE ext102025_si124_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ext102025 ALTER COLUMN si124_sequencial SET DEFAULT nextval(\'ext102025_si124_sequencial_seq\');');

        Schema::create('ext202025', function (Blueprint $table) {
            $table->bigInteger('si165_sequencial')->default(0)->primary();
            $table->bigInteger('si165_tiporegistro');
            $table->string('si165_codorgao', 2);
            $table->bigInteger('si165_codext')->default(0);
            $table->bigInteger('si165_codfontrecursos')->default(0);
            $table->float('si165_vlsaldoanteriorfonte', 8, 2)->default(0);
            $table->float('si165_vlreclassificacaoextratordalancamento', 8, 2)->default(0);
            $table->float('si165_vlnovovalor', 8, 2)->default(0);
            $table->bigInteger('si165_mes')->default(0);
            $table->bigInteger('si165_instit')->nullable();
        });
        DB::statement('
            CREATE SEQUENCE ext202025_si165_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE ext202025 ALTER COLUMN si165_sequencial SET DEFAULT nextval(\'ext202025_si165_sequencial_seq\');');

        Schema::create('ext302025', function (Blueprint $table) {
            $table->bigInteger('si126_sequencial')->default(0)->primary();
            $table->bigInteger('si126_tiporegistro')->default(0);
            $table->bigInteger('si126_codext')->default(0);
            $table->bigInteger('si126_codfontrecursos')->default(0);
            $table->bigInteger('si126_codreduzidoop')->default(0);
            $table->bigInteger('si126_nroop')->default(0);
            $table->string('si126_codunidadesub', 8);
            $table->date('si126_dtpagamento');
            $table->bigInteger('si126_tipodocumentocredor')->nullable()->default(0);
            $table->string('si126_nrodocumentocredor', 14)->nullable();
            $table->float('si126_vlop')->default(0);
            $table->string('si126_especificacaoop', 500);
            $table->string('si126_cpfresppgto', 11);
            $table->bigInteger('si126_mes')->default(0);
            $table->bigInteger('si126_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE ext302025_si126_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ext302025 ALTER COLUMN si126_sequencial SET DEFAULT nextval(\'ext302025_si126_sequencial_seq\');');

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
        DB::statement('CREATE SEQUENCE flpgo102025_si195_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE flpgo102025 ALTER COLUMN si195_sequencial SET DEFAULT nextval(\'flpgo102025_si195_sequencial_seq\');');

        Schema::create('hablic102025', function (Blueprint $table) {
            $table->bigInteger('si57_sequencial')->default(0)->primary();
            $table->bigInteger('si57_tiporegistro')->default(0);
            $table->string('si57_codorgao', 2);
            $table->string('si57_codunidadesub', 8);
            $table->bigInteger('si57_exerciciolicitacao')->default(0);
            $table->string('si57_nroprocessolicitatorio', 12);
            $table->bigInteger('si57_tipodocumento')->default(0);
            $table->string('si57_nrodocumento', 14);
            $table->text('si57_objetosocial')->nullable();
            $table->bigInteger('si57_orgaorespregistro')->nullable()->default(0);
            $table->date('si57_dataregistro')->nullable();
            $table->string('si57_nroregistro', 20)->nullable();
            $table->date('si57_dataregistrocvm')->nullable();
            $table->string('si57_nroregistrocvm', 20)->nullable();
            $table->string('si57_nroinscricaoestadual', 30)->nullable();
            $table->string('si57_ufinscricaoestadual', 2)->nullable();
            $table->string('si57_nrocertidaoregularidadeinss', 30)->nullable();
            $table->date('si57_dtemissaocertidaoregularidadeinss')->nullable();
            $table->date('si57_dtvalidadecertidaoregularidadeinss')->nullable();
            $table->string('si57_nrocertidaoregularidadefgts', 30)->nullable();
            $table->date('si57_dtemissaocertidaoregularidadefgts')->nullable();
            $table->date('si57_dtvalidadecertidaoregularidadefgts')->nullable();
            $table->string('si57_nrocndt', 30)->nullable();
            $table->date('si57_dtemissaocndt')->nullable();
            $table->date('si57_dtvalidadecndt')->nullable();
            $table->date('si57_dthabilitacao');
            $table->bigInteger('si57_presencalicitantes')->nullable();
            $table->bigInteger('si57_renunciarecurso')->nullable();
            $table->bigInteger('si57_mes')->default(0);
            $table->bigInteger('si57_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE hablic102025_si57_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE hablic102025 ALTER COLUMN si57_sequencial SET DEFAULT nextval(\'hablic102025_si57_sequencial_seq\');');

        Schema::create('hablic202025', function (Blueprint $table) {
            $table->bigInteger('si59_sequencial')->default(0)->primary();
            $table->bigInteger('si59_tiporegistro')->default(0);
            $table->string('si59_codorgao', 2);
            $table->string('si59_codunidadesub', 8)->nullable();
            $table->bigInteger('si59_exerciciolicitacao')->default(0);
            $table->string('si59_nroprocessolicitatorio', 12);
            $table->bigInteger('si59_tipodocumento')->default(0);
            $table->string('si59_nrodocumento', 14);
            $table->date('si59_datacredenciamento');
            $table->bigInteger('si59_nrolote')->nullable();
            $table->bigInteger('si59_coditem')->default(0);
            $table->string('si59_nroinscricaoestadual', 30)->nullable();
            $table->string('si59_ufinscricaoestadual', 2)->nullable();
            $table->string('si59_nrocertidaoregularidadeinss', 30)->nullable();
            $table->date('si59_dataemissaocertidaoregularidadeinss')->nullable();
            $table->date('si59_dtvalidadecertidaoregularidadeinss')->nullable();
            $table->string('si59_nrocertidaoregularidadefgts', 30)->nullable();
            $table->date('si59_dtemissaocertidaoregularidadefgts')->nullable();
            $table->date('si59_dtvalidadecertidaoregularidadefgts')->nullable();
            $table->string('si59_nrocndt', 30)->nullable();
            $table->date('si59_dtemissaocndt')->nullable();
            $table->date('si59_dtvalidadecndt')->nullable();
            $table->bigInteger('si59_mes')->default(0);
            $table->bigInteger('si59_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE hablic202025_si59_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE hablic202025 ALTER COLUMN si59_sequencial SET DEFAULT nextval(\'hablic202025_si59_sequencial_seq\');');

        Schema::create('homolic102025', function (Blueprint $table) {
            $table->bigInteger('si63_sequencial')->default(0)->primary();
            $table->bigInteger('si63_tiporegistro')->default(0);
            $table->string('si63_codorgao', 2);
            $table->string('si63_codunidadesub', 8);
            $table->bigInteger('si63_exerciciolicitacao')->default(0);
            $table->string('si63_nroprocessolicitatorio', 12);
            $table->bigInteger('si63_tipodocumento')->default(0);
            $table->string('si63_nrodocumento', 14);
            $table->bigInteger('si63_nrolote')->nullable();
            $table->bigInteger('si63_coditem')->default(0);
            $table->float('si63_quantidade')->default(0);
            $table->float('si63_vlunitariohomologado')->default(0);
            $table->bigInteger('si63_mes')->default(0);
            $table->bigInteger('si63_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE homolic102025_si63_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE homolic102025 ALTER COLUMN si63_sequencial SET DEFAULT nextval(\'homolic102025_si63_sequencial_seq\');');

        Schema::create('homolic202025', function (Blueprint $table) {
            $table->bigInteger('si64_sequencial')->default(0)->primary();
            $table->bigInteger('si64_tiporegistro')->default(0);
            $table->string('si64_codorgao', 2);
            $table->string('si64_codunidadesub', 8);
            $table->bigInteger('si64_exerciciolicitacao')->default(0);
            $table->string('si64_nroprocessolicitatorio', 12);
            $table->bigInteger('si64_tipodocumento')->default(0);
            $table->string('si64_nrodocumento', 14);
            $table->bigInteger('si64_nrolote')->nullable();
            $table->bigInteger('si64_coditem')->default(0);
            $table->float('si64_quantidade')->default(0);
            $table->float('si64_vlunitariohomologado')->default(0);
            $table->bigInteger('si64_mes')->default(0);
            $table->bigInteger('si64_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE homolic202025_si64_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE homolic202025 ALTER COLUMN si64_sequencial SET DEFAULT nextval(\'homolic202025_si64_sequencial_seq\');');

        Schema::create('homolic302025', function (Blueprint $table) {
            $table->bigInteger('si65_sequencial')->default(0)->primary();
            $table->bigInteger('si65_tiporegistro')->default(0);
            $table->string('si65_codorgao', 2);
            $table->string('si65_codunidadesub', 8);
            $table->bigInteger('si65_exerciciolicitacao')->default(0);
            $table->string('si65_nroprocessolicitatorio', 12);
            $table->bigInteger('si65_tipodocumento')->default(0);
            $table->string('si65_nrodocumento', 14);
            $table->bigInteger('si65_nrolote')->nullable();
            $table->bigInteger('si65_coditem')->default(0);
            $table->float('si65_quantidade')->default(0);
            $table->float('si65_vlunitariohomologado')->default(0);
            $table->bigInteger('si65_mes')->default(0);
            $table->bigInteger('si65_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE homolic302025_si65_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE homolic302025 ALTER COLUMN si65_sequencial SET DEFAULT nextval(\'homolic302025_si65_sequencial_seq\');');

        Schema::create('homolic402025', function (Blueprint $table) {
            $table->bigInteger('si66_sequencial')->default(0)->primary();
            $table->bigInteger('si66_tiporegistro')->default(0);
            $table->string('si66_codorgao', 2);
            $table->string('si66_codunidadesub', 8);
            $table->bigInteger('si66_exerciciolicitacao')->default(0);
            $table->string('si66_nroprocessolicitatorio', 12);
            $table->bigInteger('si66_tipodocumento')->default(0);
            $table->string('si66_nrodocumento', 14);
            $table->bigInteger('si66_nrolote')->nullable();
            $table->bigInteger('si66_coditem')->default(0);
            $table->float('si66_quantidade')->default(0);
            $table->float('si66_vlunitariohomologado')->default(0);
            $table->bigInteger('si66_mes')->default(0);
            $table->bigInteger('si66_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE homolic402025_si66_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE homolic402025 ALTER COLUMN si66_sequencial SET DEFAULT nextval(\'homolic402025_si66_sequencial_seq\');');

        Schema::create('ide2025', function (Blueprint $table) {
            $table->bigInteger('si11_sequencial')->default(0)->primary();
            $table->string('si11_codmunicipio', 5);
            $table->string('si11_cnpjmunicipio', 14);
            $table->string('si11_codorgao', 3);
            $table->string('si11_tipoorgao', 2);
            $table->bigInteger('si11_exercicioreferencia')->default(0);
            $table->string('si11_mesreferencia', 2);
            $table->date('si11_datageracao');
            $table->string('si11_codcontroleremessa', 20)->nullable();
            $table->bigInteger('si11_mes')->default(0);
            $table->bigInteger('si11_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE ide2025_si11_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ide2025 ALTER COLUMN si11_sequencial SET DEFAULT nextval(\'ide2025_si11_sequencial_seq\');');

        Schema::create('idedcasp2025', function (Blueprint $table) {
            $table->integer('si200_sequencial')->default(0)->primary();
            $table->string('si200_codmunicipio', 5);
            $table->string('si200_cnpjorgao', 14);
            $table->string('si200_codorgao', 2);
            $table->string('si200_tipoorgao', 2);
            $table->integer('si200_tipodemcontabil')->default(0);
            $table->integer('si200_exercicioreferencia')->default(0);
            $table->date('si200_datageracao');
            $table->string('si200_codcontroleremessa', 20)->nullable();
            $table->integer('si200_anousu')->default(0);
            $table->integer('si200_instit')->default(0);
        });
        DB::statement('CREATE SEQUENCE idedcasp2025_si200_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE idedcasp2025 ALTER COLUMN si200_sequencial SET DEFAULT nextval(\'idedcasp2025_si200_sequencial_seq\');');

        Schema::create('ideedital2025', function (Blueprint $table) {
            $table->bigInteger('si186_sequencial')->default(0)->primary();
            $table->char('si186_codidentificador', 5);
            $table->char('si186_cnpj', 14);
            $table->string('si186_codorgao', 3);
            $table->string('si186_tipoorgao', 2);
            $table->integer('si186_exercicioreferencia');
            $table->char('si186_mesreferencia', 2);
            $table->date('si186_datageracao');
            $table->string('si186_codcontroleremessa', 20)->nullable();
            $table->integer('si186_codseqremessames');
            $table->bigInteger('si186_mes')->default(0);
            $table->bigInteger('si186_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE ideedital2025_si186_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ideedital2025 ALTER COLUMN si186_sequencial SET DEFAULT nextval(\'ideedital2025_si186_sequencial_seq\');');

        Schema::create('iderp102025', function (Blueprint $table) {
            $table->bigInteger('si179_sequencial')->default(0)->primary();
            $table->bigInteger('si179_tiporegistro')->default(0);
            $table->bigInteger('si179_codiderp')->default(0);
            $table->string('si179_codorgao', 2)->default(0);
            $table->string('si179_codunidadesub', 8)->default(0);
            $table->bigInteger('si179_nroempenho')->default(0);
            $table->bigInteger('si179_tiporestospagar')->default(0);
            $table->bigInteger('si179_disponibilidadecaixa')->default(0);
            $table->float('si179_vlinscricao')->default(0);
            $table->bigInteger('si179_instit')->nullable()->default(0);
            $table->bigInteger('si179_mes')->default(0);
        });
        DB::statement('CREATE SEQUENCE iderp102025_si179_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE iderp102025 ALTER COLUMN si179_sequencial SET DEFAULT nextval(\'iderp102025_si179_sequencial_seq\');');

        Schema::create('iderp112025', function (Blueprint $table) {
            $table->bigInteger('si180_sequencial')->default(0)->primary();
            $table->bigInteger('si180_tiporegistro')->default(0);
            $table->bigInteger('si180_codiderp')->default(0);
            $table->bigInteger('si180_codfontrecursos')->default(0);
            $table->float('si180_vlinscricaofonte')->default(0);
            $table->bigInteger('si180_mes')->default(0);
            $table->bigInteger('si180_reg10')->default(0);
            $table->bigInteger('si180_instit')->nullable()->default(0);
            $table->string('si180_codco')->default('0000');
            $table->bigInteger('si180_disponibilidadecaixa')->default(0);
        });
        DB::statement('CREATE SEQUENCE iderp112025_si180_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE iderp112025 ALTER COLUMN si180_sequencial SET DEFAULT nextval(\'iderp112025_si180_sequencial_seq\');');

        Schema::create('iderp202025', function (Blueprint $table) {
            $table->bigInteger('si181_sequencial')->default(0)->primary();
            $table->bigInteger('si181_tiporegistro')->default(0);
            $table->string('si181_codorgao', 2);
            $table->bigInteger('si181_codfontrecursos')->default(0);
            $table->float('si181_vlcaixabruta')->default(0);
            $table->float('si181_vlrspexerciciosanteriores')->default(0);
            $table->float('si181_vlrestituiveisrecolher')->nullable()->default(0);
            $table->float('si181_vlrestituiveisativofinanceiro')->nullable()->default(0);
            $table->float('si181_vlsaldodispcaixa')->default(0);
            $table->bigInteger('si181_mes')->default(0);
            $table->bigInteger('si181_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE iderp202025_si181_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE iderp202025 ALTER COLUMN si181_sequencial SET DEFAULT nextval(\'iderp202025_si181_sequencial_seq\');');

        Schema::create('item102025', function (Blueprint $table) {
            $table->bigInteger('si43_sequencial')->default(0)->primary();
            $table->bigInteger('si43_tiporegistro')->default(0);
            $table->bigInteger('si43_coditem')->default(0);
            $table->text('si43_dscitem');
            $table->string('si43_unidademedida', 50);
            $table->bigInteger('si43_tipocadastro')->default(0);
            $table->string('si43_justificativaalteracao', 100)->nullable();
            $table->bigInteger('si43_mes')->default(0);
            $table->bigInteger('si43_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE item102025_si43_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE item102025 ALTER COLUMN si43_sequencial SET DEFAULT nextval(\'item102025_si43_sequencial_seq\');');

        Schema::create('julglic102025', function (Blueprint $table) {
            $table->bigInteger('si60_sequencial')->default(0)->primary();
            $table->bigInteger('si60_tiporegistro')->default(0);
            $table->string('si60_codorgao', 2);
            $table->string('si60_codunidadesub', 8);
            $table->bigInteger('si60_exerciciolicitacao')->default(0);
            $table->string('si60_nroprocessolicitatorio', 12);
            $table->bigInteger('si60_tipodocumento')->default(0);
            $table->string('si60_nrodocumento', 14);
            $table->bigInteger('si60_nrolote')->nullable();
            $table->bigInteger('si60_coditem')->default(0);
            $table->float('si60_vlunitario')->default(0);
            $table->float('si60_quantidade')->default(0);
            $table->bigInteger('si60_mes')->default(0);
            $table->bigInteger('si60_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE julglic102025_si60_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE julglic102025 ALTER COLUMN si60_sequencial SET DEFAULT nextval(\'julglic102025_si60_sequencial_seq\');');

        Schema::create('julglic202025', function (Blueprint $table) {
            $table->bigInteger('si61_sequencial')->default(0)->primary();
            $table->bigInteger('si61_tiporegistro')->default(0);
            $table->string('si61_codorgao', 2);
            $table->string('si61_codunidadesub', 8);
            $table->bigInteger('si61_exerciciolicitacao')->default(0);
            $table->string('si61_nroprocessolicitatorio', 12);
            $table->bigInteger('si61_tipodocumento')->default(0);
            $table->string('si61_nrodocumento', 14);
            $table->bigInteger('si61_nrolote')->nullable();
            $table->string('si61_coditem', 15)->nullable();
            $table->float('si61_percdesconto')->default(0);
            $table->bigInteger('si61_mes')->default(0);
            $table->bigInteger('si61_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE julglic202025_si61_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE julglic202025 ALTER COLUMN si61_sequencial SET DEFAULT nextval(\'julglic202025_si61_sequencial_seq\');');

        Schema::create('julglic302025', function (Blueprint $table) {
            $table->bigInteger('si62_sequencial')->default(0)->primary();
            $table->bigInteger('si62_tiporegistro')->default(0);
            $table->string('si62_codorgao', 2);
            $table->string('si62_codunidadesub', 8);
            $table->bigInteger('si62_exerciciolicitacao')->default(0);
            $table->string('si62_nroprocessolicitatorio', 12);
            $table->bigInteger('si62_tipodocumento')->default(0);
            $table->string('si62_nrodocumento', 14);
            $table->bigInteger('si62_nrolote')->nullable();
            $table->string('si62_coditem', 15)->nullable();
            $table->float('si62_perctaxaadm')->default(0);
            $table->bigInteger('si62_mes')->default(0);
            $table->bigInteger('si62_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE julglic302025_si62_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE julglic302025 ALTER COLUMN si62_sequencial SET DEFAULT nextval(\'julglic302025_si62_sequencial_seq\');');

        Schema::create('julglic402025', function (Blueprint $table) {
            $table->bigInteger('si62_sequencial')->default(0)->primary();
            $table->bigInteger('si62_tiporegistro')->default(0);
            $table->string('si62_codorgao', 2);
            $table->string('si62_codunidadesub', 8);
            $table->bigInteger('si62_exerciciolicitacao')->default(0);
            $table->string('si62_nroprocessolicitatorio', 12);
            $table->date('si62_dtjulgamento');
            $table->bigInteger('si62_presencalicitantes')->nullable();
            $table->bigInteger('si62_renunciarecurso')->nullable();
            $table->bigInteger('si62_mes')->default(0);
            $table->bigInteger('si62_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE julglic402025_si62_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE julglic402025 ALTER COLUMN si62_sequencial SET DEFAULT nextval(\'julglic402025_si62_sequencial_seq\');');

        Schema::create('lao102025', function (Blueprint $table) {
            $table->bigInteger('si34_sequencial')->default(0)->primary();
            $table->bigInteger('si34_tiporegistro')->default(0);
            $table->string('si34_codorgao', 2);
            $table->bigInteger('si34_nroleialteracao');
            $table->date('si34_dataleialteracao');
            $table->bigInteger('si34_mes')->default(0);
            $table->bigInteger('si34_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE lao102025_si34_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE lao102025 ALTER COLUMN si34_sequencial SET DEFAULT nextval(\'lao102025_si34_sequencial_seq\');');

        Schema::create('lao202025', function (Blueprint $table) {
            $table->bigInteger('si36_sequencial')->default(0)->primary();
            $table->bigInteger('si36_tiporegistro')->default(0);
            $table->string('si36_codorgao', 2);
            $table->string('si36_nroleialterorcam', 6);
            $table->date('si36_dataleialterorcam');
            $table->bigInteger('si36_mes')->default(0);
            $table->bigInteger('si36_instit')->nullable()->default(0);
        });
        DB::statement('CREATE SEQUENCE lao202025_si36_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE lao202025 ALTER COLUMN si36_sequencial SET DEFAULT nextval(\'lao202025_si36_sequencial_seq\');');

        Schema::create('licobras102025', function (Blueprint $table) {
            $table->bigInteger('si195_sequencial')->nullable();
            $table->bigInteger('si195_tiporegistro')->nullable();
            $table->string('si195_codorgaoresp', 3)->nullable();
            $table->string('si195_codunidadesubrespestadual', 4)->nullable();
            $table->bigInteger('si195_exerciciolicitacao')->nullable();
            $table->string('si195_nroprocessolicitatorio', 12)->nullable();
            $table->bigInteger('si195_nrolote')->nullable();
            $table->bigInteger('si195_contdeclicitacao')->nullable();
            $table->bigInteger('si195_codobra')->nullable();
            $table->text('si195_objeto')->nullable();
            $table->text('si195_linkobra')->nullable();
            $table->bigInteger('si195_codorgaorespsicom')->nullable();
            $table->bigInteger('si195_codunidadesubsicom')->nullable();
            $table->bigInteger('si195_nrocontrato')->nullable();
            $table->bigInteger('si195_exerciciocontrato')->nullable();
            $table->date('si195_dataassinatura')->nullable();
            $table->decimal('si195_vlcontrato', 10, 2)->nullable();
            $table->bigInteger('si195_tipodocumento')->nullable();
            $table->string('si195_numdocumentocontratado', 14)->nullable();
            $table->bigInteger('si195_undmedidaprazoexecucao')->nullable();
            $table->bigInteger('si195_prazoexecucao')->nullable();
            $table->bigInteger('si195_mes')->nullable();
            $table->bigInteger('si195_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE licobras102025_si195_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE licobras102025 ALTER COLUMN si195_sequencial SET DEFAULT nextval(\'licobras102025_si195_sequencial_seq\');');

        Schema::create('licobras202025', function (Blueprint $table) {
            $table->bigInteger('si196_sequencial')->nullable();
            $table->bigInteger('si196_tiporegistro')->nullable();
            $table->string('si196_codorgaoresp', 3)->nullable();
            $table->string('si196_codunidadesubrespestadual', 4)->nullable();
            $table->bigInteger('si196_exerciciolicitacao')->nullable();
            $table->string('si196_nroprocessolicitatorio', 12)->nullable();
            $table->bigInteger('si196_tipoprocesso')->nullable();
            $table->bigInteger('si196_contdeclicitacao')->nullable();
            $table->bigInteger('si196_codobra')->nullable();
            $table->text('si196_objeto')->nullable();
            $table->text('si196_linkobra')->nullable();
            $table->bigInteger('si196_codorgaorespsicom')->nullable();
            $table->bigInteger('si196_codunidadesubsicom')->nullable();
            $table->bigInteger('si196_nrocontrato')->nullable();
            $table->bigInteger('si196_exerciciocontrato')->nullable();
            $table->date('si196_dataassinatura')->nullable();
            $table->decimal('si196_vlcontrato', 10, 2)->nullable();
            $table->bigInteger('si196_undmedidaprazoexecucao')->nullable();
            $table->bigInteger('si196_prazoexecucao')->nullable();
            $table->bigInteger('si196_mes')->nullable();
            $table->bigInteger('si196_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE licobras202025_si196_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE licobras202025 ALTER COLUMN si196_sequencial SET DEFAULT nextval(\'licobras202025_si196_sequencial_seq\');');

        Schema::create('licobras302025', function (Blueprint $table) {
            $table->bigInteger('si203_sequencial')->nullable();
            $table->bigInteger('si203_tiporegistro')->nullable();
            $table->string('si203_codorgaoresp', 3)->nullable();
            $table->bigInteger('si203_codobra')->nullable();
            $table->string('si203_codunidadesubrespestadual', 4)->nullable();
            $table->bigInteger('si203_nroseqtermoaditivo')->nullable();
            $table->date('si203_dataassinaturatermoaditivo')->nullable();
            $table->bigInteger('si203_tipoalteracaovalor')->nullable();
            $table->string('si203_tipotermoaditivo', 2)->nullable();
            $table->text('si203_dscalteracao')->nullable();
            $table->date('si203_novadatatermino')->nullable();
            $table->bigInteger('si203_tipodetalhamento')->nullable();
            $table->decimal('si203_valoraditivo', 10, 2)->nullable();
            $table->bigInteger('si203_mes')->nullable();
            $table->bigInteger('si203_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE licobras302025_si203_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE licobras302025 ALTER COLUMN si203_sequencial SET DEFAULT nextval(\'licobras302025_si203_sequencial_seq\');');

        Schema::create('lqd102025', function (Blueprint $table) {
            $table->bigInteger('si118_sequencial')->default(0)->primary();
            $table->bigInteger('si118_tiporegistro')->default(0);
            $table->bigInteger('si118_codreduzido')->default(0);
            $table->string('si118_codorgao', 2)->nullable();
            $table->string('si118_codunidadesub', 8)->nullable();
            $table->bigInteger('si118_tpliquidacao')->default(0);
            $table->bigInteger('si118_nroempenho')->default(0);
            $table->date('si118_dtempenho');
            $table->date('si118_dtliquidacao');
            $table->bigInteger('si118_nroliquidacao')->default(0);
            $table->decimal('si118_vlliquidado', 10, 2)->default(0);
            $table->string('si118_cpfliquidante', 11);
            $table->bigInteger('si118_mes')->default(0);
            $table->bigInteger('si118_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE lqd102025_si118_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE lqd102025 ALTER COLUMN si118_sequencial SET DEFAULT nextval(\'lqd102025_si118_sequencial_seq\');');

        Schema::create('metareal102025', function (Blueprint $table) {
            $table->bigInteger('si171_sequencial')->default(0)->primary();
            $table->bigInteger('si171_tiporegistro')->default(0);
            $table->string('si171_codorgao', 2)->default(0);
            $table->string('si171_codunidadesub', 8)->default(0);
            $table->string('si171_codfuncao', 2)->default(0);
            $table->string('si171_codsubfuncao', 3)->default(0);
            $table->string('si171_codprograma', 4)->default(0);
            $table->string('si171_idacao', 4)->default(0);
            $table->string('si171_idsubacao', 4)->nullable();
            $table->decimal('si171_metarealizada', 10, 2)->default(0);
            $table->string('si171_justificativa', 1000)->nullable();
            $table->bigInteger('si171_instit')->nullable();
            $table->bigInteger('si171_mes')->nullable();
        });
        DB::statement('CREATE SEQUENCE metareal102025_si171_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE metareal102025 ALTER COLUMN si171_sequencial SET DEFAULT nextval(\'metareal102025_si171_sequencial_seq\');');

        Schema::create('ntf102025', function (Blueprint $table) {
            $table->bigInteger('si143_sequencial')->default(0)->primary();
            $table->bigInteger('si143_tiporegistro')->default(0);
            $table->bigInteger('si143_codnotafiscal')->default(0);
            $table->string('si143_codorgao', 2);
            $table->bigInteger('si143_nfnumero')->nullable();
            $table->string('si143_nfserie', 8)->nullable();
            $table->bigInteger('si143_tipodocumento')->default(0);
            $table->string('si143_nrodocumento', 14);
            $table->string('si143_nroinscestadual', 30)->nullable();
            $table->string('si143_nroinscmunicipal', 30)->nullable();
            $table->string('si143_nomemunicipio', 120);
            $table->bigInteger('si143_cepmunicipio')->default(0);
            $table->string('si143_ufcredor', 2);
            $table->bigInteger('si143_notafiscaleletronica')->default(0);
            $table->string('si143_chaveacesso', 44)->nullable();
            $table->string('si143_outrachaveacesso', 60)->nullable();
            $table->string('si143_nfaidf', 15);
            $table->date('si143_dtemissaonf');
            $table->date('si143_dtvencimentonf')->nullable();
            $table->decimal('si143_nfvalortotal', 10, 2)->default(0);
            $table->decimal('si143_nfvalordesconto', 10, 2)->default(0);
            $table->decimal('si143_nfvalorliquido', 10, 2)->default(0);
            $table->bigInteger('si143_mes')->default(0);
            $table->bigInteger('si143_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE ntf102025_si143_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ntf102025 ALTER COLUMN si143_sequencial SET DEFAULT nextval(\'ntf102025_si143_sequencial_seq\');');

        Schema::create('ntf202025', function (Blueprint $table) {
            $table->bigInteger('si145_sequencial')->default(0)->primary();
            $table->bigInteger('si145_tiporegistro')->default(0);
            $table->bigInteger('si145_nfnumero')->default(0);
            $table->string('si145_nfserie', 8)->nullable();
            $table->bigInteger('si145_tipodocumento')->default(0);
            $table->string('si145_nrodocumento', 14)->default(0);
            $table->string('si145_chaveacesso', 44)->nullable();
            $table->date('si145_dtemissaonf');
            $table->string('si145_codunidadesub', 8);
            $table->date('si145_dtempenho');
            $table->bigInteger('si145_nroempenho')->default(0);
            $table->date('si145_dtliquidacao');
            $table->bigInteger('si145_nroliquidacao')->default(0);
            $table->bigInteger('si145_mes')->default(0);
            $table->bigInteger('si145_reg10')->default(0);
            $table->bigInteger('si145_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE ntf202025_si145_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ntf202025 ALTER COLUMN si145_sequencial SET DEFAULT nextval(\'ntf202025_si145_sequencial_seq\');');

        Schema::create('obelac102025', function (Blueprint $table) {
            $table->bigInteger('si155_sequencial')->default(0)->primary();
            $table->bigInteger('si155_tiporegistro')->default(0);
            $table->string('si155_codorgao', 2)->default(0);
            $table->bigInteger('si155_numerocontrato')->nullable();
            $table->date('si155_dataassinatura')->nullable();
            $table->decimal('si155_valorcontratado', 10, 2)->nullable();
            $table->date('si155_datatermino')->nullable();
            $table->string('si155_nroempresa', 8)->nullable();
            $table->string('si155_codunidadesub', 8)->nullable();
            $table->bigInteger('si155_undmedidaprazoexecucao')->nullable();
            $table->bigInteger('si155_prazoexecucao')->nullable();
            $table->bigInteger('si155_mes')->nullable();
            $table->bigInteger('si155_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE obelac102025_si155_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE obelac102025 ALTER COLUMN si155_sequencial SET DEFAULT nextval(\'obelac102025_si155_sequencial_seq\');');

        Schema::create('ops102025', function (Blueprint $table) {
            $table->integer('si132_sequencial')->default(0)->primary();
            $table->bigInteger('si132_tiporegistro')->default(0);
            $table->string('si132_codorgao', 2);
            $table->string('si132_codunidadesub', 8);
            $table->bigInteger('si132_nroop')->default(0);
            $table->date('si132_dtpagamento');
            $table->double('si132_vlop')->default(0);
            $table->string('si132_especificacaoop', 500);
            $table->string('si132_cpfresppgto', 11);
            $table->bigInteger('si132_mes')->default(0);
            $table->bigInteger('si132_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE ops102025_si132_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ops102025 ALTER COLUMN si132_sequencial SET DEFAULT nextval(\'ops102025_si132_sequencial_seq\');');

        Schema::create('orgao102025', function (Blueprint $table) {
            $table->integer('si14_sequencial')->default(0)->primary();
            $table->bigInteger('si14_tiporegistro')->default(0);
            $table->string('si14_codorgao', 2);
            $table->string('si14_tipoorgao', 2);
            $table->string('si14_cnpjorgao', 14);
            $table->bigInteger('si14_tipodocumentofornsoftware')->default(0);
            $table->string('si14_nrodocumentofornsoftware', 14);
            $table->string('si14_versaosoftware', 50);
            $table->bigInteger('si14_assessoriacontabil');
            $table->bigInteger('si14_tipodocumentoassessoria')->nullable();
            $table->string('si14_nrodocumentoassessoria', 14)->nullable();
            $table->bigInteger('si14_mes')->default(0);
            $table->bigInteger('si14_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE orgao102025_si14_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE orgao102025 ALTER COLUMN si14_sequencial SET DEFAULT nextval(\'orgao102025_si14_sequencial_seq\');');

        Schema::create('parec102025', function (Blueprint $table) {
            $table->integer('si22_sequencial')->default(0)->primary();
            $table->bigInteger('si22_tiporegistro')->default(0);
            $table->bigInteger('si22_codreduzido')->default(0);
            $table->string('si22_codorgao', 2);
            $table->bigInteger('si22_ededucaodereceita')->default(0);
            $table->bigInteger('si22_identificadordeducao')->nullable();
            $table->bigInteger('si22_naturezareceita')->default(0);
            $table->bigInteger('si22_tipoatualizacao')->default(0);
            $table->double('si22_vlacrescidoreduzido')->default(0);
            $table->bigInteger('si22_mes')->default(0);
            $table->bigInteger('si22_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE parec102025_si22_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE parec102025 ALTER COLUMN si22_sequencial SET DEFAULT nextval(\'parec102025_si22_sequencial_seq\');');

        Schema::create('parelic102025', function (Blueprint $table) {
            $table->integer('si66_sequencial')->default(0)->primary();
            $table->bigInteger('si66_tiporegistro')->default(0);
            $table->string('si66_codorgao', 2);
            $table->string('si66_codunidadesub', 8)->nullable();
            $table->bigInteger('si66_exerciciolicitacao')->default(0);
            $table->string('si66_nroprocessolicitatorio', 12);
            $table->date('si66_dataparecer');
            $table->bigInteger('si66_tipoparecer')->default(0);
            $table->string('si66_nrocpf', 11);
            $table->bigInteger('si66_mes')->default(0);
            $table->bigInteger('si66_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE parelic102025_si66_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE parelic102025 ALTER COLUMN si66_sequencial SET DEFAULT nextval(\'parelic102025_si66_sequencial_seq\');');

        Schema::create('parpps102025', function (Blueprint $table) {
            $table->integer('si156_sequencial')->default(0)->primary();
            $table->bigInteger('si156_tiporegistro')->default(0);
            $table->string('si156_codorgao', 2);
            $table->bigInteger('si156_tipoplano')->default(0);
            $table->bigInteger('si156_exercicio')->default(0);
            $table->double('si156_vlsaldofinanceiroexercicioanterior')->default(0);
            $table->double('si156_vlreceitaprevidenciariaanterior')->default(0);
            $table->double('si156_vldespesaprevidenciariaanterior')->default(0);
            $table->bigInteger('si156_mes')->default(0);
            $table->bigInteger('si156_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE parpps102025_si156_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE parpps102025 ALTER COLUMN si156_sequencial SET DEFAULT nextval(\'parpps102025_si156_sequencial_seq\');');

        Schema::create('parpps202025', function (Blueprint $table) {
            $table->integer('si155_sequencial')->default(0)->primary();
            $table->bigInteger('si155_tiporegistro')->default(0);
            $table->string('si155_codorgao', 2);
            $table->bigInteger('si155_tipoplano')->default(0);
            $table->bigInteger('si155_exercicio')->default(0);
            $table->double('si155_vlreceitaprevidenciaria')->default(0);
            $table->double('si155_vldespesaprevidenciaria')->default(0);
            $table->bigInteger('si155_mes')->default(0);
            $table->bigInteger('si155_instit')->nullable();
            $table->date('si155_dtavaliacao')->nullable();
        });
        DB::statement('CREATE SEQUENCE parpps202025_si155_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE parpps202025 ALTER COLUMN si155_sequencial SET DEFAULT nextval(\'parpps202025_si155_sequencial_seq\');');

        Schema::create('partlic102025', function (Blueprint $table) {
            $table->integer('si203_sequencial')->default(0)->primary();
            $table->bigInteger('si203_tiporegistro')->default(0);
            $table->string('si203_codorgao', 2);
            $table->string('si203_codunidadesub', 8);
            $table->bigInteger('si203_exerciciolicitacao')->default(0);
            $table->bigInteger('si203_nroprocessolicitatorio')->default(0);
            $table->bigInteger('si203_tipodocumento')->default(0);
            $table->string('si203_nrodocumento', 14)->nullable();
            $table->bigInteger('si203_mes')->default(0);
            $table->bigInteger('si203_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE partlic102025_si203_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE partlic102025 ALTER COLUMN si203_sequencial SET DEFAULT nextval(\'partlic102025_si203_sequencial_seq\');');

        Schema::create('pessoa102025', function (Blueprint $table) {
            $table->integer('si12_sequencial')->default(0)->primary();
            $table->bigInteger('si12_tiporegistro')->default(0);
            $table->bigInteger('si12_tipodocumento')->default(0);
            $table->string('si12_nrodocumento', 14);
            $table->string('si12_nomerazaosocial', 120);
            $table->bigInteger('si12_tipocadastro')->default(0);
            $table->string('si12_justificativaalteracao', 100)->nullable();
            $table->bigInteger('si12_mes')->default(0);
            $table->bigInteger('si12_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE pessoa102025_si12_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE pessoa102025 ALTER COLUMN si12_sequencial SET DEFAULT nextval(\'pessoa102025_si12_sequencial_seq\');');

        Schema::create('pessoaflpgo102025', function (Blueprint $table) {
            $table->integer('si193_sequencial')->default(0)->primary();
            $table->bigInteger('si193_tiporegistro')->default(0);
            $table->bigInteger('si193_tipodocumento')->default(0);
            $table->string('si193_nrodocumento', 14);
            $table->string('si193_nome', 120);
            $table->string('si193_indsexo', 1)->nullable();
            $table->date('si193_datanascimento')->nullable();
            $table->bigInteger('si193_tipocadastro')->default(0);
            $table->string('si193_justalteracao', 100)->nullable();
            $table->bigInteger('si193_mes')->default(0);
            $table->bigInteger('si193_inst')->nullable();
        });
        DB::statement('CREATE SEQUENCE pessoaflpgo102025_si193_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE pessoaflpgo102025 ALTER COLUMN si193_sequencial SET DEFAULT nextval(\'pessoaflpgo102025_si193_sequencial_seq\');');

        Schema::create('pessoasobra102025', function (Blueprint $table) {
            $table->integer('si194_sequencial')->nullable();
            $table->bigInteger('si194_tipodocumento')->nullable();
            $table->bigInteger('si194_tiporegistro')->nullable();
            $table->string('si194_nrodocumento', 14)->nullable();
            $table->string('si194_nome', 120)->nullable();
            $table->bigInteger('si194_tipocadastro')->nullable();
            $table->string('si194_justificativaalteracao')->nullable();
            $table->bigInteger('si194_mes')->nullable();
            $table->bigInteger('si194_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE pessoasobra102025_si194_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE pessoasobra102025 ALTER COLUMN si194_sequencial SET DEFAULT nextval(\'pessoasobra102025_si194_sequencial_seq\');');

        Schema::create('ralic102025', function (Blueprint $table) {
            $table->bigInteger('si180_sequencial')->default(0)->primary();
            $table->bigInteger('si180_tiporegistro')->default(0);
            $table->integer('si180_codorgaoresp');
            $table->string('si180_codunidadesubresp', 8);
            $table->string('si180_codunidadesubrespestadual', 4)->nullable();
            $table->smallInteger('si180_exerciciolicitacao');
            $table->string('si180_nroprocessolicitatorio', 12);
            $table->char('si180_tipocadastradolicitacao', 1);
            $table->string('si180_dsccadastrolicitatorio', 150)->nullable();
            $table->smallInteger('si180_codmodalidadelicitacao');
            $table->smallInteger('si180_naturezaprocedimento');
            $table->integer('si180_nroedital');
            $table->smallInteger('si180_exercicioedital')->default(0);
            $table->date('si180_dtpublicacaoeditaldo')->nullable();
            $table->string('si180_link', 200);
            $table->smallInteger('si180_tipolicitacao')->nullable();
            $table->smallInteger('si180_naturezaobjeto')->nullable();
            $table->string('si180_objeto', 500);
            $table->smallInteger('si180_regimeexecucaoobras')->nullable();
            $table->double('si180_vlcontratacao');
            $table->float('si180_bdi')->nullable();
            $table->integer('si180_mes')->default(0);
            $table->bigInteger('si180_instit')->default(0)->nullable();
            $table->integer('si180_qtdlotes')->nullable();
            $table->integer('si180_leidalicitacao')->nullable();
            $table->integer('si180_mododisputa')->nullable();
            $table->date('si180_dtaberturaenvelopes')->nullable();
            $table->integer('si180_tipoorcamento')->nullable();
            $table->string('si180_emailcontato', 200)->nullable();
        });

        DB::statement('CREATE SEQUENCE ralic102025_si180_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ralic102025 ALTER COLUMN si180_sequencial SET DEFAULT nextval(\'ralic102025_si180_sequencial_seq\');');

        Schema::create('rec102025', function (Blueprint $table) {
            $table->bigInteger('si25_sequencial')->default(0)->primary();
            $table->bigInteger('si25_tiporegistro')->default(0);
            $table->bigInteger('si25_codreceita')->default(0);
            $table->string('si25_codorgao', 2);
            $table->bigInteger('si25_ededucaodereceita')->default(0);
            $table->bigInteger('si25_identificadordeducao')->default(0);
            $table->bigInteger('si25_naturezareceita')->default(0);
            $table->double('si25_vlarrecadado')->default(0);
            $table->bigInteger('si25_mes')->default(0);
            $table->bigInteger('si25_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE rec102025_si25_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rec102025 ALTER COLUMN si25_sequencial SET DEFAULT nextval(\'rec102025_si25_sequencial_seq\');');

        Schema::create('redispi102025', function (Blueprint $table) {
            $table->bigInteger('si183_sequencial')->default(0)->primary();
            $table->bigInteger('si183_tiporegistro')->default(0);
            $table->string('si183_codorgaoresp', 3);
            $table->string('si183_codunidadesubresp', 8)->nullable();
            $table->char('si183_codunidadesubrespestadual', 4)->nullable();
            $table->smallInteger('si183_exercicioprocesso');
            $table->string('si183_nroprocesso', 12);
            $table->smallInteger('si183_tipoprocesso');
            $table->smallInteger('si183_tipocadastradodispensainexigibilidade');
            $table->string('si183_dsccadastrolicitatorio', 150)->nullable();
            $table->date('si183_dtabertura');
            $table->smallInteger('si183_naturezaobjeto');
            $table->string('si183_objeto', 500);
            $table->string('si183_justificativa', 250);
            $table->string('si183_razao', 250);
            $table->float('si183_vlrecurso');
            $table->float('si183_bdi')->nullable();
            $table->bigInteger('si183_mes')->default(0);
            $table->bigInteger('si183_instit')->nullable();
            $table->string('si183_link', 200)->nullable();
            $table->integer('si183_leidalicitacao')->nullable();
            $table->integer('si183_regimeexecucaoobras')->nullable();
            $table->string('si183_emailcontato', 200)->nullable();
        });
        DB::statement('CREATE SEQUENCE redispi102025_si183_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE redispi102025 ALTER COLUMN si183_sequencial SET DEFAULT nextval(\'redispi102025_si183_sequencial_seq\');');

        Schema::create('regadesao102025', function (Blueprint $table) {
            $table->bigInteger('si67_sequencial')->primary();
            $table->bigInteger('si67_tiporegistro')->nullable();
            $table->integer('si67_tipocadastro')->nullable();
            $table->string('si67_codorgao', 2)->nullable();
            $table->string('si67_codunidadesub', 8)->nullable();
            $table->string('si67_nroprocadesao', 12)->nullable();
            $table->bigInteger('si63_exercicioadesao')->nullable();
            $table->date('si67_dtabertura')->nullable();
            $table->string('si67_cnpjorgaogerenciador', 14)->nullable();
            $table->bigInteger('si67_exerciciolicitacao')->nullable();
            $table->string('si67_nroprocessolicitatorio', 20)->nullable();
            $table->bigInteger('si67_codmodalidadelicitacao')->nullable();
            $table->bigInteger('si67_regimecontratacao')->nullable();
            $table->bigInteger('si67_tipocriterio')->nullable();
            $table->integer('si67_nroedital')->nullable();
            $table->integer('si67_exercicioedital')->nullable();
            $table->date('si67_dtataregpreco')->nullable();
            $table->date('si67_dtvalidade')->nullable();
            $table->bigInteger('si67_naturezaprocedimento')->nullable();
            $table->date('si67_dtpublicacaoavisointencao')->nullable();
            $table->string('si67_objetoadesao', 500)->nullable();
            $table->string('si67_cpfresponsavel', 11)->nullable();
            $table->bigInteger('si67_processoporlote')->nullable();
            $table->bigInteger('si67_mes')->nullable();
            $table->bigInteger('si67_instit')->nullable();
            $table->integer('si67_leidalicitacao')->nullable();
        });
        DB::statement('CREATE SEQUENCE regadesao102025_si67_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE regadesao102025 ALTER COLUMN si67_sequencial SET DEFAULT nextval(\'regadesao102025_si67_sequencial_seq\');');

        Schema::create('regadesao112025', function (Blueprint $table) {
            $table->bigInteger('si68_sequencial')->default(0)->primary();
            $table->bigInteger('si68_tiporegistro')->default(0);
            $table->string('si68_codorgao', 2);
            $table->string('si68_codunidadesub', 8);
            $table->string('si68_nroprocadesao', 12);
            $table->bigInteger('si68_exercicioadesao')->default(0);
            $table->bigInteger('si68_nrolote')->default(0);
            $table->string('si68_dsclote', 250);
            $table->bigInteger('si68_mes')->default(0);
            $table->bigInteger('si68_reg10')->default(0);
            $table->bigInteger('si68_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE regadesao112025_si68_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE regadesao112025 ALTER COLUMN si68_sequencial SET DEFAULT nextval(\'regadesao112025_si68_sequencial_seq\');');
        DB::statement('CREATE INDEX regadesao112025_si68_reg10_index ON regadesao112025 USING btree (si68_reg10);');

        Schema::create('regadesao122025', function (Blueprint $table) {
            $table->bigInteger('si69_sequencial')->default(0)->primary();
            $table->bigInteger('si69_tiporegistro')->default(0);
            $table->string('si69_codorgao', 2);
            $table->string('si69_codunidadesub', 8);
            $table->string('si69_nroprocadesao', 12);
            $table->bigInteger('si69_exercicioadesao')->default(0);
            $table->bigInteger('si69_coditem')->default(0);
            $table->bigInteger('si69_nroitem')->default(0);
            $table->bigInteger('si69_mes')->default(0);
            $table->bigInteger('si69_reg10')->default(0);
            $table->bigInteger('si69_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE regadesao122025_si69_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE regadesao122025 ALTER COLUMN si69_sequencial SET DEFAULT nextval(\'regadesao122025_si69_sequencial_seq\');');
        DB::statement('CREATE INDEX regadesao122025_si69_reg10_index ON regadesao122025 USING btree (si69_reg10);');

        Schema::create('regadesao132025', function (Blueprint $table) {
            $table->bigInteger('si70_sequencial')->default(0)->primary();
            $table->bigInteger('si70_tiporegistro')->default(0);
            $table->string('si70_codorgao', 2);
            $table->string('si70_codunidadesub', 8);
            $table->string('si70_nroprocadesao', 12);
            $table->bigInteger('si70_exercicioadesao')->default(0);
            $table->bigInteger('si70_nrolote')->default(0);
            $table->bigInteger('si70_coditem')->default(0);
            $table->bigInteger('si70_mes')->default(0);
            $table->bigInteger('si70_reg10')->default(0);
            $table->bigInteger('si70_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE regadesao132025_si70_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE regadesao132025 ALTER COLUMN si70_sequencial SET DEFAULT nextval(\'regadesao132025_si70_sequencial_seq\');');
        DB::statement('CREATE INDEX regadesao132025_si70_reg10_index ON regadesao132025 USING btree (si70_reg10);');

        Schema::create('regadesao142025', function (Blueprint $table) {
            $table->bigInteger('si71_sequencial')->default(0)->primary();
            $table->bigInteger('si71_tiporegistro')->default(0);
            $table->string('si71_codorgao', 2);
            $table->string('si71_codunidadesub', 8);
            $table->string('si71_nroprocadesao', 12);
            $table->bigInteger('si71_exercicioadesao')->default(0);
            $table->bigInteger('si71_nrolote')->nullable();
            $table->bigInteger('si71_coditem')->default(0);
            $table->date('si71_dtcotacao');
            $table->double('si71_vlcotprecosunitario')->default(0);
            $table->double('si71_quantidade')->default(0);
            $table->bigInteger('si71_mes')->default(0);
            $table->bigInteger('si71_reg10')->default(0);
            $table->bigInteger('si71_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE regadesao142025_si71_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE regadesao142025 ALTER COLUMN si71_sequencial SET DEFAULT nextval(\'regadesao142025_si71_sequencial_seq\');');
        DB::statement('CREATE INDEX regadesao142025_si71_reg10_index ON regadesao142025 USING btree (si71_reg10);');

        Schema::create('regadesao202025', function (Blueprint $table) {
            $table->bigInteger('si72_sequencial')->default(0)->primary();
            $table->bigInteger('si72_tiporegistro')->default(0);
            $table->string('si72_codorgao', 2);
            $table->string('si72_codunidadesub', 8);
            $table->string('si72_nroprocadesao', 12);
            $table->bigInteger('si72_exercicioadesao')->default(0);
            $table->bigInteger('si72_nrolote')->nullable();
            $table->bigInteger('si72_coditem')->default(0);
            $table->double('si72_precounitario')->default(0);
            $table->double('si72_quantidadelicitada')->default(0);
            $table->double('si72_quantidadeaderida')->default(0);
            $table->bigInteger('si72_tipodocumento')->default(0);
            $table->string('si72_nrodocumento', 14);
            $table->bigInteger('si72_mes')->default(0);
            $table->bigInteger('si72_reg10')->default(0);
            $table->bigInteger('si72_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE regadesao202025_si72_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE regadesao202025 ALTER COLUMN si72_sequencial SET DEFAULT nextval(\'regadesao202025_si72_sequencial_seq\');');

        Schema::create('regadesao302025', function (Blueprint $table) {
            $table->bigInteger('si74_sequencial')->default(0)->primary();
            $table->bigInteger('si74_tiporegistro')->default(0);
            $table->string('si74_codorgao', 2);
            $table->string('si74_codunidadesub', 8);
            $table->string('si74_nroprocadesao', 12);
            $table->bigInteger('si74_exercicioadesao')->default(0);
            $table->bigInteger('si74_nrolote')->nullable();
            $table->bigInteger('si74_coditem')->nullable();
            $table->double('si74_percdesconto')->default(0);
            $table->bigInteger('si74_tipodocumento')->default(0);
            $table->string('si74_nrodocumento', 14);
            $table->bigInteger('si74_mes')->default(0);
            $table->bigInteger('si74_instit')->nullable();
            $table->bigInteger('si74_reg10')->nullable();
        });
        DB::statement('CREATE SEQUENCE regadesao302025_si74_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE regadesao302025 ALTER COLUMN si74_sequencial SET DEFAULT nextval(\'regadesao302025_si74_sequencial_seq\');');

        Schema::create('regadesao402025', function (Blueprint $table) {
            $table->bigInteger('si73_sequencial')->default(0)->primary();
            $table->bigInteger('si73_tiporegistro')->default(0);
            $table->string('si73_codorgao', 2);
            $table->string('si73_codunidadesub', 8);
            $table->string('si73_nroprocadesao', 12);
            $table->bigInteger('si73_exercicioadesao')->default(0);
            $table->bigInteger('si73_nrolote')->nullable();
            $table->bigInteger('si73_coditem')->nullable();
            $table->double('si73_percdesconto')->default(0);
            $table->bigInteger('si73_tipodocumento')->default(0);
            $table->string('si73_nrodocumento', 14);
            $table->bigInteger('si73_mes')->default(0);
            $table->bigInteger('si73_instit')->nullable();
            $table->bigInteger('si73_reg10')->nullable();
        });
        DB::statement('CREATE SEQUENCE regadesao402025_si73_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE regadesao402025 ALTER COLUMN si73_sequencial SET DEFAULT nextval(\'regadesao402025_si73_sequencial_seq\');');

        Schema::create('reglic102025', function (Blueprint $table) {
            $table->bigInteger('si44_sequencial')->default(0)->primary();
            $table->bigInteger('si44_tiporegistro')->default(0);
            $table->string('si44_codorgao', 2);
            $table->bigInteger('si44_tipodecreto')->default(0);
            $table->bigInteger('si44_nrodecretomunicipal')->default(0);
            $table->date('si44_datadecretomunicipal');
            $table->date('si44_datapublicacaodecretomunicipal');
            $table->bigInteger('si44_mes')->default(0);
            $table->bigInteger('si44_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE reglic102025_si44_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE reglic102025 ALTER COLUMN si44_sequencial SET DEFAULT nextval(\'reglic102025_si44_sequencial_seq\');');

        Schema::create('reglic202025', function (Blueprint $table) {
            $table->bigInteger('si45_sequencial')->default(0)->primary();
            $table->bigInteger('si45_tiporegistro')->default(0);
            $table->string('si45_codorgao', 2);
            $table->bigInteger('si45_regulamentart47')->default(0);
            $table->string('si45_nronormareg', 6)->nullable();
            $table->date('si45_datanormareg')->nullable();
            $table->date('si45_datapubnormareg')->nullable();
            $table->bigInteger('si45_regexclusiva')->default(0)->nullable();
            $table->string('si45_artigoregexclusiva', 6)->nullable();
            $table->float('si45_valorlimiteregexclusiva')->default(0);
            $table->bigInteger('si45_procsubcontratacao')->default(0)->nullable();
            $table->string('si45_artigoprocsubcontratacao', 6)->nullable();
            $table->float('si45_percentualsubcontratacao')->default(0);
            $table->bigInteger('si45_criteriosempenhopagamento')->default(0)->nullable();
            $table->string('si45_artigoempenhopagamento', 6)->nullable();
            $table->bigInteger('si45_estabeleceuperccontratacao')->default(0)->nullable();
            $table->string('si45_artigoperccontratacao', 6)->nullable();
            $table->float('si45_percentualcontratacao')->default(0);
            $table->bigInteger('si45_mes')->default(0);
            $table->bigInteger('si45_instit')->default(0)->nullable();
        });
        DB::statement('CREATE SEQUENCE reglic202025_si45_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE reglic202025 ALTER COLUMN si45_sequencial SET DEFAULT nextval(\'reglic202025_si45_sequencial_seq\');');

        Schema::create('respinf2025', function (Blueprint $table) {
            $table->bigInteger('si197_sequencial')->default(0)->primary();
            $table->string('si197_nrodocumento', 11);
            $table->date('si197_dtinicio')->nullable();
            $table->date('si197_dtfinal')->nullable();
            $table->bigInteger('si197_mes')->nullable();
            $table->bigInteger('si197_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE respinf2025_si197_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE respinf2025 ALTER COLUMN si197_sequencial SET DEFAULT nextval(\'respinf2025_si197_sequencial_seq\');');

        Schema::create('resplic102025', function (Blueprint $table) {
            $table->bigInteger('si55_sequencial')->default(0)->primary();
            $table->bigInteger('si55_tiporegistro')->default(0);
            $table->string('si55_codorgao', 2);
            $table->string('si55_codunidadesub', 8);
            $table->bigInteger('si55_exerciciolicitacao')->default(0);
            $table->string('si55_nroprocessolicitatorio', 12);
            $table->bigInteger('si55_tiporesp')->default(0);
            $table->string('si55_nrocpfresp', 11);
            $table->bigInteger('si55_mes')->default(0);
            $table->bigInteger('si55_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE resplic102025_si55_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE resplic102025 ALTER COLUMN si55_sequencial SET DEFAULT nextval(\'resplic102025_si55_sequencial_seq\');');

        Schema::create('resplic202025', function (Blueprint $table) {
            $table->bigInteger('si56_sequencial')->default(0)->primary();
            $table->bigInteger('si56_tiporegistro')->default(0);
            $table->string('si56_codorgao', 2);
            $table->string('si56_codunidadesub', 8);
            $table->bigInteger('si56_exerciciolicitacao')->default(0);
            $table->string('si56_nroprocessolicitatorio', 12);
            $table->bigInteger('si56_codtipocomissao')->nullable();
            $table->bigInteger('si56_descricaoatonomeacao')->default(0);
            $table->bigInteger('si56_nroatonomeacao')->default(0);
            $table->date('si56_dataatonomeacao');
            $table->date('si56_iniciovigencia');
            $table->date('si56_finalvigencia');
            $table->string('si56_cpfmembrocomissao', 11);
            $table->bigInteger('si56_codatribuicao')->default(0);
            $table->string('si56_cargo', 50);
            $table->bigInteger('si56_naturezacargo')->default(0);
            $table->bigInteger('si56_mes')->default(0);
            $table->bigInteger('si56_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE resplic202025_si56_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE resplic202025 ALTER COLUMN si56_sequencial SET DEFAULT nextval(\'resplic202025_si56_sequencial_seq\');');

        Schema::create('rpsd102025', function (Blueprint $table) {
            $table->bigInteger('si189_sequencial')->default(0)->primary();
            $table->bigInteger('si189_tiporegistro')->default(0);
            $table->bigInteger('si189_codreduzidorsp')->default(0);
            $table->string('si189_codorgao', 2);
            $table->string('si189_codunidadesub', 8);
            $table->string('si189_codunidadesuborig', 8);
            $table->bigInteger('si189_nroempenho')->default(0);
            $table->bigInteger('si189_exercicioempenho')->default(0);
            $table->date('si189_dtempenho');
            $table->bigInteger('si189_tipopagamentorsp')->default(0);
            $table->float('si189_vlpagorsp')->default(0);
            $table->bigInteger('si189_mes')->default(0);
            $table->bigInteger('si189_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE rpsd102025_si189_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rpsd102025 ALTER COLUMN si189_sequencial SET DEFAULT nextval(\'rpsd102025_si189_sequencial_seq\');');

        Schema::create('rsp102025', function (Blueprint $table) {
            $table->bigInteger('si112_sequencial')->default(0)->primary();
            $table->bigInteger('si112_tiporegistro')->default(0);
            $table->bigInteger('si112_codreduzidorsp')->default(0);
            $table->string('si112_codorgao', 2);
            $table->string('si112_codunidadesub', 8);
            $table->string('si112_codunidadesuborig', 8);
            $table->bigInteger('si112_nroempenho')->default(0);
            $table->bigInteger('si112_exercicioempenho')->default(0);
            $table->date('si112_dtempenho');
            $table->string('si112_dotorig', 21)->nullable();
            $table->float('si112_vloriginal')->default(0);
            $table->float('si112_vlsaldoantproce')->default(0);
            $table->float('si112_vlsaldoantnaoproc')->default(0);
            $table->bigInteger('si112_mes')->default(0);
            $table->bigInteger('si112_instit')->default(0)->nullable();
        });
        DB::statement('CREATE SEQUENCE rsp102025_si112_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rsp102025 ALTER COLUMN si112_sequencial SET DEFAULT nextval(\'rsp102025_si112_sequencial_seq\');');

        Schema::create('rsp202025', function (Blueprint $table) {
            $table->bigInteger('si115_sequencial')->default(0)->primary();
            $table->bigInteger('si115_tiporegistro')->default(0);
            $table->bigInteger('si115_codreduzidomov')->default(0);
            $table->string('si115_codorgao', 2);
            $table->string('si115_codunidadesub', 8);
            $table->string('si115_codunidadesuborig', 8);
            $table->bigInteger('si115_nroempenho')->default(0);
            $table->bigInteger('si115_exercicioempenho')->default(0);
            $table->date('si115_dtempenho');
            $table->bigInteger('si115_tiporestospagar')->default(0);
            $table->bigInteger('si115_tipomovimento')->default(0);
            $table->date('si115_dtmovimentacao');
            $table->string('si115_dotorig', 21)->nullable();
            $table->float('si115_vlmovimentacao')->default(0);
            $table->string('si115_codorgaoencampatribuic', 2)->nullable();
            $table->string('si115_codunidadesubencampatribuic', 8)->nullable();
            $table->string('si115_justificativa', 500);
            $table->string('si115_atocancelamento', 20)->nullable();
            $table->date('si115_dataatocancelamento')->nullable();
            $table->bigInteger('si115_mes')->default(0);
            $table->bigInteger('si115_instit')->default(0)->nullable();
        });
        DB::statement('CREATE SEQUENCE rsp202025_si115_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rsp202025 ALTER COLUMN si115_sequencial SET DEFAULT nextval(\'rsp202025_si115_sequencial_seq\');');

        Schema::create('tce102025', function (Blueprint $table) {
            $table->bigInteger('si187_sequencial')->default(0)->primary();
            $table->bigInteger('si187_tiporegistro')->default(0);
            $table->string('si187_numprocessotce', 12);
            $table->date('si187_datainstauracaotce');
            $table->string('si187_codunidadesub', 8);
            $table->string('si187_nroconvenioconge', 30);
            $table->date('si187_dataassinaturaconvoriginalconge');
            $table->string('si187_dscinstrumelegaltce', 50);
            $table->string('si187_nrocpfautoridadeinstauratce', 11);
            $table->string('si187_dsccargoresptce', 50);
            $table->float('si187_vloriginaldano')->default(0);
            $table->float('si187_vlatualizadodano')->default(0);
            $table->date('si187_dataatualizacao');
            $table->string('si187_indice', 20);
            $table->bigInteger('si187_ocorrehipotese')->default(0);
            $table->bigInteger('si187_identiresponsavel')->default(0);
            $table->bigInteger('si187_mes')->default(0);
            $table->bigInteger('si187_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE tce102025_si187_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE tce102025 ALTER COLUMN si187_sequencial SET DEFAULT nextval(\'tce102025_si187_sequencial_seq\');');

        Schema::create('terem102025', function (Blueprint $table) {
            $table->bigInteger('si194_sequencial')->default(0)->primary();
            $table->bigInteger('si194_tiporegistro')->default(0);
            $table->string('si194_cnpj', 14)->nullable();
            $table->bigInteger('si194_codteto')->default(0)->nullable();
            $table->float('si194_vlrparateto')->default(0);
            $table->bigInteger('si194_tipocadastro')->default(0);
            $table->date('si194_dtinicial');
            $table->bigInteger('si194_nrleiteto')->default(0);
            $table->date('si194_dtpublicacaolei');
            $table->date('si194_dtfinal')->nullable();
            $table->string('si194_justalteracao', 250)->nullable();
            $table->bigInteger('si194_mes')->default(0);
            $table->bigInteger('si194_inst')->nullable();
        });
        DB::statement('CREATE SEQUENCE terem102025_si194_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE terem102025 ALTER COLUMN si194_sequencial SET DEFAULT nextval(\'terem102025_si194_sequencial_seq\');');

        Schema::create('terem202025', function (Blueprint $table) {
            $table->bigInteger('si196_sequencial')->default(0)->primary();
            $table->bigInteger('si196_tiporegistro')->default(0);
            $table->bigInteger('si196_codteto')->default(0);
            $table->float('si196_vlrparateto')->default(0);
            $table->bigInteger('si196_nrleiteto')->default(0);
            $table->date('si196_dtpublicacaolei');
            $table->string('si196_justalteracaoteto', 250)->nullable();
            $table->bigInteger('si196_mes')->default(0);
            $table->bigInteger('si196_inst')->nullable();
        });
        DB::statement('CREATE SEQUENCE terem202025_si196_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE terem202025 ALTER COLUMN si196_sequencial SET DEFAULT nextval(\'terem202025_si196_sequencial_seq\');');

        Schema::create('viap102025', function (Blueprint $table) {
            $table->integer('si198_sequencial')->default(0)->primary();
            $table->integer('si198_tiporegistro')->default(0);
            $table->string('si198_nrocpfagentepublico', 11);
            $table->integer('si198_codmatriculapessoa')->default(0);
            $table->integer('si198_codvinculopessoa')->default(0);
            $table->integer('si198_mes')->default(0);
            $table->integer('si198_instit')->nullable();
        });
        DB::statement('CREATE SEQUENCE viap102025_si198_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE viap102025 ALTER COLUMN si198_sequencial SET DEFAULT nextval(\'viap102025_si198_sequencial_seq\');');

        Schema::create('aop122025', function (Blueprint $table) {
            $table->bigInteger('si139_sequencial')->default(0);
            $table->bigInteger('si139_tiporegistro')->default(0);
            $table->bigInteger('si139_codreduzido')->default(0);
            $table->string('si139_tipodocumento', 2);
            $table->string('si139_nrodocumento', 15)->nullable();
            $table->bigInteger('si139_codctb')->nullable();
            $table->bigInteger('si139_codfontectb')->nullable();
            $table->string('si139_desctipodocumentoop', 50)->nullable();
            $table->date('si139_dtemissao');
            $table->float('si139_vldocumento', 8, 2)->default(0);
            $table->bigInteger('si139_mes')->default(0);
            $table->bigInteger('si139_reg10')->default(0);
            $table->bigInteger('si139_instit')->default(0)->nullable();
            $table->primary('si139_sequencial');
            $table->foreign('si139_reg10')->references('si137_sequencial')->on('aop102025');
        });
        DB::statement('
            CREATE SEQUENCE aop122025_si139_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aop122025 ALTER COLUMN si139_sequencial SET DEFAULT nextval(\'aop122025_si139_sequencial_seq\');');

        Schema::create('aop132025', function (Blueprint $table) {
            $table->bigInteger('si140_sequencial')->default(0);
            $table->bigInteger('si140_tiporegistro')->default(0);
            $table->bigInteger('si140_codreduzidoop')->default(0);
            $table->string('si140_tiporetencao', 4);
            $table->string('si140_descricaoretencao', 50)->nullable();
            $table->float('si140_vlretencao', 8, 2)->default(0);
            $table->float('si140_vlantecipado', 8, 2)->default(0);
            $table->bigInteger('si140_mes')->default(0);
            $table->bigInteger('si140_reg10')->default(0);
            $table->bigInteger('si140_instit')->nullable();
            $table->primary('si140_sequencial');
            $table->foreign('si140_reg10')->references('si137_sequencial')->on('aop102025');
        });
        DB::statement('
            CREATE SEQUENCE aop132025_si140_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE aop132025 ALTER COLUMN si140_sequencial SET DEFAULT nextval(\'aop132025_si140_sequencial_seq\');');

        Schema::create('arc112025', function (Blueprint $table) {
            $table->bigInteger('si29_sequencial')->default(0);
            $table->bigInteger('si29_tiporegistro')->default(0);
            $table->bigInteger('si29_codcorrecao')->default(0);
            $table->bigInteger('si29_codfontereduzida')->default(0);
            $table->bigInteger('si29_tipodocumento')->nullable()->default(0);
            $table->string('si29_nrodocumento', 14)->nullable();
            $table->string('si29_nroconvenio', 30)->nullable();
            $table->string('si29_dataassinatura', 8)->nullable();
            $table->float('si29_vlreduzidofonte')->default(0);
            $table->bigInteger('si29_reg10')->default(0);
            $table->bigInteger('si29_mes')->default(0);
            $table->bigInteger('si29_instit')->nullable();
            $table->primary('si29_sequencial');
            $table->foreign('si29_reg10')->references('si28_sequencial')->on('arc102025');
        });
        DB::statement('
            CREATE SEQUENCE arc112025_si29_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE arc112025 ALTER COLUMN si29_sequencial SET DEFAULT nextval(\'arc112025_si29_sequencial_seq\');');
        DB::statement('CREATE INDEX arc112025_si15_reg10_index ON arc112025 USING btree (si29_reg10);');

        Schema::create('arc122025', function (Blueprint $table) {
            $table->bigInteger('si30_sequencial')->default(0);
            $table->bigInteger('si30_tiporegistro')->default(0);
            $table->bigInteger('si30_codcorrecao')->default(0);
            $table->bigInteger('si30_codfonteacrescida')->default(0);
            $table->bigInteger('si30_tipodocumento')->nullable()->default(0);
            $table->string('si30_nrodocumento', 14)->nullable();
            $table->string('si30_nroconvenio', 30)->nullable();
            $table->date('si30_datassinatura')->nullable();
            $table->float('si30_vlacrescidofonte')->default(0);
            $table->bigInteger('si30_reg10')->default(0);
            $table->bigInteger('si30_mes')->default(0);
            $table->bigInteger('si30_instit')->nullable();
            $table->primary('si30_sequencial');
            $table->foreign('si30_reg10')->references('si28_sequencial')->on('arc102025');
        });
        DB::statement('
            CREATE SEQUENCE arc122025_si30_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE arc122025 ALTER COLUMN si30_sequencial SET DEFAULT nextval(\'arc122025_si30_sequencial_seq\');');
        DB::statement('CREATE INDEX arc122025_si30_reg10_index ON arc122025 USING btree (si30_reg10);');

        Schema::create('arc212025', function (Blueprint $table) {
            $table->bigInteger('si32_sequencial')->default(0);
            $table->bigInteger('si32_tiporegistro')->default(0);
            $table->bigInteger('si32_codestorno')->default(0);
            $table->bigInteger('si32_codfonteestornada')->default(0);
            $table->bigInteger('si32_tipodocumento')->nullable();
            $table->string('si32_nrodocumento', 14)->nullable();
            $table->string('si32_nroconvenio', 30)->nullable();
            $table->date('si32_dataassinatura')->nullable();
            $table->float('si32_vlestornadofonte')->default(0);
            $table->bigInteger('si32_reg20')->default(0);
            $table->bigInteger('si32_instit')->nullable();
            $table->bigInteger('si32_mes')->default(0);
            $table->string('si32_nrocontratoop', 30)->nullable();
            $table->date('si32_dataassinaturacontratoop')->nullable();
            $table->string('si32_codigocontroleorcamentario', 4)->default('0000');
            $table->primary('si32_sequencial');
            $table->foreign('si32_reg20')->references('si31_sequencial')->on('arc202025');
        });
        DB::statement('
            CREATE SEQUENCE arc212025_si32_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE arc212025 ALTER COLUMN si32_sequencial SET DEFAULT nextval(\'arc212025_si32_sequencial_seq\');');
        DB::statement('CREATE INDEX arc212025_si32_reg20_index ON arc212025 USING btree (si32_reg20);');

        Schema::create('balancete112025', function (Blueprint $table) {
            $table->bigInteger('si178_sequencial')->default(0);
            $table->bigInteger('si178_tiporegistro')->default(0);
            $table->bigInteger('si178_contacontaabil')->default(0);
            $table->string('si178_codfundo', 8)->default('00000000');
            $table->string('si178_codorgao', 2);
            $table->string('si178_codunidadesub', 8);
            $table->string('si178_codfuncao', 2);
            $table->string('si178_codsubfuncao', 3);
            $table->string('si178_codprograma', 4)->nullable();
            $table->string('si178_idacao', 4);
            $table->string('si178_idsubacao', 4);
            $table->bigInteger('si178_naturezadespesa')->default(0);
            $table->bigInteger('si178_codfontrecursos')->default(0);
            $table->double('si178_saldoinicialcd')->default(0);
            $table->string('si178_naturezasaldoinicialcd', 1);
            $table->double('si178_totaldebitoscd')->default(0);
            $table->double('si178_totalcreditoscd')->default(0);
            $table->double('si178_saldofinalcd')->default(0);
            $table->string('si178_naturezasaldofinalcd', 1);
            $table->bigInteger('si178_mes')->default(0);
            $table->bigInteger('si178_instit')->nullable();
            $table->bigInteger('si178_reg10');
            $table->primary('si178_sequencial');
            $table->foreign('si178_reg10')->references('si177_sequencial')->on('balancete102025');
        });

        DB::statement('
            CREATE SEQUENCE balancete112025_si178_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');

        DB::statement('ALTER TABLE balancete112025 ALTER COLUMN si178_sequencial SET DEFAULT nextval(\'balancete112025_si178_sequencial_seq\');');

        Schema::create('balancete122025', function (Blueprint $table) {
            $table->bigInteger('si179_sequencial')->default(0);
            $table->bigInteger('si179_tiporegistro')->default(0);
            $table->bigInteger('si179_contacontabil')->default(0);
            $table->string('si179_codfundo', 8)->default('00000000');
            $table->bigInteger('si179_naturezareceita')->default(0);
            $table->bigInteger('si179_codfontrecursos')->default(0);
            $table->double('si179_saldoinicialcr')->default(0);
            $table->string('si179_naturezasaldoinicialcr', 1);
            $table->double('si179_totaldebitoscr')->default(0);
            $table->double('si179_totalcreditoscr')->default(0);
            $table->double('si179_saldofinalcr')->default(0);
            $table->string('si179_naturezasaldofinalcr', 1);
            $table->bigInteger('si179_mes')->default(0);
            $table->bigInteger('si179_instit')->nullable();
            $table->bigInteger('si179_reg10');
            $table->primary('si179_sequencial');
            $table->foreign('si179_reg10')->references('si177_sequencial')->on('balancete102025');
        });

        DB::statement('
            CREATE SEQUENCE balancete122025_si179_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');

        DB::statement('ALTER TABLE balancete122025 ALTER COLUMN si179_sequencial SET DEFAULT nextval(\'balancete122025_si179_sequencial_seq\');');

        Schema::create('balancete132025', function (Blueprint $table) {
            $table->bigInteger('si180_sequencial')->default(0);
            $table->bigInteger('si180_tiporegistro')->default(0);
            $table->bigInteger('si180_contacontabil')->default(0);
            $table->string('si180_codfundo', 8)->default('00000000');
            $table->string('si180_codprograma', 4);
            $table->string('si180_idacao', 4);
            $table->string('si180_idsubacao', 4)->nullable();
            $table->float('si180_saldoinicialpa')->default(0);
            $table->string('si180_naturezasaldoinicialpa', 1);
            $table->float('si180_totaldebitospa')->default(0);
            $table->float('si180_totalcreditospa')->default(0);
            $table->float('si180_saldofinalpa')->default(0);
            $table->string('si180_naturezasaldofinalpa', 1);
            $table->bigInteger('si180_mes')->default(0);
            $table->bigInteger('si180_instit')->nullable();
            $table->bigInteger('si180_reg10');
            $table->primary('si180_sequencial');
            $table->foreign('si180_reg10')->references('si177_sequencial')->on('balancete102025');
        });

        DB::statement('
            CREATE SEQUENCE balancete132025_si180_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');

        DB::statement('ALTER TABLE balancete132025 ALTER COLUMN si180_sequencial SET DEFAULT nextval(\'balancete132025_si180_sequencial_seq\');');

        Schema::create('balancete142025', function (Blueprint $table) {
            $table->bigInteger('si181_sequencial')->default(0);
            $table->bigInteger('si181_tiporegistro')->default(0);
            $table->bigInteger('si181_contacontabil')->default(0);
            $table->string('si181_codfundo', 8)->default('00000000');
            $table->string('si181_codorgao', 2);
            $table->string('si181_codunidadesub', 8);
            $table->string('si181_codunidadesuborig', 8);
            $table->string('si181_codfuncao', 2);
            $table->string('si181_codsubfuncao', 3);
            $table->string('si181_codprograma', 4);
            $table->string('si181_idacao', 4);
            $table->string('si181_idsubacao', 4)->nullable();
            $table->bigInteger('si181_naturezadespesa')->default(0);
            $table->string('si181_subelemento', 2);
            $table->bigInteger('si181_codfontrecursos')->default(0);
            $table->bigInteger('si181_codco')->default(0);
            $table->bigInteger('si181_nroempenho')->default(0);
            $table->bigInteger('si181_anoinscricao')->default(0);
            $table->float('si181_saldoinicialrsp')->default(0);
            $table->string('si181_naturezasaldoinicialrsp', 1);
            $table->float('si181_totaldebitosrsp')->default(0);
            $table->float('si181_totalcreditosrsp')->default(0);
            $table->float('si181_saldofinalrsp')->default(0);
            $table->string('si181_naturezasaldofinalrsp', 1);
            $table->bigInteger('si181_mes')->default(0);
            $table->bigInteger('si181_instit')->nullable();
            $table->bigInteger('si181_reg10');
            $table->primary('si181_sequencial');
            $table->foreign('si181_reg10')->references('si177_sequencial')->on('balancete102025');
        });

        DB::statement('
            CREATE SEQUENCE balancete142025_si181_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');

        DB::statement('ALTER TABLE balancete142025 ALTER COLUMN si181_sequencial SET DEFAULT nextval(\'balancete142025_si181_sequencial_seq\');');

        Schema::create('balancete152025', function (Blueprint $table) {
            $table->bigInteger('si182_sequencial')->default(0);
            $table->bigInteger('si182_tiporegistro')->default(0);
            $table->bigInteger('si182_contacontabil')->default(0);
            $table->string('si182_codfundo', 8)->default('00000000');
            $table->bigInteger('si182_naturezareceita')->default(0);
            $table->bigInteger('si182_codfontrecursos')->default(0);
            $table->float('si182_saldoinicialcc')->default(0);
            $table->string('si182_naturezasaldoinicialcc', 1);
            $table->float('si182_totaldebitoscc')->default(0);
            $table->float('si182_totalcreditoscc')->default(0);
            $table->float('si182_saldofinalcc')->default(0);
            $table->string('si182_naturezasaldofinalcc', 1);
            $table->bigInteger('si182_mes')->default(0);
            $table->bigInteger('si182_instit')->nullable();
            $table->bigInteger('si182_reg10');
            $table->primary('si182_sequencial');
            $table->foreign('si182_reg10')->references('si177_sequencial')->on('balancete102025');
        });

        DB::statement('
            CREATE SEQUENCE balancete152025_si182_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');

        DB::statement('ALTER TABLE balancete152025 ALTER COLUMN si182_sequencial SET DEFAULT nextval(\'balancete152025_si182_sequencial_seq\');');

        Schema::create('balancete162025', function (Blueprint $table) {
            $table->bigInteger('si183_sequencial')->default(0);
            $table->bigInteger('si183_tiporegistro')->default(0);
            $table->bigInteger('si183_contacontabil')->default(0);
            $table->string('si183_codfundo', 8)->default('00000000');
            $table->string('si183_atributosf', 1);
            $table->bigInteger('si183_codfontrecursos')->nullable()->default(0);
            $table->bigInteger('si183_codco')->nullable()->default(0);
            $table->double('si183_saldoinicialfontsf')->default(0);
            $table->string('si183_naturezasaldoinicialfontsf', 1);
            $table->double('si183_totaldebitosfontsf')->default(0);
            $table->double('si183_totalcreditosfontsf')->default(0);
            $table->double('si183_saldofinalfontsf')->default(0);
            $table->string('si183_naturezasaldofinalfontsf', 1);
            $table->bigInteger('si183_mes')->default(0);
            $table->bigInteger('si183_instit')->nullable()->default(0);
            $table->bigInteger('si183_reg10');
            $table->primary('si183_sequencial');
            $table->foreign('si183_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete162025_si183_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete162025 ALTER COLUMN si183_sequencial SET DEFAULT nextval(\'balancete162025_si183_sequencial_seq\');');

        Schema::create('balancete172025', function (Blueprint $table) {
            $table->bigInteger('si184_sequencial')->default(0);
            $table->bigInteger('si184_tiporegistro')->default(0);
            $table->bigInteger('si184_contacontabil')->default(0);
            $table->string('si184_codfundo', 8)->default('00000000');
            $table->string('si184_atributosf', 1);
            $table->bigInteger('si184_codctb')->default(0);
            $table->bigInteger('si184_codfontrecursos')->default(0);
            $table->bigInteger('si184_codco')->default(0);
            $table->double('si184_saldoinicialctb')->default(0);
            $table->string('si184_naturezasaldoinicialctb', 1);
            $table->double('si184_totaldebitosctb')->default(0);
            $table->double('si184_totalcreditosctb')->default(0);
            $table->double('si184_saldofinalctb')->default(0);
            $table->string('si184_naturezasaldofinalctb', 1);
            $table->bigInteger('si184_mes')->default(0);
            $table->bigInteger('si184_instit')->nullable()->default(0);
            $table->bigInteger('si184_reg10');
            $table->primary('si184_sequencial');
            $table->foreign('si184_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete172025_si184_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete172025 ALTER COLUMN si184_sequencial SET DEFAULT nextval(\'balancete172025_si184_sequencial_seq\');');

        Schema::create('balancete182025', function (Blueprint $table) {
            $table->bigInteger('si185_sequencial')->default(0);
            $table->bigInteger('si185_tiporegistro')->default(0);
            $table->bigInteger('si185_contacontabil')->default(0);
            $table->string('si185_codfundo', 8)->default('00000000');
            $table->bigInteger('si185_codfontrecursos')->default(0);
            $table->double('si185_saldoinicialfr')->default(0);
            $table->string('si185_naturezasaldoinicialfr', 1);
            $table->double('si185_totaldebitosfr')->default(0);
            $table->double('si185_totalcreditosfr')->default(0);
            $table->double('si185_saldofinalfr')->default(0);
            $table->string('si185_naturezasaldofinalfr', 1);
            $table->bigInteger('si185_mes')->default(0);
            $table->bigInteger('si185_instit')->nullable()->default(0);
            $table->bigInteger('si185_reg10');
            $table->primary('si185_sequencial');
            $table->foreign('si185_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete182025_si185_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete182025 ALTER COLUMN si185_sequencial SET DEFAULT nextval(\'balancete182025_si185_sequencial_seq\');');

        Schema::create('balancete192025', function (Blueprint $table) {
            $table->bigInteger('si186_sequencial')->default(0);
            $table->bigInteger('si186_tiporegistro')->default(0);
            $table->bigInteger('si186_contacontabil')->default(0);
            $table->string('si186_codfundo', 8)->default('00000000');
            $table->bigInteger('si186_codfontrecursos')->default(0);
            $table->double('si186_saldoinicialfr')->default(0);
            $table->string('si186_naturezasaldoinicialfr', 1);
            $table->double('si186_totaldebitosfr')->default(0);
            $table->double('si186_totalcreditosfr')->default(0);
            $table->double('si186_saldofinalfr')->default(0);
            $table->string('si186_naturezasaldofinalfr', 1);
            $table->bigInteger('si186_mes')->default(0);
            $table->bigInteger('si186_instit')->nullable()->default(0);
            $table->bigInteger('si186_reg10');
            $table->primary('si186_sequencial');
            $table->foreign('si186_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete192025_si186_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete192025 ALTER COLUMN si186_sequencial SET DEFAULT nextval(\'balancete192025_si186_sequencial_seq\');');

        Schema::create('balancete202025', function (Blueprint $table) {
            $table->bigInteger('si187_sequencial')->default(0);
            $table->bigInteger('si187_tiporegistro')->default(0);
            $table->bigInteger('si187_contacontabil')->default(0);
            $table->string('si187_codfundo', 8)->default('00000000');
            $table->bigInteger('si187_codfontrecursos')->default(0);
            $table->double('si187_saldoinicialfr')->default(0);
            $table->string('si187_naturezasaldoinicialfr', 1);
            $table->double('si187_totaldebitosfr')->default(0);
            $table->double('si187_totalcreditosfr')->default(0);
            $table->double('si187_saldofinalfr')->default(0);
            $table->string('si187_naturezasaldofinalfr', 1);
            $table->bigInteger('si187_mes')->default(0);
            $table->bigInteger('si187_instit')->nullable()->default(0);
            $table->bigInteger('si187_reg10');
            $table->primary('si187_sequencial');
            $table->foreign('si187_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete202025_si187_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete202025 ALTER COLUMN si187_sequencial SET DEFAULT nextval(\'balancete202025_si187_sequencial_seq\');');

        Schema::create('balancete212025', function (Blueprint $table) {
            $table->bigInteger('si188_sequencial')->default(0);
            $table->bigInteger('si188_tiporegistro')->default(0);
            $table->bigInteger('si188_contacontabil')->default(0);
            $table->string('si188_codfundo', 8)->default('00000000');
            $table->bigInteger('si188_cnpjconsorcio')->default(0);
            $table->bigInteger('si188_codfontrecursos')->default(0);
            $table->double('si188_saldoinicialconsorfr')->default(0);
            $table->string('si188_naturezasaldoinicialconsorfr', 1);
            $table->double('si188_totaldebitosconsorfr')->default(0);
            $table->double('si188_totalcreditosconsorfr')->default(0);
            $table->double('si188_saldofinalconsorfr')->default(0);
            $table->string('si188_naturezasaldofinalconsorfr', 1);
            $table->bigInteger('si188_mes')->default(0);
            $table->bigInteger('si188_instit')->nullable()->default(0);
            $table->bigInteger('si188_reg10');
            $table->primary('si188_sequencial');
            $table->foreign('si188_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete212025_si188_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete212025 ALTER COLUMN si188_sequencial SET DEFAULT nextval(\'balancete212025_si188_sequencial_seq\');');

        Schema::create('balancete222025', function (Blueprint $table) {
            $table->bigInteger('si189_sequencial')->default(0);
            $table->bigInteger('si189_tiporegistro')->default(0);
            $table->bigInteger('si189_contacontabil')->default(0);
            $table->string('si189_codfundo', 8)->default('00000000');
            $table->string('si189_atributosf', 1);
            $table->bigInteger('si189_codctb')->default(0);
            $table->double('si189_saldoinicialctbsf')->default(0);
            $table->string('si189_naturezasaldoinicialctbsf', 1);
            $table->double('si189_totaldebitosctbsf')->default(0);
            $table->double('si189_totalcreditosctbsf')->default(0);
            $table->double('si189_saldofinalctbsf')->default(0);
            $table->string('si189_naturezasaldofinalctbsf', 1);
            $table->bigInteger('si189_mes')->default(0);
            $table->bigInteger('si189_instit')->nullable()->default(0);
            $table->bigInteger('si189_reg10');
            $table->primary('si189_sequencial');
            $table->foreign('si189_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete222025_si189_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete222025 ALTER COLUMN si189_sequencial SET DEFAULT nextval(\'balancete222025_si189_sequencial_seq\');');

        Schema::create('balancete232025', function (Blueprint $table) {
            $table->bigInteger('si190_sequencial')->default(0);
            $table->bigInteger('si190_tiporegistro')->default(0);
            $table->bigInteger('si190_contacontabil')->default(0);
            $table->string('si190_codfundo', 8)->default('00000000');
            $table->bigInteger('si190_naturezareceita')->default(0);
            $table->double('si190_saldoinicialnatreceita')->default(0);
            $table->string('si190_naturezasaldoinicialnatreceita', 1);
            $table->double('si190_totaldebitosnatreceita')->default(0);
            $table->double('si190_totalcreditosnatreceita')->default(0);
            $table->double('si190_saldofinalnatreceita')->default(0);
            $table->string('si190_naturezasaldofinalnatreceita', 1);
            $table->bigInteger('si190_mes')->default(0);
            $table->bigInteger('si190_instit')->nullable()->default(0);
            $table->bigInteger('si190_reg10');
            $table->primary('si190_sequencial');
            $table->foreign('si190_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete232025_si190_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete232025 ALTER COLUMN si190_sequencial SET DEFAULT nextval(\'balancete232025_si190_sequencial_seq\');');

        Schema::create('balancete242025', function (Blueprint $table) {
            $table->bigInteger('si191_sequencial')->default(0);
            $table->bigInteger('si191_tiporegistro')->default(0);
            $table->bigInteger('si191_contacontabil')->default(0);
            $table->string('si191_codfundo', 8)->default('00000000');
            $table->string('si191_codorgao', 2);
            $table->string('si191_codunidadesub', 8);
            $table->double('si191_saldoinicialorgao')->default(0);
            $table->string('si191_naturezasaldoinicialorgao', 1);
            $table->double('si191_totaldebitosorgao')->default(0);
            $table->double('si191_totalcreditosorgao')->default(0);
            $table->double('si191_saldofinalorgao')->default(0);
            $table->string('si191_naturezasaldofinalorgao', 1);
            $table->bigInteger('si191_mes')->default(0);
            $table->bigInteger('si191_instit')->nullable()->default(0);
            $table->bigInteger('si191_reg10');
            $table->primary('si191_sequencial');
            $table->foreign('si191_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete242025_si191_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete242025 ALTER COLUMN si191_sequencial SET DEFAULT nextval(\'balancete242025_si191_sequencial_seq\');');

        Schema::create('balancete252025', function (Blueprint $table) {
            $table->bigInteger('si195_sequencial')->default(0);
            $table->bigInteger('si195_tiporegistro')->default(0);
            $table->bigInteger('si195_contacontabil')->default(0);
            $table->string('si195_codfundo', 8)->default('00000000');
            $table->string('si195_atributosf', 1);
            $table->bigInteger('si195_naturezareceita')->default(0);
            $table->double('si195_saldoinicialnrsf')->default(0);
            $table->string('si195_naturezasaldoinicialnrsf', 1);
            $table->double('si195_totaldebitosnrsf')->default(0);
            $table->double('si195_totalcreditosnrsf')->default(0);
            $table->double('si195_saldofinalnrsf')->default(0);
            $table->string('si195_naturezasaldofinalnrsf', 1);
            $table->bigInteger('si195_mes')->default(0);
            $table->bigInteger('si195_instit')->nullable()->default(0);
            $table->bigInteger('si195_reg10');
            $table->primary('si195_sequencial');
            $table->foreign('si195_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete252025_si195_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete252025 ALTER COLUMN si195_sequencial SET DEFAULT nextval(\'balancete252025_si195_sequencial_seq\');');

        Schema::create('balancete262025', function (Blueprint $table) {
            $table->bigInteger('si196_sequencial')->default(0);
            $table->bigInteger('si196_tiporegistro')->default(0);
            $table->bigInteger('si196_contacontabil')->default(0);
            $table->string('si196_codfundo', 8)->default('00000000');
            $table->bigInteger('si196_tipodocumentopessoaatributosf');
            $table->string('si196_nrodocumentopessoaatributosf', 14);
            $table->string('si196_atributosf', 1);
            $table->double('si196_saldoinicialpessoaatributosf')->default(0);
            $table->string('si196_naturezasaldoinicialpessoaatributosf', 1);
            $table->double('si196_totaldebitospessoaatributosf')->default(0);
            $table->double('si196_totalcreditospessoaatributosf')->default(0);
            $table->double('si196_saldofinalpessoaatributosf')->default(0);
            $table->string('si196_naturezasaldofinalpessoaatributosf', 1);
            $table->bigInteger('si196_mes')->default(0);
            $table->bigInteger('si196_instit')->nullable()->default(0);
            $table->bigInteger('si196_reg10');
            $table->primary('si196_sequencial');
            $table->foreign('si196_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete262025_si196_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete262025 ALTER COLUMN si196_sequencial SET DEFAULT nextval(\'balancete262025_si196_sequencial_seq\');');

        Schema::create('balancete272025', function (Blueprint $table) {
            $table->bigInteger('si197_sequencial')->default(0);
            $table->bigInteger('si197_tiporegistro')->default(0);
            $table->bigInteger('si197_contacontabil')->default(0);
            $table->string('si197_codfundo', 8)->default('00000000');
            $table->string('si197_codorgao', 2);
            $table->string('si197_codunidadesub', 8);
            $table->bigInteger('si197_codfontrecursos');
            $table->string('si197_atributosf', 1);
            $table->double('si197_saldoinicialoufontesf');
            $table->string('si197_naturezasaldoinicialoufontesf', 1);
            $table->double('si197_totaldebitosoufontesf');
            $table->double('si197_totalcreditosoufontesf');
            $table->double('si197_saldofinaloufontesf');
            $table->string('si197_naturezasaldofinaloufontesf', 1);
            $table->bigInteger('si197_mes')->default(0);
            $table->bigInteger('si197_instit')->nullable()->default(0);
            $table->bigInteger('si197_reg10');
            $table->primary('si197_sequencial');
            $table->foreign('si197_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete272025_si197_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete272025 ALTER COLUMN si197_sequencial SET DEFAULT nextval(\'balancete272025_si197_sequencial_seq\');');

        Schema::create('balancete282025', function (Blueprint $table) {
            $table->bigInteger('si198_sequencial')->default(0);
            $table->bigInteger('si198_tiporegistro')->default(0);
            $table->bigInteger('si198_contacontabil')->default(0);
            $table->string('si198_codfundo', 8)->default('00000000');
            $table->bigInteger('si198_codctb');
            $table->bigInteger('si198_codfontrecursos');
            $table->double('si198_saldoinicialctbfonte')->default(0);
            $table->string('si198_naturezasaldoinicialctbfonte', 1);
            $table->double('si198_totaldebitosctbfonte')->default(0);
            $table->double('si198_totalcreditosctbfonte')->default(0);
            $table->double('si198_saldofinalctbfonte')->default(0);
            $table->string('si198_naturezasaldofinalctbfonte', 1);
            $table->bigInteger('si198_mes')->default(0);
            $table->bigInteger('si198_instit')->nullable()->default(0);
            $table->bigInteger('si198_reg10');
            $table->primary('si198_sequencial');
            $table->foreign('si198_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete282025_si198_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete282025 ALTER COLUMN si198_sequencial SET DEFAULT nextval(\'balancete282025_si198_sequencial_seq\');');

        Schema::create('balancete292025', function (Blueprint $table) {
            $table->bigInteger('si241_sequencial')->default(0);
            $table->bigInteger('si241_tiporegistro')->default(0);
            $table->bigInteger('si241_contacontabil')->default(0);
            $table->string('si241_codfundo', 8)->default('00000000');
            $table->string('si241_atributosf', 1);
            $table->bigInteger('si241_codfontrecursos');
            $table->bigInteger('si241_dividaconsolidada')->default(0);
            $table->double('si241_saldoinicialfontsf')->default(0);
            $table->string('si241_naturezasaldoinicialfontsf', 1);
            $table->double('si241_totaldebitosfontsf')->default(0);
            $table->double('si241_totalcreditosfontsf')->default(0);
            $table->double('si241_saldofinalfontsf')->default(0);
            $table->string('si241_naturezasaldofinalfontsf', 1);
            $table->bigInteger('si241_mes')->default(0);
            $table->bigInteger('si241_instit')->nullable()->default(0);
            $table->bigInteger('si241_reg10');
            $table->primary('si241_sequencial');
            $table->foreign('si241_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete292025_si241_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete292025 ALTER COLUMN si241_sequencial SET DEFAULT nextval(\'balancete292025_si241_sequencial_seq\');');

        Schema::create('balancete302025', function (Blueprint $table) {
            $table->bigInteger('si242_sequencial')->default(0);
            $table->bigInteger('si242_tiporegistro')->default(0);
            $table->bigInteger('si242_contacontaabil')->default(0);
            $table->string('si242_codfundo', 8)->default('00000000');
            $table->string('si242_codorgao', 2);
            $table->string('si242_codunidadesub', 8);
            $table->string('si242_codfuncao', 2);
            $table->string('si242_codsubfuncao', 3);
            $table->string('si242_codprograma', 4)->nullable();
            $table->string('si242_idacao', 4);
            $table->string('si242_idsubacao', 4);
            $table->bigInteger('si242_naturezadespesa')->default(0);
            $table->string('si242_subelemento', 2);
            $table->bigInteger('si242_codfontrecursos')->default(0);
            $table->bigInteger('si242_codco')->default(0);
            $table->double('si242_saldoinicialcde')->default(0);
            $table->string('si242_naturezasaldoinicialcde', 1);
            $table->double('si242_totaldebitoscde')->default(0);
            $table->double('si242_totalcreditoscde')->default(0);
            $table->double('si242_saldofinalcde')->default(0);
            $table->string('si242_naturezasaldofinalcde', 1);
            $table->bigInteger('si242_mes')->default(0);
            $table->bigInteger('si242_instit')->nullable()->default(0);
            $table->bigInteger('si242_reg10');
            $table->primary('si242_sequencial');
            $table->foreign('si242_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete302025_si242_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete302025 ALTER COLUMN si242_sequencial SET DEFAULT nextval(\'balancete302025_si242_sequencial_seq\');');

        Schema::create('balancete312025', function (Blueprint $table) {
            $table->bigInteger('si243_sequencial')->default(0);
            $table->bigInteger('si243_tiporegistro')->default(0);
            $table->bigInteger('si243_contacontabil')->default(0);
            $table->string('si243_codfundo', 8)->default('00000000');
            $table->bigInteger('si243_naturezareceita')->default(0);
            $table->bigInteger('si243_codfontrecursos')->default(0);
            $table->bigInteger('si243_codco')->default(0);
            $table->string('si243_nrocontratoop', 30)->nullable();
            $table->date('si243_dataassinaturacontratoop')->nullable();
            $table->double('si243_saldoinicialcre')->default(0);
            $table->string('si243_naturezasaldoinicialcre', 1);
            $table->double('si243_totaldebitoscre')->default(0);
            $table->double('si243_totalcreditoscre')->default(0);
            $table->double('si243_saldofinalcre')->default(0);
            $table->string('si243_naturezasaldofinalcre', 1);
            $table->bigInteger('si243_mes')->default(0);
            $table->bigInteger('si243_instit')->nullable()->default(0);
            $table->bigInteger('si243_reg10');
            $table->primary('si243_sequencial');
            $table->foreign('si243_reg10')->references('si177_sequencial')->on('balancete102025');
        });
        DB::statement('
            CREATE SEQUENCE balancete312025_si243_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1;
        ');
        DB::statement('ALTER TABLE balancete312025 ALTER COLUMN si243_sequencial SET DEFAULT nextval(\'balancete312025_si243_sequencial_seq\');');

        Schema::create('caixa112025', function (Blueprint $table) {
            $table->bigInteger('si166_sequencial')->default(0)->primary();
            $table->bigInteger('si166_tiporegistro')->default(0);
            $table->bigInteger('si166_codfontecaixa')->default(0);
            $table->double('si166_vlsaldoinicialfonte')->default(0);
            $table->double('si166_vlsaldofinalfonte')->default(0);
            $table->bigInteger('si166_mes')->default(0);
            $table->bigInteger('si166_instit')->nullable()->default(0);
            $table->bigInteger('si166_reg10')->default(0);
            $table->foreign('si166_reg10')->references('si103_sequencial')->on('caixa102025');
        });
        DB::statement('CREATE SEQUENCE caixa112025_si166_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE caixa112025 ALTER COLUMN si166_sequencial SET DEFAULT nextval(\'caixa112025_si166_sequencial_seq\');');

        Schema::create('caixa122025', function (Blueprint $table) {
            $table->bigInteger('si104_sequencial')->default(0)->primary();
            $table->bigInteger('si104_tiporegistro')->default(0);
            $table->bigInteger('si104_codreduzido')->default(0);
            $table->bigInteger('si104_codfontecaixa')->default(0);
            $table->bigInteger('si104_tipomovimentacao')->default(0);
            $table->string('si104_tipoentrsaida', 2);
            $table->string('si104_descrmovimentacao', 50)->nullable();
            $table->double('si104_valorentrsaida')->default(0);
            $table->bigInteger('si104_codctbtransf')->nullable()->default(0);
            $table->bigInteger('si104_codfontectbtransf')->nullable()->default(0);
            $table->bigInteger('si104_mes')->default(0);
            $table->bigInteger('si104_reg10')->default(0);
            $table->bigInteger('si104_instit')->nullable()->default(0);
            $table->bigInteger('si104_codidentificafr')->nullable();
            $table->foreign('si104_reg10')->references('si103_sequencial')->on('caixa102025');
        });
        DB::statement('CREATE INDEX caixa122025_si104_reg10_index ON caixa122025 USING btree (si104_reg10);');
        DB::statement('CREATE SEQUENCE caixa122025_si104_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE caixa122025 ALTER COLUMN si104_sequencial SET DEFAULT nextval(\'caixa122025_si104_sequencial_seq\');');

        Schema::create('caixa132025', function (Blueprint $table) {
            $table->bigInteger('si105_sequencial')->default(0)->primary();
            $table->bigInteger('si105_tiporegistro')->default(0);
            $table->bigInteger('si105_codreduzido')->default(0);
            $table->bigInteger('si105_ededucaodereceita')->default(0);
            $table->bigInteger('si105_identificadordeducao')->nullable()->default(0);
            $table->bigInteger('si105_naturezareceita')->default(0);
            $table->bigInteger('si105_codfontecaixa')->default(0);
            $table->double('si105_vlrreceitacont')->default(0);
            $table->bigInteger('si105_mes')->default(0);
            $table->bigInteger('si105_reg10')->default(0);
            $table->bigInteger('si105_instit')->nullable()->default(0);
            $table->bigInteger('si105_codfontcaixa')->nullable()->default(0);
            $table->string('si105_codco', 4)->default('0000')->nullable();
            $table->foreign('si105_reg10')->references('si103_sequencial')->on('caixa102025');
        });
        DB::statement('CREATE INDEX caixa132025_si105_reg10_index ON caixa132025 USING btree (si105_reg10);');
        DB::statement('CREATE SEQUENCE caixa132025_si105_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE caixa132025 ALTER COLUMN si105_sequencial SET DEFAULT nextval(\'caixa132025_si105_sequencial_seq\');');

        Schema::create('conv112025', function (Blueprint $table) {
            $table->bigInteger('si93_sequencial')->default(0)->primary();
            $table->bigInteger('si93_tiporegistro')->default(0);
            $table->bigInteger('si93_codconvenio')->default(0);
            $table->string('si93_nrodocumento', 14)->nullable();
            $table->bigInteger('si93_esferaconcedente')->default(0);
            $table->string('si93_dscexterior', 120)->nullable();
            $table->double('si93_valorconcedido')->default(0);
            $table->bigInteger('si93_mes')->default(0);
            $table->bigInteger('si93_reg10')->default(0);
            $table->bigInteger('si93_instit')->nullable()->default(0);
            $table->foreign('si93_reg10')->references('si92_sequencial')->on('conv102025');
        });
        DB::statement('CREATE INDEX conv112025_si93_reg10_index ON conv112025 USING btree (si93_reg10);');
        DB::statement('CREATE SEQUENCE conv112025_si93_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE conv112025 ALTER COLUMN si93_sequencial SET DEFAULT nextval(\'conv112025_si93_sequencial_seq\');');

        Schema::create('ctb212025', function (Blueprint $table) {
            $table->bigInteger('si97_sequencial')->default(0)->primary();
            $table->bigInteger('si97_tiporegistro')->default(0);
            $table->string('si97_codctb', 255)->default(0);
            $table->bigInteger('si97_codfontrecursos')->default(0);
            $table->string('si97_codreduzidomov', 255)->default(0);
            $table->bigInteger('si97_tipomovimentacao')->default(0);
            $table->string('si97_tipoentrsaida', 2);
            $table->string('si97_dscoutrasmov', 50)->nullable();
            $table->double('si97_valorentrsaida')->default(0);
            $table->bigInteger('si97_codctbtransf')->nullable()->default(0);
            $table->bigInteger('si97_codfontectbtransf')->default(0);
            $table->bigInteger('si97_mes')->default(0);
            $table->bigInteger('si97_reg20')->default(0);
            $table->bigInteger('si97_instit')->nullable()->default(0);
            $table->bigInteger('si97_saldocec')->nullable()->default(0);
            $table->bigInteger('si97_saldocectransf')->nullable()->default(0);
            $table->bigInteger('si97_codidentificafr')->nullable();
            $table->foreign('si97_reg20')->references('si96_sequencial')->on('ctb202025');
        });
        DB::statement('CREATE INDEX ctb212025_si97_reg20_index ON ctb212025 USING btree (si97_reg20);');
        DB::statement('CREATE SEQUENCE ctb212025_si97_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ctb212025 ALTER COLUMN si97_sequencial SET DEFAULT nextval(\'ctb212025_si97_sequencial_seq\');');

        Schema::create('ctb222025', function (Blueprint $table) {
            $table->bigInteger('si98_sequencial')->default(0)->primary();
            $table->bigInteger('si98_tiporegistro')->default(0);
            $table->bigInteger('si98_codreduzidomov')->default(0);
            $table->bigInteger('si98_ededucaodereceita')->default(0);
            $table->bigInteger('si98_identificadordeducao')->nullable()->default(0);
            $table->bigInteger('si98_naturezareceita')->default(0);
            $table->bigInteger('si98_codfontrecursos')->default(0);
            $table->double('si98_vlrreceitacont')->default(0);
            $table->bigInteger('si98_mes')->default(0);
            $table->bigInteger('si98_reg21')->default(0);
            $table->bigInteger('si98_instit')->nullable()->default(0);
            $table->bigInteger('si98_saldocec')->nullable()->default(0);
            $table->string('si98_codco', 4)->default('0000')->nullable();
            $table->foreign('si98_reg21')->references('si97_sequencial')->on('ctb212025');
        });
        DB::statement('CREATE INDEX ctb222025_si98_reg21_index ON ctb222025 USING btree (si98_reg21);');
        DB::statement('CREATE SEQUENCE ctb222025_si98_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ctb222025 ALTER COLUMN si98_sequencial SET DEFAULT nextval(\'ctb222025_si98_sequencial_seq\');');

        Schema::create('ctb312025', function (Blueprint $table) {
            $table->bigInteger('si100_sequencial')->default(0)->primary();
            $table->bigInteger('si100_tiporegistro')->default(0);
            $table->bigInteger('si100_codagentearrecadador')->default(0);
            $table->bigInteger('si100_codfontrecursos')->default(0);
            $table->double('si100_vlsaldoinicialagfonte')->default(0);
            $table->double('si100_vlentradafonte')->default(0);
            $table->double('si100_vlsaidafonte')->default(0);
            $table->double('si100_vlsaldofinalagfonte')->default(0);
            $table->bigInteger('si100_mes')->default(0);
            $table->bigInteger('si100_reg30')->default(0);
            $table->bigInteger('si100_instit')->nullable()->default(0);
            $table->foreign('si100_reg30')->references('si99_sequencial')->on('ctb302025');
        });
        DB::statement('CREATE INDEX ctb312025_si100_reg30_index ON ctb312025 USING btree (si100_reg30);');
        DB::statement('CREATE SEQUENCE ctb312025_si100_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ctb312025 ALTER COLUMN si100_sequencial SET DEFAULT nextval(\'ctb312025_si100_sequencial_seq\');');

        Schema::create('cute212025', function (Blueprint $table) {
            $table->bigInteger('si201_sequencial')->default(0)->primary();
            $table->bigInteger('si201_tiporegistro')->default(0);
            $table->bigInteger('si201_codctb')->default(0);
            $table->bigInteger('si201_codfontrecursos')->default(0);
            $table->bigInteger('si201_tipomovimentacao')->default(0);
            $table->string('si201_tipoentrsaida', 2);
            $table->double('si201_valorentrsaida')->default(0);
            $table->string('si201_codorgaotransf', 2)->nullable();
            $table->bigInteger('si201_reg10')->default(0);
            $table->bigInteger('si201_mes')->default(0);
            $table->bigInteger('si201_instit')->nullable()->default(0);
            $table->foreign('si201_reg10')->references('si199_sequencial')->on('cute102025');
        });
        DB::statement('CREATE INDEX cute212025_si201_reg10_index ON cute212025 USING btree (si201_reg10);');
        DB::statement('CREATE SEQUENCE cute212025_si201_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE cute212025 ALTER COLUMN si201_sequencial SET DEFAULT nextval(\'cute212025_si201_sequencial_seq\');');

        Schema::create('dispensa112025', function (Blueprint $table) {
            $table->bigInteger('si75_sequencial')->default(0)->primary();
            $table->bigInteger('si75_tiporegistro')->default(0);
            $table->string('si75_codorgaoresp', 2)->default(0);
            $table->string('si75_codunidadesubresp', 8);
            $table->bigInteger('si75_exercicioprocesso')->default(0);
            $table->string('si75_nroprocesso', 12);
            $table->bigInteger('si75_tipoprocesso')->default(0);
            $table->bigInteger('si75_nrolote')->default(0);
            $table->string('si75_dsclote', 250);
            $table->bigInteger('si75_mes')->nullable();
            $table->bigInteger('si75_reg10')->default(0);
            $table->bigInteger('si75_instit')->nullable();
            $table->foreign('si75_reg10')->references('si74_sequencial')->on('dispensa102025');
        });
        DB::statement('CREATE INDEX dispensa112025_si75_reg10_index ON dispensa112025 USING btree (si75_reg10);');
        DB::statement('CREATE SEQUENCE dispensa112025_si75_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE dispensa112025 ALTER COLUMN si75_sequencial SET DEFAULT nextval(\'dispensa112025_si75_sequencial_seq\');');

        Schema::create('dispensa122025', function (Blueprint $table) {
            $table->bigInteger('si76_sequencial')->default(0)->primary();
            $table->bigInteger('si76_tiporegistro')->default(0);
            $table->string('si76_codorgaoresp', 2);
            $table->string('si76_codunidadesubresp', 8);
            $table->bigInteger('si76_exercicioprocesso')->default(0);
            $table->string('si76_nroprocesso', 12);
            $table->bigInteger('si76_tipoprocesso');
            $table->bigInteger('si76_coditem')->default(0);
            $table->bigInteger('si76_nroitem')->default(0);
            $table->bigInteger('si76_mes');
            $table->bigInteger('si76_reg10')->default(0);
            $table->bigInteger('si76_instit');
            $table->foreign('si76_reg10')->references('si74_sequencial')->on('dispensa102025');
        });
        DB::statement('CREATE INDEX dispensa122025_si76_reg10_index ON dispensa122025 USING btree (si76_reg10);');
        DB::statement('CREATE SEQUENCE dispensa122025_si76_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE dispensa122025 ALTER COLUMN si76_sequencial SET DEFAULT nextval(\'dispensa122025_si76_sequencial_seq\');');

        Schema::create('dispensa132025', function (Blueprint $table) {
            $table->bigInteger('si77_sequencial')->default(0)->primary();
            $table->bigInteger('si77_tiporegistro')->default(0);
            $table->string('si77_codorgaoresp', 2);
            $table->string('si77_codunidadesubresp', 8);
            $table->bigInteger('si77_exercicioprocesso');
            $table->string('si77_nroprocesso', 12);
            $table->bigInteger('si77_tipoprocesso');
            $table->bigInteger('si77_nrolote');
            $table->bigInteger('si77_coditem');
            $table->bigInteger('si77_mes');
            $table->bigInteger('si77_reg10')->default(0);
            $table->bigInteger('si77_instit');
            $table->foreign('si77_reg10')->references('si74_sequencial')->on('dispensa102025');
        });
        DB::statement('CREATE INDEX dispensa132025_si77_reg10_index ON dispensa132025 USING btree (si77_reg10);');
        DB::statement('CREATE SEQUENCE dispensa132025_si77_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE dispensa132025 ALTER COLUMN si77_sequencial SET DEFAULT nextval(\'dispensa132025_si77_sequencial_seq\');');

        Schema::create('dispensa142025', function (Blueprint $table) {
            $table->bigInteger('si78_sequencial')->default(0)->primary();
            $table->bigInteger('si78_tiporegistro');
            $table->string('si78_codorgaoresp', 2);
            $table->string('si78_codunidadesubres', 8);
            $table->bigInteger('si78_exercicioprocesso');
            $table->string('si78_nroprocesso', 12);
            $table->bigInteger('si78_tipoprocesso');
            $table->bigInteger('si78_tiporesp');
            $table->string('si78_nrocpfresp', 11);
            $table->bigInteger('si78_mes')->nullable();
            $table->bigInteger('si78_reg10')->default(0);
            $table->bigInteger('si78_instit')->nullable();
            $table->foreign('si78_reg10')->references('si74_sequencial')->on('dispensa102025');
        });
        DB::statement('CREATE INDEX dispensa142025_si78_reg10_index ON dispensa142025 USING btree (si78_reg10);');
        DB::statement('CREATE SEQUENCE dispensa142025_si78_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE dispensa142025 ALTER COLUMN si78_sequencial SET DEFAULT nextval(\'dispensa142025_si78_sequencial_seq\');');

        Schema::create('dispensa152025', function (Blueprint $table) {
            $table->bigInteger('si79_sequencial')->default(0)->primary();
            $table->bigInteger('si79_tiporegistro')->default(0);
            $table->string('si79_codorgaoresp', 2);
            $table->string('si79_codunidadesubresp', 8);
            $table->bigInteger('si79_exercicioprocesso');
            $table->string('si79_nroprocesso', 12);
            $table->bigInteger('si79_tipoprocesso');
            $table->bigInteger('si79_nrolote')->nullable();
            $table->bigInteger('si79_coditem');
            $table->double('si79_vlcotprecosunitario');
            $table->double('si79_quantidade');
            $table->bigInteger('si79_mes')->nullable();
            $table->bigInteger('si79_reg10')->default(0);
            $table->bigInteger('si79_instit')->nullable();
            $table->foreign('si79_reg10')->references('si74_sequencial')->on('dispensa102025');
        });
        DB::statement('CREATE INDEX dispensa152025_si79_reg10_index ON dispensa152025 USING btree (si79_reg10);');
        DB::statement('CREATE SEQUENCE dispensa152025_si79_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE dispensa152025 ALTER COLUMN si79_sequencial SET DEFAULT nextval(\'dispensa152025_si79_sequencial_seq\');');

        Schema::create('dispensa162025', function (Blueprint $table) {
            $table->bigInteger('si80_sequencial')->default(0)->primary();
            $table->bigInteger('si80_tiporegistro');
            $table->string('si80_codorgaoresp', 2);
            $table->string('si80_codunidadesubresp', 8);
            $table->bigInteger('si80_exercicioprocesso');
            $table->string('si80_nroprocesso', 12);
            $table->bigInteger('si80_tipoprocesso');
            $table->string('si80_codorgao', 2);
            $table->string('si80_codunidadesub', 8);
            $table->string('si80_codfuncao', 2);
            $table->string('si80_codsubfuncao', 3);
            $table->string('si80_codprograma', 4);
            $table->string('si80_idacao', 4);
            $table->string('si80_idsubacao', 4)->nullable();
            $table->bigInteger('si80_naturezadespesa');
            $table->bigInteger('si80_codfontrecursos');
            $table->double('si80_vlrecurso');
            $table->bigInteger('si80_mes')->nullable();
            $table->bigInteger('si80_reg10')->default(0);
            $table->bigInteger('si80_instit')->nullable();
            $table->foreign('si80_reg10')->references('si74_sequencial')->on('dispensa102025');
        });
        DB::statement('CREATE INDEX dispensa162025_si80_reg10_index ON dispensa162025 USING btree (si80_reg10);');
        DB::statement('CREATE SEQUENCE dispensa162025_si80_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE dispensa162025 ALTER COLUMN si80_sequencial SET DEFAULT nextval(\'dispensa162025_si80_sequencial_seq\');');

        Schema::create('dispensa172025', function (Blueprint $table) {
            $table->bigInteger('si81_sequencial')->default(0)->primary();
            $table->bigInteger('si81_tiporegistro')->default(0);
            $table->string('si81_codorgaoresp', 2);
            $table->string('si81_codunidadesubresp', 8);
            $table->bigInteger('si81_exercicioprocesso')->default(0);
            $table->string('si81_nroprocesso', 12);
            $table->bigInteger('si81_tipoprocesso')->default(0);
            $table->bigInteger('si81_tipodocumento')->default(0);
            $table->string('si81_nrodocumento', 14);
            $table->string('si81_nroinscricaoestadual', 30)->nullable();
            $table->string('si81_ufinscricaoestadual', 2)->nullable();
            $table->string('si81_nrocertidaoregularidadeinss', 30)->nullable();
            $table->date('si81_dtemissaocertidaoregularidadeinss')->nullable();
            $table->date('si81_dtvalidadecertidaoregularidadeinss')->nullable();
            $table->string('si81_nrocertidaoregularidadefgts', 30)->nullable();
            $table->date('si81_dtemissaocertidaoregularidadefgts')->nullable();
            $table->date('si81_dtvalidadecertidaoregularidadefgts')->nullable();
            $table->string('si81_nrocndt', 30)->nullable();
            $table->date('si81_dtemissaocndt')->nullable();
            $table->date('si81_dtvalidadecndt')->nullable();
            $table->bigInteger('si81_nrolote')->nullable();
            $table->bigInteger('si81_coditem');
            $table->double('si81_quantidade')->default(0);
            $table->double('si81_vlitem')->default(0);
            $table->bigInteger('si81_mes')->default(0);
            $table->bigInteger('si81_reg10')->default(0);
            $table->bigInteger('si81_instit')->nullable();
            $table->foreign('si81_reg10')->references('si74_sequencial')->on('dispensa102025');
        });
        DB::statement('CREATE INDEX dispensa172025_si81_reg10_index ON dispensa172025 USING btree (si81_reg10);');
        DB::statement('CREATE SEQUENCE dispensa172025_si81_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE dispensa172025 ALTER COLUMN si81_sequencial SET DEFAULT nextval(\'dispensa172025_si81_sequencial_seq\');');

        Schema::create('ext312025', function (Blueprint $table) {
            $table->bigInteger('si127_sequencial')->default(0)->primary();
            $table->bigInteger('si127_tiporegistro')->default(0);
            $table->bigInteger('si127_codreduzidoop')->default(0);
            $table->string('si127_tipodocumentoop', 2);
            $table->string('si127_nrodocumento', 15)->nullable();
            $table->bigInteger('si127_codctb')->nullable()->default(0);
            $table->bigInteger('si127_codfontectb')->nullable()->default(0);
            $table->string('si127_desctipodocumentoop', 50)->nullable();
            $table->date('si127_dtemissao');
            $table->double('si127_vldocumento')->default(0);
            $table->bigInteger('si127_mes')->default(0);
            $table->bigInteger('si127_reg30')->default(0);
            $table->bigInteger('si127_instit')->default(0);
            $table->foreign('si127_reg30')->references('si126_sequencial')->on('ext302025');
        });
        DB::statement('CREATE SEQUENCE ext312025_si127_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ext312025 ALTER COLUMN si127_sequencial SET DEFAULT nextval(\'ext312025_si127_sequencial_seq\');');
        DB::statement('CREATE INDEX ext312025_si127_reg30_index ON ext312025 USING btree (si127_reg30);');

        Schema::create('ext322025', function (Blueprint $table) {
            $table->bigInteger('si128_sequencial')->default(0)->primary();
            $table->bigInteger('si128_tiporegistro')->default(0);
            $table->bigInteger('si128_codreduzidoop')->default(0);
            $table->string('si128_tiporetencao', 4);
            $table->string('si128_descricaoretencao', 50)->nullable();
            $table->double('si128_vlretencao')->default(0);
            $table->bigInteger('si128_mes')->default(0);
            $table->bigInteger('si128_reg30')->default(0);
            $table->bigInteger('si128_instit')->nullable()->default(0);
            $table->foreign('si128_reg30')->references('si126_sequencial')->on('ext302025');

        });
        DB::statement('CREATE SEQUENCE ext322025_si128_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ext322025 ALTER COLUMN si128_sequencial SET DEFAULT nextval(\'ext322025_si128_sequencial_seq\');');
        DB::statement('CREATE INDEX ext322025_si128_reg20_index ON ext322025 USING btree (si128_reg30);');

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
        DB::statement('CREATE SEQUENCE flpgo112025_si196_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE flpgo112025 ALTER COLUMN si196_sequencial SET DEFAULT nextval(\'flpgo112025_si196_sequencial_seq\');');

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
        DB::statement('CREATE SEQUENCE flpgo122025_si197_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE flpgo122025 ALTER COLUMN si197_sequencial SET DEFAULT nextval(\'flpgo122025_si197_sequencial_seq\');');

        Schema::create('hablic112025', function (Blueprint $table) {
            $table->bigInteger('si58_sequencial')->default(0)->primary();
            $table->bigInteger('si58_tiporegistro')->default(0);
            $table->string('si58_codorgao', 2);
            $table->string('si58_codunidadesub', 8);
            $table->bigInteger('si58_exerciciolicitacao')->default(0);
            $table->string('si58_nroprocessolicitatorio', 12);
            $table->bigInteger('si58_tipodocumentocnpjempresahablic')->default(0);
            $table->string('si58_cnpjempresahablic', 14);
            $table->bigInteger('si58_tipodocumentosocio')->default(0);
            $table->string('si58_nrodocumentosocio', 14);
            $table->bigInteger('si58_tipoparticipacao')->default(0);
            $table->bigInteger('si58_mes')->default(0);
            $table->bigInteger('si58_reg10')->default(0);
            $table->bigInteger('si58_instit')->nullable();
            $table->foreign('si58_reg10')->references('si57_sequencial')->on('hablic102025');
        });
        DB::statement('CREATE SEQUENCE hablic112025_si58_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE hablic112025 ALTER COLUMN si58_sequencial SET DEFAULT nextval(\'hablic112025_si58_sequencial_seq\');');
        DB::statement('CREATE INDEX hablic112025_si58_reg10_index ON hablic112025 USING btree (si58_mes);');

        Schema::create('lao112025', function (Blueprint $table) {
            $table->bigInteger('si35_sequencial')->default(0)->primary();
            $table->bigInteger('si35_tiporegistro')->default(0);
            $table->bigInteger('si35_nroleialteracao');
            $table->bigInteger('si35_tipoleialteracao')->default(0);
            $table->string('si35_artigoleialteracao', 6);
            $table->string('si35_descricaoartigo', 512);
            $table->float('si35_vlautorizadoalteracao', 8, 2)->default(0);
            $table->bigInteger('si35_mes')->default(0);
            $table->bigInteger('si35_reg10')->default(0);
            $table->bigInteger('si35_instit')->nullable()->default(0);
            $table->foreign('si35_reg10')->references('si34_sequencial')->on('lao102025');
        });
        DB::statement('CREATE SEQUENCE lao112025_si35_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE lao112025 ALTER COLUMN si35_sequencial SET DEFAULT nextval(\'lao112025_si35_sequencial_seq\');');
        DB::statement('CREATE INDEX lao112025_si35_reg10_index ON lao112025 USING btree (si35_reg10);');

        Schema::create('lao212025', function (Blueprint $table) {
            $table->bigInteger('si37_sequencial')->default(0)->primary();
            $table->bigInteger('si37_tiporegistro')->default(0);
            $table->bigInteger('si37_nroleialterorcam');
            $table->bigInteger('si37_tipoautorizacao')->default(0);
            $table->string('si37_artigoleialterorcamento', 6);
            $table->string('si37_descricaoartigo', 512);
            $table->float('si37_novopercentual', 8, 2)->default(0);
            $table->bigInteger('si37_mes')->default(0);
            $table->bigInteger('si37_reg20')->default(0);
            $table->bigInteger('si37_instit')->nullable()->default(0);
            $table->foreign('si37_reg20')->references('si36_sequencial')->on('lao202025');
        });
        DB::statement('CREATE SEQUENCE lao212025_si37_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE lao212025 ALTER COLUMN si37_sequencial SET DEFAULT nextval(\'lao212025_si37_sequencial_seq\');');
        DB::statement('CREATE INDEX lao212025_si37_reg20_index ON lao212025 USING btree (si37_reg20);');

        Schema::create('lqd112025', function (Blueprint $table) {
            $table->bigInteger('si119_sequencial')->default(0)->primary();
            $table->bigInteger('si119_tiporegistro')->default(0);
            $table->bigInteger('si119_codreduzido')->default(0);
            $table->bigInteger('si119_codfontrecursos')->default(0);
            $table->float('si119_valorfonte', 8, 2)->default(0);
            $table->bigInteger('si119_mes')->default(0);
            $table->bigInteger('si119_reg10')->default(0);
            $table->bigInteger('si119_instit')->nullable()->default(0);
            $table->string('si119_codco', 4)->default('0000')->nullable();
            $table->foreign('si119_reg10')->references('si118_sequencial')->on('lqd102025');
        });
        DB::statement('CREATE SEQUENCE lqd112025_si119_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE lqd112025 ALTER COLUMN si119_sequencial SET DEFAULT nextval(\'lqd112025_si119_sequencial_seq\');');
        DB::statement('CREATE INDEX lqd112025_si119_reg10_index ON lqd112025 USING btree (si119_reg10);');

        Schema::create('lqd122025', function (Blueprint $table) {
            $table->bigInteger('si120_sequencial')->default(0)->primary();
            $table->bigInteger('si120_tiporegistro')->default(0);
            $table->bigInteger('si120_codreduzido')->default(0);
            $table->string('si120_mescompetencia', 2);
            $table->bigInteger('si120_exerciciocompetencia')->default(0);
            $table->float('si120_vldspexerant', 8, 2)->default(0);
            $table->bigInteger('si120_mes')->default(0);
            $table->bigInteger('si120_reg10')->default(0);
            $table->bigInteger('si120_instit')->nullable();
            $table->foreign('si120_reg10')->references('si118_sequencial')->on('lqd102025');
        });
        DB::statement('CREATE SEQUENCE lqd122025_si120_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE lqd122025 ALTER COLUMN si120_sequencial SET DEFAULT nextval(\'lqd122025_si120_sequencial_seq\');');
        DB::statement('CREATE INDEX lqd122025_si120_reg10_index ON lqd122025 USING btree (si120_reg10);');

        Schema::create('ntf112025', function (Blueprint $table) {
            $table->bigInteger('si144_sequencial')->default(0)->primary();
            $table->bigInteger('si144_tiporegistro')->default(0);
            $table->bigInteger('si144_codnotafiscal')->default(0);
            $table->bigInteger('si144_coditem')->default(0);
            $table->float('si144_quantidadeitem', 8, 2)->default(0);
            $table->float('si144_valorunitarioitem', 8, 2)->default(0);
            $table->bigInteger('si144_mes')->default(0);
            $table->bigInteger('si144_reg10')->default(0);
            $table->bigInteger('si144_instit')->nullable();
            $table->foreign('si144_reg10')->references('si143_sequencial')->on('ntf102025');
        });
        DB::statement('CREATE SEQUENCE ntf112025_si144_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ntf112025 ALTER COLUMN si144_sequencial SET DEFAULT nextval(\'ntf112025_si144_sequencial_seq\');');
        DB::statement('CREATE INDEX ntf112025_si144_reg10_index ON ntf112025 USING btree (si144_reg10);');

        Schema::create('obelac112025', function (Blueprint $table) {
            $table->bigInteger('si140_sequencial')->default(0)->primary();
            $table->bigInteger('si140_tiporegistro')->default(0);
            $table->bigInteger('si140_codreduzido')->default(0);
            $table->bigInteger('si140_codfontrecursos')->default(0);
            $table->float('si140_valorfonte', 8, 2)->default(0);
            $table->bigInteger('si140_mes')->default(0);
            $table->bigInteger('si140_reg10')->default(0);
            $table->bigInteger('si140_instit')->nullable();
            $table->foreign('si140_reg10')->references('si120_sequencial')->on('lqd122025');
        });
        DB::statement('CREATE SEQUENCE obelac112025_si140_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE obelac112025 ALTER COLUMN si140_sequencial SET DEFAULT nextval(\'obelac112025_si140_sequencial_seq\');');
        DB::statement('CREATE INDEX obelac112025_si140_reg10_index ON obelac112025 USING btree (si140_reg10);');

        Schema::create('ops112025', function (Blueprint $table) {
            $table->bigInteger('si133_sequencial')->default(0)->primary();
            $table->bigInteger('si133_tiporegistro')->default(0);
            $table->bigInteger('si133_codreduzidoop')->default(0);
            $table->string('si133_codunidadesub', 8);
            $table->bigInteger('si133_nroop')->default(0);
            $table->date('si133_dtpagamento');
            $table->bigInteger('si133_tipopagamento')->default(0);
            $table->bigInteger('si133_nroempenho')->default(0);
            $table->date('si133_dtempenho');
            $table->bigInteger('si133_nroliquidacao')->nullable();
            $table->date('si133_dtliquidacao')->nullable();
            $table->bigInteger('si133_codfontrecursos')->default(0);
            $table->float('si133_valorfonte', 8, 2)->default(0);
            $table->bigInteger('si133_tipodocumentocredor')->nullable();
            $table->string('si133_nrodocumento', 14)->nullable();
            $table->string('si133_codorgaoempop', 2)->nullable();
            $table->string('si133_codunidadeempop', 8)->nullable();
            $table->bigInteger('si133_mes')->default(0);
            $table->bigInteger('si133_reg10')->default(0);
            $table->bigInteger('si133_instit')->nullable();
            $table->string('si133_codco', 4)->default('0000');
            $table->foreign('si133_reg10')->references('si132_sequencial')->on('ops102025');
        });
        DB::statement('CREATE SEQUENCE ops112025_si133_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ops112025 ALTER COLUMN si133_sequencial SET DEFAULT nextval(\'ops112025_si133_sequencial_seq\');');
        DB::statement('CREATE INDEX ops112025_si133_reg10_index ON ops112025 USING btree (si133_reg10);');

        Schema::create('ops122025', function (Blueprint $table) {
            $table->bigInteger('si134_sequencial')->default(0)->primary();
            $table->bigInteger('si134_tiporegistro')->default(0);
            $table->bigInteger('si134_codreduzidoop')->default(0);
            $table->string('si134_tipodocumentoop', 2);
            $table->string('si134_nrodocumento', 15)->nullable();
            $table->bigInteger('si134_codctb')->nullable()->default(0);
            $table->bigInteger('si134_codfontectb')->nullable()->default(0);
            $table->string('si134_desctipodocumentoop', 50)->nullable();
            $table->date('si134_dtemissao');
            $table->double('si134_vldocumento')->default(0);
            $table->bigInteger('si134_mes')->default(0);
            $table->bigInteger('si134_reg10')->default(0);
            $table->bigInteger('si134_instit')->nullable()->default(0);
            $table->foreign('si134_reg10')->references('si132_sequencial')->on('ops102025');
        });

        DB::statement('CREATE INDEX ops122025_si134_reg10_index ON ops122025 USING btree (si134_reg10);');
        DB::statement('CREATE SEQUENCE ops122025_si134_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ops122025 ALTER COLUMN si134_sequencial SET DEFAULT nextval(\'ops122025_si134_sequencial_seq\');');

        Schema::create('ops132025', function (Blueprint $table) {
            $table->bigInteger('si135_sequencial')->default(0)->primary();
            $table->bigInteger('si135_tiporegistro')->default(0);
            $table->bigInteger('si135_codreduzidoop')->default(0);
            $table->string('si135_tiporetencao', 4);
            $table->string('si135_descricaoretencao', 50)->nullable();
            $table->double('si135_vlretencao')->default(0);
            $table->double('si135_vlantecipado')->default(0);
            $table->bigInteger('si135_mes')->default(0);
            $table->bigInteger('si135_reg10')->default(0);
            $table->bigInteger('si135_instit')->default(0)->nullable();
            $table->foreign('si135_reg10')->references('si132_sequencial')->on('ops102025');
        });

        DB::statement('CREATE INDEX ops132025_si135_reg10_index ON ops132025 USING btree (si135_reg10);');
        DB::statement('CREATE SEQUENCE ops132025_si135_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ops132025 ALTER COLUMN si135_sequencial SET DEFAULT nextval(\'ops132025_si135_sequencial_seq\');');

        Schema::create('orgao112025', function (Blueprint $table) {
            $table->bigInteger('si15_sequencial')->default(0)->primary();
            $table->bigInteger('si15_tiporegistro')->default(0);
            $table->string('si15_tiporesponsavel', 2);
            $table->string('si15_cartident', 10);
            $table->string('si15_orgemissorci', 10);
            $table->string('si15_cpf', 11);
            $table->string('si15_crccontador', 11)->nullable();
            $table->string('si15_ufcrccontador', 2)->nullable();
            $table->string('si15_cargoorddespdeleg', 50)->nullable();
            $table->date('si15_dtinicio');
            $table->date('si15_dtfinal');
            $table->string('si15_email', 50);
            $table->bigInteger('si15_reg10')->default(0);
            $table->bigInteger('si15_mes')->default(0);
            $table->bigInteger('si15_instit')->nullable()->default(0);
            $table->bigInteger('si15_numerotelefone')->nullable();
            $table->foreign('si15_reg10')->references('si14_sequencial')->on('orgao102025');
        });

        DB::statement('CREATE INDEX orgao112025_si15_reg10_index ON orgao112025 USING btree (si15_reg10);');
        DB::statement('CREATE SEQUENCE orgao112025_si15_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE orgao112025 ALTER COLUMN si15_sequencial SET DEFAULT nextval(\'orgao112025_si15_sequencial_seq\');');

        Schema::create('parec112025', function (Blueprint $table) {
            $table->bigInteger('si23_sequencial')->default(0)->primary();
            $table->bigInteger('si23_tiporegistro')->default(0);
            $table->bigInteger('si23_codreduzido')->default(0);
            $table->bigInteger('si23_codfontrecursos')->default(0);
            $table->double('si23_vlfonte')->default(0);
            $table->bigInteger('si23_reg10')->default(0);
            $table->bigInteger('si23_mes')->default(0);
            $table->bigInteger('si23_instit')->nullable()->default(0);
            $table->foreign('si23_reg10')->references('si22_sequencial')->on('parec102025');
        });

        DB::statement('CREATE INDEX parec112025_si23_reg10_index ON parec112025 USING btree (si23_reg10);');
        DB::statement('CREATE SEQUENCE parec112025_si23_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE parec112025 ALTER COLUMN si23_sequencial SET DEFAULT nextval(\'parec112025_si23_sequencial_seq\');');

        Schema::create('ralic112025', function (Blueprint $table) {
            $table->bigInteger('si181_sequencial')->default(0)->primary();
            $table->bigInteger('si181_tiporegistro')->default(0);
            $table->string('si181_codorgaoresp', 3);
            $table->string('si181_codunidadesubresp', 8)->nullable();
            $table->string('si181_codunidadesubrespestadual', 4)->nullable();
            $table->smallInteger('si181_exerciciolicitacao');
            $table->string('si181_nroprocessolicitatorio', 12);
            $table->bigInteger('si181_codobralocal')->nullable();
            $table->smallInteger('si181_classeobjeto');
            $table->smallInteger('si181_tipoatividadeobra')->nullable();
            $table->smallInteger('si181_tipoatividadeservico')->nullable();
            $table->string('si181_dscatividadeservico', 150)->nullable();
            $table->smallInteger('si181_tipoatividadeservespecializado')->nullable();
            $table->string('si181_dscatividadeservespecializado', 150)->nullable();
            $table->string('si181_codfuncao', 2);
            $table->string('si181_codsubfuncao', 3);
            $table->smallInteger('si181_codbempublico')->nullable();
            $table->bigInteger('si181_reg10')->default(0);
            $table->bigInteger('si181_mes')->default(0);
            $table->bigInteger('si181_instit')->nullable()->default(0);
            $table->bigInteger('si181_nrolote')->nullable();
            $table->bigInteger('si181_utilizacaoplanilhamodelo')->nullable();
            $table->foreign('si181_reg10')->references('si180_sequencial')->on('ralic102025');
        });
        DB::statement('CREATE SEQUENCE ralic112025_si181_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ralic112025 ALTER COLUMN si181_sequencial SET DEFAULT nextval(\'ralic112025_si181_sequencial_seq\');');

        Schema::create('ralic122025', function (Blueprint $table) {
            $table->bigInteger('si182_sequencial');
            $table->bigInteger('si182_tiporegistro')->default(0);
            $table->string('si182_codorgaoresp', 3);
            $table->string('si182_codunidadesubresp', 8)->nullable();
            $table->char('si182_codunidadesubrespestadual', 4)->nullable();
            $table->smallInteger('si182_exercicioprocesso')->default(0);
            $table->string('si182_nroprocessolicitatorio', 12);
            $table->bigInteger('si182_codobralocal')->nullable();
            $table->string('si182_logradouro', 100);
            $table->smallInteger('si182_numero');
            $table->string('si182_bairro', 100)->nullable();
            $table->string('si182_distrito', 100)->nullable();
            $table->string('si182_municipio', 50);
            $table->bigInteger('si182_cep');
            $table->bigInteger('si182_reg10')->default(0);
            $table->bigInteger('si182_mes')->default(0);
            $table->bigInteger('si182_instit')->nullable();
            $table->decimal('si182_latitude', 10, 6)->nullable();
            $table->decimal('si182_longitude', 10, 6)->nullable();
            $table->integer('si182_nrolote')->nullable();
            $table->smallInteger('si182_codbempublico')->nullable();
            $table->primary('si182_sequencial');
            $table->foreign('si182_reg10')->references('si180_sequencial')->on('ralic102025');
        });
        DB::statement('CREATE SEQUENCE ralic122025_si182_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE ralic122025 ALTER COLUMN si182_sequencial SET DEFAULT nextval(\'ralic122025_si182_sequencial_seq\');');

        Schema::create('rec112025', function (Blueprint $table) {
            $table->bigInteger('si26_sequencial');
            $table->bigInteger('si26_tiporegistro')->default(0);
            $table->bigInteger('si26_codreceita')->default(0);
            $table->bigInteger('si26_codfontrecursos')->default(0);
            $table->bigInteger('si26_tipodocumento')->nullable();
            $table->string('si26_nrodocumento', 14)->nullable();
            $table->string('si26_nroconvenio', 30)->nullable();
            $table->date('si26_dataassinatura')->nullable();
            $table->float('si26_vlarrecadadofonte')->default(0);
            $table->bigInteger('si26_reg10')->default(0);
            $table->bigInteger('si26_mes')->default(0);
            $table->bigInteger('si26_instit')->nullable();
            $table->string('si26_nrocontratoop', 30)->nullable();
            $table->date('si26_dataassinaturacontratoop')->nullable();
            $table->string('si26_codigocontroleorcamentario')->nullable();
            $table->primary('si26_sequencial');
            $table->foreign('si26_reg10')->references('si25_sequencial')->on('rec102025');
        });
        DB::statement('CREATE INDEX rec112025_si26_reg10_index ON rec112025 USING btree (si26_reg10);');
        DB::statement('CREATE SEQUENCE rec112025_si26_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rec112025 ALTER COLUMN si26_sequencial SET DEFAULT nextval(\'rec112025_si26_sequencial_seq\');');

        Schema::create('redispi112025', function (Blueprint $table) {
            $table->bigInteger('si184_sequencial');
            $table->bigInteger('si184_tiporegistro')->default(0);
            $table->string('si184_codorgaoresp', 3);
            $table->string('si184_codunidadesubresp', 8)->nullable();
            $table->char('si184_codunidadesubrespestadual', 4)->nullable();
            $table->smallInteger('si184_exercicioprocesso');
            $table->string('si184_nroprocesso', 12);
            $table->bigInteger('si184_codobralocal')->nullable();
            $table->smallInteger('si184_tipoprocesso');
            $table->smallInteger('si184_classeobjeto');
            $table->smallInteger('si184_tipoatividadeobra')->nullable();
            $table->smallInteger('si184_tipoatividadeservico')->nullable();
            $table->string('si184_dscatividadeservico', 150)->nullable();
            $table->smallInteger('si184_tipoatividadeservespecializado')->nullable();
            $table->string('si184_dscatividadeservespecializado', 150)->nullable();
            $table->char('si184_codfuncao', 2);
            $table->char('si184_codsubfuncao', 3);
            $table->smallInteger('si184_codbempublico');
            $table->bigInteger('si184_reg10')->default(0);
            $table->bigInteger('si184_mes')->default(0);
            $table->bigInteger('si184_instit')->nullable();
            $table->bigInteger('si184_utilizacaoplanilhamodelo')->nullable();
            $table->primary('si184_sequencial');
            $table->foreign('si184_reg10')->references('si183_sequencial')->on('redispi102025');
        });
        DB::statement('CREATE SEQUENCE redispi112025_si184_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE redispi112025 ALTER COLUMN si184_sequencial SET DEFAULT nextval(\'redispi112025_si184_sequencial_seq\');');

        Schema::create('redispi122025', function (Blueprint $table) {
            $table->bigInteger('si185_sequencial');
            $table->bigInteger('si185_tiporegistro')->default(0);
            $table->char('si185_codorgaoresp', 3);
            $table->string('si185_codunidadesubresp', 8)->nullable();
            $table->char('si185_codunidadesubrespestadual', 4)->nullable();
            $table->smallInteger('si185_exercicioprocesso')->default(0);
            $table->char('si185_nroprocesso', 12);
            $table->bigInteger('si185_codobralocal')->nullable();
            $table->string('si185_logradouro', 100);
            $table->smallInteger('si185_numero')->nullable();
            $table->string('si185_bairro', 100)->nullable();
            $table->string('si185_distrito', 100)->nullable();
            $table->string('si185_cidade', 100);
            $table->char('si185_cep', 8);
            $table->bigInteger('si185_reg10')->default(0);
            $table->bigInteger('si185_mes')->default(0);
            $table->bigInteger('si185_instit')->nullable();
            $table->decimal('si185_latitude', 10, 6)->nullable();
            $table->decimal('si185_longitude', 10, 6)->nullable();
            $table->integer('si185_codbempublico')->nullable();
            $table->primary('si185_sequencial');
            $table->foreign('si185_reg10')->references('si183_sequencial')->on('redispi102025');
        });
        DB::statement('CREATE SEQUENCE redispi122025_si185_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE redispi122025 ALTER COLUMN si185_sequencial SET DEFAULT nextval(\'redispi122025_si185_sequencial_seq\');');

        Schema::create('rpsd112025', function (Blueprint $table) {
            $table->bigInteger('si190_sequencial');
            $table->bigInteger('si190_tiporegistro')->default(0);
            $table->bigInteger('si190_codreduzidorsp')->default(0);
            $table->bigInteger('si190_codfontrecursos')->default(0);
            $table->float('si190_vlpagofontersp')->default(0);
            $table->bigInteger('si190_reg10')->default(0);
            $table->bigInteger('si190_mes')->default(0);
            $table->bigInteger('si190_instit')->nullable();
            $table->string('si190_codco', 4)->nullable();
            $table->primary('si190_sequencial');
            $table->foreign('si190_reg10')->references('si189_sequencial')->on('rpsd102025');
        });
        DB::statement('CREATE SEQUENCE rpsd112025_si190_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rpsd112025 ALTER COLUMN si190_sequencial SET DEFAULT nextval(\'rpsd112025_si190_sequencial_seq\');');

        Schema::create('rsp112025', function (Blueprint $table) {
            $table->bigInteger('si113_sequencial');
            $table->bigInteger('si113_tiporegistro')->default(0);
            $table->bigInteger('si113_codreduzidorsp')->default(0);
            $table->bigInteger('si113_codfontrecursos')->default(0);
            $table->float('si113_vloriginalfonte')->default(0);
            $table->float('si113_vlsaldoantprocefonte')->default(0);
            $table->float('si113_vlsaldoantnaoprocfonte')->default(0);
            $table->bigInteger('si113_mes')->default(0);
            $table->bigInteger('si113_reg10')->default(0);
            $table->bigInteger('si113_instit')->nullable();
            $table->string('si113_codco');
            $table->primary('si113_sequencial');
            $table->foreign('si113_reg10')->references('si112_sequencial')->on('rsp102025');
        });
        DB::statement('CREATE INDEX rsp112025_si113_reg10_index ON rsp112025 USING btree (si113_reg10);');
        DB::statement('CREATE SEQUENCE rsp112025_si113_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rsp112025 ALTER COLUMN si113_sequencial SET DEFAULT nextval(\'rsp112025_si113_sequencial_seq\');');


        Schema::create('rsp122025', function (Blueprint $table) {
            $table->bigInteger('si114_sequencial');
            $table->bigInteger('si114_tiporegistro')->default(0);
            $table->bigInteger('si114_codreduzidorsp')->default(0);
            $table->bigInteger('si114_tipodocumento')->default(0);
            $table->string('si114_nrodocumento', 14);
            $table->bigInteger('si114_mes')->default(0);
            $table->bigInteger('si114_reg10')->default(0);
            $table->bigInteger('si114_instit')->nullable();
            $table->primary('si114_sequencial');
            $table->foreign('si114_reg10')->references('si112_sequencial')->on('rsp102025');
        });
        DB::statement('CREATE INDEX rsp122025_si114_reg10_index ON rsp122025 USING btree (si114_reg10);');
        DB::statement('CREATE SEQUENCE rsp122025_si114_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rsp122025 ALTER COLUMN si114_sequencial SET DEFAULT nextval(\'rsp122025_si114_sequencial_seq\');');

        Schema::create('rsp212025', function (Blueprint $table) {
            $table->bigInteger('si116_sequencial');
            $table->bigInteger('si116_tiporegistro')->default(0);
            $table->bigInteger('si116_codreduzidomov')->default(0);
            $table->bigInteger('si116_codfontrecursos')->default(0);
            $table->float('si116_vlmovimentacaofonte')->default(0);
            $table->bigInteger('si116_mes')->default(0);
            $table->bigInteger('si116_reg20')->default(0);
            $table->bigInteger('si116_instit')->nullable();
            $table->string('si116_codco');
            $table->integer('si116_codidentificafr')->nullable();
            $table->primary('si116_sequencial');
            $table->foreign('si116_reg20')->references('si115_sequencial')->on('rsp202025');
        });
        DB::statement('CREATE INDEX rsp212025_si116_reg20_index ON rsp212025 USING btree (si116_reg20);');
        DB::statement('CREATE SEQUENCE rsp212025_si116_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rsp212025 ALTER COLUMN si116_sequencial SET DEFAULT nextval(\'rsp212025_si116_sequencial_seq\');');

        Schema::create('rsp222025', function (Blueprint $table) {
            $table->bigInteger('si117_sequencial');
            $table->bigInteger('si117_tiporegistro')->default(0);
            $table->bigInteger('si117_codreduzidomov')->default(0);
            $table->bigInteger('si117_tipodocumento')->default(0);
            $table->string('si117_nrodocumento', 14);
            $table->bigInteger('si117_mes')->default(0);
            $table->bigInteger('si117_reg20')->default(0);
            $table->bigInteger('si117_instit')->nullable();
            $table->primary('si117_sequencial');
            $table->foreign('si117_reg20')->references('si115_sequencial')->on('rsp202025');
        });
        DB::statement('CREATE INDEX rsp222025_si117_reg20_index ON rsp222025 USING btree (si117_reg20);');
        DB::statement('CREATE SEQUENCE rsp222025_si117_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE rsp222025 ALTER COLUMN si117_sequencial SET DEFAULT nextval(\'rsp222025_si117_sequencial_seq\');');

        Schema::create('tce112025', function (Blueprint $table) {
            $table->bigInteger('si188_sequencial');
            $table->bigInteger('si188_tiporegistro')->default(0);
            $table->string('si188_numprocessotce', 12);
            $table->date('si188_datainstauracaotce');
            $table->bigInteger('si188_tipodocumentorespdano')->default(0);
            $table->string('si188_nrodocumentorespdano', 14);
            $table->bigInteger('si188_mes')->default(0);
            $table->bigInteger('si188_reg10')->default(0);
            $table->bigInteger('si188_instit')->nullable();
            $table->primary('si188_sequencial');
            $table->foreign('si188_reg10')->references('si188_sequencial')->on('tce112025');
        });
        DB::statement('CREATE SEQUENCE tce112025_si188_sequencial_seq INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1;');
        DB::statement('ALTER TABLE tce112025 ALTER COLUMN si188_sequencial SET DEFAULT nextval(\'tce112025_si188_sequencial_seq\');');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('aberlic102025');
        Schema::dropIfExists('aberlic112025');
        Schema::dropIfExists('aberlic122025');
        Schema::dropIfExists('aberlic132025');
        Schema::dropIfExists('aberlic142025');
        Schema::dropIfExists('aberlic152025');
        Schema::dropIfExists('aberlic162025');
        Schema::dropIfExists('aex102025');
        Schema::dropIfExists('afast102025');
        Schema::dropIfExists('afast202025');
        Schema::dropIfExists('afast302025');
        Schema::dropIfExists('alq102025');
        Schema::dropIfExists('alq112025');
        Schema::dropIfExists('alq122025');
        Schema::dropIfExists('anl102025');
        Schema::dropIfExists('anl112025');
        Schema::dropIfExists('aob102025');
        Schema::dropIfExists('aob112025');
        Schema::dropIfExists('aoc102025');
        Schema::dropIfExists('aoc112025');
        Schema::dropIfExists('aoc122025');
        Schema::dropIfExists('aoc132025');
        Schema::dropIfExists('aoc142025');
        Schema::dropIfExists('aoc152025');
        Schema::dropIfExists('aop102025');
        Schema::dropIfExists('aop112025');
        Schema::dropIfExists('arc102025');
        Schema::dropIfExists('arc202025');
        Schema::dropIfExists('balancete102025');
        Schema::dropIfExists('bodcasp102025');
        Schema::dropIfExists('bodcasp202025');
        Schema::dropIfExists('bodcasp302025');
        Schema::dropIfExists('bodcasp402025');
        Schema::dropIfExists('bodcasp502025');
        Schema::dropIfExists('bfdcasp102025');
        Schema::dropIfExists('bfdcasp202025');
        Schema::dropIfExists('bpdcasp102025');
        Schema::dropIfExists('bpdcasp202025');
        Schema::dropIfExists('bpdcasp302025');
        Schema::dropIfExists('bpdcasp402025');
        Schema::dropIfExists('bpdcasp502025');
        Schema::dropIfExists('bpdcasp602025');
        Schema::dropIfExists('bpdcasp702025');
        Schema::dropIfExists('bpdcasp712025');
        Schema::dropIfExists('cadobras102025');
        Schema::dropIfExists('cadobras202025');
        Schema::dropIfExists('cadobras212025');
        Schema::dropIfExists('cadobras302025');
        Schema::dropIfExists('caixa102025');
        Schema::dropIfExists('conge102025');
        Schema::dropIfExists('conge202025');
        Schema::dropIfExists('conge302025');
        Schema::dropIfExists('conge402025');
        Schema::dropIfExists('conge502025');
        Schema::dropIfExists('consid102025');
        Schema::dropIfExists('consor102025');
        Schema::dropIfExists('consor202025');
        Schema::dropIfExists('consor302025');
        Schema::dropIfExists('consor402025');
        Schema::dropIfExists('consor502025');
        Schema::dropIfExists('contratos102025');
        Schema::dropIfExists('contratos112025');
        Schema::dropIfExists('contratos122025');
        Schema::dropIfExists('contratos132025');
        Schema::dropIfExists('contratos202025');
        Schema::dropIfExists('contratos212025');
        Schema::dropIfExists('contratos302025');
        Schema::dropIfExists('contratos402025');
        Schema::dropIfExists('ctb102025');
        Schema::dropIfExists('ctb202025');
        Schema::dropIfExists('ctb302025');
        Schema::dropIfExists('ctb402025');
        Schema::dropIfExists('ctb502025');
        Schema::dropIfExists('cronem102025');
        Schema::dropIfExists('conv312025');
        Schema::dropIfExists('conv302025');
        Schema::dropIfExists('conv212025');
        Schema::dropIfExists('conv202025');
        Schema::dropIfExists('conv102025');
        Schema::dropIfExists('cute102025');
        Schema::dropIfExists('cute202025');
        Schema::dropIfExists('cute302025');
        Schema::dropIfExists('cvc102025');
        Schema::dropIfExists('cvc202025');
        Schema::dropIfExists('cvc302025');
        Schema::dropIfExists('cvc402025');
        Schema::dropIfExists('cvc502025');
        Schema::dropIfExists('dclrf102025');
        Schema::dropIfExists('dclrf112025');
        Schema::dropIfExists('dclrf202025');
        Schema::dropIfExists('dclrf302025');
        Schema::dropIfExists('dclrf402025');
        Schema::dropIfExists('ddc102025');
        Schema::dropIfExists('ddc202025');
        Schema::dropIfExists('ddc302025');
        Schema::dropIfExists('dfcdcasp1002025');
        Schema::dropIfExists('dfcdcasp102025');
        Schema::dropIfExists('dfcdcasp1102025');
        Schema::dropIfExists('dfcdcasp202025');
        Schema::dropIfExists('dfcdcasp302025');
        Schema::dropIfExists('dfcdcasp402025');
        Schema::dropIfExists('dfcdcasp502025');
        Schema::dropIfExists('dfcdcasp602025');
        Schema::dropIfExists('dfcdcasp702025');
        Schema::dropIfExists('dfcdcasp802025');
        Schema::dropIfExists('dfcdcasp902025');
        Schema::dropIfExists('dipr102025');
        Schema::dropIfExists('dipr202025');
        Schema::dropIfExists('dipr302025');
        Schema::dropIfExists('dipr402025');
        Schema::dropIfExists('dipr502025');
        Schema::dropIfExists('dispensa102025');
        Schema::dropIfExists('dispensa182025');
        Schema::dropIfExists('dispensa302025');
        Schema::dropIfExists('dispensa402025');
        Schema::dropIfExists('dvpdcasp102025');
        Schema::dropIfExists('dvpdcasp202025');
        Schema::dropIfExists('dvpdcasp302025');
        Schema::dropIfExists('emp102025');
        Schema::dropIfExists('emp112025');
        Schema::dropIfExists('emp122025');
        Schema::dropIfExists('emp202025');
        Schema::dropIfExists('emp302025');
        Schema::dropIfExists('exeobras102025');
        Schema::dropIfExists('exeobras202025');
        Schema::dropIfExists('ext102025');
        Schema::dropIfExists('ext202025');
        Schema::dropIfExists('ext302025');
        Schema::dropIfExists('flpgo102025');
        Schema::dropIfExists('hablic102025');
        Schema::dropIfExists('hablic202025');
        Schema::dropIfExists('homolic102025');
        Schema::dropIfExists('homolic202025');
        Schema::dropIfExists('homolic302025');
        Schema::dropIfExists('homolic402025');
        Schema::dropIfExists('ide2025');
        Schema::dropIfExists('idedcasp2025');
        Schema::dropIfExists('ideedital2025');
        Schema::dropIfExists('iderp102025');
        Schema::dropIfExists('iderp112025');
        Schema::dropIfExists('iderp202025');
        Schema::dropIfExists('item102025');
        Schema::dropIfExists('julglic102025');
        Schema::dropIfExists('julglic202025');
        Schema::dropIfExists('julglic302025');
        Schema::dropIfExists('julglic402025');
        Schema::dropIfExists('lao102025');
        Schema::dropIfExists('lao202025');
        Schema::dropIfExists('licobras102025');
        Schema::dropIfExists('licobras202025');
        Schema::dropIfExists('licobras302025');
        Schema::dropIfExists('lqd102025');
        Schema::dropIfExists('metareal102025');
        Schema::dropIfExists('ntf102025');
        Schema::dropIfExists('ntf202025');
        Schema::dropIfExists('obelac102025');
        Schema::dropIfExists('ops102025');
        Schema::dropIfExists('orgao102025');
        Schema::dropIfExists('parec102025');
        Schema::dropIfExists('parelic102025');
        Schema::dropIfExists('parpps102025');
        Schema::dropIfExists('parpps202025');
        Schema::dropIfExists('partlic102025');
        Schema::dropIfExists('pessoa102025');
        Schema::dropIfExists('pessoaflpgo102025');
        Schema::dropIfExists('pessoasobra102025');
        Schema::dropIfExists('ralic102025');
        Schema::dropIfExists('rec102025');
        Schema::dropIfExists('redispi102025');
        Schema::dropIfExists('regadesao102025');
        Schema::dropIfExists('regadesao112025');
        Schema::dropIfExists('regadesao122025');
        Schema::dropIfExists('regadesao132025');
        Schema::dropIfExists('regadesao142025');
        Schema::dropIfExists('regadesao202025');
        Schema::dropIfExists('regadesao302025');
        Schema::dropIfExists('regadesao402025');
        Schema::dropIfExists('reglic102025');
        Schema::dropIfExists('reglic202025');
        Schema::dropIfExists('respinf2025');
        Schema::dropIfExists('resplic102025');
        Schema::dropIfExists('resplic202025');
        Schema::dropIfExists('rpsd102025');
        Schema::dropIfExists('rsp102025');
        Schema::dropIfExists('rsp202025');
        Schema::dropIfExists('tce102025');
        Schema::dropIfExists('terem102025');
        Schema::dropIfExists('terem202025');
        Schema::dropIfExists('viap102025');
        Schema::dropIfExists('alq112025');
        Schema::dropIfExists('alq122025');
        Schema::dropIfExists('anl112025');
        Schema::dropIfExists('aob112025');
        Schema::dropIfExists('aoc112025');
        Schema::dropIfExists('aoc122025');
        Schema::dropIfExists('aoc132025');
        Schema::dropIfExists('aoc142025');
        Schema::dropIfExists('aoc152025');
        Schema::dropIfExists('aop112025');
        Schema::dropIfExists('aop122025');
        Schema::dropIfExists('aop132025');
        Schema::dropIfExists('arc112025');
        Schema::dropIfExists('arc122025');
        Schema::dropIfExists('arc212025');
        Schema::dropIfExists('balancete112025');
        Schema::dropIfExists('balancete122025');
        Schema::dropIfExists('balancete132025');
        Schema::dropIfExists('balancete142025');
        Schema::dropIfExists('balancete152025');
        Schema::dropIfExists('balancete162025');
        Schema::dropIfExists('balancete172025');
        Schema::dropIfExists('balancete182025');
        Schema::dropIfExists('balancete192025');
        Schema::dropIfExists('balancete202025');
        Schema::dropIfExists('balancete212025');
        Schema::dropIfExists('balancete222025');
        Schema::dropIfExists('balancete232025');
        Schema::dropIfExists('balancete242025');
        Schema::dropIfExists('balancete252025');
        Schema::dropIfExists('balancete262025');
        Schema::dropIfExists('balancete272025');
        Schema::dropIfExists('balancete282025');
        Schema::dropIfExists('balancete292025');
        Schema::dropIfExists('balancete302025');
        Schema::dropIfExists('balancete312025');
        Schema::dropIfExists('caixa112025');
        Schema::dropIfExists('caixa122025');
        Schema::dropIfExists('caixa132025');
        Schema::dropIfExists('conv112025');
        Schema::dropIfExists('ctb212025');
        Schema::dropIfExists('ctb222025');
        Schema::dropIfExists('ctb312025');
        Schema::dropIfExists('cute212025');
        Schema::dropIfExists('dispensa112025');
        Schema::dropIfExists('dispensa122025');
        Schema::dropIfExists('dispensa132025');
        Schema::dropIfExists('dispensa142025');
        Schema::dropIfExists('dispensa152025');
        Schema::dropIfExists('dispensa162025');
        Schema::dropIfExists('dispensa172025');
        Schema::dropIfExists('ext312025');
        Schema::dropIfExists('ext322025');
        Schema::dropIfExists('flpgo112025');
        Schema::dropIfExists('flpgo122025');
        Schema::dropIfExists('hablic112025');
        Schema::dropIfExists('lao112025');
        Schema::dropIfExists('lao212025');
        Schema::dropIfExists('lqd112025');
        Schema::dropIfExists('lqd112025');
        Schema::dropIfExists('lqd122025');
        Schema::dropIfExists('ntf112025');
        Schema::dropIfExists('obelac112025');
        Schema::dropIfExists('ops112025');
        Schema::dropIfExists('ops122025');
        Schema::dropIfExists('ops132025');
        Schema::dropIfExists('orgao112025');
        Schema::dropIfExists('parec112025');
        Schema::dropIfExists('ralic112025');
        Schema::dropIfExists('ralic122025');
        Schema::dropIfExists('rec112025');
        Schema::dropIfExists('redispi112025');
        Schema::dropIfExists('redispi122025');
        Schema::dropIfExists('rpsd112025');
        Schema::dropIfExists('rsp112025');
        Schema::dropIfExists('rsp122025');
        Schema::dropIfExists('rsp212025');
        Schema::dropIfExists('rsp222025');
        Schema::dropIfExists('tce112025');

        // Drop sequence
        DB::statement('DROP SEQUENCE IF EXISTS aberlic102025_si46_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic112025_si47_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic122025_si48_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic132025_si49_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic142025_si50_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic152025_si51_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic162025_si52_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aex102025_si130_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS afast102025_si199_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS afast202025_si200_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS afast302025_si201_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS alq102025_si121_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS alq112025_si122_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS alq122025_si123_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS anl102025_si110_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS anl112025_si111_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aob102025_si141_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aob112025_si142_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aoc102025_si38_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aoc112025_si39_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aoc122025_si40_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aoc132025_si41_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aoc142025_si42_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aoc152025_si194_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aop102025_si137_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS aop112025_si138_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS arc102025_si28_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS arc202025_si31_sequencial_seq CASCADE');
        DB::statement('DROP SEQUENCE IF EXISTS balancete102025_si177_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS bfdcasp102025_si206_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS bfdcasp202025_si207_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS bodcasp102025_si201_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS bodcasp202025_si202_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS bodcasp302025_si203_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS bodcasp402025_si204_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS bodcasp502025_si205_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS bodcasp702025_si214_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bodcasp712025_si215_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bpdcasp102025_si208_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bpdcasp202025_si209_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bpdcasp302025_si210_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bpdcasp402025_si211_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bpdcasp502025_si212_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bpdcasp602025_si213_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bpdcasp702025_si214_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS bpdcasp712025_si215_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS cadobras102025_si198_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cadobras202025_si199_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cadobras212025_si200_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cadobras302025_si201_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS caixa102025_si103_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS conge102025_si182_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS conge202025_si183_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS conge302025_si184_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS conge402025_si237_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS conge502025_si238_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS consid102025_si158_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS consor102025_si16_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS consor202025_si17_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS consor302025_si18_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS consor402025_si19_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS consor502025_si20_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS contratos102025_si83_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS contratos112025_si84_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS contratos122025_si85_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS contratos132025_si86_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS contratos202025_si87_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS contratos212025_si88_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS contratos302025_si89_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS contratos402025_si91_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ctb502025_si102_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ctb402025_si101_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ctb302025_si99_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ctb202025_si96_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ctb102025_si95_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS cronem102025_si170_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS conv312025_si204_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS conv302025_si203_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS conv212025_si232_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS conv202025_si94_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS conv102025_si92_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS cute102025_si199_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cute202025_si200_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cute302025_si202_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cvc102025_si146_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cvc202025_si147_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cvc302025_si148_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cvc402025_si150_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS cvc502025_si149_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dclrf102025_si157_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dclrf112025_si205_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dclrf202025_si191_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dclrf302025_si192_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dclrf402025_si193_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS ddc102025_si153_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS ddc202025_si154_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS ddc302025_si178_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp1002025_si228_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp102025_si219_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp1102025_si229_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp202025_si220_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp302025_si221_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp402025_si222_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp502025_si223_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp602025_si224_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp702025_si225_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp802025_si226_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dfcdcasp902025_si227_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS dipr102025_si230_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dipr202025_si231_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dipr302025_si232_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dipr402025_si233_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dipr502025_si234_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa102025_si74_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa182025_si82_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa302025_si203_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa402025_si204_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dvpdcasp102025_si216_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS dvpdcasp202025_si217_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS dvpdcasp302025_si218_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS emp102025_si106_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS emp112025_si107_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS emp122025_si108_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS emp202025_si109_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS emp302025_si206_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS exeobras102025_si197_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS exeobras202025_si204_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ext102025_si124_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ext202025_si165_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ext302025_si126_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS flpgo102025_si195_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS hablic102025_si57_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS hablic202025_si59_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS homolic102025_si63_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS homolic202025_si64_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS homolic302025_si65_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS homolic402025_si66_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ide2025_si11_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS idedcasp2025_si200_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ideedital2025_si186_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS iderp102025_si179_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS iderp112025_si180_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS iderp202025_si181_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS item102025_si43_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS julglic102025_si60_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS julglic202025_si61_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS julglic302025_si62_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS julglic402025_si62_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS lao102025_si34_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS lao202025_si36_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS licobras102025_si195_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS licobras202025_si196_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS licobras302025_si203_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS lqd102025_si118_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS metareal102025_si171_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ntf102025_si143_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ntf202025_si145_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS obelac102025_si155_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ops102025_si132_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS orgao102025_si14_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS parec102025_si22_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS parelic102025_si66_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS parpps102025_si156_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS parpps202025_si155_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS partlic102025_si203_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS pessoa102025_si12_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS pessoaflpgo102025_si193_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS pessoasobra102025_si194_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ralic102025_si180_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS rec102025_si25_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS redispi102025_si183_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS regadesao102025_si67_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS regadesao112025_si68_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS regadesao122025_si69_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS regadesao132025_si70_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS regadesao142025_si71_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS regadesao202025_si72_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS regadesao302025_si74_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS regadesao402025_si73_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic112025_si47_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS reglic102025_si44_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS reglic202025_si45_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS respinf2025_si197_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS resplic102025_si55_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS resplic202025_si56_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS rpsd102025_si189_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS rsp102025_si112_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS rsp202025_si115_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS tce102025_si187_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS terem102025_si180_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS terem202025_si182_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS viap102025_si62_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic122025_si48_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic132025_si49_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic142025_si50_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic152025_si51_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS aberlic162025_si52_sequencial_seq CASCADE;');
        DB::statement('DROP SEQUENCE IF EXISTS alq112025_si122_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS alq122025_si123_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS anl112025_si111_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aob112025_si142_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aoc112025_si39_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aoc122025_si40_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aoc132025_si41_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aoc142025_si42_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aoc152025_si194_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aop112025_si138_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aop122025_si139_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS aop132025_si140_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS arc112025_si29_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS arc122025_si30_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS arc212025_si32_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS balancete112025_si178_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS balancete122025_si179_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS balancete132025_si180_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS balancete142025_si181_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS balancete152025_si182_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS balancete162025_si183_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete172025_si184_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete182025_si185_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete192025_si186_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete202025_si187_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete212025_si188_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete222025_si189_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete232025_si190_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete242025_si191_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete252025_si195_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete262025_si196_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete272025_si197_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete282025_si198_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete292025_si241_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete302025_si242_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS balancete312025_si243_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS caixa112025_si166_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS caixa122025_si104_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS caixa132025_si105_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS conv112025_si93_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ctb212025_si97_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ctb222025_si98_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ctb312025_si100_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS cute212025_si201_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa112025_si75_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa122025_si76_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa132025_si77_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa142025_si78_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa152025_si79_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa162025_si80_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS dispensa172025_si81_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ext312025_si127_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS ext322025_si128_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS flpgo112025_si196_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS flpgo122025_si197_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS hablic112025_si58_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS lao112025_si35_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS lao212025_si37_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS lqd112025_si119_sequencial_seq;');
        DB::statement('DROP SEQUENCE IF EXISTS lqd122025_si120_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ntf112025_si144_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS obelac112025_si140_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ops112025_si133_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ops122025_si134_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ops132025_si135_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS orgao112025_si15_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS parec112025_si23_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ralic112025_si181_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS ralic122025_si182_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS rec112025_si26_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS redispi112025_si184_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS redispi122025_si185_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS rpsd112025_si190_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS rsp112025_si113_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS rsp122025_si114_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS rsp212025_si116_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS rsp222025_si117_sequencial_seq');
        DB::statement('DROP SEQUENCE IF EXISTS tce112025_si188_sequencial_seq');

        // drop índices
        DB::statement('DROP INDEX IF EXISTS regadesao112025_si68_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS regadesao122025_si69_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS regadesao132025_si70_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS regadesao142025_si71_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS aop112025_si138_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS aoc152025_si194_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS aoc142025_si42_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS aoc132025_si41_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS aoc122025_si40_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS aoc112025_si39_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS aob112025_si142_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS anl112025_si111_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS alq122025_si123_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS alq112025_si122_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS arc112025_si15_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS arc122025_si30_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS arcwq2025_si32_reg20_index;');
        DB::statement('DROP INDEX IF EXISTS caixa122025_si104_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS caixa132025_si105_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS conv112025_si93_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS ctb212025_si97_reg20_index;');
        DB::statement('DROP INDEX IF EXISTS ctb222025_si98_reg21_index;');
        DB::statement('DROP INDEX IF EXISTS ctb312025_si100_reg30_index;');
        DB::statement('DROP INDEX IF EXISTS cute212025_si201_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS dispensa112025_si75_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS dispensa122025_si76_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS dispensa132025_si77_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS dispensa142025_si78_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS dispensa152025_si79_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS dispensa162025_si80_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS dispensa172025_si81_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS ext312025_si127_reg30_index;');
        DB::statement('DROP INDEX IF EXISTS ext322025_si128_reg20_index;');
        DB::statement('DROP INDEX IF EXISTS hablic112025_si58_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS lao112025_si35_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS lao212025_si37_reg20_index;');
        DB::statement('DROP INDEX IF EXISTS lqd112025_si119_reg10_index;');
        DB::statement('DROP INDEX IF EXISTS lqd112025_si119_reg10_index');
        DB::statement('DROP INDEX IF EXISTS lqd122025_si120_reg10_index');
        DB::statement('DROP INDEX IF EXISTS ntf112025_si144_reg10_index');
        DB::statement('DROP INDEX IF EXISTS obelac112025_si140_reg10_index');
        DB::statement('DROP INDEX IF EXISTS ops112025_si133_reg10_index');
        DB::statement('DROP INDEX IF EXISTS ops122025_si134_reg10_index');
        DB::statement('DROP INDEX IF EXISTS ops132025_si135_reg10_index');
        DB::statement('DROP INDEX IF EXISTS orgao112025_si15_reg10_index');
        DB::statement('DROP INDEX IF EXISTS parec112025_si23_reg10_index');
        DB::statement('DROP INDEX IF EXISTS rec112025_si26_reg10_index');
        DB::statement('DROP INDEX IF EXISTS rsp112025_si113_reg10_index');
        DB::statement('DROP INDEX IF EXISTS rsp122025_si114_reg10_index');
        DB::statement('DROP INDEX IF EXISTS rsp212025_si116_reg20_index');
        DB::statement('DROP INDEX IF EXISTS rsp222025_si117_reg20_index');
        DB::statement('DROP INDEX IF EXISTS contratos122025_si85_reg10_index');
        DB::statement('DROP INDEX IF EXISTS contratos132025_si86_reg10_index');
        DB::statement('DROP INDEX IF EXISTS emp112025_si107_reg10_index');
        DB::statement('DROP INDEX IF EXISTS emp122025_si108_reg10_index');
    }
}
