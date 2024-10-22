<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc12731 extends PostgresMigration
{
    public function up()
    {
        $sql = "

        INSERT INTO db_menu VALUES (
            1818,
            2000032,
            (SELECT max(menusequencia)+1 as count FROM db_menu  WHERE id_item = 1818),
            (381));
";
        $this->execute($sql);
    }
}
