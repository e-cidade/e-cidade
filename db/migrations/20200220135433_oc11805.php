<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11805 extends PostgresMigration
{

    public function up()
    {
		$sql = "ALTER TABLE dispensa102020 ADD COLUMN si74_tipocadastro bigint;";
		$this->execute($sql);
    }

    public function down(){
		$sql = "ALTER TABLE dispensa102020 DROP COLUMN si74_tipocadastro;";
		$this->execute($sql);
	}
}
