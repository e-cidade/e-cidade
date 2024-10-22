<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20239 extends PostgresMigration
{
    public function up()
    {
            $sql = "
            select fc_startsession();

            begin;

            delete from rubricasesocial where e990_sequencial in ('1016','1017');

            insert into rubricasesocial values ('1016','Férias'),('1017','Terço constitucional de férias');

            commit;
            ";

            $this->execute($sql);
        }
}
