<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16226 extends PostgresMigration
{

    public function up()
    {
        $sql = "insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','con4_adesaoitensregprecos001.php',1,1,'Inclus�o','t');
        insert into db_menu values ((select id_item from db_itensmenu where descricao = 'Ades�o de registro de pre�o'),(select max(id_item) from db_itensmenu),1,28);

        insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','con4_adesaoitensregprecos002.php',1,1,'Altera��o','t');
        insert into db_menu values ((select id_item from db_itensmenu where descricao = 'Ades�o de registro de pre�o'),(select max(id_item) from db_itensmenu),2,28);

        insert into db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','con4_adesaoitensregprecos003.php',1,1,'Exclus�o','t');
        insert into db_menu values ((select id_item from db_itensmenu where descricao = 'Ades�o de registro de pre�o'),(select max(id_item) from db_itensmenu),3,28);";

        $this->execute($sql);
    }
}
