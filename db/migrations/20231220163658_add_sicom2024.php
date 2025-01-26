<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AddSicom2024 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

CREATE TABLE public.aberlic102024 (
	si46_sequencial int8 NOT NULL DEFAULT 0,
	si46_tiporegistro int8 NOT NULL DEFAULT 0,
	si46_tipocadastro int8 NOT NULL DEFAULT 0,
	si46_codorgaoresp varchar(2) NOT NULL,
	si46_codunidadesubresp varchar(8) NOT NULL,
	si46_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si46_nroprocessolicitatorio varchar(12) NOT NULL,
	si46_codmodalidadelicitacao int8 NOT NULL DEFAULT 0,
	si46_nroedital int8 NOT NULL DEFAULT 0,
	si46_exercicioedital int8 NOT NULL DEFAULT 0,
	si46_naturezaprocedimento int8 NOT NULL DEFAULT 0,
	si46_dtabertura date NOT NULL,
	si46_dteditalconvite date NOT NULL,
	si46_dtpublicacaoeditaldo date NULL,
	si46_dtpublicacaoeditalveiculo1 date NULL,
	si46_veiculo1publicacao varchar(50) NULL,
	si46_dtpublicacaoeditalveiculo2 date NULL,
	si46_veiculo2publicacao varchar(50) NULL,
	si46_dtrecebimentodoc date NOT NULL,
	si46_tipolicitacao int8 NULL,
	si46_naturezaobjeto int8 NULL,
	si46_objeto varchar(1000) NOT NULL,
	si46_regimeexecucaoobras int8 NULL DEFAULT 0,
	si46_nroconvidado int8 NULL DEFAULT 0,
	si46_clausulaprorrogacao varchar(250) NULL,
	si46_unidademedidaprazoexecucao int8 NOT NULL DEFAULT 0,
	si46_prazoexecucao int8 NOT NULL DEFAULT 0,
	si46_formapagamento varchar(80) NOT NULL,
	si46_criterioaceitabilidade varchar(80) NULL,
	si46_criterioadjudicacao int8 NOT NULL DEFAULT 0,
	si46_processoporlote int8 NOT NULL DEFAULT 0,
	si46_criteriodesempate int8 NOT NULL DEFAULT 0,
	si46_destinacaoexclusiva int8 NOT NULL DEFAULT 0,
	si46_subcontratacao int8 NOT NULL DEFAULT 0,
	si46_limitecontratacao int8 NOT NULL DEFAULT 0,
	si46_mes int8 NOT NULL DEFAULT 0,
	si46_instit int8 NULL DEFAULT 0,
	si46_leidalicitacao int4 NULL,
	si46_dtpulicacaopncp date NULL,
	si46_linkpncp varchar(255) NULL,
	si46_dtpulicacaoedital date NULL,
	si46_linkedital varchar(255) NULL,
	si46_diariooficialdivulgacao int4 NULL,
	si46_mododisputa int4 NULL,
	CONSTRAINT aberlic102024_sequ_pk PRIMARY KEY (si46_sequencial)
);


CREATE TABLE public.aex102024 (
	si130_sequencial int8 NOT NULL DEFAULT 0,
	si130_tiporegistro int8 NOT NULL DEFAULT 0,
	si130_codext int8 NOT NULL DEFAULT 0,
	si130_codfontrecursos int8 NOT NULL DEFAULT 0,
	si130_nroop int8 NOT NULL DEFAULT 0,
	si130_codunidadesub varchar(8) NOT NULL,
	si130_dtpagamento date NOT NULL,
	si130_nroanulacaoop int8 NOT NULL DEFAULT 0,
	si130_dtanulacaoop date NOT NULL,
	si130_vlanulacaoop float8 NOT NULL DEFAULT 0,
	si130_mes int8 NOT NULL DEFAULT 0,
	si130_instit int8 NULL DEFAULT 0,
	CONSTRAINT aex112024_sequ_pk PRIMARY KEY (si130_sequencial)
);


CREATE TABLE public.afast102024 (
	si199_sequencial int4 NOT NULL DEFAULT 0,
	si199_tiporegistro int4 NOT NULL DEFAULT 0,
	si199_codvinculopessoa int4 NOT NULL DEFAULT 0,
	si199_codafastamento int8 NOT NULL DEFAULT 0,
	si199_dtinicioafastamento date NOT NULL,
	si199_dtretornoafastamento date NOT NULL,
	si199_tipoafastamento int4 NOT NULL DEFAULT 0,
	si199_dscoutrosafastamentos varchar(500) NULL,
	si199_mes int4 NOT NULL DEFAULT 0,
	si199_inst int4 NULL DEFAULT 0,
	CONSTRAINT afast102024_sequ_pk PRIMARY KEY (si199_sequencial)
);



CREATE TABLE public.afast202024 (
	si200_sequencial int4 NOT NULL DEFAULT 0,
	si200_tiporegistro int4 NOT NULL DEFAULT 0,
	si200_codvinculopessoa int4 NOT NULL DEFAULT 0,
	si200_codafastamento int8 NOT NULL DEFAULT 0,
	si200_dtterminoafastamento date NOT NULL,
	si200_mes int4 NOT NULL DEFAULT 0,
	si200_inst int4 NULL DEFAULT 0,
	CONSTRAINT afast202024_sequ_pk PRIMARY KEY (si200_sequencial)
);



CREATE TABLE public.afast302024 (
	si201_sequencial int4 NOT NULL DEFAULT 0,
	si201_tiporegistro int4 NOT NULL DEFAULT 0,
	si201_codvinculopessoa int4 NOT NULL DEFAULT 0,
	si201_codafastamento int8 NOT NULL DEFAULT 0,
	si201_dtretornoafastamento date NOT NULL,
	si201_mes int4 NOT NULL DEFAULT 0,
	si201_inst int4 NULL DEFAULT 0,
	CONSTRAINT afast302024_sequ_pk PRIMARY KEY (si201_sequencial)
);



CREATE TABLE public.alq102024 (
	si121_sequencial int8 NOT NULL DEFAULT 0,
	si121_tiporegistro int8 NOT NULL DEFAULT 0,
	si121_codreduzido int8 NOT NULL DEFAULT 0,
	si121_codorgao varchar(2) NOT NULL,
	si121_codunidadesub varchar(8) NOT NULL,
	si121_nroempenho int8 NOT NULL DEFAULT 0,
	si121_dtempenho date NOT NULL,
	si121_dtliquidacao date NOT NULL,
	si121_nroliquidacao int8 NOT NULL DEFAULT 0,
	si121_dtanulacaoliq date NOT NULL,
	si121_nroliquidacaoanl int8 NOT NULL DEFAULT 0,
	si121_tpliquidacao int8 NOT NULL DEFAULT 0,
	si121_justificativaanulacao varchar(500) NOT NULL,
	si121_vlanulado float8 NOT NULL DEFAULT 0,
	si121_mes int8 NOT NULL DEFAULT 0,
	si121_instit int8 NULL DEFAULT 0,
	CONSTRAINT alq102024_sequ_pk PRIMARY KEY (si121_sequencial)
);



CREATE TABLE public.anl102024 (
	si110_sequencial int8 NOT NULL DEFAULT 0,
	si110_tiporegistro int8 NOT NULL DEFAULT 0,
	si110_codorgao varchar(2) NOT NULL,
	si110_codunidadesub varchar(8) NOT NULL,
	si110_nroempenho int8 NOT NULL DEFAULT 0,
	si110_dtempenho date NOT NULL,
	si110_dtanulacao date NOT NULL,
	si110_nroanulacao int8 NOT NULL DEFAULT 0,
	si110_tipoanulacao int8 NOT NULL DEFAULT 0,
	si110_especanulacaoempenho varchar(200) NOT NULL,
	si110_vlanulacao float8 NOT NULL DEFAULT 0,
	si110_mes int8 NOT NULL DEFAULT 0,
	si110_instit int8 NULL DEFAULT 0,
	CONSTRAINT anl102024_sequ_pk PRIMARY KEY (si110_sequencial)
);



CREATE TABLE public.aob102024 (
	si141_sequencial int8 NOT NULL DEFAULT 0,
	si141_tiporegistro int8 NOT NULL DEFAULT 0,
	si141_codreduzido int8 NOT NULL DEFAULT 0,
	si141_codorgao varchar(2) NOT NULL,
	si141_codunidadesub varchar(8) NOT NULL,
	si141_nrolancamento int8 NOT NULL DEFAULT 0,
	si141_dtlancamento date NOT NULL,
	si141_tipolancamento int8 NOT NULL DEFAULT 0,
	si141_nroanulacaolancamento int8 NOT NULL DEFAULT 0,
	si141_dtanulacaolancamento date NOT NULL,
	si141_nroempenho int8 NOT NULL DEFAULT 0,
	si141_dtempenho date NOT NULL,
	si141_nroliquidacao int8 NULL DEFAULT 0,
	si141_dtliquidacao date NULL,
	si141_valoranulacaolancamento float8 NOT NULL DEFAULT 0,
	si141_mes int8 NOT NULL DEFAULT 0,
	si141_instit int8 NULL DEFAULT 0,
	CONSTRAINT aob102024_sequ_pk PRIMARY KEY (si141_sequencial)
);



CREATE TABLE public.aoc102024 (
	si38_sequencial int8 NOT NULL DEFAULT 0,
	si38_tiporegistro int8 NOT NULL DEFAULT 0,
	si38_codorgao varchar(2) NOT NULL,
	si38_nrodecreto varchar(8) NOT NULL DEFAULT 0,
	si38_datadecreto date NOT NULL,
	si38_mes int8 NOT NULL DEFAULT 0,
	si38_instit int8 NULL DEFAULT 0,
	CONSTRAINT aoc102024_sequ_pk PRIMARY KEY (si38_sequencial)
);



CREATE TABLE public.aop102024 (
	si137_sequencial int8 NOT NULL DEFAULT 0,
	si137_tiporegistro int8 NOT NULL DEFAULT 0,
	si137_codreduzido int8 NOT NULL DEFAULT 0,
	si137_codorgao varchar(2) NOT NULL,
	si137_codunidadesub varchar(8) NOT NULL,
	si137_nroop int8 NOT NULL DEFAULT 0,
	si137_dtpagamento date NOT NULL,
	si137_nroanulacaoop int8 NOT NULL DEFAULT 0,
	si137_dtanulacaoop date NOT NULL,
	si137_justificativaanulacao varchar(500) NOT NULL,
	si137_vlanulacaoop float8 NOT NULL DEFAULT 0,
	si137_mes int8 NOT NULL DEFAULT 0,
	si137_instit int8 NULL DEFAULT 0,
	CONSTRAINT aop102024_sequ_pk PRIMARY KEY (si137_sequencial)
);




CREATE TABLE public.arc102024 (
	si28_sequencial int8 NOT NULL DEFAULT 0,
	si28_tiporegistro int8 NOT NULL DEFAULT 0,
	si28_codcorrecao int8 NOT NULL DEFAULT 0,
	si28_codorgao varchar(2) NOT NULL,
	si28_ededucaodereceita int8 NOT NULL DEFAULT 0,
	si28_identificadordeducaorecreduzida int8 NULL DEFAULT 0,
	si28_naturezareceitareduzida int8 NOT NULL DEFAULT 0,
	si28_identificadordeducaorecacrescida int8 NULL DEFAULT 0,
	si28_naturezareceitaacrescida int8 NOT NULL DEFAULT 0,
	si28_vlreduzidoacrescido float8 NOT NULL DEFAULT 0,
	si28_mes int8 NOT NULL DEFAULT 0,
	si28_instit int8 NULL DEFAULT 0,
	CONSTRAINT arc102024_sequ_pk PRIMARY KEY (si28_sequencial)
);



CREATE TABLE public.arc202024 (
	si31_sequencial int8 NOT NULL DEFAULT 0,
	si31_tiporegistro int8 NOT NULL DEFAULT 0,
	si31_codorgao varchar(2) NOT NULL,
	si31_codestorno int8 NOT NULL DEFAULT 0,
	si31_ededucaodereceita int8 NOT NULL DEFAULT 0,
	si31_identificadordeducao int8 NULL,
	si31_naturezareceitaestornada int8 NOT NULL DEFAULT 0,
	si31_vlestornado float8 NOT NULL DEFAULT 0,
	si31_mes int8 NOT NULL DEFAULT 0,
	si31_instit int8 NULL DEFAULT 0,
	CONSTRAINT arc202024_sequ_pk PRIMARY KEY (si31_sequencial)
);




CREATE TABLE public.balancete102024 (
	si177_sequencial int8 NOT NULL DEFAULT 0,
	si177_tiporegistro int8 NOT NULL DEFAULT 0,
	si177_contacontaabil int8 NOT NULL DEFAULT 0,
	si177_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si177_saldoinicial float8 NOT NULL DEFAULT 0,
	si177_naturezasaldoinicial varchar(1) NOT NULL,
	si177_totaldebitos float8 NOT NULL DEFAULT 0,
	si177_totalcreditos float8 NOT NULL DEFAULT 0,
	si177_saldofinal float8 NOT NULL DEFAULT 0,
	si177_naturezasaldofinal varchar(1) NOT NULL,
	si177_mes int8 NOT NULL DEFAULT 0,
	si177_instit int8 NULL DEFAULT 0,
	CONSTRAINT balancete102024_sequ_pk PRIMARY KEY (si177_sequencial)
);



CREATE TABLE public.bfdcasp102024 (
	si206_sequencial int4 NOT NULL DEFAULT 0,
	si206_tiporegistro int4 NOT NULL DEFAULT 0,
	si206_exercicio int4 NOT NULL DEFAULT 0,
	si206_vlrecorcamenrecurord float8 NOT NULL DEFAULT 0,
	si206_vlrecorcamenrecinceduc float8 NOT NULL DEFAULT 0,
	si206_vlrecorcamenrecurvincusaude float8 NOT NULL DEFAULT 0,
	si206_vlrecorcamenrecurvincurpps float8 NOT NULL DEFAULT 0,
	si206_vlrecorcamenrecurvincuassistsoc float8 NOT NULL DEFAULT 0,
	si206_vlrecorcamenoutrasdestrecursos float8 NOT NULL DEFAULT 0,
	si206_vltransfinanexecuorcamentaria float8 NOT NULL DEFAULT 0,
	si206_vltransfinanindepenexecuorc float8 NOT NULL DEFAULT 0,
	si206_vltransfinanreceaportesrpps float8 NOT NULL DEFAULT 0,
	si206_vlincrirspnaoprocessado float8 NOT NULL DEFAULT 0,
	si206_vlincrirspprocessado float8 NOT NULL DEFAULT 0,
	si206_vldeporestituvinculados float8 NOT NULL DEFAULT 0,
	si206_vloutrosrecextraorcamentario float8 NOT NULL DEFAULT 0,
	si206_vlsaldoexeranteriorcaixaequicaixa float8 NOT NULL DEFAULT 0,
	si206_vlsaldoexerantdeporestvinc float8 NOT NULL DEFAULT 0,
	si206_vltotalingresso float8 NULL DEFAULT 0,
	si206_ano int4 NOT NULL DEFAULT 0,
	si206_periodo int4 NOT NULL DEFAULT 0,
	si206_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bfdcasp102024_sequ_pk PRIMARY KEY (si206_sequencial)
);





CREATE TABLE public.bfdcasp202024 (
	si207_sequencial int4 NOT NULL DEFAULT 0,
	si207_tiporegistro int4 NOT NULL DEFAULT 0,
	si207_exercicio int4 NOT NULL DEFAULT 0,
	si207_vldesporcamenrecurordinarios float8 NOT NULL DEFAULT 0,
	si207_vldesporcamenrecurvincueducacao float8 NOT NULL DEFAULT 0,
	si207_vldesporcamenrecurvincusaude float8 NOT NULL DEFAULT 0,
	si207_vldesporcamenrecurvincurpps float8 NOT NULL DEFAULT 0,
	si207_vldesporcamenrecurvincuassistsoc float8 NOT NULL DEFAULT 0,
	si207_vloutrasdesporcamendestrecursos float8 NOT NULL DEFAULT 0,
	si207_vltransfinanconcexecorcamentaria float8 NOT NULL DEFAULT 0,
	si207_vltransfinanconcindepenexecorc float8 NOT NULL DEFAULT 0,
	si207_vltransfinanconcaportesrecurpps float8 NOT NULL DEFAULT 0,
	si207_vlpagrspnaoprocessado float8 NOT NULL DEFAULT 0,
	si207_vlpagrspprocessado float8 NOT NULL DEFAULT 0,
	si207_vldeposrestvinculados float8 NOT NULL DEFAULT 0,
	si207_vloutrospagextraorcamentarios float8 NOT NULL DEFAULT 0,
	si207_vlsaldoexeratualcaixaequicaixa float8 NOT NULL DEFAULT 0,
	si207_vlsaldoexeratualdeporestvinc float8 NOT NULL DEFAULT 0,
	si207_vltotaldispendios float8 NULL DEFAULT 0,
	si207_ano int4 NOT NULL DEFAULT 0,
	si207_periodo int4 NOT NULL DEFAULT 0,
	si207_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bfdcasp202024_sequ_pk PRIMARY KEY (si207_sequencial)
);





CREATE TABLE public.bodcasp102024 (
	si201_sequencial int4 NOT NULL DEFAULT 0,
	si201_tiporegistro int4 NOT NULL DEFAULT 0,
	si201_faserecorcamentaria int4 NOT NULL DEFAULT 0,
	si201_vlrectributaria float8 NOT NULL DEFAULT 0,
	si201_vlreccontribuicoes float8 NOT NULL DEFAULT 0,
	si201_vlrecpatrimonial float8 NOT NULL DEFAULT 0,
	si201_vlrecagropecuaria float8 NOT NULL DEFAULT 0,
	si201_vlrecindustrial float8 NOT NULL DEFAULT 0,
	si201_vlrecservicos float8 NOT NULL DEFAULT 0,
	si201_vltransfcorrentes float8 NOT NULL DEFAULT 0,
	si201_vloutrasreccorrentes float8 NOT NULL DEFAULT 0,
	si201_vloperacoescredito float8 NOT NULL DEFAULT 0,
	si201_vlalienacaobens float8 NOT NULL DEFAULT 0,
	si201_vlamortemprestimo float8 NOT NULL DEFAULT 0,
	si201_vltransfcapital float8 NOT NULL DEFAULT 0,
	si201_vloutrasreccapital float8 NOT NULL DEFAULT 0,
	si201_vlrecarrecadaxeant float8 NOT NULL DEFAULT 0,
	si201_vlopcredrefintermob float8 NOT NULL DEFAULT 0,
	si201_vlopcredrefintcontrat float8 NOT NULL DEFAULT 0,
	si201_vlopcredrefextmob float8 NOT NULL DEFAULT 0,
	si201_vlopcredrefextcontrat float8 NOT NULL DEFAULT 0,
	si201_vldeficit float8 NOT NULL DEFAULT 0,
	si201_vltotalquadroreceita float8 NULL DEFAULT 0,
	si201_ano int4 NOT NULL DEFAULT 0,
	si201_periodo int4 NOT NULL DEFAULT 0,
	si201_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bodcasp102024_sequ_pk PRIMARY KEY (si201_sequencial)
);





CREATE TABLE public.bodcasp202024 (
	si202_sequencial int4 NOT NULL DEFAULT 0,
	si202_tiporegistro int4 NOT NULL DEFAULT 0,
	si202_faserecorcamentaria int4 NOT NULL DEFAULT 0,
	si202_vlsaldoexeantsupfin float8 NOT NULL DEFAULT 0,
	si202_vlsaldoexeantrecredad float8 NOT NULL DEFAULT 0,
	si202_vltotalsaldoexeant float8 NULL DEFAULT 0,
	si202_anousu int4 NOT NULL DEFAULT 0,
	si202_periodo int4 NOT NULL DEFAULT 0,
	si202_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT bodcasp202024_sequ_pk PRIMARY KEY (si202_sequencial)
);





CREATE TABLE public.bodcasp302024 (
	si203_sequencial int4 NOT NULL DEFAULT 0,
	si203_tiporegistro int4 NOT NULL DEFAULT 0,
	si203_fasedespesaorca int4 NOT NULL DEFAULT 0,
	si203_vlpessoalencarsoci float8 NOT NULL DEFAULT 0,
	si203_vljurosencardividas float8 NOT NULL DEFAULT 0,
	si203_vloutrasdespcorren float8 NOT NULL DEFAULT 0,
	si203_vlinvestimentos float8 NOT NULL DEFAULT 0,
	si203_vlinverfinanceira float8 NOT NULL DEFAULT 0,
	si203_vlamortizadivida float8 NOT NULL DEFAULT 0,
	si203_vlreservacontingen float8 NOT NULL DEFAULT 0,
	si203_vlreservarpps float8 NOT NULL DEFAULT 0,
	si203_vlamortizadiviintermob float8 NOT NULL DEFAULT 0,
	si203_vlamortizaoutrasdivinter float8 NOT NULL DEFAULT 0,
	si203_vlamortizadivextmob float8 NOT NULL DEFAULT 0,
	si203_vlamortizaoutrasdivext float8 NOT NULL DEFAULT 0,
	si203_vlsuperavit float8 NOT NULL DEFAULT 0,
	si203_vltotalquadrodespesa float8 NULL DEFAULT 0,
	si203_anousu int4 NOT NULL DEFAULT 0,
	si203_periodo int4 NOT NULL DEFAULT 0,
	si203_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT bodcasp302024_sequ_pk PRIMARY KEY (si203_sequencial)
);





CREATE TABLE public.bodcasp402024 (
	si204_sequencial int4 NOT NULL DEFAULT 0,
	si204_tiporegistro int4 NOT NULL DEFAULT 0,
	si204_faserestospagarnaoproc int4 NOT NULL DEFAULT 0,
	si204_vlrspnaoprocpessoalencarsociais float8 NOT NULL DEFAULT 0,
	si204_vlrspnaoprocjurosencardividas float8 NOT NULL DEFAULT 0,
	si204_vlrspnaoprocoutrasdespcorrentes float8 NOT NULL DEFAULT 0,
	si204_vlrspnaoprocinvestimentos float8 NOT NULL DEFAULT 0,
	si204_vlrspnaoprocinverfinanceira float8 NOT NULL DEFAULT 0,
	si204_vlrspnaoprocamortizadivida float8 NOT NULL DEFAULT 0,
	si204_vltotalexecurspnaoprocessado float8 NULL DEFAULT 0,
	si204_ano int4 NOT NULL DEFAULT 0,
	si204_periodo int4 NOT NULL DEFAULT 0,
	si204_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bodcasp402024_sequ_pk PRIMARY KEY (si204_sequencial)
);





CREATE TABLE public.bodcasp502024 (
	si205_sequencial int4 NOT NULL DEFAULT 0,
	si205_tiporegistro int4 NOT NULL DEFAULT 0,
	si205_faserestospagarprocnaoliqui int4 NOT NULL DEFAULT 0,
	si205_vlrspprocliqpessoalencarsoc float8 NOT NULL DEFAULT 0,
	si205_vlrspprocliqjurosencardiv float8 NOT NULL DEFAULT 0,
	si205_vlrspprocliqoutrasdespcorrentes float8 NOT NULL DEFAULT 0,
	si205_vlrspprocesliqinv float8 NOT NULL DEFAULT 0,
	si205_vlrspprocliqinverfinan float8 NOT NULL DEFAULT 0,
	si205_vlrspprocliqamortizadivida float8 NOT NULL DEFAULT 0,
	si205_vltotalexecrspprocnaoproceli float8 NULL DEFAULT 0,
	si205_ano int4 NOT NULL DEFAULT 0,
	si205_periodo int4 NOT NULL DEFAULT 0,
	si205_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bodcasp502024_sequ_pk PRIMARY KEY (si205_sequencial)
);





CREATE TABLE public.bpdcasp102024 (
	si208_sequencial int4 NOT NULL DEFAULT 0,
	si208_tiporegistro int4 NOT NULL DEFAULT 0,
	si208_vlativocircucaixaequicaixa float8 NOT NULL DEFAULT 0,
	si208_vlativocircucredicurtoprazo float8 NOT NULL DEFAULT 0,
	si208_vlativocircuinvestapliccurtoprazo float8 NOT NULL DEFAULT 0,
	si208_vlativocircuestoques float8 NOT NULL DEFAULT 0,
	si208_vlativocircuvpdantecipada float8 NOT NULL DEFAULT 0,
	si208_vlativonaocircuestoques float8 NOT NULL DEFAULT 0,
	si208_vlativonaocircuvpdantecipada float8 NOT NULL DEFAULT 0,
	si208_vlativonaocircuinvestimentos float8 NOT NULL DEFAULT 0,
	si208_vlativonaocircuimobilizado float8 NOT NULL DEFAULT 0,
	si208_vlativonaocircuintagivel float8 NOT NULL DEFAULT 0,
	si208_vltotalativo float8 NULL DEFAULT 0,
	si208_ano int4 NOT NULL DEFAULT 0,
	si208_periodo int4 NOT NULL DEFAULT 0,
	si208_institu int4 NOT NULL DEFAULT 0,
	si208_vlativocircudemaiscredicurtoprazo float8 NOT NULL DEFAULT 0,
	si208_vlativonaocircumantidovenda float8 NOT NULL DEFAULT 0,
	si208_vlativonaocircurlp float8 NOT NULL DEFAULT 0,
	si208_vlativocircuativobio float8 NOT NULL DEFAULT 0,
	CONSTRAINT bpdcasp102024_sequ_pk PRIMARY KEY (si208_sequencial)
);





CREATE TABLE public.bpdcasp202024 (
	si209_sequencial int4 NOT NULL DEFAULT 0,
	si209_tiporegistro int4 NOT NULL DEFAULT 0,
	si209_exercicio int4 NOT NULL DEFAULT 0,
	si209_vlpassivcircultrabprevicurtoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassivcirculemprefinancurtoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassivocirculafornecedcurtoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassicircuobrigfiscacurtoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassivocirculaobrigacoutrosentes float8 NOT NULL DEFAULT 0,
	si209_vlpassivocirculaprovisoecurtoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassicircudemaiobrigcurtoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassinaocircutrabprevilongoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassnaocircemprfinalongpraz float8 NOT NULL DEFAULT 0,
	si209_vlpassivnaocirculforneclongoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassnaocircobrifisclongpraz float8 NOT NULL DEFAULT 0,
	si209_vlpassivnaocirculprovislongoprazo float8 NOT NULL DEFAULT 0,
	si209_vlpassnaocircdemaobrilongpraz float8 NOT NULL DEFAULT 0,
	si209_vlpassivonaocircularesuldiferido float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidocapitalsocial float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidoadianfuturocapital float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidoreservacapital float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidoajustavaliacao float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidoreservalucros float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidodemaisreservas float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidoresultexercicio float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidresultacumexeranteri float8 NOT NULL DEFAULT 0,
	si209_vlpatriliquidoacoescotas float8 NOT NULL DEFAULT 0,
	si209_vltotalpassivo float8 NULL DEFAULT 0,
	si209_ano int4 NOT NULL DEFAULT 0,
	si209_periodo int4 NOT NULL DEFAULT 0,
	si209_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bpdcasp202024_sequ_pk PRIMARY KEY (si209_sequencial)
);





CREATE TABLE public.bpdcasp302024 (
	si210_sequencial int4 NOT NULL DEFAULT 0,
	si210_tiporegistro int4 NOT NULL DEFAULT 0,
	si210_exercicio int4 NOT NULL DEFAULT 0,
	si210_vlativofinanceiro float8 NOT NULL DEFAULT 0,
	si210_vlativopermanente float8 NOT NULL DEFAULT 0,
	si210_vltotalativofinanceiropermanente float8 NULL DEFAULT 0,
	si210_ano int4 NOT NULL DEFAULT 0,
	si210_periodo int4 NOT NULL DEFAULT 0,
	si210_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bpdcasp302024_sequ_pk PRIMARY KEY (si210_sequencial)
);





CREATE TABLE public.bpdcasp402024 (
	si211_sequencial int4 NOT NULL DEFAULT 0,
	si211_tiporegistro int4 NOT NULL DEFAULT 0,
	si211_exercicio int4 NOT NULL DEFAULT 0,
	si211_vlpassivofinanceiro float8 NOT NULL DEFAULT 0,
	si211_vlpassivopermanente float8 NOT NULL DEFAULT 0,
	si211_vltotalpassivofinanceiropermanente float8 NULL DEFAULT 0,
	si211_ano int4 NOT NULL DEFAULT 0,
	si211_periodo int4 NOT NULL DEFAULT 0,
	si211_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bpdcasp402024_sequ_pk PRIMARY KEY (si211_sequencial)
);





CREATE TABLE public.bpdcasp502024 (
	si212_sequencial int4 NOT NULL DEFAULT 0,
	si212_tiporegistro int4 NOT NULL DEFAULT 0,
	si212_exercicio int4 NOT NULL DEFAULT 0,
	si212_vlsaldopatrimonial float8 NULL DEFAULT 0,
	si212_ano int4 NOT NULL DEFAULT 0,
	si212_periodo int4 NOT NULL DEFAULT 0,
	si212_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bpdcasp502024_sequ_pk PRIMARY KEY (si212_sequencial)
);





CREATE TABLE public.bpdcasp602024 (
	si213_sequencial int4 NOT NULL DEFAULT 0,
	si213_tiporegistro int4 NOT NULL DEFAULT 0,
	si213_exercicio int4 NOT NULL DEFAULT 0,
	si213_vlatospotenativosgarancontrarecebi float8 NOT NULL DEFAULT 0,
	si213_vlatospotenativodirconveoutroinstr float8 NOT NULL DEFAULT 0,
	si213_vlatospotenativosdireitoscontratua float8 NOT NULL DEFAULT 0,
	si213_vlatospotenativosoutrosatos float8 NOT NULL DEFAULT 0,
	si213_vlatospotenpassivgarancontraconced float8 NOT NULL DEFAULT 0,
	si213_vlatospotepassobriconvoutrinst float8 NOT NULL DEFAULT 0,
	si213_vlatospotenpassivoobrigacocontratu float8 NOT NULL DEFAULT 0,
	si213_vlatospotenpassivooutrosatos float8 NULL DEFAULT 0,
	si213_ano int4 NOT NULL DEFAULT 0,
	si213_periodo int4 NOT NULL DEFAULT 0,
	si213_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bpdcasp602024_sequ_pk PRIMARY KEY (si213_sequencial)
);





CREATE TABLE public.bpdcasp702024 (
	si214_sequencial int4 NOT NULL DEFAULT 0,
	si214_tiporegistro int4 NOT NULL DEFAULT 0,
	si214_exercicio int4 NOT NULL DEFAULT 0,
	si214_vltotalsupdef float8 NULL DEFAULT 0,
	si214_ano int4 NOT NULL DEFAULT 0,
	si214_periodo int4 NOT NULL DEFAULT 0,
	si214_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bpdcasp702024_sequ_pk PRIMARY KEY (si214_sequencial)
);





CREATE TABLE public.bpdcasp712024 (
	si215_sequencial int4 NOT NULL DEFAULT 0,
	si215_tiporegistro int4 NOT NULL DEFAULT 0,
	si215_exercicio int4 NOT NULL DEFAULT 0,
	si215_codfontrecursos int4 NOT NULL DEFAULT 0,
	si215_vlsaldofonte float8 NULL DEFAULT 0,
	si215_ano int4 NOT NULL DEFAULT 0,
	si215_periodo int4 NOT NULL DEFAULT 0,
	si215_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT bpdcasp712024_sequ_pk PRIMARY KEY (si215_sequencial)
);





CREATE TABLE public.cadobras102024 (
	si198_sequencial int8 NULL,
	si198_tiporegistro int8 NULL,
	si198_codorgaoresp varchar(3) NULL,
	si198_codobra int8 NULL,
	si198_tiporesponsavel int8 NULL,
	si198_nrodocumento varchar(14) NULL,
	si198_tiporegistroconselho int8 NULL,
	si198_dscoutroconselho varchar(40) NULL,
	si198_nroregistroconseprof varchar(10) NULL,
	si198_numrt int8 NULL DEFAULT 0,
	si198_dtinicioatividadeseng date NULL,
	si198_tipovinculo int8 NULL,
	si198_mes int8 NULL,
	si198_instit int4 NULL
);





CREATE TABLE public.cadobras202024 (
	si199_sequencial int8 NULL,
	si199_tiporegistro int8 NULL,
	si199_codorgaoresp varchar(3) NULL,
	si199_codobra int8 NULL,
	si199_situacaodaobra int8 NULL,
	si199_dtsituacao date NULL,
	si199_veiculopublicacao varchar(50) NULL,
	si199_dtpublicacao date NULL,
	si199_descsituacao varchar(500) NULL,
	si199_mes int8 NULL,
	si199_instit int4 NULL
);





CREATE TABLE public.cadobras212024 (
	si200_sequencial int8 NULL,
	si200_tiporegistro int8 NULL,
	si200_codorgaoresp varchar(3) NULL,
	si200_codobra int8 NULL,
	si200_dtparalisacao date NULL,
	si200_motivoparalisacap int8 NULL,
	si200_descoutrosparalisacao varchar(150) NULL,
	si200_dtretomada date NULL,
	si200_mes int8 NULL,
	si200_instit int4 NULL
);





