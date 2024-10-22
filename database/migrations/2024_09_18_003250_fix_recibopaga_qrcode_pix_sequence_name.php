<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixRecibopagaQrcodePixSequenceName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        $this->fixRecibopagaQrcodePixSequence();
//        $this->fixInstituicoesFinanceirasApiPixSequence();
//        $this->fixConfiguracoesPixBBSequence();
    }

    private function fixRecibopagaQrcodePixSequence()
    {
        $nextVal = DB::selectOne("select nextval('arrecadacao.recibopaga_qrcode_pix_k176_sequencial')");

        $sqlSequence = "CREATE SEQUENCE arrecadacao.recibopaga_qrcode_pix_k176_sequencial_seq
                        START WITH {$nextVal->nextval}
                        INCREMENT BY 1
                        MINVALUE 0
                        NO MAXVALUE
                        CACHE 1";

        DB::statement($sqlSequence);

        DB::statement('drop sequence arrecadacao.recibopaga_qrcode_pix_k176_sequencial');

        Schema::table('arrecadacao.recibopaga_qrcode_pix', function (Blueprint $table) {
            $table->integer('k176_sequencial')->default("nextval('recibopaga_qrcode_pix_k176_sequencial_seq'::text::regclass)")->change();
        });
    }

    private function fixInstituicoesFinanceirasApiPixSequence()
    {
        $nextVal = DB::selectOne("select nextval('arrecadacao.instituicoes_financeiras_api_pix_k175_sequencial')");

        $sqlSequence = "CREATE SEQUENCE arrecadacao.instituicoes_financeiras_api_pix_k175_sequencial_seq
                        START WITH {$nextVal->nextval}
                        INCREMENT BY 1
                        MINVALUE 0
                        NO MAXVALUE
                        CACHE 1";

        DB::statement($sqlSequence);

        DB::statement('drop sequence arrecadacao.instituicoes_financeiras_api_pix_k175_sequencial');

        Schema::table('arrecadacao.instituicoes_financeiras_api_pix', function (Blueprint $table) {
            $table->integer('k175_sequencial')->default("nextval('instituicoes_financeiras_api_pix_k175_sequencial_seq'::text::regclass)")->change();
        });
    }

    private function fixConfiguracoesPixBBSequence()
    {
        $nextVal = DB::selectOne("select nextval('arrecadacao.configuracoes_pix_banco_do_brasil_k177_sequencial')");

        $sqlSequence = "CREATE SEQUENCE arrecadacao.configuracoes_pix_banco_do_brasil_k177_sequencial_seq
                        START WITH {$nextVal->nextval}
                        INCREMENT BY 1
                        MINVALUE 0
                        NO MAXVALUE
                        CACHE 1";

        DB::statement($sqlSequence);

        DB::statement('drop sequence arrecadacao.configuracoes_pix_banco_do_brasil_k177_sequencial');

        Schema::table('arrecadacao.configuracoes_pix_banco_do_brasil', function (Blueprint $table) {
            $table->integer('k177_sequencial')->default("nextval('configuracoes_pix_banco_do_brasil_k177_sequencial_seq'::text::regclass)")->change();
        });
    }
}
