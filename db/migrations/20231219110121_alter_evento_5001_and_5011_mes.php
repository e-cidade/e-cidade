<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class AlterEvento5001And5011Mes extends PostgresMigration
{
    public function up()
    {
        $sSql = "ALTER TABLE evt5001consulta ALTER COLUMN rh218_perapurmes DROP NOT NULL;
        ALTER TABLE evt5011consulta ALTER COLUMN rh219_perapurmes DROP NOT NULL;
        ";
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "ALTER TABLE evt5001consulta ALTER COLUMN rh218_perapurmes SET NOT NULL;
        ALTER TABLE evt5011consulta ALTER COLUMN rh219_perapurmes SET NOT NULL;
        ";
        $this->execute($sSql);
    }
}
