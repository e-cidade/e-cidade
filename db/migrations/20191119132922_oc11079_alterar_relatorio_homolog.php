<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11079AlterarRelatorioHomolog extends PostgresMigration
{
    public function up()
    {
        $sql = '
        UPDATE db_paragrafopadrao
        SET db61_texto = \'Homologa julgamento proferido pela Comiss�o de Licita��o do processo licitat�rio N� #$l20_edital#/#$l20_datacria# dando outras provid�ncias.\'
        WHERE db61_codparag = 349;

        UPDATE db_paragrafopadrao
        SET db61_texto = \'Fica homologado o julgamento proferido pela comiss�o de licita��o, nomeada pela portaria N� #$l30_portaria# sobre processo de licita��o N� #$l20_edital#/#$l20_datacria# que tem por objeto:\r
#$l20_objeto#\r
        \r
        ITENS:\'
        WHERE db61_codparag = 350;'
        ;

        $this->execute($sql);

    }

    public function down()
    {

    }
}
