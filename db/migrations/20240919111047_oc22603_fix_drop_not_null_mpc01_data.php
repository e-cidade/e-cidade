<?php

use Phinx\Migration\AbstractMigration;

class Oc22603FixDropNotNullMpc01Data extends AbstractMigration
{
    public function up()
    {
        $sql = "
            ALTER TABLE compras.pcplanocontratacao ALTER COLUMN mpc01_data DROP NOT NULL;

            ALTER TABLE compras.pcplanocontratacao DROP CONSTRAINT pcplanocontratacao_mpc01_uncompradora_fk;

            ALTER TABLE
                compras.pcplanocontratacao
                ADD CONSTRAINT pcplanocontratacao_mpc01_uncompradora_fk
                FOREIGN KEY (mpc01_uncompradora) REFERENCES configuracoes.db_config(codigo);

            DO $$
                DECLARE
                    ultimo_codigo INTEGER;
                BEGIN
                    SELECT COALESCE(MAX(mpc01_codigo), 0) INTO ultimo_codigo FROM compras.pcplanocontratacao;
                    PERFORM setval('mpc01_codigo_seq', ultimo_codigo + 1);
            END $$;

        ";

        $this->execute($sql);
    }
}
