<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Oc23688 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alq112025', function (Blueprint $table) {
            if (!Schema::hasColumn('alq112025', 'si122_codco')) {
                $table->string('si122_codco', 4)->default('0000')->nullable();
            }
        });

        Schema::table('anl112025', function (Blueprint $table) {
            if (!Schema::hasColumn('anl112025', 'si111_codco')) {
                $table->string('si111_codco', 4)->default('0000')->nullable();
            }
        });

        Schema::table('aop112025', function (Blueprint $table) {
            if (!Schema::hasColumn('aop112025', 'si138_codco')) {
                $table->string('si138_codco', 4)->default('0000')->nullable();
            }
        });

        Schema::table('ext202025', function (Blueprint $table) {
            if (!Schema::hasColumn('ext202025', 'si165_exerciciocompdevo')) {
                $table->integer('si165_exerciciocompdevo')->default(0)->nullable();
            }
            if (!Schema::hasColumn('ext202025', 'si165_natsaldoanteriorfonte')) {
                $table->string('si165_natsaldoanteriorfonte', 1)->nullable(false);
            }
            if (!Schema::hasColumn('ext202025', 'si165_totaldebitos')) {
                $table->float('si165_totaldebitos', 8)->default(0)->nullable(false);
            }
            if (!Schema::hasColumn('ext202025', 'si165_totalcreditos')) {
                $table->float('si165_totalcreditos', 8)->default(0)->nullable(false);
            }
            if (!Schema::hasColumn('ext202025', 'si165_vlsaldoatualfonte')) {
                $table->float('si165_vlsaldoatualfonte', 8)->default(0)->nullable(false);
            }
            if (!Schema::hasColumn('ext202025', 'si165_natsaldoatualfonte')) {
                $table->string('si165_natsaldoatualfonte', 1)->nullable(false);
            }
            if (Schema::hasColumn('ext202025', 'si165_vlnovovalor')) {
                $table->dropColumn('si165_vlnovovalor');
            }
            if (Schema::hasColumn('ext202025', 'si165_vlreclassificacaoextratordalancamento')) {
                $table->dropColumn('si165_vlreclassificacaoextratordalancamento');
            }
        });

        Schema::table('contratos102025', function (Blueprint $table) {
            if (!Schema::hasColumn('contratos102025', 'si83_multainadimplemento')) {
                $table->string('si83_multainadimplemento', 100)->nullable();
            }
            if (!Schema::hasColumn('contratos102025', 'si83_garantia')) {
                $table->bigInteger('si83_garantia')->default(0)->nullable();
            }
            if (!Schema::hasColumn('contratos102025', 'si83_cpfsignatariocontratante')) {
                $table->string('si83_cpfsignatariocontratante', 11)->nullable(false);
            }
            if (!Schema::hasColumn('contratos102025', 'si83_datapublicacao')) {
                $table->date('si83_datapublicacao')->nullable(false);
            }
            if (!Schema::hasColumn('contratos102025', 'si83_veiculodivulgacao')) {
                $table->string('si83_veiculodivulgacao', 50)->nullable(false);
            }
            if (!Schema::hasColumn('contratos102025', 'si83_mes')) {
                $table->bigInteger('si83_mes')->default(0)->nullable(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alq112025', function (Blueprint $table) {
            if (Schema::hasColumn('alq112025', 'si122_codco')) {
                $table->dropColumn('si122_codco');
            }
        });

        Schema::table('anl112025', function (Blueprint $table) {
            if (Schema::hasColumn('anl112025', 'si111_codco')) {
                $table->dropColumn('si111_codco');
            }
        });

        Schema::table('aop112025', function (Blueprint $table) {
            if (Schema::hasColumn('aop112025', 'si138_codco')) {
                $table->dropColumn('si138_codco');
            }
        });

        Schema::table('ext202025', function (Blueprint $table) {
            if (Schema::hasColumn('ext202025', 'si165_exerciciocompdevo')) {
                $table->dropColumn('si165_exerciciocompdevo');
            }
            if (Schema::hasColumn('ext202025', 'si165_natsaldoanteriorfonte')) {
                $table->dropColumn('si165_natsaldoanteriorfonte');
            }
            if (Schema::hasColumn('ext202025', 'si165_totaldebitos')) {
                $table->dropColumn('si165_totaldebitos');
            }
            if (Schema::hasColumn('ext202025', 'si165_totalcreditos')) {
                $table->dropColumn('si165_totalcreditos');
            }
            if (Schema::hasColumn('ext202025', 'si165_vlsaldoatualfonte')) {
                $table->dropColumn('si165_vlsaldoatualfonte');
            }
            if (Schema::hasColumn('ext202025', 'si165_natsaldoatualfonte')) {
                $table->dropColumn('si165_natsaldoatualfonte');
            }
            if (!Schema::hasColumn('ext202025', 'si165_vlnovovalor')) {
                $table->float('si165_vlnovovalor', 8)->default(0)->nullable(false);
            }
            if (!Schema::hasColumn('ext202025', 'si165_vlreclassificacaoextratordalancamento')) {
                $table->float('si165_vlreclassificacaoextratordalancamento', 8)->default(0)->nullable(false);
            }
        });

        Schema::table('contratos102025', function (Blueprint $table) {
            if (Schema::hasColumn('contratos102025', 'si83_multainadimplemento')) {
                $table->dropColumn('si83_multainadimplemento');
            }
            if (Schema::hasColumn('contratos102025', 'si83_garantia')) {
                $table->dropColumn('si83_garantia');
            }
            if (Schema::hasColumn('contratos102025', 'si83_cpfsignatariocontratante')) {
                $table->dropColumn('si83_cpfsignatariocontratante');
            }
            if (Schema::hasColumn('contratos102025', 'si83_datapublicacao')) {
                $table->dropColumn('si83_datapublicacao');
            }
            if (Schema::hasColumn('contratos102025', 'si83_veiculodivulgacao')) {
                $table->dropColumn('si83_veiculodivulgacao');
            }
            if (Schema::hasColumn('contratos102025', 'si83_mes')) {
                $table->dropColumn('si83_mes');
            }
        });
    }
}
