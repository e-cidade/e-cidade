<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc19890 extends PostgresMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE bpdcasp712022 ADD column si215_codfontrecursos23 int4 NOT NULL DEFAULT 0");
    }
}
