<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18229 extends PostgresMigration
{

    public function up()
    {
        $sql = "INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'Solicita��o de Compras (Novo)','Solicita��o de Compras (Novo)','',1,1,'Solicita��o de Compras (Novo)','t');
        INSERT INTO db_menu VALUES(32,(select max(id_item) from db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 32 and modulo = 28),28);



        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','com1_solicitanovo001.php',1,1,'Inclus�o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Solicita��o de Compras (Novo)%'),(select max(id_item) from db_itensmenu),1,28);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','com1_solicitanovo002.php',1,1,'Altera��o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Solicita��o de Compras (Novo)%'),(select max(id_item) from db_itensmenu),2,28);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','com1_solicitanovo003.php',1,1,'Exclus�o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Solicita��o de Compras (Novo)%'),(select max(id_item) from db_itensmenu),3,28);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Alterar Dota��es','Alterar Dota��es','com4_alterardotacaosolicitacao001.php',1,1,'Alterar Dota��es','t');


INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Solicita��o de Compras (Novo)%'),(select max(id_item) from db_itensmenu),4,28);

INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Anula��o','Anula��o','com4_anularsolicitacaocompras001.php',1,1,'Anula��o','t');


INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Solicita��o de Compras (Novo)%'),(select max(id_item) from db_itensmenu),5,28);

        ";

        $this->execute($sql);
    }
}
