<?php

use Phinx\Migration\AbstractMigration;

class AddCampoEmpNota extends AbstractMigration
{
    public function change()
    {
        $this->getAdapter()->setOptions(array_replace($this->getAdapter()->getOptions(), array('schema' => 'empenho')));
        $table = $this->table('empnota');
        $table->addColumn('e69_id_documento_assinado', 'string', ['null' => true])
            ->addColumn('e69_node_id_libresing', 'string', ['null' => true])
            ->update();
    }
}