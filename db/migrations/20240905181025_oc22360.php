<?php

use Phinx\Migration\AbstractMigration;

class Oc22360 extends AbstractMigration
{

    public function up()
    {
        $sSql = "
        BEGIN;

        ALTER TABLE licitemobra ADD COLUMN obr06_solicitacao INT;

        ALTER TABLE licitemobra ADD CONSTRAINT licitemobra_solicita_fk FOREIGN KEY (obr06_solicitacao) REFERENCES solicita(pc10_numero);

        delete from licitemobra where obr06_pcmater in (select distinct obr06_pcmater from licitemobra left join solicitempcmater on pc16_codmater = obr06_pcmater where pc16_codmater is null);

        DO $$
        DECLARE
            -- Variável para armazenar o código da licitação
            v_l20_codigo INTEGER;
        BEGIN
            -- Obtém todos os códigos de licitação para natureza de objeto (1,7)
            FOR v_l20_codigo IN
                SELECT l20_codigo
                FROM liclicita
                WHERE l20_naturezaobjeto IN (1, 7)
            LOOP
                -- Primeira atualização
                UPDATE licitemobra
                SET obr06_solicitacao = subquery.pc11_numero
                FROM (
                    SELECT DISTINCT ON (licitemobra.obr06_sequencial)
                           licitemobra.obr06_sequencial,
                           solicitem.pc11_numero
                    FROM licitemobra
                    INNER JOIN solicitempcmater ON licitemobra.obr06_pcmater = solicitempcmater.pc16_codmater
                    INNER JOIN solicitem ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                    INNER JOIN pcprocitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                    INNER JOIN liclicitem ON pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem 
                        AND liclicitem.l21_ordem = licitemobra.obr06_ordem
                    WHERE liclicitem.l21_codliclicita = v_l20_codigo
                ) subquery
                WHERE licitemobra.obr06_sequencial = subquery.obr06_sequencial;
        
                -- Segunda atualização
                UPDATE licitemobra
                SET obr06_solicitacao = subquery.pc11_numero
                FROM (
                    SELECT DISTINCT ON (licitemobra.obr06_sequencial)
                           licitemobra.obr06_sequencial,
                           solicitem.pc11_numero
                    FROM licitemobra
                    INNER JOIN solicitempcmater ON licitemobra.obr06_pcmater = solicitempcmater.pc16_codmater
                    INNER JOIN solicitem ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                    INNER JOIN pcprocitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                    INNER JOIN liclicitem ON pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem 
                    WHERE liclicitem.l21_codliclicita = v_l20_codigo
                ) subquery
                WHERE licitemobra.obr06_sequencial = subquery.obr06_sequencial 
                  AND licitemobra.obr06_ordem IS NULL;
            END LOOP;
        END $$;

        COMMIT;
        ";
        $this->execute($sSql);
    }
}
