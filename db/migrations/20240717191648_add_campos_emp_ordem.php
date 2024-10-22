<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddCamposEmpOrdem extends PostgresMigration
{
    public function change()
    {
        $this->getAdapter()->setOptions(array_replace($this->getAdapter()->getOptions(), array('schema' => 'empenho')));
        $table = $this->table('empord');
        $table->addColumn('e82_id_documento_assinado', 'string', ['null' => true])
            ->addColumn('e82_node_id_libresing', 'string', ['null' => true])
            ->update();
    }
}
