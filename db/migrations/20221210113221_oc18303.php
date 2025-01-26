<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18303 extends PostgresMigration
{
    public function up()
    {
        $sql =
        "BEGIN;
            INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Agenda de S�bado Letivo','Agenda de S�bado Letivo','edu1_agendasabadoletivo001.php',1,1,'Agenda de S�bado Letivo','t');
            INSERT INTO db_menu VALUES((select distinct m.id_item from db_menu m inner join db_itensmenu s on m.id_item = s.id_item where m.modulo=1100747 and s.descricao='Turmas' and s.desctec='Cadastro de Turmas'),(select max(id_item) from db_itensmenu),7,1100747);
        COMMIT;";
        $this->execute($sql);

    }
}
