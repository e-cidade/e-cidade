<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18303HotFix extends PostgresMigration
{
    public function up()
    {
        $sql = "BEGIN; ALTER TABLE escola.regenciahorario ADD ed58_d_data date NULL; COMMIT;";
        $this->execute($sql);
    }
}
