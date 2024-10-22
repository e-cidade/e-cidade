<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Updatepcproc extends PostgresMigration
{

    public function up()
    {
        $sql = "
                    UPDATE pcproc
        SET pc80_dispvalor='f'
        WHERE pc80_codproc IN
                (SELECT pc80_codproc
                 FROM pcproc
                 WHERE pc80_dispvalor IS NULL);
        ";
        $this->execute($sql);
    }
}
