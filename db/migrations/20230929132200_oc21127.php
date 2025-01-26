<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class oc21127 extends PostgresMigration
{

    public function up(){
        $this->atualizaTabelaDeNatureza();
        $this->insereRegistros();
        $this->criaTabelaPagordemreinf();
        $this->insereNovosCamposPagordem();
    }

    public function atualizaTabelaDeNatureza(){
        $sql = "
            BEGIN;

            DROP TABLE IF EXISTS empenho.naturezabemservico;
            CREATE TABLE IF NOT EXISTS empenho.naturezabemservico
            (
                e101_sequencial SERIAL,
                e101_codnaturezarendimento int4,
                e101_descr text NOT NULL,
                e101_resumo varchar(120) NOT NULL,
                e101_aliquota float8 NOT NULL DEFAULT 0
            );

            UPDATE db_itensmenu SET
            descricao = 'Natureza do Rendimento',
            help = 'Cadastro de Natureza do Rendimento',
            desctec = 'Cadastro de Natureza do Rendimento'
            WHERE descricao = 'Natureza de Bem ou Servi�o';

            INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
            VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e101_codnaturezarendimento' ,'int4' ,'Cod. Natureza do Rendimento' ,'' ,'Cod. Natureza do Rendimento' ,15 ,'false' ,'false' ,'false' ,4 ,'text' ,'Cod. Natureza do Rendimento' );

            INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
            VALUES (
            (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico'),
            (SELECT codcam FROM db_syscampo WHERE nomecam = 'e101_codnaturezarendimento'),
            (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico')),0 );

            COMMIT;
        ";
        $this->execute($sql);
    }

    public function insereRegistros(){
        $sql="
            BEGIN;

            INSERT INTO empenho.naturezabemservico (e101_descr, e101_resumo,e101_aliquota,e101_codnaturezarendimento) VALUES
            ('Alimenta��o','Alimenta��o','1.2', 17001),
            ('Energia el�trica','Energia el�trica','1.2', 17002),
            ('Servi�os prestados com emprego de materiais','Servi�os prestados com emprego de materiais','1.2', 17003),
            ('Constru��o Civil por empreitada com emprego de materiais','Constru��o Civil por empreitada com emprego de materiais','1.2', 17004),
            ('Servi�os hospitalares de que trata o art. 30','Servi�os hospitalares de que trata o art. 30','1.2', 17005),
            ('Servi�os de aux�lio diagn�stico e terapia, patologia cl�nica, imagenologia, anatomia patol�gica e citopatol�gia, medicina nuclear e an�lises e patologias cl�nicas de que trata o art. 31.','Servi�os de aux�lio diagn�stico e terapia, patologia cl�nica, imagenologia, anatomia patol�gica e citopatol�gia, medi...','1.2', 17006),
            ('Transporte de cargas, exceto os relacionados no c�digo 8767','Transporte de cargas, exceto os relacionados no c�digo 8767','1.2', 17007),
            ('Produtos farmac�uticos, de perfumaria, de toucador ou de higiene pessoal adquiridos de produtor, importador, distribuidor ou varejista, exceto os relacionados no c�digo 8767 e','Produtos farmac�uticos, de perfumaria, de toucador ou de higiene pessoal adquiridos de produtor, importador, distribu...','1.2', 17008),
            ('Mercadorias e bens em geral.','Mercadorias e bens em geral.','1.2', 17009),
            ('Gasolina, inclusive de avia��o, �leo diesel, g�s liquefeito de petr�leo (GLP), combust�veis derivados de petr�leo ou de g�s natural, querosene de avia��o (QAV), e demais produtos derivados de petr�leo, adquiridos de refinarias de petr�leo, de demais produtores, de importadores, de distribuidor ou varejista, pelos �rg�os da administra��o p�blica de que trata o caput do art. 19','Gasolina, inclusive de avia��o, �leo diesel, g�s liquefeito de petr�leo (GLP), combust�veis derivados de petr�leo ou ...','0.24', 17010),
            ('�lcool et�lico hidratado, inclusive para fins carburantes, adquirido diretamente de produtor, importador ou distribuidor de que trata o art. 20','�lcool et�lico hidratado, inclusive para fins carburantes, adquirido diretamente de produtor, importador ou distribui...','0.24', 17011),
            ('Biodiesel adquirido de produtor ou importador, de que trata o art. 21.','Biodiesel adquirido de produtor ou importador, de que trata o art. 21.','0.24', 17012),
            ('Gasolina, exceto gasolina de avia��o, �leo diesel, g�s liquefeito de petr�leo (GLP), derivados de petr�leo ou de g�s natural e querosene de avia��o adquiridos de dis- tribuidores e comerciantes varejistas','Gasolina, exceto gasolina de avia��o, �leo diesel, g�s liquefeito de petr�leo (GLP), derivados de petr�leo ou de g�s ...','0.24', 17013),
            ('�lcool et�lico hidratado nacional, inclusive para fins carburantes adquirido de comerciante varejista','�lcool et�lico hidratado nacional, inclusive para fins carburantes adquirido de comerciante varejista','0.24', 17014),
            ('Biodiesel adquirido de distribuidores e comerciantes varejistas','Biodiesel adquirido de distribuidores e comerciantes varejistas','0.24', 17015),
            ('Biodiesel adquirido de produtor detentor regular do selo Combust�vel Social, fabricado a partir de mamona ou fruto, caro�o ou am�ndoa de palma produzidos nas regi�es norte e nordeste e no semi�rido, por agricultor familiar enquadrado no Programa Nacional de Fortalecimento da Agricultura Familiar (Pronaf).','Biodiesel adquirido de produtor detentor regular do selo Combust�vel Social, fabricado a partir de mamona ou fruto, c...','0.24', 17016),
            ('Transporte internacional de cargas efetuado por empresas nacionais','Transporte internacional de cargas efetuado por empresas nacionais','1.2', 17017),
            ('Estaleiros navais brasileiros nas atividades de constru��o, conserva��o, moderniza��o, convers�o e reparo de embarca��es pr�-registradas ou registradas no Registro Especial Brasileiro (REB), institu�do pela Lei n� 9.432, de 8 de janeiro de 1997','Estaleiros navais brasileiros nas atividades de constru��o, conserva��o, moderniza��o, convers�o e reparo de embarca�...','1.2', 17018),
            ('Produtos farmac�uticos, de perfumaria, de toucador e de higiene pessoal a que se refere o � 1� do art. 22 , adquiridos de distribuidores e de comerciantes varejistas','Produtos farmac�uticos, de perfumaria, de toucador e de higiene pessoal a que se refere o � 1� do art. 22 , adquirido...','1.2', 17019),
            ('Produtos a que se refere o � 2� do art. 22','Produtos a que se refere o � 2� do art. 22','1.2', 17020),
            ('Produtos de que tratam as al�neas \'c\' a \'k\' do inciso I do art. 5�','Produtos de que tratam as al�neas \'c\' a \'k\' do inciso I do art. 5�','1.2', 17021),
            ('Outros produtos ou servi�os beneficiados com isen��o, n�o incid�ncia ou al�quotas zero da Cofins e da Contribui��o para o PIS/Pasep, observado o disposto no � 5� do art. 2�.','Outros produtos ou servi�os beneficiados com isen��o, n�o incid�ncia ou al�quotas zero da Cofins e da Contribui��o pa...','1.2', 17022),
            ('Passagens a�reas, rodovi�rias e demais servi�os de transporte de passageiros, inclusive, tarifa de embarque, exceto as relacionadas no c�digo 8850.','Passagens a�reas, rodovi�rias e demais servi�os de transporte de passageiros, inclusive, tarifa de embarque, exceto a...','2.40', 17023),
            ('Transporte internacional de passageiros efetuado por empresas nacionais.','Transporte internacional de passageiros efetuado por empresas nacionais.','2.40', 17024),
            ('Servi�os prestados por associa��es profissionais ou assemelhadas e cooperativas.','Servi�os prestados por associa��es profissionais ou assemelhadas e cooperativas.','0', 17025),
            ('Servi�os prestados por bancos comerciais, bancos de investimento, bancos de desenvolvimento, caixas econ�micas, sociedades de cr�dito, financiamento e investimento, sociedades de cr�dito imobili�rio, e c�mbio, distribuidoras de t�tulos e valores mobili�rios, empresas de arrendamento mercantil, cooperativas de cr�dito, empresas de seguros privados e de capitaliza��o e entidades abertas de previd�ncia complementar','Servi�os prestados por bancos comerciais, bancos de investimento, bancos de desenvolvimento, caixas econ�micas, socie...','2.40', 17026),
            ('Seguro sa�de.','Seguro sa�de.','2.40', 17027),
            ('Servi�os de abastecimento de �gua','Servi�os de abastecimento de �gua','4.80', 17028),
            ('Telefone','Telefone','4.80', 17029),
            ('Correio e tel�grafos','Correio e tel�grafos','4.80', 17030),
            ('Vigil�ncia','Vigil�ncia','4.80', 17031),
            ('Limpeza','Limpeza','4.80', 17032),
            ('Loca��o de m�o de obra','Loca��o de m�o de obra','4.80', 17033),
            ('Intermedia��o de neg�cios','Intermedia��o de neg�cios','4.80', 17034),
            ('Administra��o, loca��o ou cess�o de bens im�veis, m�veis e direitos de qualquer natureza','Administra��o, loca��o ou cess�o de bens im�veis, m�veis e direitos de qualquer natureza','4.80', 17035),
            ('Factoring','Factoring','4.80', 17036),
            ('Plano de sa�de humano, veterin�rio ou odontol�gico com valores fixos por servidor, por empregado ou por animal','Plano de sa�de humano, veterin�rio ou odontol�gico com valores fixos por servidor, por empregado ou por animal','4.80', 17037),
            ('Demais servi�os','Demais servi�os','4.80', 17099),
            ('Pagamento efetuado a sociedade cooperativa pelo fornecimento de bens, conforme art. 24, da IN 1234/12','Pagamento efetuado a sociedade cooperativa pelo fornecimento de bens, conforme art. 24, da IN 1234/12',0,17038),
            ('Pagamento a Cooperativa de produ��o, em rela��o aos atos decorrentes da comercializa��o ou da industrializa��o de produtos de seus associados, excetuado o previsto no �� 1� e 2� do art. 25 da IN 1.234/12','Pagamento a Cooperativa de produ��o, em rela��o aos atos decorrentes da comercializa��o ou da industrializa��o de pro...', 0, 17039),
            ('Servi�os prestados por associa��es profissionais ou assemelhadas e cooperativas que envolver parcela de servi�os fornecidos por terceiros n�o cooperados ou n�o associados, contratados ou conveniados, para cumprimento de contratos - Servi�os prestados com emprego de materiais, inclusive o de que trata a al�nea \"C\" do Inciso II do art. 27 da IN 1.1234.','Servi�os prestados por associa��es profissionais ou assemelhadas e cooperativas que envolver parcela de servi�os forn...', 0, 17040),
            ('Servi�os prestados por associa��es profissionais ou assemelhadas e cooperativas que envolver parcela de servi�os fornecidos por terceiros n�o cooperados ou n�o associados, contratados ou conveniados, para cumprimento de contratos - Demais servi�os','Servi�os prestados por associa��es profissionais ou assemelhadas e cooperativas que envolver parcela de servi�os forn...', 0, 17041),
            ('Pagamentos efetuados �s associa��es e �s cooperativas de m�dicos e de odont�logos, relativamente �s import�ncias recebidas a t�tulo de comiss�o, taxa de administra��o ou de ades�o ao plano','Pagamentos efetuados �s associa��es e �s cooperativas de m�dicos e de odont�logos, relativamente �s import�ncias rece...', 0, 17042),
            ('Pagamento efetuado a sociedade cooperativa de produ��o, em rela��o aos atos decorrentes da comercializa��o ou de industrializa��o, pelas cooperativas agropecu�rias e de pesca, de produtos adquiridos de n�o associados, agricultores, pecuaristas ou pescadores, para completar lotes destinados aoa cumprimento de contratos ou para suprir capacidade ociosa de suas instala��es industriais, conforme � 1�do art. 25, da IN 1234/12.','Pagamento efetuado a sociedade cooperativa de produ��o, em rela��o aos atos decorrentes da comercializa��o ou de indu...', 0, 17043),
            ('Pagamento referente a aluguel de im�vel quando efetuado � entidade aberta de previd�ncia complementar sem fins lucrativos, de que trata o art 34, � 2� da IN 1.234/2012.','Pagamento referente a aluguel de im�vel quando efetuado � entidade aberta de previd�ncia complementar sem fins lucrat...', 0, 17044),
            ('Servi�os prestados por cooperativas de radiotaxi, bem como �quelas cujos cooperados se dediquem a servi�os relacionados a atividades culturais e demais cooperativas de servi�os, conforme art. 5�-A, da IN RFB 1.234/2012.','Servi�os prestados por cooperativas de radiotaxi, bem como �quelas cujos cooperados se dediquem a servi�os relacionad...', 0, 17045),
            ('Pagamento efetuado na aquisi��o de bem im�vel, quando o vendedor for pessoa jur�dica que exerce a atividade de compra e venda de im�veis, ou quando se tratar de im�veis adquiridos de entidades abertas de previd�ncia complementar com fins lucrativos, conforme art. 23, inc I, da IN RFB 1234/2012','Pagamento efetuado na aquisi��o de bem im�vel, quando o vendedor for pessoa jur�dica que exerce a atividade de compra...', 0, 17046),
            ('Pagamento efetuado na aquisi��o de bem im�vel adquirido pertencente ao ativo n�o circulante da empresa vendedora, conforme art. 23, inc II da IN RFB 1234/2012.','Pagamento efetuado na aquisi��o de bem im�vel adquirido pertencente ao ativo n�o circulante da empresa vendedora, con...', 0, 17047),
            ('Pagamento efetuado na aquisi��o de bem im�vel adquirido de entidade aberta de previd�ncia complementar sem fins lucrativos, conforme art. 23, inc III, da IN RFB 1234/2012.','Pagamento efetuado na aquisi��o de bem im�vel adquirido de entidade aberta de previd�ncia complementar sem fins lucra...', 0, 17048),
            ('Propaganda e Publicidade, em desconformidade ao art 16 da IN RFB 1234/2012, referente ao � 4� do citado artigo.','Propaganda e Publicidade, em desconformidade ao art 16 da IN RFB 1234/2012, referente ao � 4� do citado artigo.', 0, 17049),
            ('Propaganda e Publicidade, em conformidade ao art 16 da IN RFB 1234/2012, referente ao � 4� do citado artigo.','Propaganda e Publicidade, em conformidade ao art 16 da IN RFB 1234/2012, referente ao � 4� do citado artigo.', 0, 17050),
            ('Rendimento decorrente do trabalho com v�nculo empregat�cio','Rendimento decorrente do trabalho com v�nculo empregat�cio',0,10001),
            ('Rendimento decorrente do trabalho sem v�nculo empregat�cio','Rendimento decorrente do trabalho sem v�nculo empregat�cio',0,10002),
            ('Rendimento decorrente do trabalho pago a trabalhador avulso','Rendimento decorrente do trabalho pago a trabalhador avulso',0,10003),
            ('Participa��o nos lucros ou resultados (PLR)','Participa��o nos lucros ou resultados (PLR)',0,10004),
            ('Benef�cio de Regime Pr�prio de Previd�ncia Social','Benef�cio de Regime Pr�prio de Previd�ncia Social',0,10005),
            ('Benef�cio do Regime Geral de Previd�ncia Social','Benef�cio do Regime Geral de Previd�ncia Social',0,10006),
            ('Rendimentos relativos a presta��o de servi�os de Transporte Rodovi�rio Internacional de Carga, Auferidos por Transportador Aut�nomo Pessoa F�sica, Residente na Rep�blica do Paraguai, considerado como Sociedade Unipessoal nesse Pa�s','Rendimentos relativos a presta��o de servi�os de Transporte Rodovi�rio Internacional de Carga, Auferidos por Transpor...',0,10007),
            ('Honor�rios advocat�cios de sucumb�ncia recebidos pelos advogados e procuradores p�blicos de que trata o art. 27 da Lei n� 13.327','Honor�rios advocat�cios de sucumb�ncia recebidos pelos advogados e procuradores p�blicos de que trata o art. 27 da Le...',0,10008),
            ('Aux�lio moradia','Aux�lio moradia',0,10009),
            ('Bolsa ao m�dico residente','Bolsa ao m�dico residente',0,10010),
            ('Decorrente de Decis�o da Justi�a do Trabalho','Decorrente de Decis�o da Justi�a do Trabalho',0,11001),
            ('Decorrente de Decis�o da Justi�a Federal','Decorrente de Decis�o da Justi�a Federal',0,11002),
            ('Decorrente de Decis�o da Justi�a dos Estados/Distrito Federal','Decorrente de Decis�o da Justi�a dos Estados/Distrito Federal',0,11003),
            ('Responsabilidade Civil - juros e indeniza��es por lucros cessantes, inclusive astreinte','Responsabilidade Civil - juros e indeniza��es por lucros cessantes, inclusive astreinte',0,11004),
            ('Decis�o Judicial - Import�ncias pagas a t�tulo de indeniza��es por danos morais, decorrentes de senten�a judicial.','Decis�o Judicial - Import�ncias pagas a t�tulo de indeniza��es por danos morais, decorrentes de senten�a judicial.',0,11005),
            ('Lucro e Dividendo','Lucro e Dividendo',0,12001),
            ('Resgate de Previd�ncia Complementar - Modalidade Contribui��o Definida/Vari�vel - N�o Optante pela Tributa��o Exclusiva','Resgate de Previd�ncia Complementar - Modalidade Contribui��o Definida/Vari�vel - N�o Optante pela Tributa��o Exclusi...',0,12002),
            ('Resgate de Fundo de Aposentadoria Programada Individual (Fapi)- N�o Optante pela Tributa��o Exclusiva','Resgate de Fundo de Aposentadoria Programada Individual (Fapi)- N�o Optante pela Tributa��o Exclusiva',0,12003),
            ('Resgate de Previd�ncia Complementar - Modalidade Benef�cio Definido - N�o Optante pela Tributa��o Exclusiva','Resgate de Previd�ncia Complementar - Modalidade Benef�cio Definido - N�o Optante pela Tributa��o Exclusiva',0,12004),
            ('Resgate de Previd�ncia Complementar - Modalidade Contribui��o Definida/Vari�vel - Optante pela Tributa��o Exclusiva','Resgate de Previd�ncia Complementar - Modalidade Contribui��o Definida/Vari�vel - Optante pela Tributa��o Exclusiva',0,12005),
            ('Resgate de Fundo de Aposentadoria Programada Individual (Fapi)- Optante pela Tributa��o Exclusiva','Resgate de Fundo de Aposentadoria Programada Individual (Fapi)- Optante pela Tributa��o Exclusiva',0,12006),
            ('Resgate de Planos de Seguro de Vida com Cl�usula de Cobertura por Sobreviv�ncia- Optante pela Tributa��o Exclusiva','Resgate de Planos de Seguro de Vida com Cl�usula de Cobertura por Sobreviv�ncia- Optante pela Tributa��o Exclusiva',0,12007),
            ('Resgate de Planos de Seguro de Vida com Cl�usula de Cobertura por Sobreviv�ncia - N�o Optante pela Tributa��o Exclusiva','Resgate de Planos de Seguro de Vida com Cl�usula de Cobertura por Sobreviv�ncia - N�o Optante pela Tributa��o Exclusi...',0,12008),
            ('Benef�cio de Previd�ncia Complementar - Modalidade Contribui��o Definida/Vari�vel - N�o Optante pela Tributa��o Exclusiva','Benef�cio de Previd�ncia Complementar - Modalidade Contribui��o Definida/Vari�vel - N�o Optante pela Tributa��o Exclu...',0,12009),
            ('Benef�cio de Fundo de Aposentadoria Programada Individual (Fapi)- N�o Optante pela Tributa��o Exclusiva','Benef�cio de Fundo de Aposentadoria Programada Individual (Fapi)- N�o Optante pela Tributa��o Exclusiva',0,12010),
            ('Benef�cio de Previd�ncia Complementar - Modalidade Benef�cio Definido - N�o Optante pela Tributa��o Exclusiva','Benef�cio de Previd�ncia Complementar - Modalidade Benef�cio Definido - N�o Optante pela Tributa��o Exclusiva',0,12011),
            ('Benef�cio de Previd�ncia Complementar - Modalidade Contribui��o Definida/Vari�vel - Optante pela Tributa��o Exclusiva','Benef�cio de Previd�ncia Complementar - Modalidade Contribui��o Definida/Vari�vel - Optante pela Tributa��o Exclusiva',0,12012),
            ('Benef�cio de Fundo de Aposentadoria Programada Individual (Fapi)- Optante pela Tributa��o Exclusiva','Benef�cio de Fundo de Aposentadoria Programada Individual (Fapi)- Optante pela Tributa��o Exclusiva',0,12013),
            ('Benef�cio de Planos de Seguro de Vida com Cl�usula de Cobertura por Sobreviv�ncia- Optante pela Tributa��o Exclusiva','Benef�cio de Planos de Seguro de Vida com Cl�usula de Cobertura por Sobreviv�ncia- Optante pela Tributa��o Exclusiva',0,12014),
            ('Benef�cio de Planos de Seguro de Vida com Cl�usula de Cobertura por Sobreviv�ncia - N�o Optante pela Tributa��o Exclusiva','Benef�cio de Planos de Seguro de Vida com Cl�usula de Cobertura por Sobreviv�ncia - N�o Optante pela Tributa��o Exclu...',0,12015),
            ('Juros sobre o Capital Pr�prio','Juros sobre o Capital Pr�prio',0,12016),
            ('Rendimento de Aplica��es Financeiras de Renda Fixa, decorrentes de aliena��o, liquida��o (total ou parcial), resgate, cess�o ou repactua��o do t�tulo ou aplica��o','Rendimento de Aplica��es Financeiras de Renda Fixa, decorrentes de aliena��o, liquida��o (total ou parcial), resgate,...',0,12017),
            ('Rendimentos auferidos pela entrega de recursos � pessoa jur�dica, sob qualquer forma e a qualquer t�tulo, independentemente de ser ou n�o a fonte pagadora institui��o autorizada a funcionar pelo Banco Central','Rendimentos auferidos pela entrega de recursos � pessoa jur�dica, sob qualquer forma e a qualquer t�tulo, independent...',0,12018),
            ('Rendimentos predeterminados obtidos em opera��es conjugadas realizadas: nos mercados de op��es de compra e venda em bolsas de valores, de mercadorias e de futuros (box); no mercado a termo nas bolsas de valores, de mercadorias e de futuros, em opera��es de venda coberta e sem ajustes di�rios, e no mercado de balc�o.','Rendimentos predeterminados obtidos em opera��es conjugadas realizadas: nos mercados de op��es de compra e venda em b...',0,12019),
            ('Rendimentos obtidos nas opera��es de transfer�ncia de d�vidas realizadas com institui��o financeira e outras institui��es autorizadas a funcionar pelo Banco Central do Brasil','Rendimentos obtidos nas opera��es de transfer�ncia de d�vidas realizadas com institui��o financeira e outras institui...',0,12020),
            ('Rendimentos peri�dicos produzidos por t�tulo ou aplica��o, bem como qualquer remunera��o adicional aos rendimentos prefixados','Rendimentos peri�dicos produzidos por t�tulo ou aplica��o, bem como qualquer remunera��o adicional aos rendimentos pr...',0,12021),
            ('Rendimentos auferidos nas opera��es de m�tuo de recursos financeiros entre pessoa f�sica e pessoa jur�dica e entre pessoas jur�dicas, inclusive controladoras, controladas, coligadas e interligadas','Rendimentos auferidos nas opera��es de m�tuo de recursos financeiros entre pessoa f�sica e pessoa jur�dica e entre pe...',0,12022),
            ('Rendimentos auferidos em opera��es de adiantamento sobre contratos de c�mbio de exporta��o, n�o sacado (trava de c�mbio), bem como opera��es com export notes, com deb�ntures, com dep�sitos volunt�rios para garantia de inst�ncia e com dep�sitos judiciais ou administrativos, quando seu levantamento se der em favor do depositante','Rendimentos auferidos em opera��es de adiantamento sobre contratos de c�mbio de exporta��o, n�o sacado (trava de c�mb...',0,12023),
            ('Rendimentos obtidos nas opera��es de m�tuo e de compra vinculada � revenda tendo por objeto ouro, ativo financeiro','Rendimentos obtidos nas opera��es de m�tuo e de compra vinculada � revenda tendo por objeto ouro, ativo financeiro',0,12024),
            ('Rendimentos auferidos em contas de dep�sitos de poupan�a','Rendimentos auferidos em contas de dep�sitos de poupan�a',0,12025),
            ('Rendimentos auferidos sobre juros produzidos por letras hipotec�rias','Rendimentos auferidos sobre juros produzidos por letras hipotec�rias',0,12026),
            ('Rendimentos ou ganhos decorrentes da negocia��o de t�tulos ou valores mobili�rios de renda fixa em bolsas de valores, de mercadorias, de futuros e assemelhadas','Rendimentos ou ganhos decorrentes da negocia��o de t�tulos ou valores mobili�rios de renda fixa em bolsas de valores,...',0,12027),
            ('Rendimentos auferidos em outras aplica��es financeiras de renda fixa ou de renda vari�vel','Rendimentos auferidos em outras aplica��es financeiras de renda fixa ou de renda vari�vel',0,12028),
            ('Rendimentos auferidos em Fundo de Investimento','Rendimentos auferidos em Fundo de Investimento',0,12029),
            ('Rendimentos auferidos em Fundos de investimento em quotas de fundos de investimento','Rendimentos auferidos em Fundos de investimento em quotas de fundos de investimento',0,12030),
            ('Rendimentos produzidos por aplica��es em fundos de investimento em a��es','Rendimentos produzidos por aplica��es em fundos de investimento em a��es',0,12031),
            ('Rendimentos produzidos por aplica��es em fundos de investimento em quotas de fundos de investimento em a��es','Rendimentos produzidos por aplica��es em fundos de investimento em quotas de fundos de investimento em a��es',0,12032),
            ('Rendimentos produzidos por aplica��es em Fundos M�tuos de Privatiza��o com recursos do Fundo de Garantia por Tempo de Servi�o (FGTS)','Rendimentos produzidos por aplica��es em Fundos M�tuos de Privatiza��o com recursos do Fundo de Garantia por Tempo de...',0,12033),
            ('Rendimentos auferidos pela carteira dos Fundos de Investimento Imobili�rio','Rendimentos auferidos pela carteira dos Fundos de Investimento Imobili�rio',0,12034),
            ('Rendimentos distribu�dos pelo Fundo de Investimento Imobili�rio aos seus cotistas','Rendimentos distribu�dos pelo Fundo de Investimento Imobili�rio aos seus cotistas',0,12035),
            ('Rendimento auferido pelo cotista no resgate de cotas na liquida��o do Fundo de Investimento Imobili�rio','Rendimento auferido pelo cotista no resgate de cotas na liquida��o do Fundo de Investimento Imobili�rio',0,12036),
            ('Rendimentos auferidos pela carteira dos Fundos de Investimento Imobili�rio - Distribui��o semestral','Rendimentos auferidos pela carteira dos Fundos de Investimento Imobili�rio - Distribui��o semestral',0,12037),
            ('Rendimentos distribu�dos pelo Fundo de Investimento Imobili�rio aos seus cotistas - - Distribui��o semestral','Rendimentos distribu�dos pelo Fundo de Investimento Imobili�rio aos seus cotistas - - Distribui��o semestral',0,12038),
            ('Rendimento auferido pelo cotista no resgate de cotas na liquida��o do Fundo de Investimento Imobili�rio - Distribui��o semestral','Rendimento auferido pelo cotista no resgate de cotas na liquida��o do Fundo de Investimento Imobili�rio - Distribui��...',0,12039),
            ('Rendimentos e ganhos de capital distribu�dos pelo Fundo de Investimento Cultural e Art�stico (Ficart)','Rendimentos e ganhos de capital distribu�dos pelo Fundo de Investimento Cultural e Art�stico (Ficart)',0,12040),
            ('Rendimentos e ganhos de capital distribu�dos pelo Fundo de Financiamento da Ind�stria Cinematogr�fica Nacional (Funcines)','Rendimentos e ganhos de capital distribu�dos pelo Fundo de Financiamento da Ind�stria Cinematogr�fica Nacional (Funci...',0,12041),
            ('Rendimentos auferidos no resgate de quotas de fundos de investimento mantidos com recursos provenientes de convers�o de d�bitos externos brasileiros, e de que participem, exclusivamente, residentes ou domiciliados no exterior','Rendimentos auferidos no resgate de quotas de fundos de investimento mantidos com recursos provenientes de convers�o ...',0,12042),
            ('Ganho de capital decorrente da integraliza��o de cotas de fundos ou clubes de investimento por meio da entrega de ativos financeiros','Ganho de capital decorrente da integraliza��o de cotas de fundos ou clubes de investimento por meio daentrega de ati...',0,12043),
            ('Distribui��o de Juros sobre o Capital Pr�prio pela companhia emissora de a��es objeto de empr�stimo','Distribui��o de Juros sobre o Capital Pr�prio pela companhia emissora de a��es objeto de empr�stimo',0,12044),
            ('Rendimentos de Partes Benefici�rias ou de Fundador','Rendimentos de Partes Benefici�rias ou de Fundador',0,12045),
            ('Rendimentos auferidos em opera��es de swap','Rendimentos auferidos em opera��es de swap',0,12046),
            ('Rendimentos auferidos em opera��es day trade realizadas em bolsa de valores, de mercadorias, de futuros e assemelhadas','Rendimentos auferidos em opera��es day trade realizadas em bolsa de valores, de mercadorias, de futuros e assemelhada...',0,12047),
            ('Rendimento decorrente de Opera��o realizada em bolsas de valores, de mercadorias, de futuros, e assemelhadas, exceto day trade','Rendimento decorrente de Opera��o realizada em bolsas de valores, de mercadorias, de futuros, e assemelhadas, exceto ...',0,12048),
            ('Rendimento decorrente de Opera��o realizada no mercado de balc�o, com intermedia��o, tendo por objeto a��es, ouro ativo financeiro e outros valores mobili�rios negociados no mercado � vista','Rendimento decorrente de Opera��o realizada no mercado de balc�o, com intermedia��o, tendo por objeto a��es, ouro ati...',0,12049),
            ('Rendimento decorrente de Opera��o realizada em mercados de liquida��o futura fora de bolsa','Rendimento decorrente de Opera��o realizada em mercados de liquida��o futura fora de bolsa',0,12050),
            ('Rendimentos de deb�ntures emitidas por sociedade de prop�sito espec�fico conforme previsto no art. 2� da Lei n� 12.431 de 2011','Rendimentos de deb�ntures emitidas por sociedade de prop�sito espec�fico conforme previsto no art. 2� da Lei n� 12.43...',0,12051),
            ('Juros sobre o Capital Pr�prio cujos benefici�rios n�o estejam identificados no momento do registro cont�bil','Juros sobre o Capital Pr�prio cujos benefici�rios n�o estejam identificados no momento do registro cont�bil',0,12052),
            ('Demais rendimentos de Capital','Demais rendimentos de Capital',0,12099),
            ('Rendimentos de Aforamento','Rendimentos de Aforamento',0,13001),
            ('Rendimentos de Alugu�is, Loca��o ou Subloca��o','Rendimentos de Alugu�is, Loca��o ou Subloca��o',0,13002),
            ('Rendimentos de Arrendamento ou Subarrendamento','Rendimentos de Arrendamento ou Subarrendamento',0,13003),
            ('Import�ncias pagas por terceiros por conta do locador do bem (juros, comiss�es etc.)','Import�ncias pagas por terceiros por conta do locador do bem (juros, comiss�es etc.)',0,13004),
            ('Import�ncias pagas ao locador pelo contrato celebrado (luvas, pr�mios etc.)','Import�ncias pagas ao locador pelo contrato celebrado (luvas, pr�mios etc.)',0,13005),
            ('Benfeitorias e quaisquer melhoramentos realizados no bem locado','Benfeitorias e quaisquer melhoramentos realizados no bem locado',0,13006),
            ('Juros decorrente da aliena��o a prazo de bens','Juros decorrente da aliena��o a prazo de bens',0,13007),
            ('Rendimentos de Direito de Uso ou Passagem de Terrenos e de aproveitamento de �guas','Rendimentos de Direito de Uso ou Passagem de Terrenos e de aproveitamento de �guas',0,13008),
            ('Rendimentos de Direito de colher ou extrair recursos vegetais, pesquisar e extrair recursos minerais','Rendimentos de Direito de colher ou extrair recursos vegetais, pesquisar e extrair recursos minerais',0,13009),
            ('Rendimentos de Direito Autoral','Rendimentos de Direito Autoral',0,13010),
            ('Rendimentos de Direito Autoral (quando n�o percebidos pelo autor ou criador da obra)','Rendimentos de Direito Autoral (quando n�o percebidos pelo autor ou criador da obra)',0,13011),
            ('Rendimentos de Direito de Imagem','Rendimentos de Direito de Imagem',0,13012),
            ('Rendimentos de Direito sobre pel�culas cinematogr�ficas, Obras Audiovisuais, e Videof�nicas','Rendimentos de Direito sobre pel�culas cinematogr�ficas, Obras Audiovisuais, e Videof�nicas',0,13013),
            ('Rendimento de Direito relativo a radiodifus�o de sons e imagens e servi�o de comunica��o eletr�nica de massa por assinatura','Rendimento de Direito relativo a radiodifus�o de sons e imagens e servi�o de comunica��o eletr�nica de massa por assi...',0,13014),
            ('Rendimentos de Direito de Conjuntos Industriais e Inven��es','Rendimentos de Direito de Conjuntos Industriais e Inven��es',0,13015),
            ('Rendimento de Direito de marcas de ind�stria e com�rcio, patentes de inven��o e processo ou f�rmulas de fabrica��o','Rendimento de Direito de marcas de ind�stria e com�rcio, patentes de inven��o e processo ou f�rmulas de fabrica��o',0,13016),
            ('Import�ncias pagas por terceiros por conta do cedente dos direitos (juros, comiss�es etc.)','Import�ncias pagas por terceiros por conta do cedente dos direitos (juros, comiss�es etc.)',0,13017),
            ('Import�ncias pagas ao cedente do direito, pelo contrato celebrado (luvas, pr�mios etc.)','Import�ncias pagas ao cedente do direito, pelo contrato celebrado (luvas, pr�mios etc.)',0,13018),
            ('Despesas para conserva��o dos direitos cedidos (quando compensadas pelo uso do bem ou direito)','Despesas para conserva��o dos direitos cedidos (quando compensadas pelo uso do bem ou direito)',0,13019),
            ('Juros de mora e quaisquer outras compensa��es pelo atraso no pagamento de royalties - decorrente de presta��o de servi�o','Juros de mora e quaisquer outras compensa��es pelo atraso no pagamento de royalties - decorrente de presta��o de serv...',0,13020),
            ('Juros de mora e quaisquer outras compensa��es pelo atraso no pagamento de royalties - decorrente de aquisi��o de bens','Juros de mora e quaisquer outras compensa��es pelo atraso no pagamento de royalties - decorrente de aquisi��o de bens',0,13021),
            ('Juros decorrente da aliena��o a prazo de direitos - decorrente de presta��o de servi�o','Juros decorrente da aliena��o a prazo de direitos - decorrente de presta��o de servi�o',0,13022),
            ('Juros decorrente da aliena��o a prazo de direitos - decorrente de aquisi��o de bens','Juros decorrente da aliena��o a prazo de direitos - decorrente de aquisi��o de bens',0,13023),
            ('Aliena��o de bens e direitos do ativo n�o circulante localizados no Brasil','Aliena��o de bens e direitos do ativo n�o circulante localizados no Brasil',0,13024),
            ('Rendimento de Direito decorrente da transfer�ncia de atleta profissional','Rendimento de Direito decorrente da transfer�ncia de atleta profissional',0,13025),
            ('Juros e comiss�es correspondentes � parcela dos cr�ditos de que trata o inciso XI do art. 1� da Lei n� 9.481, de 1997, n�o aplicada no financiamento de exporta��es','Juros e comiss�es correspondentes � parcela dos cr�ditos de que trata o inciso XI do art. 1� da Lei n� 9.481, de 1997...',0,13026),
            ('Demais rendimentos de Royalties','Demais rendimentos de Royalties',0,13098),
            ('Demais rendimentos de Direito','Demais rendimentos de Direito',0,13099),
            ('Pr�mios distribu�dos, sob a forma de bens e servi�os, mediante loterias, concursos e sorteios, exceto a distribui��o realizada por meio de vale-brinde','Pr�mios distribu�dos, sob a forma de bens e servi�os, mediante loterias, concursos e sorteios, exceto a distribui��o ...',0,14001),
            ('Pr�mios distribu�dos, sob a forma de dinheiro, mediante loterias, concursos e sorteios, exceto os de antecipa��o nos t�tulos de capitaliza��o e os de amortiza��o e resgate das a��es das sociedades an�nimas','Pr�mios distribu�dos, sob a forma de dinheiro, mediante loterias, concursos e sorteios, exceto os de antecipa��o nos ...',0,14002),
            ('Pr�mios de Propriet�rios e Criadores de Cavalos de Corrida','Pr�mios de Propriet�rios e Criadores de Cavalos de Corrida',0,14003),
            ('Benef�cios l�quidos mediante sorteio de t�tulos de capitaliza��o, sem amortiza��o antecipada','Benef�cios l�quidos mediante sorteio de t�tulos de capitaliza��o, sem amortiza��o antecipada',0,14004),
            ('Benef�cios l�quidos resultantes da amortiza��o antecipada, mediante sorteio, dos t�tulos de capitaliza��o e benef�cios atribu�dos aos portadores de t�tulos de capitaliza��o nos lucros da empresa emitente','Benef�cios l�quidos resultantes da amortiza��o antecipada, mediante sorteio, dos t�tulos de capitaliza��o e benef�cio...',0,14005),
            ('Pr�mios distribu�dos, sob a forma de bens e servi�os, mediante sorteios de jogos de bingo permanente ou eventual','Pr�mios distribu�dos, sob a forma de bens e servi�os, mediante sorteios de jogos de bingo permanente ou eventual',0,14006),
            ('Pr�mios distribu�dos, em dinheiro, obtido mediante sorteios de jogos de bingo permanente ou eventual','Pr�mios distribu�dos, em dinheiro, obtido mediante sorteios de jogos de bingo permanente ou eventual',0,14007),
            ('Import�ncias correspondentes a multas e qualquer outra vantagem, ainda que a t�tulo de indeniza��o, em virtude de rescis�o de contrato','Import�ncias correspondentes a multas e qualquer outra vantagem, ainda que a t�tulo de indeniza��o, em virtude de res...',0,14008),
            ('Demais Benef�cios L�quidos decorrentes de t�tulo de capitaliza��o','Demais Benef�cios L�quidos decorrentes de t�tulo de capitaliza��o',0,14099),
            ('Import�ncias pagas ou creditadas a cooperativas de trabalho relativas a servi�os pessoais que lhes forem prestados por associados destas ou colocados � disposi��o','Import�ncias pagas ou creditadas a cooperativas de trabalho relativas a servi�os pessoais que lhes forem prestados po...',0,15001),
            ('Import�ncias pagas ou creditadas a associa��es de profissionais ou assemelhadas, relativas a servi�os pessoais que lhes forem prestados por associados destas ou colocados � disposi��o','Import�ncias pagas ou creditadas a associa��es de profissionais ou assemelhadas, relativas a servi�os pessoais que lh...',0,15002),
            ('Remunera��o de Servi�os de administra��o de bens ou neg�cios em geral, exceto cons�rcios ou fundos m�tuos para aquisi��o de bens','Remunera��o de Servi�os de administra��o de bens ou neg�cios em geral, exceto cons�rcios ou fundos m�tuos para aquisi...',0,15003),
            ('Remunera��o de Servi�os de advocacia','Remunera��o de Servi�os de advocacia',0,15004),
            ('Remunera��o de Servi�os de an�lise cl�nica laboratorial','Remunera��o de Servi�os de an�lise cl�nica laboratorial',0,15005),
            ('Remunera��o de Servi�os de an�lises t�cnicas','Remunera��o de Servi�os de an�lises t�cnicas',0,15006),
            ('Remunera��o de Servi�os de arquitetura','Remunera��o de Servi�os de arquitetura',0,15007),
            ('Remunera��o de Servi�os de assessoria e consultoria t�cnica, exceto servi�o de assist�ncia t�cnica prestado a terceiros e concernente a ramo de ind�stria ou com�rcio explorado pelo prestador do servi�o','Remunera��o de Servi�os de assessoria e consultoria t�cnica, exceto servi�o de assist�ncia t�cnica prestado a terceir...',0,15008),
            ('Remunera��o de Servi�os de assist�ncia social','Remunera��o de Servi�os de assist�ncia social',0,15009),
            ('Remunera��o de Servi�os de auditoria','Remunera��o de Servi�os de auditoria',0,15010),
            ('Remunera��o de Servi�os de avalia��o e per�cia','Remunera��o de Servi�os de avalia��o e per�cia',0,15011),
            ('Remunera��o de Servi�os de biologia e biomedicina','Remunera��o de Servi�os de biologia e biomedicina',0,15012),
            ('Remunera��o de Servi�os de c�lculo em geral','Remunera��o de Servi�os de c�lculo em geral',0,15013),
            ('Remunera��o de Servi�os de consultoria','Remunera��o de Servi�os de consultoria',0,15014),
            ('Remunera��o de Servi�os de contabilidade','Remunera��o de Servi�os de contabilidade',0,15015),
            ('Remunera��o de Servi�os de desenho t�cnico','Remunera��o de Servi�os de desenho t�cnico',0,15016),
            ('Remunera��o de Servi�os de economia','Remunera��o de Servi�os de economia',0,15017),
            ('Remunera��o de Servi�os de elabora��o de projetos','Remunera��o de Servi�os de elabora��o de projetos',0,15018),
            ('Remunera��o de Servi�os de engenharia, exceto constru��o de estradas, pontes, pr�dios e obras assemelhadas','Remunera��o de Servi�os de engenharia, exceto constru��o de estradas, pontes, pr�dios e obras assemelhadas',0,15019),
            ('Remunera��o de Servi�os de ensino e treinamento','Remunera��o de Servi�os de ensino e treinamento',0,15020),
            ('Remunera��o de Servi�os de estat�stica','Remunera��o de Servi�os de estat�stica',0,15021),
            ('Remunera��o de Servi�os de fisioterapia','Remunera��o de Servi�os de fisioterapia',0,15022),
            ('Remunera��o de Servi�os de fonoaudiologia','Remunera��o de Servi�os de fonoaudiologia',0,15023),
            ('Remunera��o de Servi�os de geologia','Remunera��o de Servi�os de geologia',0,15024),
            ('Remunera��o de Servi�os de leil�o','Remunera��o de Servi�os de leil�o',0,15025),
            ('Remunera��o de Servi�os de medicina, exceto aquela prestada por ambulat�rio, banco de sangue, casa de sa�de, casa de recupera��o ou repouso sob orienta��o m�dica, hospital e pronto-socorro','Remunera��o de Servi�os de medicina, exceto aquela prestada por ambulat�rio, banco de sangue, casa de sa�de, casa de ...',0,15026),
            ('Remunera��o de Servi�os de nutricionismo e diet�tica','Remunera��o de Servi�os de nutricionismo e diet�tica',0,15027),
            ('Remunera��o de Servi�os de odontologia','Remunera��o de Servi�os de odontologia',0,15028),
            ('Remunera��o de Servi�os de organiza��o de feiras de amostras, congressos, semin�rios, simp�sios e cong�neres','Remunera��o de Servi�os de organiza��o de feiras de amostras, congressos, semin�rios, simp�sios e cong�neres',0,15029),
            ('Remunera��o de Servi�os de pesquisa em geral','Remunera��o de Servi�os de pesquisa em geral',0,15030),
            ('Remunera��o de Servi�os de planejamento','Remunera��o de Servi�os de planejamento',0,15031),
            ('Remunera��o de Servi�os de programa��o','Remunera��o de Servi�os de programa��o',0,15032),
            ('Remunera��o de Servi�os de pr�tese','Remunera��o de Servi�os de pr�tese',0,15033),
            ('Remunera��o de Servi�os de psicologia e psican�lise','Remunera��o de Servi�os de psicologia e psican�lise',0,15034),
            ('Remunera��o de Servi�os de qu�mica','Remunera��o de Servi�os de qu�mica',0,15035),
            ('Remunera��o de Servi�os de radiologia e radioterapia','Remunera��o de Servi�os de radiologia e radioterapia',0,15036),
            ('Remunera��o de Servi�os de rela��es p�blicas','Remunera��o de Servi�os de rela��es p�blicas',0,15037),
            ('Remunera��o de Servi�os de servi�o de despachante','Remunera��o de Servi�os de servi�o de despachante',0,15038),
            ('Remunera��o de Servi�os de terap�utica ocupacional','Remunera��o de Servi�os de terap�utica ocupacional',0,15039),
            ('Remunera��o de Servi�os de tradu��o ou interpreta��o comercial','Remunera��o de Servi�os de tradu��o ou interpreta��o comercial',0,15040),
            ('Remunera��o de Servi�os de urbanismo','Remunera��o de Servi�os de urbanismo',0,15041),
            ('Remunera��o de Servi�os de veterin�ria','Remunera��o de Servi�os de veterin�ria',0,15042),
            ('Remunera��o de Servi�os de Limpeza','Remunera��o de Servi�os de Limpeza',0,15043),
            ('Remunera��o de Servi�os de Conserva��o/ Manuten��o, exceto reformas e obras assemelhadas','Remunera��o de Servi�os de Conserva��o/ Manuten��o, exceto reformas e obras assemelhadas',0,15044),
            ('Remunera��o de Servi�os de Seguran�a/Vigil�ncia/Transporte de valores','Remunera��o de Servi�os de Seguran�a/Vigil�ncia/Transporte de valores',0,15045),
            ('Remunera��o de Servi�os Loca��o de M�o de obra','Remunera��o de Servi�os Loca��o de M�o de obra',0,15046),
            ('Remunera��o de Servi�os de Assessoria Credit�cia, Mercadol�gica, Gest�o de Cr�dito, Sele��o e Riscos e Administra��o de Contas a Pagar e a Receber','Remunera��o de Servi�os de Assessoria Credit�cia, Mercadol�gica, Gest�o de Cr�dito, Sele��o e Riscos e Administra��o ...',0,15047),
            ('Pagamentos Referentes � Aquisi��o de Autope�as','Pagamentos Referentes � Aquisi��o de Autope�as',0,15048),
            ('Pagamentos a entidades imunes ou isentas - IN RFB 1.234/2012','Pagamentos a entidades imunes ou isentas - IN RFB 1.234/2012',0,15049),
            ('Pagamento a t�tulo de transporte internacional de valores efetuado por empresas nacionais estaleiros navais brasileiros nas atividades de conserva��o, moderniza��o, convers�o e reparo de embarca��es pr�-registradas ou registradas no Registro Especial Brasileiro (REB)','Pagamento a t�tulo de transporte internacional de valores efetuado por empresas nacionais estaleiros navais brasileir...',0,15050),
            ('Pagamento efetuado a empresas estrangeiras de transporte de valores','Pagamento efetuado a empresas estrangeiras de transporte de valores',0,15051),
            ('Demais comiss�es, corretagens, ou qualquer outra import�ncia paga/creditada pela representa��o comercial ou pela media��o na realiza��o de neg�cios civis e comerciais, que n�o se enquadrem nas situa��es listadas nos c�digos do grupo 20','Demais comiss�es, corretagens, ou qualquer outra import�ncia paga/creditada pela representa��o comercial ou pela medi...',0,15052),
            ('Demais rendimentos de servi�os t�cnicos, de assist�ncia t�cnica, de assist�ncia administrativa e semelhantes','Demais rendimentos de servi�os t�cnicos, de assist�ncia t�cnica, de assist�ncia administrativa e semelhantes',0,15099),
            ('Rendimentos de servi�os t�cnicos, de assist�ncia t�cnica, de assist�ncia administrativa e semelhantes','Rendimentos de servi�os t�cnicos, de assist�ncia t�cnica, de assist�ncia administrativa e semelhantes',0,16001),
            ('Demais rendimentos de juros e comiss�es','Demais rendimentos de juros e comiss�es',0,16002),
            ('Rendimento pago a companhia de navega��o a�rea e mar�tima','Rendimento pago a companhia de navega��o a�rea e mar�tima',0,16003),
            ('Rendimento de Direito relativo a explora��o de obras audiovisuais estrangeiras, radiodifus�o de sons e imagens e servi�o de comunica��o eletr�nica de massa por assinatura','Rendimento de Direito relativo a explora��o de obras audiovisuais estrangeiras, radiodifus�o de sons e imagens e serv...',0,16004),
            ('Demais Rendimentos de qualquer natureza','Demais Rendimentos de qualquer natureza',0,16005),
            ('Demais Rendimentos sujeitos � Al�quota Zero','Demais Rendimentos sujeitos � Al�quota Zero',0,16006),
            ('Fornecimento de bens, nos termos do art. 33 da Lei n� 10.833, de 2003','Fornecimento de bens, nos termos do art. 33 da Lei n� 10.833, de 2003',0,18001),
            ('Presta��o de servi�os em geral, nos termos do art. 33 da Lei n� 10.833, de 2003','Presta��o de servi�os em geral, nos termos do art. 33 da Lei n� 10.833, de 2003',0,18002),
            ('Transporte internacional de cargas ou de passageiros efetuados por empresas nacionais, aos estaleiros navais brasileiros e na aquisi��o de produtos isentos ou com Al�quota zero da Cofins e Pis/Pasep, conforme art. 4�, da IN SRF n� 475 de 2004.','Transporte internacional de cargas ou de passageiros efetuados por empresas nacionais, aos estaleiros navais brasilei...',0,18003),
            ('Pagamentos efetuados �s cooperativas, em rela��o aos atos cooperativos, conforme art. 5�, da IN SRF n� 475 de 2004.','Pagamentos efetuados �s cooperativas, em rela��o aos atos cooperativos, conforme art. 5�, da IN SRF n� 475 de 2004.',0,18004),
            ('Aquisi��o de im�vel pertencente a ativo permanente da empresa vendedora, conforme art. 19, II, da IN SRF n� 475 de 2004.','Aquisi��o de im�vel pertencente a ativo permanente da empresa vendedora, conforme art. 19, II, da IN SRF n� 475 de 20...',0,18005),
            ('Pagamentos efetuados �s sociedades cooperativas, pelo fornecimento de bens ou servi�os, conforme art. 24, II, da IN SRF n� 475 de 2004.','Pagamentos efetuados �s sociedades cooperativas, pelo fornecimento de bens ou servi�os, conforme art. 24, II, da IN S...',0,18006),
            ('Pagamentos efetuados � sociedade cooperativa de produ��o, em rela��o aos atos decorrentes da comercializa��o ou industrializa��o de produtos de seus associados, conforme art. 25, da IN SRF n� 475 de 2004.','Pagamentos efetuados � sociedade cooperativa de produ��o, em rela��o aos atos decorrentes da comercializa��o ou indus...',0,18007),
            ('Pagamentos efetuados �s cooperativas de trabalho, pela presta��o de servi�os pessoais prestados pelos cooperados, nos termos do art. 26, da IN SRF n� 475 de 2004.','Pagamentos efetuados �s cooperativas de trabalho, pela presta��o de servi�os pessoais prestados pelos cooperados, nos...',0,18008),
            ('Pagamento de remunera��o indireta a Benefici�rio n�o identificado','Pagamento de remunera��o indireta a Benefici�rio n�o identificado',0,19001),
            ('Pagamento a Benefici�rio n�o identificado','Pagamento a Benefici�rio n�o identificado',0,19009),
            ('Rendimento de Servi�os de propaganda e publicidade','Rendimento de Servi�os de propaganda e publicidade',0,20001),
            ('Import�ncias a t�tulo de comiss�es e corretagens relativas a coloca��o ou negocia��o de t�tulos de renda fixa','Import�ncias a t�tulo de comiss�es e corretagens relativas a coloca��o ou negocia��o de t�tulos de renda fixa',0,20002),
            ('Import�ncias a t�tulo de comiss�es e corretagens relativas a opera��es realizadas em Bolsas de Valores e em Bolsas de Mercadorias','Import�ncias a t�tulo de comiss�es e corretagens relativas a opera��es realizadas em Bolsas de Valores e em Bolsas d...',0,20003),
            ('Import�ncias a t�tulo de comiss�es e corretagens relativas a distribui��o de emiss�o de valores mobili�rios, quando a pessoa jur�dica atuar como agente da companhia emissora','Import�ncias a t�tulo de comiss�es e corretagens relativas a distribui��o de emiss�o de valores mobili�rios, quando a...',0,20004),
            ('Import�ncias a t�tulo de comiss�es e corretagens relativas a opera��es de c�mbio','Import�ncias a t�tulo de comiss�es e corretagens relativas a opera��es de c�mbio',0,20005),
            ('Import�ncias a t�tulo de comiss�es e corretagens relativas a vendas de passagens, excurs�es ou viagens','Import�ncias a t�tulo de comiss�es e corretagens relativas a vendas de passagens, excurs�es ou viagens',0,20006),
            ('Import�ncias a t�tulo de comiss�es e corretagens relativas a administra��o de cart�es de cr�dito','Import�ncias a t�tulo de comiss�es e corretagens relativas a administra��o de cart�es de cr�dito',0,20007),
            ('Import�ncias a t�tulo de comiss�es e corretagens relativas a presta��o de servi�os de distribui��o de refei��es pelo sistema de refei��es-conv�nio','Import�ncias a t�tulo de comiss�es e corretagens relativas a presta��o de servi�os de distribui��o de refei��es pelo ...',0,20008),
            ('Import�ncias a t�tulo de comiss�es e corretagens relativas a presta��o de servi�o de administra��o de conv�nios','Import�ncias a t�tulo de comiss�es e corretagens relativas a presta��o de servi�o de administra��o de conv�nios',0,20009);


            COMMIT;
        ";
        $this->execute($sql);
    }

    public function criaTabelaPagordemreinf(){
        $sql="
        BEGIN;

        CREATE TABLE IF NOT EXISTS empenho.pagordemreinf
        (
            e102_codord int4 NOT NULL,
            e102_numcgm int4 NOT NULL,
            e102_vlrbruto float8 NOT NULL DEFAULT 0,
            e102_vlrbase float8 NOT NULL DEFAULT 0,
            e102_vlrir float8 NOT NULL DEFAULT 0
        );

        INSERT INTO db_sysarquivo VALUES ((SELECT max(codarq)+1 FROM db_sysarquivo), 'pagordemreinf', 'Tabela de Reten��o Realizada por Terceiro', 'e102', CURRENT_DATE, 'Reten��o Realizada por Terceiro', 0, 'f', 'f', 'f', 'f' );
        INSERT INTO db_sysarqmod VALUES (38,(SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf'));

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e102_codord' ,'int4' ,'Ordem de Pagamento' ,'0' ,'Ordem de Pagamento' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Ordem de Pagamento' );
        INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
        VALUES (
        (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf'),
        (SELECT codcam FROM db_syscampo WHERE nomecam = 'e102_codord'),1 ,0 );

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e102_numcgm' ,'int4' ,'Num. CGM' ,'0' ,'Num. CGM' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Num. CGM' );
        INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
        VALUES (
        (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf'),
        (SELECT codcam FROM db_syscampo WHERE nomecam = 'e102_numcgm'),1 ,0 );

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e102_vlrbruto' ,'float8' ,'Valor Bruto' ,'' ,'Valor Bruto' ,15 ,'false' ,'false' ,'false' ,4 ,'text' ,'Valor Bruto' );
        INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
        VALUES (
        (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf'),
        (SELECT codcam FROM db_syscampo WHERE nomecam = 'e102_vlrbruto'),4 ,0 );

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e102_vlrbase' ,'float8' ,'Valor Base' ,'' ,'Valor Base' ,15 ,'false' ,'false' ,'false' ,4 ,'text' ,'Valor Base' );
        INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
        VALUES (
        (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf'),
        (SELECT codcam FROM db_syscampo WHERE nomecam = 'e102_vlrbase'),4 ,0 );

        INSERT INTO db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel )
        VALUES ((SELECT max(codcam) + 1 FROM db_syscampo) ,'e102_vlrir' ,'float8' ,'Valor IR' ,'' ,'Valor IR' ,15 ,'false' ,'false' ,'false' ,4 ,'text' ,'Valor IR' );
        INSERT INTO db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia )
        VALUES (
        (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf'),
        (SELECT codcam FROM db_syscampo WHERE nomecam = 'e102_vlrir'),4 ,0 );

        COMMIT;
        ";

        $this->execute($sql);
    }

    public function insereNovosCamposPagordem(){
        $sql="
            BEGIN;

            ALTER TABLE pagordem ADD COLUMN e50_retencaoir bool;
            ALTER TABLE pagordem ADD COLUMN e50_naturezabemservico int4;

            COMMIT;
        ";
        $this->execute($sql);
    }

    public function down(){
        $sql="
            BEGIN;

            DELETE FROM db_syssequencia WHERE nomesequencia = 'naturezabemservico_e101_sequencial_seq';

            DELETE FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico');

            DELETE FROM db_syscampo WHERE nomecam like 'e101_%';

            DELETE FROM db_sysarqmod WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico');

            DELETE FROM db_acount WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'naturezabemservico');

            DELETE FROM db_sysarquivo WHERE nomearq = 'naturezabemservico';

            DROP TABLE IF EXISTS empenho.naturezabemservico;


            DELETE FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf');

            DELETE FROM db_syscampo WHERE nomecam like 'e102_%';

            DELETE FROM db_sysarqmod WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf');

            DELETE FROM db_acount WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'pagordemreinf');

            DELETE FROM db_sysarquivo WHERE nomearq = 'pagordemreinf';

            DROP TABLE IF EXISTS empenho.pagordemreinf;


            ALTER TABLE pagordem DROP COLUMN e50_retencaoir;
            ALTER TABLE pagordem DROP COLUMN e50_naturezabemservico;

            COMMIT;
        ";

        $this->execute($sql);
    }
}
