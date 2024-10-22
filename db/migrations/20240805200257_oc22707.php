<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22707 extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL
        BEGIN;
        select fc_startsession();

        CREATE TABLE contabilidade.dv_dividaconsolidadapcasp (
            dv01_sequencial SERIAL PRIMARY KEY,
            dv01_codoperacaocredito INT8,
            dv01_principaldividalongoprazop INT8 NULL,
            dv01_principaldividacurtoprazop INT8 NULL,
            dv01_principaldividaf INT8 NULL,
            dv01_jurosdividalongoprazop INT8 NULL,
            dv01_jurosdividacurtoprazop INT8 NULL,
            dv01_jurosdividaf INT8 NULL,
            dv01_reduzido INT8 NULL,
            dv01_anousu INT8 NULL,
            CONSTRAINT fk_dv01_codoperacaocredito FOREIGN KEY (dv01_codoperacaocredito) REFERENCES db_operacaodecredito(op01_sequencial),
            CONSTRAINT fk_conplanoreduz_reduzido_anousu FOREIGN KEY (dv01_reduzido, dv01_anousu) REFERENCES conplanoreduz(c61_reduz, c61_anousu)
        );

        COMMIT;
SQL;
        $this->execute($sql);
    }
}
