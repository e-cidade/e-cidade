<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20736 extends PostgresMigration
{
    public function up()
    {
        $sSql =
            "
                BEGIN;
                --Cria o campo k02_descrjm na tabela tabrecjm no m�dulo caixa.
                ALTER TABLE tabrecjm ADD k02_descrjm varchar(40) NULL;
                --Inser��o dos dados do campo na tabela db_syscampo no m�dulo configuracoes.
                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES(2013000, 'k02_descrjm                             ', 'varchar(40)                             ', 'Descri��o de juros e multa', '', 'Descri��o de juros e multa', 40, NULL, true, false, 0, NULL, 'Descri��o de juros e multa');
                --Altera��o do sequencial do campo codcam da tabela db_syscampo.
                ALTER SEQUENCE db_syscampo_codcam_seq RESTART WITH 2013001;
                COMMIT;
            ";
        $this->execute($sSql);
    }
}
