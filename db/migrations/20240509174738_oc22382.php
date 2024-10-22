<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22382 extends PostgresMigration
{

    public function up()
    {
        $sSql = "

        BEGIN;
        ALTER TABLE pcorcamfornelic ADD COLUMN pc31_representante varchar(100);
        ALTER TABLE pcorcamfornelic ADD COLUMN pc31_cpf varchar(11);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),
        'Habilitação de Fornecedores','Habilitação de Fornecedores','lic1_habilitacaofornecedor.php',1,1,'Habilitação de Fornecedores','t');

        INSERT INTO db_menu VALUES(1818,(select max(id_item) from db_itensmenu),6,381);
        COMMIT;";

        $this->execute($sSql);

    }
}
