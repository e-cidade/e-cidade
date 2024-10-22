<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addcamporegimobiliario extends PostgresMigration
{

    public function up()
    {
        $sql = "alter table pcmater add column pc01_regimobiliario text";
        $this->execute($sql);
    }
}
