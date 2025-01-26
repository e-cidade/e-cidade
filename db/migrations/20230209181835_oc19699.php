<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19699 extends PostgresMigration
{
    public function up()
    {
        $sql = "
                select fc_startsession();

                begin;

                select setval('db_itensmenu_id_item_seq', (select max(id_item) from db_itensmenu));

                insert into db_itensmenu values (nextval('db_itensmenu_id_item_seq'),'Vig�ncia Registro Preco','Vig�ncia Registro Preco','com2_vigenciaregpreco001.php',1,1,'Vig�ncia Registro Preco','t');

                insert into db_menu values(7952,(select max(id_item) from db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 7952),28);

                commit;

              ";

        $this->execute($sql);
    }
}
