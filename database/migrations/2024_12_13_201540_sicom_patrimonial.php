<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SicomPatrimonial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Cria a tabela dentro do schema "licitacao"
        Schema::create('licitacao.statusenviosicom', function (Blueprint $table) {
            $table->id('l225_sequencial');
            $table->string('l225_status', 30);
        });

        // Insere os registros na tabela
        DB::table('licitacao.statusenviosicom')->insert([
            ['l225_sequencial' => 1, 'l225_status' => 'Abertura Pendente'],
            ['l225_sequencial' => 2, 'l225_status' => 'Julgamento Pendente'],
            ['l225_sequencial' => 3, 'l225_status' => 'Homologação Pendente'],
            ['l225_sequencial' => 4, 'l225_status' => 'Envio Pendente'],
            ['l225_sequencial' => 5, 'l225_status' => 'Enviado'],
        ]);

        DB::statement("
            ALTER TABLE licitacao.liclicita 
            ADD COLUMN l20_statusenviosicom INT NULL,
            ADD CONSTRAINT fk_l20_statusenviosicom
            FOREIGN KEY (l20_statusenviosicom)
            REFERENCES licitacao.statusenviosicom (l225_sequencial)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ");

        DB::statement("
            ALTER TABLE adesaoregprecos
            ADD COLUMN si06_statusenviosicom INT NULL,
            ADD CONSTRAINT fk_si06_statusenviosicom
            FOREIGN KEY (si06_statusenviosicom)
            REFERENCES licitacao.statusenviosicom (l225_sequencial)
            ON DELETE SET NULL
            ON UPDATE CASCADE
        ");

        // Executa cada comando SQL separadamente
        DB::statement("
            INSERT INTO db_sysarquivo 
            VALUES (
                (SELECT max(codarq)+1 FROM db_sysarquivo),
                'statusenviosicom',
                'status do envio do sicom',
                'l225',
                '2024-12-13',
                'status do envio do sicom',
                0,
                'f',
                'f',
                'f',
                'f'
            );
        ");

        DB::statement("
            INSERT INTO db_syscampo 
            VALUES (
                (SELECT max(codcam)+1 FROM db_syscampo), 
                'l225_sequencial',
                'int8',
                'Cód. Sequencial',
                '',
                'Cód. Sequencial',
                11,
                false,
                false,
                false,
                1,
                'int8',
                'Cód. Sequencial'
            );
        ");

        DB::statement("
            INSERT INTO db_syscampo 
            VALUES (
                (SELECT max(codcam)+1 FROM db_syscampo), 
                'l225_status',
                'varchar(30)',
                'Status',
                '',
                'Status',
                30,
                false,
                false,
                false,
                0,
                'varchar(30)',
                'Status'
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l225_sequencial'), 
                1, 
                0
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l225_status'), 
                2, 
                0
            );
        ");

        // Primeiro SELECT: Atualizar l20_statusenviosicom para 4
        DB::statement("
            UPDATE licitacao.liclicita
            SET l20_statusenviosicom = 4
            WHERE l20_codtipocom IN (
                SELECT l03_codigo
                FROM licitacao.cflicita
                WHERE l03_pctipocompratribunal IN (100, 101, 102, 103)
            )
            AND l20_dtpubratificacao > '2024-12-31'
        ");

        // Terceiro SELECT: Atualizar l20_statusenviosicom para 1
        DB::statement("
            UPDATE licitacao.liclicita
            SET l20_statusenviosicom = 1
            WHERE l20_codtipocom IN (
                SELECT l03_codigo
                FROM licitacao.cflicita
                WHERE l03_pctipocompratribunal IN (28, 30, 32, 34, 36, 52, 54, 110)
            )
            AND l20_licsituacao = 0
            AND l20_dtpublic > '2024-12-31'
        ");

        // Quarto SELECT: Atualizar l20_statusenviosicom para 1
        DB::statement("
            UPDATE licitacao.liclicita
            SET l20_statusenviosicom = 1
            WHERE l20_codtipocom IN (
                SELECT l03_codigo
                FROM licitacao.cflicita
                WHERE l03_pctipocompratribunal IN (28, 30, 32, 34, 36, 52, 54, 110)
            )
            AND l20_licsituacao = 1
            AND EXISTS (
                SELECT 1
                FROM licitacao.liclicitasituacao
                WHERE l11_liclicita = l20_codigo
                AND l11_licsituacao = l20_licsituacao
                AND l11_data > '2024-12-31'
            )
        ");

        // Quinto SELECT: Atualizar l20_statusenviosicom para 1
        DB::statement("
            UPDATE licitacao.liclicita
            SET l20_statusenviosicom = 1
            WHERE l20_codtipocom IN (
                SELECT l03_codigo
                FROM licitacao.cflicita
                WHERE l03_pctipocompratribunal IN (28, 30, 32, 34, 36, 52, 54, 110)
            )
            AND l20_licsituacao = 10
            AND EXISTS (
                SELECT 1
                FROM licitacao.homologacaoadjudica
                WHERE l202_licitacao = l20_codigo
                AND l202_datahomologacao IS NOT NULL
            )
        ");

        // Segundo SELECT: Atualizar si06_statusenviosicom para 4
        DB::statement("
                UPDATE adesaoregprecos
                SET si06_statusenviosicom = 4
                WHERE si06_dataadesao > '2024-12-31'
            ");

        // Criar a tabela licitacao.arquivossicom
        DB::statement("
            CREATE TABLE licitacao.arquivossicom (
                l226_sequencial SERIAL PRIMARY KEY,
                l226_nome VARCHAR(50) NOT NULL UNIQUE
            );
        ");

        // Inserir valores iniciais na tabela licitacao.arquivossicom
        DB::table('licitacao.arquivossicom')->insert([
            ['l226_nome' => 'IDE'],
            ['l226_nome' => 'REGLIC'],
            ['l226_nome' => 'ABERLIC'],
            ['l226_nome' => 'RESPLIC'],
            ['l226_nome' => 'PARTLIC'],
            ['l226_nome' => 'HABLIC'],
            ['l226_nome' => 'PARELIC'],
            ['l226_nome' => 'JULGLIC'],
            ['l226_nome' => 'HOMOLIC'],
            ['l226_nome' => 'DISPENSA'],
            ['l226_nome' => 'REGADESAO'],
            ['l226_nome' => 'CONSID'],
        ]);

        // Criar a tabela licitacao.remessasicom
        DB::statement("
            CREATE TABLE licitacao.remessasicom (
                l227_sequencial SERIAL PRIMARY KEY,
                l227_licitacao INT NULL,
                l227_adesao INT NULL,
                l227_arquivo INT NOT NULL,
                l227_remessa INT NOT NULL,
                l227_dataenvio DATE NOT NULL,
                l227_usuario INT NOT NULL,
                CONSTRAINT fk_licitacao FOREIGN KEY (l227_licitacao) REFERENCES licitacao.liclicita(l20_codigo) ON DELETE CASCADE,
                CONSTRAINT fk_adesao FOREIGN KEY (l227_adesao) REFERENCES adesaoregprecos(si06_sequencial) ON DELETE CASCADE,
                CONSTRAINT fk_arquivo FOREIGN KEY (l227_arquivo) REFERENCES licitacao.arquivossicom(l226_sequencial) ON DELETE RESTRICT,
                CONSTRAINT fk_usuario FOREIGN KEY (l227_usuario) REFERENCES protocolo.cgm(z01_numcgm) ON DELETE CASCADE
            );
        ");

        DB::statement("
            INSERT INTO db_sysarquivo 
            VALUES (
                (SELECT max(codarq)+1 FROM db_sysarquivo),
                'arquivossicom',
                'arquivos do sicom patrimonial',
                'l226',
                '2024-12-13',
                'arquivos do sicom patrimonial',
                0,
                'f',
                'f',
                'f',
                'f'
            );
        ");

        DB::statement("
            INSERT INTO db_syscampo 
            VALUES (
                (SELECT max(codcam)+1 FROM db_syscampo), 
                'l226_sequencial',
                'int8',
                'Cód. Sequencial',
                '',
                'Cód. Sequencial',
                11,
                false,
                false,
                false,
                1,
                'int8',
                'Cód. Sequencial'
            );
        ");

        DB::statement("
            INSERT INTO db_syscampo 
            VALUES (
                (SELECT max(codcam)+1 FROM db_syscampo), 
                'l226_nome',
                'varchar(50)',
                'nome do arquivo',
                '',
                'nome do arquivo',
                50,
                false,
                false,
                false,
                0,
                'varchar(50)',
                'nome do arquivo'
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l226_sequencial'), 
                1, 
                0
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l226_nome'), 
                2, 
                0
            );
        ");

        DB::statement("
        INSERT INTO db_sysarquivo 
        VALUES (
            (SELECT max(codarq)+1 FROM db_sysarquivo),
            'remessasicom',
            'remessas do sicom patrimonial',
            'l227',
            '2024-12-13',
            'remessas do sicom patrimonial',
            0,
            'f',
            'f',
            'f',
            'f'
        );
    ");

        DB::statement("
        INSERT INTO db_syscampo 
        VALUES (
            (SELECT max(codcam)+1 FROM db_syscampo), 
            'l227_sequencial',
            'int8',
            'Cód. Sequencial',
            '',
            'Cód. Sequencial',
            11,
            false,
            false,
            false,
            1,
            'int8',
            'Cód. Sequencial'
        );
    ");

        DB::statement("
        INSERT INTO db_syscampo 
        VALUES (
            (SELECT max(codcam)+1 FROM db_syscampo), 
            'l227_licitacao',
            'int',
            'Seq. Licitacao',
            '',
            'Seq. Licitacao',
            11,
            false,
            false,
            false,
            1,
            'int',
            'Seq. Licitacao'
        );
    ");

        DB::statement("
        INSERT INTO db_syscampo 
        VALUES (
            (SELECT max(codcam)+1 FROM db_syscampo), 
            'l227_adesao',
            'int',
            'Seq. Adesao',
            '',
            'Seq. Adesao',
            11,
            false,
            false,
            false,
            1,
            'int',
            'Seq. Adesao'
        );
    ");

        DB::statement("
        INSERT INTO db_syscampo 
        VALUES (
            (SELECT max(codcam)+1 FROM db_syscampo), 
            'l227_arquivo',
            'int',
            'Seq. Arquivo',
            '',
            'Seq. Arquivo',
            11,
            false,
            false,
            false,
            1,
            'int',
            'Seq. Arquivo'
        );
    ");

        DB::statement("
        INSERT INTO db_syscampo 
        VALUES (
            (SELECT max(codcam)+1 FROM db_syscampo), 
            'l227_remessa',
            'int',
            'codigo remessa',
            '',
            'codigo remessa',
            11,
            false,
            false,
            false,
            1,
            'int',
            'codigo remessa'
        );
    ");

        DB::statement("
        INSERT INTO db_syscampo 
        VALUES (
            (SELECT max(codcam)+1 FROM db_syscampo), 
            'l227_dataenvio',
            'date',
            'data remessa',
            '',
            'data remessa',
            11,
            false,
            false,
            false,
            1,
            'date',
            'data remessa'
        );
    ");

        DB::statement("
            INSERT INTO db_syscampo 
            VALUES (
                (SELECT max(codcam)+1 FROM db_syscampo), 
                'l227_usuario',
                'int',
                'usuario',
                '',
                'usuario',
                11,
                false,
                false,
                false,
                1,
                'int',
                'usuario'
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l227_sequencial'), 
                1, 
                0
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l227_licitacao'), 
                2, 
                0
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l227_adesao'), 
                3, 
                0
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l227_arquivo'), 
                4, 
                0
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l227_remessa'), 
                5, 
                0
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l227_dataenvio'), 
                6, 
                0
            );
        ");

        DB::statement("
            INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) 
            VALUES (
                (SELECT max(codarq) FROM db_sysarquivo), 
                (SELECT codcam FROM db_syscampo WHERE nomecam = 'l227_usuario'), 
                7, 
                0
            );
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove os dados do dicionário de dados
        DB::statement("
            DELETE FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo where nomearq = 'statusenviosicom');
        ");
        DB::statement("
            DELETE FROM db_syscampo WHERE nomecam IN ('l225_sequencial', 'l225_status');
        ");
        DB::statement("
            DELETE FROM db_sysarquivo WHERE nomearq = 'statusenviosicom';
        ");

        // Remove a tabela
        Schema::dropIfExists('licitacao.statusenviosicom');

        DB::statement("
            DELETE FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo where nomearq = 'arquivossicom');
        ");
        DB::statement("
            DELETE FROM db_syscampo WHERE nomecam IN ('l226_sequencial', 'l226_nome');
        ");
        DB::statement("
            DELETE FROM db_sysarquivo WHERE nomearq = 'arquivossicom';
        ");

        DB::statement("
            DELETE FROM db_sysarqcamp WHERE codarq = (SELECT codarq FROM db_sysarquivo where nomearq = 'remessasicom');
        ");

        DB::statement("
            DELETE FROM db_syscampo WHERE nomecam LIKE 'l227%';
        ");

        DB::statement("
            DELETE FROM db_sysarquivo WHERE nomearq = 'remessasicom';
        ");

        DB::statement("
            ALTER TABLE licitacao.liclicita 
            DROP CONSTRAINT fk_l20_statusenviosicom,
            DROP COLUMN l20_statusenviosicom
        ");

        DB::statement("
            ALTER TABLE adesaoregprecos
            DROP CONSTRAINT fk_si06_statusenviosicom,
            DROP COLUMN si06_statusenviosicom
        ");

        DB::statement("UPDATE licitacao.liclicita SET l20_statusenviosicom = NULL");
        DB::statement("UPDATE adesaoregprecos SET si06_statusenviosicom = NULL");
        DB::statement("DROP TABLE IF EXISTS licitacao.remessasicom;");
        DB::statement("DROP TABLE IF EXISTS licitacao.arquivossicom;");
    }
}
