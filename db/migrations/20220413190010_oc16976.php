<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16976 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

        BEGIN;
        SELECT fc_startsession();

        ALTER TABLE balancete112022 DROP COLUMN IF EXISTS si178_subelemento;

        ALTER TABLE balancete162022 ALTER COLUMN si183_codfontrecursos DROP NOT NULL;

        DROP TABLE IF EXISTS balancete292022;

        CREATE TABLE balancete292022 (
            si241_sequencial int8 NOT NULL DEFAULT 0,
            si241_tiporegistro int8 NOT NULL DEFAULT 0,
            si241_contacontabil int8 NOT NULL DEFAULT 0,
            si241_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
            si241_atributosf varchar(1) NOT NULL,
            si241_codfontrecursos int8 NOT NULL DEFAULT 0,
            si241_dividaconsolidada int4 NOT NULL DEFAULT 0,
            si241_saldoinicialfontsf float8 NOT NULL DEFAULT 0,
            si241_naturezasaldoinicialfontsf varchar(1) NOT NULL,
            si241_totaldebitosfontsf float8 NOT NULL DEFAULT 0,
            si241_totalcreditosfontsf float8 NOT NULL DEFAULT 0,
            si241_saldofinalfontsf float8 NOT NULL DEFAULT 0,
            si241_naturezasaldofinalfontsf varchar(1) NOT NULL,
            si241_mes int8 NOT NULL DEFAULT 0,
            si241_instit int8 NULL DEFAULT 0,
            si241_reg10 int8 NOT NULL,
            CONSTRAINT balancete292022_sequ_pk PRIMARY KEY (si241_sequencial),
            CONSTRAINT fk_balancete292022_reg10_fk FOREIGN KEY (si241_reg10) REFERENCES balancete102022(si177_sequencial)
        );

        DROP SEQUENCE IF EXISTS balancete292022_si241_sequencial_seq;

        CREATE SEQUENCE balancete292022_si241_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807;

        DROP TABLE IF EXISTS balancete302022;

        CREATE TABLE balancete302022 (
            si242_sequencial int8 NOT NULL DEFAULT 0,
            si242_tiporegistro int8 NOT NULL DEFAULT 0,
            si242_contacontaabil int8 NOT NULL DEFAULT 0,
            si242_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
            si242_codorgao varchar(2) NOT NULL,
            si242_codunidadesub varchar(8) NOT NULL,
            si242_codfuncao varchar(2) NOT NULL,
            si242_codsubfuncao varchar(3) NOT NULL,
            si242_codprograma varchar(4) NULL,
            si242_idacao varchar(4) NOT NULL,
            si242_idsubacao varchar(4) NOT NULL,
            si242_naturezadespesa int8 NOT NULL DEFAULT 0,
            si242_subelemento varchar(2) NOT NULL,
            si242_codfontrecursos int8 NOT NULL DEFAULT 0,
            si242_tipodespesarpps int4 DEFAULT 0,
            si242_saldoinicialcde float8 NOT NULL DEFAULT 0,
            si242_naturezasaldoinicialcde varchar(1) NOT NULL,
            si242_totaldebitoscde float8 NOT NULL DEFAULT 0,
            si242_totalcreditoscde float8 NOT NULL DEFAULT 0,
            si242_saldofinalcde float8 NOT NULL DEFAULT 0,
            si242_naturezasaldofinalcde varchar(1) NOT NULL,
            si242_mes int8 NOT NULL DEFAULT 0,
            si242_instit int8 NULL DEFAULT 0,
            si242_reg10 int8 NOT NULL,
            CONSTRAINT balancete302022_sequ_pk PRIMARY KEY (si242_sequencial),
            CONSTRAINT fk_balancete302022_reg10_fk FOREIGN KEY (si242_reg10) REFERENCES balancete102022(si177_sequencial)
        );

        DROP SEQUENCE IF EXISTS balancete302022_si242_sequencial_seq;

        CREATE SEQUENCE balancete302022_si242_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807;

        DROP TABLE IF EXISTS balancete312022;

        CREATE TABLE balancete312022 (
            si243_sequencial int8 NOT NULL DEFAULT 0,
            si243_tiporegistro int8 NOT NULL DEFAULT 0,
            si243_contacontabil int8 NOT NULL DEFAULT 0,
            si243_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
            si243_naturezareceita int8 NOT NULL DEFAULT 0,
            si243_codfontrecursos int8 NOT NULL DEFAULT 0,
            si243_emendaparlamentar int4 NOT NULL DEFAULT 0,
            si243_nrocontratoop varchar(30),
            si243_dataassinaturacontratoop date,
            si243_saldoinicialcre float8 NOT NULL DEFAULT 0,
            si243_naturezasaldoinicialcre varchar(1) NOT NULL,
            si243_totaldebitoscre float8 NOT NULL DEFAULT 0,
            si243_totalcreditoscre float8 NOT NULL DEFAULT 0,
            si243_saldofinalcre float8 NOT NULL DEFAULT 0,
            si243_naturezasaldofinalcre varchar(1) NOT NULL,
            si243_mes int8 NOT NULL DEFAULT 0,
            si243_instit int8 NULL DEFAULT 0,
            si243_reg10 int8 NOT NULL,
            CONSTRAINT balancete312022_sequ_pk PRIMARY KEY (si243_sequencial),
            CONSTRAINT fk_balancete312022_reg10_fk FOREIGN KEY (si243_reg10) REFERENCES balancete102022(si177_sequencial)
        );

        DROP SEQUENCE IF EXISTS balancete312022_si243_sequencial_seq;

        CREATE SEQUENCE balancete312022_si243_sequencial_seq
            INCREMENT BY 1
            MINVALUE 1
            MAXVALUE 9223372036854775807;


        COMMIT;

SQL;
    $this->execute($sql);
 	}
}
