<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21264relatorio extends PostgresMigration
{
    public function up()
    {
        $sqlMoveMenu = "SELECT fc_startsession();

                        INSERT INTO db_menu
                        VALUES (
                                    (SELECT id_item FROM db_modulos
                                    WHERE nome_modulo = 'EFD-Reinf'),

                                    30,

                                    1,

                                    (SELECT id_item FROM db_modulos
                                    WHERE nome_modulo = 'EFD-Reinf')
                                );


                        INSERT INTO db_itensmenu
                        VALUES (
                                    (SELECT max(id_item)+1 FROM db_itensmenu),
                                    'Reltat�rios de Confer�ncia',
                                    'Reltat�rio de Confer�ncia do EFD-Reinf',
                                    '',
                                    1,
                                    1,
                                    'Reltat�rio de Confer�ncia do EFD-Reinf',
                                    't'
                                );


                        INSERT INTO db_menu
                        VALUES (
                                    30,
                                    (SELECT id_item FROM db_itensmenu
                                    WHERE descricao = 'Reltat�rios de Confer�ncia'),
                                    1,
                                    (SELECT id_item FROM db_modulos
                                    WHERE nome_modulo = 'EFD-Reinf')
                                );


                        UPDATE db_menu
                        SET id_item = (SELECT id_item FROM db_itensmenu
                        WHERE help = 'Reltat�rio de Confer�ncia do EFD-Reinf'),
                              menusequencia = 1,
                              modulo = (SELECT id_item FROM db_modulos WHERE nome_modulo = 'EFD-Reinf')
                        WHERE id_item_filho IN (SELECT id_item FROM db_itensmenu WHERE descricao = 'EFD-Reinf R-4000')";

        $this->execute($sqlMoveMenu);

    }
}
