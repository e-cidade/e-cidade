<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15508 extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-PostgresMigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $nomeCampo = "k29_exibeobservacao";
        $sql = "
            BEGIN;
                SELECT fc_startsession();

                -- Insere novo campo
                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), '{$nomeCampo}', 'bool', 'Exibe observa��o em ordem banc�ria',
                    'f', 'Exibe observa��o em ordem banc�ria', 1, FALSE, FALSE, FALSE, 5, 'select', 'Exibe observa��o em ordem banc�ria');

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'caiparametro'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = '{$nomeCampo}'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'caiparametro')), 0);

                ALTER TABLE caiparametro ADD COLUMN {$nomeCampo} bool DEFAULT 't';
            COMMIT";
        $this->execute($sql);
    }
}
