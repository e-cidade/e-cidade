<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Encodata extends PostgresMigration
{

    public function up()
    {
        $sql = "BEGIN;

        UPDATE db_itensmenu SET descricao = 'Ata de Registro de Pre�o', help = 'Ata de Registro de Pre�o', desctec = 'Ata de Registro de Pre�o' WHERE id_item in (select id_item from db_itensmenu WHERE descricao like '%Ata de Registro de Pre%');

        UPDATE db_itensmenu SET descricao = 'Inclus�o', help = 'Inclus�o', desctec = 'Inclus�o', funcao = 'lic1_licataregaba001.php' WHERE funcao = 'lic1_licatareg002.php' AND descricao like '%Inclus%';

        UPDATE db_itensmenu SET descricao = 'Altera��o', help = 'Altera��o', desctec = 'Altera��o', funcao = 'lic1_licataregaba002.php' WHERE funcao = 'lic1_licatareg002.php' AND descricao like '%Altera%';

        UPDATE db_itensmenu SET descricao = 'Exclus�o', help = 'Exclus�o', desctec = 'Exclus�o', funcao = 'lic1_licatareg003.php' WHERE funcao = 'lic1_licatareg002.php' AND descricao like '%Exclus%';

        DELETE FROM db_menu WHERE id_item_filho in (select max(id_item) FROM db_itensmenu WHERE descricao like '%Gerar Autoriz%' AND funcao = 'lic1_licatareg002.php');
        DELETE FROM db_itensmenu WHERE descricao like '%Gerar Autoriz%' and funcao = 'lic1_licatareg002.php';

        DELETE FROM db_menu WHERE id_item_filho in (select max(id_item) FROM db_itensmenu WHERE descricao like '%Reequil%' AND funcao = 'lic1_licatareg002.php');
        DELETE FROM db_itensmenu WHERE descricao like '%Reequil%' AND funcao = 'lic1_licatareg002.php';

        DELETE FROM db_menu WHERE id_item_filho in (select max(id_item) FROM db_itensmenu WHERE descricao like '%Desist%' AND funcao = 'lic1_licatareg002.php');
        DELETE FROM db_itensmenu WHERE descricao like '%Desist%' AND funcao = 'lic1_licatareg002.php';

        UPDATE db_syscampo SET descricao = 'Licita��o', rotulo = 'Licita��o', rotulorel = 'Licita��o' WHERE nomecam = 'l221_licitacao';

        UPDATE db_syscampo SET descricao = 'N�mero da Ata', rotulo = 'N�mero da Ata', rotulorel = 'N�mero da Ata' WHERE nomecam = 'l221_numata';

        UPDATE db_syscampo SET descricao = 'Exerc�cio da Ata', rotulo = 'Exerc�cio da Ata', rotulorel = 'Exerc�cio da Ata' WHERE nomecam = 'l221_exercicio';

        UPDATE db_syscampo SET descricao = 'Data de Publica��o', rotulo = 'Data de Publica��o', rotulorel = 'Data de Publica��o' WHERE nomecam = 'l221_datapublica';

        UPDATE db_syscampo SET descricao = 'Ve�culo de Publica��o', rotulo = 'Ve�culo de Publica��o', rotulorel = 'Ve�culo de Publica��o' WHERE nomecam = 'l221_veiculopublica';

        UPDATE db_syscampo SET descricao = 'Descri��o Item', rotulo = 'Descri��o Item', rotulorel = 'Descri��o Item' WHERE nomecam = 'l222_descricao';

        COMMIT;



        ";




        $this->execute($sql);

    }
}
