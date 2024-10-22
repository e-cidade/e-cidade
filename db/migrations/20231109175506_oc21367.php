<?php

use Phinx\Migration\AbstractMigration;

class Oc21367 extends AbstractMigration
{
    public function up()
    {
        // Adiciona colunas às tabelas existentes
        $this->addColumns();

        // Cria novas tabelas e outros objetos no banco de dados
        $this->createCgmcnaeTable();
        $this->updateConfigDb();
        $this->createSequencesAndIndexes();
    }

    public function down()
    {
        // Remove colunas das tabelas existentes
        $this->removeColumns();

        // Remove tabelas e outros objetos do banco de dados
        $this->dropCgmcnaeTable();
        $this->revertConfigDbUpdates();
        $this->dropSequencesAndIndexes();
    }

    private function addColumns()
    {
        $this->execute("
            ALTER TABLE protocolo.cgm
            ADD COLUMN z01_dtabertura DATE NULL,
            ADD COLUMN z01_datasituacaoespecial DATE NULL,
            ADD COLUMN z01_anoobito INTEGER NULL,
            ADD COLUMN z01_produtorrural VARCHAR(1) NULL,
            ADD COLUMN z01_situacaocadastral VARCHAR(2) NULL,
            ADD COLUMN z01_situacaoespecial VARCHAR(10) NULL,
            ADD COLUMN z01_tipoestabelecimento VARCHAR(10) NULL,
            ADD COLUMN z01_porte VARCHAR(10) NULL,
            ADD COLUMN z01_optantesimples VARCHAR(10) NULL,
            ADD COLUMN z01_optantemei VARCHAR(10) NULL;

            ALTER TABLE protocolo.cgmjuridico
            ADD COLUMN z08_tipoestabelecimento INTEGER NULL,
            ADD COLUMN z08_porte INTEGER NULL,
            ADD COLUMN z08_optantesimples INTEGER NULL,
            ADD COLUMN z08_capitalsocial VARCHAR(255) NULL,
            ADD COLUMN z08_datasituacaoespecial DATE NULL;
        ");
    }

    private function removeColumns()
    {
        $this->execute("
            ALTER TABLE protocolo.cgm
            DROP COLUMN IF EXISTS z01_dtabertura,
            DROP COLUMN IF EXISTS z01_datasituacaoespecial,
            DROP COLUMN IF EXISTS z01_anoobito,
            DROP COLUMN IF EXISTS z01_produtorrural,
            DROP COLUMN IF EXISTS z01_situacaocadastral,
            DROP COLUMN IF EXISTS z01_situacaoespecial,
            DROP COLUMN IF EXISTS z01_tipoestabelecimento,
            DROP COLUMN IF EXISTS z01_porte,
            DROP COLUMN IF EXISTS z01_optantesimples,
            DROP COLUMN IF EXISTS z01_optantemei;

            ALTER TABLE protocolo.cgmjuridico
            DROP COLUMN IF EXISTS z08_tipoestabelecimento,
            DROP COLUMN IF EXISTS z08_porte,
            DROP COLUMN IF EXISTS z08_optantesimples,
            DROP COLUMN IF EXISTS z08_capitalsocial,
            DROP COLUMN IF EXISTS z08_datasituacaoespecial;
        ");
    }

    private function createCgmcnaeTable()
    {
        $this->execute("
            CREATE TABLE protocolo.cgmcnae (
                z16_sequencial INTEGER NOT NULL DEFAULT 0,
                z16_numcgm INTEGER NOT NULL DEFAULT 0,
                z16_tipo VARCHAR(11),
                z16_cnae INTEGER,
                CONSTRAINT cgmcnae_numcgm_fk FOREIGN KEY (z16_numcgm) REFERENCES protocolo.cgm(z01_numcgm),
                PRIMARY KEY (z16_sequencial)
            );
        ");
    }

    private function dropCgmcnaeTable()
    {
        $this->execute("
            DROP TABLE IF EXISTS protocolo.cgmcnae;
        ");
    }

    private function updateConfigDb()
    {
        $this->execute("
            ALTER TABLE configuracoes.db_config ADD COLUMN db21_apirfb INTEGER DEFAULT 0;
            
            UPDATE configuracoes.db_syscampo SET tamanho = 7 WHERE codcam = 2011724;
            UPDATE configuracoes.db_syscampo SET tamanho = 8 WHERE codcam = 6240;
        ");
    }

    private function revertConfigDbUpdates()
    {
        $this->execute("
            ALTER TABLE configuracoes.db_config DROP COLUMN IF EXISTS db21_apirfb;
            
            UPDATE configuracoes.db_syscampo SET tamanho = 4 WHERE codcam = 2011724;
            UPDATE configuracoes.db_syscampo SET tamanho = 3 WHERE codcam = 6240;
        ");
    }

    private function createSequencesAndIndexes()
    {
        $this->execute("
            CREATE SEQUENCE protocolo.cgmcnae_z16_sequencial_seq;
            CREATE UNIQUE INDEX cgmcnae_sequ_pk ON protocolo.cgmcnae (z16_sequencial);
            CREATE INDEX cgmcnae_numcgm_in ON protocolo.cgmcnae (z16_numcgm);
        ");
    }

    private function dropSequencesAndIndexes()
    {
        $this->execute("
            DROP SEQUENCE IF EXISTS protocolo.cgmcnae_z16_sequencial_seq;
            DROP INDEX IF EXISTS protocolo.cgmcnae_sequ_pk;
            DROP INDEX IF EXISTS protocolo.cgmcnae_numcgm_in;
        ");
    }
}