CREATE TABLE public.cadobras302024 (
	si201_sequencial int8 NULL,
	si201_tiporegistro int8 NULL,
	si201_codorgaoresp varchar(3) NULL,
	si201_codobra int8 NULL,
	si201_tipomedicao int8 NULL,
	si201_descoutrostiposmed varchar(500) NULL,
	si201_nummedicao varchar(20) NULL,
	si201_descmedicao varchar(500) NULL,
	si201_dtiniciomedicao date NULL,
	si201_dtfimmedicao date NULL,
	si201_dtmedicao date NULL,
	si201_valormedicao float8 NULL,
	si201_mes int8 NULL,
	si201_pdf varchar(25) NULL,
	si201_instit int4 NULL
);





CREATE TABLE public.caixa102024 (
	si103_sequencial int8 NOT NULL DEFAULT 0,
	si103_tiporegistro int8 NOT NULL DEFAULT 0,
	si103_codorgao varchar(2) NOT NULL,
	si103_vlsaldoinicial float8 NOT NULL DEFAULT 0,
	si103_vlsaldofinal float8 NOT NULL DEFAULT 0,
	si103_mes int8 NOT NULL DEFAULT 0,
	si103_instit int8 NULL DEFAULT 0,
	CONSTRAINT caixa102024_sequ_pk PRIMARY KEY (si103_sequencial)
);





CREATE TABLE public.conge102024 (
	si182_sequencial int8 NOT NULL DEFAULT 0,
	si182_tiporegistro int8 NOT NULL DEFAULT 0,
	si182_codconvenioconge int8 NOT NULL DEFAULT 0,
	si182_codorgao varchar(2) NOT NULL,
	si182_codunidadesub varchar(8) NOT NULL,
	si182_nroconvenioconge varchar(30) NOT NULL,
	si182_dscinstrumento varchar(50) NOT NULL,
	si182_dataassinaturaconge date NOT NULL,
	si182_datapublicconge date NOT NULL,
	si182_nrocpfrespconge varchar(11) NOT NULL,
	si182_dsccargorespconge varchar(50) NOT NULL,
	si182_objetoconvenioconge varchar(500) NOT NULL,
	si182_datainiciovigenciaconge date NOT NULL,
	si182_datafinalvigenciaconge date NOT NULL,
	si182_formarepasse int8 NOT NULL,
	si182_tipodocumentoincentivador int8 NULL,
	si182_nrodocumentoincentivador varchar(14) NULL,
	si182_quantparcelas int8 NULL,
	si182_vltotalconvenioconge float8 NOT NULL DEFAULT 0,
	si182_vlcontrapartidaconge float8 NOT NULL DEFAULT 0,
	si182_tipodocumentobeneficiario int8 NOT NULL DEFAULT 0,
	si182_nrodocumentobeneficiario varchar(14) NOT NULL DEFAULT 0,
	si182_mes int8 NOT NULL DEFAULT 0,
	si182_instit int8 NULL DEFAULT 0,
	CONSTRAINT conge102024_sequ_pk PRIMARY KEY (si182_sequencial)
);





CREATE TABLE public.conge202024 (
	si183_sequencial int8 NOT NULL DEFAULT 0,
	si183_tiporegistro int8 NOT NULL DEFAULT 0,
	si183_codorgao varchar(2) NOT NULL,
	si183_codunidadesub varchar(8) NOT NULL,
	si183_nroconvenioconge varchar(30) NOT NULL,
	si183_dataassinaturaconvoriginalconge date NOT NULL,
	si183_nroseqtermoaditivoconge int8 NOT NULL DEFAULT 0,
	si183_dscalteracaoconge varchar(500) NOT NULL DEFAULT 0,
	si183_dataassinaturatermoaditivoconge date NOT NULL,
	si183_datafinalvigenciaconge date NOT NULL,
	si183_valoratualizadoconvenioconge float8 NOT NULL,
	si183_valoratualizadocontrapartidaconge float8 NOT NULL,
	si183_mes int8 NOT NULL DEFAULT 0,
	si183_instit int8 NULL DEFAULT 0,
	CONSTRAINT conge202024_sequ_pk PRIMARY KEY (si183_sequencial)
);





CREATE TABLE public.conge302024 (
	si184_sequencial int8 NOT NULL DEFAULT 0,
	si184_tiporegistro int8 NOT NULL DEFAULT 0,
	si184_codorgao varchar(2) NOT NULL,
	si184_codunidadesub varchar(8) NOT NULL,
	si184_nroconvenioconge varchar(30) NOT NULL,
	si184_dataassinaturaconvoriginalconge date NOT NULL,
	si184_numeroparcela int8 NOT NULL DEFAULT 0,
	si184_datarepasseconge int8 NOT NULL DEFAULT 0,
	si184_vlrepassadoconge float8 NOT NULL,
	si184_banco varchar(3) NOT NULL DEFAULT 0,
	si184_agencia varchar(6) NOT NULL DEFAULT 0,
	si184_digitoverificadoragencia varchar(2) NOT NULL DEFAULT 0,
	si184_contabancaria varchar(12) NOT NULL DEFAULT 0,
	si184_digitoverificadorcontabancaria varchar(2) NOT NULL DEFAULT 0,
	si184_tipodocumentotitularconta int8 NOT NULL DEFAULT 0,
	si184_nrodocumentotitularconta varchar(14) NOT NULL DEFAULT 0,
	si184_prazoprestacontas date NOT NULL,
	si184_mes int8 NOT NULL DEFAULT 0,
	si184_instit int8 NULL DEFAULT 0,
	CONSTRAINT conge302024_sequ_pk PRIMARY KEY (si184_sequencial)
);





CREATE TABLE public.conge402024 (
	si237_sequencial int8 NOT NULL DEFAULT 0,
	si237_tiporegistro int8 NOT NULL DEFAULT 0,
	si237_codorgao varchar(2) NOT NULL,
	si237_codunidadesub varchar(8) NOT NULL,
	si237_nroconvenioconge varchar(30) NOT NULL,
	si237_dataassinaturaconvoriginalconge date NOT NULL,
	si237_datarepasseconge int8 NOT NULL DEFAULT 0,
	si237_prestacaocontasparcela int8 NOT NULL,
	si237_dataprestacontasparcela date NULL,
	si237_prestacaocontas int8 NULL,
	si237_datacienfatos date NULL,
	si237_prorrogprazo int8 NOT NULL DEFAULT 0,
	si237_dataprorrogprazo date NULL,
	si237_nrocpfrespprestconge varchar(11) NULL,
	si237_dsccargorespprestconge varchar(50) NULL,
	si237_mes int8 NOT NULL DEFAULT 0,
	si237_instit int8 NULL DEFAULT 0
);





CREATE TABLE public.conge502024 (
	si238_sequencial int8 NOT NULL DEFAULT 0,
	si238_tiporegistro int8 NOT NULL DEFAULT 0,
	si238_codorgao varchar(2) NOT NULL,
	si238_codunidadesub varchar(8) NOT NULL,
	si238_nroconvenioconge varchar(30) NOT NULL,
	si238_dataassinaturaconvoriginalconge date NOT NULL,
	si238_dscmedidaadministrativa varchar(500) NOT NULL,
	si238_datainiciomedida date NOT NULL,
	si238_datafinalmedida date NOT NULL,
	si238_adocaomedidasadmin int8 NOT NULL DEFAULT 0,
	si238_nrocpfrespmedidaconge varchar(11) NOT NULL,
	si238_dsccargorespmedidaconge varchar(50) NOT NULL,
	si238_mes int8 NOT NULL DEFAULT 0,
	si238_instit int8 NULL DEFAULT 0
);





CREATE TABLE public.consid102024 (
	si158_sequencial int8 NOT NULL DEFAULT 0,
	si158_tiporegistro int8 NOT NULL DEFAULT 0,
	si158_codarquivo varchar(20) NOT NULL,
	si158_exercicioreferenciaconsid int8 NULL DEFAULT 0,
	si158_mesreferenciaconsid varchar(2) NULL,
	si158_consideracoes varchar(4000) NOT NULL,
	si158_mes int8 NULL,
	si158_instit int8 NULL,
	CONSTRAINT consid102024_sequ_pk PRIMARY KEY (si158_sequencial)
);





CREATE TABLE public.consor102024 (
	si16_sequencial int8 NOT NULL DEFAULT 0,
	si16_tiporegistro int8 NOT NULL DEFAULT 0,
	si16_codorgao varchar(2) NOT NULL,
	si16_cnpjconsorcio varchar(14) NOT NULL,
	si16_areaatuacao varchar(2) NOT NULL,
	si16_descareaatuacao varchar(150) NULL,
	si16_mes int8 NOT NULL DEFAULT 0,
	si16_instit int8 NULL DEFAULT 0,
	CONSTRAINT consor102024_sequ_pk PRIMARY KEY (si16_sequencial)
);





CREATE TABLE public.consor202024 (
	si17_sequencial int8 NOT NULL DEFAULT 0,
	si17_tiporegistro int8 NOT NULL DEFAULT 0,
	si17_codorgao varchar(2) NOT NULL,
	si17_cnpjconsorcio varchar(14) NOT NULL,
	si17_codfontrecursos int8 NOT NULL DEFAULT 0,
	si17_vltransfrateio float8 NOT NULL DEFAULT 0,
	si17_prestcontas int8 NOT NULL DEFAULT 0,
	si17_mes int8 NOT NULL DEFAULT 0,
	si17_instit int8 NULL DEFAULT 0,
	si17_codacompanhamento text NULL,
	CONSTRAINT consor202024_sequ_pk PRIMARY KEY (si17_sequencial)
);





CREATE TABLE public.consor302024 (
	si18_sequencial int8 NOT NULL DEFAULT 0,
	si18_tiporegistro int8 NOT NULL DEFAULT 0,
	si18_cnpjconsorcio varchar(14) NOT NULL,
	si18_mesreferencia varchar(2) NOT NULL,
	si18_codfuncao varchar(2) NOT NULL,
	si18_codsubfuncao varchar(3) NOT NULL,
	si18_naturezadespesa int8 NOT NULL DEFAULT 0,
	si18_subelemento varchar(2) NOT NULL,
	si18_codfontrecursos int8 NOT NULL DEFAULT 0,
	si18_vlempenhadofonte float8 NOT NULL DEFAULT 0,
	si18_vlanulacaoempenhofonte float8 NOT NULL DEFAULT 0,
	si18_vlliquidadofonte float8 NOT NULL DEFAULT 0,
	si18_vlanulacaoliquidacaofonte float8 NOT NULL DEFAULT 0,
	si18_vlpagofonte float8 NOT NULL DEFAULT 0,
	si18_vlanulacaopagamentofonte float8 NOT NULL DEFAULT 0,
	si18_mes int8 NOT NULL DEFAULT 0,
	si18_instit int8 NULL DEFAULT 0,
	si18_codacompanhamento text NULL,
	CONSTRAINT consor302024_sequ_pk PRIMARY KEY (si18_sequencial)
);





CREATE TABLE public.consor402024 (
	si19_sequencial int8 NOT NULL DEFAULT 0,
	si19_tiporegistro int8 NOT NULL DEFAULT 0,
	si19_cnpjconsorcio varchar(14) NOT NULL,
	si19_codfontrecursos int8 NOT NULL DEFAULT 0,
	si19_vldispcaixa float8 NOT NULL DEFAULT 0,
	si19_mes int8 NOT NULL DEFAULT 0,
	si19_instit int8 NULL DEFAULT 0,
	CONSTRAINT consor402024_sequ_pk PRIMARY KEY (si19_sequencial)
);





CREATE TABLE public.consor502024 (
	si20_sequencial int8 NOT NULL DEFAULT 0,
	si20_tiporegistro int8 NOT NULL DEFAULT 0,
	si20_codorgao varchar(2) NOT NULL,
	si20_cnpjconsorcio varchar(14) NOT NULL,
	si20_tipoencerramento int8 NOT NULL DEFAULT 0,
	si20_dtencerramento date NOT NULL,
	si20_mes int8 NOT NULL DEFAULT 0,
	si20_instit int8 NULL DEFAULT 0,
	CONSTRAINT consor502024_sequ_pk PRIMARY KEY (si20_sequencial)
);





CREATE TABLE public.contratos102024 (
	si83_sequencial int8 NOT NULL DEFAULT 0,
	si83_tiporegistro int8 NOT NULL DEFAULT 0,
	si83_tipocadastro int8 NULL,
	si83_codcontrato int8 NOT NULL DEFAULT 0,
	si83_codorgao varchar(2) NOT NULL,
	si83_codunidadesub varchar(8) NOT NULL,
	si83_nrocontrato int8 NOT NULL DEFAULT 0,
	si83_exerciciocontrato int8 NOT NULL DEFAULT 0,
	si83_dataassinatura date NOT NULL,
	si83_contdeclicitacao int8 NOT NULL DEFAULT 0,
	si83_codorgaoresp varchar(2) NULL,
	si83_codunidadesubresp varchar(8) NULL,
	si83_nroprocesso varchar(12) NULL,
	si83_exercicioprocesso int8 NULL DEFAULT 0,
	si83_tipoprocesso int8 NULL DEFAULT 0,
	si83_naturezaobjeto int8 NOT NULL DEFAULT 0,
	si83_objetocontrato varchar(1000) NOT NULL,
	si83_tipoinstrumento int8 NOT NULL DEFAULT 0,
	si83_datainiciovigencia date NOT NULL,
	si83_datafinalvigencia date NOT NULL,
	si83_vlcontrato float8 NOT NULL DEFAULT 0,
	si83_formafornecimento varchar(50) NULL,
	si83_formapagamento varchar(100) NULL,
	si83_unidadedemedidaprazoexec int8 NULL DEFAULT 0,
	si83_prazoexecucao int8 NULL,
	si83_multarescisoria varchar(100) NULL,
	si83_multainadimplemento varchar(100) NULL,
	si83_garantia int8 NULL DEFAULT 0,
	si83_cpfsignatariocontratante varchar(11) NOT NULL,
	si83_datapublicacao date NOT NULL,
	si83_veiculodivulgacao varchar(50) NOT NULL,
	si83_mes int8 NOT NULL DEFAULT 0,
	si83_instit int8 NULL DEFAULT 0,
	si83_indcriterioreajuste int4 NULL,
	si83_tipocriterioreajuste varchar(2) NULL,
	si83_databasereajuste date NULL,
	si83_indiceunicoreajuste varchar(2) NULL,
	si83_periodicidadereajuste varchar(2) NULL,
	si83_dscreajuste varchar(300) NULL,
	si83_dscindice varchar(300) NULL,
	CONSTRAINT contratos102024_sequ_pk PRIMARY KEY (si83_sequencial)
);





CREATE TABLE public.contratos122024 (
	si85_sequencial int8 NOT NULL DEFAULT 0,
	si85_tiporegistro int8 NOT NULL DEFAULT 0,
	si85_codcontrato int8 NOT NULL DEFAULT 0,
	si85_codorgao varchar(2) NOT NULL,
	si85_codunidadesub varchar(8) NOT NULL,
	si85_codfuncao varchar(2) NOT NULL,
	si85_codsubfuncao varchar(3) NOT NULL,
	si85_codprograma varchar(4) NOT NULL,
	si85_idacao varchar(4) NOT NULL,
	si85_idsubacao varchar(4) NULL,
	si85_naturezadespesa int8 NOT NULL DEFAULT 0,
	si85_codfontrecursos int8 NOT NULL DEFAULT 0,
	si85_vlrecurso float8 NOT NULL DEFAULT 0,
	si85_mes int8 NOT NULL DEFAULT 0,
	si85_reg10 int8 NOT NULL DEFAULT 0,
	si85_instit int8 NULL DEFAULT 0,
	CONSTRAINT contratos122024_sequ_pk PRIMARY KEY (si85_sequencial)
);
CREATE INDEX contratos122024_si85_reg10_index ON public.contratos122024 USING btree (si85_reg10);





CREATE TABLE public.contratos132024 (
	si86_sequencial int8 NOT NULL DEFAULT 0,
	si86_tiporegistro int8 NOT NULL DEFAULT 0,
	si86_codcontrato int8 NOT NULL DEFAULT 0,
	si86_tipodocumento int8 NOT NULL DEFAULT 0,
	si86_nrodocumento varchar(14) NOT NULL,
	si86_tipodocrepresentante int8 NULL,
	si86_nrodocrepresentantelegal varchar(14) NOT NULL,
	si86_mes int8 NOT NULL DEFAULT 0,
	si86_reg10 int8 NOT NULL DEFAULT 0,
	si86_instit int8 NULL DEFAULT 0,
	CONSTRAINT contratos132024_sequ_pk PRIMARY KEY (si86_sequencial)
);
CREATE INDEX contratos132024_si86_reg10_index ON public.contratos132024 USING btree (si86_reg10);





CREATE TABLE public.contratos202024 (
	si87_sequencial int8 NOT NULL DEFAULT 0,
	si87_tiporegistro int8 NOT NULL DEFAULT 0,
	si87_codaditivo int8 NOT NULL DEFAULT 0,
	si87_codorgao varchar(2) NOT NULL,
	si87_codunidadesub varchar(8) NOT NULL,
	si87_nrocontrato varchar(14) NOT NULL DEFAULT 0,
	si87_dtassinaturacontoriginal date NULL,
	si87_nroseqtermoaditivo varchar(2) NOT NULL,
	si87_dtassinaturatermoaditivo date NOT NULL,
	si87_tipoalteracaovalor int8 NOT NULL DEFAULT 0,
	si87_tipotermoaditivo varchar(2) NOT NULL,
	si87_dscalteracao varchar(250) NULL,
	si87_novadatatermino date NULL,
	si87_valoraditivo float8 NOT NULL DEFAULT 0,
	si87_datapublicacao date NOT NULL,
	si87_veiculodivulgacao varchar(50) NOT NULL,
	si87_mes int8 NOT NULL DEFAULT 0,
	si87_instit int8 NULL DEFAULT 0,
	si87_percentualreajuste float8 NULL,
	si87_indiceunicoreajuste int4 NULL,
	si87_dscreajuste varchar(300) NULL,
	CONSTRAINT contratos202024_sequ_pk PRIMARY KEY (si87_sequencial)
);





CREATE TABLE public.contratos302024 (
	si89_sequencial int8 NOT NULL DEFAULT 0,
	si89_tiporegistro int8 NOT NULL DEFAULT 0,
	si89_codorgao varchar(2) NOT NULL,
	si89_codunidadesub varchar(8) NOT NULL,
	si89_nrocontrato varchar(14) NOT NULL DEFAULT 0,
	si89_dtassinaturacontoriginal date NOT NULL,
	si89_tipoapostila varchar(2) NOT NULL,
	si89_nroseqapostila int8 NOT NULL DEFAULT 0,
	si89_dataapostila date NOT NULL,
	si89_tipoalteracaoapostila int8 NOT NULL DEFAULT 0,
	si89_dscalteracao varchar(250) NOT NULL,
	si89_valorapostila float8 NOT NULL DEFAULT 0,
	si89_mes int8 NOT NULL DEFAULT 0,
	si89_instit int8 NULL DEFAULT 0,
	si89_percentualreajuste float8 NULL,
	si89_indiceunicoreajuste int4 NULL,
	si89_dscreajuste varchar(300) NULL,
	CONSTRAINT contratos302024_sequ_pk PRIMARY KEY (si89_sequencial)
);





CREATE TABLE public.contratos402024 (
	si91_sequencial int8 NOT NULL DEFAULT 0,
	si91_tiporegistro int8 NOT NULL DEFAULT 0,
	si91_codorgao varchar(2) NOT NULL,
	si91_codunidadesub varchar(8) NULL,
	si91_nrocontrato varchar(14) NOT NULL DEFAULT 0,
	si91_dtassinaturacontoriginal date NOT NULL,
	si91_datarescisao date NOT NULL,
	si91_valorcancelamentocontrato float8 NOT NULL DEFAULT 0,
	si91_mes int8 NOT NULL DEFAULT 0,
	si91_instit int8 NULL DEFAULT 0,
	CONSTRAINT contratos402024_sequ_pk PRIMARY KEY (si91_sequencial)
);





CREATE TABLE public.conv102024 (
	si92_sequencial int8 NOT NULL DEFAULT 0,
	si92_tiporegistro int8 NOT NULL DEFAULT 0,
	si92_codconvenio int8 NOT NULL DEFAULT 0,
	si92_codorgao varchar(2) NOT NULL,
	si92_nroconvenio varchar(30) NOT NULL,
	si92_dataassinatura date NOT NULL,
	si92_objetoconvenio varchar(500) NOT NULL,
	si92_datainiciovigencia date NOT NULL,
	si92_datafinalvigencia date NOT NULL,
	si92_codfontrecursos int8 NOT NULL DEFAULT 0,
	si92_vlconvenio float8 NOT NULL DEFAULT 0,
	si92_vlcontrapartida float8 NOT NULL DEFAULT 0,
	si92_mes int8 NOT NULL DEFAULT 0,
	si92_instit int8 NULL DEFAULT 0,
	CONSTRAINT conv102024_sequ_pk PRIMARY KEY (si92_sequencial)
);





CREATE TABLE public.conv202024 (
	si94_sequencial int8 NOT NULL DEFAULT 0,
	si94_tiporegistro int8 NOT NULL DEFAULT 0,
	si94_codorgao varchar(2) NOT NULL,
	si94_nroconvenio varchar(30) NOT NULL,
	si94_dtassinaturaconvoriginal date NOT NULL,
	si94_nroseqtermoaditivo varchar(2) NOT NULL,
	si94_codconvaditivo varchar(20) NOT NULL,
	si94_dscalteracao varchar(500) NOT NULL,
	si94_dtassinaturatermoaditivo date NOT NULL,
	si94_datafinalvigencia date NOT NULL,
	si94_valoratualizadoconvenio float8 NOT NULL DEFAULT 0,
	si94_valoratualizadocontrapartida float8 NOT NULL DEFAULT 0,
	si94_mes int8 NOT NULL DEFAULT 0,
	si94_instit int8 NULL DEFAULT 0
);





CREATE TABLE public.conv212024 (
	si232_sequencial int8 NOT NULL DEFAULT 0,
	si232_tiporegistro int8 NOT NULL DEFAULT 0,
	si232_codconvaditivo varchar(20) NOT NULL,
	si232_tipotermoaditivo varchar(2) NOT NULL,
	si232_dsctipotermoaditivo varchar(250) NULL,
	si232_mes int8 NOT NULL DEFAULT 0,
	si232_instint int8 NULL DEFAULT 0
);





CREATE TABLE public.conv302024 (
	si203_sequencial int8 NOT NULL DEFAULT 0,
	si203_tiporegistro int8 NOT NULL DEFAULT 0,
	si203_codreceita int8 NOT NULL DEFAULT 0,
	si203_codorgao varchar(2) NOT NULL,
	si203_naturezareceita int8 NOT NULL DEFAULT 0,
	si203_codfontrecursos int8 NOT NULL DEFAULT 0,
	si203_vlprevisao float8 NOT NULL DEFAULT 0,
	si203_mes int8 NOT NULL DEFAULT 0,
	si203_instit int8 NULL DEFAULT 0
);





CREATE TABLE public.conv312024 (
	si204_sequencial int8 NOT NULL DEFAULT 0,
	si204_tiporegistro int8 NOT NULL DEFAULT 0,
	si204_codreceita int8 NOT NULL DEFAULT 0,
	si204_prevorcamentoassin int8 NOT NULL DEFAULT 0,
	si204_nroconvenio varchar(30) NULL,
	si204_dataassinatura date NULL,
	si204_vlprevisaoconvenio float8 NOT NULL DEFAULT 0,
	si204_mes int8 NOT NULL DEFAULT 0,
	si204_instit int8 NULL DEFAULT 0
);





CREATE TABLE public.cronem102024 (
	si170_sequencial int8 NOT NULL DEFAULT 0,
	si170_tiporegistro int8 NOT NULL DEFAULT 0,
	si170_codorgao varchar(2) NOT NULL DEFAULT 0,
	si170_codunidadesub varchar(8) NOT NULL DEFAULT 0,
	si170_grupodespesa int8 NOT NULL DEFAULT 0,
	si170_vldotmensal float8 NOT NULL DEFAULT 0,
	si170_instit int8 NULL DEFAULT 0,
	si170_mes int8 NULL,
	CONSTRAINT cronem102024_sequ_pk PRIMARY KEY (si170_sequencial)
);





CREATE TABLE public.ctb102024 (
	si95_sequencial int8 NOT NULL DEFAULT 0,
	si95_tiporegistro int8 NOT NULL DEFAULT 0,
	si95_codctb int8 NOT NULL DEFAULT 0,
	si95_codorgao varchar(2) NOT NULL,
	si95_banco varchar(3) NOT NULL,
	si95_agencia varchar(6) NOT NULL,
	si95_digitoverificadoragencia varchar(2) NULL,
	si95_contabancaria int8 NOT NULL DEFAULT 0,
	si95_digitoverificadorcontabancaria varchar(2) NOT NULL,
	si95_tipoconta varchar(2) NOT NULL,
	si95_nroseqaplicacao int8 NULL DEFAULT 0,
	si95_desccontabancaria varchar(50) NOT NULL,
	si95_contaconvenio int8 NOT NULL DEFAULT 0,
	si95_nroconvenio varchar(30) NULL,
	si95_dataassinaturaconvenio date NULL,
	si95_mes int8 NOT NULL DEFAULT 0,
	si95_instit int8 NULL DEFAULT 0,
	CONSTRAINT ctb102024_sequ_pk PRIMARY KEY (si95_sequencial)
);





CREATE TABLE public.ctb202024 (
	si96_sequencial int8 NOT NULL DEFAULT 0,
	si96_tiporegistro int8 NOT NULL DEFAULT 0,
	si96_codorgao varchar(2) NOT NULL,
	si96_codctb int8 NOT NULL DEFAULT 0,
	si96_codfontrecursos int8 NOT NULL DEFAULT 0,
	si96_vlsaldoinicialfonte float8 NOT NULL DEFAULT 0,
	si96_vlsaldofinalfonte float8 NOT NULL DEFAULT 0,
	si96_mes int8 NOT NULL DEFAULT 0,
	si96_instit int8 NULL DEFAULT 0,
	si96_saldocec int8 NULL DEFAULT 0,
	CONSTRAINT ctb202024_sequ_pk PRIMARY KEY (si96_sequencial)
);





CREATE TABLE public.ctb302024 (
	si99_sequencial int8 NOT NULL DEFAULT 0,
	si99_tiporegistro int8 NOT NULL DEFAULT 0,
	si99_codorgao varchar(2) NOT NULL,
	si99_codagentearrecadador int8 NOT NULL DEFAULT 0,
	si99_cnpjagentearrecadador varchar(14) NOT NULL,
	si99_vlsaldoinicial float8 NOT NULL DEFAULT 0,
	si99_vlsaldofinal float8 NOT NULL DEFAULT 0,
	si99_mes int8 NOT NULL DEFAULT 0,
	si99_instit int8 NULL DEFAULT 0,
	CONSTRAINT ctb302024_sequ_pk PRIMARY KEY (si99_sequencial)
);





CREATE TABLE public.ctb402024 (
	si101_sequencial int8 NOT NULL DEFAULT 0,
	si101_tiporegistro int8 NOT NULL DEFAULT 0,
	si101_codorgao varchar(2) NOT NULL,
	si101_codctb int8 NOT NULL DEFAULT 0,
	si101_desccontabancaria varchar(50) NOT NULL,
	si101_nroconvenio varchar(30) NULL,
	si101_dataassinaturaconvenio date NULL,
	si101_mes int8 NOT NULL DEFAULT 0,
	si101_instit int8 NULL DEFAULT 0,
	CONSTRAINT ctb402024_sequ_pk PRIMARY KEY (si101_sequencial)
);





CREATE TABLE public.ctb502024 (
	si102_sequencial int8 NOT NULL DEFAULT 0,
	si102_tiporegistro int8 NOT NULL DEFAULT 0,
	si102_codorgao varchar(2) NOT NULL,
	si102_codctb int8 NOT NULL DEFAULT 0,
	si102_situacaoconta varchar(1) NOT NULL,
	si102_datasituacao date NOT NULL,
	si102_mes int8 NOT NULL DEFAULT 0,
	si102_instit int8 NULL DEFAULT 0,
	CONSTRAINT ctb502024_sequ_pk PRIMARY KEY (si102_sequencial)
);





CREATE TABLE public.cute102024 (
	si199_sequencial int8 NOT NULL DEFAULT 0,
	si199_tiporegistro int8 NOT NULL DEFAULT 0,
	si199_tipoconta varchar(2) NOT NULL,
	si199_codctb int8 NOT NULL DEFAULT 0,
	si199_codorgao varchar(2) NOT NULL,
	si199_banco int8 NOT NULL DEFAULT 0,
	si199_agencia varchar(6) NOT NULL,
	si199_digitoverificadoragencia varchar(2) NULL,
	si199_contabancaria int8 NOT NULL DEFAULT 0,
	si199_digitoverificadorcontabancaria varchar(2) NOT NULL,
	si199_desccontabancaria varchar(50) NOT NULL,
	si199_mes int8 NOT NULL DEFAULT 0,
	si199_instit int8 NULL DEFAULT 0,
	CONSTRAINT cute102024_sequ_pk PRIMARY KEY (si199_sequencial)
);





CREATE TABLE public.cute202024 (
	si200_sequencial int8 NOT NULL DEFAULT 0,
	si200_tiporegistro int8 NOT NULL DEFAULT 0,
	si200_codorgao varchar(2) NOT NULL,
	si200_codctb int8 NOT NULL DEFAULT 0,
	si200_codfontrecursos int8 NOT NULL DEFAULT 0,
	si200_vlsaldoinicialfonte float8 NOT NULL DEFAULT 0,
	si200_vlsaldofinalfonte float8 NOT NULL DEFAULT 0,
	si200_mes int8 NOT NULL DEFAULT 0,
	si200_instit int8 NULL DEFAULT 0,
	CONSTRAINT cute202024_sequ_pk PRIMARY KEY (si200_sequencial)
);





CREATE TABLE public.cute302024 (
	si202_sequencial int8 NOT NULL DEFAULT 0,
	si202_tiporegistro int8 NOT NULL DEFAULT 0,
	si202_codorgao varchar(2) NOT NULL,
	si202_codctb int8 NOT NULL DEFAULT 0,
	si202_situacaoconta varchar(1) NOT NULL,
	si202_datasituacao date NOT NULL,
	si202_mes int8 NOT NULL DEFAULT 0,
	si202_instit int8 NULL DEFAULT 0,
	CONSTRAINT cute302024_sequ_pk PRIMARY KEY (si202_sequencial)
);





CREATE TABLE public.cvc102024 (
	si146_sequencial int8 NOT NULL DEFAULT 0,
	si146_tiporegistro int8 NOT NULL DEFAULT 0,
	si146_codorgao varchar(2) NOT NULL,
	si146_codunidadesub varchar(8) NOT NULL,
	si146_codveiculo varchar(10) NOT NULL,
	si146_tpveiculo varchar(2) NOT NULL,
	si146_subtipoveiculo varchar(2) NOT NULL,
	si146_descveiculo varchar(100) NOT NULL,
	si146_marca varchar(50) NOT NULL,
	si146_modelo varchar(50) NOT NULL,
	si146_ano int8 NOT NULL DEFAULT 0,
	si146_placa varchar(8) NULL,
	si146_chassi varchar(30) NULL,
	si146_numerorenavam int8 NULL DEFAULT 0,
	si146_nroserie varchar(20) NULL,
	si146_situacao varchar(2) NOT NULL,
	si146_tipodocumento int8 NULL DEFAULT 0,
	si146_nrodocumento varchar(14) NULL,
	si146_tpdeslocamento varchar(2) NOT NULL,
	si146_mes int8 NOT NULL DEFAULT 0,
	si146_instit int8 NULL DEFAULT 0,
	CONSTRAINT cvc102024_sequ_pk PRIMARY KEY (si146_sequencial)
);





CREATE TABLE public.cvc202024 (
	si147_sequencial int8 NOT NULL DEFAULT 0,
	si147_tiporegistro int8 NOT NULL DEFAULT 0,
	si147_codorgao varchar(2) NOT NULL,
	si147_codunidadesub varchar(8) NOT NULL,
	si147_codveiculo varchar(10) NOT NULL,
	si147_origemgasto int8 NOT NULL DEFAULT 0,
	si147_codunidadesubempenho varchar(8) NULL,
	si147_nroempenho int8 NULL DEFAULT 0,
	si147_dtempenho date NULL,
	si147_marcacaoinicial int8 NOT NULL DEFAULT 0,
	si147_marcacaofinal int8 NOT NULL DEFAULT 0,
	si147_tipogasto varchar(2) NOT NULL,
	si147_qtdeutilizada float8 NOT NULL DEFAULT 0,
	si147_vlgasto float8 NOT NULL DEFAULT 0,
	si147_dscpecasservicos varchar(50) NULL,
	si147_atestadocontrole varchar(1) NOT NULL,
	si147_mes int8 NOT NULL DEFAULT 0,
	si147_instit int8 NULL DEFAULT 0,
	CONSTRAINT cvc202024_sequ_pk PRIMARY KEY (si147_sequencial)
);





