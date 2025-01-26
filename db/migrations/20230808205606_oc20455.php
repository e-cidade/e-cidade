<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc20455 extends PostgresMigration
{
    public function up()
    {
        $sql = "
        INSERT INTO db_itensmenu VALUES (
            (SELECT max(id_item)+1 FROM db_itensmenu),
            'Controle de Datas',
            'Controle de Datas',
            '',
            1,
            1,
            'Controle de Datas',
            't');

        INSERT INTO db_menu VALUES(
                                   (SELECT id_item FROM db_itensmenu WHERE descricao LIKE '%(Patrimonial)%'),
                                   (SELECT max(id_item) from db_itensmenu),
                                   5,
                                   1);

        INSERT INTO db_itensmenu VALUES (
                            (SELECT max(id_item)+1 FROM db_itensmenu),
                            'Material / Servi�o',
                            'Material / Servi�o',
                            'm4_controledatas_materialservico.php',
                            1,
                            1,
                            'Material / Servi�o',
                            't');

        INSERT INTO db_menu VALUES(
                    (SELECT id_item FROM db_itensmenu WHERE desctec = 'Controle de Datas'),
                    (SELECT max(id_item) from db_itensmenu),
                    1,
                    1);

        INSERT INTO db_itensmenu VALUES (
                                    (SELECT max(id_item)+1 FROM db_itensmenu),
                                    'Solicita��o de Compra',
                                    'Solicita��o de Compra',
                                    'm4_controledatas_solicitacaocompra.php',
                                    1,
                                    1,
                                    'Solicita��o de Compra',
                                    't');

        INSERT INTO db_menu VALUES(
                    (SELECT id_item FROM db_itensmenu WHERE desctec = 'Controle de Datas'),
                    (SELECT max(id_item) from db_itensmenu),
                    2,
                    1);

        INSERT INTO db_itensmenu VALUES (
                                    (SELECT max(id_item)+1 FROM db_itensmenu),
                                    'Processo de Compra',
                                    'Processo de Compra',
                                    'm4_controledatas_processocompra.php',
                                    1,
                                    1,
                                    'Processo de Compra',
                                    't');

        INSERT INTO db_menu VALUES(
                    (SELECT id_item FROM db_itensmenu WHERE desctec = 'Controle de Datas'),
                    (SELECT max(id_item) from db_itensmenu),
                    3,
                    1);

        INSERT INTO db_itensmenu VALUES (
                                    (SELECT max(id_item)+1 FROM db_itensmenu),
                                    'Or�amentos',
                                    'Or�amentos',
                                    'm4_controledatas_orcamentos.php',
                                    1,
                                    1,
                                    'Or�amentos',
                                    't');

        INSERT INTO db_menu VALUES(
                    (SELECT id_item FROM db_itensmenu WHERE desctec = 'Controle de Datas'),
                    (SELECT max(id_item) from db_itensmenu),
                    4,
                    1);
        ";

        $this->execute($sql);
    }
}
