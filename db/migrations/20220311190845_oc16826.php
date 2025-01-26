<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc16826 extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL

        BEGIN;

        SELECT fc_startsession();

        UPDATE db_itensmenu
				SET descricao = 'Anexo III (Fundeb) (at� 2020)',
    				help = 'Anexo III (Fundeb) (at� 2020)',
    				desctec = 'Anexo III (Fundeb) (at� 2020)'
				WHERE id_item = (select id_item from db_itensmenu where descricao = 'ANEXO III (FUNDEB) ');

        UPDATE db_itensmenu
				SET descricao = 'Anexo I Educa��o (at� 2021)',
    				help = 'Anexo I Educa��o (at� 2021)',
    				desctec = 'Anexo I Educa��o (at� 2021)'
				WHERE id_item = (select id_item from db_itensmenu where descricao = 'ANEXO I Educa��o');

        UPDATE db_itensmenu
				SET descricao = 'Anexo II Educa��o (at� 2021)',
    				help = 'Anexo II Educa��o (at� 2021)',
    				desctec = 'Anexo II Educa��o (at� 2021)'
				WHERE id_item = (select id_item from db_itensmenu where descricao = 'ANEXO II Educa��o');

        UPDATE db_itensmenu
				SET descricao = 'Balan�o Or�ament�rio'
				WHERE id_item = (select id_item from db_itensmenu where descricao = 'Exerc�cio 2015' and help = 'Balan�o Or�ament�rio do DCASP - Exerc�cio 2015');


        COMMIT;

SQL;
        $this->execute($sql);
    }
}

