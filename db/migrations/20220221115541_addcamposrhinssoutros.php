<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Addcamposrhinssoutros extends PostgresMigration
{

    public function up()
    {
        $sql = "
            ALTER TABLE rhinssoutros ADD COLUMN rh51_indicadesconto int4;
            ALTER TABLE rhinssoutros ADD COLUMN rh51_cgcvinculo varchar(14);
            ALTER TABLE rhinssoutros ADD COLUMN rh51_categoria int4;

            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'rh51_cgcvinculo'       ,'text' ,'CNPJ/CPF do Outro Vinculo'     ,'', 'CNPJ/CPF do Outro Vinculo'      ,14 ,false, false, false, 0, 'text', 'CNPJ/CPF do Outro Vinculo');
            INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'rh51_categoria'        ,'text' ,' Categoria do Trabalhador no outro V�nculo'     ,'', ' Categoria do Trabalhador no outro V�nculo'      ,3 ,false, false, false, 0, 'text', '');
        ";
        $this->execute($sql);
    }
}
