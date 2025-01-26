<?php

use Phinx\Console\Command\Rollback;
use ECidade\Suporte\Phinx\PostgresMigration;

class Oc15176 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            BEGIN;
                SELECT fc_startsession();
                -- Criar um menu
                INSERT INTO db_itensmenu VALUES ((select max(id_item) + 1 from db_itensmenu), 'Despesas do Exerc�cio Anterior', 'Despesas do Exerc�cio Anterior', '', 1, 1, 'Despesas do Exerc�cio Anterior', 't');
                INSERT INTO db_menu VALUES (29, (select max(id_item) from db_itensmenu), 263, 209);

                INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Inclus�o', 'Inclus�o', 'con1_despesaexercicioanterior001.php', '1', '1', 'Inclus�o', 't');
                INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Despesas do Exerc�cio Anterior'), (select max(id_item) from db_itensmenu), 1, 209);

                INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Altera��o', 'Altera��o', 'con1_despesaexercicioanterior002.php', '1', '1', 'Altera��o', 't');
                INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Despesas do Exerc�cio Anterior'), (select max(id_item) from db_itensmenu), 2, 209);

                INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Exclus�o', 'Exclus�o', 'con1_despesaexercicioanterior003.php', '1', '1', 'Exclus�o', 't');
                INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Despesas do Exerc�cio Anterior'), (select max(id_item) from db_itensmenu), 3, 209);

                INSERT INTO db_itensmenu VALUES ((select max(id_item) + 1 from db_itensmenu), 'Receitas do Exerc�cio Anterior', 'Receitas do Exerc�cio Anterior', '', 1, 1, 'Receitas do Exerc�cio Anterior', 't');
                INSERT INTO db_menu VALUES (29, (select max(id_item) from db_itensmenu), 264, 209);

                INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Inclus�o', 'Inclus�o', 'con1_receitaexercicioanterior001.php', '1', '1', 'Inclus�o', 't');
                INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Receitas do Exerc�cio Anterior'), (select max(id_item) from db_itensmenu), 1, 209);

                INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Altera��o', 'Altera��o', 'con1_receitaexercicioanterior002.php', '1', '1', 'Altera��o', 't');
                INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Receitas do Exerc�cio Anterior'), (select max(id_item) from db_itensmenu), 2, 209);

                INSERT INTO db_itensmenu VALUES ((select max(id_item) +1 from db_itensmenu), 'Exclus�o', 'Exclus�o', 'con1_receitaexercicioanterior003.php', '1', '1', 'Exclus�o', 't');
                INSERT INTO db_menu VALUES ((select max(id_item) from db_itensmenu WHERE descricao = 'Receitas do Exerc�cio Anterior'), (select max(id_item) from db_itensmenu), 3, 209);

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'c90_dataimplantacao', 'date', 'Data Implanta��o de Cliente', '', 'Data Implanta��o de Cliente', 1, true, true, true, 0, 'text', 'Data Implanta��o de Cliente');
                INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia ) VALUES ( 784, (SELECT max(codcam) FROM db_syscampo), 6, 0);

                -- INSERE db_sysarquivo
                INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((SELECT max(codarq) + 1 FROM db_sysarquivo), 'despesaexercicioanterior', 'Cadastra despesa do exercicio anterior', 'c233', '2021-07-26', 'Despesa Exercicio Anterior', 0, false, false, false, false);
                -- INSERE db_sysarqmod
                INSERT INTO db_sysarqmod (codmod, codarq) VALUES (32, (SELECT max(codarq) FROM db_sysarquivo));
                -- INSERE db_syscampo
                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'c233_sequencial', 'int4', 'Sequencial', '', 'Sequencial', 25, false, true, true, 0, 'text', 'Sequencial');

                INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo), (SELECT max(codcam) FROM db_syscampo), 1, 0);

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_orgao', 'int4', '�rg�o', '', '�rg�o', 25, false, true, false, 0, 'text', '�rgao');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,2 ,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_mes', 'int4', 'M�s Refer�ncia', '', 'M�s Refer�ncia', 2, false, true, false, 0, 'text', 'M�s Refer�ncia');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,3 ,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_ano', 'int4', 'Ano Refer�ncia', '', 'Ano Refer�ncia', 4, false, true, false, 0, 'text', 'Ano Refer�ncia');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,4,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_elemento', 'int8', 'Elemento', '', 'Elemento', 25, false, true, false, 0, 'text', 'Elemento');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,5,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_fonte', 'int8', 'Fonte', '', 'Fonte', 3, true, true, false, 0, 'text', 'Fonte');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,6,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_valorempenhado', 'float8', 'Valor Empenhado', '', 'Valor Empenhado', 25, false, true, false, 0, 'text', 'Valor Empenhado');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,6,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_tipodespesarpps', 'int4', 'Tipo Despesa RPPS', '', 'Tipo Despesa RPPS', 1, true, true, false, 0, 'text', 'Tipo Despesa RPPS');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,7,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_competencia', 'date', 'Compet�ncia (M�s/Ano)', '', 'Compet�ncia (M�s/Ano)', 1, true, true, false, 0, 'text', 'Compet�ncia (M�s/Ano)');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,8,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_valorliquidado', 'float8', 'Valor Liquidado', '', 'Valor Liquidado', 25, true, true, false, 0, 'text', 'Valor Liquidado');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,9,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c233_valorpago', 'float8', 'Valor Pago', '', 'Valor Pago', 25, true, true, false, 0, 'text', 'Valor Pago');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,10,0 );

                -- INSERE db_sysarquivo
                INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((SELECT max(codarq)+1 FROM db_sysarquivo), 'receitaexercicioanterior', 'Cadastra receita do exercicio anterior', 'c234', '2021-07-26', 'Receita Exercicio Anterior', 0, false, false, false, false);
                -- INSERE db_sysarqmod
                INSERT INTO db_sysarqmod (codmod, codarq) VALUES (32, (SELECT max(codarq) FROM db_sysarquivo));
                -- INSERE db_syscampo
                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c234_sequencial', 'int4', 'Sequencial', '', 'Sequencial', 25, false, true, true, 0, 'text', 'Sequencial');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,1 ,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c234_orgao', 'int4', '�rg�o', '', '�rg�o', 25, false, true, false, 0, 'text', '�rgao');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,2 ,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c234_mes', 'int4', 'M�s Refer�ncia', '', 'M�s Refer�ncia', 2, false, true, false, 0, 'text', 'M�s Refer�ncia');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,3 ,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c234_ano', 'int4', 'Ano Refer�ncia', '', 'Ano Refer�ncia', 4, false, true, false, 0, 'text', 'Ano Refer�ncia');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,4,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c234_receita', 'int8', 'Elemento', '', 'Elemento', 25, false, true, false, 0, 'text', 'Elemento');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,5,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c234_tipoemenda', 'int8', 'Tipo de Emenda', '', 'Tipo de Emenda', 1, false, true, false, 0, 'text', 'Tipo de Emenda');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,6,0 );

                INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((SELECT max(codcam)+1 FROM db_syscampo), 'c234_valorarrecadado', 'float8', 'Valor Arrecadado', '', 'Valor Arrecadado', 25, false, true, false, 0, 'text', 'Valor Arrecadado');

                INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) VALUES ( (SELECT max(codarq) FROM db_sysarquivo),(SELECT max(codcam) FROM db_syscampo) ,7,0 );

                ALTER TABLE contabilidade.conparametro ADD COLUMN c90_dataimplantacao date;

                CREATE TABLE contabilidade.despesaexercicioanterior (
                    c233_sequencial serial,
                    c233_orgao int4 NOT NULL,
                    c233_mes int4 NOT NULL,
                    c233_ano int4 NOT NULL,
                    c233_elemento int8 NOT NULL,
                    c233_fonte int8,
                    c233_valorempenhado float8 NOT NULL,
                    c233_tipodespesarpps int4,
                    c233_competencia date,
                    c233_valorliquidado float8,
                    c233_valorpago float8
                );

                CREATE TABLE contabilidade.receitaexercicioanterior (
                    c234_sequencial serial,
                    c234_orgao int4 NOT NULL,
                    c234_mes int4 NOT NULL,
                    c234_ano int4 NOT NULL,
                    c234_receita int8 NOT NULL,
                    c234_tipoemenda int8 NOT NULL,
                    c234_valorarrecadado float8 NOT NULL
                );
            COMMIT;
SQL;

        $this->execute($sql);
    }

    public function down() {}
}
