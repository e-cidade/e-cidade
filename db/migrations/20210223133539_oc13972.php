<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13972 extends PostgresMigration
{
    public function up(){
        $sql = "
            BEGIN;

            SELECT fc_startsession();

            -- Altera a posi��o dos menus 'Processamento' e 'Cancelar Processamento'
            -- para dentro da rotina M�d. Compras >> Procedimentos >> Registro de Pre�o >>  Por Quantidade

            DELETE FROM db_menu where id_item_filho in (7968, 7969) and modulo = 28;

            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo)
                values (10004, 7968, 4, 28),
                       (10004, 7969, 5, 28);

            -- Inverte a posi��o dos menus 'Processo de Compras' e 'Registro de Pre�o'

            UPDATE db_menu
                SET menusequencia = 4
                WHERE id_item_filho = 7941 AND id_item = 32 AND modulo = 28;

            UPDATE db_menu
                SET menusequencia = 3
                WHERE id_item_filho = 4071 AND id_item = 32 AND modulo = 28;

            -- Troca a nomenclatura de 'Outros' para 'Incluir' na rotina de Aditamento

            UPDATE db_itensmenu
                SET descricao = 'Incluir'
                WHERE id_item =
                        (SELECT id_item_filho
                        FROM db_menu
                        INNER JOIN db_itensmenu ON id_item_filho = db_itensmenu.id_item
                        WHERE db_menu.modulo = 8251
                            AND descricao = 'Outros'
                            AND funcao = 'ac04_aditaoutros.php');


            COMMIT;
        ";
        $this->execute($sql);
    }

    public function down(){
        $sql = "
            BEGIN;

            SELECT fc_startsession();

            -- Retorna a posi��o dos menus 'Processamento' e 'Cancelar Processamento'

            DELETE FROM db_menu where id_item_filho IN (7968, 7969) and modulo = 28;

            INSERT INTO db_menu(id_item, id_item_filho, menusequencia, modulo)
                VALUES (7941, 7968, 6, 28),
                       (7941, 7969, 6, 28);

            -- Retorna as posi��es anteriores

            UPDATE db_menu
                SET menusequencia = 3
                WHERE id_item_filho = 7941 AND id_item = 32 AND modulo = 28;

            UPDATE db_menu
                SET menusequencia = 4
                WHERE id_item_filho = 4071 AND id_item = 32 AND modulo = 28;

            -- Retorna a nomenclatura de 'Incluir' para 'Outros' na rotina de Aditamento

            UPDATE db_itensmenu
                SET descricao = 'Outros'
                WHERE id_item =
                        (SELECT id_item_filho
                        FROM db_menu
                        INNER JOIN db_itensmenu ON id_item_filho = db_itensmenu.id_item
                        WHERE db_menu.modulo = 8251
                            AND descricao = 'Incluir'
                            AND funcao = 'ac04_aditaoutros.php');

            COMMIT;
        ";
        $this->execute($sql);
    }
}
