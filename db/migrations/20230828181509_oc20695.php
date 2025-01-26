<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20695 extends PostgresMigration {
    public function up() {
        $sql = "
        INSERT INTO db_itensmenu VALUES (
            (SELECT max(id_item)+1 FROM db_itensmenu),
            'Invent�rio de Bens',
            'Invent�rio de Bens',
            'pat2_inventariobens001.php',
            1,
            1,
            'Invent�rio de Bens',
            't');

        INSERT INTO db_menu VALUES(
            (SELECT id_item FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao LIKE'%Bens%' AND funcao = 'pat2_bensdepart001.php')),
            (SELECT max(id_item) from db_itensmenu),
            (SELECT MAX(menusequencia)+1 AS COUNT FROM db_menu WHERE id_item = (SELECT id_item FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao LIKE'%Bens%' AND funcao = 'pat2_bensdepart001.php'))),
            (SELECT modulo FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao LIKE '%Bens%' AND funcao = 'pat2_bensdepart001.php')));
        ";

        $this->execute($sql);
    }
}
