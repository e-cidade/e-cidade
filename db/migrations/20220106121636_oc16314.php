<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16314 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();


        Create table IF NOT EXISTS db_operacaodecredito(
            op01_sequencial Serial,
            op01_numerocontratoopc varchar(30) not null,
            op01_dataassinaturacop date not null
        );

        ALTER TABLE contabancaria ADD COLUMN db83_codigoopcredito int;

        ALTER TABLE orcsuplemval ADD COLUMN o47_numerocontratooc varchar(30);

        ALTER TABLE orcsuplemval ADD COLUMN o47_dataassinaturacop date;

        ALTER TABLE orcsuplemval ADD COLUMN o47_codigoopcredito int;

        INSERT INTO db_syscampo
            ( codcam,nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
            VALUES
            ((select max(codcam)+1 from db_syscampo),'o47_numerocontratooc', 'int', 'Opera��o de Cr�dito', '0', 'Opera��o de Cr�dito', 11, false, false, false, 1, 'text', 'Opera��o de Cr�dito');

        INSERT INTO db_itensmenu
            VALUES
            ((select max(id_item)+1 from db_itensmenu), 'Opera��es de Cr�dito', 'Opera��es de Cr�dito', ' ', 1, 1, 'Opera��es de Cr�dito', 't');

        INSERT INTO db_menu
            VALUES
            (29,(select max(id_item) from db_itensmenu),265,209);

        INSERT INTO db_itensmenu
            VALUES
            ((select max(id_item)+1 from db_itensmenu), 'Inclus�o', 'Inclus�o', 'con1_db_operacaodecredito001.php', 1, 1, 'Inclus�o', 't');

        INSERT INTO db_menu
            VALUES
            ((select id_item from db_itensmenu where descricao like 'Opera��es de Cr�dito') ,(select max(id_item) from db_itensmenu),1,209);

        INSERT INTO db_itensmenu
            VALUES
            ((select max(id_item)+1 from db_itensmenu), 'Altera��o', 'Altera��o', 'con1_db_operacaodecredito002.php', 1, 1, 'Altera��o', 't');

        INSERT INTO db_menu
            VALUES
            ((select id_item from db_itensmenu where descricao like 'Opera��es de Cr�dito') ,(select max(id_item) from db_itensmenu),2,209);

        INSERT INTO db_itensmenu
            VALUES
            ((select max(id_item)+1 from db_itensmenu), 'Exclus�o', 'Exclus�o', 'con1_db_operacaodecredito003.php', 1, 1, 'Exclus�o', 't');

        INSERT INTO db_menu
            VALUES
            ((select id_item from db_itensmenu where descricao like 'Opera��es de Cr�dito') ,(select max(id_item) from db_itensmenu),3,209);

        INSERT INTO db_syscampo
                ( codcam,nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                VALUES
                ((select max(codcam)+1 from db_syscampo),'op01_sequencial', 'int', 'Sequencial', '0', 'Sequencial', 11, false, false, false, 1, 'text', 'Sequencial');

        INSERT INTO db_syscampo
                (codcam,nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                VALUES
                ((select max(codcam)+1 from db_syscampo),'op01_numerocontratoopc', 'varchar(30)', 'N� do Contrato da Opera��o de Cr�dito', '0', 'N� do Contrato da Opera��o de Cr�dito', 30, false, false, false, 1, 'text', 'N� do Contrato da Opera��o de Cr�dito');

        INSERT INTO db_syscampo
                ( codcam,nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                VALUES
                ((select max(codcam)+1 from db_syscampo),'op01_dataassinaturacop', 'data', 'Data de Assinatura do Contrato OP', '0', 'Data de Assinatura do Contrato OP', 10, false, false, false, 1, 'text', 'Data de Assinatura do Contrato OP');

        INSERT INTO db_sysarqcamp
                (codarq, codcam, seqarq, codsequencia)
                VALUES
                ((select max(codarq) from db_sysarquivo),(select codcam from db_syscampo where nomecam = 'op01_numerocontratoopc'),'1','0');

        INSERT INTO db_sysarqcamp
                (codarq, codcam, seqarq, codsequencia)
                VALUES
                ((select max(codarq) from db_sysarquivo),(select codcam from db_syscampo where nomecam = 'op01_dataassinaturacop'),'2','0');

        INSERT INTO db_sysarqcamp
                (codarq, codcam, seqarq, codsequencia)
                VALUES
                ((select max(codarq) from db_sysarquivo),(select codcam from db_syscampo where nomecam = 'op01_sequencial'),'3','0');



        UPDATE db_syscampo
        SET descricao = 'N� do Contrato da Opera��o de Cr�dito', rotulo = 'N� do Contrato da Opera��o de Cr�dito',rotulorel = 'N� do Contrato da Opera��o de Cr�dito'
        WHERE nomecam = 'op01_numerocontratoopc';

        UPDATE db_syscampo
        SET descricao = 'Data de Assinatura do Contrato OP', rotulo = 'Data de Assinatura do Contrato OP',rotulorel = 'Data de Assinatura do Contrato OP'
        WHERE nomecam = 'op01_dataassinaturacop';

        UPDATE db_syscampo
        SET descricao = 'Sequencial', rotulo = 'Sequencial',rotulorel = 'Sequencial'
        WHERE nomecam = 'op01_sequencial';

        COMMIT;

SQL;
        $this->execute($sql);
    }
}
