<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class InserirMenuEnvio extends PostgresMigration
{

    public function up()
    {
        $sSql = "INSERT INTO db_itensmenu
        VALUES ((SELECT MAX(id_item)+1 FROM db_itensmenu),
            'Exporta��o (Envio)',
            'Exporta��o (Envio)',
            'eso4_envioesocial001.php',
            1,
            1,
            'Exporta��o (Envio)',
            't');

        INSERT INTO db_menu
        VALUES (32,
            (SELECT MAX(id_item) FROM db_itensmenu),
            (SELECT coalesce(MAX(menusequencia),0) FROM db_menu WHERE id_item = 32 and modulo = 10216),
            10216);";

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
        DELETE FROM db_menu WHERE id_item_filho = (SELECT id_item FROM db_itensmenu WHERE funcao = 'eso4_envioesocial001.php');
        DELETE FROM db_itensmenu WHERE funcao = 'eso4_envioesocial001.php';
        ";
        $this->execute($sSql);
    }
}
