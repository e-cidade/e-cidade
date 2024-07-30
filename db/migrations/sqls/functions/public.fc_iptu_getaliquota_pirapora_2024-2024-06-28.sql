
-- FUNCTION: public.fc_iptu_getaliquota_pirapora_2024(integer, integer, integer, double precision, integer, boolean, boolean)
-- DROP FUNCTION IF EXISTS public.fc_iptu_getaliquota_pirapora_2024(integer, integer, integer, double precision, integer, boolean, boolean);

CREATE OR REPLACE FUNCTION public.fc_iptu_getaliquota_pirapora_2024(
    integer, integer, integer, double precision, integer, boolean, boolean
) RETURNS numeric LANGUAGE 'plpgsql' COST 100 VOLATILE PARALLEL UNSAFE AS $BODY$
DECLARE
    iMatricula        ALIAS FOR $1;
    iIdbql            ALIAS FOR $2;
    iNumcgm           ALIAS FOR $3;
    nVv               ALIAS FOR $4;
    iAnousu           ALIAS FOR $5;
    bPredial          ALIAS FOR $6;
    bRaise            ALIAS FOR $7;

    rnAliq            numeric DEFAULT 0;
    cSetor            char(4);
    cQuadra           char(4);
    iCaract           integer DEFAULT 0;
    iImoTerritoriais  integer DEFAULT 0;
    iNumcalculos      integer DEFAULT 0;
    iDestinacao       integer DEFAULT 0;
    iCount            integer DEFAULT 0;
    iFalta            integer DEFAULT 0;
    iAliqAnt          numeric DEFAULT 0;
    nAliqPadrao       numeric DEFAULT 0;
