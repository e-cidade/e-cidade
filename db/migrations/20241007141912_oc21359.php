<?php

use Phinx\Migration\AbstractMigration;

class Oc21359 extends AbstractMigration
{
    public function up()
    {
        $this->criarItensMenu();
        $this->criaTabelaPlanoSaude();
        $this->criarDadosCamposTabelaPlanoSaude();
        $this->criarCampoTabelaCfpess();
    }

    public function criarItensMenu()
    {
        $sql = " 
             BEGIN;

             SELECT SETVAL('db_itensmenu_id_item_seq', (SELECT max(id_item) FROM db_itensmenu));

             --Criando itens do menu
             INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Plano de Saúde', 'Cadastro de Plano de Saúde', '', 1, 1, 'Cadastro de Plano de Saúde', 't');
             INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Inclusão', 'Inclusão de Plano de Saúde', 'pes1_planosaude001.php', 1, 1, 'Inclusão de Plano de Saúde', 't');
             INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Alteração', 'Alteração de Plano de Saúde', 'pes1_planosaude002.php', 1, 1, 'Alteração de Plano de Saúde', 't');
             INSERT INTO db_itensmenu VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Exclusão', 'Exclusão de Plano de Saúde', 'pes1_planosaude003.php', 1, 1, 'Exclusão de Plano de Saúde', 't');

            --Adicionando menus na rotina
            INSERT INTO db_menu VALUES (29, (SELECT id_item FROM db_itensmenu WHERE help = 'Cadastro de Plano de Saúde'), ((SELECT max(menusequencia)+1 FROM db_menu WHERE id_item = 29) ), 952);
            INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Plano de Saúde'), (SELECT id_item FROM db_itensmenu WHERE help = 'Inclusão de Plano de Saúde'), 1, 952);
            INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Plano de Saúde'), (SELECT id_item FROM db_itensmenu WHERE help = 'Alteração de Plano de Saúde'), 2, 952);
            INSERT INTO db_menu VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Plano de Saúde'), (SELECT id_item FROM db_itensmenu WHERE help = 'Exclusão de Plano de Saúde'), 3, 952);

            COMMIT;
        ";
        $this->execute($sql);
    }

    public function criaTabelaPlanoSaude()
    {
        $sql = "
             BEGIN;

            CREATE TABLE IF NOT EXISTS pessoal.planosaude
            (
                r75_sequencial SERIAL,
                r75_anousu integer not null default 0,
                r75_mesusu integer not null default 0,
                r75_regist integer not null default 0,
                r75_cnpj varchar(14) not null,
                r75_ans varchar(6) not null,
                r75_dependente bool not null,
                r75_numcgm integer default 0,
                r75_nomedependente character varying(100),
                r75_valor double precision not null default 0,
                r75_instit integer not null default 0,
                CONSTRAINT planosaude_seq_pk primary key(r75_sequencial)
            );

            create sequence pessoal.planosaude_sequencial_seq
                increment 1
                minvalue 1
                maxvalue 9223372036854775807
                start 1
                cache 1;

            COMMIT;
        ";
        $this->execute($sql);
    }

