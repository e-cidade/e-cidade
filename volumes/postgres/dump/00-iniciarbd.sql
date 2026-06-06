-- iniciarbd.sql
-- Role histórica do projeto, mantida por compatibilidade com scripts e migrações legadas.
CREATE ROLE ecidade WITH SUPERUSER LOGIN PASSWORD 'ecidade';
CREATE ROLE plugin WITH LOGIN PASSWORD 'plugin';
CREATE ROLE dbportal WITH LOGIN PASSWORD 'dbportal';
CREATE ROLE usersrole WITH LOGIN PASSWORD 'usersrole';
CREATE DATABASE ecidade OWNER ecidade;

-- Configurar search_path para todos os roles do e-Cidade
ALTER ROLE ecidade SET search_path = public, sicom, recursoshumanos, site, empenho, diversos, issqn, secretariadeeducacao, tfd, arrecadacao, agua, ouvidoria, acordos, laboratorio, cadastro, habitacao, vacinas, inflatores, escola, juridico, notificacoes, orcamento, licitacao, projetos, divida, protocolo, itbi, material, merenda, pessoal, prefeitura, agendamento, veiculos, tributario, recursoshumanos, dbpref, farmacia, marcas, cemiterio, transporteescolar, ambulatorial, patrimonio, gestorbi, caixa, fiscal, biblioteca, configuracoes, contabilidade, esocial, compras, contrib, social, custos;

ALTER ROLE dbportal SET search_path = public, sicom, recursoshumanos, site, empenho, diversos, issqn, secretariadeeducacao, tfd, arrecadacao, agua, ouvidoria, acordos, laboratorio, cadastro, habitacao, vacinas, inflatores, escola, juridico, notificacoes, orcamento, licitacao, projetos, divida, protocolo, itbi, material, merenda, pessoal, prefeitura, agendamento, veiculos, tributario, recursoshumanos, dbpref, farmacia, marcas, cemiterio, transporteescolar, ambulatorial, patrimonio, gestorbi, caixa, fiscal, biblioteca, configuracoes, contabilidade, esocial, compras, contrib, social, custos;

ALTER ROLE plugin SET search_path = public, sicom, recursoshumanos, site, empenho, diversos, issqn, secretariadeeducacao, tfd, arrecadacao, agua, ouvidoria, acordos, laboratorio, cadastro, habitacao, vacinas, inflatores, escola, juridico, notificacoes, orcamento, licitacao, projetos, divida, protocolo, itbi, material, merenda, pessoal, prefeitura, agendamento, veiculos, tributario, recursoshumanos, dbpref, farmacia, marcas, cemiterio, transporteescolar, ambulatorial, patrimonio, gestorbi, caixa, fiscal, biblioteca, configuracoes, contabilidade, esocial, compras, contrib, social, custos;

-- Seed minimo para permitir o login local admin/admin em um banco zerado.
\connect ecidade

CREATE SCHEMA IF NOT EXISTS configuracoes;

CREATE SEQUENCE IF NOT EXISTS configuracoes.db_usuarios_id_usuario_seq;
CREATE TABLE IF NOT EXISTS configuracoes.db_usuarios (
  id_usuario integer NOT NULL DEFAULT nextval('configuracoes.db_usuarios_id_usuario_seq'::regclass),
  nome varchar(40) NOT NULL,
  login varchar(20) NOT NULL UNIQUE,
  senha varchar(40) NOT NULL,
  usuarioativo char(1) NOT NULL DEFAULT '1',
  email varchar(200) NOT NULL DEFAULT '',
  usuext integer NOT NULL DEFAULT 0,
  administrador integer NOT NULL DEFAULT 0,
  datatoken date NOT NULL DEFAULT CURRENT_DATE,
  dataexpira date,
  CONSTRAINT db_usuarios_pk PRIMARY KEY (id_usuario)
);
ALTER SEQUENCE configuracoes.db_usuarios_id_usuario_seq OWNED BY configuracoes.db_usuarios.id_usuario;

CREATE TABLE IF NOT EXISTS configuracoes.db_depusu (
  id_usuario integer NOT NULL,
  coddepto integer NOT NULL,
  db17_ordem integer NOT NULL DEFAULT 1,
  CONSTRAINT db_depusu_pk PRIMARY KEY (id_usuario, coddepto)
);

