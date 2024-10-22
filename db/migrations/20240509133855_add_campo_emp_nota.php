<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddCampoEmpNota extends PostgresMigration
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
