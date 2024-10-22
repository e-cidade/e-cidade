<?php

use Phinx\Migration\AbstractMigration;

class FixOc22603 extends AbstractMigration
{
    public function up()
    {
        $sql = "
            ALTER TABLE compras.pcplanocontratacao ALTER COLUMN mpc01_data SET NOT NULL;

            DELETE FROM compras.pccatalogo WHERE mpc04_codigo = 99;
            INSERT INTO compras.pccatalogo
              (mpc04_codigo, mpc04_pcdesc)
            VALUES
                (2, 'Outros');

            UPDATE compras.pcpcitem SET
                mpc02_qtdd = NULL,
                mpc02_vlrunit = NULL,
                mpc02_vlrtotal = NULL,
                mpc02_datap = NULL
            WHERE 1 = 1;

            ALTER TABLE compras.pcpcitem DROP COLUMN mpc02_qtdd;
            ALTER TABLE compras.pcpcitem DROP COLUMN mpc02_vlrunit;
            ALTER TABLE compras.pcpcitem DROP COLUMN mpc02_vlrtotal;
            ALTER TABLE compras.pcpcitem DROP COLUMN mpc02_datap;

            ALTER TABLE compras.pcplanocontratacaopcpcitem ADD COLUMN mpcpc01_qtdd NUMERIC(10, 4) DEFAULT 0;
            ALTER TABLE compras.pcplanocontratacaopcpcitem ADD COLUMN mpcpc01_vlrunit NUMERIC(10, 4) DEFAULT 0;
            ALTER TABLE compras.pcplanocontratacaopcpcitem ADD COLUMN mpcpc01_vlrtotal NUMERIC(10, 4) DEFAULT 0;
            ALTER TABLE compras.pcplanocontratacaopcpcitem ADD COLUMN mpcpc01_datap DATE NULL;

        ";

        $this->execute($sql);
    }
}
