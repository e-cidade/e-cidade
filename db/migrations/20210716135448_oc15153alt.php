<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15153alt extends PostgresMigration
{
    public function up()
    {
        $sql = "UPDATE db_estruturavalor SEt db121_descricao = replace(db121_descricao,'INDIVIDUAIS -','-') WHERE db121_estrutural IN ('164','264');";
        $this->execute($sql);
    }
}
