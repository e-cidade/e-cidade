<?php

use Phinx\Migration\AbstractMigration;

class AddCampoEmpenho extends AbstractMigration
{
    public function change()
    {
        $this->getAdapter()->setOptions(array_replace($this->getAdapter()->getOptions(), array('schema' => 'empenho')));
        $table = $this->table('empempenho');
        $table->addColumn('e60_id_documento_assinado', 'string', ['null' => true])
            ->update();
    }
}
