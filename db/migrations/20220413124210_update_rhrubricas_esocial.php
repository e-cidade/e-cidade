<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class UpdateRhrubricasEsocial extends PostgresMigration
{

    public function up()
    {
        $sql = "
        UPDATE rhrubricas SET rh27_codincidirrf = (SELECT db104_sequencial FROM avaliacaoperguntaopcao WHERE db104_identificadorcampo = 'codIncIRRF_09')
        WHERE rh27_codincidirrf IN(3003846,3003845);
        ";
        $this->execute($sql);
    }
}
