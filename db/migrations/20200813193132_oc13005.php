<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13005 extends PostgresMigration
{

    public function up()
    {
        $sql = "
        UPDATE db_itensmenu SET libcliente = 'f' WHERE descricao LIKE 'Anula%o do Acordo';
        UPDATE db_itensmenu SET libcliente = 'f' WHERE descricao LIKE 'Configurar Cronograma Execu%o';
        UPDATE db_itensmenu SET libcliente = 'f' WHERE funcao = 'ac04_movimentacaomanual001.php';
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
        UPDATE db_itensmenu SET libcliente = 't' WHERE descricao LIKE 'Anula%o do Acordo';
        UPDATE db_itensmenu SET libcliente = 't' WHERE descricao LIKE 'Configurar Cronograma Execu%o';
        UPDATE db_itensmenu SET libcliente = 't' WHERE funcao = 'ac04_movimentacaomanual001.php';
        ";
        $this->execute($sql);
    }
}