CREATE TABLE public.cvc302024 (
	si148_sequencial int8 NOT NULL DEFAULT 0,
	si148_tiporegistro int8 NOT NULL DEFAULT 0,
	si148_codorgao varchar(2) NOT NULL,
	si148_codunidadesub varchar(8) NOT NULL,
	si148_codveiculo varchar(10) NOT NULL,
	si148_nomeestabelecimento varchar(250) NOT NULL,
	si148_localidade varchar(250) NOT NULL,
	si148_qtdediasrodados int8 NOT NULL DEFAULT 0,
	si148_distanciaestabelecimento float8 NOT NULL DEFAULT 0,
	si148_numeropassageiros int8 NOT NULL DEFAULT 0,
	si148_turnos varchar(2) NOT NULL,
	si148_mes int8 NOT NULL DEFAULT 0,
	si148_instit int8 NULL DEFAULT 0,
	CONSTRAINT cvc302024_sequ_pk PRIMARY KEY (si148_sequencial)
);





CREATE TABLE public.cvc402024 (
	si149_sequencial int8 NOT NULL DEFAULT 0,
	si149_tiporegistro int8 NOT NULL DEFAULT 0,
	si149_codorgao varchar(2) NOT NULL,
	si149_codunidadesub varchar(8) NOT NULL,
	si149_codveiculo varchar(10) NOT NULL,
	si149_tipobaixa int8 NOT NULL DEFAULT 0,
	si149_descbaixa varchar(50) NULL,
	si149_dtbaixa date NOT NULL,
	si149_mes int8 NOT NULL DEFAULT 0,
	si149_instit int8 NULL DEFAULT 0,
	CONSTRAINT cvc402024_sequ_pk PRIMARY KEY (si149_sequencial)
);





CREATE TABLE public.dclrf102024 (
	si157_sequencial int8 NOT NULL DEFAULT 0,
	si157_tiporegistro int8 NOT NULL DEFAULT 0,
	si157_codorgao varchar(2) NOT NULL,
	si157_vlsaldoatualconcgarantiainterna float8 NOT NULL DEFAULT 0,
	si157_vlsaldoatualconcgarantia float8 NOT NULL DEFAULT 0,
	si157_vlsaldoatualcontragarantiainterna float8 NOT NULL DEFAULT 0,
	si157_vlsaldoatualcontragarantiaexterna float8 NOT NULL DEFAULT 0,
	si157_medidascorretivas varchar(4000) NULL,
	si157_recalieninvpermanente float8 NOT NULL DEFAULT 0,
	si157_vldotinicialincentcontrib float8 NOT NULL DEFAULT 0,
	si157_vldotatualizadaincentcontrib float8 NOT NULL DEFAULT 0,
	si157_vlempenhadoicentcontrib float8 NOT NULL DEFAULT 0,
	si157_vldotinicialincentinstfinanc float8 NOT NULL DEFAULT 0,
	si157_vldotatualizadaincentinstfinanc float8 NOT NULL DEFAULT 0,
	si157_vlempenhadoincentinstfinanc float8 NOT NULL DEFAULT 0,
	si157_vlliqincentcontrib float8 NOT NULL DEFAULT 0,
	si157_vlliqincentinstfinanc float8 NOT NULL DEFAULT 0,
	si157_vlirpnpincentcontrib float8 NOT NULL DEFAULT 0,
	si157_vlirpnpincentinstfinanc float8 NOT NULL DEFAULT 0,
	si157_vlapropiacaodepositosjudiciais float8 NOT NULL DEFAULT 0,
	si157_vlajustesrelativosrpps float8 NOT NULL DEFAULT 0,
	si157_vloutrosajustes float8 NOT NULL DEFAULT 0,
	si157_metarrecada int8 NOT NULL DEFAULT 0,
	si157_mes int8 NOT NULL DEFAULT 0,
	si157_instit int8 NULL DEFAULT 0
);





CREATE TABLE public.dclrf112024 (
	si205_sequencial int8 NOT NULL DEFAULT 0,
	si205_tiporegistro int8 NOT NULL DEFAULT 0,
	si205_medidasadotadas int8 NOT NULL DEFAULT 0,
	si205_dscmedidasadotadas varchar(4000) NULL,
	si205_reg10 int8 NOT NULL DEFAULT 0,
	si205_mes int8 NOT NULL DEFAULT 0,
	si205_instit int8 NOT NULL DEFAULT 0
);





CREATE TABLE public.dclrf202024 (
	si191_sequencial int8 NOT NULL DEFAULT 0,
	si191_tiporegistro int8 NOT NULL DEFAULT 0,
	si191_contopcredito int8 NOT NULL DEFAULT 0,
	si191_dsccontopcredito varchar(1000) NULL DEFAULT 0,
	si191_realizopcredito int8 NOT NULL DEFAULT 0,
	si191_tiporealizopcreditocapta int8 NULL DEFAULT 0,
	si191_tiporealizopcreditoreceb int8 NULL DEFAULT 0,
	si191_tiporealizopcreditoassundir int8 NULL DEFAULT 0,
	si191_tiporealizopcreditoassunobg int8 NULL DEFAULT 0,
	si191_reg10 int8 NOT NULL DEFAULT 0,
	si191_mes int8 NOT NULL DEFAULT 0,
	si191_instit int8 NOT NULL DEFAULT 0,
	si191_dscnumeroinst varchar(3) NULL
);





CREATE TABLE public.dclrf302024 (
	si192_sequencial int8 NOT NULL,
	si192_tiporegistro int4 NOT NULL,
	si192_publiclrf int4 NOT NULL,
	si192_dtpublicacaorelatoriolrf date NULL,
	si192_localpublicacao varchar(1000) NULL,
	si192_tpbimestre int4 NULL,
	si192_exerciciotpbimestre int4 NULL,
	si192_reg10 int8 NOT NULL DEFAULT 0,
	si192_mes int8 NOT NULL DEFAULT 0,
	si192_instit int8 NOT NULL DEFAULT 0
);





CREATE TABLE public.dclrf402024 (
	si193_sequencial int8 NOT NULL,
	si193_tiporegistro int4 NOT NULL,
	si193_publicrgf int4 NOT NULL,
	si193_dtpublicacaorgf date NULL,
	si193_localpublicacaorgf varchar(1000) NULL,
	si193_tpperiodo int4 NULL,
	si193_exerciciotpperiodo int4 NULL,
	si193_reg10 int8 NOT NULL DEFAULT 0,
	si193_mes int8 NOT NULL DEFAULT 0,
	si193_instit int8 NOT NULL DEFAULT 0
);





CREATE TABLE public.ddc102024 (
	si153_sequencial int8 NOT NULL DEFAULT 0,
	si153_tiporegistro int8 NOT NULL DEFAULT 0,
	si153_codorgao varchar(2) NOT NULL,
	si153_nrocontratodivida varchar(30) NOT NULL,
	si153_dtassinatura date NOT NULL,
	si153_nroleiautorizacao varchar(6) NULL,
	si153_dtleiautorizacao date NULL,
	si153_objetocontratodivida varchar(1000) NOT NULL,
	si153_especificacaocontratodivida varchar(500) NOT NULL,
	si153_mes int8 NOT NULL DEFAULT 0,
	si153_instit int8 NULL DEFAULT 0,
	CONSTRAINT ddc202024_sequ_pk PRIMARY KEY (si153_sequencial)
);





CREATE TABLE public.ddc202024 (
	si154_sequencial int8 NOT NULL DEFAULT 0,
	si154_tiporegistro int8 NOT NULL DEFAULT 0,
	si154_codorgao varchar(2) NOT NULL,
	si154_nrocontratodivida varchar(30) NOT NULL DEFAULT '0'::character varying,
	si154_dtassinatura date NOT NULL,
	si154_tipolancamento varchar(2) NOT NULL,
	si154_subtipo varchar(1) NULL,
	si154_tipodocumentocredor int8 NOT NULL DEFAULT 0,
	si154_nrodocumentocredor varchar(14) NOT NULL,
	si154_justificativacancelamento varchar(500) NULL,
	si154_vlsaldoanterior float8 NOT NULL DEFAULT 0,
	si154_vlcontratacao float8 NOT NULL DEFAULT 0,
	si154_vlamortizacao float8 NOT NULL DEFAULT 0,
	si154_vlcancelamento float8 NOT NULL DEFAULT 0,
	si154_vlencampacao float8 NOT NULL DEFAULT 0,
	si154_vlatualizacao float8 NOT NULL DEFAULT 0,
	si154_vlsaldoatual float8 NOT NULL DEFAULT 0,
	si154_mes int8 NOT NULL DEFAULT 0,
	si154_instit int8 NULL DEFAULT 0,
	CONSTRAINT ddc302024_sequ_pk PRIMARY KEY (si154_sequencial)
);





CREATE TABLE public.ddc302024 (
	si178_sequencial int8 NOT NULL DEFAULT 0,
	si178_tiporegistro int8 NOT NULL DEFAULT 0,
	si178_codorgao varchar(2) NOT NULL,
	si178_passivoatuarial int8 NOT NULL DEFAULT 0,
	si178_vlsaldoanterior float8 NOT NULL DEFAULT 0,
	si178_vlsaldoatual float8 NULL DEFAULT 0,
	si178_mes int8 NOT NULL DEFAULT 0,
	si178_instit int8 NULL DEFAULT 0,
	CONSTRAINT ddc402024_sequ_pk PRIMARY KEY (si178_sequencial)
);





CREATE TABLE public.dfcdcasp1002024 (
	si228_sequencial int4 NOT NULL DEFAULT 0,
	si228_tiporegistro int4 NOT NULL DEFAULT 0,
	si228_vlgeracaoliquidaequivalentecaixa float8 NULL DEFAULT 0,
	si228_anousu int4 NOT NULL DEFAULT 0,
	si228_periodo int4 NOT NULL DEFAULT 0,
	si228_mes int4 NOT NULL DEFAULT 0,
	si228_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp1002024_sequ_pk PRIMARY KEY (si228_sequencial)
);





CREATE TABLE public.dfcdcasp102024 (
	si219_sequencial int4 NOT NULL DEFAULT 0,
	si219_tiporegistro int4 NOT NULL DEFAULT 0,
	si219_vlreceitaderivadaoriginaria float8 NOT NULL DEFAULT 0,
	si219_vltranscorrenterecebida float8 NULL DEFAULT 0,
	si219_vloutrosingressosoperacionais float8 NULL DEFAULT 0,
	si219_vltotalingressosativoperacionais float8 NULL DEFAULT 0,
	si219_anousu int4 NOT NULL DEFAULT 0,
	si219_periodo int4 NOT NULL DEFAULT 0,
	si219_instit int4 NOT NULL DEFAULT 0,
	si219_vlreceitatributaria float8 NOT NULL DEFAULT 0,
	si219_vlreceitacontribuicao float8 NOT NULL DEFAULT 0,
	si219_vlreceitapatrimonial float8 NOT NULL DEFAULT 0,
	si219_vlreceitaagropecuaria float8 NOT NULL DEFAULT 0,
	si219_vlreceitaindustrial float8 NOT NULL DEFAULT 0,
	si219_vlreceitaservicos float8 NOT NULL DEFAULT 0,
	si219_vlremuneracaodisponibilidade float8 NOT NULL DEFAULT 0,
	si219_vloutrasreceitas float8 NOT NULL DEFAULT 0,
	si219_vltransferenciarecebidas float8 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp102024_sequ_pk PRIMARY KEY (si219_sequencial)
);





CREATE TABLE public.dfcdcasp1102024 (
	si229_sequencial int4 NOT NULL DEFAULT 0,
	si229_tiporegistro int4 NOT NULL DEFAULT 0,
	si229_vlcaixaequivalentecaixainicial float8 NOT NULL DEFAULT 0,
	si229_vlcaixaequivalentecaixafinal float8 NULL DEFAULT 0,
	si229_anousu int4 NOT NULL DEFAULT 0,
	si229_periodo int4 NOT NULL DEFAULT 0,
	si229_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp1102024_sequ_pk PRIMARY KEY (si229_sequencial)
);





CREATE TABLE public.dfcdcasp202024 (
	si220_sequencial int4 NOT NULL DEFAULT 0,
	si220_tiporegistro int4 NOT NULL DEFAULT 0,
	si220_vldesembolsopessoaldespesas float8 NOT NULL DEFAULT 0,
	si220_vldesembolsojurosencargdivida float8 NOT NULL DEFAULT 0,
	si220_vldesembolsotransfconcedidas float8 NOT NULL DEFAULT 0,
	si220_vloutrosdesembolsos float8 NOT NULL DEFAULT 0,
	si220_vltotaldesembolsosativoperacionais float8 NULL DEFAULT 0,
	si220_anousu int4 NOT NULL DEFAULT 0,
	si220_periodo int4 NOT NULL DEFAULT 0,
	si220_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp202024_sequ_pk PRIMARY KEY (si220_sequencial)
);





CREATE TABLE public.dfcdcasp302024 (
	si221_sequencial int4 NOT NULL DEFAULT 0,
	si221_tiporegistro int4 NOT NULL DEFAULT 0,
	si221_vlfluxocaixaliquidooperacional float8 NULL DEFAULT 0,
	si221_anousu int4 NOT NULL DEFAULT 0,
	si221_periodo int4 NOT NULL DEFAULT 0,
	si221_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp302024_sequ_pk PRIMARY KEY (si221_sequencial)
);





CREATE TABLE public.dfcdcasp402024 (
	si222_sequencial int4 NOT NULL DEFAULT 0,
	si222_tiporegistro int4 NOT NULL DEFAULT 0,
	si222_vlalienacaobens float8 NOT NULL DEFAULT 0,
	si222_vlamortizacaoemprestimoconcedido float8 NOT NULL DEFAULT 0,
	si222_vloutrosingressos float8 NOT NULL DEFAULT 0,
	si222_vltotalingressosatividainvestiment float8 NULL DEFAULT 0,
	si222_anousu int4 NOT NULL DEFAULT 0,
	si222_periodo int4 NOT NULL DEFAULT 0,
	si222_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp402024_sequ_pk PRIMARY KEY (si222_sequencial)
);





CREATE TABLE public.dfcdcasp502024 (
	si223_sequencial int4 NOT NULL DEFAULT 0,
	si223_tiporegistro int4 NOT NULL DEFAULT 0,
	si223_vlaquisicaoativonaocirculante float8 NOT NULL DEFAULT 0,
	si223_vlconcessaoempresfinanciamento float8 NOT NULL DEFAULT 0,
	si223_vloutrosdesembolsos float8 NOT NULL DEFAULT 0,
	si223_vltotaldesembolsoatividainvestimen float8 NULL DEFAULT 0,
	si223_anousu int4 NOT NULL DEFAULT 0,
	si223_periodo int4 NOT NULL DEFAULT 0,
	si223_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp502024_sequ_pk PRIMARY KEY (si223_sequencial)
);





CREATE TABLE public.dfcdcasp602024 (
	si224_sequencial int4 NOT NULL DEFAULT 0,
	si224_tiporegistro int4 NOT NULL DEFAULT 0,
	si224_vlfluxocaixaliquidoinvestimento float8 NULL DEFAULT 0,
	si224_anousu int4 NOT NULL DEFAULT 0,
	si224_periodo int4 NOT NULL DEFAULT 0,
	si224_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp602024_sequ_pk PRIMARY KEY (si224_sequencial)
);





CREATE TABLE public.dfcdcasp702024 (
	si225_sequencial int4 NOT NULL DEFAULT 0,
	si225_tiporegistro int4 NOT NULL DEFAULT 0,
	si225_vloperacoescredito float8 NOT NULL DEFAULT 0,
	si225_vlintegralizacaodependentes float8 NOT NULL DEFAULT 0,
	si225_vltranscapitalrecebida float8 NOT NULL DEFAULT 0,
	si225_vloutrosingressosfinanciamento float8 NOT NULL DEFAULT 0,
	si225_vltotalingressoatividafinanciament float8 NULL DEFAULT 0,
	si225_anousu int4 NOT NULL DEFAULT 0,
	si225_periodo int4 NOT NULL DEFAULT 0,
	si225_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp702024_sequ_pk PRIMARY KEY (si225_sequencial)
);





CREATE TABLE public.dfcdcasp802024 (
	si226_sequencial int4 NOT NULL DEFAULT 0,
	si226_tiporegistro int4 NOT NULL DEFAULT 0,
	si226_vlamortizacaorefinanciamento float8 NOT NULL DEFAULT 0,
	si226_vloutrosdesembolsosfinanciamento float8 NOT NULL DEFAULT 0,
	si226_vltotaldesembolsoatividafinanciame float8 NULL DEFAULT 0,
	si226_anousu int4 NOT NULL DEFAULT 0,
	si226_periodo int4 NOT NULL DEFAULT 0,
	si226_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp802024_sequ_pk PRIMARY KEY (si226_sequencial)
);





CREATE TABLE public.dfcdcasp902024 (
	si227_sequencial int4 NOT NULL DEFAULT 0,
	si227_tiporegistro int4 NOT NULL DEFAULT 0,
	si227_vlfluxocaixafinanciamento float8 NULL DEFAULT 0,
	si227_anousu int4 NOT NULL DEFAULT 0,
	si227_periodo int4 NOT NULL DEFAULT 0,
	si227_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT dfcdcasp902024_sequ_pk PRIMARY KEY (si227_sequencial)
);





CREATE TABLE public.dipr102024 (
	si230_sequencial int8 NOT NULL,
	si230_tiporegistro int8 NOT NULL DEFAULT 0,
	si230_tipocadastro int8 NOT NULL DEFAULT 0,
	si230_segregacaomassa int8 NOT NULL DEFAULT 0,
	si230_benefcustesouro int8 NOT NULL DEFAULT 0,
	si230_atonormativo int8 NOT NULL DEFAULT 0,
	si230_mes int8 NOT NULL DEFAULT 0,
	si230_instit int8 NOT NULL DEFAULT 0,
	si230_nroatonormasegremassa int8 NULL,
	si230_dtatonormasegremassa date NULL,
	si230_planodefatuarial int8 NULL,
	si230_atonormplanodefat int8 NULL,
	si230_dtatoplanodefat date NULL,
	si230_dtatonormativo date NULL,
	CONSTRAINT dipr102024_pkey PRIMARY KEY (si230_sequencial)
);





CREATE TABLE public.dipr202024 (
	si231_sequencial int8 NOT NULL,
	si231_tiporegistro int8 NOT NULL DEFAULT 0,
	si231_codorgao varchar(2) NOT NULL,
	si231_tipobasecalculo int8 NOT NULL DEFAULT 0,
	si231_mescompetencia int8 NOT NULL DEFAULT 0,
	si231_exerciciocompetencia int8 NOT NULL DEFAULT 0,
	si231_tipofundo int8 NOT NULL DEFAULT 0,
	si231_remuneracaobrutafolhapag float8 NOT NULL DEFAULT 0,
	si231_tipobasecalculocontrprevidencia int8 NOT NULL DEFAULT 0,
	si231_tipobasecalculocontrseg int8 NOT NULL DEFAULT 0,
	si231_valorbasecalculocontr float8 NOT NULL DEFAULT 0,
	si231_tipocontribuicao int8 NOT NULL DEFAULT 0,
	si231_aliquota float8 NOT NULL DEFAULT 0,
	si231_valorcontribdevida float8 NOT NULL DEFAULT 0,
	si231_mes int8 NOT NULL DEFAULT 0,
	si231_instit int8 NOT NULL DEFAULT 0,
	CONSTRAINT dipr202024_pkey PRIMARY KEY (si231_sequencial)
);





CREATE TABLE public.dipr302024 (
	si232_sequencial int8 NOT NULL,
	si232_tiporegistro int8 NOT NULL DEFAULT 0,
	si232_codorgao varchar(2) NOT NULL,
	si232_mescompetencia int8 NOT NULL DEFAULT 0,
	si232_exerciciocompetencia int8 NOT NULL DEFAULT 0,
	si232_tipofundo int8 NOT NULL DEFAULT 0,
	si232_tiporepasse int8 NOT NULL DEFAULT 0,
	si232_tipocontripatronal int8 NOT NULL DEFAULT 0,
	si232_tipocontrisegurado int8 NOT NULL DEFAULT 0,
	si232_tipocontribuicao int8 NOT NULL DEFAULT 0,
	si232_datarepasse date NOT NULL,
	si232_datavencirepasse date NOT NULL,
	si232_valororiginal float8 NOT NULL DEFAULT 0,
	si232_valororiginalrepassado float8 NOT NULL DEFAULT 0,
	si232_mes int8 NOT NULL DEFAULT 0,
	si232_instit int8 NOT NULL DEFAULT 0,
	si232_valorjuros numeric NULL,
	si232_valormulta numeric NULL,
	si232_valoratualizacaomonetaria numeric NULL,
	si232_valortotaldeducoes numeric NULL,
	CONSTRAINT dipr302024_pkey PRIMARY KEY (si232_sequencial)
);





CREATE TABLE public.dipr402024 (
	si233_sequencial int8 NOT NULL,
	si233_tiporegistro int8 NOT NULL DEFAULT 0,
	si233_codorgao varchar(2) NOT NULL,
	si233_mescompetencia int8 NOT NULL DEFAULT 0,
	si233_exerciciocompetencia int8 NOT NULL DEFAULT 0,
	si233_tipofundo int8 NOT NULL DEFAULT 0,
	si233_tiporepasse int8 NOT NULL DEFAULT 0,
	si233_tipocontripatronal int8 NOT NULL DEFAULT 0,
	si233_tipocontrisegurado int8 NOT NULL DEFAULT 0,
	si233_tipocontribuicao int8 NOT NULL DEFAULT 0,
	si233_tipodeducao int8 NOT NULL DEFAULT 0,
	si233_dsctiposdeducoes text NOT NULL,
	si233_valordeducao float8 NOT NULL DEFAULT 0,
	si233_mes int8 NOT NULL DEFAULT 0,
	si233_instit int8 NOT NULL DEFAULT 0,
	si233_datarepasse date NULL,
	CONSTRAINT dipr402024_pkey PRIMARY KEY (si233_sequencial)
);





CREATE TABLE public.dipr502024 (
	si234_sequencial int8 NOT NULL,
	si234_tiporegistro int8 NOT NULL DEFAULT 0,
	si234_codorgao varchar(2) NOT NULL,
	si234_mescompetencia int8 NOT NULL DEFAULT 0,
	si234_exerciciocompetencia int8 NOT NULL DEFAULT 0,
	si234_tipofundo int8 NOT NULL DEFAULT 0,
	si234_tipoaportetransf int8 NOT NULL DEFAULT 0,
	si234_dscoutrosaportestransf text NOT NULL,
	si234_valoraportetransf float8 NOT NULL DEFAULT 0,
	si234_mes int8 NOT NULL DEFAULT 0,
	si234_instit int8 NOT NULL DEFAULT 0,
	si234_datarepasse date NULL,
	CONSTRAINT dipr502024_pkey PRIMARY KEY (si234_sequencial)
);





CREATE TABLE public.dispensa102024 (
	si74_sequencial int8 NOT NULL DEFAULT 0,
	si74_tiporegistro int8 NOT NULL,
	si74_codorgaoresp varchar(2) NOT NULL,
	si74_codunidadesubresp varchar(8) NOT NULL,
	si74_exercicioprocesso int8 NOT NULL,
	si74_nroprocesso varchar(12) NOT NULL,
	si74_tipoprocesso int8 NOT NULL,
	si74_dtabertura date NOT NULL,
	si74_naturezaobjeto int8 NOT NULL,
	si74_objeto varchar(1000) NOT NULL,
	si74_justificativa varchar(250) NOT NULL,
	si74_razao varchar(250) NOT NULL,
	si74_dtpublicacaotermoratificacao date NOT NULL,
	si74_veiculopublicacao varchar(50) NOT NULL,
	si74_processoporlote int8 NOT NULL,
	si74_mes int8 NULL,
	si74_instit int8 NULL,
	si74_tipocadastro int8 NULL,
	si74_leidalicitacao int4 NULL,
	CONSTRAINT dispensa102024_sequ_pk PRIMARY KEY (si74_sequencial)
);





CREATE TABLE public.dispensa182024 (
	si82_sequencial int8 NOT NULL DEFAULT 0,
	si82_tiporegistro int8 NOT NULL DEFAULT 0,
	si82_codorgaoresp varchar(2) NOT NULL,
	si82_codunidadesubresp varchar(8) NOT NULL,
	si82_exercicioprocesso int8 NOT NULL DEFAULT 0,
	si82_nroprocesso varchar(12) NOT NULL,
	si82_tipoprocesso int8 NOT NULL DEFAULT 0,
	si82_tipodocumento int8 NOT NULL DEFAULT 0,
	si82_nrodocumento varchar(14) NOT NULL,
	si82_datacredenciamento date NOT NULL,
	si82_nrolote int8 NULL DEFAULT 0,
	si82_coditem int8 NOT NULL DEFAULT 0,
	si82_nroinscricaoestadual varchar(30) NULL,
	si82_ufinscricaoestadual varchar(2) NULL,
	si82_nrocertidaoregularidadeinss varchar(30) NULL,
	si82_dataemissaocertidaoregularidadeinss date NULL,
	si82_dtvalidadecertidaoregularidadeinss date NULL,
	si82_nrocertidaoregularidadefgts varchar(30) NULL,
	si82_dtemissaocertidaoregularidadefgts date NULL,
	si82_dtvalidadecertidaoregularidadefgts date NULL,
	si82_nrocndt varchar(30) NULL,
	si82_dtemissaocndt date NULL,
	si82_dtvalidadecndt date NULL,
	si82_mes int8 NOT NULL DEFAULT 0,
	si82_reg10 int8 NOT NULL DEFAULT 0,
	si82_instit int8 NULL DEFAULT 0,
	CONSTRAINT dispensa182024_sequ_pk PRIMARY KEY (si82_sequencial)
);
CREATE INDEX dispensa182024_si82_reg10_index ON public.dispensa182024 USING btree (si82_reg10);





CREATE TABLE public.dvpdcasp102024 (
	si216_sequencial int4 NOT NULL DEFAULT 0,
	si216_tiporegistro int4 NOT NULL DEFAULT 0,
	si216_vlimpostos float8 NOT NULL DEFAULT 0,
	si216_vlcontribuicoes float8 NOT NULL DEFAULT 0,
	si216_vlexploracovendasdireitos float8 NOT NULL DEFAULT 0,
	si216_vlvariacoesaumentativasfinanceiras float8 NOT NULL DEFAULT 0,
	si216_vltransfdelegacoesrecebidas float8 NOT NULL DEFAULT 0,
	si216_vlvalorizacaoativodesincorpassivo float8 NOT NULL DEFAULT 0,
	si216_vloutrasvariacoespatriaumentativas float8 NOT NULL DEFAULT 0,
	si216_vltotalvpaumentativas float8 NULL DEFAULT 0,
	si216_ano int4 NOT NULL DEFAULT 0,
	si216_periodo int4 NOT NULL DEFAULT 0,
	si216_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT dvpdcasp102024_sequ_pk PRIMARY KEY (si216_sequencial)
);





CREATE TABLE public.dvpdcasp202024 (
	si217_sequencial int4 NOT NULL DEFAULT 0,
	si217_tiporegistro int4 NOT NULL DEFAULT 0,
	si217_vldiminutivapessoaencargos float8 NOT NULL DEFAULT 0,
	si217_vlprevassistenciais float8 NOT NULL DEFAULT 0,
	si217_vlservicoscapitalfixo float8 NOT NULL DEFAULT 0,
	si217_vldiminutivavariacoesfinanceiras float8 NOT NULL DEFAULT 0,
	si217_vltransfconcedidas float8 NOT NULL DEFAULT 0,
	si217_vldesvaloativoincorpopassivo float8 NOT NULL DEFAULT 0,
	si217_vltributarias float8 NOT NULL DEFAULT 0,
	si217_vlmercadoriavendidoservicos float8 NOT NULL DEFAULT 0,
	si217_vloutrasvariacoespatridiminutivas float8 NOT NULL DEFAULT 0,
	si217_vltotalvpdiminutivas float8 NULL DEFAULT 0,
	si217_ano int4 NOT NULL DEFAULT 0,
	si217_periodo int4 NOT NULL DEFAULT 0,
	si217_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT dvpdcasp202024_sequ_pk PRIMARY KEY (si217_sequencial)
);





CREATE TABLE public.dvpdcasp302024 (
	si218_sequencial int4 NOT NULL DEFAULT 0,
	si218_tiporegistro int4 NOT NULL DEFAULT 0,
	si218_vlresultadopatrimonialperiodo float8 NULL DEFAULT 0,
	si218_ano int4 NOT NULL DEFAULT 0,
	si218_periodo int4 NOT NULL DEFAULT 0,
	si218_institu int4 NOT NULL DEFAULT 0,
	CONSTRAINT dvpdcasp302024_sequ_pk PRIMARY KEY (si218_sequencial)
);





CREATE TABLE public.emp102024 (
	si106_sequencial int8 NOT NULL DEFAULT 0,
	si106_tiporegistro int8 NOT NULL DEFAULT 0,
	si106_codorgao varchar(2) NOT NULL,
	si106_codunidadesub varchar(8) NOT NULL,
	si106_codfuncao varchar(2) NOT NULL,
	si106_codsubfuncao varchar(3) NOT NULL,
	si106_codprograma varchar(4) NOT NULL,
	si106_idacao varchar(4) NOT NULL,
	si106_idsubacao varchar(4) NULL,
	si106_naturezadespesa int8 NOT NULL DEFAULT 0,
	si106_subelemento varchar(2) NOT NULL,
	si106_nroempenho int8 NOT NULL DEFAULT 0,
	si106_dtempenho date NOT NULL,
	si106_modalidadeempenho int8 NOT NULL DEFAULT 0,
	si106_tpempenho varchar(2) NOT NULL,
	si106_vlbruto float8 NOT NULL DEFAULT 0,
	si106_especificacaoempenho varchar(500) NOT NULL,
	si106_despdeccontrato int8 NOT NULL DEFAULT 0,
	si106_codorgaorespcontrato varchar(2) NULL,
	si106_codunidadesubrespcontrato varchar(8) NULL,
	si106_nrocontrato varchar(14) NULL DEFAULT 0,
	si106_dtassinaturacontrato date NULL,
	si106_nrosequencialtermoaditivo varchar(2) NULL,
	si106_despdecconvenio int8 NOT NULL DEFAULT 0,
	si106_nroconvenio varchar(30) NULL,
	si106_dataassinaturaconvenio date NULL,
	si106_despdecconvenioconge int8 NOT NULL DEFAULT 0,
	si106_nroconvenioconge varchar(30) NULL,
	si106_dataassinaturaconvenioconge date NULL,
	si106_despdeclicitacao int8 NOT NULL DEFAULT 0,
	si106_codorgaoresplicit varchar(2) NULL,
	si106_codunidadesubresplicit varchar(8) NULL,
	si106_nroprocessolicitatorio varchar(12) NULL,
	si106_exercicioprocessolicitatorio int8 NULL DEFAULT 0,
	si106_tipoprocesso int8 NULL DEFAULT 0,
	si106_cpfordenador varchar(11) NOT NULL,
	si106_mes int8 NOT NULL DEFAULT 0,
	si106_instit int8 NULL DEFAULT 0,
	CONSTRAINT emp102024_sequ_pk PRIMARY KEY (si106_sequencial)
);





CREATE TABLE public.emp202024 (
	si109_sequencial int8 NOT NULL DEFAULT 0,
	si109_tiporegistro int8 NOT NULL DEFAULT 0,
	si109_codorgao varchar(2) NOT NULL,
	si109_codunidadesub varchar(8) NOT NULL,
	si109_nroempenho int8 NOT NULL DEFAULT 0,
	si109_dtempenho date NOT NULL,
	si109_nroreforco int8 NOT NULL DEFAULT 0,
	si109_dtreforco date NOT NULL,
	si109_codfontrecursos int8 NOT NULL DEFAULT 0,
	si109_vlreforco float8 NOT NULL DEFAULT 0,
	si109_mes int8 NOT NULL DEFAULT 0,
	si109_instit int8 NULL DEFAULT 0,
	CONSTRAINT emp202024_sequ_pk PRIMARY KEY (si109_sequencial)
);





