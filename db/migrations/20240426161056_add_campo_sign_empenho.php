<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddCampoSignEmpenho extends PostgresMigration
{
    public function change()
    {
        $this->getAdapter()->setOptions(array_replace($this->getAdapter()->getOptions(), array('schema' => 'empenho')));
        $table = $this->table('empempenho');
        $table->addColumn('e60_node_id_libresing', 'string', ['null' => true])
            ->update();
    }
}
