<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11315 extends PostgresMigration
{
    public function up(){
        $this->execute("ALTER TABLE processocompralote ALTER COLUMN pc68_nome TYPE varchar(250)");
        $this->execute("ALTER TABLE liclicitemlote ALTER COLUMN l04_descricao TYPE varchar(250)");

    }
}
