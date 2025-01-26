<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class RemoveOpcaoirrfRubricaEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = "
        UPDATE avaliacaoresposta SET db106_avaliacaoperguntaopcao = (SELECT db104_sequencial FROM avaliacaoperguntaopcao WHERE db104_identificadorcampo = 'codIncIRRF_09')
        WHERE db106_avaliacaoperguntaopcao IN(3003846,3003845);
        UPDATE avaliacaoperguntaopcao SET db104_descricao = 'Dedu��es IRRF - Previd�ncia Social Oficial - PSO - Remuner. mensal',
        db104_identificador='deducoes-irrf-previdencia-social-oficial-pso-remun' WHERE db104_sequencial = 3003834;
        DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial = 3003846;
        DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial = 3003845;
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
        INSERT INTO avaliacaoperguntaopcao VALUES (3003846,3000948,'Rendimento n�o tribut�vel','f','rendimento-nao-tributavel',0,'00','codIncIRRF_00');
        INSERT INTO avaliacaoperguntaopcao VALUES (3003845,3000948,'Rendimento n�o tribut�vel em fun��o de acordos internacionais de bitributa��o','f','rendimento-nao-tributavel-em-funcao-de-acordos-int',1,'1','codIncIRRF_01');
        ";
        $this->execute($sql);
    }
}
