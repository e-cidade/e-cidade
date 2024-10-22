<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class OcNoTaskIgor extends PostgresMigration
{

    public function up()
    {
        $sql = "SELECT setval('orcparamrelperiodos_o113_sequencial_seq',
                  (SELECT max(o113_sequencial)
                   FROM orcparamrelperiodos));


                SELECT setval('orcparamseqorcparamseqcoluna_o116_sequencial_seq',
                  (SELECT max(o116_sequencial)
                   FROM orcparamseqorcparamseqcoluna));";
        $this->execute($sql);
    }

    public function down()
    {

    }
}
