<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16966 extends PostgresMigration
{

    public function up()
    {
        $sql = "alter table bodcasp202021  drop column si202_vlsaldoexeantrecredad;";
        $sql .= "alter table bodcasp302021  rename column si203_vlamortizaoutrasdivinter to si203_vlamortizadividacontratualinternas; ";
        $sql .= "alter table bodcasp302021  rename column si203_vlamortizaoutrasdivext to si203_vlamortizadividacontratualexternas; ";

        $this->execute($sql);
    }
}
