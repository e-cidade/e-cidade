<?php

use Phinx\Migration\AbstractMigration;

class OcdividaConsolidada extends AbstractMigration
{

    public function up()
    {
        $sql = <<<SQL
        BEGIN;
        select fc_startsession();

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
        values ((select max(codcam)+1 from db_syscampo), 'op01_datadepublicacaodalei', 'date                                    ', 'Data de Publicação da Lei', '', 'Data de Publicação da Lei', 10, false, false, false, 0, 'text', 'Data de Publicação da Lei');


        ALTER TABLE db_operacaodecredito ADD COLUMN op01_datadepublicacaodalei DATE;

        COMMIT;
SQL;
        $this->execute($sql);
    }        
}
