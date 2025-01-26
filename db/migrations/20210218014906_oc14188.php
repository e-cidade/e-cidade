<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14188 extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO db_estruturavalor
            SELECT nextval('db_estruturavalor_db121_sequencial_seq'),
                   '150000',
                   '16.02',
                   'Outros servi�os de transporte de natureza municipal.',
                   177,
                   2,
                   2
            FROM db_estruturavalor
            WHERE NOT EXISTS
                (SELECT 1
                 FROM db_estruturavalor
                 WHERE db121_db_estrutura = 150000
                   AND db121_estrutural = '16.02')
            LIMIT 1;
        ");
    }
}
