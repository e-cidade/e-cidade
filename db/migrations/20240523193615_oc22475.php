<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22475 extends PostgresMigration
{
    public function up()
    {
        $sSql = "
        ALTER TABLE emp102024 DROP COLUMN si106_dtassinaturacontrato;
        ALTER TABLE emp102024 ADD column si106_exerciciocontrato int;
        ALTER TABLE emp302024 DROP COLUMN si206_dtassinaturacontrato;
        ALTER TABLE emp302024 ADD column si206_exerciciocontrato int;
        ALTER TABLE contratos202024 DROP COLUMN si87_dtassinaturacontoriginal;
        ALTER TABLE contratos202024 ADD column si87_exerciciocontrato int;
        ALTER TABLE contratos302024 DROP COLUMN si89_dtassinaturacontoriginal;
        ALTER TABLE contratos302024 ADD column si89_exerciciocontrato int;
        ALTER TABLE contratos402024 DROP COLUMN si91_dtassinaturacontoriginal;
        ALTER TABLE contratos402024 ADD column si91_exerciciocontrato int;";
        $this->execute($sSql);
    }
}
