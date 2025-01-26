<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class oc20870 extends PostgresMigration
{

    public function up(){
        $this->criaItensMenu();
        $this->criaTabelaNaturezaBemServico();
        $this->insereControleDaNovaTabela();
        $this->insereRegistrosIniciais();
    }

    public function criaItensMenu(){
        $sql = "
            BEGIN;

            --Cria itens do menu
            INSERT INTO db_itensmenu
            VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Natureza de Bem ou Servi�o', 'Cadastro de Natureza de Bem ou Servi�o', '', 1, 1, 'Cadastro de Natureza de Bem ou Servi�o', 't');

            INSERT INTO db_itensmenu
            VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Inclus�o', 'Inclus�o de naturezabemservi�o', 'emp1_naturezabemservico001.php', 1, 1, 'Inclus�o de naturezabemservi�o', 't');

            INSERT INTO db_itensmenu
            VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Altera��o', 'Altera��o de naturezabemservi�o', 'emp1_naturezabemservico002.php', 1, 1, 'Altera��o de naturezabemservi�o', 't');

            INSERT INTO db_itensmenu
            VALUES ((SELECT max(id_item)+1 FROM db_itensmenu), 'Exclus�o', 'Exclus�o de naturezabemservi�o', 'emp1_naturezabemservico003.php', 1, 1, 'Exclus�o de naturezabemservi�o', 't');

            --Registra ninho do menu
            INSERT INTO db_menu VALUES (29, (SELECT id_item FROM db_itensmenu WHERE help = 'Cadastro de Natureza de Bem ou Servi�o'), ((SELECT max(menusequencia)+1 FROM db_menu WHERE id_item = 29) ), 398);

            INSERT INTO db_menu
            VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Natureza de Bem ou Servi�o'), (SELECT id_item FROM db_itensmenu WHERE help = 'Inclus�o de naturezabemservi�o'), 1, 398);

            INSERT INTO db_menu
            VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Natureza de Bem ou Servi�o'), (SELECT id_item FROM db_itensmenu WHERE help = 'Altera��o de naturezabemservi�o'), 2, 398);

            INSERT INTO db_menu
            VALUES ((SELECT id_item FROM db_itensmenu WHERE descricao = 'Natureza de Bem ou Servi�o'), (SELECT id_item FROM db_itensmenu WHERE help = 'Exclus�o de naturezabemservi�o'), 3, 398);

            COMMIT;
        ";
        $this->execute($sql);
    }

    public function criaTabelaNaturezaBemServico(){
        $sql="
            BEGIN;

            CREATE TABLE IF NOT EXISTS empenho.naturezabemservico
            (
                e101_sequencial SERIAL,
                e101_descr text not null,
                e101_resumo varchar(120) not null,
                e101_aliquota float8 not null default 0
            );

            COMMIT;
        ";
        $this->execute($sql);
    }

    public function insereControleDaNovaTabela(){
        $sql="
            BEGIN;

            --Cria registros da tabela naturezabemservico e de seus campos
            INSERT INTO db_sysarquivo
            VALUES ((SELECT max(codarq)+1 FROM db_sysarquivo), 'naturezabemservico', 'Tabela de Natureza de Bem ou Servi�o', 'e101', CURRENT_DATE, 'Natureza de Bem ou Servico', 0, 'f', 'f', 'f', 'f' );

            INSERT INTO db_sysarqmod
            VALUES (38,(SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico'));

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e101_sequencial' ,'int4' ,'Sequencial' ,'0' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );

            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'e101_sequencial'),1 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e101_descr' ,'varchar' ,'Descri��o da Natureza de Bem ou Servi�o' ,'' ,'Descri��o' ,9999 ,'false' ,'false' ,'false' ,0 ,'text' ,'Descri��o' );

            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'e101_descr'),2 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e101_resumo' ,'varchar' ,'Resumo da descri��o da Natureza de Bem ou Servi�o' ,'' ,'Resumo da descri��o' ,120,'false' ,'false' ,'false' ,0 ,'text' ,'Resumo da descri��o' );

            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'e101_resumo'),3 ,0 );

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e101_aliquota' ,'float8' ,'Aliquota' ,'' ,'Al�quota' ,15 ,'false' ,'false' ,'false' ,4 ,'text' ,'Al�quota' );

            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'e101_aliquota'),4 ,0 );

            INSERT INTO db_syssequencia VALUES((SELECT max(codsequencia) + 1 FROM db_syssequencia), 'naturezabemservico_e101_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = (SELECT codsequencia FROM db_syssequencia WHERE nomesequencia = 'naturezabemservico_e101_sequencial_seq') WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico') and codcam = (SELECT codcam FROM db_syscampo WHERE nomecam = 'e101_sequencial');

            COMMIT;
        ";
        $this->execute($sql);
    }

    public function insereRegistrosIniciais(){
        $sql="
            BEGIN;

            INSERT INTO empenho.naturezabemservico (e101_descr, e101_resumo,e101_aliquota)
            VALUES
            ('Alimenta��o','Alimenta��o','1.2'),
            ('Energia el�trica','Energia el�trica','1.2'),
            ('Servi�os prestados com emprego de materiais','Servi�os prestados com emprego de materiais','1.2'),
            ('Constru��o Civil por empreitada com emprego de materiais','Constru��o Civil por empreitada com emprego de materiais','1.2'),
            ('Servi�os hospitalares de que trata o art. 30','Servi�os hospitalares de que trata o art. 30','1.2'),
            ('Servi�os de aux�lio diagn�stico e terapia, patologia cl�nica, imagenologia, anatomia patol�gica e citopatol�gia, medicina nuclear e an�lises e patologias cl�nicas de que trata o art. 31.','Servi�os de aux�lio diagn�stico e terapia, patologia cl�nica, imagenologia, anatomia patol�gica e citopatol�gia, medi...','1.2'),
            ('Transporte de cargas, exceto os relacionados no c�digo 8767','Transporte de cargas, exceto os relacionados no c�digo 8767','1.2'),
            ('Produtos farmac�uticos, de perfumaria, de toucador ou de higiene pessoal adquiridos de produtor, importador, distribuidor ou varejista, exceto os relacionados no c�digo 8767 e','Produtos farmac�uticos, de perfumaria, de toucador ou de higiene pessoal adquiridos de produtor, importador, distribu...','1.2'),
            ('Mercadorias e bens em geral.','Mercadorias e bens em geral.','1.2'),
            ('Gasolina, inclusive de avia��o, �leo diesel, g�s liquefeito de petr�leo (GLP), combust�veis derivados de petr�leo ou de g�s natural, querosene de avia��o (QAV), e demais produtos derivados de petr�leo, adquiridos de refinarias de petr�leo, de demais produtores, de importadores, de distribuidor ou varejista, pelos �rg�os da administra��o p�blica de que trata o caput do art. 19','Gasolina, inclusive de avia��o, �leo diesel, g�s liquefeito de petr�leo (GLP), combust�veis derivados de petr�leo ou ...','0.24'),
            ('�lcool et�lico hidratado, inclusive para fins carburantes, adquirido diretamente de produtor, importador ou distribuidor de que trata o art. 20','�lcool et�lico hidratado, inclusive para fins carburantes, adquirido diretamente de produtor, importador ou distribui...','0.24'),
            ('Biodiesel adquirido de produtor ou importador, de que trata o art. 21.','Biodiesel adquirido de produtor ou importador, de que trata o art. 21.','0.24'),
            ('Gasolina, exceto gasolina de avia��o, �leo diesel, g�s liquefeito de petr�leo (GLP), derivados de petr�leo ou de g�s natural e querosene de avia��o adquiridos de dis- tribuidores e comerciantes varejistas','Gasolina, exceto gasolina de avia��o, �leo diesel, g�s liquefeito de petr�leo (GLP), derivados de petr�leo ou de g�s ...','0.24'),
            ('�lcool et�lico hidratado nacional, inclusive para fins carburantes adquirido de comerciante varejista','�lcool et�lico hidratado nacional, inclusive para fins carburantes adquirido de comerciante varejista','0.24'),
            ('Biodiesel adquirido de distribuidores e comerciantes varejistas','Biodiesel adquirido de distribuidores e comerciantes varejistas','0.24'),
            ('Biodiesel adquirido de produtor detentor regular do selo Combust�vel Social, fabricado a partir de mamona ou fruto, caro�o ou am�ndoa de palma produzidos nas regi�es norte e nordeste e no semi�rido, por agricultor familiar enquadrado no Programa Nacional de Fortalecimento da Agricultura Familiar (Pronaf).','Biodiesel adquirido de produtor detentor regular do selo Combust�vel Social, fabricado a partir de mamona ou fruto, c...','0.24'),
            ('Transporte internacional de cargas efetuado por empresas nacionais','Transporte internacional de cargas efetuado por empresas nacionais','1.2'),
            ('Estaleiros navais brasileiros nas atividades de constru��o, conserva��o, moderniza��o, convers�o e reparo de embarca��es pr�-registradas ou registradas no Registro Especial Brasileiro (REB), institu�do pela Lei n� 9.432, de 8 de janeiro de 1997','Estaleiros navais brasileiros nas atividades de constru��o, conserva��o, moderniza��o, convers�o e reparo de embarca�...','1.2'),
            ('Produtos farmac�uticos, de perfumaria, de toucador e de higiene pessoal a que se refere o � 1� do art. 22 , adquiridos de distribuidores e de comerciantes varejistas','Produtos farmac�uticos, de perfumaria, de toucador e de higiene pessoal a que se refere o � 1� do art. 22 , adquirido...','1.2'),
            ('Produtos a que se refere o � 2� do art. 22','Produtos a que se refere o � 2� do art. 22','1.2'),
            ('Produtos de que tratam as al�neas \'c\' a \'k\' do inciso I do art. 5�','Produtos de que tratam as al�neas \'c\' a \'k\' do inciso I do art. 5�','1.2'),
            ('Outros produtos ou servi�os beneficiados com isen��o, n�o incid�ncia ou al�quotas zero da Cofins e da Contribui��o para o PIS/Pasep, observado o disposto no � 5� do art. 2�.','Outros produtos ou servi�os beneficiados com isen��o, n�o incid�ncia ou al�quotas zero da Cofins e da Contribui��o pa...','1.2'),
            ('Passagens a�reas, rodovi�rias e demais servi�os de transporte de passageiros, inclusive, tarifa de embarque, exceto as relacionadas no c�digo 8850.','Passagens a�reas, rodovi�rias e demais servi�os de transporte de passageiros, inclusive, tarifa de embarque, exceto a...','2.40'),
            ('Transporte internacional de passageiros efetuado por empresas nacionais.','Transporte internacional de passageiros efetuado por empresas nacionais.','2.40'),
            ('Servi�os prestados por associa��es profissionais ou assemelhadas e cooperativas.','Servi�os prestados por associa��es profissionais ou assemelhadas e cooperativas.','0'),
            ('Servi�os prestados por bancos comerciais, bancos de investimento, bancos de desenvolvimento, caixas econ�micas, sociedades de cr�dito, financiamento e investimento, sociedades de cr�dito imobili�rio, e c�mbio, distribuidoras de t�tulos e valores mobili�rios, empresas de arrendamento mercantil, cooperativas de cr�dito, empresas de seguros privados e de capitaliza��o e entidades abertas de previd�ncia complementar','Servi�os prestados por bancos comerciais, bancos de investimento, bancos de desenvolvimento, caixas econ�micas, socie...','2.40'),
            ('Seguro sa�de.','Seguro sa�de.','2.40'),
            ('Servi�os de abastecimento de �gua','Servi�os de abastecimento de �gua','4.80'),
            ('Telefone','Telefone','4.80'),
            ('Correio e tel�grafos','Correio e tel�grafos','4.80'),
            ('Vigil�ncia','Vigil�ncia','4.80'),
            ('Limpeza','Limpeza','4.80'),
            ('Loca��o de m�o de obra','Loca��o de m�o de obra','4.80'),
            ('Intermedia��o de neg�cios','Intermedia��o de neg�cios','4.80'),
            ('Administra��o, loca��o ou cess�o de bens im�veis, m�veis e direitos de qualquer natureza','Administra��o, loca��o ou cess�o de bens im�veis, m�veis e direitos de qualquer natureza','4.80'),
            ('Factoring','Factoring','4.80'),
            ('Plano de sa�de humano, veterin�rio ou odontol�gico com valores fixos por servidor, por empregado ou por animal','Plano de sa�de humano, veterin�rio ou odontol�gico com valores fixos por servidor, por empregado ou por animal','4.80'),
            ('Demais servi�os','Demais servi�os','4.80');

            COMMIT;
        ";
        $this->execute($sql);
    }

    public function down(){
        $sql="
            BEGIN;

            DELETE FROM db_syssequencia WHERE nomesequencia = 'naturezabemservico_e101_sequencial_seq';

            DELETE FROM db_sysarqcamp WHERE codarq = (select codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico');

            DELETE FROM db_syscampo WHERE nomecam like 'e101_%';

            DELETE FROM db_sysarqmod WHERE codarq = (select codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico');

            DELETE FROM db_acount WHERE codarq = (select codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico');

            DELETE FROM db_sysarquivo WHERE nomearq = 'naturezabemservico';

            DROP TABLE IF EXISTS empenho.naturezabemservico;

            DELETE FROM db_menu WHERE id_item = (select id_item FROM db_itensmenu WHERE descricao = 'Natureza de Bem ou Servi�o');

            DELETE FROM db_itensmenu WHERE desctec = 'Cadastro de Natureza de Bem ou Servi�o';
            DELETE FROM db_itensmenu WHERE desctec = 'Inclus�o de naturezabemservi�o';
            DELETE FROM db_itensmenu WHERE desctec = 'Altera��o de naturezabemservi�o';
            DELETE FROM db_itensmenu WHERE desctec = 'Exclus�o de naturezabemservi�o';

            COMMIT;
        ";

        $this->execute($sql);
    }
}
