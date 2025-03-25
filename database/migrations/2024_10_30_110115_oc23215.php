<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Oc23215 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $aUsers = array('vcl.contass',
                        'jtsg.contass',
                        'rm.contass',
                        'nt.contass',
                        'tarique.contass',
                        'aebr.contass',
                        'joyce.contass',
                        'rfv.contass',
                        'fps.contass');

        DB::table('configuracoes.db_usuarios')->whereIn('login',$aUsers)->update(['usuarioativo' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $aUsers = array('vcl.contass',
                        'jtsg.contass',
                        'rm.contass',
                        'nt.contass',
                        'tarique.contass',
                        'aebr.contass',
                        'joyce.contass',
                        'rfv.contass',
                        'fps.contass');

        DB::table('configuracoes.db_usuarios')->whereIn('login',$aUsers)->update(['usuarioativo' => 1]);
    }
}