    public function criarDadosCamposTabelaPlanoSaude()
    {
        $sql = "
            BEGIN;

            --Criando dados tabela planosaude
            INSERT INTO db_sysarquivo 
            VALUES ((SELECT max(codarq)+1 FROM db_sysarquivo), 'planosaude', 'Cadastro do Plano de Saúde do Servidor', 'r75', '2024-01-02', 'Plano de Saúde', 0, 'f', 'f', 'f', 'f');

            INSERT INTO db_sysarqmod 
            VALUES (28,(SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'));

            --Criando dados dos campos da tabela
            INSERT INTO db_syscampo (codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_sequencial' , 'int4', 'Campo sequencial da tabela planosaude', '0', 'Sequencial', 20, 'f', 'f', 't', 1, 'text', 'Sequencial');

            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_sequencial'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_anousu', 'int4', 'Ano do exercicio', '0', 'Ano', 4, 'f', 'f', 'f', 0, 'text', 'Ano');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_anousu'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_mesusu', 'int4', 'Mes do exercicio', '0', 'Mês', 2, 'f', 'f', 'f', 0, 'text', 'Mês');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_mesusu'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_regist', 'int4', 'Código do Servidor', '0', 'Código do Servidor', 9, 'f', 'f', 'f', 0, 'text', 'Servidor');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_regist'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_cnpj', 'varchar(14)', 'CNPJ da Operadora', '0', 'CNPJ da Operadora', 14, 'f', 'f', 'f', 4, 'text', 'CNPJ da Operadora');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_cnpj'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_ans', 'varchar(6)', 'Registro ANS', '0', 'Registro ANS', 6, 'f', 'f', 'f', 4, 'text', 'CNPJ da Operadora');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_ans'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_dependente', 'bool', 'Possui Dependente', '0', 'Possui Dependente', 4, 't', 'f', 'f', 0, 'text', 'Possui Dependente');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_dependente'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_numcgm', 'int4', 'CGM Dependente', '0', 'CGM Dependente', 10, 't', 'f', 'f', 0, 'text', 'CGM Dependente');

            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_numcgm'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_nomedependente', 'varchar(100)', 'Nome dependente', '0', 'Nome dependente', 100, 't', 'f', 'f', 3, 'text', 'Nome dependente');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_nomedependente'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_valor', 'float8', 'Instituição', '0', 'Valor do plano de saúde', 15, 't', 'f', 'f', 4, 'text', 'Valor Plano de Saude');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_valor'), 1, 0);

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo), 'r75_instit', 'int4', 'Cod. Instituicao', '0', 'Cod. Instituicao', 15, 't', 'f', 'f', 4, 'text', 'Cod. Instituicao');
            
            INSERT INTO db_sysarqcamp (codarq ,codcam ,seqarq ,codsequencia) 
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_instit'), 1, 0);

            --inserindo sequencial do campo
            INSERT INTO db_syssequencia VALUES((SELECT max(codsequencia) + 1 FROM db_syssequencia), 'planosaude_r75_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = (SELECT codsequencia FROM db_syssequencia WHERE nomesequencia = 'planosaude_r75_sequencial_seq') WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'planosaude') and codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'r75_sequencial');

            COMMIT;
        ";

        $this->execute($sql);
    }

    public function criarCampoTabelaCfpess(){
        
        $sql = "BEGIN;
        
                ALTER TABLE cfpess ADD COLUMN r11_planosaude character varying(4);

                INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from configuracoes.db_syscampo), 'r11_planosaude', 'varchar(4)', 'Plano de Saude', '', 'Plano de Saude', 4, true, true, false, 0, 'text', 'Plano de Saude');

                INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from configuracoes.db_sysarquivo where nomearq = 'cfpess'), (select codcam from configuracoes.db_syscampo where nomecam = 'r11_planosaude'), 96, 0);
                
                COMMIT;
        ";
        
        
        $this->execute($sql);
    }

    public function down(){
        $sql="
            BEGIN;

            DELETE FROM db_syssequencia WHERE nomesequencia = 'planosaude_r75_sequencial_seq';

            DELETE FROM db_sysarqcamp WHERE codarq = (select codarq FROM db_sysarquivo WHERE nomearq = 'planosaude');

            DELETE FROM db_syscampo WHERE nomecam like 'r75_%';

            DELETE FROM db_sysarqmod WHERE codarq = (select codarq FROM db_sysarquivo WHERE nomearq = 'planosaude');

            DELETE FROM db_acount WHERE codarq = (select codarq FROM db_sysarquivo WHERE nomearq = 'planosaude');

            DELETE FROM db_sysarquivo WHERE nomearq = 'planosaude';

            DROP TABLE IF EXISTS pessoal.planosaude;

            DELETE FROM db_menu WHERE id_item = (select id_item FROM db_itensmenu WHERE descricao = 'Plano de Saúde');

            DELETE FROM db_itensmenu WHERE desctec = 'Cadastro de Plano de Saúde';
            DELETE FROM db_itensmenu WHERE desctec = 'Inclusão de Plano de Saúde';
            DELETE FROM db_itensmenu WHERE desctec = 'Alteração de Plano de Saúde';
            DELETE FROM db_itensmenu WHERE desctec = 'Exclusão de Plano de Saúde';

            ALTER TABLE cfpess DROP COLUMN r11_planosaude;

            COMMIT;
        ";

        $this->execute($sql);
    }
}
