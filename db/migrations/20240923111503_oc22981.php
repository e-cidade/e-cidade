<?php

use Phinx\Migration\AbstractMigration;

class Oc22981 extends AbstractMigration
{
    public function up()
    {
        $sql = <<<SQL
        BEGIN;

        SELECT fc_startsession();

        ALTER TABLE db_operacaodecredito ADD COLUMN op01_datadecadastro DATE;

        ALTER TABLE contabilidade.dv_dividaconsolidadapcasp ADD COLUMN dv01_controlelrf INT8 NULL;

        INSERT INTO configuracoes.db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        values ((select max(codcam)+1 from configuracoes.db_syscampo), 'op01_datadecadastro', 'date                                    ', 'Data de Cadastro', '', 'Data de Cadastro', 10, false, false, false, 0, 'text', 'Data de Cadastro');

        UPDATE db_operacaodecredito
        SET op01_datadecadastro = op01_dataassinaturacop
        WHERE op01_datadecadastro IS NULL;

        COMMIT;
        SQL;
        $this->execute($sql);
    }
}
