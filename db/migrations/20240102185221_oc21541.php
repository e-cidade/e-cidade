<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21541 extends PostgresMigration
{
    public function up()
    {
        $sql = "
        select fc_startsession();

        begin;

        alter table rhfuncao add column rh37_dedicacaoexc bool default 'f';

        insert into db_syscampo values ((select max(codcam)+1 from db_syscampo),'rh37_dedicacaoexc','bool','Dedica��o Exclusiva','false','Dedica��o Exclusiva',1,'t','f','f',5,'text','Dedica��o Exclusiva');

        commit;";

        $this->execute($sql);
    }
}
