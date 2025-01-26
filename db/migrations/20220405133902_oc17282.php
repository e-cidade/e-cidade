<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17282 extends PostgresMigration
{

    public function up()
    {
        $sql = "begin;
        INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu),'Importa��o Saldo Conta Corrente' ,'Importa��o Saldo Conta Corrente' ,'con4_importacaosaldocontacorrente001.php' ,'1' ,'1' ,'' ,'true' );
        INSERT INTO db_menu VALUES (9414, (SELECT id_item FROM db_itensmenu WHERE descricao = 'Importa��o Saldo Conta Corrente' LIMIT 1), (SELECT max(menusequencia)+1 FROM db_menu WHERE id_item = 9414), 209);
        commit;
        ";

        $this->execute($sql);
    }
}


