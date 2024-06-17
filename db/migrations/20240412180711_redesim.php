<?php

use App\Support\Database\Sequence;
use Classes\PostgresMigration;

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

            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_sequencial', 'int8', 'Código Sequencial', '', 'Código Sequencial', 11, false, false, false, 1, 'text', 'Código Sequencial');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_url_api', 'text', 'End Point API URL', '', 'API URL', 200, false, false, false, 0, 'text', 'End Point API URL');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_client_id   ', 'text', 'Identificador do Cliente', '', 'Cliente ID', 200, false, false, false, 0, 'text', 'Cliente ID');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_vendor_id', 'text', 'Identificador do Fornecedor', '', 'Fornecedor ID', 200, false, false, false, 0, 'text', 'Fornecedor ID');
            INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES ((select max(codcam)+1 from db_syscampo), 'q180_access_key', 'text', 'Chave de acesso para utilização da API.', '', 'Chave acesso', 500, false, false, false, 0, 'text', 'Chave acesso');

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

            insert into issqn.issmotivoparalisacao values (99, 'INTEGRAÇÃO REDESIM');
            insert into issqn.issmotivobaixa values (99, 'INTEGRAÇÃO REDESIM');
            CREATE TABLE issqn.qualificacaosocio (
                q182_sequencial SERIAL NOT NULL,
                q182_codigo INTEGER NOT NULL,
                q182_descricao TEXT NOT NULL,
                CONSTRAINT "qualificacaosocio_pk" PRIMARY KEY ("q182_sequencial")
            );

            INSERT INTO issqn.qualificacaosocio VALUES
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 5  ,'ADMINISTRADOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 8 ,'CONSELHEIRO DE ADMINISTRAÇÃO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 10 ,'DIRETOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 16 ,'PRESIDENTE'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 17 ,'PROCURADOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 18 ,'SECRETÁRIO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 20 ,'SOCIEDADE CONSORCIADA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 21 ,'SOCIEDADE FILIADA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 22 ,'SÓCIO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 23 ,'SÓCIO CAPITALISTA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 24 ,'SÓCIO COMANDITADO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 25 ,'SÓCIO COMANDITÁRIO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 26 ,'SÓCIO DE INDÚSTRIA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 28 ,'SÓCIO-GERENTE'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 29 ,'SÓCIO INCAPAZ OU RELAT.INCAPAZ (EXCETO MENOR)'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 30 ,'SÓCIO MENOR (ASSISTIDO/REPRESENTADO)'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 31 ,'SÓCIO OSTENSIVO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 33 ,'TESOUREIRO'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 37 ,'SÓCIO PESSOA JURÍDICA DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 38 ,'SÓCIO PESSOA FÍSICA RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 47 ,'SÓCIO PESSOA FÍSICA RESIDENTE NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 48 ,'SÓCIO PESSOA JURÍDICA DOMICILIADO NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 49 ,'SÓCIO-ADMINISTRADOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 52 ,'SÓCIO COM CAPITAL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 53 ,'SÓCIO SEM CAPITAL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 54 ,'FUNDADOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 55 ,'SÓCIO COMANDITADO RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 56 ,'SÓCIO COMANDITÁRIO PESSOA FÍSICA RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 57 ,'SÓCIO COMANDITÁRIO PESSOA JURÍDICA DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 58 ,'SÓCIO COMANDITÁRIO INCAPAZ'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 59 ,'PRODUTOR RURAL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 63 ,'COTAS EM TESOURARIA'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 64 ,'ADMINISTRADOR JUDICIAL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 65 ,'TITULAR PESSOA FÍSICA RESIDENTE OU DOMICILIADO NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 66 ,'TITULAR PESSOA FÍSICA RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 67 ,'TITULAR PESSOA FÍSICA INCAPAZ OU RELATIVAMENTE INCAPAZ (EXCETO MENOR)'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 68 ,'TITULAR PESSOA FÍSICA MENOR (ASSISTIDO/REPRESENTADO)'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 70 ,'ADMINISTRADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 71 ,'CONSELHEIRO DE ADMINISTRAÇÃO RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 72 ,'DIRETOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 73 ,'PRESIDENTE RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 74 ,'SÓCIO-ADMINISTRADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 75 ,'FUNDADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 76 ,'PROTETOR'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 77 ,'VICE-PRESIDENTE'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 78 ,'TITULAR PESSOA JURÍDICA DOMICILIADA NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q182_sequencial_seq'), 79 ,'TITULAR PESSOA JURÍDICA DOMICILIADA NO EXTERIOR');

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
                    (1015,	'ÓRGÃO PÚBLICO DO PODER EXECUTIVO FEDERAL'),
                    (1023,	'ÓRGÃO PÚBLICO DO PODER EXECUTIVO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1031,	'ÓRGÃO PÚBLICO DO PODER EXECUTIVO MUNICIPAL'),
                    (1040,	'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO FEDERAL'),
                    (1058,	'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1066,	'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO MUNICIPAL'),
                    (1074,	'ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO FEDERAL'),
                    (1082,	'ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO ESTADUAL'),
                    (1104,	'AUTARQUIA FEDERAL'),
                    (1112,	'AUTARQUIA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1120,	'AUTARQUIA MUNICIPAL'),
                    (1139,	'FUNDAÇÃOPÚBLICA DE DIREITO PÚBLICOFEDERAL'),
                    (1147,	'FUNDAÇÃOPÚBLICA DE DIREITO PÚBLICOESTADUAL OU DO DISTRITO FEDERAL'),
                    (1155,	'FUNDAÇÃOPÚBLICA DE DIREITO PÚBLICOMUNICIPAL'),
                    (1163,	'ÓRGÃO PÚBLICO AUTÔNOMO FEDERAL'),
                    (1171,	'ÓRGÃO PÚBLICO AUTÔNOMO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1180,	'ÓRGÃO PÚBLICO AUTÔNOMO MUNICIPAL'),
                    (1198,	'COMISSÃO POLINACIONAL'),
                    (1210,	'CONSÓRCIO PÚBLICO DE DIREITO PÚBLICO(ASSOCIAÇÃO PÚBLICA)'),
                    (1228,	'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO'),
                    (1236,	'ESTADO OU DISTRITO FEDERAL'),
                    (1244,	'MUNICÍPIO'),
                    (1252,	'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO FEDERAL'),
                    (1260,	'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1279,	'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO MUNICIPAL'),
                    (1287,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA FEDERAL'),
                    (1295,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1309,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA MUNICIPAL'),
                    (1317,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA FEDERAL'),
                    (1325,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1333,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA MUNICIPAL'),
                    (1341,	'UNIÃO'),
                    (2011,	'EMPRESA PÚBLICA'),
                    (2038,	'SOCIEDADE DE ECONOMIA MISTA'),
                    (2046,	'SOCIEDADE ANÔNIMA ABERTA'),
                    (2054,	'SOCIEDADE ANÔNIMA FECHADA'),
                    (2062,	'SOCIEDADE EMPRESÁRIA LIMITADA'),
                    (2070,	'SOCIEDADE EMPRESÁRIA EM NOME COLETIVO'),
                    (2089,	'SOCIEDADE EMPRESÁRIA EM COMANDITA SIMPLES'),
                    (2097,	'SOCIEDADE EMPRESÁRIA EM COMANDITA POR AÇÕES'),
                    (2127,	'SOCIEDADE EM CONTA DE PARTICIPAÇÃO'),
                    (2135,	'EMPRESÁRIO (INDIVIDUAL)'),
                    (2143,	'COOPERATIVA'),
                    (2151,	'CONSÓRCIO DE SOCIEDADES'),
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
                    (2283,	'CONSÓRCIO DE EMPREGADORES'),
                    (2291,	'CONSÓRCIO SIMPLES'),
                    (2305,	'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA EMPRESÁRIA)'),
                    (2313,	'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA SIMPLES)'),
                    (2321,	'SOCIEDADE UNIPESSOAL DE ADVOCACIA'),
                    (2330,	'COOPERATIVAS DE CONSUMO'),
                    (2348,	'EMPRESA SIMPLES DE INOVAÇÃO - INOVA SIMPLES'),
                    (3034,	'SERVIÇO NOTARIAL E REGISTRAL (CARTÓRIO)'),
                    (3069,	'FUNDAÇÃO PRIVADA'),
                    (3077,	'SERVIÇO SOCIAL AUTÔNOMO'),
                    (3085,	'CONDOMÍNIO EDILÍCIO'),
                    (3107,	'COMISSÃO DE CONCILIAÇÃO PRÉVIA'),
                    (3115,	'ENTIDADE DE MEDIAÇÃO E ARBITRAGEM'),
                    (3131,	'ENTIDADE SINDICAL'),
                    (3204,	'ESTABELECIMENTO, NO BRASIL, DE FUNDAÇÃO OU ASSOCIAÇÃO ESTRANGEIRAS'),
                    (3212,	'FUNDAÇÃO OU ASSOCIAÇÃO DOMICILIADA NO EXTERIOR'),
                    (3220,	'ORGANIZAÇÃO RELIGIOSA'),
                    (3239,	'COMUNIDADE INDÍGENA'),
                    (3247,	'FUNDO PRIVADO'),
                    (3255,	'ÓRGÃO DE DIREÇÃO NACIONAL DE PARTIDO POLÍTICO'),
                    (3263,	'ÓRGÃO DE DIREÇÃO REGIONAL DE PARTIDO POLÍTICO'),
                    (3271,	'ÓRGÃO DE DIREÇÃO LOCAL DE PARTIDO POLÍTICO'),
                    (3280,	'COMITÊ FINANCEIRO DE PARTIDO POLÍTICO'),
                    (3298,	'FRENTE PLEBISCITÁRIA OU REFERENDÁRIA'),
                    (3301,	'ORGANIZAÇÃO SOCIAL (OS)'),
                    (3999,	'ASSOCIAÇÃO PRIVADA'),
                    (4014,	'EMPRESA INDIVIDUAL IMOBILIÁRIA'),
                    (4022,	'SEGURADO ESPECIAL'),
                    (4090,	'CANDIDATO A CARGO POLÍTICO ELETIVO'),
                    (4111,	'LEILOEIRO'),
                    (4120,	'PRODUTOR RURAL (PESSOA FÍSICA)'),
                    (5010,	'ORGANIZAÇÃO INTERNACIONAL'),
                    (5029,	'REPRESENTAÇÃO DIPLOMÁTICA ESTRANGEIRA'),
                    (5037,	'OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS');

SQL
        );
    }
}
