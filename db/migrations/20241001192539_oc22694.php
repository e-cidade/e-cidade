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
        'Altera��o/Inclus�o de dota��es','Altera��o/Inclus�o de dota��es','m4_inclusaodotacao.php',1,1,'Altera��o/Inclus�o de dota��es','t');
        
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help = 'Manutencao de acordo'),(select max(id_item) from db_itensmenu),2,1);
        
        
         INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),
        'Altera��o Desdobramento','Altera��o Desdobramento','m4_acordodesdobramento.php',1,1,'Altera��o Desdobramento','t');
        
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help = 'Manutencao de acordo'),(select max(id_item) from db_itensmenu),3,1);";

        $this->execute($sSql);
    }
}
