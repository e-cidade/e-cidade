<?php

use Phinx\Migration\AbstractMigration;

class Oc22894 extends AbstractMigration
{

    public function up()
    {
        $sSql = "
        BEGIN;
        ALTER TABLE public.licanexoataspncp ADD COLUMN l216_nomearquivo varchar(100);
        update public.licanexoataspncp set l216_nomearquivo = 'Anexo.pdf' where l216_nomearquivo is null;
        ALTER TABLE public.anexotermospncp ADD COLUMN ac56_nomearquivo varchar(100);
        update public.anexotermospncp set ac56_nomearquivo = 'Anexo.pdf' where ac56_nomearquivo is null;
        COMMIT;";
        $this->execute($sSql);
    }
}
