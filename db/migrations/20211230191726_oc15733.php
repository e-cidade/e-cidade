<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15733 extends PostgresMigration
{

    public function up()
    {
        $sql = "INSERT into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Alterar dota��o credenciamento','Alterar dota��o credenciamento','ac04_alteradotacaocredenciamento001.php',1,1,'Alterar dota��o credenciamento','t');
         INSERT into db_menu values (8289,(select max(id_item) from db_itensmenu),10,8251);";
        $this->execute($sql);
    }
}
