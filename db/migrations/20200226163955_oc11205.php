<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11205 extends PostgresMigration
{
    public function up()
    {
		$sql = "ALTER TABLE liccomissaocgm ALTER COLUMN l31_tipo TYPE char(2);";
		$this->execute($sql);
    }

    public function down(){
		$sql = "ALTER TABLE liccomissaocgm ALTER COLUMN l31_tipo TYPE char(1);";
		$this->execute($sql);
	}
}
