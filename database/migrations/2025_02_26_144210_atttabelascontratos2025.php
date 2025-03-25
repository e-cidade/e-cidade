<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Atttabelascontratos2025 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
        BEGIN;

            DROP TABLE public.contratos102025 CASCADE;

            CREATE TABLE public.contratos102025 (
                si83_sequencial int8 DEFAULT 0 NOT NULL,
                si83_tiporegistro int8 DEFAULT 0 NOT NULL,
                si83_tipocadastro int8 NULL,
                si83_codcontrato int8 DEFAULT 0 NOT NULL,
                si83_codorgao varchar(2) NOT NULL,
                si83_codunidadesub varchar(8) NOT NULL,
                si83_nrocontrato int8 DEFAULT 0 NOT NULL,
                si83_exerciciocontrato int8 DEFAULT 0 NOT NULL,
                si83_dataassinatura date NOT NULL,
                si83_contdeclicitacao int8 DEFAULT 0 NOT NULL,
                si83_cnpjorgaoentresp varchar(14) NULL,
                si83_codunidadesubresp varchar(8) NULL,
                si83_nroprocesso varchar(12) NULL,
                si83_exercicioprocesso int8 DEFAULT 0 NULL,
                si83_tipoprocesso int8 DEFAULT 0 NULL,
                si83_naturezaobjeto int8 DEFAULT 0 NOT NULL,
                si83_objetocontrato varchar(1000) NOT NULL,
                si83_datainiciovigencia date NOT NULL,
                si83_vigenciaindeterminada int4 NULL,
                si83_datafinalvigencia date NULL,
                si83_vlcontrato float8 DEFAULT 0 NOT NULL,
                si83_formafornecimento varchar(50) NULL,
                si83_formapagamento varchar(100) NULL,
                si83_indcriterioreajuste int4 NULL,
                si83_databasereajuste date NULL,
                si83_periodicidadereajuste varchar(2) NULL,
                si83_tipocriterioreajuste varchar(2) NULL,
                si83_dscreajuste varchar(300) NULL,
                si83_indiceunicoreajuste varchar(2) NULL,
                si83_dscindice varchar(300) NULL,
                si83_unidadedemedidaprazoexec int8 DEFAULT 0 NULL,
                si83_prazoexecucao int8 NULL,
                si83_multarescisoria varchar(100) NULL,
                si83_multainadimplemento varchar(100) NULL,
                si83_garantia int8 DEFAULT 0 NULL,
                si83_cpfsignatariocontratante varchar(11) NOT NULL,
                si83_datapublicacao date NOT NULL,
                si83_veiculodivulgacao varchar(50) NOT NULL,
                si83_mes int8 DEFAULT 0 NOT NULL,
                si83_instit int8 DEFAULT 0 NULL,
                CONSTRAINT contratos102025_sequ_pk PRIMARY KEY (si83_sequencial)
            );


            DROP TABLE public.contratos112025;

            CREATE TABLE public.contratos112025 (
                si84_sequencial int8 DEFAULT 0 NOT NULL,
                si84_tiporegistro int8 DEFAULT 0 NOT NULL,
                si84_codcontrato int8 DEFAULT 0 NOT NULL,
                si84_coditem int8 DEFAULT 0 NOT NULL,
                si84_tipomaterial int8 DEFAULT 0 NULL,
                si84_coditemsinapi varchar(15) NULL,
                si84_coditemsimcro varchar(15) NULL,
                si84_descoutrosmateriais varchar(250) NULL,
                si84_itemplanilha int8 DEFAULT 0 NULL,
                si84_quantidadeitem float8 DEFAULT 0 NOT NULL,
                si84_valorunitarioitem float8 DEFAULT 0 NOT NULL,
                si84_mes int8 DEFAULT 0 NOT NULL,
                si84_reg10 int8 DEFAULT 0 NOT NULL,
                si84_instit int8 DEFAULT 0 NULL,
                si84_nrolote int4 NULL,
                CONSTRAINT contratos112025_sequ_pk PRIMARY KEY (si84_sequencial)
            );

            DROP TABLE public.contratos122025;


            CREATE TABLE public.contratos122025 (
                si85_sequencial int8 DEFAULT 0 NOT NULL,
                si85_tiporegistro int8 DEFAULT 0 NOT NULL,
                si85_codcontrato int8 DEFAULT 0 NOT NULL,
                si85_codorgao varchar(2) NOT NULL,
                si85_codunidadesub varchar(8) NOT NULL,
                si85_codfuncao varchar(2) NOT NULL,
                si85_codsubfuncao varchar(3) NOT NULL,
                si85_codprograma varchar(4) NOT NULL,
                si85_idacao varchar(4) NOT NULL,
                si85_idsubacao varchar(4) NULL,
                si85_naturezadespesa int8 DEFAULT 0 NOT NULL,
                si85_codfontrecursos int8 DEFAULT 0 NOT NULL,
                si85_vlrecurso float8 DEFAULT 0 NOT NULL,
                si85_mes int8 DEFAULT 0 NOT NULL,
                si85_reg10 int8 DEFAULT 0 NOT NULL,
                si85_instit int8 DEFAULT 0 NULL,
                CONSTRAINT contratos122025_sequ_pk PRIMARY KEY (si85_sequencial)
            );
            CREATE INDEX contratos122025_si85_reg10_index ON public.contratos122025 USING btree (si85_reg10);



            DROP TABLE public.contratos132025;

            CREATE TABLE public.contratos132025 (
                si86_sequencial int8 DEFAULT 0 NOT NULL,
                si86_tiporegistro int8 DEFAULT 0 NOT NULL,
                si86_codcontrato int8 DEFAULT 0 NOT NULL,
                si86_tipodocumento int8 DEFAULT 0 NOT NULL,
                si86_nrodocumento varchar(14) NOT NULL,
                si86_tipodocrepresentante int8 NULL,
                si86_nrodocrepresentantelegal varchar(14) NOT NULL,
                si86_mes int8 DEFAULT 0 NOT NULL,
                si86_reg10 int8 DEFAULT 0 NOT NULL,
                si86_instit int8 DEFAULT 0 NULL,
                CONSTRAINT contratos132025_sequ_pk PRIMARY KEY (si86_sequencial)
            );
            CREATE INDEX contratos132025_si86_reg10_index ON public.contratos132025 USING btree (si86_reg10);


            DROP TABLE public.contratos202025;

            CREATE TABLE public.contratos202025 (
                si87_sequencial int8 DEFAULT 0 NOT NULL,
                si87_tiporegistro int8 DEFAULT 0 NOT NULL,
                si87_codaditivo int8 DEFAULT 0 NOT NULL,
                si87_codorgao varchar(2) NOT NULL,
                si87_codunidadesub varchar(8) NOT NULL,
                si87_codunidadesubatual varchar(8) NULL,
                si87_nrocontrato varchar(14) DEFAULT '0' NOT NULL,
                si87_nroseqtermoaditivo varchar(2) NOT NULL,
                si87_dtassinaturatermoaditivo date NOT NULL,
                si87_tipoalteracaovalor int8 DEFAULT 0 NOT NULL,
                si87_tipotermoaditivo varchar(2) NOT NULL,
                si87_dscalteracao varchar(250) NULL,
                si87_novadatatermino date NULL,
                si87_percentualreajuste float8 NULL,
                si87_criterioreajuste int4 NULL,
                si87_descricaoindice varchar(300) NULL,
                si87_indiceunicoreajuste int4 NULL,
                si87_descricaoreajuste varchar(300) NULL,
                si87_valoraditivo float8 DEFAULT 0 NOT NULL,
                si87_datapublicacao date NOT NULL,
                si87_veiculodivulgacao varchar(50) NOT NULL,
                si87_mes int8 DEFAULT 0 NOT NULL,
                si87_instit int8 DEFAULT 0 NULL,
                si87_exerciciocontrato int4 NULL,
                CONSTRAINT contratos202025_sequ_pk PRIMARY KEY (si87_sequencial)
            );

            DROP TABLE public.contratos212025;

            CREATE TABLE public.contratos212025 (
                si88_sequencial int8 DEFAULT 0 NOT NULL,
                si88_tiporegistro int8 DEFAULT 0 NOT NULL,
                si88_codaditivo int8 DEFAULT 0 NOT NULL,
                si88_coditem int8 DEFAULT 0 NOT NULL,
                si88_tipomaterial int8 DEFAULT 0 NULL,
                si88_coditemsinapi varchar(15) NULL,
                si88_coditemsimcro varchar(15) NULL,
                si88_descoutrosmateriais varchar(250) NULL,
                si88_itemplanilha int8 DEFAULT 0 NULL,
                si88_tipoalteracaoitem int8 DEFAULT 0 NOT NULL,
                si88_quantacrescdecresc float8 DEFAULT 0 NOT NULL,
                si88_valorunitarioitem float8 DEFAULT 0 NOT NULL,
                si88_mes int8 DEFAULT 0 NOT NULL,
                si88_reg20 int8 DEFAULT 0 NOT NULL,
                si88_instit int8 DEFAULT 0 NULL,
                si88_nrolote int4 NULL,
                CONSTRAINT contratos212025_sequ_pk PRIMARY KEY (si88_sequencial)
            );



            DROP TABLE public.contratos302025;

            CREATE TABLE public.contratos302025 (
                si89_sequencial int8 DEFAULT 0 NOT NULL,
                si89_tiporegistro int8 DEFAULT 0 NOT NULL,
                si89_codorgao varchar(2) NOT NULL,
                si89_codunidadesub varchar(8) NOT NULL,
                si89_codunidadesubatual varchar(8) NULL,
                si89_nrocontrato varchar(14) DEFAULT '0' NOT NULL,
                si89_tipoapostila varchar(2) NOT NULL,
                si89_nroseqapostila int8 DEFAULT 0 NOT NULL,
                si89_dataapostila date NOT NULL,
                si89_tipoalteracaoapostila int8 DEFAULT 0 NOT NULL,
                si89_dscalteracao varchar(250) NOT NULL,
                si89_percentualreajuste float8 NULL,
                si89_criterioreajuste int4 NULL,
                si89_descricaoindice varchar(300) NULL,
                si89_indiceunicoreajuste int4 NULL,
                si89_dscreajuste varchar(300) NULL,
                si89_valorapostila float8 DEFAULT 0 NOT NULL,
                si89_mes int8 DEFAULT 0 NOT NULL,
                si89_instit int8 DEFAULT 0 NULL,
                si89_exerciciocontrato int4 NULL,
                CONSTRAINT contratos302025_sequ_pk PRIMARY KEY (si89_sequencial)
            );


            DROP TABLE public.contratos402025;

            CREATE TABLE public.contratos402025 (
                si91_sequencial int8 DEFAULT 0 NOT NULL,
                si91_tiporegistro int8 DEFAULT 0 NOT NULL,
                si91_codorgao varchar(2) NOT NULL,
                si91_codunidadesub varchar(8) NULL,
                si91_codunidadesubatual varchar(8) NULL,
                si91_nrocontrato varchar(14) DEFAULT '0' NOT NULL,
                si91_datarescisao date NOT NULL,
                si91_valorcancelamentocontrato float8 DEFAULT 0 NOT NULL,
                si91_mes int8 DEFAULT 0 NOT NULL,
                si91_instit int8 DEFAULT 0 NULL,
                si91_exerciciocontrato int4 NULL,
                CONSTRAINT contratos402025_sequ_pk PRIMARY KEY (si91_sequencial)
            );

        COMMIT;
        ";
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
