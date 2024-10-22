<?php

use Phinx\Migration\AbstractMigration;

class Oc22823 extends AbstractMigration
{
    public function up()
    {
        $sql = "ALTER TABLE empenho.pagordem ADD COLUMN e50_contafornecedor int8 default null;

        INSERT INTO configuracoes.db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        VALUES ((SELECT max(codcam) + 1 FROM configuracoes.db_syscampo) ,'e50_contafornecedor' ,'int8' ,'Conta Fornecedor' ,'1' ,'Conta Fornecedor' ,10,'false' ,'false' ,'false' ,3 ,'text' ,'Conta Fornecedor' );

        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
        VALUES (
         (SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = 'pagordem'),
         (SELECT codcam FROM configuracoes.db_syscampo WHERE nomecam = 'e50_contafornecedor'),
         (SELECT COALESCE(MAX(seqarq), 0) + 1 FROM configuracoes.db_sysarqcamp WHERE codarq = (SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = 'pagordem')),0);";

        $this->execute($sql);
    }
}
