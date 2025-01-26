<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class HotfixOc13956 extends PostgresMigration
{
    public function change(){
        $sql = "
            begin;

            select fc_startsession();

            -- Pega todos os submenus do menu Cadastro e associa ao novo menu Cadastro nos m�dulos TCE/MG e STN

            UPDATE db_menu SET id_item = 29 WHERE modulo = 2000018 AND id_item = 3962;
            UPDATE db_menu SET id_item = 29 WHERE modulo = 2000025 AND id_item = 3962;

            -- Exclui o menu Fornecedor nos m�dulos TCE/MG e STN

            DELETE FROM db_menu
                WHERE id_item IN (2000018, 2000025)
                AND id_item_filho = 3962;

            -- Cadastra o menu 'Cadastro' nos m�dulos TCE/MG e STN respectivamente
            INSERT INTO db_menu(id_item, id_item_filho, menusequencia, modulo)
                VALUES(2000018, 29, 1, 2000018),
                      (2000025, 29, 1, 2000025);

            COMMIT;
        ";

        $this->execute($sql);
    }
}