CREATE SEQUENCE IF NOT EXISTS configuracoes.db_versao_db30_codver_seq;
CREATE TABLE IF NOT EXISTS configuracoes.db_versao (
  db30_codver integer NOT NULL DEFAULT nextval('configuracoes.db_versao_db30_codver_seq'::regclass),
  db30_codversao integer NOT NULL DEFAULT 1,
  db30_codrelease integer NOT NULL DEFAULT 1,
  db30_data date,
  db30_obs text,
  CONSTRAINT db_versao_pk PRIMARY KEY (db30_codver)
);
ALTER SEQUENCE configuracoes.db_versao_db30_codver_seq OWNED BY configuracoes.db_versao.db30_codver;

CREATE SEQUENCE IF NOT EXISTS public.db_versao_db30_codver_seq;
CREATE TABLE IF NOT EXISTS public.db_versao (
  db30_codver integer NOT NULL DEFAULT nextval('public.db_versao_db30_codver_seq'::regclass),
  db30_codversao integer NOT NULL DEFAULT 1,
  db30_codrelease integer NOT NULL DEFAULT 1,
  db30_data date,
  db30_obs text,
  CONSTRAINT db_versao_public_pk PRIMARY KEY (db30_codver)
);
ALTER SEQUENCE public.db_versao_db30_codver_seq OWNED BY public.db_versao.db30_codver;

CREATE TABLE IF NOT EXISTS public.db_versaocpd (
  db33_codcpd integer NOT NULL,
  db33_codver integer NOT NULL,
  db33_obs text,
  db33_obscpd text,
  db33_data date,
  CONSTRAINT db_versaocpd_pk PRIMARY KEY (db33_codcpd)
);

CREATE TABLE IF NOT EXISTS public.db_versaousu (
  db32_codusu integer NOT NULL,
  db32_codver integer NOT NULL,
  db32_id_item integer NOT NULL,
  db32_obs text,
  db32_obsdb text,
  db32_data date,
  CONSTRAINT db_versaousu_pk PRIMARY KEY (db32_codusu)
);

CREATE OR REPLACE FUNCTION public.fc_sessionname()
RETURNS text AS $$
  SELECT 'ecidade.'::text;
$$ LANGUAGE sql;

CREATE OR REPLACE FUNCTION public.fc_putsession(text, text)
RETURNS boolean AS $$
  SELECT set_config(public.fc_sessionname() || lower($1), $2, false) = $2;
$$ LANGUAGE sql;

CREATE OR REPLACE FUNCTION public.fc_getsession(text)
RETURNS text AS $$
DECLARE
  sRetorno text;
BEGIN
  BEGIN
    sRetorno := nullif(current_setting(public.fc_sessionname() || lower($1), true), '');
  EXCEPTION
    WHEN others THEN
      sRetorno := null;
  END;
  RETURN sRetorno;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION public.fc_delsession(text)
RETURNS boolean AS $$
  SELECT public.fc_putsession($1, '');
$$ LANGUAGE sql;

CREATE OR REPLACE FUNCTION public.fc_set_pg_search_path()
RETURNS boolean AS $$
  SELECT true;
$$ LANGUAGE sql;

CREATE OR REPLACE FUNCTION public.fc_startsession()
RETURNS boolean AS $$
  SELECT true;
$$ LANGUAGE sql;

CREATE SEQUENCE IF NOT EXISTS configuracoes.db_logsacessa_codsequen_seq;
CREATE TABLE IF NOT EXISTS configuracoes.db_logsacessa (
  codsequen bigint NOT NULL DEFAULT nextval('configuracoes.db_logsacessa_codsequen_seq'::regclass),
  ip varchar(45),
  data date,
  hora time,
  uri text,
  mensagem text,
  id_usuario integer,
  modulo integer,
  item integer,
  coddepto integer,
  instit integer,
  CONSTRAINT db_logsacessa_pk PRIMARY KEY (codsequen)
);
ALTER SEQUENCE configuracoes.db_logsacessa_codsequen_seq OWNED BY configuracoes.db_logsacessa.codsequen;

CREATE SEQUENCE IF NOT EXISTS configuracoes.db_acount_id_acount_seq;
CREATE TABLE IF NOT EXISTS configuracoes.db_acountacesso (
  id_acount bigint NOT NULL,
  id_acessado bigint
);
CREATE TABLE IF NOT EXISTS configuracoes.db_acountkey (
  id_acount bigint NOT NULL,
  codcam integer NOT NULL,
  valor text NOT NULL,
  tipo char(1) NOT NULL
);
CREATE TABLE IF NOT EXISTS configuracoes.db_acount (
  id_acount bigint NOT NULL,
  codarq integer NOT NULL,
  codcam integer NOT NULL,
  valor_ant text,
  valor_novo text,
  data_sessao bigint,
  id_usuario integer
);
ALTER SEQUENCE configuracoes.db_acount_id_acount_seq OWNED BY configuracoes.db_acountacesso.id_acount;

