<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc12263 extends PostgresMigration
{

    public function up()
    {
        $sql = "alter table aoc122020 alter column si40_dataleialteracao drop not null;
        alter table aoc122020 alter column si40_tipoleialteracao drop not null;
        alter table aoc122020 alter column si40_valorabertolei drop not null;";
        $this->execute($sql);

    }
}
