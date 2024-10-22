<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22603 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Planos de Contratação', 'Planos de Contratação', 'com_planodecontratacao001.php', 1, 1, 'Planos de Contratação', 'f');
            INSERT INTO db_menu VALUES(32,(select max(id_item) from db_itensmenu),491,28);

            CREATE TABLE compras.pccategoria (
                mpc03_codigo INTEGER NOT NULL DEFAULT 0,
                mpc03_pcdesc VARCHAR(255) NOT NULL,
                CONSTRAINT pccategoria_mpc03_codigo_pk PRIMARY KEY (mpc03_codigo)
            );

            CREATE TABLE compras.pccatalogo (
                mpc04_codigo INTEGER NOT NULL DEFAULT 0,
                mpc04_pcdesc VARCHAR(255) NOT NULL,
                CONSTRAINT pcpcatalogo_mpc04_codigo_pk PRIMARY KEY (mpc04_codigo)
            );

            CREATE TABLE compras.pctipoproduto (
                mpc05_codigo INTEGER NOT NULL DEFAULT 0,
                mpc05_pcdesc VARCHAR(255) NOT NULL,
                CONSTRAINT pctipoproduto_mpc05_codigo_pk PRIMARY KEY (mpc05_codigo)
            );

            CREATE TABLE compras.pcplanocontratacao (
                mpc01_codigo INTEGER NOT NULL DEFAULT 0,
                mpc01_ano INTEGER CHECK (mpc01_ano >= 1000 AND mpc01_ano <= 9999) NOT NULL DEFAULT 0,
                mpc01_uncompradora INTEGER NOT NULL DEFAULT 0,
                mpc01_data DATE NOT NULL,
                mpc01_datacria DATE NOT NULL DEFAULT NOW(),
                mpc01_usuario INTEGER NOT NULL DEFAULT 0,
                mpc01_sequencial VARCHAR(255) NULL,
                mpc01_is_send_pncp INTEGER DEFAULT 0,
                CONSTRAINT pcplanocontratacao_mpc01_codigo_pk PRIMARY KEY (mpc01_codigo),
                CONSTRAINT pcplanocontratacao_mpc01_uncompradora_fk FOREIGN KEY (mpc01_uncompradora) REFERENCES configuracoes.db_depart(coddepto),
                CONSTRAINT pcplanocontratacao_mpc01_usuario_fk FOREIGN KEY (mpc01_usuario) REFERENCES configuracoes.db_usuarios(id_usuario)
            );

            CREATE TABLE compras.pcpcitem (
                mpc02_codigo INTEGER NOT NULL DEFAULT 0,
                mpc02_codmater INTEGER NOT NULL,
                mpc02_datap DATE,
                mpc02_categoria INTEGER NULL,
                mpc02_qtdd NUMERIC(10, 4) DEFAULT 0,
                mpc02_vlrunit NUMERIC(10, 4) DEFAULT 0,
                mpc02_vlrtotal NUMERIC(10, 4) DEFAULT 0,
                mpc02_un INTEGER NOT NULL,
                mpc02_depto INTEGER NOT NULL,
                mpc02_catalogo INTEGER NULL,
                mpc02_tproduto INTEGER NULL,
                mpc02_subgrupo INTEGER NOT NULL,
                CONSTRAINT pcpcitem_mpc02_codigo_pk PRIMARY KEY (mpc02_codigo),
                CONSTRAINT pcpcitem_mpc02_codmater_fk FOREIGN KEY (mpc02_codmater) REFERENCES compras.pcmater(pc01_codmater),
                CONSTRAINT pcpcitem_mpc02_categoria_fk FOREIGN KEY (mpc02_categoria) REFERENCES compras.pccategoria(mpc03_codigo),
                CONSTRAINT pcpcitem_mpc02_catalogo_fk FOREIGN KEY (mpc02_catalogo) REFERENCES compras.pccatalogo(mpc04_codigo),
                CONSTRAINT pcpcitem_mpc02_tproduto_fk FOREIGN KEY (mpc02_tproduto) REFERENCES compras.pctipoproduto(mpc05_codigo),
                CONSTRAINT pcpcitem_mpc02_un_fk FOREIGN KEY (mpc02_un) REFERENCES material.matunid(m61_codmatunid),
                CONSTRAINT pcpcitem_mpc02_depto_fk FOREIGN KEY (mpc02_depto) REFERENCES configuracoes.db_config(codigo),
                CONSTRAINT pcpcitem_mpc02_subgrupo_fk FOREIGN KEY (mpc02_subgrupo) REFERENCES compras.pcsubgrupo(pc04_codsubgrupo)
            );

            CREATE TABLE compras.pcplanocontratacaopcpcitem(
                mpcpc01_codigo INTEGER NOT NULL DEFAULT 0,
                mpc01_pcplanocontratacao_codigo INTEGER NOT NULL,
                mpc02_pcpcitem_codigo INTEGER NOT NULL,
                mpcpc01_is_send_pncp INTEGER DEFAULT 0,
                mpcpc01_cod_item_itegracao INTEGER DEFAULT 0,
                mpcpc01_numero_item INTEGER DEFAULT NULL,
                CONSTRAINT pcplanocontratacaopcpcitem_mpcpc01_codigo_pk PRIMARY KEY (mpcpc01_codigo),
                CONSTRAINT pcplanocontratacaopcpcitem_mpc01_pcplanocontratacao_codigo_fk FOREIGN KEY (mpc01_pcplanocontratacao_codigo) REFERENCES compras.pcplanocontratacao(mpc01_codigo),
                CONSTRAINT pcplanocontratacaopcpcitem_mpc02_pcpcitem_codigo_fk FOREIGN KEY (mpc02_pcpcitem_codigo) REFERENCES compras.pcpcitem(mpc02_codigo)
            );

            CREATE TABLE compras.pcplanocontratacaointegracao(
                mpci01_codigo INTEGER NOT NULL DEFAULT 0,
                mpci01_pcplanocontratacao_codigo INTEGER NOT NULL,
                mpci01_sequencial bigint NOT NULL,
                mpci01_usuario bigint NOT NULL,
                mpci01_dtlancamento date NOT NULL,
                mpci01_anousu bigint NOT NULL,
                mpci01_instit bigint NOT NULL,
                mpci01_ano bigint NOT NULL,
                mpci01_status bigint NOT NULL,
                mpci01_response_body JSONB NULL,
                mpci01_send_body JSONB NULL,
                mpci01_response_headers JSONB NULL,
                mpci01_send_headers JSONB NULL,
                mpci01_url varchar(255) NULL,
                CONSTRAINT pcplanocontratacaointegracao_mpci01_codigo_pk PRIMARY KEY (mpci01_codigo)
            );

            CREATE SEQUENCE IF NOT EXISTS mpc01_codigo_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            CREATE SEQUENCE IF NOT EXISTS mpc02_codigo_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            CREATE SEQUENCE IF NOT EXISTS pccategoria_mpc03_codigo_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            CREATE SEQUENCE IF NOT EXISTS pccatalogo_mpc04_codigo_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            CREATE SEQUENCE IF NOT EXISTS pctipoproduto_mpc05_codigo_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            CREATE SEQUENCE IF NOT EXISTS pcplanocontratacaopcpcitem_mpcpc01_codigo_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            CREATE SEQUENCE IF NOT EXISTS pcplanocontratacaointegracao_mpci01_codigo_seq
                START WITH 1
                INCREMENT BY 1
                NO MINVALUE
                NO MAXVALUE
                CACHE 1;

            INSERT INTO compras.pccatalogo
              (mpc04_codigo, mpc04_pcdesc)
            VALUES
                (1,'CNBS - Catálogo Nacional de Bens e Serviços'),
                (99,'Outros');


            INSERT INTO compras.pccategoria
                (mpc03_codigo, mpc03_pcdesc)
            VALUES
                (1,'Material'),
                (2,'Serviço'),
                (3,'Obras'),
                (4,'Serviços de Engenharia'),
                (5,'Soluções de TIC'),
                (6,'Locação de Imóveis'),
                (7,'Alienação/Concessão/Permissão'),
                (8,'Obras e Serviços de Engenharia');


            INSERT INTO compras.pctipoproduto
                (mpc05_codigo, mpc05_pcdesc)
            VALUES
                (1,'Material'),
                (2,'Serviço');

        ";

        $this->execute($sql);
    }
}
