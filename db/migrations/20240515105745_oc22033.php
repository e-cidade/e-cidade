<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class oc22033 extends PostgresMigration
{

    public function up()
    {
        $this->criaMenu();
    }

    public function down()
    {
        $this->restauraMenu();
    }

    public function criaMenu(){
            $sql = " INSERT INTO configuracoes.db_itensmenu
                                (id_item,
                                 descricao,
                                 help,
                                 funcao,
                                 itemativo,
                                 manutencao,
                                 desctec,
                                 libcliente)
                    VALUES      ((SELECT Max(id_item) + 1
                                  FROM   db_itensmenu),
                                 'Rel. Retenções NFsA',
                                 'Consulta NFs',
                                 'iss1_relnotaavulsa001.php',
                                 1,
                                 '1',
                                 'Consulta NFs',
                                 true);

                    ALTER TABLE issqn.issnotaavulsaservico ADD q62_baseirrf float8 NULL;
                    ALTER TABLE issqn.issnotaavulsaservico ADD q62_basecalcaposinss float8 NULL;
                    ALTER TABLE issqn.issnotaavulsaservico ADD q62_aliquotairrf float8 NULL;
                    ALTER TABLE issqn.issnotaavulsaservico ADD q62_baseinss float8 NULL;
                ";

        $this->execute($sql);
    }

    public function restauraMenu(){
        $sql = " DELETE FROM configuracoes.db_itensmenu
                WHERE  descricao = 'Rel. Retenções NFsA'; ";

        $this->execute($sql);
    }
}
