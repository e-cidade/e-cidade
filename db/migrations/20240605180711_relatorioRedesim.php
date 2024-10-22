<?php

use ECidade\Suporte\Phinx\PostgresMigration;;

class RelatorioRedesim extends PostgresMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addItemMenu();
    }

    private function addItemMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( (SELECT max(id_item)+1 FROM db_itensmenu) ,'REDESIM' ,'Relatórios REDESIM' ,'' ,'1' ,'1' ,'Relatórios REDESIM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 30 ,(SELECT max(id_item) FROM db_itensmenu) ,843 ,40 );

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( (SELECT max(id_item)+1 FROM db_itensmenu) ,'Inscrições geradas a partir da REDESIM' ,'Inscrições geradas a partir da REDESIM' ,'iss179_inscricaoredesim.php' ,'1' ,'1' ,'Rotina para geração de relatório com as inscrições geradas a partir da REDESIM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ((SELECT id_item FROM db_itensmenu where descricao = 'REDESIM') ,(SELECT max(id_item) FROM db_itensmenu) ,1 ,40 );
        ";

        $this->execute($sql);
    }
}
