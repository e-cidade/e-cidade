<?php

use Phinx\Migration\AbstractMigration;

class Oc16074 extends AbstractMigration
{
    public function change()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        INSERT INTO naturrecsiops VALUES
        ('91111801110100', '91111801110000', 2021),
        ('91111801110200', '91111801110000', 2021),
        ('91111801110300', '91111801110000', 2021),
        ('91111802310100', '91111802310000', 2021),
        ('91111802310200', '91111802310000', 2021),
        ('91111802310300', '91111802310000', 2021),
        ('92111303110100', '92111300000000', 2021),
        ('92111303110200', '92111300000000', 2021),
        ('92111303110300', '92111300000000', 2021),
        ('92161001110100', '92161000000000', 2021),
        ('92161001110200', '92161000000000', 2021),
        ('92161001110300', '92161000000000', 2021),
        ('92161001120000', '92161000000000', 2021),
        ('92169099110300', '92169000000000', 2021),
        ('92169099110400', '92169000000000', 2021),
        ('92169099110900', '92169000000000', 2021),
        ('92192299110000', '92192200000000', 2021),
        ('92199099110000', '92199099000000', 2021),
        ('93111801120100', '93111801120000', 2021),
        ('93111801120200', '93111801120000', 2021),
        ('93111801120300', '93111801120000', 2021),
        ('93111801140100', '93111801140000', 2021),
        ('93111801140200', '93111801140000', 2021),
        ('93111801140300', '93111801140000', 2021),
        ('93111802340100', '93111802340000', 2021),
        ('93111802340200', '93111802340000', 2021),
        ('93111802340300', '93111802340000', 2021),
        ('93112102240100', '93112100000000', 2021),
        ('93112801940100', '93112801900000', 2021),
        ('93132100410000', '93132100400000', 2021),
        ('93199099120100', '93199099000000', 2021),
        ('95111201110100', '95111201000000', 2021),
        ('95171801210100', '95171801200000', 2021),
        ('95171801510100', '95171801500000', 2021),
        ('95171806110100', '95171806000000', 2021),
        ('95172801110100', '95172801100000', 2021),
        ('95172801210100', '95172801200000', 2021),
        ('95172801310100', '95172801300000', 2021);

        INSERT INTO naturdessiops VALUES
        ('3191920000', '3191990000', 2021),
        ('3390080000', '3390080000', 2021),
        ('3190115200', '3190119900', 2021);
        

        COMMIT;

SQL;
        $this->execute($sql);

    }
}