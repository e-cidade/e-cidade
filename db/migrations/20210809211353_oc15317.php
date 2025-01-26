<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15317 extends PostgresMigration
{

    public function up()
    {
        $sSql = "
        INSERT INTO db_syscampo VALUES ((SELECT MAX(codcam)+1 FROM db_syscampo),'rh02_tipcatprof','int4','Categoria Profissional SIOPE', 0, 'Categoria Profissional SIOPE', 10, 't', 't', 'f', 1, 'text', 'Categoria Profissional SIOPE');
        INSERT INTO db_sysarqcamp VALUES (1158, (SELECT MAX(codcam) FROM db_syscampo), (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq=1158));

        ALTER TABLE rhpessoalmov ADD COLUMN rh02_art61ldb1 boolean default false;
        INSERT INTO db_syscampo VALUES ((SELECT MAX(codcam)+1 FROM db_syscampo),'rh02_art61ldb1','boolean','Professores habilitados em n�vel m�dio ou superior para doc�ncia na educa��o infantil e nos ensinos fundamental e m�dio', 0, 'Professores habilitados em n�vel m�dio ou superior', 1, 'f', 'f', 'f', NULL, NULL, 'Professores habilitados em n�vel m�dio');
        INSERT INTO db_sysarqcamp VALUES (1158, (SELECT MAX(codcam) FROM db_syscampo), (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq=1158));

        ALTER TABLE rhpessoalmov ADD COLUMN rh02_art61ldb2 boolean default false;
        INSERT INTO db_syscampo VALUES ((SELECT MAX(codcam)+1 FROM db_syscampo),'rh02_art61ldb2','boolean','Trabalhadores em educa��o portadores de diploma de pedagogia, com habilita��o em administra��o, planejamento, supervis�o, inspe��o e orienta��o educacional, bem como com t�tulos de mestrado ou doutorado nas mesmas �reas', 0, 'Trabalhadores em educa��o portadores de diploma', 1, 'f', 'f', 'f', NULL, NULL, 'Trabalhadores em educa��o portadores');
        INSERT INTO db_sysarqcamp VALUES (1158, (SELECT MAX(codcam) FROM db_syscampo), (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq=1158));

        ALTER TABLE rhpessoalmov ADD COLUMN rh02_art61ldb3 boolean default false;
        INSERT INTO db_syscampo VALUES ((SELECT MAX(codcam)+1 FROM db_syscampo),'rh02_art61ldb3','boolean','Trabalhadores em educa��o portadores de diploma de curso t�cnico ou superior em �rea pedag�gica ou afim', 0, 'Trabalhadores em educa��o portadores de diploma', 1, 'f', 'f', 'f', NULL, NULL, 'Trabalhadores em educa��o portadores');
        INSERT INTO db_sysarqcamp VALUES (1158, (SELECT MAX(codcam) FROM db_syscampo), (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq=1158));

        ALTER TABLE rhpessoalmov ADD COLUMN rh02_art61ldb4 boolean default false;
        INSERT INTO db_syscampo VALUES ((SELECT MAX(codcam)+1 FROM db_syscampo),'rh02_art61ldb4','boolean','Profissionais com not�rio saber reconhecido pelos respectivos sistemas de ensino, para ministrar conte�dos de �reas afins � sua forma��o ou experi�ncia profissional, atestados por titula��o espec�fica ou pr�tica de ensino em unidades educacionais da rede p�blica ou privada ou das corpora��es privadas em que tenham atuado, exclusivamente para atender ao inciso V do caput do art. 36', 0, 'Profissionais com not�rio saber reconhecido pelos', 1, 'f', 'f', 'f', NULL, NULL, 'Profissionais com not�rio saber reconhec');
        INSERT INTO db_sysarqcamp VALUES (1158, (SELECT MAX(codcam) FROM db_syscampo), (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq=1158));

        ALTER TABLE rhpessoalmov ADD COLUMN rh02_art61ldb5 boolean default false;
        INSERT INTO db_syscampo VALUES ((SELECT MAX(codcam)+1 FROM db_syscampo),'rh02_art61ldb5','boolean','Profissionais graduados que tenham feito complementa��o pedag�gica, conforme disposto pelo Conselho Nacional de Educa��o', 0, 'Profissionais graduados que tenham feito complemen', 1, 'f', 'f', 'f', NULL, NULL, 'Profissionais graduados que tenham feito');
        INSERT INTO db_sysarqcamp VALUES (1158, (SELECT MAX(codcam) FROM db_syscampo), (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq=1158));

        ALTER TABLE rhpessoalmov ADD COLUMN rh02_art1leiprestpsiccologia boolean default false;
        INSERT INTO db_syscampo VALUES ((SELECT MAX(codcam)+1 FROM db_syscampo),'rh02_art1leiprestpsiccologia','boolean','Presta��o de servi�os em psicologia', 0, 'Presta��o de servi�os em psicologia', 1, 'f', 'f', 'f', NULL, NULL, 'Presta��o de servi�os em psicologia');
        INSERT INTO db_sysarqcamp VALUES (1158, (SELECT MAX(codcam) FROM db_syscampo), (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq=1158));

        ALTER TABLE rhpessoalmov ADD COLUMN rh02_art1leiprestservsocial boolean default false;
        INSERT INTO db_syscampo VALUES ((SELECT MAX(codcam)+1 FROM db_syscampo),'rh02_art1leiprestservsocial','boolean','Presta��o de servi�os em servi�o social', 0, 'Presta��o de servi�os em servi�o social', 1, 'f', 'f', 'f', NULL, NULL, 'Presta��o de servi�os em servi�o social');
        INSERT INTO db_sysarqcamp VALUES (1158, (SELECT MAX(codcam) FROM db_syscampo), (SELECT max(seqarq)+1 FROM db_sysarqcamp WHERE codarq=1158));
        ";
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh02_tipcatprof');
        DELETE fROM db_syscampo WHERE nomecam = 'rh02_tipcatprof';

        ALTER TABLE rhpessoalmov DROP COLUMN rh02_art61ldb1;
        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh02_art61ldb1');
        DELETE fROM db_syscampo WHERE nomecam = 'rh02_art61ldb1';

        ALTER TABLE rhpessoalmov DROP COLUMN rh02_art61ldb2;
        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh02_art61ldb2');
        DELETE fROM db_syscampo WHERE nomecam = 'rh02_art61ldb2';

        ALTER TABLE rhpessoalmov DROP COLUMN rh02_art61ldb3;
        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh02_art61ldb3');
        DELETE fROM db_syscampo WHERE nomecam = 'rh02_art61ldb3';

        ALTER TABLE rhpessoalmov DROP COLUMN rh02_art61ldb4;
        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh02_art61ldb4');
        DELETE fROM db_syscampo WHERE nomecam = 'rh02_art61ldb4';

        ALTER TABLE rhpessoalmov DROP COLUMN rh02_art61ldb5;
        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh02_art61ldb5');
        DELETE fROM db_syscampo WHERE nomecam = 'rh02_art61ldb5';

        ALTER TABLE rhpessoalmov DROP COLUMN rh02_art1leiprestpsiccologia;
        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh02_art1leiprestpsiccologia');
        DELETE fROM db_syscampo WHERE nomecam = 'rh02_art1leiprestpsiccologia';

        ALTER TABLE rhpessoalmov DROP COLUMN rh02_art1leiprestservsocial;
        DELETE FROM db_sysarqcamp WHERE codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'rh02_art1leiprestservsocial');
        DELETE fROM db_syscampo WHERE nomecam = 'rh02_art1leiprestservsocial';
        ";

        $this->execute($sSql);
    }
}
