<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addcampoprocproc extends PostgresMigration
{

    public function up()
    {
        $sql = "alter table pcproc add column pc80_categoriaprocesso int4";
        $this->execute($sql);
    }
}