CREATE TABLE public.emp302024 (
	si206_sequencial int8 NOT NULL DEFAULT 0,
	si206_tiporegistro int8 NOT NULL DEFAULT 0,
	si206_codorgao varchar(2) NOT NULL,
	si206_codunidadesub varchar(8) NOT NULL,
	si206_nroempenho int8 NOT NULL DEFAULT 0,
	si206_dtempenho date NOT NULL,
	si206_codorgaorespcontrato varchar(2) NULL DEFAULT NULL::character varying,
	si206_codunidadesubrespcontrato varchar(8) NULL DEFAULT NULL::character varying,
	si206_nrocontrato int8 NULL DEFAULT 0,
	si206_dtassinaturacontrato date NULL,
	si206_nrosequencialtermoaditivo int8 NULL DEFAULT 0,
	si206_nroconvenio varchar(30) NULL DEFAULT NULL::character varying,
	si206_dtassinaturaconvenio date NULL,
	si206_nroconvenioconge varchar(30) NULL DEFAULT NULL::character varying,
	si206_dtassinaturaconge date NULL,
	si206_mes int8 NOT NULL DEFAULT 0,
	si206_instit int8 NULL DEFAULT 0,
	CONSTRAINT emp302024_sequ_pk PRIMARY KEY (si206_sequencial)
);





CREATE TABLE public.exeobras102024 (
	si197_sequencial int8 NULL,
	si197_tiporegistro int8 NULL,
	si197_codorgao varchar(3) NULL,
	si197_codunidadesub varchar(8) NULL,
	si197_nrocontrato int8 NULL,
	si197_exerciciocontrato int8 NULL,
	si197_contdeclicitacao int8 NULL,
	si197_exerciciolicitacao int8 NULL,
	si197_nroprocessolicitatorio int8 NULL,
	si197_codunidadesubresp int8 NULL,
	si197_nrolote int8 NULL,
	si197_codobra int8 NULL,
	si197_objeto text NULL,
	si197_linkobra text NULL,
	si197_mes int8 NULL,
	si197_instit int4 NULL
);





CREATE TABLE public.exeobras202024 (
	si204_sequencial int8 NULL,
	si204_tiporegistro int8 NULL,
	si204_codorgao varchar(3) NULL,
	si204_codunidadesub varchar(8) NULL,
	si204_nrocontrato int8 NULL,
	si204_exerciciocontrato int8 NULL,
	si204_contdeclicitacao int8 NULL,
	si204_exercicioprocesso int8 NULL,
	si204_nroprocesso varchar(12) NULL,
	si204_codunidadesubresp varchar(8) NULL,
	si204_tipoprocesso int8 NULL,
	si204_codobra int8 NULL,
	si204_objeto text NULL,
	si204_linkobra text NULL,
	si204_mes int8 NULL,
	si204_instit int4 NULL
);





CREATE TABLE public.ext102024 (
	si124_sequencial int8 NOT NULL DEFAULT 0,
	si124_tiporegistro int8 NOT NULL DEFAULT 0,
	si124_codext int8 NOT NULL DEFAULT 0,
	si124_codorgao varchar(2) NOT NULL,
	si124_tipolancamento varchar(2) NOT NULL,
	si124_subtipo varchar(4) NOT NULL,
	si124_desdobrasubtipo varchar(4) NULL,
	si124_descextraorc varchar(50) NOT NULL,
	si124_mes int8 NOT NULL DEFAULT 0,
	si124_instit int8 NULL DEFAULT 0,
	CONSTRAINT ext102024_sequ_pk PRIMARY KEY (si124_sequencial)
);





CREATE TABLE public.ext202024 (
	si165_sequencial int8 NOT NULL DEFAULT 0,
	si165_tiporegistro int8 NOT NULL DEFAULT 0,
	si165_codorgao varchar(2) NOT NULL,
	si165_codext int8 NOT NULL DEFAULT 0,
	si165_codfontrecursos int8 NOT NULL DEFAULT 0,
	si165_vlsaldoanteriorfonte float8 NOT NULL DEFAULT 0,
	si165_natsaldoanteriorfonte varchar(1) NOT NULL,
	si165_totaldebitos float8 NOT NULL DEFAULT 0,
	si165_totalcreditos float8 NOT NULL DEFAULT 0,
	si165_vlsaldoatualfonte float8 NOT NULL DEFAULT 0,
	si165_natsaldoatualfonte varchar(1) NOT NULL,
	si165_mes int8 NOT NULL DEFAULT 0,
	si165_instit int8 NULL DEFAULT 0,
	si165_exerciciocompdevo int4 NULL DEFAULT 0,
	CONSTRAINT ext202024_sequ_pk PRIMARY KEY (si165_sequencial)
);





CREATE TABLE public.ext302024 (
	si126_sequencial int8 NOT NULL DEFAULT 0,
	si126_tiporegistro int8 NOT NULL DEFAULT 0,
	si126_codext int8 NOT NULL DEFAULT 0,
	si126_codfontrecursos int8 NOT NULL DEFAULT 0,
	si126_codreduzidoop int8 NOT NULL DEFAULT 0,
	si126_nroop int8 NOT NULL DEFAULT 0,
	si126_codunidadesub varchar(8) NOT NULL,
	si126_dtpagamento date NOT NULL,
	si126_tipodocumentocredor int8 NULL DEFAULT 0,
	si126_nrodocumentocredor varchar(14) NULL,
	si126_vlop float8 NOT NULL DEFAULT 0,
	si126_especificacaoop varchar(500) NOT NULL,
	si126_cpfresppgto varchar(11) NOT NULL,
	si126_mes int8 NOT NULL DEFAULT 0,
	si126_instit int8 NULL DEFAULT 0,
	CONSTRAINT ext302024_sequ_pk PRIMARY KEY (si126_sequencial)
);





CREATE TABLE public.flpgo102024 (
	si195_sequencial int8 NOT NULL DEFAULT 0,
	si195_tiporegistro int8 NULL,
	si195_codvinculopessoa int8 NULL,
	si195_regime varchar(1) NULL,
	si195_indtipopagamento varchar(1) NULL,
	si195_dsctipopagextra varchar(150) NULL,
	si195_indsituacaoservidorpensionista varchar(2) NULL,
	si195_indpensionista int4 NULL,
	si195_nrocpfinstituidor varchar(11) NULL,
	si195_datobitoinstituidor date NULL,
	si195_tipodependencia int8 NULL,
	si195_dscdependencia varchar(150) NULL,
	si195_optouafastpreliminar int4 NULL,
	si195_datfastpreliminar date NULL,
	si195_datconcessaoaposentadoriapensao date NULL,
	si195_dsccargo varchar(120) NULL,
	si195_codcargo int8 NULL,
	si195_sglcargo varchar(3) NULL,
	si195_dscapo varchar(3) NULL,
	si195_natcargo int4 NULL,
	si195_indcessao varchar(3) NULL,
	si195_dsclotacao varchar(250) NULL,
	si195_indsalaaula varchar(1) NULL,
	si195_vlrcargahorariasemanal int8 NULL,
	si195_datefetexercicio date NULL,
	si195_datcomissionado date NULL,
	si195_datexclusao date NULL,
	si195_datcomissionadoexclusao date NULL,
	si195_vlrremuneracaobruta float8 NULL,
	si195_vlrdescontos float8 NULL,
	si195_vlrremuneracaoliquida float8 NULL,
	si195_natsaldoliquido varchar(1) NULL,
	si195_mes int8 NULL,
	si195_inst int8 NULL,
	CONSTRAINT flpgo102024_sequ_pk PRIMARY KEY (si195_sequencial)
);





CREATE TABLE public.hablic102024 (
	si57_sequencial int8 NOT NULL DEFAULT 0,
	si57_tiporegistro int8 NOT NULL DEFAULT 0,
	si57_codorgao varchar(2) NOT NULL,
	si57_codunidadesub varchar(8) NOT NULL,
	si57_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si57_nroprocessolicitatorio varchar(12) NOT NULL,
	si57_tipodocumento int8 NOT NULL DEFAULT 0,
	si57_nrodocumento varchar(14) NOT NULL,
	si57_objetosocial varchar(2000) NULL,
	si57_orgaorespregistro int8 NULL DEFAULT 0,
	si57_dataregistro date NULL,
	si57_nroregistro varchar(20) NULL,
	si57_dataregistrocvm date NULL,
	si57_nroregistrocvm varchar(20) NULL,
	si57_nroinscricaoestadual varchar(30) NULL,
	si57_ufinscricaoestadual varchar(2) NULL,
	si57_nrocertidaoregularidadeinss varchar(30) NULL,
	si57_dtemissaocertidaoregularidadeinss date NULL,
	si57_dtvalidadecertidaoregularidadeinss date NULL,
	si57_nrocertidaoregularidadefgts varchar(30) NULL,
	si57_dtemissaocertidaoregularidadefgts date NULL,
	si57_dtvalidadecertidaoregularidadefgts date NULL,
	si57_nrocndt varchar(30) NULL,
	si57_dtemissaocndt date NULL,
	si57_dtvalidadecndt date NULL,
	si57_dthabilitacao date NOT NULL,
	si57_presencalicitantes int8 NULL,
	si57_renunciarecurso int8 NULL,
	si57_mes int8 NOT NULL DEFAULT 0,
	si57_instit int8 NULL DEFAULT 0,
	CONSTRAINT hablic102024_sequ_pk PRIMARY KEY (si57_sequencial)
);





CREATE TABLE public.hablic202024 (
	si59_sequencial int8 NOT NULL DEFAULT 0,
	si59_tiporegistro int8 NOT NULL DEFAULT 0,
	si59_codorgao varchar(2) NOT NULL,
	si59_codunidadesub varchar(8) NULL,
	si59_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si59_nroprocessolicitatorio varchar(12) NOT NULL,
	si59_tipodocumento int8 NOT NULL DEFAULT 0,
	si59_nrodocumento varchar(14) NOT NULL,
	si59_datacredenciamento date NOT NULL,
	si59_nrolote int8 NULL DEFAULT 0,
	si59_coditem int8 NOT NULL DEFAULT 0,
	si59_nroinscricaoestadual varchar(30) NULL,
	si59_ufinscricaoestadual varchar(2) NULL,
	si59_nrocertidaoregularidadeinss varchar(30) NULL,
	si59_dataemissaocertidaoregularidadeinss date NULL,
	si59_dtvalidadecertidaoregularidadeinss date NULL,
	si59_nrocertidaoregularidadefgts varchar(30) NULL,
	si59_dtemissaocertidaoregularidadefgts date NULL,
	si59_dtvalidadecertidaoregularidadefgts date NULL,
	si59_nrocndt varchar(30) NULL,
	si59_dtemissaocndt date NULL,
	si59_dtvalidadecndt date NULL,
	si59_mes int8 NOT NULL DEFAULT 0,
	si59_instit int8 NULL DEFAULT 0,
	CONSTRAINT hablic202024_sequ_pk PRIMARY KEY (si59_sequencial)
);





CREATE TABLE public.homolic102024 (
	si63_sequencial int8 NOT NULL DEFAULT 0,
	si63_tiporegistro int8 NOT NULL DEFAULT 0,
	si63_codorgao varchar(2) NOT NULL,
	si63_codunidadesub varchar(8) NOT NULL,
	si63_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si63_nroprocessolicitatorio varchar(12) NOT NULL,
	si63_tipodocumento int8 NOT NULL DEFAULT 0,
	si63_nrodocumento varchar(14) NOT NULL,
	si63_nrolote int8 NULL DEFAULT 0,
	si63_coditem int8 NOT NULL DEFAULT 0,
	si63_quantidade float8 NOT NULL DEFAULT 0,
	si63_vlunitariohomologado float8 NOT NULL DEFAULT 0,
	si63_mes int8 NOT NULL DEFAULT 0,
	si63_instit int8 NULL DEFAULT 0,
	CONSTRAINT homolic102024_sequ_pk PRIMARY KEY (si63_sequencial)
);





CREATE TABLE public.homolic202024 (
	si64_sequencial int8 NOT NULL DEFAULT 0,
	si64_tiporegistro int8 NOT NULL DEFAULT 0,
	si64_codorgao varchar(2) NOT NULL,
	si64_codunidadesub varchar(8) NOT NULL,
	si64_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si64_nroprocessolicitatorio varchar(12) NOT NULL,
	si64_tipodocumento int8 NOT NULL DEFAULT 0,
	si64_nrodocumento varchar(14) NOT NULL,
	si64_nrolote int8 NULL DEFAULT 0,
	si64_coditem varchar(15) NULL,
	si64_percdesconto float8 NOT NULL DEFAULT 0,
	si64_mes int8 NOT NULL DEFAULT 0,
	si64_instit int8 NULL DEFAULT 0,
	CONSTRAINT homolic202024_sequ_pk PRIMARY KEY (si64_sequencial)
);





CREATE TABLE public.homolic302024 (
	si65_sequencial int8 NOT NULL DEFAULT 0,
	si65_tiporegistro int8 NOT NULL DEFAULT 0,
	si65_codorgao varchar(2) NOT NULL,
	si65_codunidadesub varchar(8) NOT NULL,
	si65_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si65_nroprocessolicitatorio varchar(12) NOT NULL,
	si65_tipodocumento int8 NOT NULL DEFAULT 0,
	si65_nrodocumento varchar(14) NOT NULL,
	si65_nrolote int8 NULL DEFAULT 0,
	si65_coditem varchar(15) NULL,
	si65_perctaxaadm float8 NOT NULL DEFAULT 0,
	si65_mes int8 NOT NULL DEFAULT 0,
	si65_instit int8 NULL DEFAULT 0,
	CONSTRAINT homolic302024_sequ_pk PRIMARY KEY (si65_sequencial)
);





CREATE TABLE public.homolic402024 (
	si65_sequencial int8 NOT NULL DEFAULT 0,
	si65_tiporegistro int8 NOT NULL DEFAULT 0,
	si65_codorgao varchar(2) NOT NULL,
	si65_codunidadesub varchar(8) NULL,
	si65_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si65_nroprocessolicitatorio varchar(12) NOT NULL,
	si65_dthomologacao date NOT NULL,
	si65_dtadjudicacao date NULL,
	si65_mes int8 NOT NULL DEFAULT 0,
	si65_instit int8 NULL DEFAULT 0,
	CONSTRAINT homolic402024_sequ_pk PRIMARY KEY (si65_sequencial)
);





CREATE TABLE public.ide2024 (
	si11_sequencial int8 NOT NULL DEFAULT 0,
	si11_codmunicipio varchar(5) NOT NULL,
	si11_cnpjmunicipio varchar(14) NOT NULL,
	si11_codorgao varchar(3) NOT NULL,
	si11_tipoorgao varchar(2) NOT NULL,
	si11_exercicioreferencia int8 NOT NULL DEFAULT 0,
	si11_mesreferencia varchar(2) NOT NULL,
	si11_datageracao date NOT NULL,
	si11_codcontroleremessa varchar(20) NULL,
	si11_mes int8 NOT NULL DEFAULT 0,
	si11_instit int8 NULL DEFAULT 0,
	CONSTRAINT ide2024_sequ_pk PRIMARY KEY (si11_sequencial)
);





CREATE TABLE public.idedcasp2024 (
	si200_sequencial int4 NOT NULL DEFAULT 0,
	si200_codmunicipio varchar(5) NOT NULL,
	si200_cnpjorgao varchar(14) NOT NULL,
	si200_codorgao varchar(2) NOT NULL,
	si200_tipoorgao varchar(2) NOT NULL,
	si200_tipodemcontabil int4 NOT NULL DEFAULT 0,
	si200_exercicioreferencia int4 NOT NULL DEFAULT 0,
	si200_datageracao date NOT NULL,
	si200_codcontroleremessa varchar(20) NULL,
	si200_anousu int4 NOT NULL DEFAULT 0,
	si200_instit int4 NOT NULL DEFAULT 0,
	CONSTRAINT idedcasp2024_sequ_pk PRIMARY KEY (si200_sequencial)
);





CREATE TABLE public.ideedital2024 (
	si186_sequencial int8 NOT NULL DEFAULT 0,
	si186_codidentificador bpchar(5) NOT NULL,
	si186_cnpj bpchar(14) NOT NULL,
	si186_codorgao varchar(3) NOT NULL,
	si186_tipoorgao varchar(2) NOT NULL,
	si186_exercicioreferencia int2 NOT NULL,
	si186_mesreferencia bpchar(2) NOT NULL,
	si186_datageracao date NOT NULL,
	si186_codcontroleremessa varchar(20) NULL,
	si186_codseqremessames int4 NOT NULL,
	si186_mes int8 NOT NULL DEFAULT 0,
	si186_instit int8 NULL DEFAULT 0,
	CONSTRAINT ideedital2024_sequ_pk PRIMARY KEY (si186_sequencial)
);





CREATE TABLE public.iderp102024 (
	si179_sequencial int8 NOT NULL DEFAULT 0,
	si179_tiporegistro int8 NOT NULL DEFAULT 0,
	si179_codiderp int8 NOT NULL DEFAULT 0,
	si179_codorgao varchar(2) NOT NULL DEFAULT 0,
	si179_codunidadesub varchar(8) NOT NULL DEFAULT 0,
	si179_nroempenho int8 NOT NULL DEFAULT 0,
	si179_tiporestospagar int8 NOT NULL DEFAULT 0,
	si179_disponibilidadecaixa int8 NOT NULL DEFAULT 0,
	si179_vlinscricao float8 NOT NULL DEFAULT 0,
	si179_instit int8 NULL DEFAULT 0,
	si179_mes int8 NOT NULL DEFAULT 0,
	CONSTRAINT iderp102024_sequ_pk PRIMARY KEY (si179_sequencial)
);





CREATE TABLE public.iderp112024 (
	si180_sequencial int8 NOT NULL DEFAULT 0,
	si180_tiporegistro int8 NOT NULL DEFAULT 0,
	si180_codiderp int8 NOT NULL DEFAULT 0,
	si180_codfontrecursos int8 NOT NULL DEFAULT 0,
	si180_vlinscricaofonte float8 NOT NULL DEFAULT 0,
	si180_mes int8 NOT NULL DEFAULT 0,
	si180_reg10 int8 NOT NULL DEFAULT 0,
	si180_instit int8 NULL DEFAULT 0,
	CONSTRAINT iderp112024_sequ_pk PRIMARY KEY (si180_sequencial)
);





CREATE TABLE public.iderp202024 (
	si181_sequencial int8 NOT NULL DEFAULT 0,
	si181_tiporegistro int8 NOT NULL DEFAULT 0,
	si181_codorgao varchar(2) NOT NULL,
	si181_codfontrecursos int8 NOT NULL DEFAULT 0,
	si181_vlcaixabruta float8 NOT NULL DEFAULT 0,
	si181_vlrspexerciciosanteriores float8 NOT NULL DEFAULT 0,
	si181_vlrestituiveisrecolher float8 NULL DEFAULT 0,
	si181_vlrestituiveisativofinanceiro float8 NULL DEFAULT 0,
	si181_vlsaldodispcaixa float8 NOT NULL DEFAULT 0,
	si181_mes int8 NOT NULL DEFAULT 0,
	si181_instit int8 NULL DEFAULT 0,
	CONSTRAINT iderp202024_sequ_pk PRIMARY KEY (si181_sequencial)
);





CREATE TABLE public.item102024 (
	si43_sequencial int8 NOT NULL DEFAULT 0,
	si43_tiporegistro int8 NOT NULL DEFAULT 0,
	si43_coditem int8 NOT NULL DEFAULT 0,
	si43_dscitem text NOT NULL,
	si43_unidademedida varchar(50) NOT NULL,
	si43_tipocadastro int8 NOT NULL DEFAULT 0,
	si43_justificativaalteracao varchar(100) NULL,
	si43_mes int8 NOT NULL DEFAULT 0,
	si43_instit int8 NULL DEFAULT 0,
	CONSTRAINT item102024_sequ_pk PRIMARY KEY (si43_sequencial)
);





CREATE TABLE public.julglic102024 (
	si60_sequencial int8 NOT NULL DEFAULT 0,
	si60_tiporegistro int8 NOT NULL DEFAULT 0,
	si60_codorgao varchar(2) NOT NULL,
	si60_codunidadesub varchar(8) NOT NULL,
	si60_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si60_nroprocessolicitatorio varchar(12) NOT NULL,
	si60_tipodocumento int8 NOT NULL DEFAULT 0,
	si60_nrodocumento varchar(14) NOT NULL,
	si60_nrolote int8 NULL DEFAULT 0,
	si60_coditem int8 NOT NULL DEFAULT 0,
	si60_vlunitario float8 NOT NULL DEFAULT 0,
	si60_quantidade float8 NOT NULL DEFAULT 0,
	si60_mes int8 NOT NULL DEFAULT 0,
	si60_instit int8 NULL DEFAULT 0,
	CONSTRAINT julglic102024_sequ_pk PRIMARY KEY (si60_sequencial)
);





CREATE TABLE public.julglic202024 (
	si61_sequencial int8 NOT NULL DEFAULT 0,
	si61_tiporegistro int8 NOT NULL DEFAULT 0,
	si61_codorgao varchar(2) NOT NULL,
	si61_codunidadesub varchar(8) NOT NULL,
	si61_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si61_nroprocessolicitatorio varchar(12) NOT NULL,
	si61_tipodocumento int8 NOT NULL DEFAULT 0,
	si61_nrodocumento varchar(14) NOT NULL,
	si61_nrolote int8 NULL DEFAULT 0,
	si61_coditem varchar(15) NULL,
	si61_percdesconto float8 NOT NULL DEFAULT 0,
	si61_mes int8 NOT NULL DEFAULT 0,
	si61_instit int8 NULL DEFAULT 0,
	CONSTRAINT julglic202024_sequ_pk PRIMARY KEY (si61_sequencial)
);





CREATE TABLE public.julglic302024 (
	si62_sequencial int8 NOT NULL DEFAULT 0,
	si62_tiporegistro int8 NOT NULL DEFAULT 0,
	si62_codorgao varchar(2) NOT NULL,
	si62_codunidadesub varchar(8) NOT NULL,
	si62_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si62_nroprocessolicitatorio varchar(12) NOT NULL,
	si62_tipodocumento int8 NOT NULL DEFAULT 0,
	si62_nrodocumento varchar(14) NOT NULL,
	si62_nrolote int8 NULL,
	si62_coditem varchar(15) NULL,
	si62_perctaxaadm float8 NOT NULL DEFAULT 0,
	si62_mes int8 NOT NULL DEFAULT 0,
	si62_instit int4 NULL DEFAULT 0
);





CREATE TABLE public.julglic402024 (
	si62_sequencial int8 NOT NULL DEFAULT 0,
	si62_tiporegistro int8 NOT NULL DEFAULT 0,
	si62_codorgao varchar(2) NOT NULL,
	si62_codunidadesub varchar(8) NOT NULL,
	si62_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si62_nroprocessolicitatorio varchar(12) NOT NULL,
	si62_dtjulgamento date NOT NULL,
	si62_presencalicitantes int8 NULL,
	si62_renunciarecurso int8 NULL,
	si62_mes int8 NOT NULL DEFAULT 0,
	si62_instit int4 NULL DEFAULT 0,
	CONSTRAINT julglic402024_sequ_pk PRIMARY KEY (si62_sequencial)
);





CREATE TABLE public.lao102024 (
	si34_sequencial int8 NOT NULL DEFAULT 0,
	si34_tiporegistro int8 NOT NULL DEFAULT 0,
	si34_codorgao varchar(2) NOT NULL,
	si34_nroleialteracao int8 NOT NULL,
	si34_dataleialteracao date NOT NULL,
	si34_mes int8 NOT NULL DEFAULT 0,
	si34_instit int8 NULL DEFAULT 0,
	CONSTRAINT lao102024_sequ_pk PRIMARY KEY (si34_sequencial)
);





CREATE TABLE public.lao202024 (
	si36_sequencial int8 NOT NULL DEFAULT 0,
	si36_tiporegistro int8 NOT NULL DEFAULT 0,
	si36_codorgao varchar(2) NOT NULL,
	si36_nroleialterorcam varchar(6) NOT NULL,
	si36_dataleialterorcam date NOT NULL,
	si36_mes int8 NOT NULL DEFAULT 0,
	si36_instit int8 NULL DEFAULT 0,
	CONSTRAINT lao202024_sequ_pk PRIMARY KEY (si36_sequencial)
);





CREATE TABLE public.licobras102024 (
	si195_sequencial int8 NULL,
	si195_tiporegistro int8 NULL,
	si195_codorgaoresp varchar(3) NULL,
	si195_codunidadesubrespestadual varchar(4) NULL,
	si195_exerciciolicitacao int8 NULL,
	si195_nroprocessolicitatorio varchar(12) NULL,
	si195_nrolote int8 NULL,
	si195_contdeclicitacao int8 NULL,
	si195_codobra int8 NULL,
	si195_objeto text NULL,
	si195_linkobra text NULL,
	si195_codorgaorespsicom int8 NULL,
	si195_codunidadesubsicom int8 NULL,
	si195_nrocontrato int8 NULL,
	si195_exerciciocontrato int8 NULL,
	si195_dataassinatura date NULL,
	si195_vlcontrato numeric NULL,
	si195_undmedidaprazoexecucao int8 NULL,
	si195_prazoexecucao int8 NULL,
	si195_mes int8 NULL,
	si195_instit int4 NULL
);





CREATE TABLE public.licobras202024 (
	si196_sequencial int8 NULL,
	si196_tiporegistro int8 NULL,
	si196_codorgaoresp varchar(3) NULL,
	si196_codunidadesubrespestadual varchar(4) NULL,
	si196_exerciciolicitacao int8 NULL,
	si196_nroprocessolicitatorio varchar(12) NULL,
	si196_tipoprocesso int4 NULL,
	si196_contdeclicitacao int8 NULL,
	si196_codobra int8 NULL,
	si196_objeto text NULL,
	si196_linkobra text NULL,
	si196_codorgaorespsicom int8 NULL,
	si196_codunidadesubsicom int8 NULL,
	si196_nrocontrato int8 NULL,
	si196_exerciciocontrato int8 NULL,
	si196_dataassinatura date NULL,
	si196_vlcontrato numeric NULL,
	si196_undmedidaprazoexecucao int8 NULL,
	si196_prazoexecucao int8 NULL,
	si196_mes int8 NULL,
	si196_instit int4 NULL
);





CREATE TABLE public.licobras302024 (
	si203_sequencial int8 NULL,
	si203_tiporegistro int8 NULL,
	si203_codorgaoresp varchar(3) NULL,
	si203_codobra int8 NULL,
	si203_codunidadesubrespestadual varchar(4) NULL,
	si203_nroseqtermoaditivo int8 NULL,
	si203_dataassinaturatermoaditivo date NULL,
	si203_tipoalteracaovalor int4 NULL,
	si203_tipotermoaditivo varchar(2) NULL,
	si203_dscalteracao text NULL,
	si203_novadatatermino date NULL,
	si203_tipodetalhamento int8 NULL,
	si203_valoraditivo numeric NULL,
	si203_mes int8 NULL,
	si203_instit int4 NULL
);





CREATE TABLE public.lqd102024 (
	si118_sequencial int8 NOT NULL DEFAULT 0,
	si118_tiporegistro int8 NOT NULL DEFAULT 0,
	si118_codreduzido int8 NOT NULL DEFAULT 0,
	si118_codorgao varchar(2) NOT NULL,
	si118_codunidadesub varchar(8) NOT NULL,
	si118_tpliquidacao int8 NOT NULL DEFAULT 0,
	si118_nroempenho int8 NOT NULL DEFAULT 0,
	si118_dtempenho date NOT NULL,
	si118_dtliquidacao date NOT NULL,
	si118_nroliquidacao int8 NOT NULL DEFAULT 0,
	si118_vlliquidado float8 NOT NULL DEFAULT 0,
	si118_cpfliquidante varchar(11) NOT NULL,
	si118_mes int8 NOT NULL DEFAULT 0,
	si118_instit int8 NULL DEFAULT 0,
	CONSTRAINT lqd102024_sequ_pk PRIMARY KEY (si118_sequencial)
);





CREATE TABLE public.metareal102024 (
	si171_sequencial int8 NOT NULL DEFAULT 0,
	si171_tiporegistro int8 NOT NULL DEFAULT 0,
	si171_codorgao varchar(2) NOT NULL DEFAULT 0,
	si171_codunidadesub varchar(8) NOT NULL DEFAULT 0,
	si171_codfuncao varchar(2) NOT NULL DEFAULT 0,
	si171_codsubfuncao varchar(3) NOT NULL DEFAULT 0,
	si171_codprograma varchar(4) NOT NULL DEFAULT 0,
	si171_idacao varchar(4) NOT NULL DEFAULT 0,
	si171_idsubacao varchar(4) NULL DEFAULT 0,
	si171_metarealizada float8 NOT NULL DEFAULT 0,
	si171_justificativa varchar(1000) NULL DEFAULT 0,
	si171_instit int8 NULL DEFAULT 0,
	si171_mes int4 NULL,
	CONSTRAINT metareal102024_sequ_pk PRIMARY KEY (si171_sequencial)
);





CREATE TABLE public.ntf102024 (
	si143_sequencial int8 NOT NULL DEFAULT 0,
	si143_tiporegistro int8 NOT NULL DEFAULT 0,
	si143_codnotafiscal int8 NOT NULL DEFAULT 0,
	si143_codorgao varchar(2) NOT NULL,
	si143_nfnumero int8 NULL DEFAULT 0,
	si143_nfserie varchar(8) NULL,
	si143_tipodocumento int8 NOT NULL DEFAULT 0,
	si143_nrodocumento varchar(14) NOT NULL,
	si143_nroinscestadual varchar(30) NULL,
	si143_nroinscmunicipal varchar(30) NULL,
	si143_nomemunicipio varchar(120) NOT NULL,
	si143_cepmunicipio int8 NOT NULL DEFAULT 0,
	si143_ufcredor varchar(2) NOT NULL,
	si143_notafiscaleletronica int8 NOT NULL DEFAULT 0,
	si143_chaveacesso varchar(44) NULL,
	si143_outrachaveacesso varchar(60) NULL,
	si143_nfaidf varchar(15) NOT NULL,
	si143_dtemissaonf date NOT NULL,
	si143_dtvencimentonf date NULL,
	si143_nfvalortotal float8 NOT NULL DEFAULT 0,
	si143_nfvalordesconto float8 NOT NULL DEFAULT 0,
	si143_nfvalorliquido float8 NOT NULL DEFAULT 0,
	si143_mes int8 NOT NULL DEFAULT 0,
	si143_instit int8 NULL DEFAULT 0,
	CONSTRAINT ntf102024_sequ_pk PRIMARY KEY (si143_sequencial)
);





CREATE TABLE public.ntf202024 (
	si145_sequencial int8 NOT NULL DEFAULT 0,
	si145_tiporegistro int8 NOT NULL DEFAULT 0,
	si145_nfnumero int8 NOT NULL DEFAULT 0,
	si145_nfserie varchar(8) NULL DEFAULT 0,
	si145_tipodocumento int8 NOT NULL DEFAULT 0,
	si145_nrodocumento varchar(14) NOT NULL DEFAULT 0,
	si145_chaveacesso varchar(44) NULL,
	si145_dtemissaonf date NOT NULL,
	si145_codunidadesub varchar(8) NOT NULL,
	si145_dtempenho date NOT NULL,
	si145_nroempenho int8 NOT NULL DEFAULT 0,
	si145_dtliquidacao date NOT NULL,
	si145_nroliquidacao int8 NOT NULL DEFAULT 0,
	si145_mes int8 NOT NULL DEFAULT 0,
	si145_reg10 int8 NOT NULL DEFAULT 0,
	si145_instit int8 NULL DEFAULT 0,
	CONSTRAINT ntf202024_sequ_pk PRIMARY KEY (si145_sequencial)
);





CREATE TABLE public.obelac102024 (
	si139_sequencial int8 NOT NULL DEFAULT 0,
	si139_tiporegistro int8 NOT NULL DEFAULT 0,
	si139_codreduzido int8 NOT NULL DEFAULT 0,
	si139_codorgao varchar(2) NOT NULL,
	si139_codunidadesub varchar(8) NOT NULL,
	si139_nrolancamento int8 NOT NULL DEFAULT 0,
	si139_dtlancamento date NOT NULL,
	si139_tipolancamento int8 NOT NULL DEFAULT 0,
	si139_nroempenho int8 NOT NULL DEFAULT 0,
	si139_dtempenho date NOT NULL,
	si139_nroliquidacao int8 NULL DEFAULT 0,
	si139_dtliquidacao date NULL,
	si139_esplancamento varchar(500) NOT NULL,
	si139_valorlancamento float8 NOT NULL DEFAULT 0,
	si139_mes int8 NOT NULL DEFAULT 0,
	si139_instit int8 NULL DEFAULT 0,
	CONSTRAINT obelac102024_sequ_pk PRIMARY KEY (si139_sequencial)
);





CREATE TABLE public.ops102024 (
	si132_sequencial int8 NOT NULL DEFAULT 0,
	si132_tiporegistro int8 NOT NULL DEFAULT 0,
	si132_codorgao varchar(2) NOT NULL,
	si132_codunidadesub varchar(8) NOT NULL,
	si132_nroop int8 NOT NULL DEFAULT 0,
	si132_dtpagamento date NOT NULL,
	si132_vlop float8 NOT NULL DEFAULT 0,
	si132_especificacaoop varchar(500) NOT NULL,
	si132_cpfresppgto varchar(11) NOT NULL,
	si132_mes int8 NOT NULL DEFAULT 0,
	si132_instit int8 NULL DEFAULT 0,
	CONSTRAINT ops102024_sequ_pk PRIMARY KEY (si132_sequencial)
);





