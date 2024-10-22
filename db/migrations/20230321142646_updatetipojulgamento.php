<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Updatetipojulgamento extends PostgresMigration
{

    public function up()
    {
        $sql = "
            update liclicita set l20_tipliticacao=8 where l20_tipliticacao=2;
            update liclicita set l20_tipliticacao=4 where l20_tipliticacao=3;
            update liclicita set l20_tipliticacao=5 where l20_tipliticacao=4;
        ";

        $this->execute($sql);
    }
}
