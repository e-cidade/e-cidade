<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17504 extends PostgresMigration
{

    public function up()
    {
        $sql = "
                INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Ades�o de Registro de Pre�o','Ades�o de Registro de Pre�o','m5_adesaoregpreco.php',1,1,'Ades�o de Registro de Pre�o','t');
                INSERT INTO db_menu VALUES((select id_item from db_itensmenu where help like'%amentos (Patrimonial)%'),(select max(id_item) from db_itensmenu),6,1);

                ALTER TABLE adesaoregprecos ADD COLUMN si06_departamento int8;
                ALTER TABLE adesaoregprecos ADD COLUMN si06_codunidadesubant varchar(8);";
        $this->execute($sql);
    }
}
