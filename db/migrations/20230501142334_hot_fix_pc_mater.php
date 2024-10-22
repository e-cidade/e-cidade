<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class HotFixPcMater extends PostgresMigration
{
    public function up()
    {
        $this->execute("update pcmater set pc01_codmaterant=null where pc01_codmaterant = 0");
    }
}
