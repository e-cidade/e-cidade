<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22661 extends PostgresMigration
{

    public function up()
    {
//        $sSql = "
//        BEGIN;
//
//        delete from itemprecoreferencia where si02_itemproccompra in (select pc22_orcamitem from itemprecoreferencia
//        left join pcorcamitem on pc22_orcamitem = si02_itemproccompra where pc22_orcamitem is null);
//
//        ALTER TABLE precoreferencia ADD CONSTRAINT precoreferencia_cgmcotacao_fk FOREIGN KEY(si01_numcgmcotacao) REFERENCES cgm(z01_numcgm);
//        ALTER TABLE precoreferencia ADD CONSTRAINT precoreferencia_cgmorcamento_fk FOREIGN KEY(si01_numcgmorcamento) REFERENCES cgm(z01_numcgm);
//
//        ALTER TABLE itemprecoreferencia ADD CONSTRAINT itemprecoreferencia_itemproccompra_fk FOREIGN KEY(si02_itemproccompra) REFERENCES pcorcamitem(pc22_orcamitem);
//        ALTER TABLE itemprecoreferencia ADD CONSTRAINT itemprecoreferencia_coditem_fk FOREIGN KEY(si02_coditem) REFERENCES pcmater(pc01_codmater);
//        ALTER TABLE itemprecoreferencia ADD CONSTRAINT itemprecoreferencia_codunidadeitem_fk FOREIGN KEY(si02_codunidadeitem) REFERENCES matunid(m61_codmatunid);
//
//        COMMIT;
//        ";
//
//        $this->execute($sSql);
    }
}
