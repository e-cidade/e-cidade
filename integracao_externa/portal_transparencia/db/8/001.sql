CREATE SEQUENCE empenhos_diarias_id_seq;

CREATE TABLE empenhos_diarias (
                id INTEGER NOT NULL DEFAULT nextval('empenhos_diarias_id_seq'),
                codord INTEGER NOT NULL,
                dtautorizacao DATE NOT NULL,
                matricula INTEGER NOT NULL ,
                cargo VARCHAR(60) NOT NULL,
                dtinicial DATE NOT NULL,
                dtfinal DATE NOT NULL,
                origem VARCHAR(60) NOT NULL,
                destino VARCHAR(60) NOT NULL,
                qtddiarias NUMERIC(15,2) DEFAULT 0 ,
                vrldiariauni NUMERIC(15,2) DEFAULT 0 ,
                transporte VARCHAR(60),
                vlrtransport NUMERIC(15,2) DEFAULT 0 ,
                objetivo VARCHAR(500),
                horainicial VARCHAR(5) NOT NULL DEFAULT '00:00',
                horafinal VARCHAR(5) NOT NULL DEFAULT '00:00',
                qtdhospedagens NUMERIC(15,2) DEFAULT 0 ,
                vrlhospedagemuni NUMERIC(15,2) DEFAULT 0 ,
                qtddiariaspernoite NUMERIC(15,2) DEFAULT 0 ,
                vrldiariaspernoiteuni NUMERIC(15,2) DEFAULT 0 ,
                codempenho NUMERIC(15,2) DEFAULT 0 NOT NULL,
                codnota NUMERIC(15,2),
                CONSTRAINT empenhos_diarias_id_pk PRIMARY KEY (id)
);

COMMENT ON TABLE empenhos_diarias IS 'Cadastro de Diarias';
COMMENT ON COLUMN empenhos_diarias.id IS 'ID da Diaria';
COMMENT ON COLUMN empenhos_diarias.codord IS 'Número da Ordem';
COMMENT ON COLUMN empenhos_diarias.dtautorizacao IS 'Data de Autorização';
COMMENT ON COLUMN empenhos_diarias.matricula IS 'Numero Matricula';
COMMENT ON COLUMN empenhos_diarias.cargo IS 'Cargo';
COMMENT ON COLUMN empenhos_diarias.dtinicial IS 'Data Final';
COMMENT ON COLUMN empenhos_diarias.dtfinal IS 'Data Final';
COMMENT ON COLUMN empenhos_diarias.origem IS 'Origem';
COMMENT ON COLUMN empenhos_diarias.destino IS 'Destino';
COMMENT ON COLUMN empenhos_diarias.qtddiarias IS 'Quantidades de Diarias';
COMMENT ON COLUMN empenhos_diarias.vrldiariauni IS 'Valor da Diaria';
COMMENT ON COLUMN empenhos_diarias.transporte IS 'Transporte';
COMMENT ON COLUMN empenhos_diarias.vlrtransport IS 'Valor do Transporte';
COMMENT ON COLUMN empenhos_diarias.objetivo IS 'Objetivo';
COMMENT ON COLUMN empenhos_diarias.horainicial IS 'Hora Inicial';
COMMENT ON COLUMN empenhos_diarias.horafinal IS 'Hora Final';
COMMENT ON COLUMN empenhos_diarias.qtdhospedagens IS 'Quantidade Hospedagem';
COMMENT ON COLUMN empenhos_diarias.vrlhospedagemuni IS 'Valor da Hospedagem';
COMMENT ON COLUMN empenhos_diarias.qtddiariaspernoite IS 'Quantidadde de diarias pernoite';
COMMENT ON COLUMN empenhos_diarias.vrldiariaspernoiteuni IS 'Valor de diarias pernoite';
COMMENT ON COLUMN empenhos_diarias.codempenho IS 'Codigo do Empenho';
COMMENT ON COLUMN empenhos_diarias.codnota IS 'Codigo da Nota';

ALTER SEQUENCE empenhos_diarias_id_seq OWNED BY empenhos_diarias.id;
