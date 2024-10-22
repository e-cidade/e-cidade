<?php

use Phinx\Migration\AbstractMigration;

class Oc22694 extends AbstractMigration
{

    public function up()
    {
        $sSql = " INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),
        'Acordo','Acordo','m4_acordo.php',1,1,'Acordo','t');
        
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help = 'Manutencao de acordo'),(select max(id_item) from db_itensmenu),1,1);
        
         INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),
        'Alteração/Inclusão de dotações','Alteração/Inclusão de dotações','m4_inclusaodotacao.php',1,1,'Alteração/Inclusão de dotações','t');
        
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help = 'Manutencao de acordo'),(select max(id_item) from db_itensmenu),2,1);
        
        
         INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),
        'Alteração Desdobramento','Alteração Desdobramento','m4_acordodesdobramento.php',1,1,'Alteração Desdobramento','t');
        
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help = 'Manutencao de acordo'),(select max(id_item) from db_itensmenu),3,1);";

        $this->execute($sSql);
    }
}
