-- FUNCTION: public.fc_corre(integer, date, double precision, date, integer, date)

-- DROP FUNCTION IF EXISTS public.fc_corre(integer, date, double precision, date, integer, date);

CREATE OR REPLACE FUNCTION public.fc_corre(
  integer,
  date,
  double precision,
  date,
  integer,
  date)
    RETURNS double precision
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
AS $BODY$
DECLARE
  RECE_CORR               ALIAS FOR $1;
  DATA_VENC               ALIAS FOR $2;
  VALOR_B                 ALIAS FOR $3;
  DATA_HOJE               ALIAS FOR $4;
  SUBDIR                  ALIAS FOR $5;
  DTVENC                  ALIAS FOR $6;

  INFLATOR_CORRECAO       varchar(5);
  AUX_INFLATOR_CORRECAO  varchar(5);
  VALOR_RETORNO           float8 := 0;
  VALOR_TOTAL_CORRECAO          float8 := 0;
  DT_VENC                 DATE;
  V_TABREC                RECORD;
  DT_HOJE_FIXO            DATE; --usada para guardar o valor fixo da data_hoje para o calculo quando ha mais de um inflator
  lRaise     boolean default false;
BEGIN

  lRaise := (CASE WHEN fc_getsession('DB_debugon') IS NULL THEN false ELSE true END);
  DT_HOJE_FIXO := DATA_HOJE;
  IF lRaise THEN
    IF trim(fc_getsession('db_debug')) <> '' THEN
      PERFORM fc_debug('  <corre>  - INICIANDO PROCESSAMENTO...', lRaise, false, false);
    ELSE
      PERFORM fc_debug('  <corre>  - INICIANDO PROCESSAMENTO...', lRaise, true, false);
    END IF;
  END IF;

  IF DATA_VENC > DATA_HOJE THEN
    IF lRaise THEN
      PERFORM fc_debug('  <corre>  - Data de Vencimento1 ('||DATA_VENC||') e maior que a data corrente ('||DATA_HOJE||')', lRaise, false, false);
      PERFORM fc_debug('  <corre>  - Retornando valor de correcao o proprio valor da receita: '||round(VALOR_B,2), lRaise, false, false);
    END IF;
    RETURN round(VALOR_B,2);
  END IF;

  IF lRaise THEN
    PERFORM fc_debug('  <corre>  - Verificando o cadastro da receita '||RECE_CORR, lRaise, false, false);
  END IF;

  FOR V_TABREC IN
    SELECT TABREC.*, tabrecjm.*, tabrecregrasjm.k04_dtini, tabrecregrasjm.k04_dtfim
      FROM TABREC
     inner join tabrecregrasjm on tabrecregrasjm.k04_receit = tabrec.k02_codigo
     inner join tabrecjm on tabrecregrasjm.k04_codjm = tabrecjm.k02_codjm
   WHERE K02_CODIGO = RECE_CORR
   and tabrecregrasjm.k04_dtfim >= DATA_VENC order by tabrecregrasjm.k04_dtfim
  LOOP
    DATA_HOJE := DT_HOJE_FIXO; -- resetamos a data hoje para cada inflator
  INFLATOR_CORRECAO := trim(V_TABREC.K02_CORR);

  -- guardamos o inflator para que no proximo loop possamos comparar se houve troca do periodo
  IF AUX_INFLATOR_CORRECAO IS NULL THEN
    AUX_INFLATOR_CORRECAO := INFLATOR_CORRECAO;
  END IF;

  -- se houve troca entao a data de inicio do calculo deve ser V_TABREC.k04_dtini
  IF AUX_INFLATOR_CORRECAO != INFLATOR_CORRECAO THEN
    IF lRaise THEN
        PERFORM fc_debug('  <corre>  - Houve troca da data de vencimento para fins de calculo do novo inflator '||INFLATOR_CORRECAO, lRaise, false, false);
        PERFORM fc_debug('  <corre>  - Nova DTVENC  = '||V_TABREC.k04_dtini, lRaise, false, false);
      END IF;
    DTVENC := V_TABREC.k04_dtini;
  END IF;

    IF lRaise THEN
      PERFORM fc_debug('  <corre>  - Processando registro com K02_CORR = '||INFLATOR_CORRECAO, lRaise, false, false);
      PERFORM fc_debug('  <corre>  - Processando registro com K02_CORVEN = '||V_TABREC.K02_CORVEN, lRaise, false, false);
    END IF;

    IF V_TABREC.K02_CORVEN THEN
      DT_VENC := DTVENC;
    ELSE
      DT_VENC := DATA_VENC;
    END IF;

    IF DT_VENC <= V_TABREC.k04_dtfim THEN
      IF DATA_HOJE > V_TABREC.k04_dtfim THEN
        DATA_HOJE = V_TABREC.k04_dtfim;

        IF lRaise THEN
          PERFORM fc_debug('  <corre>  - Nova DATA_HOJE: '||DATA_HOJE, lRaise, false, false);
        END IF;

      END IF;
    END IF;

  IF lRaise THEN
      PERFORM fc_debug('  <corre>  - Processando registro com INFLATOR_CORRECAO = '||INFLATOR_CORRECAO, lRaise, false, false);
    PERFORM fc_debug('  <corre>  - Processando registro com DTVENC = '||DTVENC, lRaise, false, false);
      PERFORM fc_debug('  <corre>  - Processando registro com DATA_HOJE = '||DATA_HOJE, lRaise, false, false);
    END IF;

    IF INFLATOR_CORRECAO <> 'REAL' THEN
      IF VALOR_B IS NOT NULL AND VALOR_B <> 0 THEN
        VALOR_TOTAL_CORRECAO := VALOR_TOTAL_CORRECAO + fc_INFLA(INFLATOR_CORRECAO, round(VALOR_B,2), DT_VENC, DATA_HOJE) - VALOR_B;
      IF lRaise THEN
      PERFORM fc_debug('  <corre>  - Chamando a funcao fc_infla para calcular o valor da correcao ('||VALOR_B||'), DT_VENC: '||DT_VENC||', DATA_HOJE: '||DATA_HOJE, lRaise, false, false);
          PERFORM fc_debug('  <corre>  - Retorno da funcao fc_infla para calcular o valor da correcao ('||VALOR_B||'): '||VALOR_TOTAL_CORRECAO, lRaise, false, false);
        END IF;
      END IF;
    ELSE
      IF lRaise THEN
        PERFORM fc_debug('  <corre>  - Inflator REAL... Nao calcula correcao', lRaise, false, false);
      END IF;
      VALOR_TOTAL_CORRECAO := VALOR_TOTAL_CORRECAO + VALOR_B;
    END IF;
  END LOOP;

  IF NOT FOUND THEN
    IF lRaise THEN
      PERFORM fc_debug('  <corre>  - Nao encontrou cadastro para a receita '||RECE_CORR, lRaise, false, false);
    END IF;
    RETURN -1;
  END IF;

  IF VALOR_TOTAL_CORRECAO = 0 THEN
    VALOR_RETORNO := VALOR_B;
  ELSE
    VALOR_RETORNO := VALOR_B + VALOR_TOTAL_CORRECAO;
  END IF;

  IF lRaise THEN
    PERFORM fc_debug('  <corre>  - Valor total de correcao retornado: '||VALOR_RETORNO, lRaise, false, false);
  END IF;

  RETURN round(VALOR_RETORNO, 2);
END;
$BODY$;

ALTER FUNCTION public.fc_corre(integer, date, double precision, date, integer, date)
    OWNER TO ecidade;
