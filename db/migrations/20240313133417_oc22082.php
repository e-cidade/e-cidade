<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22082 extends PostgresMigration
{

    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'Bens Valor por Per�odo','Bens Valor por Per�odo','pat2_bensporvalor001.php',1,1,'Bens Valor por Per�odo','t');
            INSERT INTO db_menu VALUES(9150,(select max(id_item) from db_itensmenu),7,439);
        ";
        $this->execute($sql);
    }
}
