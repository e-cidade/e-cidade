<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Updatepresencial extends PostgresMigration
{

    public function up()
    {
        $sql = "update cflicita set l03_presencial='f' where l03_pctipocompratribunal in (54,50);";

        $this->execute($sql);
    }
}
