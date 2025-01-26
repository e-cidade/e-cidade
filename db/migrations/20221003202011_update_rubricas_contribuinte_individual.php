<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class UpdateRubricasContribuinteIndividual extends PostgresMigration
{

    public function up()
    {

        $sql = "
            UPDATE rhrubricas SET rh27_codincidirrf = (select db104_sequencial  from avaliacaoperguntaopcao where db104_descricao ilike 'Parte n�o tribut�vel do valor de servi�o de transporte de passageiros ou cargas' LIMIT 1) WHERE rh27_rubric IN ('R002','R003');
                    ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            UPDATE rhrubricas SET rh27_codincidirrf = 4003071 WHERE rh27_rubric IN ('R002','R003');
                    ";
        $this->execute($sql);
    }
}
