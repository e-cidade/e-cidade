<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class CreateSeqProcessoLote extends PostgresMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE processocompraloteitem ADD pc69_seq int8 NULL");
        $this->execute("ALTER TABLE liclicitemlote ADD L04_seq int8 NULL");
    }
}
