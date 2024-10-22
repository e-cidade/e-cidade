<?php

use Phinx\Migration\AbstractMigration;

class Oc22705 extends AbstractMigration
{
    public function up()
    {
        $nomeCampo      = "e30_buscarordenadores";
        $descricaoCampo = "Buscar Ordenadores";
        $nomeArquivo    = "empenho.empparametro";
        $sqlConsulta    = $this->query("SELECT 1 FROM configuracoes.db_syscampo WHERE nomecam = '{$nomeCampo}' AND descricao = '{$descricaoCampo}'");
        $resultado      = $sqlConsulta->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($resultado)){
            $sql = "
            BEGIN;
                SELECT fc_startsession();

                    -- Insere novo campo
                    INSERT INTO configuracoes.db_syscampo
                    VALUES (
                        (SELECT max(codcam) + 1 FROM configuracoes.db_syscampo), '{$nomeCampo}', 'bool', '{$descricaoCampo}',
                        'f', '{$descricaoCampo}', 1, FALSE, FALSE, FALSE, 5, 'select', '{$descricaoCampo}');

                    INSERT INTO configuracoes.db_sysarqcamp
                    VALUES ((SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = 'empparametro'),
                        (SELECT codcam FROM configuracoes.db_syscampo WHERE nomecam = '{$nomeCampo}'),
                        (SELECT max(seqarq) + 1 FROM configuracoes.db_sysarqcamp WHERE codarq = (SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = 'empparametro')), 0);

                    ALTER TABLE {$nomeArquivo} ADD COLUMN {$nomeCampo} int8 DEFAULT 1;
            COMMIT";

        $this->execute($sql);
        }

        $nomeCampoEmpempenho      = "e60_numcgmordenador";
        $descricaoCampoEmpempenho = "CGM do ordenador";
        $nomeArquivoEmpempenho    = "empenho.empempenho";
        $sqlConsultaEmpempenho    = $this->query("SELECT 1 FROM configuracoes.db_syscampo WHERE nomecam = '{$nomeCampoEmpempenho}' AND descricao = '{$descricaoCampoEmpempenho}'");
        $resultadoEmpempenho      = $sqlConsultaEmpempenho->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($resultadoEmpempenho)){
            $sqlEmpempenho = "
            BEGIN;
                SELECT fc_startsession();

                    -- Insere novo campo
                    INSERT INTO configuracoes.db_syscampo
                    VALUES (
                        (SELECT max(codcam) + 1 FROM configuracoes.db_syscampo), '{$nomeCampoEmpempenho}', 'int8', '{$descricaoCampoEmpempenho}',
                        '', '{$descricaoCampoEmpempenho}', 10, FALSE, FALSE, FALSE, 1, 'int8', '{$descricaoCampoEmpempenho}');

                    INSERT INTO configuracoes.db_sysarqcamp
                    VALUES ((SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = 'empempenho'),
                        (SELECT codcam FROM configuracoes.db_syscampo WHERE nomecam = '{$nomeCampoEmpempenho}'),
                        (SELECT max(seqarq) + 1 FROM configuracoes.db_sysarqcamp WHERE codarq = (SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = 'empempenho')), 0);

                    ALTER TABLE {$nomeArquivoEmpempenho} ADD COLUMN {$nomeCampoEmpempenho} int8;
            COMMIT";

        $this->execute($sqlEmpempenho);
        }

        $nomeCampoPagordem      = "e50_numcgmordenador";
        $descricaoCampoPagordem = "CGM do ordenador";
        $nomeArquivoPagordem    = "empenho.pagordem";
        $sqlConsultaPagordem    = $this->query("SELECT 1 FROM configuracoes.db_syscampo WHERE nomecam = '{$nomeCampoPagordem}' AND descricao = '{$descricaoCampoPagordem}'");
        $resultadoPagordem      = $sqlConsultaPagordem->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($resultadoPagordem)){
            $sqlPagordem = "
            BEGIN;
                SELECT fc_startsession();

                    -- Insere novo campo
                    INSERT INTO configuracoes.db_syscampo
                    VALUES (
                        (SELECT max(codcam) + 1 FROM configuracoes.db_syscampo), '{$nomeCampoPagordem}', 'int8', '{$descricaoCampoPagordem}',
                        '', '{$descricaoCampoPagordem}', 10, FALSE, FALSE, FALSE, 1, 'int8', '{$descricaoCampoPagordem}');

                    INSERT INTO configuracoes.db_sysarqcamp
                    VALUES ((SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = 'pagordem'),
                        (SELECT codcam FROM configuracoes.db_syscampo WHERE nomecam = '{$nomeCampoPagordem}'),
                        (SELECT max(seqarq) + 1 FROM configuracoes.db_sysarqcamp WHERE codarq = (SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = 'pagordem')), 0);

                    ALTER TABLE {$nomeArquivoPagordem} ADD COLUMN {$nomeCampoPagordem} int8;
            COMMIT";

        $this->execute($sqlPagordem);
        }
    }
}
