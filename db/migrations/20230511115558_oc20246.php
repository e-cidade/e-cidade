<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20246 extends PostgresMigration
{

    public function up()
    {
        $sql = "
        UPDATE db_itensmenu
        SET descricao = 'Lan�amentos - Frequ�ncia', help = 'Lan�amentos - Frequ�ncia', desctec = 'Lan�amentos - Frequ�ncia'
        WHERE funcao = 'edu4_lancdiario001.php';


        INSERT INTO db_itensmenu VALUES (
            (SELECT max(id_item)+1 FROM db_itensmenu),
            'Lan�amentos - Conte�do',
            'Lan�amentos - Conte�do',
            'edu4_lancdiario002.php',
            1,
            1,
            'Lan�amentos - Conte�do',
            't');

        INSERT INTO db_menu VALUES(
            (SELECT id_item FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE desctec LIKE'%Lan�amentos%' AND funcao = 'edu4_lancdiario001.php')),
            (SELECT max(id_item) from db_itensmenu),
            (SELECT MAX(menusequencia)+1 AS COUNT FROM db_menu WHERE id_item = 1100930),
            1100747);
        ";

        $this->execute($sql);
    }
}
