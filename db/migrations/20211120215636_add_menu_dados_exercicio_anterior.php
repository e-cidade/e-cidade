<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddMenuDadosExercicioAnterior extends PostgresMigration
{
    public function up()
    {

        $sql  = " INSERT INTO db_itensmenu VALUES ((select max(id_item) + 1 from db_itensmenu), 'Dados de Exerc�cios Anteriores', 'Dados de Exerc�cios Anteriores', '', 1, 1, 'Dados de Exerc�cios Anteriores', 't');";
        $sql .= " INSERT INTO db_menu VALUES (29, (select max(id_item) from db_itensmenu), 264, 209);";
        $sql .= " INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Inclus�o', 'Inclus�o', 'con1_dadosexercicioanterior001.php', '1', '1', 'Inclus�o', 't');";
        $sql .= " INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Dados de Exerc�cios Anteriores'), (select max(id_item) from db_itensmenu), 1, 209);";
        $sql .= " INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Altera��o', 'Altera��o', 'con1_dadosexercicioanterior002.php', '1', '1', 'Altera��o', 't');";
        $sql .= " INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Dados de Exerc�cios Anteriores'), (select max(id_item) from db_itensmenu), 2, 209);";
        $sql .= " INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Exclus�o', 'Exclus�o', 'con1_dadosexercicioanterior003.php', '1', '1', 'Exclus�o', 't');";
        $sql .= " INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Dados de Exerc�cios Anteriores'), (select max(id_item) from db_itensmenu), 3, 209);";

        $this->execute($sql);
    }

    public function down()
    {
    }
}
