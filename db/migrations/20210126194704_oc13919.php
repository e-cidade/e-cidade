<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13919 extends PostgresMigration
{
    public function up(){
        $sql = "
        BEGIN;

            select fc_startsession();

            -- Inser��o do menu no M�dulo Compras

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (31, (select id_item from db_itensmenu where funcao = 'emp1_empconsultaaut001.php'), (select max(menusequencia)+1 from db_menu where modulo = 28), 28);

            -- Inser��o do menu no M�dulo Contratos

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (31, (select id_item from db_itensmenu where funcao = 'emp1_empconsultaaut001.php'), (select max(menusequencia)+1 from db_menu where modulo = 8251), 8251);

        COMMIT;
        ";
        $this->execute($sql);
    }

    public function down(){
        $sql = "
            -- Exclui os menus de Autoriza��o de Empenho dos m�dulos Contrato e Compras

            DELETE from db_menu where id_item_filho = (select id_item from db_itensmenu where funcao = 'emp1_empconsultaaut001.php' and modulo in (28, 8251));
        ";
        $this->execute($sql);
    }
}
