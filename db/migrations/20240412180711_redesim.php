<?php

use App\Support\Database\Sequence;
use ECidade\Suporte\Phinx\PostgresMigration;

class Redesim extends PostgresMigration
{
    use Sequence;

    public const COD_MODULO_ISSQN = 3;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createRedesimSettingsTable();
        $this->insertDicionarioDadosRedesimSettingsTable();
        $this->addItemMenu();
        $this->createAuxTables();
        $this->createRedesimLogTable();
    }

    public function createRedesimSettingsTable()
    {
        $this->createSequence('parametros_redesim_q180_sequencial_seq', 'issqn');

        $this->table(
            'parametros_redesim',
            [
                'id' => false,
                'primary_key' => ['q180_sequencial'],
                'schema' => 'issqn'
            ]
        )
            ->addColumn('q180_sequencial', 'integer')
            ->addColumn('q180_client_id', 'text')
            ->addColumn('q180_vendor_id', 'text')
            ->addColumn('q180_access_key', 'text')
            ->addColumn('q180_url_api', 'text')
            ->create();


        $this->setAsAutoIncrement(
            'issqn.parametros_redesim',
            'q180_sequencial',
            'issqn.parametros_redesim_q180_sequencial'
        );
    }

    public function createRedesimLogTable()
    {
        $this->createSequence('redesim_log_q190_sequencial_seq', 'issqn');

        $this->table(
            'redesim_log',
            [
                'id' => false,
                'primary_key' => ['q190_sequencial'],
                'schema' => 'issqn'
            ]
        )
            ->addColumn('q190_sequencial', 'integer')
            ->addColumn('q190_data', 'datetime')
            ->addColumn('q190_cpfcnpj', 'string', ['limit' => 14])
            ->addColumn('q190_json', 'json')
            ->create();


        $this->setAsAutoIncrement(
            'issqn.redesim_log',
            'q190_sequencial',
            'issqn.redesim_log_q190_sequencial'
        );
    }

    private function insertDicionarioDadosRedesimSettingsTable()
    {
        $codModuloIssqn = self::COD_MODULO_ISSQN;
        $sql = <<<SQL
            INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES ((select max(codarq)+1 from db_sysarquivo), 'parametros_redesim', 'Configuracoes de integragacao com a API Redesim', 'q180', '2024-04-17', 'Configuracoes de integragacao com a API Redesim', 0, false, false, false, false);

            INSERT INTO db_sysarqmod (codmod, codarq) VALUES ($codModuloIssqn, (select max(codarq) from db_sysarquivo));

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_sequencial', 'int8', 'C�digo Sequencial', '', 'C�digo Sequencial', 11, false, false, false, 1, 'text', 'C�digo Sequencial');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_url_api', 'text', 'End Point API URL', '', 'API URL', 200, false, false, false, 0, 'text', 'End Point API URL');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_client_id   ', 'text', 'Identificador do Cliente', '', 'Cliente ID', 200, false, false, false, 0, 'text', 'Cliente ID');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_vendor_id', 'text', 'Identificador do Fornecedor', '', 'Fornecedor ID', 200, false, false, false, 0, 'text', 'Fornecedor ID');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_access_key', 'text', 'Chave de acesso para utiliza��o da API.', '', 'Chave acesso', 500, false, false, false, 0, 'text', 'Chave acesso');

            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'parametros_redesim'), (select codcam from db_syscampo where nomecam = 'q180_sequencial'), 1, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'parametros_redesim'), (select codcam from db_syscampo where nomecam = 'q180_url_api'), 2, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'parametros_redesim'), (select codcam from db_syscampo where nomecam = 'q180_client_id'), 3, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'parametros_redesim'), (select codcam from db_syscampo where nomecam = 'q180_vendor_id'), 4, 0);
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select codarq from db_sysarquivo where nomearq = 'parametros_redesim'), (select codcam from db_syscampo where nomecam = 'q180_access_key'), 5, 0);

            INSERT INTO db_sysprikey (codarq, codcam, sequen, referen, camiden) VALUES ((select codarq from db_sysarquivo where nomearq = 'parametros_redesim'), (select codcam from db_syscampo where nomecam = 'q180_sequencial'), 1, 0, 0);
