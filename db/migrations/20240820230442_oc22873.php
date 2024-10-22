<?php

use Phinx\Migration\AbstractMigration;

class Oc22873 extends AbstractMigration
{

    public function up()
    {
        $sql = "ALTER TABLE cfpess ADD r11_tipoempenho int8 default 1;

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )  
        VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'r11_tipoempenho' ,'int8' ,'Tipo de Empenho Padrão' ,'1' ,'Tipo de Empenho Padrão' ,10,'false' ,'false' ,'false' ,3 ,'text' ,'Tipo de Empenho Padrão' );
        
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
        VALUES (
         (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'cfpess'),
         (SELECT codcam FROM db_syscampo WHERE nomecam = 'r11_tipoempenho'),
         (SELECT COALESCE(MAX(seqarq), 0) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'cfpess')),0);";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'r11_tipoempenho');

        DELETE FROM db_syscampo WHERE nomecam = 'r11_tipoempenho';
    
        ALTER TABLE cfpess DROP COLUMN r11_tipoempenho;";

        $this->execute($sql);
    }
}
