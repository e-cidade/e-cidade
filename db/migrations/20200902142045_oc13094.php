<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13094 extends PostgresMigration
{

    public function up()
    {
        $sql = "

        SELECT fc_startsession();

        UPDATE cfautent SET k11_tipautent = 2;";

        $this->execute($sql);
    }

    public function down()
    {

    }
}