CREATE TABLE public.orgao102024 (
	si14_sequencial int8 NOT NULL DEFAULT 0,
	si14_tiporegistro int8 NOT NULL DEFAULT 0,
	si14_codorgao varchar(2) NOT NULL,
	si14_tipoorgao varchar(2) NOT NULL,
	si14_cnpjorgao varchar(14) NOT NULL,
	si14_tipodocumentofornsoftware int8 NOT NULL DEFAULT 0,
	si14_nrodocumentofornsoftware varchar(14) NOT NULL,
	si14_versaosoftware varchar(50) NOT NULL,
	si14_assessoriacontabil int8 NOT NULL,
	si14_tipodocumentoassessoria int8 NULL,
	si14_nrodocumentoassessoria varchar(14) NULL,
	si14_mes int8 NOT NULL DEFAULT 0,
	si14_instit int8 NULL DEFAULT 0,
	CONSTRAINT orgao102024_sequ_pk PRIMARY KEY (si14_sequencial)
);





CREATE TABLE public.parec102024 (
	si22_sequencial int8 NOT NULL DEFAULT 0,
	si22_tiporegistro int8 NOT NULL DEFAULT 0,
	si22_codreduzido int8 NOT NULL DEFAULT 0,
	si22_codorgao varchar(2) NOT NULL,
	si22_ededucaodereceita int8 NOT NULL DEFAULT 0,
	si22_identificadordeducao int8 NULL DEFAULT 0,
	si22_naturezareceita int8 NOT NULL DEFAULT 0,
	si22_tipoatualizacao int8 NOT NULL DEFAULT 0,
	si22_vlacrescidoreduzido float8 NOT NULL DEFAULT 0,
	si22_mes int8 NOT NULL DEFAULT 0,
	si22_instit int8 NULL DEFAULT 0,
	CONSTRAINT parec102024_sequ_pk PRIMARY KEY (si22_sequencial)
);





CREATE TABLE public.parelic102024 (
	si66_sequencial int8 NOT NULL DEFAULT 0,
	si66_tiporegistro int8 NOT NULL DEFAULT 0,
	si66_codorgao varchar(2) NOT NULL,
	si66_codunidadesub varchar(8) NULL,
	si66_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si66_nroprocessolicitatorio varchar(12) NOT NULL,
	si66_dataparecer date NOT NULL,
	si66_tipoparecer int8 NOT NULL DEFAULT 0,
	si66_nrocpf varchar(11) NOT NULL,
	si66_mes int8 NOT NULL DEFAULT 0,
	si66_instit int8 NULL DEFAULT 0,
	CONSTRAINT parelic102024_sequ_pk PRIMARY KEY (si66_sequencial)
);





CREATE TABLE public.parpps102024 (
	si156_sequencial int8 NOT NULL DEFAULT 0,
	si156_tiporegistro int8 NOT NULL DEFAULT 0,
	si156_codorgao varchar(2) NOT NULL,
	si156_tipoplano int8 NOT NULL DEFAULT 0,
	si156_exercicio int8 NOT NULL DEFAULT 0,
	si156_vlsaldofinanceiroexercicioanterior float8 NOT NULL DEFAULT 0,
	si156_vlreceitaprevidenciariaanterior float8 NOT NULL DEFAULT 0,
	si156_vldespesaprevidenciariaanterior float8 NOT NULL DEFAULT 0,
	si156_mes int8 NOT NULL DEFAULT 0,
	si156_instit int8 NULL DEFAULT 0,
	CONSTRAINT parpps102024_sequ_pk PRIMARY KEY (si156_sequencial)
);





CREATE TABLE public.parpps202024 (
	si155_sequencial int8 NOT NULL DEFAULT 0,
	si155_tiporegistro int8 NOT NULL DEFAULT 0,
	si155_codorgao varchar(2) NOT NULL,
	si155_tipoplano int8 NOT NULL DEFAULT 0,
	si155_exercicio int8 NOT NULL DEFAULT 0,
	si155_vlreceitaprevidenciaria float8 NOT NULL DEFAULT 0,
	si155_vldespesaprevidenciaria float8 NOT NULL DEFAULT 0,
	si155_mes int8 NOT NULL DEFAULT 0,
	si155_instit int8 NULL DEFAULT 0,
	si155_dtavaliacao date NULL,
	CONSTRAINT parpps202024_sequ_pk PRIMARY KEY (si155_sequencial)
);





CREATE TABLE public.partlic102024 (
	si203_sequencial int8 NOT NULL DEFAULT 0,
	si203_tiporegistro int8 NOT NULL DEFAULT 0,
	si203_codorgao varchar(2) NOT NULL,
	si203_codunidadesub varchar(8) NOT NULL,
	si203_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si203_nroprocessolicitatorio int8 NOT NULL DEFAULT 0,
	si203_tipodocumento int8 NOT NULL DEFAULT 0,
	si203_nrodocumento varchar(14) NULL,
	si203_mes int8 NOT NULL DEFAULT 0,
	si203_instit int8 NULL DEFAULT 0
);





CREATE TABLE public.pessoa102024 (
	si12_sequencial int8 NOT NULL DEFAULT 0,
	si12_tiporegistro int8 NOT NULL DEFAULT 0,
	si12_tipodocumento int8 NOT NULL DEFAULT 0,
	si12_nrodocumento varchar(14) NOT NULL,
	si12_nomerazaosocial varchar(120) NOT NULL,
	si12_tipocadastro int8 NOT NULL DEFAULT 0,
	si12_justificativaalteracao varchar(100) NULL,
	si12_mes int8 NOT NULL DEFAULT 0,
	si12_instit int8 NULL DEFAULT 0,
	CONSTRAINT pessoa102024_sequ_pk PRIMARY KEY (si12_sequencial)
);





CREATE TABLE public.pessoaflpgo102024 (
	si193_sequencial int8 NOT NULL DEFAULT 0,
	si193_tiporegistro int8 NOT NULL DEFAULT 0,
	si193_tipodocumento int8 NOT NULL DEFAULT 0,
	si193_nrodocumento varchar(14) NOT NULL,
	si193_nome varchar(120) NOT NULL,
	si193_indsexo varchar(1) NULL,
	si193_datanascimento date NULL,
	si193_tipocadastro int8 NOT NULL DEFAULT 0,
	si193_justalteracao varchar(100) NULL,
	si193_mes int8 NOT NULL DEFAULT 0,
	si193_inst int8 NULL DEFAULT 0,
	CONSTRAINT pessoaflpgo102024_sequ_pk PRIMARY KEY (si193_sequencial)
);





CREATE TABLE public.pessoasobra102024 (
	si194_sequencial int8 NULL,
	si194_tiporegistro int8 NULL,
	si194_nrodocumento varchar(14) NULL,
	si194_nome varchar(120) NULL,
	si194_tipocadastro int8 NULL,
	si194_justificativaalteracao text NULL,
	si194_mes int8 NULL,
	si194_instit int4 NULL
);





CREATE TABLE public.ralic102024 (
	si180_sequencial int8 NOT NULL DEFAULT 0,
	si180_tiporegistro int8 NOT NULL DEFAULT 0,
	si180_codorgaoresp varchar(3) NOT NULL,
	si180_codunidadesubresp varchar(8) NOT NULL,
	si180_codunidadesubrespestadual varchar(4) NULL,
	si180_exerciciolicitacao int2 NOT NULL,
	si180_nroprocessolicitatorio varchar(12) NOT NULL,
	si180_tipocadastradolicitacao bpchar(1) NOT NULL,
	si180_dsccadastrolicitatorio varchar(150) NULL,
	si180_codmodalidadelicitacao int2 NOT NULL,
	si180_naturezaprocedimento int2 NOT NULL,
	si180_nroedital int4 NOT NULL,
	si180_exercicioedital int2 NOT NULL DEFAULT 0,
	si180_dtpublicacaoeditaldo date NULL,
	si180_link varchar(200) NOT NULL,
	si180_tipolicitacao int2 NULL,
	si180_naturezaobjeto int2 NULL,
	si180_objeto varchar(500) NOT NULL,
	si180_regimeexecucaoobras int2 NULL,
	si180_vlcontratacao float4 NOT NULL,
	si180_bdi float4 NULL,
	si180_mesexercicioreforc int4 NULL,
	si180_origemrecurso int2 NOT NULL,
	si180_dscorigemrecurso varchar(150) NULL,
	si180_mes int8 NOT NULL DEFAULT 0,
	si180_instit int8 NULL DEFAULT 0,
	si180_qtdlotes int4 NULL,
	si180_leidalicitacao int4 NULL,
	si180_mododisputa int4 NULL,
	si180_dtaberturaenvelopes date NULL,
	si180_tipoorcamento int4 NULL,
	CONSTRAINT ralic102024_sequ_pk PRIMARY KEY (si180_sequencial)
);





CREATE TABLE public.rec102024 (
	si25_sequencial int8 NOT NULL DEFAULT 0,
	si25_tiporegistro int8 NOT NULL DEFAULT 0,
	si25_codreceita int8 NOT NULL DEFAULT 0,
	si25_codorgao varchar(2) NOT NULL,
	si25_ededucaodereceita int8 NOT NULL DEFAULT 0,
	si25_identificadordeducao int8 NOT NULL DEFAULT 0,
	si25_naturezareceita int8 NOT NULL DEFAULT 0,
	si25_vlarrecadado float8 NOT NULL DEFAULT 0,
	si25_mes int8 NOT NULL DEFAULT 0,
	si25_instit int8 NULL DEFAULT 0,
	CONSTRAINT rec102024_sequ_pk PRIMARY KEY (si25_sequencial)
);





CREATE TABLE public.redispi102024 (
	si183_sequencial int8 NOT NULL DEFAULT 0,
	si183_tiporegistro int8 NOT NULL DEFAULT 0,
	si183_codorgaoresp varchar(3) NOT NULL,
	si183_codunidadesubresp varchar(8) NULL,
	si183_codunidadesubrespestadual bpchar(4) NULL,
	si183_exercicioprocesso int2 NOT NULL,
	si183_nroprocesso varchar(12) NOT NULL,
	si183_tipoprocesso int2 NOT NULL,
	si183_tipocadastradodispensainexigibilidade int2 NOT NULL,
	si183_dsccadastrolicitatorio varchar(150) NULL,
	si183_dtabertura date NOT NULL,
	si183_naturezaobjeto int2 NOT NULL,
	si183_objeto varchar(500) NOT NULL,
	si183_justificativa varchar(250) NOT NULL,
	si183_razao varchar(250) NOT NULL,
	si183_vlrecurso float4 NOT NULL,
	si183_bdi float4 NULL,
	si183_mes int8 NOT NULL DEFAULT 0,
	si183_instit int8 NULL DEFAULT 0,
	si183_link varchar(200) NULL,
	si183_leidalicitacao int4 NULL,
	si183_regimeexecucaoobras int4 NULL,
	CONSTRAINT redispi102024_sequ_pk PRIMARY KEY (si183_sequencial)
);





CREATE TABLE public.regadesao102024 (
	si67_sequencial int8 NOT NULL DEFAULT 0,
	si67_tiporegistro int8 NOT NULL DEFAULT 0,
	si67_tipocadastro int4 NOT NULL,
	si67_codorgao varchar(2) NOT NULL,
	si67_codunidadesub varchar(8) NOT NULL,
	si67_nroprocadesao varchar(12) NOT NULL,
	si63_exercicioadesao int8 NOT NULL DEFAULT 0,
	si67_dtabertura date NOT NULL,
	si67_nomeorgaogerenciador varchar(100) NOT NULL,
	si67_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si67_nroprocessolicitatorio varchar(20) NOT NULL,
	si67_codmodalidadelicitacao int8 NOT NULL DEFAULT 0,
	si67_nroedital int4 NOT NULL,
	si67_exercicioedital int4 NOT NULL,
	si67_dtataregpreco date NOT NULL,
	si67_dtvalidade date NOT NULL,
	si67_naturezaprocedimento int8 NOT NULL DEFAULT 0,
	si67_dtpublicacaoavisointencao date NULL,
	si67_objetoadesao varchar(500) NOT NULL,
	si67_cpfresponsavel varchar(11) NOT NULL,
	si67_descontotabela int8 NOT NULL DEFAULT 0,
	si67_processoporlote int8 NOT NULL DEFAULT 0,
	si67_mes int8 NOT NULL DEFAULT 0,
	si67_instit int8 NULL DEFAULT 0,
	si67_leidalicitacao int4 NULL,
	CONSTRAINT regadesao102024_sequ_pk PRIMARY KEY (si67_sequencial)
);





CREATE TABLE public.regadesao202024 (
	si73_sequencial int8 NOT NULL DEFAULT 0,
	si73_tiporegistro int8 NOT NULL DEFAULT 0,
	si73_codorgao varchar(2) NOT NULL,
	si73_codunidadesub varchar(8) NOT NULL,
	si73_nroprocadesao varchar(12) NOT NULL,
	si73_exercicioadesao int8 NOT NULL DEFAULT 0,
	si73_nrolote int8 NULL DEFAULT 0,
	si73_coditem int8 NULL DEFAULT 0,
	si73_percdesconto float8 NOT NULL DEFAULT 0,
	si73_tipodocumento int8 NOT NULL DEFAULT 0,
	si73_nrodocumento varchar(14) NOT NULL,
	si73_mes int8 NOT NULL DEFAULT 0,
	si73_instit int8 NULL DEFAULT 0,
	CONSTRAINT regadesao202024_sequ_pk PRIMARY KEY (si73_sequencial)
);





CREATE TABLE public.reglic102024 (
	si44_sequencial int8 NOT NULL DEFAULT 0,
	si44_tiporegistro int8 NOT NULL DEFAULT 0,
	si44_codorgao varchar(2) NOT NULL,
	si44_tipodecreto int8 NOT NULL DEFAULT 0,
	si44_nrodecretomunicipal int8 NOT NULL DEFAULT 0,
	si44_datadecretomunicipal date NOT NULL,
	si44_datapublicacaodecretomunicipal date NOT NULL,
	si44_mes int8 NOT NULL DEFAULT 0,
	si44_instit int8 NULL DEFAULT 0,
	CONSTRAINT reglic102024_sequ_pk PRIMARY KEY (si44_sequencial)
);





CREATE TABLE public.reglic202024 (
	si45_sequencial int8 NOT NULL DEFAULT 0,
	si45_tiporegistro int8 NOT NULL DEFAULT 0,
	si45_codorgao varchar(2) NOT NULL,
	si45_regulamentart47 int8 NOT NULL DEFAULT 0,
	si45_nronormareg varchar(6) NULL,
	si45_datanormareg date NULL,
	si45_datapubnormareg date NULL,
	si45_regexclusiva int8 NULL DEFAULT 0,
	si45_artigoregexclusiva varchar(6) NULL,
	si45_valorlimiteregexclusiva float8 NOT NULL DEFAULT 0,
	si45_procsubcontratacao int8 NULL DEFAULT 0,
	si45_artigoprocsubcontratacao varchar(6) NULL,
	si45_percentualsubcontratacao float8 NOT NULL DEFAULT 0,
	si45_criteriosempenhopagamento int8 NULL DEFAULT 0,
	si45_artigoempenhopagamento varchar(6) NULL,
	si45_estabeleceuperccontratacao int8 NULL DEFAULT 0,
	si45_artigoperccontratacao varchar(6) NULL,
	si45_percentualcontratacao float8 NOT NULL DEFAULT 0,
	si45_mes int8 NOT NULL DEFAULT 0,
	si45_instit int8 NULL DEFAULT 0,
	CONSTRAINT reglic202024_sequ_pk PRIMARY KEY (si45_sequencial)
);





CREATE TABLE public.respinf2024 (
	si197_sequencial int8 NOT NULL DEFAULT 0,
	si197_nrodocumento varchar(11) NOT NULL,
	si197_dtinicio date NULL,
	si197_dtfinal date NULL,
	si197_mes int8 NULL,
	si197_instit int8 NULL,
	CONSTRAINT respinf2024_sequ_pk PRIMARY KEY (si197_sequencial)
);





CREATE TABLE public.resplic102024 (
	si55_sequencial int8 NOT NULL DEFAULT 0,
	si55_tiporegistro int8 NOT NULL DEFAULT 0,
	si55_codorgao varchar(2) NOT NULL,
	si55_codunidadesub varchar(8) NOT NULL,
	si55_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si55_nroprocessolicitatorio varchar(12) NOT NULL,
	si55_tiporesp int8 NOT NULL DEFAULT 0,
	si55_nrocpfresp varchar(11) NOT NULL,
	si55_mes int8 NOT NULL DEFAULT 0,
	si55_instit int8 NULL DEFAULT 0,
	CONSTRAINT resplic102024_sequ_pk PRIMARY KEY (si55_sequencial)
);





CREATE TABLE public.resplic202024 (
	si56_sequencial int8 NOT NULL DEFAULT 0,
	si56_tiporegistro int8 NOT NULL DEFAULT 0,
	si56_codorgao varchar(2) NOT NULL,
	si56_codunidadesub varchar(8) NOT NULL,
	si56_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si56_nroprocessolicitatorio varchar(12) NOT NULL,
	si56_codtipocomissao int8 NULL,
	si56_descricaoatonomeacao int8 NOT NULL DEFAULT 0,
	si56_nroatonomeacao int8 NOT NULL DEFAULT 0,
	si56_dataatonomeacao date NOT NULL,
	si56_iniciovigencia date NOT NULL,
	si56_finalvigencia date NOT NULL,
	si56_cpfmembrocomissao varchar(11) NOT NULL,
	si56_codatribuicao int8 NOT NULL DEFAULT 0,
	si56_cargo varchar(50) NOT NULL,
	si56_naturezacargo int8 NOT NULL DEFAULT 0,
	si56_mes int8 NOT NULL DEFAULT 0,
	si56_instit int8 NULL DEFAULT 0,
	CONSTRAINT resplic202024_sequ_pk PRIMARY KEY (si56_sequencial)
);





CREATE TABLE public.rpsd102024 (
	si189_sequencial int8 NOT NULL DEFAULT 0,
	si189_tiporegistro int8 NOT NULL DEFAULT 0,
	si189_codreduzidorsp int8 NOT NULL DEFAULT 0,
	si189_codorgao varchar(2) NOT NULL,
	si189_codunidadesub varchar(8) NOT NULL,
	si189_codunidadesuborig varchar(8) NOT NULL,
	si189_nroempenho int8 NOT NULL DEFAULT 0,
	si189_exercicioempenho int8 NOT NULL DEFAULT 0,
	si189_dtempenho date NOT NULL,
	si189_tipopagamentorsp int8 NOT NULL DEFAULT 0,
	si189_vlpagorsp float8 NOT NULL DEFAULT 0,
	si189_mes int8 NOT NULL DEFAULT 0,
	si189_instit int8 NULL DEFAULT 0,
	CONSTRAINT rpsd102024_sequ_pk PRIMARY KEY (si189_sequencial)
);





CREATE TABLE public.rsp102024 (
	si112_sequencial int8 NOT NULL DEFAULT 0,
	si112_tiporegistro int8 NOT NULL DEFAULT 0,
	si112_codreduzidorsp int8 NOT NULL DEFAULT 0,
	si112_codorgao varchar(2) NOT NULL,
	si112_codunidadesub varchar(8) NOT NULL,
	si112_codunidadesuborig varchar(8) NOT NULL,
	si112_nroempenho int8 NOT NULL DEFAULT 0,
	si112_exercicioempenho int8 NOT NULL DEFAULT 0,
	si112_dtempenho date NOT NULL,
	si112_dotorig varchar(21) NULL,
	si112_vloriginal float8 NOT NULL DEFAULT 0,
	si112_vlsaldoantproce float8 NOT NULL DEFAULT 0,
	si112_vlsaldoantnaoproc float8 NOT NULL DEFAULT 0,
	si112_mes int8 NOT NULL DEFAULT 0,
	si112_instit int8 NULL DEFAULT 0,
	CONSTRAINT rsp102024_sequ_pk PRIMARY KEY (si112_sequencial)
);





CREATE TABLE public.rsp202024 (
	si115_sequencial int8 NOT NULL DEFAULT 0,
	si115_tiporegistro int8 NOT NULL DEFAULT 0,
	si115_codreduzidomov int8 NOT NULL DEFAULT 0,
	si115_codorgao varchar(2) NOT NULL,
	si115_codunidadesub varchar(8) NOT NULL,
	si115_codunidadesuborig varchar(8) NOT NULL,
	si115_nroempenho int8 NOT NULL DEFAULT 0,
	si115_exercicioempenho int8 NOT NULL DEFAULT 0,
	si115_dtempenho date NOT NULL,
	si115_tiporestospagar int8 NOT NULL DEFAULT 0,
	si115_tipomovimento int8 NOT NULL DEFAULT 0,
	si115_dtmovimentacao date NOT NULL,
	si115_dotorig varchar(21) NULL,
	si115_vlmovimentacao float8 NOT NULL DEFAULT 0,
	si115_codorgaoencampatribuic varchar(2) NULL,
	si115_codunidadesubencampatribuic varchar(8) NULL,
	si115_justificativa varchar(500) NOT NULL,
	si115_atocancelamento varchar(20) NULL,
	si115_dataatocancelamento date NULL,
	si115_mes int8 NOT NULL DEFAULT 0,
	si115_instit int8 NULL DEFAULT 0,
	CONSTRAINT rsp202024_sequ_pk PRIMARY KEY (si115_sequencial)
);





CREATE TABLE public.tce102024 (
	si187_sequencial int8 NOT NULL DEFAULT 0,
	si187_tiporegistro int8 NOT NULL DEFAULT 0,
	si187_numprocessotce varchar(12) NOT NULL,
	si187_datainstauracaotce date NOT NULL,
	si187_codunidadesub varchar(8) NOT NULL,
	si187_nroconvenioconge varchar(30) NOT NULL,
	si187_dataassinaturaconvoriginalconge date NOT NULL,
	si187_dscinstrumelegaltce varchar(50) NOT NULL,
	si187_nrocpfautoridadeinstauratce varchar(11) NOT NULL,
	si187_dsccargoresptce varchar(50) NOT NULL,
	si187_vloriginaldano float8 NOT NULL DEFAULT 0,
	si187_vlatualizadodano float8 NOT NULL DEFAULT 0,
	si187_dataatualizacao date NOT NULL,
	si187_indice varchar(20) NOT NULL,
	si187_ocorrehipotese int8 NOT NULL DEFAULT 0,
	si187_identiresponsavel int8 NOT NULL DEFAULT 0,
	si187_mes int8 NOT NULL DEFAULT 0,
	si187_instit int8 NULL DEFAULT 0,
	CONSTRAINT tce102024_sequ_pk PRIMARY KEY (si187_sequencial)
);





CREATE TABLE public.terem102024 (
	si194_sequencial int8 NOT NULL DEFAULT 0,
	si194_tiporegistro int8 NOT NULL DEFAULT 0,
	si194_cnpj varchar(14) NULL,
	si194_codteto int8 NULL DEFAULT 0,
	si194_vlrparateto float8 NOT NULL DEFAULT 0,
	si194_tipocadastro int8 NOT NULL DEFAULT 0,
	si194_dtinicial date NOT NULL,
	si194_nrleiteto int8 NOT NULL DEFAULT 0,
	si194_dtpublicacaolei date NOT NULL,
	si194_dtfinal date NULL,
	si194_justalteracao varchar(250) NULL,
	si194_mes int8 NOT NULL DEFAULT 0,
	si194_inst int8 NULL DEFAULT 0,
	CONSTRAINT terem102024_sequ_pk PRIMARY KEY (si194_sequencial)
);





CREATE TABLE public.terem202024 (
	si196_sequencial int8 NOT NULL DEFAULT 0,
	si196_tiporegistro int8 NOT NULL DEFAULT 0,
	si196_codteto int8 NOT NULL DEFAULT 0,
	si196_vlrparateto float8 NOT NULL DEFAULT 0,
	si196_nrleiteto int8 NOT NULL DEFAULT 0,
	si196_dtpublicacaolei date NOT NULL,
	si196_justalteracaoteto varchar(250) NULL,
	si196_mes int8 NOT NULL DEFAULT 0,
	si196_inst int8 NULL DEFAULT 0,
	CONSTRAINT terem202024_sequ_pk PRIMARY KEY (si196_sequencial)
);





CREATE TABLE public.viap102024 (
	si198_sequencial int4 NOT NULL DEFAULT 0,
	si198_tiporegistro int4 NOT NULL DEFAULT 0,
	si198_nrocpfagentepublico varchar(11) NOT NULL,
	si198_codmatriculapessoa int4 NOT NULL DEFAULT 0,
	si198_codvinculopessoa int4 NOT NULL DEFAULT 0,
	si198_mes int4 NOT NULL DEFAULT 0,
	si198_instit int4 NULL DEFAULT 0,
	CONSTRAINT viap102024_sequ_pk PRIMARY KEY (si198_sequencial)
);





CREATE TABLE public.aberlic112024 (
	si47_sequencial int8 NOT NULL DEFAULT 0,
	si47_tiporegistro int8 NOT NULL DEFAULT 0,
	si47_codorgaoresp varchar(2) NOT NULL,
	si47_codunidadesubresp varchar(8) NOT NULL,
	si47_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si47_nroprocessolicitatorio varchar(12) NOT NULL,
	si47_nrolote int8 NOT NULL DEFAULT 0,
	si47_dsclote varchar(250) NOT NULL,
	si47_reg10 int8 NOT NULL DEFAULT 0,
	si47_mes int8 NOT NULL DEFAULT 0,
	si47_instit int8 NULL DEFAULT 0,
	CONSTRAINT aberlic112024_reg10_fk FOREIGN KEY (si47_reg10) REFERENCES public.aberlic102024(si46_sequencial)
);





CREATE TABLE public.aberlic122024 (
	si48_sequencial int8 NOT NULL DEFAULT 0,
	si48_tiporegistro int8 NOT NULL DEFAULT 0,
	si48_codorgaoresp varchar(2) NOT NULL,
	si48_codunidadesubresp varchar(8) NOT NULL,
	si48_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si48_nroprocessolicitatorio varchar(12) NOT NULL,
	si48_coditem int8 NOT NULL DEFAULT 0,
	si48_nroitem int8 NOT NULL DEFAULT 0,
	si48_reg10 int8 NOT NULL DEFAULT 0,
	si48_mes int8 NOT NULL DEFAULT 0,
	si48_instit int8 NULL DEFAULT 0,
	CONSTRAINT aberlic122024_reg10_fk FOREIGN KEY (si48_reg10) REFERENCES public.aberlic102024(si46_sequencial)
);





CREATE TABLE public.aberlic132024 (
	si49_sequencial int8 NOT NULL DEFAULT 0,
	si49_tiporegistro int8 NOT NULL DEFAULT 0,
	si49_codorgaoresp varchar(2) NOT NULL,
	si49_codunidadesubresp varchar(8) NOT NULL,
	si49_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si49_nroprocessolicitatorio varchar(12) NOT NULL,
	si49_nrolote int8 NOT NULL DEFAULT 0,
	si49_coditem int8 NOT NULL DEFAULT 0,
	si49_mes int8 NOT NULL DEFAULT 0,
	si49_reg10 int8 NOT NULL DEFAULT 0,
	si49_instit int8 NULL DEFAULT 0,
	CONSTRAINT aberlic132024_reg10_fk FOREIGN KEY (si49_reg10) REFERENCES public.aberlic102024(si46_sequencial)
);





CREATE TABLE public.aberlic142024 (
	si50_sequencial int8 NOT NULL DEFAULT 0,
	si50_tiporegistro int8 NOT NULL DEFAULT 0,
	si50_codorgaoresp varchar(2) NOT NULL,
	si50_codunidadesubresp varchar(8) NOT NULL,
	si50_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si50_nroprocessolicitatorio varchar(12) NOT NULL,
	si50_nrolote int8 NULL DEFAULT 0,
	si50_coditem int8 NOT NULL DEFAULT 0,
	si50_dtcotacao date NOT NULL,
	si50_vlrefpercentual float4 NOT NULL DEFAULT 0,
	si50_vlcotprecosunitario float8 NOT NULL DEFAULT 0,
	si50_quantidade float8 NOT NULL DEFAULT 0,
	si50_vlminalienbens float8 NOT NULL DEFAULT 0,
	si50_mes int8 NOT NULL DEFAULT 0,
	si50_reg10 int8 NOT NULL DEFAULT 0,
	si50_instit int8 NULL DEFAULT 0,
	CONSTRAINT aberlic142024_reg10_fk FOREIGN KEY (si50_reg10) REFERENCES public.aberlic102024(si46_sequencial)
);





CREATE TABLE public.aberlic152024 (
	si51_sequencial int8 NOT NULL DEFAULT 0,
	si51_tiporegistro int8 NOT NULL DEFAULT 0,
	si51_codorgaoresp varchar(2) NOT NULL,
	si51_codunidadesubresp varchar(8) NOT NULL,
	si51_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si51_nroprocessolicitatorio varchar(12) NOT NULL,
	si51_nrolote int8 NULL DEFAULT 0,
	si51_coditem int8 NOT NULL DEFAULT 0,
	si51_vlitem float8 NOT NULL DEFAULT 0,
	si51_mes int8 NOT NULL DEFAULT 0,
	si51_reg10 int8 NOT NULL DEFAULT 0,
	si51_instit int8 NULL DEFAULT 0,
	CONSTRAINT aberlic152024_reg10_fk FOREIGN KEY (si51_reg10) REFERENCES public.aberlic102024(si46_sequencial)
);





CREATE TABLE public.aberlic162024 (
	si52_sequencial int8 NOT NULL DEFAULT 0,
	si52_tiporegistro int8 NOT NULL DEFAULT 0,
	si52_codorgaoresp varchar(2) NOT NULL,
	si52_codunidadesubresp varchar(8) NOT NULL,
	si52_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si52_nroprocessolicitatorio varchar(12) NOT NULL,
	si52_codorgao varchar(2) NOT NULL,
	si52_codunidadesub varchar(8) NOT NULL,
	si52_codfuncao varchar(2) NOT NULL,
	si52_codsubfuncao varchar(3) NOT NULL,
	si52_codprograma varchar(4) NOT NULL,
	si52_idacao varchar(4) NOT NULL,
	si52_idsubacao varchar(4) NULL,
	si52_naturezadespesa int8 NOT NULL DEFAULT 0,
	si52_codfontrecursos int8 NOT NULL DEFAULT 0,
	si52_vlrecurso float8 NOT NULL DEFAULT 0,
	si52_mes int8 NOT NULL DEFAULT 0,
	si52_reg10 int8 NOT NULL DEFAULT 0,
	si52_instit int8 NULL DEFAULT 0,
	CONSTRAINT aberlic162024_reg10_fk FOREIGN KEY (si52_reg10) REFERENCES public.aberlic102024(si46_sequencial)
);





CREATE TABLE public.alq112024 (
	si122_sequencial int8 NOT NULL DEFAULT 0,
	si122_tiporegistro int8 NOT NULL DEFAULT 0,
	si122_codreduzido int8 NOT NULL DEFAULT 0,
	si122_codfontrecursos int8 NOT NULL DEFAULT 0,
	si122_valoranuladofonte float8 NOT NULL DEFAULT 0,
	si122_mes int8 NOT NULL DEFAULT 0,
	si122_reg10 int8 NOT NULL DEFAULT 0,
	si122_instit int8 NULL DEFAULT 0,
	si122_codco varchar(4) NULL DEFAULT '0000'::character varying,
	CONSTRAINT alq112024_sequ_pk PRIMARY KEY (si122_sequencial),
	CONSTRAINT alq112024_reg10_fk FOREIGN KEY (si122_reg10) REFERENCES public.alq102024(si121_sequencial)
);
CREATE INDEX alq112024_si122_reg10_index ON public.alq112024 USING btree (si122_reg10);





CREATE TABLE public.alq122024 (
	si123_sequencial int8 NOT NULL DEFAULT 0,
	si123_tiporegistro int8 NOT NULL DEFAULT 0,
	si123_codreduzido int8 NOT NULL DEFAULT 0,
	si123_mescompetencia varchar(2) NOT NULL,
	si123_exerciciocompetencia int8 NOT NULL DEFAULT 0,
	si123_vlanuladodspexerant float8 NOT NULL DEFAULT 0,
	si123_mes int8 NOT NULL DEFAULT 0,
	si123_reg10 int8 NOT NULL DEFAULT 0,
	si123_instit int8 NULL DEFAULT 0,
	CONSTRAINT alq122024_sequ_pk PRIMARY KEY (si123_sequencial),
	CONSTRAINT alq122024_reg10_fk FOREIGN KEY (si123_reg10) REFERENCES public.alq102024(si121_sequencial)
);
CREATE INDEX alq122024_si123_reg10_index ON public.alq122024 USING btree (si123_reg10);