BEGIN
    -- INICIANDO
    IF bRaise THEN
        RAISE NOTICE 'DEFININDO QUAL ALIQUOTA APLICAR ...';
        RAISE NOTICE 'IPTU : %', CASE WHEN bPredial IS TRUE THEN 'PREDIAL' ELSE 'TERRITORIAL' END;
    END IF;

    -- Configuração das Aliquotas baseadas na destinação do imóvel (Caracteristicas do lote, grupo 5), para imóveis edificados:
    -- 18  - Residencial
    -- 19  - Comercial
    -- 35  - Instituições Financeiras
    -- 21,147, 38  - Industrial
    -- 154 - Profissional Liberal
    -- Para Imóveis não edificados, a liquota é determinada apenas pela faixa de valor venal total.

    IF bRaise THEN
        RAISE NOTICE 'Valor Venal Total: %', nVv;
    END IF;

    -- Imóveis não Edificados
    IF bPredial IS FALSE THEN
        IF nVv <= 41524.56 THEN
            rnAliq := 0.11;
        ELSIF nVv > 41524.56 AND nVv <= 166098.24 THEN
            rnAliq := 0.12;
        ELSE
            rnAliq := 0.13;
        END IF;
    ELSE
        select distinct j31_codigo
        from caracter
                 inner join carconstr on j48_caract = j31_codigo
                 inner join iptuconstr on (j39_matric, j39_idcons) = (j48_matric, j48_idcons)
        where j48_matric = iMatricula and j31_grupo = 58 and j39_idprinc is true
        into iDestinacao;

        /* se nao for possivel obter a destinacao da construcao principal, tenta-se obter de qualquer construcao secundaria */
        if iDestinacao is null then
            select j31_codigo
            from caracter
                     inner join carconstr on j48_caract = j31_codigo
            where j48_matric = iMatricula and j31_grupo = 58 limit 1
            into iDestinacao;
        end if;

        /* se mesmo assim nao for possivel obter a destinacao, tenta-se obter pelo lote */
        if iDestinacao is null then
            select j31_codigo
            from caracter
                inner join carlote on j35_caract = j31_codigo
            where j35_idbql = iIdbql and j31_grupo = 5
            into iDestinacao;
        end if;

        /* se mesmo assim nao for possivel obter a destinacao, o padrao sera 18 - Residencial */
        if iDestinacao is null then
            iDestinacao = 18;
        end if;

        IF bRaise THEN
            RAISE NOTICE 'Destinacao do Imovel: %', iDestinacao;
        END IF;

        -- Residencial
        IF iDestinacao IN (18, 30135) THEN
            IF nVv <= 124574.72 THEN
                rnAliq := 0.10;
            ELSIF nVv > 124574.72 AND nVv <= 290672.96 THEN
                rnAliq := 0.12;
            ELSE
                rnAliq := 0.14;
            END IF;
        END IF;

        -- Comercial
        IF iDestinacao IN (19, 20, 32, 34, 30154, 30153, 30146, 30151, 30145, 30141, 30148, 30144, 30140, 30149, 30139, 30137, 30136) THEN
            IF nVv <= 83050.16 THEN
                rnAliq := 0.16;
            ELSIF nVv > 83050.16 AND nVv <= 207623.84 THEN
                rnAliq := 0.20;
            ELSE
                rnAliq := 0.24;
            END IF;
        END IF;

        -- Instituições Financeiras
        IF iDestinacao IN (35, 30152) THEN
            rnAliq := 0.40;
        END IF;

        -- Indústria
        IF iDestinacao IN (21, 38, 147, 3013, 30147) THEN
            IF nVv <= 166099.28 THEN
                rnAliq := 0.12;
            ELSIF nVv > 166099.28 AND nVv <= 415246.64 THEN
                rnAliq := 0.18;
            ELSE
                rnAliq := 0.27;
            END IF;
        END IF;

        -- Profissionais Liberais
        IF iDestinacao IN (154, 30159) THEN
            rnAliq := 0.12;
        END IF;

        RAISE NOTICE 'Aliquota Apurada: %', rnAliq;
    END IF;

    -- CALCULA REDUCAO NA ALIQUOTA CONF ART.48
    IF bRaise THEN
        RAISE NOTICE 'CALCULA DESCONTO IPTU QUANDO O IMÓVEL TIVER MURO OU PASSEIO';
    END IF;

    -- 1) Meio-fio ou calçamento, com canalização de águas pluviais
    PERFORM j38_caract
    FROM testpri
    INNER JOIN carface ON j38_face = j49_face
    WHERE j49_idbql = iIdbql AND j38_caract IN (143, 138);
    IF FOUND THEN
        iCount := iCount + 1;
        IF bRaise THEN
            RAISE NOTICE '1) Meio-fio ou calçamento, com canalização de águas pluviais; Caracteristicas (143, 138); iCount: %', iCount;
        END IF;
    END IF;

    -- 2) Abastecimento de água
    PERFORM j38_caract
    FROM testpri
    INNER JOIN carface ON j38_face = j49_face
    WHERE j49_idbql = iIdbql AND j38_caract IN (135);
    IF FOUND THEN
        iCount := iCount + 1;
        IF bRaise THEN
            RAISE NOTICE '2) Abastecimento de água; Caracteristica 135; iCount: %', iCount;
        END IF;
    END IF;

    -- 3) Sistemas de esgotos sanitários
    PERFORM j38_caract
    FROM testpri
    INNER JOIN carface ON j38_face = j49_face
    WHERE j49_idbql = iIdbql AND j38_caract IN (136, 1362);
    IF FOUND THEN
        iCount := iCount + 1;
        IF bRaise THEN
            RAISE NOTICE '3) Sistemas de esgotos sanitários; Caracteristica 136; iCount: %', iCount;
        END IF;
    END IF;

    -- 4) Rede de iluminação pública, com ou sem posteamento, para distribuição domiciliar
    PERFORM j38_caract
    FROM testpri
    INNER JOIN carface ON j38_face = j49_face
    WHERE j49_idbql = iIdbql AND j38_caract IN (140);
    IF FOUND THEN
        iCount := iCount + 1;
        IF bRaise THEN
            RAISE NOTICE '4) Rede de iluminação pública, com ou sem posteamento, para distribuição domiciliar; Caracteristica 140; iCount: %', iCount;
        END IF;
    END IF;

    iFalta := 4 - iCount;

    IF bRaise THEN
        RAISE NOTICE 'Falta % melhoramentos', iFalta;
    END IF;

    IF iFalta = 3 THEN
        -- DESCONTO DE 30%
        -- rnAliq := (rnAliq-(rnAliq*0.3));
        IF bRaise THEN
            RAISE NOTICE 'Aliquota com DESCONTO de 30 %: ', rnAliq;
        END IF;
    END IF;
    IF iFalta = 2 THEN
        -- DESCONTO DE 20%
        -- rnAliq := (rnAliq-(rnAliq*0.3));
        IF bRaise THEN
            RAISE NOTICE 'Aliquota com DESCONTO de 20 %: ', rnAliq;
        END IF;
    END IF;
    IF iFalta = 1 THEN
        -- DESCONTO DE 10%
        -- rnAliq := (rnAliq-(rnAliq*0.3));
        IF bRaise THEN
            RAISE NOTICE 'Aliquota com DESCONTO de 10 %: ', rnAliq;
        END IF;
    END IF;

    IF bRaise THEN
        RAISE NOTICE 'DEFINIDA ALIQUOTA APLICAR: %', rnAliq;
    END IF;

    RETURN rnAliq;
END;
$BODY$;

ALTER FUNCTION public.fc_iptu_getaliquota_pirapora_2024(integer, integer, integer, double precision, integer, boolean, boolean) OWNER TO dbportal;
