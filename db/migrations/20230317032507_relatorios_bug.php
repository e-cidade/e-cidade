<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class RelatoriosBug extends PostgresMigration
{
    public function change()
    {
        $sql = "
        begin;
            select setval('orcparamseqorcparamseqcoluna_o116_sequencial_seq',(select MAX(o116_sequencial) from orcparamseqorcparamseqcoluna));
        commit;";
        $this->execute($sql);
    }
}
