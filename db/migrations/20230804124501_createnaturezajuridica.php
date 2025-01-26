<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Createnaturezajuridica extends PostgresMigration
{
    public function up()
    {
        $sql = "create table naturezajurifica (
            n1_sequencial int8,
            n1_codigo varchar(4),
            n1_descricao text
            );

            CREATE SEQUENCE naturezajurifica_n1_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            alter table cgm add column z01_naturezajuridica varchar (4);

            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'0000', 'Natureza Jur�dica n�o informada');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1015','�rg�o P�blico do Poder Executivo Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1023','�rg�o P�blico do Poder Executivo Estadual ou do Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1031','�rg�o P�blico do Poder Executivo Municipal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1040','�rg�o P�blico do Poder Legislativo Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1058','�rg�o P�blico do Poder Legislativo Estadual ou do Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1066','�rg�o P�blico do Poder Legislativo Municipal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1074','�rg�o P�blico do Poder Judici�rio Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1082','�rg�o P�blico do Poder Judici�rio Estadual');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1104','Autarquia Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1112','Autarquia Estadual ou do Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1120','Autarquia Municipal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1139','Funda��o P�blica de Direito P�blico Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1147','Funda��o P�blica de Direito P�blico Estadual ou do Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1155','Funda��o P�blica de Direito P�blico Municipal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1163','�rg�o P�blico Aut�nomo Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1171','�rg�o P�blico Aut�nomo Estadual ou do Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1180','�rg�o P�blico Aut�nomo Municipal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1198','Comiss�o Polinacional');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1210','Cons�rcio P�blico de Direito P�blico (Associa��o P�blica)');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1228','Cons�rcio P�blico de Direito Privado');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1236','Estado ou Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1244','Munic�pio');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1252','Funda��o P�blica de Direito Privado Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1260','Funda��o P�blica de Direito Privado Estadual ou do Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1279','Funda��o P�blica de Direito Privado Municipal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1287','Fundo P�blico da Administra��o Indireta Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1295','Fundo P�blico da Administra��o Indireta Estadual ou do Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1309','Fundo P�blico da Administra��o Indireta Municipal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1317','Fundo P�blico da Administra��o Direta Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1325','Fundo P�blico da Administra��o Direta Estadual ou do Distrito Federal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1333','Fundo P�blico da Administra��o Direta Municipal');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'1341','Uni�o');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2011','Empresa P�blica');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2038','Sociedade de Economia Mista');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2046','Sociedade An�nima Aberta');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2054','Sociedade An�nima Fechada');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2062','Sociedade Empres�ria Limitada');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2070','Sociedade Empres�ria em Nome Coletivo');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2089','Sociedade Empres�ria em Comandita Simples');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2097','Sociedade Empres�ria em Comandita por A��es');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2100','Sociedade Mercantil de Capital e Ind�stria');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2127','Sociedade em Conta de Participa��o');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2135','Empres�rio (Individual)');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2143','Cooperativa');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2151','Cons�rcio de Sociedades');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2160','Grupo de Sociedades');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2178','Estabelecimento, no Brasil, de Sociedade Estrangeira');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2194','Estabelecimento, no Brasil, de Empresa Binacional Argentino-Brasileira');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2216','Empresa Domiciliada no Exterior');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2224','Clube/Fundo de Investimento');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2232','Sociedade Simples Pura');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2240','Sociedade Simples Limitada');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2259','Sociedade Simples em Nome Coletivo');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2267','Sociedade Simples em Comandita Simples');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2275','Empresa Binacional');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2283','Cons�rcio de Empregadores');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2291','Cons�rcio Simples');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2305','Empresa Individual de Responsabilidade Limitada (de Natureza Empres�ria)');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2313','Empresa Individual de Responsabilidade Limitada (de Natureza Simples)');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2321','Sociedade Unipessoal de Advocacia');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2330','Cooperativas de Consumo');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2348','Empresa Simples de Inova��o - Inova Simples');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'2356','Investidor N�o Residente');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3034','Servi�o Notarial e Registral (Cart�rio)');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3069','Funda��o Privada');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3077','Servi�o Social Aut�nomo');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3085','Condom�nio Edil�cio');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3107','Comiss�o de Concilia��o Pr�via');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3115','Entidade de Media��o e Arbitragem');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3131','Entidade Sindical');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3204','Estabelecimento, no Brasil, de Funda��o ou Associa��o Estrangeiras');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3212','Funda��o ou Associa��o Domiciliada no Exterior');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3220','Organiza��o Religiosa');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3239','Comunidade Ind�gena');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3247','Fundo Privado');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3255','�rg�o de Dire��o Nacional de Partido Pol�tico');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3263','�rg�o de Dire��o Regional de Partido Pol�tico');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3271','�rg�o de Dire��o Local de Partido Pol�tico');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3280','Comit� Financeiro de Partido Pol�tico');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3298','Frente Plebiscit�ria ou Referend�ria');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3301','Organiza��o Social (OS)');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3328','Plano de Benef�cios de Previd�ncia Complementar Fechada');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'3999','Associa��o Privada');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'4014','Empresa Individual Imobili�ria');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'4090','Candidato a Cargo Pol�tico Eletivo');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'4120','Produtor Rural (Pessoa F�sica)');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'5010','Organiza��o Internacional');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'5029','Representa��o Diplom�tica Estrangeira');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'5037','Outras Institui��es Extraterritoriais');
            insert into naturezajurifica values(nextval('naturezajurifica_n1_sequencial_seq'),'8885','Natureza Jur�dica n�o informada');
        ";

        $this->execute($sql);
    }
}
