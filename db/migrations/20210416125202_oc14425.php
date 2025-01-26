<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc14425 extends PostgresMigration
{
    public function up()
    {
        $this->conciliacaoBancaria();
        $this->createTableConciliacaoBancaria();
        $this->createTableConciliacaoBancariaPendencia();
        $this->createTableConciliacaoBancariaLancamento();
        $this->inserirCamposConciliacaoBancariaPendencia();
        $this->inserirTabelasConciliacaoBancariaPendencia();
    }

    public function conciliacaoBancaria() {
        $nomeCampo = "k29_conciliacaobancaria";
        $sql = "
            BEGIN;
                SELECT fc_startsession();
                -- Inativa menu anterior
                UPDATE db_itensmenu SET itemativo = 2 WHERE id_item IN (2000029, 147883);
                UPDATE db_permissao SET permissaoativa = 2 WHERE id_item IN (2000029, 147883) AND anousu >= 2021;
                DELETE FROM db_menu WHERE id_item = 32 AND id_item_filho = 147883;

                -- Cria novo menu
                INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Concilia��o Banc�ria (Novo)', 'Concilia��o Banc�ria (Novo)', 'cai4_concbancnovo001.php', 1, 1, 'Concilia��o Banc�ria (Novo)', 't');
                INSERT INTO db_menu VALUES (32, (select max(id_item) from db_itensmenu), 464, 39);

                -- Insere novo campo
                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), '{$nomeCampo}', 'date', 'Implanta��o da Concilia��o Banc�ria',
                    NULL, 'Implanta��o da Concilia��o Banc�ria', 10, TRUE, FALSE, FALSE, 1, 'text', 'Implanta��o da Concilia��o Banc�ria');

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'caiparametro'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = '{$nomeCampo}'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'caiparametro')), 0);

                ALTER TABLE caiparametro ADD COLUMN {$nomeCampo} date DEFAULT NULL;
            COMMIT";
        $this->execute($sql);
    }

    public function inserirTabelasConciliacaoBancariaPendencia() {
        $sql = "
            BEGIN;
                SELECT fc_startsession();

                INSERT INTO db_sysarquivo VALUES ((select max(codarq)+1 from db_sysarquivo), 'conciliacaobancariapendencia', 'Armazena as pendencias da conciliacao bancaria', 'k173', '2021-05-20', 'Pendencia - Conciliacao bancaria', 0, FALSE, FALSE, FALSE, FALSE);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_sequencial'),
                    1, 0);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_conta'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_mov'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_tipomovimento', 'int8', 'Tipo de Movimento',
                    NULL, 'Tipo de Movimento', 10, TRUE, FALSE, FALSE, 1, 'text', 'Tipo de Movimento');

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_tipomovimento'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_numcgm'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                    INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_codigo'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_documento'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_data'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_valor'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_observacao'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);

                INSERT INTO db_sysarqcamp
                VALUES ((SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia'),
                    (SELECT codcam FROM db_syscampo WHERE nomecam = 'k173_dataconciliacao'),
                    (SELECT max(seqarq) + 1 FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo WHERE nomearq = 'conciliacaobancariapendencia')), 0);
            COMMIT;";
        $this->execute($sql);
    }

    public function createTableConciliacaoBancariaPendencia()
    {
        $sql = "
            BEGIN;
            SELECT fc_startsession();
            CREATE TABLE caixa.conciliacaobancariapendencia (
                k173_sequencial serial,
                k173_conta int8,
                k173_tipolancamento int4,
                k173_mov int8,
                k173_tipomovimento varchar,
                k173_numcgm int8,
                k173_codigo varchar,
                k173_documento varchar,
                k173_data date,
                k173_valor float8,
                k173_historico text,
                k173_dataconciliacao date
            );
            COMMIT;";
        $this->execute($sql);
    }

    public function createTableConciliacaoBancaria()
    {
        $sql = "
            BEGIN;
            SELECT fc_startsession();
            CREATE TABLE caixa.conciliacaobancaria (
                k171_conta int8,
                k171_saldo float4,
                k171_data date,
                k171_dataconciliacao date
            );
            COMMIT;";
        $this->execute($sql);
    }

    public function createTableConciliacaoBancariaLancamento()
    {
        $sql = "
            BEGIN;
            SELECT fc_startsession();
            CREATE TABLE caixa.conciliacaobancarialancamento (
                k172_conta int8,
                k172_data date,
                k172_numcgm int8,
                k172_coddoc int8,
                k172_codigo varchar,
                k172_valor float8,
                k172_dataconciliacao date,
                k172_mov int8
            );
            COMMIT;";
        $this->execute($sql);
    }

    public function inserirCamposConciliacaoBancariaPendencia()
    {
        $sql = "
            BEGIN;
                SELECT fc_startsession();
                -- Insere novo campo
                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_sequencial', 'int8', 'Sequencial',
                    NULL, 'Sequencial', 10, TRUE, FALSE, FALSE, 1, 'text', 'Sequencial');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_conta', 'int8', 'Conta Corrente',
                    NULL, 'Conta Corrente', 10, TRUE, FALSE, FALSE, 1, 'text', 'Conta Corrente');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_tipolancamento', 'int8', 'Tipo de Lan�amento',
                    NULL, 'Tipo de Lan�amento', 10, TRUE, FALSE, FALSE, 1, 'text', 'Tipo de Lan�amento');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_mov', 'int8', 'Tipo de Movimenta��o',
                    NULL, 'Tipo de Movimenta��o', 10, TRUE, FALSE, FALSE, 1, 'text', 'Tipo de Movimenta��o');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_numcgm', 'int8', 'N�mero do CGM',
                    NULL, 'N�mero do CGM', 10, TRUE, FALSE, FALSE, 1, 'text', 'N�mero do CGM');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_codigo', 'string', 'N�mero do C�digo',
                    NULL, 'N�mero do C�digo', 10, TRUE, FALSE, FALSE, 1, 'text', 'N�mero do C�digo');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_documento', 'string', 'N�mero do Documento',
                    NULL, 'N�mero do Documento', 10, TRUE, FALSE, FALSE, 1, 'text', 'N�mero do Documento');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_data', 'date', 'Data do Lan�amento',
                    NULL, 'Data do Lan�amento', 10, TRUE, FALSE, FALSE, 1, 'text', 'Data do Lan�amento');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_valor', 'float8', 'Valor',
                    NULL, 'Valor', 10, TRUE, FALSE, FALSE, 1, 'text', 'Valor');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_observacao', 'text', 'Observa��o',
                    NULL, 'Observa��o', 10, TRUE, FALSE, FALSE, 1, 'text', 'Observa��o');

                INSERT INTO db_syscampo
                VALUES (
                    (SELECT max(codcam) + 1 FROM db_syscampo), 'k173_dataconciliacao', 'date', 'Data da Concilia��o',
                    NULL, 'Data do Concilia��o', 10, TRUE, FALSE, FALSE, 1, 'text', 'Data do Concilia��o');
            COMMIT;";
        $this->execute($sql);
    }
}
