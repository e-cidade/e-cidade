<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18271 extends PostgresMigration
{
    public function up()
    {
        $sql = "begin;

        select fc_startsession();

        insert into db_syscampo values ((select max(codcam)+1 from db_syscampo),'pc01_instit','int4','Institui��o',0,'Institui��o',4,'f','f','f',1,'text','Institui��o');
        insert into db_syscampo values ((select max(codcam)+1 from db_syscampo),'pc01_codmaterant','int4','Cod Mat Ant',0,'Cod Mat Ant',4,'f','f','f',1,'text','Cod Mat Ant');

        ";

        $this->execute($sql);
    }
}
