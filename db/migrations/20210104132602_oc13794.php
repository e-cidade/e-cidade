<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13794 extends PostgresMigration
{
    public function up(){
        $sql = "
            UPDATE db_itensmenu
                SET descricao = 'Rol de Licita��es',
                    help = 'Rol de Licita��es'
            WHERE id_item = 3000281;
        ";
        $this->execute($sql);
    }

    public function down(){
        $sql = "
        UPDATE db_itensmenu
        SET descricao = 'Roll de Licita��es',
            help = 'Roll de Licita��es'
        WHERE id_item = 3000281;
        ";
        $this->execute($sql);
    }
}
