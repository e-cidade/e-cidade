<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc8339menus extends PostgresMigration
{
    public function up()
    {
        $sql = "
        select fc_startsession();

        -- Cadastro de itens do menu
        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Publica��o Termo de Ratifica��o', 'Publica��o Termo de Ratifica��o', ' ', 1, 1, 'Publica��o Termo de Ratifica��o', 't');
        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Inclus�o', 'Inclus�o', 'lic1_publicratificacao001.php', 1, 1, 'Inclus�o', 't');
        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Altera��o', 'Altera��o', 'lic1_publicratificacao002.php', 1, 1, 'Altera��o', 't');
        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Exclus�o', 'Exclus�o', 'lic1_publicratificacao003.php', 1, 1, 'Exclus�o', 't');

        -- Cadastro de Menus
        INSERT INTO db_menu VALUES (1818, (select max(id_item)-3 from db_itensmenu),9, 381);
        INSERT INTO db_menu VALUES ((select max(id_item)-3 from db_itensmenu), (select max(id_item)-2 from db_itensmenu), 1, 381);
        INSERT INTO db_menu VALUES ((select max(id_item)-3 from db_itensmenu), (select max(id_item)-1 from db_itensmenu), 2, 381);
        INSERT INTO db_menu VALUES ((select max(id_item)-3 from db_itensmenu), (select max(id_item) from db_itensmenu), 3, 381)";
        $this->execute($sql);
    }

}
