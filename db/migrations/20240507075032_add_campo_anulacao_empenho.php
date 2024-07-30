<?php

use Phinx\Migration\AbstractMigration;

class AddCampoAnulacaoEmpenho extends AbstractMigration
{
    public function change()
    {
        $this->getAdapter()->setOptions(array_replace($this->getAdapter()->getOptions(), array('schema' => 'empenho')));
        $table = $this->table('empanulado');
        $table->addColumn('e94_id_documento_assinado', 'string', ['null' => true])
            ->addColumn('e94_node_id_libresing', 'string', ['null' => true])
            ->update();
    }
}
