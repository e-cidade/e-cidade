<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Fix2300Categ extends PostgresMigration
{

    public function up()
    {
        $sql = "
        UPDATE avaliacaopergunta SET db103_camposql = db103_camposql||'InfoDirigenteSindical' WHERE db103_avaliacaogrupopergunta = 4000321;
        ";
        $this->execute($sql);
    }

    public function down()
    {

    }
}
