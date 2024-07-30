<?php

use Phinx\Migration\AbstractMigration;

class AddCampoSlip extends AbstractMigration
{
    public function change()
    {
        $this->getAdapter()->setOptions(array_replace($this->getAdapter()->getOptions(), array('schema' => 'caixa')));
        $table = $this->table('slip');
        $table->addColumn('k17_id_documento_assinado', 'string', ['null' => true])
            ->addColumn('k17_node_id_libresing', 'string', ['null' => true])
            ->update();
    }
}
