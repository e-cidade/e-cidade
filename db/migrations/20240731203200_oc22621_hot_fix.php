<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22621HotFix extends PostgresMigration
{

    public function up()
    {
        /*$sSql = "
        BEGIN;

        update precoreferencia set si01_numcgmcotacao = (select z01_numcgm from cgm where z01_cgccpf = '46418911687' limit 1)
        where si01_sequencial in (select si01_sequencial from precoreferencia left join cgm on z01_numcgm = si01_numcgmcotacao where z01_numcgm is null);

        delete from itemprecoreferencia where si02_itemproccompra in (select si02_itemproccompra from itemprecoreferencia
        left join pcorcamitem on pc22_orcamitem = si02_itemproccompra where pc22_orcamitem is null);

        ALTER TABLE precoreferencia DROP constraint IF exists precoreferencia_cgmcotacao_fk;
        ALTER TABLE precoreferencia DROP constraint IF exists precoreferencia_cgmorcamento_fk;
       	ALTER TABLE itemprecoreferencia DROP constraint IF exists itemprecoreferencia_itemproccompra_fk;
       	ALTER TABLE itemprecoreferencia DROP constraint IF exists itemprecoreferencia_coditem_fk;
       	ALTER TABLE itemprecoreferencia DROP constraint IF exists itemprecoreferencia_codunidadeitem_fk;

        ALTER TABLE precoreferencia ADD CONSTRAINT precoreferencia_cgmcotacao_fk FOREIGN KEY(si01_numcgmcotacao) REFERENCES cgm(z01_numcgm);
        ALTER TABLE precoreferencia ADD CONSTRAINT precoreferencia_cgmorcamento_fk FOREIGN KEY(si01_numcgmorcamento) REFERENCES cgm(z01_numcgm);

        ALTER TABLE itemprecoreferencia ADD CONSTRAINT itemprecoreferencia_itemproccompra_fk FOREIGN KEY(si02_itemproccompra) REFERENCES pcorcamitem(pc22_orcamitem);
        ALTER TABLE itemprecoreferencia ADD CONSTRAINT itemprecoreferencia_coditem_fk FOREIGN KEY(si02_coditem) REFERENCES pcmater(pc01_codmater);
        ALTER TABLE itemprecoreferencia ADD CONSTRAINT itemprecoreferencia_codunidadeitem_fk FOREIGN KEY(si02_codunidadeitem) REFERENCES matunid(m61_codmatunid);

        COMMIT;
        ";

        $this->execute($sSql);*/
    }
}
