<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddMenuAssinaturaDigital extends PostgresMigration
{
    public function up()
    {
        $sql = "
        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Assinatura Digital', 'Assinatura Digital', ' ', 1, 1, 'Assinatura Digital', 't');
        INSERT INTO db_menu VALUES(29,(select max(id_item) from db_itensmenu),278,(select id_item from db_modulos where nome_manual like 'configuracao'));
        ";

        $this->execute($sql);


        $sql = "
        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Parâmetros', 'Parâmetros', 'con1_assinaturadigitalparametros.php', 1, 1, 'Parâmetros', 't');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao='Assinatura Digital'),(select max(id_item) from db_itensmenu),1,(select id_item from db_modulos where nome_manual like 'configuracao'));
        ";

        $this->execute($sql);

        $sql = "
        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Assinantes', 'Assinantes', 'con1_assinaturadigitalassinantes.php', 1, 1, 'Parâmetros', 't');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao='Assinatura Digital'),(select max(id_item) from db_itensmenu),2,(select id_item from db_modulos where nome_manual like 'configuracao'));
        ";

        $this->execute($sql);
    }
}