CREATE TABLE public.anl112024 (
	si111_sequencial int8 NOT NULL DEFAULT 0,
	si111_tiporegistro int8 NOT NULL DEFAULT 0,
	si111_codunidadesub varchar(8) NOT NULL,
	si111_nroempenho int8 NOT NULL DEFAULT 0,
	si111_nroanulacao int8 NOT NULL DEFAULT 0,
	si111_codfontrecursos int8 NOT NULL DEFAULT 0,
	si111_vlanulacaofonte float8 NOT NULL DEFAULT 0,
	si111_mes int8 NOT NULL DEFAULT 0,
	si111_reg10 int8 NOT NULL DEFAULT 0,
	si111_instit int8 NULL DEFAULT 0,
	si111_codco varchar(4) NULL DEFAULT NULL::character varying,
	CONSTRAINT anl112024_sequ_pk PRIMARY KEY (si111_sequencial),
	CONSTRAINT anl112024_reg10_fk FOREIGN KEY (si111_reg10) REFERENCES public.anl102024(si110_sequencial)
);
CREATE INDEX anl112024_si111_reg10_index ON public.anl112024 USING btree (si111_reg10);





CREATE TABLE public.aob112024 (
	si142_sequencial int8 NOT NULL DEFAULT 0,
	si142_tiporegistro int8 NOT NULL DEFAULT 0,
	si142_codreduzido int8 NOT NULL DEFAULT 0,
	si142_codfontrecursos int8 NOT NULL DEFAULT 0,
	si142_valoranulacaofonte float8 NOT NULL DEFAULT 0,
	si142_mes int8 NOT NULL DEFAULT 0,
	si142_reg10 int8 NOT NULL DEFAULT 0,
	si142_instit int8 NULL DEFAULT 0,
	CONSTRAINT aob112024_sequ_pk PRIMARY KEY (si142_sequencial),
	CONSTRAINT aob112024_reg10_fk FOREIGN KEY (si142_reg10) REFERENCES public.aob102024(si141_sequencial)
);
CREATE INDEX aob112024_si142_reg10_index ON public.aob112024 USING btree (si142_reg10);





CREATE TABLE public.aoc112024 (
	si39_sequencial int8 NOT NULL DEFAULT 0,
	si39_tiporegistro int8 NOT NULL DEFAULT 0,
	si39_codreduzidodecreto int8 NOT NULL DEFAULT 0,
	si39_nrodecreto varchar(8) NOT NULL DEFAULT 0,
	si39_tipodecretoalteracao int8 NOT NULL DEFAULT 0,
	si39_valoraberto float8 NOT NULL DEFAULT 0,
	si39_mes int8 NOT NULL DEFAULT 0,
	si39_reg10 int8 NOT NULL DEFAULT 0,
	si39_instit int8 NULL DEFAULT 0,
	si39_justificativa text NULL,
	CONSTRAINT aoc112024_sequ_pk PRIMARY KEY (si39_sequencial),
	CONSTRAINT aoc112024_reg10_fk FOREIGN KEY (si39_reg10) REFERENCES public.aoc102024(si38_sequencial)
);
CREATE INDEX aoc112024_si39_reg10_index ON public.aoc112024 USING btree (si39_reg10);





CREATE TABLE public.aoc122024 (
	si40_sequencial int8 NOT NULL DEFAULT 0,
	si40_tiporegistro int8 NOT NULL DEFAULT 0,
	si40_codreduzidodecreto int8 NOT NULL DEFAULT 0,
	si40_nroleialteracao varchar(6) NOT NULL,
	si40_dataleialteracao date NULL,
	si40_tpleiorigdecreto varchar(4) NOT NULL,
	si40_tipoleialteracao int8 NULL DEFAULT 0,
	si40_valorabertolei float8 NULL,
	si40_mes int8 NOT NULL DEFAULT 0,
	si40_reg10 int8 NOT NULL DEFAULT 0,
	si40_instit int8 NULL DEFAULT 0,
	CONSTRAINT aoc122024_sequ_pk PRIMARY KEY (si40_sequencial),
	CONSTRAINT aoc122024_reg10_fk FOREIGN KEY (si40_reg10) REFERENCES public.aoc102024(si38_sequencial)
);
CREATE INDEX aoc122024_si40_reg10_index ON public.aoc122024 USING btree (si40_reg10);





CREATE TABLE public.aoc132024 (
	si41_sequencial int8 NOT NULL DEFAULT 0,
	si41_tiporegistro int8 NOT NULL DEFAULT 0,
	si41_codreduzidodecreto int8 NOT NULL DEFAULT 0,
	si41_origemrecalteracao varchar(2) NOT NULL,
	si41_valorabertoorigem float8 NOT NULL DEFAULT 0,
	si41_mes int8 NOT NULL DEFAULT 0,
	si41_reg10 int8 NOT NULL DEFAULT 0,
	si41_instit int8 NULL DEFAULT 0,
	CONSTRAINT aoc132024_sequ_pk PRIMARY KEY (si41_sequencial),
	CONSTRAINT aoc132024_reg10_fk FOREIGN KEY (si41_reg10) REFERENCES public.aoc102024(si38_sequencial)
);
CREATE INDEX aoc132024_si41_reg10_index ON public.aoc132024 USING btree (si41_reg10);





CREATE TABLE public.aoc142024 (
	si42_sequencial int8 NOT NULL DEFAULT 0,
	si42_tiporegistro int8 NOT NULL DEFAULT 0,
	si42_codreduzidodecreto int8 NOT NULL DEFAULT 0,
	si42_origemrecalteracao varchar(2) NOT NULL,
	si42_codorigem int8 NULL DEFAULT 0,
	si42_codorgao varchar(2) NOT NULL,
	si42_codunidadesub varchar(8) NOT NULL,
	si42_codfuncao varchar(2) NOT NULL,
	si42_codsubfuncao varchar(3) NOT NULL,
	si42_codprograma varchar(4) NOT NULL,
	si42_idacao varchar(4) NOT NULL,
	si42_idsubacao varchar(4) NULL,
	si42_naturezadespesa int8 NOT NULL DEFAULT 0,
	si42_codfontrecursos int8 NOT NULL DEFAULT 0,
	si42_vlacrescimo float8 NOT NULL DEFAULT 0,
	si42_mes int8 NOT NULL DEFAULT 0,
	si42_reg10 int8 NOT NULL DEFAULT 0,
	si42_instit int8 NULL DEFAULT 0,
	CONSTRAINT aoc142024_sequ_pk PRIMARY KEY (si42_sequencial),
	CONSTRAINT aoc142024_reg10_fk FOREIGN KEY (si42_reg10) REFERENCES public.aoc102024(si38_sequencial)
);
CREATE INDEX aoc142024_si42_reg10_index ON public.aoc142024 USING btree (si42_reg10);





CREATE TABLE public.aoc152024 (
	si194_sequencial int8 NOT NULL DEFAULT 0,
	si194_tiporegistro int8 NOT NULL DEFAULT 0,
	si194_codreduzidodecreto int8 NOT NULL DEFAULT 0,
	si194_origemrecalteracao varchar(2) NOT NULL,
	si194_codorigem int8 NOT NULL DEFAULT 0,
	si194_codorgao varchar(2) NOT NULL,
	si194_codunidadesub varchar(8) NOT NULL,
	si194_codfuncao varchar(2) NOT NULL,
	si194_codsubfuncao varchar(3) NOT NULL,
	si194_codprograma varchar(4) NOT NULL,
	si194_idacao varchar(4) NOT NULL,
	si194_idsubacao varchar(4) NULL DEFAULT NULL::character varying,
	si194_naturezadespesa int8 NOT NULL DEFAULT 0,
	si194_codfontrecursos int8 NOT NULL DEFAULT 0,
	si194_vlreducao float8 NOT NULL DEFAULT 0,
	si194_mes int8 NOT NULL DEFAULT 0,
	si194_reg10 int8 NOT NULL DEFAULT 0,
	si194_instit int8 NOT NULL DEFAULT 0,
	CONSTRAINT aoc152024_sequ_pk PRIMARY KEY (si194_sequencial),
	CONSTRAINT aoc152024_reg10_fk FOREIGN KEY (si194_reg10) REFERENCES public.aoc102024(si38_sequencial)
);
CREATE INDEX aoc152024_si194_reg10_index ON public.aoc152024 USING btree (si194_reg10);





CREATE TABLE public.aop112024 (
	si138_sequencial int8 NOT NULL DEFAULT 0,
	si138_tiporegistro int8 NOT NULL DEFAULT 0,
	si138_codreduzido int8 NOT NULL DEFAULT 0,
	si138_tipopagamento int8 NOT NULL DEFAULT 0,
	si138_nroempenho int8 NOT NULL DEFAULT 0,
	si138_dtempenho date NOT NULL,
	si138_nroliquidacao int8 NULL DEFAULT 0,
	si138_dtliquidacao date NULL,
	si138_codfontrecursos int8 NOT NULL DEFAULT 0,
	si138_valoranulacaofonte float8 NOT NULL DEFAULT 0,
	si138_mes int8 NOT NULL DEFAULT 0,
	si138_reg10 int8 NOT NULL DEFAULT 0,
	si138_instit int8 NULL DEFAULT 0,
	si138_codco int8 NOT NULL DEFAULT 0,
	CONSTRAINT aop112024_sequ_pk PRIMARY KEY (si138_sequencial),
	CONSTRAINT aop112024_reg10_fk FOREIGN KEY (si138_reg10) REFERENCES public.aop102024(si137_sequencial)
);
CREATE INDEX aop112024_si138_reg10_index ON public.aop112024 USING btree (si138_reg10);





CREATE TABLE public.aop122024 (
	si139_sequencial int8 NOT NULL DEFAULT 0,
	si139_tiporegistro int8 NOT NULL DEFAULT 0,
	si139_codreduzido int8 NOT NULL DEFAULT 0,
	si139_tipodocumento varchar(2) NOT NULL,
	si139_nrodocumento varchar(15) NULL,
	si139_codctb int8 NULL DEFAULT 0,
	si139_codfontectb int8 NULL DEFAULT 0,
	si139_desctipodocumentoop varchar(50) NULL,
	si139_dtemissao date NOT NULL,
	si139_vldocumento float8 NOT NULL DEFAULT 0,
	si139_mes int8 NOT NULL DEFAULT 0,
	si139_reg10 int8 NOT NULL DEFAULT 0,
	si139_instit int4 NULL DEFAULT 0,
	CONSTRAINT aop122024_pkey PRIMARY KEY (si139_sequencial),
	CONSTRAINT aop122024_reg10_fk FOREIGN KEY (si139_reg10) REFERENCES public.aop102024(si137_sequencial)
);





CREATE TABLE public.aop132024 (
	si140_sequencial int8 NOT NULL DEFAULT 0,
	si140_tiporegistro int8 NOT NULL DEFAULT 0,
	si140_codreduzidoop int8 NOT NULL DEFAULT 0,
	si140_tiporetencao varchar(4) NOT NULL,
	si140_descricaoretencao varchar(50) NULL,
	si140_vlretencao float8 NOT NULL DEFAULT 0,
	si140_vlantecipado float8 NOT NULL DEFAULT 0,
	si140_mes int8 NOT NULL DEFAULT 0,
	si140_reg10 int8 NOT NULL DEFAULT 0,
	si140_instit int8 NULL DEFAULT 0,
	CONSTRAINT aop132024_pkey PRIMARY KEY (si140_sequencial),
	CONSTRAINT aop132024_reg10_fk FOREIGN KEY (si140_reg10) REFERENCES public.aop102024(si137_sequencial)
);





CREATE TABLE public.arc112024 (
	si29_sequencial int8 NOT NULL DEFAULT 0,
	si29_tiporegistro int8 NOT NULL DEFAULT 0,
	si29_codcorrecao int8 NOT NULL DEFAULT 0,
	si29_codfontereduzida int8 NOT NULL DEFAULT 0,
	si29_tipodocumento int8 NULL DEFAULT 0,
	si29_nrodocumento varchar(14) NULL,
	si29_nroconvenio varchar(30) NULL,
	si29_dataassinatura varchar(8) NULL,
	si29_vlreduzidofonte float8 NOT NULL DEFAULT 0,
	si29_reg10 int8 NOT NULL DEFAULT 0,
	si29_mes int8 NOT NULL DEFAULT 0,
	si29_instit int8 NULL DEFAULT 0,
	CONSTRAINT arc112024_sequ_pk PRIMARY KEY (si29_sequencial),
	CONSTRAINT arc112024_reg10_fk FOREIGN KEY (si29_reg10) REFERENCES public.arc102024(si28_sequencial)
);
CREATE INDEX arc112024_si15_reg10_index ON public.arc112024 USING btree (si29_reg10);





CREATE TABLE public.arc122024 (
	si30_sequencial int8 NOT NULL DEFAULT 0,
	si30_tiporegistro int8 NOT NULL DEFAULT 0,
	si30_codcorrecao int8 NOT NULL DEFAULT 0,
	si30_codfonteacrescida int8 NOT NULL DEFAULT 0,
	si30_tipodocumento int8 NULL DEFAULT 0,
	si30_nrodocumento varchar(14) NULL,
	si30_nroconvenio varchar(30) NULL,
	si30_datassinatura date NULL,
	si30_vlacrescidofonte float8 NOT NULL DEFAULT 0,
	si30_reg10 int8 NOT NULL DEFAULT 0,
	si30_mes int8 NOT NULL DEFAULT 0,
	si30_instit int8 NULL DEFAULT 0,
	CONSTRAINT arc122024_sequ_pk PRIMARY KEY (si30_sequencial),
	CONSTRAINT arc122024_reg10_fk FOREIGN KEY (si30_reg10) REFERENCES public.arc102024(si28_sequencial)
);
CREATE INDEX arc122024_si30_reg10_index ON public.arc122024 USING btree (si30_reg10);





CREATE TABLE public.arc212024 (
	si32_sequencial int8 NOT NULL DEFAULT 0,
	si32_tiporegistro int8 NOT NULL DEFAULT 0,
	si32_codestorno int8 NOT NULL DEFAULT 0,
	si32_codfonteestornada int8 NOT NULL DEFAULT 0,
	si32_tipodocumento int8 NULL,
	si32_nrodocumento varchar(14) NULL DEFAULT NULL::character varying,
	si32_nroconvenio varchar(30) NULL DEFAULT NULL::character varying,
	si32_dataassinatura date NULL,
	si32_vlestornadofonte float8 NOT NULL DEFAULT 0,
	si32_reg20 int8 NOT NULL DEFAULT 0,
	si32_instit int8 NULL DEFAULT 0,
	si32_mes int8 NOT NULL,
	si32_nrocontratoop varchar(30) NULL,
	si32_dataassinaturacontratoop date NULL,
	si32_codigocontroleorcamentario varchar(4) NULL DEFAULT '0000'::character varying,
	CONSTRAINT arc212024_sequ_pk PRIMARY KEY (si32_sequencial),
	CONSTRAINT arc212024_reg20_fk FOREIGN KEY (si32_reg20) REFERENCES public.arc202024(si31_sequencial)
);
CREATE INDEX arcwq2024_si32_reg20_index ON public.arc212024 USING btree (si32_reg20);