CREATE TABLE IF NOT EXISTS configuracoes.db_sysregrasacesso (
  db46_idacesso integer NOT NULL,
  db46_dtinicio date,
  db46_datafinal date,
  db46_horaini time,
  db46_horafinal time,
  CONSTRAINT db_sysregrasacesso_pk PRIMARY KEY (db46_idacesso)
);
CREATE TABLE IF NOT EXISTS configuracoes.db_sysregrasacessousu (
  db47_idacesso integer NOT NULL,
  db47_id_usuario integer NOT NULL,
  CONSTRAINT db_sysregrasacessousu_pk PRIMARY KEY (db47_idacesso, db47_id_usuario)
);
CREATE TABLE IF NOT EXISTS configuracoes.db_sysregrasacessoip (
  db48_idacesso integer NOT NULL,
  db48_ip varchar(45) NOT NULL,
  CONSTRAINT db_sysregrasacessoip_pk PRIMARY KEY (db48_idacesso, db48_ip)
);
CREATE TABLE IF NOT EXISTS configuracoes.db_sysregrasacessocanc (
  db49_idacesso integer NOT NULL,
  CONSTRAINT db_sysregrasacessocanc_pk PRIMARY KEY (db49_idacesso)
);

INSERT INTO configuracoes.db_sysregrasacesso (
  db46_idacesso, db46_dtinicio, db46_datafinal, db46_horaini, db46_horafinal
) VALUES (
  1,
  DATE '2000-01-01',
  DATE '2099-12-31',
  TIME '00:00:00',
  TIME '23:59:59'
) ON CONFLICT (db46_idacesso) DO UPDATE
  SET db46_dtinicio = EXCLUDED.db46_dtinicio,
      db46_datafinal = EXCLUDED.db46_datafinal,
      db46_horaini = EXCLUDED.db46_horaini,
      db46_horafinal = EXCLUDED.db46_horafinal;

INSERT INTO configuracoes.db_sysregrasacessoip (db48_idacesso, db48_ip)
VALUES (1, '*')
ON CONFLICT DO NOTHING;

INSERT INTO configuracoes.db_usuarios (
  id_usuario, nome, login, senha, usuarioativo, email, usuext, administrador, datatoken, dataexpira
) VALUES (
  1,
  'Administrador',
  'admin',
  '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad',
  '1',
  '',
  0,
  1,
  CURRENT_DATE,
  NULL
) ON CONFLICT (login) DO UPDATE
  SET nome = EXCLUDED.nome,
      senha = EXCLUDED.senha,
      usuarioativo = EXCLUDED.usuarioativo,
      usuext = EXCLUDED.usuext,
      administrador = EXCLUDED.administrador;

INSERT INTO configuracoes.db_depusu (id_usuario, coddepto, db17_ordem)
VALUES (1, 1, 1)
ON CONFLICT DO NOTHING;

INSERT INTO configuracoes.db_versao (db30_codver, db30_codversao, db30_codrelease, db30_data, db30_obs)
VALUES (1, 3, 39, CURRENT_DATE, 'Seed minimo do login admin/admin')
ON CONFLICT (db30_codver) DO UPDATE
  SET db30_codversao = EXCLUDED.db30_codversao,
      db30_codrelease = EXCLUDED.db30_codrelease,
      db30_data = EXCLUDED.db30_data,
      db30_obs = EXCLUDED.db30_obs;

INSERT INTO public.db_versao (db30_codver, db30_codversao, db30_codrelease, db30_data, db30_obs)
VALUES (1, 3, 39, CURRENT_DATE, 'Seed minimo do login admin/admin')
ON CONFLICT (db30_codver) DO UPDATE
  SET db30_codversao = EXCLUDED.db30_codversao,
      db30_codrelease = EXCLUDED.db30_codrelease,
      db30_data = EXCLUDED.db30_data,
      db30_obs = EXCLUDED.db30_obs;

GRANT USAGE ON SCHEMA configuracoes TO ecidade, dbportal;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA configuracoes TO ecidade, dbportal;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA configuracoes TO ecidade, dbportal;
GRANT SELECT, INSERT, UPDATE, DELETE ON public.db_versao TO ecidade, dbportal;
GRANT SELECT, INSERT, UPDATE, DELETE ON public.db_versaocpd TO ecidade, dbportal;
GRANT SELECT, INSERT, UPDATE, DELETE ON public.db_versaousu TO ecidade, dbportal;
GRANT USAGE, SELECT ON SEQUENCE public.db_versao_db30_codver_seq TO ecidade, dbportal;
