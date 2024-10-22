<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddMenuListaDocumentosAssinatura extends PostgresMigration
{
    public function up()
    {

        $sql = "
        INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Documentos Digitais', 'Documentos Digitais', 'con1_assinaturadigitaldocumentos.php', 1, 1, 'Documentos Digitais', 't');
        INSERT INTO db_menu VALUES (32,(select max(id_item) from db_itensmenu),(select max(menusequencia)+1 from db_menu where id_item = 32 and modulo = 604),604);
        ";

        $this->execute($sql);
    }
}
