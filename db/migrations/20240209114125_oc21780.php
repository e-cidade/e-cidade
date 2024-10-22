<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21780 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            update db_itensmenu set descricao='Publicar Resultado',help='Publicar Resultado',desctec='Publicar Resultado' where funcao='com1_pncpresultadodispensaporvalor001.php';
        ";
        $this->execute($sql);
    }
}
