<?php

use Phinx\Migration\AbstractMigration;

class Criamenusmigracaocompras extends AbstractMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO configuracoes.db_itensmenu VALUES ((select max(id_item)+1 from configuracoes.db_itensmenu), 'Migração', 'Migração', ' ', 1, 1, 'Migração Compras', 'f');
            INSERT INTO configuracoes.db_menu VALUES(28,(select max(id_item) from configuracoes.db_itensmenu),50,28);


            INSERT INTO configuracoes.db_itensmenu VALUES ((select max(id_item)+1 from configuracoes.db_itensmenu), 'Sub Grupos', 'Sub Grupos', 'com1_subgrupos.php', 1, 1, 'Migração Sub Grupos', 't');
            INSERT INTO configuracoes.db_menu VALUES((select max(id_item) from configuracoes.db_itensmenu where descricao='Migração'),(select max(id_item) from configuracoes.db_itensmenu),1,28);
        ";

        $this->execute($sql);
    }
}
