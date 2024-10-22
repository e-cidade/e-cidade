<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13101 extends PostgresMigration
{

    public function up()
    {
		$sql = 'update acordo set ac16_tipocadastro = 1 where ac16_tipocadastro is null and ac16_anousu = 2020';
		$this->execute($sql);
    }
}
