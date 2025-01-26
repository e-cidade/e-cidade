<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21069 extends PostgresMigration
{
    public function up()
    {
        $sql = "alter table pcproc add column pc80_modalidadecontratacao int4;

                UPDATE db_itensmenu
                SET descricao='Contrata��o Direta - Sem concorr�ncia'
                WHERE id_item IN
                        (SELECT id_item
                         FROM db_itensmenu
                         WHERE funcao = 'com1_pncpdispensaporvalor001.php' );";

        $this->execute($sql);
    }
}