SQL;
        $this->execute($sql);
    }

    private function addItemMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu VALUES (
            (SELECT max(id_item)+1 FROM db_itensmenu),
            'Redesim',
            'Redesim',
            'iss1_parametros_redesim002.php',
            1,
            1,
            'Redesim',
            't');

        INSERT INTO db_menu VALUES(
            608574,
            (SELECT max(id_item) from db_itensmenu),
            (SELECT MAX(menusequencia)+1 AS COUNT FROM db_menu WHERE id_item = 608574),
            40);
        ";

        $this->execute($sql);
    }

    private function createAuxTables()
    {
        $this->execute(<<<SQL

            insert into issqn.issmotivoparalisacao values (99, 'INTEGRA��O REDESIM');
            insert into issqn.issmotivobaixa values (99, 'INTEGRA��O REDESIM');
            CREATE TABLE issqn.qualificacaosocio (
                q182_sequencial SERIAL NOT NULL,
                q182_codigo INTEGER NOT NULL,
                q182_descricao TEXT NOT NULL,
                CONSTRAINT "qualificacaosocio_pk" PRIMARY KEY ("q182_sequencial")
            );

            INSERT INTO issqn.qualificacaosocio VALUES
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 5  ,'ADMINISTRADOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 8 ,'CONSELHEIRO DE ADMINISTRA��O'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 10 ,'DIRETOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 16 ,'PRESIDENTE'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 17 ,'PROCURADOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 18 ,'SECRET�RIO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 20 ,'SOCIEDADE CONSORCIADA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 21 ,'SOCIEDADE FILIADA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 22 ,'S�CIO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 23 ,'S�CIO CAPITALISTA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 24 ,'S�CIO COMANDITADO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 25 ,'S�CIO COMANDIT�RIO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 26 ,'S�CIO DE IND�STRIA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 28 ,'S�CIO-GERENTE'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 29 ,'S�CIO INCAPAZ OU RELAT.INCAPAZ (EXCETO MENOR)'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 30 ,'S�CIO MENOR (ASSISTIDO/REPRESENTADO)'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 31 ,'S�CIO OSTENSIVO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 33 ,'TESOUREIRO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 37 ,'S�CIO PESSOA JUR�DICA DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 38 ,'S�CIO PESSOA F�SICA RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 47 ,'S�CIO PESSOA F�SICA RESIDENTE NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 48 ,'S�CIO PESSOA JUR�DICA DOMICILIADO NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 49 ,'S�CIO-ADMINISTRADOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 52 ,'S�CIO COM CAPITAL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 53 ,'S�CIO SEM CAPITAL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 54 ,'FUNDADOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 55 ,'S�CIO COMANDITADO RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 56 ,'S�CIO COMANDIT�RIO PESSOA F�SICA RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 57 ,'S�CIO COMANDIT�RIO PESSOA JUR�DICA DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 58 ,'S�CIO COMANDIT�RIO INCAPAZ'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 59 ,'PRODUTOR RURAL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 63 ,'COTAS EM TESOURARIA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 64 ,'ADMINISTRADOR JUDICIAL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 65 ,'TITULAR PESSOA F�SICA RESIDENTE OU DOMICILIADO NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 66 ,'TITULAR PESSOA F�SICA RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 67 ,'TITULAR PESSOA F�SICA INCAPAZ OU RELATIVAMENTE INCAPAZ (EXCETO MENOR)'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 68 ,'TITULAR PESSOA F�SICA MENOR (ASSISTIDO/REPRESENTADO)'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 70 ,'ADMINISTRADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 71 ,'CONSELHEIRO DE ADMINISTRA��O RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 72 ,'DIRETOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 73 ,'PRESIDENTE RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 74 ,'S�CIO-ADMINISTRADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 75 ,'FUNDADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 76 ,'PROTETOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 77 ,'VICE-PRESIDENTE'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 78 ,'TITULAR PESSOA JUR�DICA DOMICILIADA NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 79 ,'TITULAR PESSOA JUR�DICA DOMICILIADA NO EXTERIOR');

            ALTER TABLE issqn.socios ADD COLUMN q95_qualificacaosocio INTEGER;
            ALTER TABLE issqn.socios ADD CONSTRAINT "socios_qualificacaosocio_fk" FOREIGN KEY ("q95_qualificacaosocio") REFERENCES issqn.qualificacaosocio ("q182_sequencial");

            CREATE TABLE issqn.inscricaoredesim (
                q179_sequencial SERIAL NOT NULL,
                q179_inscricao INTEGER NOT NULL,
                q179_inscricaoredesim VARCHAR NOT NULL,
                q179_dadosregistro JSON NOT NULL,
                CONSTRAINT "inscricaoredesim_pk" PRIMARY KEY ("q179_sequencial"),
                CONSTRAINT "inscricaoredesim_issbase_fk" FOREIGN KEY ("q179_inscricao") REFERENCES issqn.issbase ("q02_inscr")
            );

            INSERT INTO configuracoes.tipoempresa
                    (
                        db98_sequencial,
                        db98_descricao
                    )
                 VALUES
                    (1015,	'�RG�O P�BLICO DO PODER EXECUTIVO FEDERAL'),
                    (1023,	'�RG�O P�BLICO DO PODER EXECUTIVO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1031,	'�RG�O P�BLICO DO PODER EXECUTIVO MUNICIPAL'),
                    (1040,	'�RG�O P�BLICO DO PODER LEGISLATIVO FEDERAL'),
                    (1058,	'�RG�O P�BLICO DO PODER LEGISLATIVO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1066,	'�RG�O P�BLICO DO PODER LEGISLATIVO MUNICIPAL'),
                    (1074,	'�RG�O P�BLICO DO PODER JUDICI�RIO FEDERAL'),
                    (1082,	'�RG�O P�BLICO DO PODER JUDICI�RIO ESTADUAL'),
                    (1104,	'AUTARQUIA FEDERAL'),
                    (1112,	'AUTARQUIA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1120,	'AUTARQUIA MUNICIPAL'),
                    (1139,	'FUNDA��OP�BLICA DE DIREITO P�BLICOFEDERAL'),
                    (1147,	'FUNDA��OP�BLICA DE DIREITO P�BLICOESTADUAL OU DO DISTRITO FEDERAL'),
                    (1155,	'FUNDA��OP�BLICA DE DIREITO P�BLICOMUNICIPAL'),
                    (1163,	'�RG�O P�BLICO AUT�NOMO FEDERAL'),
                    (1171,	'�RG�O P�BLICO AUT�NOMO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1180,	'�RG�O P�BLICO AUT�NOMO MUNICIPAL'),
                    (1198,	'COMISS�O POLINACIONAL'),
                    (1210,	'CONS�RCIO P�BLICO DE DIREITO P�BLICO(ASSOCIA��O P�BLICA)'),
                    (1228,	'CONS�RCIO P�BLICO DE DIREITO PRIVADO'),
                    (1236,	'ESTADO OU DISTRITO FEDERAL'),
                    (1244,	'MUNIC�PIO'),
                    (1252,	'CONS�RCIO P�BLICO DE DIREITO PRIVADO FEDERAL'),
                    (1260,	'CONS�RCIO P�BLICO DE DIREITO PRIVADO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1279,	'CONS�RCIO P�BLICO DE DIREITO PRIVADO MUNICIPAL'),
                    (1287,	'FUNDO P�BLICO DA ADMINISTRA��O INDIRETA FEDERAL'),
                    (1295,	'FUNDO P�BLICO DA ADMINISTRA��O INDIRETA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1309,	'FUNDO P�BLICO DA ADMINISTRA��O INDIRETA MUNICIPAL'),
                    (1317,	'FUNDO P�BLICO DA ADMINISTRA��O DIRETA FEDERAL'),
                    (1325,	'FUNDO P�BLICO DA ADMINISTRA��O DIRETA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1333,	'FUNDO P�BLICO DA ADMINISTRA��O DIRETA MUNICIPAL'),
                    (1341,	'UNI�O'),
                    (2011,	'EMPRESA P�BLICA'),
                    (2038,	'SOCIEDADE DE ECONOMIA MISTA'),
                    (2046,	'SOCIEDADE AN�NIMA ABERTA'),
                    (2054,	'SOCIEDADE AN�NIMA FECHADA'),
                    (2062,	'SOCIEDADE EMPRES�RIA LIMITADA'),
                    (2070,	'SOCIEDADE EMPRES�RIA EM NOME COLETIVO'),
                    (2089,	'SOCIEDADE EMPRES�RIA EM COMANDITA SIMPLES'),
                    (2097,	'SOCIEDADE EMPRES�RIA EM COMANDITA POR A��ES'),
                    (2127,	'SOCIEDADE EM CONTA DE PARTICIPA��O'),
                    (2135,	'EMPRES�RIO (INDIVIDUAL)'),
                    (2143,	'COOPERATIVA'),
                    (2151,	'CONS�RCIO DE SOCIEDADES'),
                    (2160,	'GRUPO DE SOCIEDADES'),
                    (2178,	'ESTABELECIMENTO, NO BRASIL, DE SOCIEDADE ESTRANGEIRA'),
                    (2194,	'ESTABELECIMENTO, NO BRASIL, DE EMPRESA BINACIONAL ARGENTINO-BRASILEIRA'),
                    (2216,	'EMPRESA DOMICILIADA NO EXTERIOR'),
                    (2224,	'CLUBE/FUNDO DE INVESTIMENTO'),
                    (2232,	'SOCIEDADE SIMPLES PURA'),
                    (2240,	'SOCIEDADE SIMPLES LIMITADA'),
                    (2259,	'SOCIEDADE SIMPLES EM NOME COLETIVO'),
                    (2267,	'SOCIEDADE SIMPLES EM COMANDITA SIMPLES'),
                    (2275,	'EMPRESA BINACIONAL'),
                    (2283,	'CONS�RCIO DE EMPREGADORES'),
                    (2291,	'CONS�RCIO SIMPLES'),
                    (2305,	'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA EMPRES�RIA)'),
                    (2313,	'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA SIMPLES)'),
                    (2321,	'SOCIEDADE UNIPESSOAL DE ADVOCACIA'),
                    (2330,	'COOPERATIVAS DE CONSUMO'),
                    (2348,	'EMPRESA SIMPLES DE INOVA��O - INOVA SIMPLES'),
                    (3034,	'SERVI�O NOTARIAL E REGISTRAL (CART�RIO)'),
                    (3069,	'FUNDA��O PRIVADA'),
                    (3077,	'SERVI�O SOCIAL AUT�NOMO'),
                    (3085,	'CONDOM�NIO EDIL�CIO'),
                    (3107,	'COMISS�O DE CONCILIA��O PR�VIA'),
                    (3115,	'ENTIDADE DE MEDIA��O E ARBITRAGEM'),
                    (3131,	'ENTIDADE SINDICAL'),
                    (3204,	'ESTABELECIMENTO, NO BRASIL, DE FUNDA��O OU ASSOCIA��O ESTRANGEIRAS'),
                    (3212,	'FUNDA��O OU ASSOCIA��O DOMICILIADA NO EXTERIOR'),
                    (3220,	'ORGANIZA��O RELIGIOSA'),
                    (3239,	'COMUNIDADE IND�GENA'),
                    (3247,	'FUNDO PRIVADO'),
                    (3255,	'�RG�O DE DIRE��O NACIONAL DE PARTIDO POL�TICO'),
                    (3263,	'�RG�O DE DIRE��O REGIONAL DE PARTIDO POL�TICO'),
                    (3271,	'�RG�O DE DIRE��O LOCAL DE PARTIDO POL�TICO'),
                    (3280,	'COMIT� FINANCEIRO DE PARTIDO POL�TICO'),
                    (3298,	'FRENTE PLEBISCIT�RIA OU REFEREND�RIA'),
                    (3301,	'ORGANIZA��O SOCIAL (OS)'),
                    (3999,	'ASSOCIA��O PRIVADA'),
                    (4014,	'EMPRESA INDIVIDUAL IMOBILI�RIA'),
                    (4022,	'SEGURADO ESPECIAL'),
                    (4090,	'CANDIDATO A CARGO POL�TICO ELETIVO'),
                    (4111,	'LEILOEIRO'),
                    (4120,	'PRODUTOR RURAL (PESSOA F�SICA)'),
                    (5010,	'ORGANIZA��O INTERNACIONAL'),
                    (5029,	'REPRESENTA��O DIPLOM�TICA ESTRANGEIRA'),
                    (5037,	'OUTRAS INSTITUI��ES EXTRATERRITORIAIS');

SQL
        );
    }
}
