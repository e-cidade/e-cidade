<?php

use Phinx\Migration\AbstractMigration;

class Oc23030 extends AbstractMigration
{
    public function up()
    {

        $sqlOrdenador = $this->query("SELECT 1 FROM configuracoes.db_syscampo WHERE nomecam = 'e30_buscarordenadores' AND descricao = 'Buscar Ordenadores'");
        $result       = $sqlOrdenador->fetchAll(\PDO::FETCH_ASSOC);

        if ($result){
            $sql = "
            BEGIN;
                UPDATE configuracoes.db_syscampo
                    SET descricao='Buscar Ordenador da Despesa', rotulo='Buscar Ordenador da Despesa', rotulorel='Buscar Ordenador da Despesa'
                    where
                nomecam = 'e30_buscarordenadores'
                and descricao = 'Buscar Ordenadores';
            COMMIT";
            $this->execute($sql);
        }

        $nomeCampo      = "e30_buscarordenadoresliqui";
        $descricaoCampo = "Buscar Ordenador da Liquidação";
        $nomeArquivo    = "empparametro";
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
                    VALUES ((SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = '{$nomeArquivo}'),
                        (SELECT codcam FROM configuracoes.db_syscampo WHERE nomecam = '{$nomeCampo}'),
                        (SELECT max(seqarq) + 1 FROM configuracoes.db_sysarqcamp WHERE codarq = (SELECT codarq FROM configuracoes.db_sysarquivo WHERE nomearq = '{$nomeArquivo}')), 0);

                    ALTER TABLE empenho.empparametro ADD COLUMN {$nomeCampo} int8;


            COMMIT";

        $this->execute($sql);
        }

        $sqlAno = $this->query("SELECT e39_anousu FROM empenho.empparametro ORDER BY e39_anousu");
        $resulAno = $sqlAno->fetchAll(\PDO::FETCH_ASSOC); // Pega todos os anos como array associativo

        if ($resulAno) {

            foreach ($resulAno as $row) {

                $anoUsu = $row['e39_anousu'];

                $sql = "
                        BEGIN;
                        UPDATE empenho.empparametro
                            SET e30_buscarordenadoresliqui = (
                                SELECT e30_buscarordenadores FROM empenho.empparametro WHERE e39_anousu = $anoUsu limit 1
                            )
                        WHERE e39_anousu = $anoUsu;
                        COMMIT";

                $this->execute($sql);

            }
        }

    }
}
