<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22209 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            begin;
            update configuracoes.db_itensmenu set descricao = 'Anexar Documentos no PNCP'
            where funcao = 'con4_anexarArquivos001.php';
            commit;
        ";
        $this->execute($sql);
    }
}
