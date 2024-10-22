<?php

use Phinx\Migration\AbstractMigration;

class Oc22708Obrigadivida extends AbstractMigration
{
    public function up()
    {
        $sql = <<<SQL
        BEGIN;

        SELECT fc_startsession();

        ALTER TABLE empenho.empparametro ADD COLUMN e30_obrigadivida bool default true;

        INSERT INTO configuracoes.db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from configuracoes.db_syscampo), 'e30_obrigadivida', 'bool                                    ', 'Controla Dívida Consolidada', true, 'Controla Dívida Consolidada', 5, false, false, false, 1, 'text', 'Controla Dívida Consolidada');

        COMMIT;
SQL;
        $this->execute($sql);
    }
}
