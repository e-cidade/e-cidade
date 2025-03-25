<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OC23370 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sNomeMenu = utf8_encode('Relatório de Usuários');
        $sql = "
        INSERT INTO configuracoes.db_itensmenu values ((select max(id_item)+1 from configuracoes.db_itensmenu), '$sNomeMenu','$sNomeMenu','sys1_relatoriodeusuarios.php',1,1,'$sNomeMenu','t');

        INSERT INTO configuracoes.db_menu VALUES(30,(select max(id_item) from configuracoes.db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 30 and modulo = 28),1);
        ";
        DB::unprepared($sql);
    }

}
