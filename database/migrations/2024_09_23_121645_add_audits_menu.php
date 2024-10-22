<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


class AddAuditsMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            INSERT INTO configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( (SELECT max(id_item)+1 FROM configuracoes.db_itensmenu) ,'Auditorias (Novo)' ,'Consulta auditoria em tabelas do banco' ,'web/audits' ,'1' ,'1' ,'Consulta auditoria em tabelas do banco' ,'true' );
            INSERT INTO configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 31 ,(SELECT max(id_item) FROM configuracoes.db_itensmenu) ,(select max(menusequencia)+1 from configuracoes.db_menu where id_item = 31) ,1 );
        ";

        DB::unprepared($sql);
    }
}