CREATE TABLE public.balancete112024 (
	si178_sequencial int8 NOT NULL DEFAULT 0,
	si178_tiporegistro int8 NOT NULL DEFAULT 0,
	si178_contacontaabil int8 NOT NULL DEFAULT 0,
	si178_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si178_codorgao varchar(2) NOT NULL,
	si178_codunidadesub varchar(8) NOT NULL,
	si178_codfuncao varchar(2) NOT NULL,
	si178_codsubfuncao varchar(3) NOT NULL,
	si178_codprograma varchar(4) NULL,
	si178_idacao varchar(4) NOT NULL,
	si178_idsubacao varchar(4) NOT NULL,
	si178_naturezadespesa int8 NOT NULL DEFAULT 0,
	si178_codfontrecursos int8 NOT NULL DEFAULT 0,
	si178_saldoinicialcd float8 NOT NULL DEFAULT 0,
	si178_naturezasaldoinicialcd varchar(1) NOT NULL,
	si178_totaldebitoscd float8 NOT NULL DEFAULT 0,
	si178_totalcreditoscd float8 NOT NULL DEFAULT 0,
	si178_saldofinalcd float8 NOT NULL DEFAULT 0,
	si178_naturezasaldofinalcd varchar(1) NOT NULL,
	si178_mes int8 NOT NULL DEFAULT 0,
	si178_instit int8 NULL DEFAULT 0,
	si178_reg10 int8 NOT NULL,
	CONSTRAINT balancete112024_sequ_pk PRIMARY KEY (si178_sequencial),
	CONSTRAINT fk_balancete112024_reg10_fk FOREIGN KEY (si178_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete122024 (
	si179_sequencial int8 NOT NULL DEFAULT 0,
	si179_tiporegistro int8 NOT NULL DEFAULT 0,
	si179_contacontabil int8 NOT NULL DEFAULT 0,
	si179_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si179_naturezareceita int8 NOT NULL DEFAULT 0,
	si179_codfontrecursos int8 NOT NULL DEFAULT 0,
	si179_saldoinicialcr float8 NOT NULL DEFAULT 0,
	si179_naturezasaldoinicialcr varchar(1) NOT NULL,
	si179_totaldebitoscr float8 NOT NULL DEFAULT 0,
	si179_totalcreditoscr float8 NOT NULL DEFAULT 0,
	si179_saldofinalcr float8 NOT NULL DEFAULT 0,
	si179_naturezasaldofinalcr varchar(1) NOT NULL,
	si179_mes int8 NOT NULL DEFAULT 0,
	si179_instit int8 NULL DEFAULT 0,
	si179_reg10 int8 NOT NULL,
	CONSTRAINT balancete122024_sequ_pk PRIMARY KEY (si179_sequencial),
	CONSTRAINT fk_balancete122024_reg10_fk FOREIGN KEY (si179_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete132024 (
	si180_sequencial int8 NOT NULL DEFAULT 0,
	si180_tiporegistro int8 NOT NULL DEFAULT 0,
	si180_contacontabil int8 NOT NULL DEFAULT 0,
	si180_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si180_codprograma varchar(4) NOT NULL,
	si180_idacao varchar(4) NOT NULL,
	si180_idsubacao varchar(4) NULL,
	si180_saldoinicialpa float8 NOT NULL DEFAULT 0,
	si180_naturezasaldoinicialpa varchar(1) NOT NULL,
	si180_totaldebitospa float8 NOT NULL DEFAULT 0,
	si180_totalcreditospa float8 NOT NULL DEFAULT 0,
	si180_saldofinalpa float8 NOT NULL DEFAULT 0,
	si180_naturezasaldofinalpa varchar(1) NOT NULL,
	si180_mes int8 NOT NULL DEFAULT 0,
	si180_instit int8 NULL DEFAULT 0,
	si180_reg10 int8 NOT NULL,
	CONSTRAINT balancete132024_sequ_pk PRIMARY KEY (si180_sequencial),
	CONSTRAINT fk_balancete132024_reg10_fk FOREIGN KEY (si180_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete142024 (
	si181_sequencial int8 NOT NULL DEFAULT 0,
	si181_tiporegistro int8 NOT NULL DEFAULT 0,
	si181_contacontabil int8 NOT NULL DEFAULT 0,
	si181_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si181_codorgao varchar(2) NOT NULL,
	si181_codunidadesub varchar(8) NOT NULL,
	si181_codunidadesuborig varchar(8) NOT NULL,
	si181_codfuncao varchar(2) NOT NULL,
	si181_codsubfuncao varchar(3) NOT NULL,
	si181_codprograma varchar(4) NOT NULL,
	si181_idacao varchar(4) NOT NULL,
	si181_idsubacao varchar(4) NULL,
	si181_naturezadespesa int8 NOT NULL DEFAULT 0,
	si181_subelemento varchar(2) NOT NULL,
	si181_codfontrecursos int8 NOT NULL DEFAULT 0,
	si181_codco int8 NOT NULL DEFAULT 0,
	si181_nroempenho int8 NOT NULL DEFAULT 0,
	si181_anoinscricao int8 NOT NULL DEFAULT 0,
	si181_saldoinicialrsp float8 NOT NULL DEFAULT 0,
	si181_naturezasaldoinicialrsp varchar(1) NOT NULL,
	si181_totaldebitosrsp float8 NOT NULL DEFAULT 0,
	si181_totalcreditosrsp float8 NOT NULL DEFAULT 0,
	si181_saldofinalrsp float8 NOT NULL DEFAULT 0,
	si181_naturezasaldofinalrsp varchar(1) NOT NULL,
	si181_mes int8 NOT NULL DEFAULT 0,
	si181_instit int8 NULL DEFAULT 0,
	si181_reg10 int8 NOT NULL,
	CONSTRAINT balancete142024_sequ_pk PRIMARY KEY (si181_sequencial),
	CONSTRAINT fk_balancete142024_reg10_fk FOREIGN KEY (si181_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete152024 (
	si182_sequencial int8 NOT NULL DEFAULT 0,
	si182_tiporegistro int8 NOT NULL DEFAULT 0,
	si182_contacontabil int8 NOT NULL DEFAULT 0,
	si182_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si182_atributosf varchar(1) NOT NULL,
	si182_saldoinicialsf float8 NOT NULL DEFAULT 0,
	si182_naturezasaldoinicialsf varchar(1) NOT NULL,
	si182_totaldebitossf float8 NOT NULL DEFAULT 0,
	si182_totalcreditossf float8 NOT NULL DEFAULT 0,
	si182_saldofinalsf float8 NOT NULL DEFAULT 0,
	si182_naturezasaldofinalsf varchar(1) NOT NULL,
	si182_mes int8 NOT NULL DEFAULT 0,
	si182_instit int8 NULL DEFAULT 0,
	si182_reg10 int8 NOT NULL,
	CONSTRAINT balancete152024_sequ_pk PRIMARY KEY (si182_sequencial),
	CONSTRAINT fk_balancete152024_reg10_fk FOREIGN KEY (si182_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete162024 (
	si183_sequencial int8 NOT NULL DEFAULT 0,
	si183_tiporegistro int8 NOT NULL DEFAULT 0,
	si183_contacontabil int8 NOT NULL DEFAULT 0,
	si183_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si183_atributosf varchar(1) NOT NULL,
	si183_codfontrecursos int8 NULL DEFAULT 0,
	si183_codco int8 NULL DEFAULT 0,
	si183_saldoinicialfontsf float8 NOT NULL DEFAULT 0,
	si183_naturezasaldoinicialfontsf varchar(1) NOT NULL,
	si183_totaldebitosfontsf float8 NOT NULL DEFAULT 0,
	si183_totalcreditosfontsf float8 NOT NULL DEFAULT 0,
	si183_saldofinalfontsf float8 NOT NULL DEFAULT 0,
	si183_naturezasaldofinalfontsf varchar(1) NOT NULL,
	si183_mes int8 NOT NULL DEFAULT 0,
	si183_instit int8 NULL DEFAULT 0,
	si183_reg10 int8 NOT NULL,
	CONSTRAINT balancete162024_sequ_pk PRIMARY KEY (si183_sequencial),
	CONSTRAINT fk_balancete162024_reg10_fk FOREIGN KEY (si183_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete172024 (
	si184_sequencial int8 NOT NULL DEFAULT 0,
	si184_tiporegistro int8 NOT NULL DEFAULT 0,
	si184_contacontabil int8 NOT NULL DEFAULT 0,
	si184_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si184_atributosf varchar(1) NOT NULL,
	si184_codctb int8 NOT NULL DEFAULT 0,
	si184_codfontrecursos int8 NOT NULL DEFAULT 0,
	si184_codco int8 NOT NULL DEFAULT 0,
	si184_saldoinicialctb float8 NOT NULL DEFAULT 0,
	si184_naturezasaldoinicialctb varchar(1) NOT NULL,
	si184_totaldebitosctb float8 NOT NULL DEFAULT 0,
	si184_totalcreditosctb float8 NOT NULL DEFAULT 0,
	si184_saldofinalctb float8 NOT NULL DEFAULT 0,
	si184_naturezasaldofinalctb varchar(1) NOT NULL,
	si184_mes int8 NOT NULL DEFAULT 0,
	si184_instit int8 NULL DEFAULT 0,
	si184_reg10 int8 NOT NULL,
	CONSTRAINT balancete172024_sequ_pk PRIMARY KEY (si184_sequencial),
	CONSTRAINT fk_balancete172024_reg10_fk FOREIGN KEY (si184_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete182024 (
	si185_sequencial int8 NOT NULL DEFAULT 0,
	si185_tiporegistro int8 NOT NULL DEFAULT 0,
	si185_contacontabil int8 NOT NULL DEFAULT 0,
	si185_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si185_codfontrecursos int8 NOT NULL DEFAULT 0,
	si185_saldoinicialfr float8 NOT NULL DEFAULT 0,
	si185_naturezasaldoinicialfr varchar(1) NOT NULL,
	si185_totaldebitosfr float8 NOT NULL DEFAULT 0,
	si185_totalcreditosfr float8 NOT NULL DEFAULT 0,
	si185_saldofinalfr float8 NOT NULL DEFAULT 0,
	si185_naturezasaldofinalfr varchar(1) NOT NULL,
	si185_mes int8 NOT NULL DEFAULT 0,
	si185_instit int8 NULL DEFAULT 0,
	si185_reg10 int8 NOT NULL,
	CONSTRAINT balancete182024_sequ_pk PRIMARY KEY (si185_sequencial),
	CONSTRAINT fk_balancete182024_reg10_fk FOREIGN KEY (si185_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete192024 (
	si186_sequencial int8 NOT NULL DEFAULT 0,
	si186_tiporegistro int8 NOT NULL DEFAULT 0,
	si186_contacontabil int8 NOT NULL DEFAULT 0,
	si186_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si186_cnpjconsorcio int8 NOT NULL DEFAULT 0,
	si186_saldoinicialconsor float8 NOT NULL DEFAULT 0,
	si186_naturezasaldoinicialconsor varchar(1) NOT NULL,
	si186_totaldebitosconsor float8 NOT NULL DEFAULT 0,
	si186_totalcreditosconsor float8 NOT NULL DEFAULT 0,
	si186_saldofinalconsor float8 NOT NULL DEFAULT 0,
	si186_naturezasaldofinalconsor varchar(1) NOT NULL,
	si186_mes int8 NOT NULL DEFAULT 0,
	si186_instit int8 NULL DEFAULT 0,
	si186_reg10 int8 NOT NULL,
	CONSTRAINT balancete192024_sequ_pk PRIMARY KEY (si186_sequencial),
	CONSTRAINT fk_balancete192024_reg10_fk FOREIGN KEY (si186_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete202024 (
	si187_sequencial int8 NOT NULL DEFAULT 0,
	si187_tiporegistro int8 NOT NULL DEFAULT 0,
	si187_contacontabil int8 NOT NULL DEFAULT 0,
	si187_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si187_cnpjconsorcio int8 NOT NULL DEFAULT 0,
	si187_tiporecurso int4 NOT NULL DEFAULT 0,
	si187_codfuncao varchar(2) NOT NULL,
	si187_codsubfuncao varchar(3) NOT NULL,
	si187_naturezadespesa int8 NOT NULL DEFAULT 0,
	si187_subelemento varchar(2) NOT NULL,
	si187_codfontrecursos int8 NOT NULL DEFAULT 0,
	si187_saldoinicialconscf float8 NOT NULL DEFAULT 0,
	si187_naturezasaldoinicialconscf varchar(1) NOT NULL,
	si187_totaldebitosconscf float8 NOT NULL DEFAULT 0,
	si187_totalcreditosconscf float8 NOT NULL DEFAULT 0,
	si187_saldofinalconscf float8 NOT NULL DEFAULT 0,
	si187_naturezasaldofinalconscf varchar(1) NOT NULL,
	si187_mes int8 NOT NULL DEFAULT 0,
	si187_instit int8 NULL DEFAULT 0,
	si187_reg10 int8 NOT NULL,
	CONSTRAINT balancete202024_sequ_pk PRIMARY KEY (si187_sequencial),
	CONSTRAINT fk_balancete202024_reg10_fk FOREIGN KEY (si187_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete212024 (
	si188_sequencial int8 NOT NULL DEFAULT 0,
	si188_tiporegistro int8 NOT NULL DEFAULT 0,
	si188_contacontabil int8 NOT NULL DEFAULT 0,
	si188_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si188_cnpjconsorcio int8 NOT NULL DEFAULT 0,
	si188_codfontrecursos int8 NOT NULL DEFAULT 0,
	si188_saldoinicialconsorfr float8 NOT NULL DEFAULT 0,
	si188_naturezasaldoinicialconsorfr varchar(1) NOT NULL,
	si188_totaldebitosconsorfr float8 NOT NULL DEFAULT 0,
	si188_totalcreditosconsorfr float8 NOT NULL DEFAULT 0,
	si188_saldofinalconsorfr float8 NOT NULL DEFAULT 0,
	si188_naturezasaldofinalconsorfr varchar(1) NOT NULL,
	si188_mes int8 NOT NULL DEFAULT 0,
	si188_instit int8 NULL DEFAULT 0,
	si188_reg10 int8 NOT NULL,
	CONSTRAINT balancete212024_sequ_pk PRIMARY KEY (si188_sequencial),
	CONSTRAINT fk_balancete212024_reg10_fk FOREIGN KEY (si188_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete222024 (
	si189_sequencial int8 NOT NULL DEFAULT 0,
	si189_tiporegistro int8 NOT NULL DEFAULT 0,
	si189_contacontabil int8 NOT NULL DEFAULT 0,
	si189_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si189_atributosf varchar(1) NOT NULL,
	si189_codctb int8 NOT NULL DEFAULT 0,
	si189_saldoinicialctbsf float8 NOT NULL DEFAULT 0,
	si189_naturezasaldoinicialctbsf varchar(1) NOT NULL,
	si189_totaldebitosctbsf float8 NOT NULL DEFAULT 0,
	si189_totalcreditosctbsf float8 NOT NULL DEFAULT 0,
	si189_saldofinalctbsf float8 NOT NULL DEFAULT 0,
	si189_naturezasaldofinalctbsf varchar(1) NOT NULL,
	si189_mes int8 NOT NULL DEFAULT 0,
	si189_instit int8 NULL DEFAULT 0,
	si189_reg10 int8 NOT NULL,
	CONSTRAINT balancete222024_sequ_pk PRIMARY KEY (si189_sequencial),
	CONSTRAINT fk_balancete222024_si77_sequencial FOREIGN KEY (si189_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete232024 (
	si190_sequencial int8 NOT NULL DEFAULT 0,
	si190_tiporegistro int8 NOT NULL DEFAULT 0,
	si190_contacontabil int8 NOT NULL DEFAULT 0,
	si190_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si190_naturezareceita int8 NOT NULL DEFAULT 0,
	si190_saldoinicialnatreceita float8 NOT NULL DEFAULT 0,
	si190_naturezasaldoinicialnatreceita varchar(1) NOT NULL,
	si190_totaldebitosnatreceita float8 NOT NULL DEFAULT 0,
	si190_totalcreditosnatreceita float8 NOT NULL DEFAULT 0,
	si190_saldofinalnatreceita float8 NOT NULL DEFAULT 0,
	si190_naturezasaldofinalnatreceita varchar(1) NOT NULL,
	si190_mes int8 NOT NULL DEFAULT 0,
	si190_instit int8 NULL DEFAULT 0,
	si190_reg10 int8 NOT NULL,
	CONSTRAINT balancete232024_sequ_pk PRIMARY KEY (si190_sequencial),
	CONSTRAINT fk_balancete232024_reg10_fk FOREIGN KEY (si190_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete242024 (
	si191_sequencial int8 NOT NULL DEFAULT 0,
	si191_tiporegistro int8 NOT NULL DEFAULT 0,
	si191_contacontabil int8 NOT NULL DEFAULT 0,
	si191_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si191_codorgao varchar(2) NOT NULL,
	si191_codunidadesub varchar(8) NOT NULL,
	si191_saldoinicialorgao float8 NOT NULL DEFAULT 0,
	si191_naturezasaldoinicialorgao varchar(1) NOT NULL,
	si191_totaldebitosorgao float8 NOT NULL DEFAULT 0,
	si191_totalcreditosorgao float8 NOT NULL DEFAULT 0,
	si191_saldofinalorgao float8 NOT NULL DEFAULT 0,
	si191_naturezasaldofinalorgao varchar(1) NOT NULL,
	si191_mes int8 NOT NULL DEFAULT 0,
	si191_instit int8 NULL DEFAULT 0,
	si191_reg10 int8 NOT NULL,
	CONSTRAINT balancete242024_sequ_pk PRIMARY KEY (si191_sequencial),
	CONSTRAINT fk_balancete242024_reg10_fk FOREIGN KEY (si191_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete252024 (
	si195_sequencial int8 NOT NULL DEFAULT 0,
	si195_tiporegistro int8 NOT NULL DEFAULT 0,
	si195_contacontabil int8 NOT NULL DEFAULT 0,
	si195_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si195_atributosf varchar(1) NOT NULL,
	si195_naturezareceita int8 NOT NULL DEFAULT 0,
	si195_saldoinicialnrsf float8 NOT NULL DEFAULT 0,
	si195_naturezasaldoinicialnrsf varchar(1) NOT NULL,
	si195_totaldebitosnrsf float8 NOT NULL DEFAULT 0,
	si195_totalcreditosnrsf float8 NOT NULL DEFAULT 0,
	si195_saldofinalnrsf float8 NOT NULL DEFAULT 0,
	si195_naturezasaldofinalnrsf varchar(1) NOT NULL,
	si195_mes int8 NOT NULL DEFAULT 0,
	si195_instit int8 NULL DEFAULT 0,
	si195_reg10 int8 NOT NULL,
	CONSTRAINT balancete252024_sequ_pk PRIMARY KEY (si195_sequencial),
	CONSTRAINT fk_balancete102024_reg10_fk FOREIGN KEY (si195_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete262024 (
	si196_sequencial int8 NOT NULL DEFAULT 0,
	si196_tiporegistro int8 NOT NULL DEFAULT 0,
	si196_contacontabil int8 NOT NULL DEFAULT 0,
	si196_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si196_tipodocumentopessoaatributosf int8 NOT NULL,
	si196_nrodocumentopessoaatributosf varchar(14) NOT NULL,
	si196_atributosf varchar(1) NOT NULL,
	si196_saldoinicialpessoaatributosf float8 NOT NULL DEFAULT 0,
	si196_naturezasaldoinicialpessoaatributosf varchar(1) NOT NULL,
	si196_totaldebitospessoaatributosf float8 NOT NULL DEFAULT 0,
	si196_totalcreditospessoaatributosf float8 NOT NULL DEFAULT 0,
	si196_saldofinalpessoaatributosf float8 NOT NULL DEFAULT 0,
	si196_naturezasaldofinalpessoaatributosf varchar(1) NOT NULL,
	si196_mes int8 NOT NULL DEFAULT 0,
	si196_instit int8 NULL DEFAULT 0,
	si196_reg10 int8 NOT NULL,
	CONSTRAINT balancete262024_sequ_pk PRIMARY KEY (si196_sequencial),
	CONSTRAINT fk_balancete102024_reg10_fk FOREIGN KEY (si196_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete272024 (
	si197_sequencial int8 NOT NULL DEFAULT 0,
	si197_tiporegistro int8 NOT NULL DEFAULT 0,
	si197_contacontabil int8 NOT NULL DEFAULT 0,
	si197_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si197_codorgao varchar(2) NOT NULL,
	si197_codunidadesub varchar(8) NOT NULL,
	si197_codfontrecursos int8 NOT NULL,
	si197_atributosf varchar(1) NOT NULL,
	si197_saldoinicialoufontesf float8 NOT NULL,
	si197_naturezasaldoinicialoufontesf varchar(1) NOT NULL,
	si197_totaldebitosoufontesf float8 NOT NULL,
	si197_totalcreditosoufontesf float8 NOT NULL,
	si197_saldofinaloufontesf float8 NOT NULL,
	si197_naturezasaldofinaloufontesf varchar(1) NOT NULL,
	si197_mes int8 NOT NULL DEFAULT 0,
	si197_instit int8 NULL DEFAULT 0,
	si197_reg10 int8 NOT NULL,
	CONSTRAINT balancete272024_sequ_pk PRIMARY KEY (si197_sequencial),
	CONSTRAINT fk_balancete272024_reg10_fk FOREIGN KEY (si197_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete282024 (
	si198_sequencial int8 NOT NULL DEFAULT 0,
	si198_tiporegistro int8 NOT NULL DEFAULT 0,
	si198_contacontabil int8 NOT NULL DEFAULT 0,
	si198_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si198_codctb int8 NOT NULL DEFAULT 0,
	si198_codfontrecursos int8 NOT NULL DEFAULT 0,
	si198_saldoinicialctbfonte float8 NOT NULL DEFAULT 0,
	si198_naturezasaldoinicialctbfonte varchar(1) NOT NULL,
	si198_totaldebitosctbfonte float8 NOT NULL DEFAULT 0,
	si198_totalcreditosctbfonte float8 NOT NULL DEFAULT 0,
	si198_saldofinalctbfonte float8 NOT NULL DEFAULT 0,
	si198_naturezasaldofinalctbfonte varchar(1) NOT NULL,
	si198_mes int8 NOT NULL DEFAULT 0,
	si198_instit int8 NULL DEFAULT 0,
	si198_reg10 int8 NOT NULL,
	CONSTRAINT balancete282024_sequ_pk PRIMARY KEY (si198_sequencial),
	CONSTRAINT fk_balancete282024_reg10_fk FOREIGN KEY (si198_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete292024 (
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
	CONSTRAINT balancete292024_sequ_pk PRIMARY KEY (si241_sequencial),
	CONSTRAINT fk_balancete292024_reg10_fk FOREIGN KEY (si241_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete302024 (
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
	si242_codco int8 NOT NULL DEFAULT 0,
	si242_saldoinicialcde float8 NOT NULL DEFAULT 0,
	si242_naturezasaldoinicialcde varchar(1) NOT NULL,
	si242_totaldebitoscde float8 NOT NULL DEFAULT 0,
	si242_totalcreditoscde float8 NOT NULL DEFAULT 0,
	si242_saldofinalcde float8 NOT NULL DEFAULT 0,
	si242_naturezasaldofinalcde varchar(1) NOT NULL,
	si242_mes int8 NOT NULL DEFAULT 0,
	si242_instit int8 NULL DEFAULT 0,
	si242_reg10 int8 NOT NULL,
	CONSTRAINT balancete302024_sequ_pk PRIMARY KEY (si242_sequencial),
	CONSTRAINT fk_balancete302024_reg10_fk FOREIGN KEY (si242_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.balancete312024 (
	si243_sequencial int8 NOT NULL DEFAULT 0,
	si243_tiporegistro int8 NOT NULL DEFAULT 0,
	si243_contacontabil int8 NOT NULL DEFAULT 0,
	si243_codfundo varchar(8) NOT NULL DEFAULT '00000000'::character varying,
	si243_naturezareceita int8 NOT NULL DEFAULT 0,
	si243_codfontrecursos int8 NOT NULL DEFAULT 0,
	si243_codco int8 NOT NULL DEFAULT 0,
	si243_nrocontratoop varchar(30) NULL,
	si243_dataassinaturacontratoop date NULL,
	si243_saldoinicialcre float8 NOT NULL DEFAULT 0,
	si243_naturezasaldoinicialcre varchar(1) NOT NULL,
	si243_totaldebitoscre float8 NOT NULL DEFAULT 0,
	si243_totalcreditoscre float8 NOT NULL DEFAULT 0,
	si243_saldofinalcre float8 NOT NULL DEFAULT 0,
	si243_naturezasaldofinalcre varchar(1) NOT NULL,
	si243_mes int8 NOT NULL DEFAULT 0,
	si243_instit int8 NULL DEFAULT 0,
	si243_reg10 int8 NOT NULL,
	CONSTRAINT balancete312024_sequ_pk PRIMARY KEY (si243_sequencial),
	CONSTRAINT fk_balancete312024_reg10_fk FOREIGN KEY (si243_reg10) REFERENCES public.balancete102024(si177_sequencial)
);





CREATE TABLE public.caixa112024 (
	si166_sequencial int8 NOT NULL DEFAULT 0,
	si166_tiporegistro int8 NOT NULL DEFAULT 0,
	si166_codfontecaixa int8 NOT NULL DEFAULT 0,
	si166_vlsaldoinicialfonte float8 NOT NULL DEFAULT 0,
	si166_vlsaldofinalfonte float8 NOT NULL DEFAULT 0,
	si166_mes int8 NOT NULL DEFAULT 0,
	si166_instit int8 NULL DEFAULT 0,
	si166_reg10 int8 NOT NULL DEFAULT 0,
	CONSTRAINT caixa112024_sequ_pk PRIMARY KEY (si166_sequencial),
	CONSTRAINT caixa112024_reg10_fk FOREIGN KEY (si166_reg10) REFERENCES public.caixa102024(si103_sequencial)
);





CREATE TABLE public.caixa122024 (
	si104_sequencial int8 NOT NULL DEFAULT 0,
	si104_tiporegistro int8 NOT NULL DEFAULT 0,
	si104_codreduzido int8 NOT NULL DEFAULT 0,
	si104_codfontecaixa int8 NOT NULL DEFAULT 0,
	si104_tipomovimentacao int8 NOT NULL DEFAULT 0,
	si104_tipoentrsaida varchar(2) NOT NULL,
	si104_descrmovimentacao varchar(50) NULL,
	si104_valorentrsaida float8 NOT NULL DEFAULT 0,
	si104_codctbtransf int8 NULL DEFAULT 0,
	si104_codfontectbtransf int8 NULL DEFAULT 0,
	si104_mes int8 NOT NULL DEFAULT 0,
	si104_reg10 int8 NOT NULL DEFAULT 0,
	si104_instit int8 NULL DEFAULT 0,
	si104_codidentificafr int4 NULL,
	CONSTRAINT caixa122024_sequ_pk PRIMARY KEY (si104_sequencial),
	CONSTRAINT caixa122024_reg10_fk FOREIGN KEY (si104_reg10) REFERENCES public.caixa102024(si103_sequencial)
);
CREATE INDEX caixa122024_si104_reg10_index ON public.caixa122024 USING btree (si104_reg10);





CREATE TABLE public.caixa132024 (
	si105_sequencial int8 NOT NULL DEFAULT 0,
	si105_tiporegistro int8 NOT NULL DEFAULT 0,
	si105_codreduzido int8 NOT NULL DEFAULT 0,
	si105_ededucaodereceita int8 NOT NULL DEFAULT 0,
	si105_identificadordeducao int8 NULL DEFAULT 0,
	si105_naturezareceita int8 NOT NULL DEFAULT 0,
	si105_codfontecaixa int8 NOT NULL DEFAULT 0,
	si105_vlrreceitacont float8 NOT NULL DEFAULT 0,
	si105_mes int8 NOT NULL DEFAULT 0,
	si105_reg10 int8 NOT NULL DEFAULT 0,
	si105_instit int8 NULL DEFAULT 0,
	si105_codfontcaixa int4 NULL DEFAULT 0,
	si105_codco varchar(4) NULL DEFAULT '0000'::character varying,
	CONSTRAINT caixa132024_sequ_pk PRIMARY KEY (si105_sequencial),
	CONSTRAINT caixa132024_reg10_fk FOREIGN KEY (si105_reg10) REFERENCES public.caixa102024(si103_sequencial)
);
CREATE INDEX caixa132024_si105_reg10_index ON public.caixa132024 USING btree (si105_reg10);





CREATE TABLE public.contratos112024 (
	si84_sequencial int8 NOT NULL DEFAULT 0,
	si84_tiporegistro int8 NOT NULL DEFAULT 0,
	si84_codcontrato int8 NOT NULL DEFAULT 0,
	si84_coditem int8 NOT NULL DEFAULT 0,
	si84_tipomaterial int8 NULL DEFAULT 0,
	si84_coditemsinapi varchar(15) NULL,
	si84_coditemsimcro varchar(15) NULL,
	si84_descoutrosmateriais varchar(250) NULL,
	si84_itemplanilha int8 NULL DEFAULT 0,
	si84_quantidadeitem float8 NOT NULL DEFAULT 0,
	si84_valorunitarioitem float8 NOT NULL DEFAULT 0,
	si84_mes int8 NOT NULL DEFAULT 0,
	si84_reg10 int8 NOT NULL DEFAULT 0,
	si84_instit int8 NULL DEFAULT 0,
	si84_nrolote int4 NULL,
	CONSTRAINT contratos112024_sequ_pk PRIMARY KEY (si84_sequencial),
	CONSTRAINT contratos112024_reg10_fk FOREIGN KEY (si84_reg10) REFERENCES public.contratos102024(si83_sequencial)
);





CREATE TABLE public.contratos212024 (
	si88_sequencial int8 NOT NULL DEFAULT 0,
	si88_tiporegistro int8 NOT NULL DEFAULT 0,
	si88_codaditivo int8 NOT NULL DEFAULT 0,
	si88_coditem int8 NOT NULL DEFAULT 0,
	si88_tipomaterial int8 NULL DEFAULT 0,
	si88_coditemsinapi varchar(15) NULL,
	si88_coditemsimcro varchar(15) NULL,
	si88_descoutrosmateriais varchar(250) NULL,
	si88_itemplanilha int8 NULL DEFAULT 0,
	si88_tipoalteracaoitem int8 NOT NULL DEFAULT 0,
	si88_quantacrescdecresc float8 NOT NULL DEFAULT 0,
	si88_valorunitarioitem float8 NOT NULL DEFAULT 0,
	si88_mes int8 NOT NULL DEFAULT 0,
	si88_reg20 int8 NOT NULL DEFAULT 0,
	si88_instit int8 NULL DEFAULT 0,
	si88_nrolote int4 NULL,
	CONSTRAINT contratos212024_sequ_pk PRIMARY KEY (si88_sequencial),
	CONSTRAINT contratos212024_reg20_fk FOREIGN KEY (si88_reg20) REFERENCES public.contratos202024(si87_sequencial)
);





CREATE TABLE public.conv112024 (
	si93_sequencial int8 NOT NULL DEFAULT 0,
	si93_tiporegistro int8 NOT NULL DEFAULT 0,
	si93_codconvenio int8 NOT NULL DEFAULT 0,
	si93_nrodocumento varchar(14) NULL,
	si93_esferaconcedente int8 NOT NULL DEFAULT 0,
	si93_dscexterior varchar(120) NULL,
	si93_valorconcedido float8 NOT NULL DEFAULT 0,
	si93_mes int8 NOT NULL DEFAULT 0,
	si93_reg10 int8 NOT NULL DEFAULT 0,
	si93_instit int8 NULL DEFAULT 0,
	CONSTRAINT conv112024_sequ_pk PRIMARY KEY (si93_sequencial),
	CONSTRAINT conv112024_reg10_fk FOREIGN KEY (si93_reg10) REFERENCES public.conv102024(si92_sequencial)
);
CREATE INDEX conv112024_si93_reg10_index ON public.conv112024 USING btree (si93_reg10);





CREATE TABLE public.ctb212024 (
	si97_sequencial int8 NOT NULL DEFAULT 0,
	si97_tiporegistro int8 NOT NULL DEFAULT 0,
	si97_codctb varchar(255) NOT NULL DEFAULT 0,
	si97_codfontrecursos int8 NOT NULL DEFAULT 0,
	si97_codreduzidomov varchar(255) NOT NULL DEFAULT 0,
	si97_tipomovimentacao int8 NOT NULL DEFAULT 0,
	si97_tipoentrsaida varchar(2) NOT NULL,
	si97_dscoutrasmov varchar(50) NULL,
	si97_valorentrsaida float8 NOT NULL DEFAULT 0,
	si97_codctbtransf int8 NULL DEFAULT 0,
	si97_codfontectbtransf int8 NOT NULL DEFAULT 0,
	si97_mes int8 NOT NULL DEFAULT 0,
	si97_reg20 int8 NOT NULL DEFAULT 0,
	si97_instit int8 NULL DEFAULT 0,
	si97_saldocec int8 NULL DEFAULT 0,
	si97_saldocectransf int8 NULL DEFAULT 0,
	si97_codidentificafr int4 NULL,
	CONSTRAINT ctb212024_sequ_pk PRIMARY KEY (si97_sequencial),
	CONSTRAINT ctb212024_reg20_fk FOREIGN KEY (si97_reg20) REFERENCES public.ctb202024(si96_sequencial)
);
CREATE INDEX ctb212024_si97_reg20_index ON public.ctb212024 USING btree (si97_reg20);





CREATE TABLE public.ctb222024 (
	si98_sequencial int8 NOT NULL DEFAULT 0,
	si98_tiporegistro int8 NOT NULL DEFAULT 0,
	si98_codreduzidomov int8 NOT NULL DEFAULT 0,
	si98_ededucaodereceita int8 NOT NULL DEFAULT 0,
	si98_identificadordeducao int8 NULL DEFAULT 0,
	si98_naturezareceita int8 NOT NULL DEFAULT 0,
	si98_codfontrecursos int8 NOT NULL DEFAULT 0,
	si98_vlrreceitacont float8 NOT NULL DEFAULT 0,
	si98_mes int8 NOT NULL DEFAULT 0,
	si98_reg21 int8 NOT NULL DEFAULT 0,
	si98_instit int8 NULL DEFAULT 0,
	si98_saldocec int8 NULL DEFAULT 0,
	si98_codco varchar(4) NULL DEFAULT '0000'::character varying,
	CONSTRAINT ctb222024_sequ_pk PRIMARY KEY (si98_sequencial),
	CONSTRAINT ctb222024_reg21_fk FOREIGN KEY (si98_reg21) REFERENCES public.ctb212024(si97_sequencial)
);
CREATE INDEX ctb222024_si98_reg21_index ON public.ctb222024 USING btree (si98_reg21);





CREATE TABLE public.ctb312024 (
	si100_sequencial int8 NOT NULL DEFAULT 0,
	si100_tiporegistro int8 NOT NULL DEFAULT 0,
	si100_codagentearrecadador int8 NOT NULL DEFAULT 0,
	si100_codfontrecursos int8 NOT NULL DEFAULT 0,
	si100_vlsaldoinicialagfonte float8 NOT NULL DEFAULT 0,
	si100_vlentradafonte float8 NOT NULL DEFAULT 0,
	si100_vlsaidafonte float8 NOT NULL DEFAULT 0,
	si100_vlsaldofinalagfonte float8 NOT NULL DEFAULT 0,
	si100_mes int8 NOT NULL DEFAULT 0,
	si100_reg30 int8 NOT NULL DEFAULT 0,
	si100_instit int4 NULL DEFAULT 0,
	CONSTRAINT ctb312024_sequ_pk PRIMARY KEY (si100_sequencial),
	CONSTRAINT ctb312024_reg30_fk FOREIGN KEY (si100_reg30) REFERENCES public.ctb302024(si99_sequencial)
);
CREATE INDEX ctb312024_si100_reg30_index ON public.ctb312024 USING btree (si100_reg30);





CREATE TABLE public.cute212024 (
	si201_sequencial int8 NOT NULL DEFAULT 0,
	si201_tiporegistro int8 NOT NULL DEFAULT 0,
	si201_codctb int8 NOT NULL DEFAULT 0,
	si201_codfontrecursos int8 NOT NULL DEFAULT 0,
	si201_tipomovimentacao int8 NOT NULL DEFAULT 0,
	si201_tipoentrsaida varchar(2) NOT NULL,
	si201_valorentrsaida float8 NOT NULL DEFAULT 0,
	si201_codorgaotransf varchar(2) NULL,
	si201_reg10 int8 NOT NULL DEFAULT 0,
	si201_mes int8 NOT NULL DEFAULT 0,
	si201_instit int8 NULL DEFAULT 0,
	CONSTRAINT cute212024_sequ_pk PRIMARY KEY (si201_sequencial),
	CONSTRAINT cute212024_reg10_fk FOREIGN KEY (si201_reg10) REFERENCES public.cute102024(si199_sequencial)
);
CREATE INDEX cute212024_si201_reg10_index ON public.cute212024 USING btree (si201_reg10);





CREATE TABLE public.dispensa112024 (
	si75_sequencial int8 NOT NULL DEFAULT 0,
	si75_tiporegistro int8 NOT NULL DEFAULT 0,
	si75_codorgaoresp varchar(2) NOT NULL DEFAULT 0,
	si75_codunidadesubresp varchar(8) NOT NULL,
	si75_exercicioprocesso int8 NOT NULL DEFAULT 0,
	si75_nroprocesso varchar(12) NOT NULL,
	si75_tipoprocesso int8 NOT NULL DEFAULT 0,
	si75_nrolote int8 NOT NULL DEFAULT 0,
	si75_dsclote varchar(250) NOT NULL,
	si75_mes int8 NULL,
	si75_reg10 int8 NOT NULL DEFAULT 0,
	si75_instit int8 NULL,
	CONSTRAINT dispensa112024_sequ_pk PRIMARY KEY (si75_sequencial),
	CONSTRAINT dispensa112024_reg10_fk FOREIGN KEY (si75_reg10) REFERENCES public.dispensa102024(si74_sequencial)
);
CREATE INDEX dispensa112024_si75_reg10_index ON public.dispensa112024 USING btree (si75_reg10);





CREATE TABLE public.dispensa122024 (
	si76_sequencial int8 NOT NULL DEFAULT 0,
	si76_tiporegistro int8 NOT NULL DEFAULT 0,
	si76_codorgaoresp varchar(2) NOT NULL,
	si76_codunidadesubresp varchar(8) NOT NULL,
	si76_exercicioprocesso int8 NOT NULL DEFAULT 0,
	si76_nroprocesso varchar(12) NOT NULL,
	si76_tipoprocesso int8 NOT NULL,
	si76_coditem int8 NOT NULL DEFAULT 0,
	si76_nroitem int8 NOT NULL DEFAULT 0,
	si76_mes int8 NOT NULL,
	si76_reg10 int8 NOT NULL DEFAULT 0,
	si76_instit int8 NOT NULL,
	CONSTRAINT dispensa122024_sequ_pk PRIMARY KEY (si76_sequencial),
	CONSTRAINT dispensa122024_reg10_fk FOREIGN KEY (si76_reg10) REFERENCES public.dispensa102024(si74_sequencial)
);
CREATE INDEX dispensa122024_si76_reg10_index ON public.dispensa122024 USING btree (si76_reg10);





CREATE TABLE public.dispensa132024 (
	si77_sequencial int8 NOT NULL DEFAULT 0,
	si77_tiporegistro int8 NOT NULL DEFAULT 0,
	si77_codorgaoresp varchar(2) NOT NULL,
	si77_codunidadesubresp varchar(8) NOT NULL,
	si77_exercicioprocesso int8 NOT NULL,
	si77_nroprocesso varchar(12) NOT NULL,
	si77_tipoprocesso int8 NOT NULL,
	si77_nrolote int8 NOT NULL,
	si77_coditem int8 NOT NULL,
	si77_mes int8 NOT NULL,
	si77_reg10 int8 NOT NULL DEFAULT 0,
	si77_instit int8 NOT NULL,
	CONSTRAINT dispensa132024_sequ_pk PRIMARY KEY (si77_sequencial),
	CONSTRAINT dispensa132024_reg10_fk FOREIGN KEY (si77_reg10) REFERENCES public.dispensa102024(si74_sequencial)
);
CREATE INDEX dispensa132024_si77_reg10_index ON public.dispensa132024 USING btree (si77_reg10);





CREATE TABLE public.dispensa142024 (
	si78_sequencial int8 NOT NULL DEFAULT 0,
	si78_tiporegistro int8 NOT NULL,
	si78_codorgaoresp varchar(2) NOT NULL,
	si78_codunidadesubres varchar(8) NOT NULL,
	si78_exercicioprocesso int8 NOT NULL,
	si78_nroprocesso varchar(12) NOT NULL,
	si78_tipoprocesso int8 NOT NULL,
	si78_tiporesp int8 NOT NULL,
	si78_nrocpfresp varchar(11) NOT NULL,
	si78_mes int8 NULL,
	si78_reg10 int8 NOT NULL DEFAULT 0,
	si78_instit int8 NULL,
	CONSTRAINT dispensa142024_sequ_pk PRIMARY KEY (si78_sequencial),
	CONSTRAINT dispensa142024_reg10_fk FOREIGN KEY (si78_reg10) REFERENCES public.dispensa102024(si74_sequencial)
);
CREATE INDEX dispensa142024_si78_reg10_index ON public.dispensa142024 USING btree (si78_reg10);





CREATE TABLE public.dispensa152024 (
	si79_sequencial int8 NOT NULL DEFAULT 0,
	si79_tiporegistro int8 NOT NULL DEFAULT 0,
	si79_codorgaoresp varchar(2) NOT NULL,
	si79_codunidadesubresp varchar(8) NOT NULL,
	si79_exercicioprocesso int8 NOT NULL,
	si79_nroprocesso varchar(12) NOT NULL,
	si79_tipoprocesso int8 NOT NULL,
	si79_nrolote int8 NULL,
	si79_coditem int8 NOT NULL,
	si79_vlcotprecosunitario float8 NOT NULL,
	si79_quantidade float8 NOT NULL,
	si79_mes int8 NULL,
	si79_reg10 int8 NOT NULL DEFAULT 0,
	si79_instit int8 NULL,
	CONSTRAINT dispensa152024_sequ_pk PRIMARY KEY (si79_sequencial),
	CONSTRAINT dispensa152024_reg10_fk FOREIGN KEY (si79_reg10) REFERENCES public.dispensa102024(si74_sequencial)
);
CREATE INDEX dispensa152024_si79_reg10_index ON public.dispensa152024 USING btree (si79_reg10);





CREATE TABLE public.dispensa162024 (
	si80_sequencial int8 NOT NULL DEFAULT 0,
	si80_tiporegistro int8 NOT NULL,
	si80_codorgaoresp varchar(2) NOT NULL,
	si80_codunidadesubresp varchar(8) NOT NULL,
	si80_exercicioprocesso int8 NOT NULL,
	si80_nroprocesso varchar(12) NOT NULL,
	si80_tipoprocesso int8 NOT NULL,
	si80_codorgao varchar(2) NOT NULL,
	si80_codunidadesub varchar(8) NOT NULL,
	si80_codfuncao varchar(2) NOT NULL,
	si80_codsubfuncao varchar(3) NOT NULL,
	si80_codprograma varchar(4) NOT NULL,
	si80_idacao varchar(4) NOT NULL,
	si80_idsubacao varchar(4) NULL,
	si80_naturezadespesa int8 NOT NULL,
	si80_codfontrecursos int8 NOT NULL,
	si80_vlrecurso float8 NOT NULL,
	si80_mes int8 NULL,
	si80_reg10 int8 NOT NULL DEFAULT 0,
	si80_instit int8 NULL,
	CONSTRAINT dispensa162024_sequ_pk PRIMARY KEY (si80_sequencial),
	CONSTRAINT dispensa162024_reg10_fk FOREIGN KEY (si80_reg10) REFERENCES public.dispensa102024(si74_sequencial)
);
CREATE INDEX dispensa162024_si80_reg10_index ON public.dispensa162024 USING btree (si80_reg10);





CREATE TABLE public.dispensa172024 (
	si81_sequencial int8 NOT NULL DEFAULT 0,
	si81_tiporegistro int8 NOT NULL DEFAULT 0,
	si81_codorgaoresp varchar(2) NOT NULL,
	si81_codunidadesubresp varchar(8) NOT NULL,
	si81_exercicioprocesso int8 NOT NULL DEFAULT 0,
	si81_nroprocesso varchar(12) NOT NULL,
	si81_tipoprocesso int8 NOT NULL DEFAULT 0,
	si81_tipodocumento int8 NOT NULL DEFAULT 0,
	si81_nrodocumento varchar(14) NOT NULL,
	si81_nroinscricaoestadual varchar(30) NULL,
	si81_ufinscricaoestadual varchar(2) NULL,
	si81_nrocertidaoregularidadeinss varchar(30) NULL,
	si81_dtemissaocertidaoregularidadeinss date NULL,
	si81_dtvalidadecertidaoregularidadeinss date NULL,
	si81_nrocertidaoregularidadefgts varchar(30) NULL,
	si81_dtemissaocertidaoregularidadefgts date NULL,
	si81_dtvalidadecertidaoregularidadefgts date NULL,
	si81_nrocndt varchar(30) NULL,
	si81_dtemissaocndt date NULL,
	si81_dtvalidadecndt date NULL,
	si81_nrolote int8 NULL DEFAULT 0,
	si81_coditem int8 NOT NULL DEFAULT 0,
	si81_quantidade float8 NOT NULL DEFAULT 0,
	si81_vlitem float8 NOT NULL DEFAULT 0,
	si81_mes int8 NOT NULL DEFAULT 0,
	si81_reg10 int8 NOT NULL DEFAULT 0,
	si81_instit int8 NULL DEFAULT 0,
	CONSTRAINT dispensa172024_sequ_pk PRIMARY KEY (si81_sequencial),
	CONSTRAINT dispensa172024_reg10_fk FOREIGN KEY (si81_reg10) REFERENCES public.dispensa102024(si74_sequencial)
);
CREATE INDEX dispensa172024_si81_reg10_index ON public.dispensa172024 USING btree (si81_reg10);





CREATE TABLE public.emp112024 (
	si107_sequencial int8 NOT NULL DEFAULT 0,
	si107_tiporegistro int8 NOT NULL DEFAULT 0,
	si107_codunidadesub varchar(8) NOT NULL,
	si107_nroempenho int8 NOT NULL DEFAULT 0,
	si107_codfontrecursos int8 NOT NULL DEFAULT 0,
	si107_valorfonte float8 NOT NULL DEFAULT 0,
	si107_mes int8 NOT NULL DEFAULT 0,
	si107_reg10 int8 NOT NULL DEFAULT 0,
	si107_instit int8 NULL DEFAULT 0,
	si107_codco varchar(4) NULL DEFAULT 0,
	CONSTRAINT emp112024_sequ_pk PRIMARY KEY (si107_sequencial),
	CONSTRAINT emp112024_reg10_fk FOREIGN KEY (si107_reg10) REFERENCES public.emp102024(si106_sequencial)
);
CREATE INDEX emp112024_si107_reg10_index ON public.emp112024 USING btree (si107_reg10);





CREATE TABLE public.emp122024 (
	si108_sequencial int8 NOT NULL DEFAULT 0,
	si108_tiporegistro int8 NOT NULL DEFAULT 0,
	si108_codunidadesub varchar(8) NOT NULL,
	si108_nroempenho int8 NOT NULL DEFAULT 0,
	si108_tipodocumento int8 NOT NULL DEFAULT 0,
	si108_nrodocumento varchar(14) NOT NULL,
	si108_mes int8 NOT NULL DEFAULT 0,
	si108_reg10 int8 NOT NULL DEFAULT 0,
	si108_instit int8 NULL DEFAULT 0,
	CONSTRAINT emp122024_sequ_pk PRIMARY KEY (si108_sequencial),
	CONSTRAINT emp122024_reg10_fk FOREIGN KEY (si108_reg10) REFERENCES public.emp102024(si106_sequencial)
);
CREATE INDEX emp122024_si108_reg10_index ON public.emp122024 USING btree (si108_reg10);





CREATE TABLE public.ext312024 (
	si127_sequencial int8 NOT NULL DEFAULT 0,
	si127_tiporegistro int8 NOT NULL DEFAULT 0,
	si127_codreduzidoop int8 NOT NULL DEFAULT 0,
	si127_tipodocumentoop varchar(2) NOT NULL,
	si127_nrodocumento varchar(15) NULL,
	si127_codctb int8 NULL DEFAULT 0,
	si127_codfontectb int8 NULL DEFAULT 0,
	si127_desctipodocumentoop varchar(50) NULL,
	si127_dtemissao date NOT NULL,
	si127_vldocumento float8 NOT NULL DEFAULT 0,
	si127_mes int8 NOT NULL DEFAULT 0,
	si127_reg30 int8 NOT NULL DEFAULT 0,
	si127_instit int8 NOT NULL DEFAULT 0,
	CONSTRAINT ext312024_sequ_pk PRIMARY KEY (si127_sequencial),
	CONSTRAINT ext312024_reg22_fk FOREIGN KEY (si127_reg30) REFERENCES public.ext302024(si126_sequencial)
);
CREATE INDEX ext312024_si127_reg20_index ON public.ext312024 USING btree (si127_reg30);





CREATE TABLE public.ext322024 (
	si128_sequencial int8 NOT NULL DEFAULT 0,
	si128_tiporegistro int8 NOT NULL DEFAULT 0,
	si128_codreduzidoop int8 NOT NULL DEFAULT 0,
	si128_tiporetencao varchar(4) NOT NULL,
	si128_descricaoretencao varchar(50) NULL,
	si128_vlretencao float8 NOT NULL DEFAULT 0,
	si128_mes int8 NOT NULL DEFAULT 0,
	si128_reg30 int8 NULL DEFAULT 0,
	si128_instit int8 NULL DEFAULT 0,
	CONSTRAINT ext322024_sequ_pk PRIMARY KEY (si128_sequencial),
	CONSTRAINT ext322024_reg23_fk FOREIGN KEY (si128_reg30) REFERENCES public.ext322024(si128_sequencial)
);
CREATE INDEX ext322024_si128_reg20_index ON public.ext322024 USING btree (si128_reg30);





CREATE TABLE public.flpgo112024 (
	si196_sequencial int8 NOT NULL DEFAULT 0,
	si196_tiporegistro int8 NULL,
	si196_indtipopagamento varchar(1) NULL,
	si196_codvinculopessoa varchar(15) NULL,
	si196_codrubricaremuneracao varchar(4) NULL,
	si196_desctiporubrica varchar(150) NULL,
	si196_vlrremuneracaodetalhada float8 NULL,
	si196_reg10 int8 NULL DEFAULT 0,
	si196_mes int8 NULL,
	si196_inst int8 NULL,
	CONSTRAINT flpgo112024_sequ_pk PRIMARY KEY (si196_sequencial),
	CONSTRAINT flpgo112024_reg10_fk FOREIGN KEY (si196_reg10) REFERENCES public.flpgo102024(si195_sequencial)
);





CREATE TABLE public.flpgo122024 (
	si197_sequencial int8 NOT NULL DEFAULT 0,
	si197_tiporegistro int8 NULL,
	si197_indtipopagamento varchar(1) NULL,
	si197_codvinculopessoa varchar(15) NULL,
	si197_codrubricadesconto varchar(4) NULL,
	si197_desctiporubricadesconto varchar(150) NULL,
	si197_vlrdescontodetalhado float8 NULL,
	si197_reg10 int8 NULL DEFAULT 0,
	si197_mes int8 NULL,
	si197_inst int8 NULL,
	CONSTRAINT flpgo122024_sequ_pk PRIMARY KEY (si197_sequencial),
	CONSTRAINT flpgo122024_reg10_fk FOREIGN KEY (si197_reg10) REFERENCES public.flpgo102024(si195_sequencial)
);





CREATE TABLE public.hablic112024 (
	si58_sequencial int8 NOT NULL DEFAULT 0,
	si58_tiporegistro int8 NOT NULL DEFAULT 0,
	si58_codorgao varchar(2) NOT NULL,
	si58_codunidadesub varchar(8) NOT NULL,
	si58_exerciciolicitacao int8 NOT NULL DEFAULT 0,
	si58_nroprocessolicitatorio varchar(12) NOT NULL,
	si58_tipodocumentocnpjempresahablic int8 NOT NULL DEFAULT 0,
	si58_cnpjempresahablic varchar(14) NOT NULL,
	si58_tipodocumentosocio int8 NOT NULL DEFAULT 0,
	si58_nrodocumentosocio varchar(14) NOT NULL,
	si58_tipoparticipacao int8 NOT NULL DEFAULT 0,
	si58_mes int8 NOT NULL DEFAULT 0,
	si58_reg10 int8 NOT NULL DEFAULT 0,
	si58_instit int8 NULL DEFAULT 0,
	CONSTRAINT hablic112024_sequ_pk PRIMARY KEY (si58_sequencial),
	CONSTRAINT hablic112024_reg10_fk FOREIGN KEY (si58_reg10) REFERENCES public.hablic102024(si57_sequencial)
);
CREATE INDEX hablic112024_si58_reg10_index ON public.hablic112024 USING btree (si58_mes);





CREATE TABLE public.lao112024 (
	si35_sequencial int8 NOT NULL DEFAULT 0,
	si35_tiporegistro int8 NOT NULL DEFAULT 0,
	si35_nroleialteracao int8 NOT NULL,
	si35_tipoleialteracao int8 NOT NULL DEFAULT 0,
	si35_artigoleialteracao varchar(6) NOT NULL,
	si35_descricaoartigo varchar(512) NOT NULL,
	si35_vlautorizadoalteracao float8 NOT NULL DEFAULT 0,
	si35_mes int8 NOT NULL DEFAULT 0,
	si35_reg10 int8 NOT NULL DEFAULT 0,
	si35_instit int8 NULL DEFAULT 0,
	CONSTRAINT lao112024_sequ_pk PRIMARY KEY (si35_sequencial),
	CONSTRAINT lao112024_reg10_fk FOREIGN KEY (si35_reg10) REFERENCES public.lao102024(si34_sequencial)
);
CREATE INDEX lao112024_si35_reg10_index ON public.lao112024 USING btree (si35_reg10);





CREATE TABLE public.lao212024 (
	si37_sequencial int8 NOT NULL DEFAULT 0,
	si37_tiporegistro int8 NOT NULL DEFAULT 0,
	si37_nroleialterorcam int8 NOT NULL,
	si37_tipoautorizacao int8 NOT NULL DEFAULT 0,
	si37_artigoleialterorcamento varchar(6) NOT NULL,
	si37_descricaoartigo varchar(512) NOT NULL,
	si37_novopercentual float8 NOT NULL DEFAULT 0,
	si37_mes int8 NOT NULL DEFAULT 0,
	si37_reg20 int8 NOT NULL DEFAULT 0,
	si37_instit int8 NULL DEFAULT 0,
	CONSTRAINT lao212024_sequ_pk PRIMARY KEY (si37_sequencial),
	CONSTRAINT lao212024_reg20_fk FOREIGN KEY (si37_reg20) REFERENCES public.lao202024(si36_sequencial)
);
CREATE INDEX lao212024_si37_reg20_index ON public.lao212024 USING btree (si37_reg20);





CREATE TABLE public.lqd112024 (
	si119_sequencial int8 NOT NULL DEFAULT 0,
	si119_tiporegistro int8 NOT NULL DEFAULT 0,
	si119_codreduzido int8 NOT NULL DEFAULT 0,
	si119_codfontrecursos int8 NOT NULL DEFAULT 0,
	si119_valorfonte float8 NOT NULL DEFAULT 0,
	si119_mes int8 NOT NULL DEFAULT 0,
	si119_reg10 int8 NOT NULL DEFAULT 0,
	si119_instit int8 NULL DEFAULT 0,
	si119_codco varchar(4) NULL DEFAULT '0000'::character varying,
	CONSTRAINT lqd112024_sequ_pk PRIMARY KEY (si119_sequencial),
	CONSTRAINT lqd112024_reg10_fk FOREIGN KEY (si119_reg10) REFERENCES public.lqd102024(si118_sequencial)
);
CREATE INDEX lqd112024_si119_reg10_index ON public.lqd112024 USING btree (si119_reg10);





CREATE TABLE public.lqd122024 (
	si120_sequencial int8 NOT NULL DEFAULT 0,
	si120_tiporegistro int8 NOT NULL DEFAULT 0,
	si120_codreduzido int8 NOT NULL DEFAULT 0,
	si120_mescompetencia varchar(2) NOT NULL,
	si120_exerciciocompetencia int8 NOT NULL DEFAULT 0,
	si120_vldspexerant float8 NOT NULL DEFAULT 0,
	si120_mes int8 NOT NULL DEFAULT 0,
	si120_reg10 int8 NOT NULL DEFAULT 0,
	si120_instit int8 NULL DEFAULT 0,
	CONSTRAINT lqd122024_sequ_pk PRIMARY KEY (si120_sequencial),
	CONSTRAINT lqd122024_reg10_fk FOREIGN KEY (si120_reg10) REFERENCES public.lqd102024(si118_sequencial)
);
CREATE INDEX lqd122024_si120_reg10_index ON public.lqd122024 USING btree (si120_reg10);





CREATE TABLE public.ntf112024 (
	si144_sequencial int8 NOT NULL DEFAULT 0,
	si144_tiporegistro int8 NOT NULL DEFAULT 0,
	si144_codnotafiscal int8 NOT NULL DEFAULT 0,
	si144_coditem int8 NOT NULL DEFAULT 0,
	si144_quantidadeitem float8 NOT NULL DEFAULT 0,
	si144_valorunitarioitem float8 NOT NULL DEFAULT 0,
	si144_mes int8 NOT NULL DEFAULT 0,
	si144_reg10 int8 NOT NULL DEFAULT 0,
	si144_instit int8 NULL DEFAULT 0,
	CONSTRAINT ntf112024_sequ_pk PRIMARY KEY (si144_sequencial),
	CONSTRAINT ntf112024_reg10_fk FOREIGN KEY (si144_reg10) REFERENCES public.ntf102024(si143_sequencial)
);
CREATE INDEX ntf112024_si144_reg10_index ON public.ntf112024 USING btree (si144_reg10);





CREATE TABLE public.obelac112024 (
	si140_sequencial int8 NOT NULL DEFAULT 0,
	si140_tiporegistro int8 NOT NULL DEFAULT 0,
	si140_codreduzido int8 NOT NULL DEFAULT 0,
	si140_codfontrecursos int8 NOT NULL DEFAULT 0,
	si140_valorfonte float8 NOT NULL DEFAULT 0,
	si140_mes int8 NOT NULL DEFAULT 0,
	si140_reg10 int8 NOT NULL DEFAULT 0,
	si140_instit int8 NULL DEFAULT 0,
	CONSTRAINT obelac112024_sequ_pk PRIMARY KEY (si140_sequencial),
	CONSTRAINT obelac112024_reg10_fk FOREIGN KEY (si140_reg10) REFERENCES public.lqd122024(si120_sequencial)
);
CREATE INDEX obelac112024_si140_reg10_index ON public.obelac112024 USING btree (si140_reg10);





CREATE TABLE public.ops112024 (
	si133_sequencial int8 NOT NULL DEFAULT 0,
	si133_tiporegistro int8 NOT NULL DEFAULT 0,
	si133_codreduzidoop int8 NOT NULL DEFAULT 0,
	si133_codunidadesub varchar(8) NOT NULL,
	si133_nroop int8 NOT NULL DEFAULT 0,
	si133_dtpagamento date NOT NULL,
	si133_tipopagamento int8 NOT NULL DEFAULT 0,
	si133_nroempenho int8 NOT NULL DEFAULT 0,
	si133_dtempenho date NOT NULL,
	si133_nroliquidacao int8 NULL DEFAULT 0,
	si133_dtliquidacao date NULL,
	si133_codfontrecursos int8 NOT NULL DEFAULT 0,
	si133_valorfonte float8 NOT NULL DEFAULT 0,
	si133_tipodocumentocredor int8 NULL DEFAULT 0,
	si133_nrodocumento varchar(14) NULL,
	si133_codorgaoempop varchar(2) NULL,
	si133_codunidadeempop varchar(8) NULL,
	si133_mes int8 NOT NULL DEFAULT 0,
	si133_reg10 int8 NOT NULL DEFAULT 0,
	si133_instit int8 NULL DEFAULT 0,
	si133_codco varchar(4) NULL DEFAULT '0000'::character varying,
	CONSTRAINT ops112024_sequ_pk PRIMARY KEY (si133_sequencial),
	CONSTRAINT ops112024_reg10_fk FOREIGN KEY (si133_reg10) REFERENCES public.ops102024(si132_sequencial)
);
CREATE INDEX ops112024_si133_reg10_index ON public.ops112024 USING btree (si133_reg10);





CREATE TABLE public.ops122024 (
	si134_sequencial int8 NOT NULL DEFAULT 0,
	si134_tiporegistro int8 NOT NULL DEFAULT 0,
	si134_codreduzidoop int8 NOT NULL DEFAULT 0,
	si134_tipodocumentoop varchar(2) NOT NULL,
	si134_nrodocumento varchar(15) NULL,
	si134_codctb int8 NULL DEFAULT 0,
	si134_codfontectb int8 NULL DEFAULT 0,
	si134_desctipodocumentoop varchar(50) NULL,
	si134_dtemissao date NOT NULL,
	si134_vldocumento float8 NOT NULL DEFAULT 0,
	si134_mes int8 NOT NULL DEFAULT 0,
	si134_reg10 int8 NOT NULL DEFAULT 0,
	si134_instit int4 NULL DEFAULT 0,
	CONSTRAINT ops122024_sequ_pk PRIMARY KEY (si134_sequencial),
	CONSTRAINT ops122024_reg10_fk FOREIGN KEY (si134_reg10) REFERENCES public.ops102024(si132_sequencial)
);
CREATE INDEX ops122024_si134_reg10_index ON public.ops122024 USING btree (si134_reg10);





CREATE TABLE public.ops132024 (
	si135_sequencial int8 NOT NULL DEFAULT 0,
	si135_tiporegistro int8 NOT NULL DEFAULT 0,
	si135_codreduzidoop int8 NOT NULL DEFAULT 0,
	si135_tiporetencao varchar(4) NOT NULL,
	si135_descricaoretencao varchar(50) NULL,
	si135_vlretencao float8 NOT NULL DEFAULT 0,
	si135_vlantecipado float8 NOT NULL DEFAULT 0,
	si135_mes int8 NOT NULL DEFAULT 0,
	si135_reg10 int8 NOT NULL DEFAULT 0,
	si135_instit int8 NULL DEFAULT 0,
	CONSTRAINT ops132024_sequ_pk PRIMARY KEY (si135_sequencial),
	CONSTRAINT ops132024_reg10_fk FOREIGN KEY (si135_reg10) REFERENCES public.ops102024(si132_sequencial)
);
CREATE INDEX ops132024_si135_reg10_index ON public.ops132024 USING btree (si135_reg10);





CREATE TABLE public.orgao112024 (
	si15_sequencial int8 NOT NULL DEFAULT 0,
	si15_tiporegistro int8 NOT NULL DEFAULT 0,
	si15_tiporesponsavel varchar(2) NOT NULL,
	si15_cartident varchar(10) NOT NULL,
	si15_orgemissorci varchar(10) NOT NULL,
	si15_cpf varchar(11) NOT NULL,
	si15_crccontador varchar(11) NULL,
	si15_ufcrccontador varchar(2) NULL,
	si15_cargoorddespdeleg varchar(50) NULL,
	si15_dtinicio date NOT NULL,
	si15_dtfinal date NOT NULL,
	si15_email varchar(50) NOT NULL,
	si15_reg10 int8 NOT NULL DEFAULT 0,
	si15_mes int8 NOT NULL DEFAULT 0,
	si15_instit int8 NULL DEFAULT 0,
	si15_numerotelefone int8 NULL,
	CONSTRAINT orgao112024_sequ_pk PRIMARY KEY (si15_sequencial),
	CONSTRAINT orgao112024_reg10_fk FOREIGN KEY (si15_reg10) REFERENCES public.orgao102024(si14_sequencial)
);
CREATE INDEX orgao112024_si15_reg10_index ON public.orgao112024 USING btree (si15_reg10);





CREATE TABLE public.parec112024 (
	si23_sequencial int8 NOT NULL DEFAULT 0,
	si23_tiporegistro int8 NOT NULL DEFAULT 0,
	si23_codreduzido int8 NOT NULL DEFAULT 0,
	si23_codfontrecursos int8 NOT NULL DEFAULT 0,
	si23_vlfonte float8 NOT NULL DEFAULT 0,
	si23_reg10 int8 NOT NULL DEFAULT 0,
	si23_mes int8 NOT NULL DEFAULT 0,
	si23_instit int8 NULL DEFAULT 0,
	CONSTRAINT parec112024_sequ_pk PRIMARY KEY (si23_sequencial),
	CONSTRAINT parec112024_reg10_fk FOREIGN KEY (si23_reg10) REFERENCES public.parec102024(si22_sequencial)
);
CREATE INDEX parec112024_si23_reg10_index ON public.parec112024 USING btree (si23_reg10);





CREATE TABLE public.ralic112024 (
	si181_sequencial int8 NOT NULL DEFAULT 0,
	si181_tiporegistro int8 NOT NULL DEFAULT 0,
	si181_codorgaoresp bpchar(3) NOT NULL,
	si181_codunidadesubresp varchar(8) NULL,
	si181_codunidadesubrespestadual bpchar(4) NULL,
	si181_exerciciolicitacao int2 NOT NULL,
	si181_nroprocessolicitatorio varchar(12) NOT NULL,
	si181_codobralocal int8 NULL,
	si181_classeobjeto int2 NOT NULL,
	si181_tipoatividadeobra int2 NULL,
	si181_tipoatividadeservico int2 NULL,
	si181_dscatividadeservico varchar(150) NULL,
	si181_tipoatividadeservespecializado int2 NULL,
	si181_dscatividadeservespecializado varchar(150) NULL,
	si181_codfuncao bpchar(2) NOT NULL,
	si181_codsubfuncao bpchar(3) NOT NULL,
	si181_codbempublico int2 NULL,
	si181_reg10 int8 NOT NULL DEFAULT 0,
	si181_mes int8 NOT NULL DEFAULT 0,
	si181_instit int8 NULL DEFAULT 0,
	si181_nrolote int4 NULL,
	CONSTRAINT ralic112024_sequ_pk PRIMARY KEY (si181_sequencial),
	CONSTRAINT ralic112024_reg10_fk FOREIGN KEY (si181_reg10) REFERENCES public.ralic102024(si180_sequencial)
);





CREATE TABLE public.ralic122024 (
	si182_sequencial int8 NOT NULL DEFAULT 0,
	si182_tiporegistro int8 NOT NULL DEFAULT 0,
	si182_codorgaoresp varchar(3) NOT NULL,
	si182_codunidadesubresp varchar(8) NULL,
	si182_codunidadesubrespestadual bpchar(4) NULL,
	si182_exercicioprocesso int2 NOT NULL DEFAULT 0,
	si182_nroprocessolicitatorio varchar(12) NOT NULL,
	si182_codobralocal int8 NULL,
	si182_logradouro varchar(100) NOT NULL,
	si182_numero int2 NOT NULL,
	si182_bairro varchar(100) NULL,
	si182_distrito varchar(100) NULL,
	si182_municipio varchar(50) NOT NULL,
	si182_cep int8 NOT NULL,
	si182_reg10 int8 NOT NULL DEFAULT 0,
	si182_mes int8 NOT NULL DEFAULT 0,
	si182_instit int8 NULL DEFAULT 0,
	si182_latitude numeric NULL,
	si182_longitude numeric NULL,
	si182_nrolote int4 NULL,
	si182_codbempublico int2 NULL,
	CONSTRAINT ralic122024_sequ_pk PRIMARY KEY (si182_sequencial),
	CONSTRAINT ralic122024_reg10_fk FOREIGN KEY (si182_reg10) REFERENCES public.ralic102024(si180_sequencial)
);





CREATE TABLE public.rec112024 (
	si26_sequencial int8 NOT NULL DEFAULT 0,
	si26_tiporegistro int8 NOT NULL DEFAULT 0,
	si26_codreceita int8 NOT NULL DEFAULT 0,
	si26_codfontrecursos int8 NOT NULL DEFAULT 0,
	si26_tipodocumento int8 NULL,
	si26_nrodocumento varchar(14) NULL,
	si26_nroconvenio varchar(30) NULL,
	si26_dataassinatura date NULL,
	si26_vlarrecadadofonte float8 NOT NULL DEFAULT 0,
	si26_reg10 int8 NOT NULL DEFAULT 0,
	si26_mes int8 NOT NULL DEFAULT 0,
	si26_instit int8 NULL DEFAULT 0,
	si26_nrocontratoop varchar(30) NULL,
	si26_dataassinaturacontratoop date NULL,
	si26_codigocontroleorcamentario varchar NULL,
	CONSTRAINT rec112024_sequ_pk PRIMARY KEY (si26_sequencial),
	CONSTRAINT rec112024_reg10_fk FOREIGN KEY (si26_reg10) REFERENCES public.rec102024(si25_sequencial)
);
CREATE INDEX rec112024_si26_reg10_index ON public.rec112024 USING btree (si26_reg10);





CREATE TABLE public.redispi112024 (
	si184_sequencial int8 NOT NULL DEFAULT 0,
	si184_tiporegistro int8 NOT NULL DEFAULT 0,
	si184_codorgaoresp varchar(3) NOT NULL,
	si184_codunidadesubresp varchar(8) NULL,
	si184_codunidadesubrespestadual varchar(4) NULL,
	si184_exercicioprocesso int2 NOT NULL,
	si184_nroprocesso varchar(12) NOT NULL,
	si184_codobralocal int8 NULL,
	si184_tipoprocesso int2 NOT NULL,
	si184_classeobjeto int2 NOT NULL,
	si184_tipoatividadeobra int2 NULL,
	si184_tipoatividadeservico int2 NULL,
	si184_dscatividadeservico varchar(150) NULL,
	si184_tipoatividadeservespecializado int2 NULL,
	si184_dscatividadeservespecializado varchar(150) NULL,
	si184_codfuncao bpchar(2) NOT NULL,
	si184_codsubfuncao bpchar(3) NOT NULL,
	si184_codbempublico int2 NOT NULL,
	si184_reg10 int8 NOT NULL DEFAULT 0,
	si184_mes int8 NOT NULL DEFAULT 0,
	si184_instit int8 NULL DEFAULT 0,
	CONSTRAINT redispi112024_sequ_pk PRIMARY KEY (si184_sequencial),
	CONSTRAINT redispi112024_reg10_fk FOREIGN KEY (si184_reg10) REFERENCES public.redispi102024(si183_sequencial)
);





CREATE TABLE public.redispi122024 (
	si185_sequencial int8 NOT NULL DEFAULT 0,
	si185_tiporegistro int8 NOT NULL DEFAULT 0,
	si185_codorgaoresp bpchar(3) NOT NULL,
	si185_codunidadesubresp varchar(8) NULL,
	si185_codunidadesubrespestadual varchar(4) NULL,
	si185_exercicioprocesso int2 NOT NULL DEFAULT 0,
	si185_nroprocesso bpchar(12) NOT NULL,
	si185_codobralocal int8 NULL,
	si185_logradouro varchar(100) NOT NULL,
	si185_numero int2 NULL,
	si185_bairro varchar(100) NULL,
	si185_distrito varchar(100) NULL,
	si185_cidade varchar(100) NOT NULL,
	si185_cep bpchar(8) NOT NULL,
	si185_reg10 int8 NOT NULL DEFAULT 0,
	si185_mes int8 NOT NULL DEFAULT 0,
	si185_instit int8 NULL DEFAULT 0,
	si185_latitude numeric NULL,
	si185_longitude numeric NULL,
	si185_codbempublico int4 NULL,
	CONSTRAINT redispi122024_sequ_pk PRIMARY KEY (si185_sequencial),
	CONSTRAINT redispi122024_reg10_fk FOREIGN KEY (si185_reg10) REFERENCES public.redispi102024(si183_sequencial)
);





CREATE TABLE public.regadesao112024 (
	si68_sequencial int8 NOT NULL DEFAULT 0,
	si68_tiporegistro int8 NOT NULL DEFAULT 0,
	si68_codorgao varchar(2) NOT NULL,
	si68_codunidadesub varchar(8) NOT NULL,
	si68_nroprocadesao varchar(12) NOT NULL,
	si68_exercicioadesao int8 NOT NULL DEFAULT 0,
	si68_nrolote int8 NOT NULL DEFAULT 0,
	si68_dsclote varchar(250) NOT NULL,
	si68_mes int8 NOT NULL DEFAULT 0,
	si68_reg10 int8 NOT NULL DEFAULT 0,
	si68_instit int8 NULL DEFAULT 0,
	CONSTRAINT regadesao112024_sequ_pk PRIMARY KEY (si68_sequencial),
	CONSTRAINT regadesao112024_reg10_fk FOREIGN KEY (si68_reg10) REFERENCES public.regadesao102024(si67_sequencial)
);
CREATE INDEX regadesao112024_si68_reg10_index ON public.regadesao112024 USING btree (si68_reg10);





CREATE TABLE public.regadesao122024 (
	si69_sequencial int8 NOT NULL DEFAULT 0,
	si69_tiporegistro int8 NOT NULL DEFAULT 0,
	si69_codorgao varchar(2) NOT NULL,
	si69_codunidadesub varchar(8) NOT NULL,
	si69_nroprocadesao varchar(12) NOT NULL,
	si69_exercicioadesao int8 NOT NULL DEFAULT 0,
	si69_coditem int8 NOT NULL DEFAULT 0,
	si69_nroitem int8 NOT NULL DEFAULT 0,
	si69_mes int8 NOT NULL DEFAULT 0,
	si69_reg10 int8 NOT NULL DEFAULT 0,
	si69_instit int8 NULL DEFAULT 0,
	CONSTRAINT regadesao122024_sequ_pk PRIMARY KEY (si69_sequencial),
	CONSTRAINT regadesao122024_reg10_fk FOREIGN KEY (si69_reg10) REFERENCES public.regadesao102024(si67_sequencial)
);
CREATE INDEX regadesao122024_si69_reg10_index ON public.regadesao122024 USING btree (si69_reg10);





CREATE TABLE public.regadesao132024 (
	si70_sequencial int8 NOT NULL DEFAULT 0,
	si70_tiporegistro int8 NOT NULL DEFAULT 0,
	si70_codorgao varchar(2) NOT NULL,
	si70_codunidadesub varchar(8) NOT NULL,
	si70_nroprocadesao varchar(12) NOT NULL,
	si70_exercicioadesao int8 NOT NULL DEFAULT 0,
	si70_nrolote int8 NOT NULL DEFAULT 0,
	si70_coditem int8 NOT NULL DEFAULT 0,
	si70_mes int8 NOT NULL DEFAULT 0,
	si70_reg10 int8 NOT NULL DEFAULT 0,
	si70_instit int8 NULL DEFAULT 0,
	CONSTRAINT regadesao132024_sequ_pk PRIMARY KEY (si70_sequencial),
	CONSTRAINT regadesao132024_reg10_fk FOREIGN KEY (si70_reg10) REFERENCES public.regadesao102024(si67_sequencial)
);
CREATE INDEX regadesao132024_si70_reg10_index ON public.regadesao132024 USING btree (si70_reg10);





CREATE TABLE public.regadesao142024 (
	si71_sequencial int8 NOT NULL DEFAULT 0,
	si71_tiporegistro int8 NOT NULL DEFAULT 0,
	si71_codorgao varchar(2) NOT NULL,
	si71_codunidadesub varchar(8) NOT NULL,
	si71_nroprocadesao varchar(12) NOT NULL,
	si71_exercicioadesao int8 NOT NULL DEFAULT 0,
	si71_nrolote int8 NULL DEFAULT 0,
	si71_coditem int8 NOT NULL DEFAULT 0,
	si71_dtcotacao date NOT NULL,
	si71_vlcotprecosunitario float8 NOT NULL DEFAULT 0,
	si71_quantidade float8 NOT NULL DEFAULT 0,
	si71_mes int8 NOT NULL DEFAULT 0,
	si71_reg10 int8 NOT NULL DEFAULT 0,
	si71_instit int8 NULL DEFAULT 0,
	CONSTRAINT regadesao142024_sequ_pk PRIMARY KEY (si71_sequencial),
	CONSTRAINT regadesao142024_reg10_fk FOREIGN KEY (si71_reg10) REFERENCES public.regadesao102024(si67_sequencial)
);
CREATE INDEX regadesao142024_si71_reg10_index ON public.regadesao142024 USING btree (si71_reg10);





CREATE TABLE public.regadesao152024 (
	si72_sequencial int8 NOT NULL DEFAULT 0,
	si72_tiporegistro int8 NOT NULL DEFAULT 0,
	si72_codorgao varchar(2) NOT NULL,
	si72_codunidadesub varchar(8) NOT NULL,
	si72_nroprocadesao varchar(12) NOT NULL,
	si72_exercicioadesao int8 NOT NULL DEFAULT 0,
	si72_nrolote int8 NULL DEFAULT 0,
	si72_coditem int8 NOT NULL DEFAULT 0,
	si72_precounitario float8 NOT NULL DEFAULT 0,
	si72_quantidadelicitada float8 NOT NULL DEFAULT 0,
	si72_quantidadeaderida float8 NOT NULL DEFAULT 0,
	si72_tipodocumento int8 NOT NULL DEFAULT 0,
	si72_nrodocumento varchar(14) NOT NULL,
	si72_mes int8 NOT NULL DEFAULT 0,
	si72_reg10 int8 NOT NULL DEFAULT 0,
	si72_instit int8 NULL DEFAULT 0,
	CONSTRAINT regadesao152024_sequ_pk PRIMARY KEY (si72_sequencial),
	CONSTRAINT regadesao152024_reg10_fk FOREIGN KEY (si72_reg10) REFERENCES public.regadesao102024(si67_sequencial)
);
CREATE INDEX regadesao152024_si72_reg10_index ON public.regadesao152024 USING btree (si72_reg10);





CREATE TABLE public.rpsd112024 (
	si190_sequencial int8 NOT NULL DEFAULT 0,
	si190_tiporegistro int8 NOT NULL DEFAULT 0,
	si190_codreduzidorsp int8 NOT NULL DEFAULT 0,
	si190_codfontrecursos int8 NOT NULL DEFAULT 0,
	si190_vlpagofontersp float8 NOT NULL DEFAULT 0,
	si190_reg10 int8 NOT NULL DEFAULT 0,
	si190_mes int8 NOT NULL DEFAULT 0,
	si190_instit int8 NULL DEFAULT 0,
	CONSTRAINT rpsd112024_sequ_pk PRIMARY KEY (si190_sequencial),
	CONSTRAINT rpsd112024_reg10_fk FOREIGN KEY (si190_reg10) REFERENCES public.rpsd102024(si189_sequencial)
);





CREATE TABLE public.rsp112024 (
	si113_sequencial int8 NOT NULL DEFAULT 0,
	si113_tiporegistro int8 NOT NULL DEFAULT 0,
	si113_codreduzidorsp int8 NOT NULL DEFAULT 0,
	si113_codfontrecursos int8 NOT NULL DEFAULT 0,
	si113_vloriginalfonte float8 NOT NULL DEFAULT 0,
	si113_vlsaldoantprocefonte float8 NOT NULL DEFAULT 0,
	si113_vlsaldoantnaoprocfonte float8 NOT NULL DEFAULT 0,
	si113_mes int8 NOT NULL DEFAULT 0,
	si113_reg10 int8 NOT NULL DEFAULT 0,
	si113_instit int8 NULL DEFAULT 0,
	si113_codco varchar NOT NULL,
	CONSTRAINT rsp112024_sequ_pk PRIMARY KEY (si113_sequencial),
	CONSTRAINT rsp112024_reg10_fk FOREIGN KEY (si113_reg10) REFERENCES public.rsp102024(si112_sequencial)
);
CREATE INDEX rsp112024_si113_reg10_index ON public.rsp112024 USING btree (si113_reg10);





CREATE TABLE public.rsp122024 (
	si114_sequencial int8 NOT NULL DEFAULT 0,
	si114_tiporegistro int8 NOT NULL DEFAULT 0,
	si114_codreduzidorsp int8 NOT NULL DEFAULT 0,
	si114_tipodocumento int8 NOT NULL DEFAULT 0,
	si114_nrodocumento varchar(14) NOT NULL,
	si114_mes int8 NOT NULL DEFAULT 0,
	si114_reg10 int8 NOT NULL DEFAULT 0,
	si114_instit int8 NULL DEFAULT 0,
	CONSTRAINT rsp122024_sequ_pk PRIMARY KEY (si114_sequencial),
	CONSTRAINT rsp122024_reg10_fk FOREIGN KEY (si114_reg10) REFERENCES public.rsp102024(si112_sequencial)
);
CREATE INDEX rsp122024_si114_reg10_index ON public.rsp122024 USING btree (si114_reg10);





CREATE TABLE public.rsp212024 (
	si116_sequencial int8 NOT NULL DEFAULT 0,
	si116_tiporegistro int8 NOT NULL DEFAULT 0,
	si116_codreduzidomov int8 NOT NULL DEFAULT 0,
	si116_codfontrecursos int8 NOT NULL DEFAULT 0,
	si116_vlmovimentacaofonte float8 NOT NULL DEFAULT 0,
	si116_mes int8 NOT NULL DEFAULT 0,
	si116_reg20 int8 NOT NULL DEFAULT 0,
	si116_instit int8 NULL DEFAULT 0,
	si116_codco varchar NOT NULL,
	si116_codidentificafr int4 NULL,
	CONSTRAINT rsp212024_sequ_pk PRIMARY KEY (si116_sequencial),
	CONSTRAINT rsp212024_reg20_fk FOREIGN KEY (si116_reg20) REFERENCES public.rsp202024(si115_sequencial)
);
CREATE INDEX rsp212024_si116_reg20_index ON public.rsp212024 USING btree (si116_reg20);





CREATE TABLE public.rsp222024 (
	si117_sequencial int8 NOT NULL DEFAULT 0,
	si117_tiporegistro int8 NOT NULL DEFAULT 0,
	si117_codreduzidomov int8 NOT NULL DEFAULT 0,
	si117_tipodocumento int8 NOT NULL DEFAULT 0,
	si117_nrodocumento varchar(14) NOT NULL,
	si117_mes int8 NOT NULL DEFAULT 0,
	si117_reg20 int8 NOT NULL DEFAULT 0,
	si117_instit int8 NULL DEFAULT 0,
	CONSTRAINT rsp222024_sequ_pk PRIMARY KEY (si117_sequencial),
	CONSTRAINT rsp222024_reg20_fk FOREIGN KEY (si117_reg20) REFERENCES public.rsp202024(si115_sequencial)
);
CREATE INDEX rsp222024_si117_reg20_index ON public.rsp222024 USING btree (si117_reg20);





CREATE TABLE public.tce112024 (
	si188_sequencial int8 NOT NULL DEFAULT 0,
	si188_tiporegistro int8 NOT NULL DEFAULT 0,
	si188_numprocessotce varchar(12) NOT NULL,
	si188_datainstauracaotce date NOT NULL,
	si188_tipodocumentorespdano int8 NOT NULL DEFAULT 0,
	si188_nrodocumentorespdano varchar(14) NOT NULL,
	si188_mes int8 NOT NULL DEFAULT 0,
	si188_reg10 int8 NOT NULL DEFAULT 0,
	si188_instit int8 NULL DEFAULT 0,
	CONSTRAINT tce112024_sequ_pk PRIMARY KEY (si188_sequencial),
	CONSTRAINT tce112024_reg10_fk FOREIGN KEY (si188_reg10) REFERENCES public.tce112024(si188_sequencial)
);

SQL;
        $this->execute($sql);
    }

}
