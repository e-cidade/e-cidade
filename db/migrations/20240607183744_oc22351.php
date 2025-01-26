<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22351 extends PostgresMigration
{
    public function up()
    {
        $sql = "
        ALTER TABLE empautoriza add e54_datainclusao timestamp default null;

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e54_datainclusao' ,'date' ,'Data de Inclus�o' ,'' ,'Data de Inclus�o' ,10,'false' ,'false' ,'false' ,3 ,'text' ,'Data de Inclus�o' );

        INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
        VALUES (
        (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'empautoriza'),
        (SELECT codcam FROM db_syscampo WHERE nomecam = 'e54_datainclusao'),
        (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'empautoriza')), 0);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
        ALTER TABLE empautoriza DROP COLUMN e54_datainclusao;

        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'e54_datainclusao');

        DELETE FROM db_syscampo WHERE nomecam = 'e54_datainclusao';
        ";

        $this->execute($sql);
    }
}
