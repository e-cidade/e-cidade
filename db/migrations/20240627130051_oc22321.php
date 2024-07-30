<?php

use Phinx\Migration\AbstractMigration;

class Oc22321 extends AbstractMigration
{
    public function up()
    {   
        $sql = "ALTER TABLE rhempenhofolhaempenho ADD rh76_lota int8 DEFAULT NULL;

                INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) 
                VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'rh76_lota' ,'int8' ,'Lotação' ,'' ,'Lotação' ,10,'false' ,'false' ,'false' ,3 ,'text' ,'Lotação' );

                INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
                VALUES (
                    (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'rhempenhofolhaempenho'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh76_lota'),
                    (SELECT COALESCE(MAX(seqarq), 0) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'rhempenhofolhaempenho')),0);

                ALTER TABLE orcreservarhempenhofolha  ADD o120_lota int8 DEFAULT NULL;

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
                VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'o120_lota', 'int8', 'Lotação', '', 'Lotação', 10, 'false', 'false', 'false', 3, 'text', 'Lotação');

                INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
                VALUES (
                    (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'orcreservarhempenhofolha'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'o120_lota'),
                    (SELECT COALESCE(MAX(seqarq), 0) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'orcreservarhempenhofolha')),0);

                ALTER TABLE rhempenhofolharhemprubrica ADD rh81_lancamento int8 DEFAULT NULL;

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
                VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'rh81_lancamento', 'int8', 'Empenho de lançamento', '', 'Empenho de lançamento', 10, 'false', 'false', 'false', 3, 'text', 'Empenho de lançamento');

                INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
                VALUES (
                    (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'rhempenhofolharhemprubrica'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh81_lancamento'),
                    (SELECT COALESCE(MAX(seqarq), 0) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'rhempenhofolharhemprubrica')),0);

                ALTER TABLE rhempenhofolharhemprubrica ADD rh81_lota int8 DEFAULT NULL;
                
                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
                VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'rh81_lota', 'int8', 'Lotação', '', 'Lotação', 10, 'false', 'false', 'false', 3, 'text', 'Lotação');
                
                INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
                VALUES (
                    (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'rhempenhofolharhemprubrica'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh81_lota'),
                    (SELECT COALESCE(MAX(seqarq), 0) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'rhempenhofolharhemprubrica')),0);";                    

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh76_lota');
        
                DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'o120_lota');
                
                DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh81_lancamento');

                DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh81_lota');

                DELETE FROM db_syscampo WHERE nomecam = 'rh76_lota';

                DELETE FROM db_syscampo WHERE nomecam = 'o120_lota';

                DELETE FROM db_syscampo WHERE nomecam = 'rh81_lancamento';

                DELETE FROM db_syscampo WHERE nomecam = 'rh81_lota';
            
                ALTER TABLE rhempenhofolhaempenho DROP COLUMN rh76_lota;
                
                ALTER TABLE orcreservarhempenhofolha DROP COLUMN o120_lota;

                ALTER TABLE rhempenhofolharhemprubrica DROP COLUMN rh81_lancamento;

                ALTER TABLE rhempenhofolharhemprubrica DROP COLUMN rh81_lota;";

        $this->execute($sql);
    }
}
