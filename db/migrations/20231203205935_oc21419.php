<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21419 extends PostgresMigration
{
    public function up()
    {
        $sql = "
        INSERT INTO db_itensmenu VALUES (
            (SELECT max(id_item)+1 FROM db_itensmenu),
            'Virada Anual de Dota��es',
            'Virada Anual de Dota��es',
            'ac04_alterardotacoescontratos001.php',
            1,
            1,
            'Virada Anual de Dota��es',
            't');

        INSERT INTO db_menu VALUES(
            (SELECT id_item FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao LIKE'%Alterar%' AND funcao = 'ac04_alteradotacaocredenciamento001.php')),
            (SELECT max(id_item) from db_itensmenu),
            (SELECT MAX(menusequencia)+1 AS COUNT FROM db_menu WHERE id_item = (SELECT id_item FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao LIKE'%Alterar%' AND funcao = 'ac04_alteradotacaocredenciamento001.php'))),
            (SELECT modulo FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE descricao LIKE'%Alterar%' AND funcao = 'ac04_alteradotacaocredenciamento001.php')));
        ";

        $this->execute($sql);
    }
}
