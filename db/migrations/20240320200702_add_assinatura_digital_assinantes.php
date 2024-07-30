<?php

use Phinx\Migration\AbstractMigration;

class AddAssinaturaDigitalAssinantes extends AbstractMigration
{
    public function change()
    {
        $this->getAdapter()->setOptions(array_replace($this->getAdapter()->getOptions(), array('schema' => 'configuracoes')));
        $table = $this->table('assinatura_digital_assinante', ['id' => 'db243_codigo']);
        $table->addColumn('db243_instit', 'integer')
            ->addColumn('db243_orgao', 'integer')
            ->addColumn('db243_unidade', 'integer')
            ->addColumn('db243_usuario', 'integer')
            ->addColumn('db243_cargo', 'integer')
            ->addColumn('db243_documento', 'integer')
            ->addColumn('db243_data_inicio', 'date')
            ->addColumn('db243_data_final', 'date')
            ->addColumn('db243_anousu', 'integer')
            ->addForeignKey('db243_instit', 'db_config', 'codigo', ['delete' => 'CASCADE'])
            ->addForeignKey('db243_usuario', 'db_usuarios', 'id_usuario', ['delete' => 'CASCADE'])
            ->create();
    }
}
