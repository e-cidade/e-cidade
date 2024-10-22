<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22454 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        create table patrimonio.bensexcluidos (
            t136_sequencial bigint default 0 not null,
            t136_bens int8 not null,
            t136_empnotaitem int8 not null,
            t136_numcgm int8 not null,
            t136_instit int8 not null,
            t136_depart int8 not null,
            t136_codcla int8 not null,
            t136_bensmarca int8 not null,
            t136_bensmodelo int8 not null,
            t136_bensmedida int8 not null,
            t136_descr varchar(250) not null,
            t136_dtaqu date not null,
            t136_obs text not null,
            t136_valaqu float8,
            t136_ident varchar(20) not null
            );

            CREATE SEQUENCE bensexcluidos_t136_sequencial_seq
            START WITH 1
            INCREMENT BY 1
            NO MINVALUE
            NO MAXVALUE
            CACHE 1;


        COMMIT;

SQL;
        $this->execute($sql);
    }
}
