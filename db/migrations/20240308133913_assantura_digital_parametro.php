<?php

use Phinx\Migration\AbstractMigration;

class AssanturaDigitalParametro extends AbstractMigration
{
    public function change()
    {
        $this->getAdapter()->setOptions(array_replace($this->getAdapter()->getOptions(), array('schema' => 'configuracoes')));
        $table = $this->table('assinatura_digital_parametro', ['id' => 'db242_codigo']);
        $table->addColumn('db242_assinador_url', 'string')
              ->addColumn('db242_assinador_token', 'string')
              ->addColumn('db242_instit', 'integer')
              ->addColumn('db242_assinador_ativo', 'boolean')
              ->addForeignKey('db242_instit', 'configuracoes.db_config', 'codigo', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->create();
    }
}
