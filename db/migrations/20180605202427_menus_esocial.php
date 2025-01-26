<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class MenusEsocial extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-PostgresMigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $sql = <<<SQL

        INSERT INTO configuracoes.db_modulos (id_item, nome_modulo, descr_modulo, imagem, temexerc, nome_manual) VALUES (10216, 'eSocial', 'eSocial', '', true, null);
        INSERT INTO configuracoes.db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) VALUES (10216, 'eSocial', 'eSocial', '', 2, '1', 'M�dulo de integra��o com o eSocial.', true);
        INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10216, 29, 1, 10216);
        INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10216, 31, 2, 10216);
        INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10216, 30, 3, 10216);
        INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10216, 32, 4, 10216);

        INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (29, 10218, 268, 10216);
        INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10218, 10223, 2, 10216);


        --INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (32, 10465, 491, 10216);
        --INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (32, 10466, 492, 10216);
        --INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10466, 10220, 1, 10216);
        --INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10466, 10426, 2, 10216);
        --INSERT INTO configuracoes.db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10466, 10244, 3, 10216);

        INSERT INTO configuracoes.atendcadareamod (at26_sequencia, at26_codarea, at26_id_item) VALUES (76, 3, 10216);


        INSERT INTO configuracoes.db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) VALUES (10218, 'Cadastro de formul�rios', 'Cadastro de formul�rios', '', 1, '1', 'Cadastra formul�rio de resposta.', true);
        INSERT INTO configuracoes.db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) VALUES (10223, 'Altera��o', 'Altera um formul�rio do eSocial', 'eso1_cadastroavaliacoes002.php', 1, '1', 'Menu para manuten��o dos formul�rios do eSocial.', true);

        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10244 ,'Informa��es do Empregador' ,'Informa��es do Empregador para o eSocial' ,'eso01_preenchimentoempregador.php' ,'1' ,'1' ,'Formul�rio para preenchimento das informa��es que ser�o enviadas ao eSocial referente ao estabelecimento empregador.' ,'true' );
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10220 ,'Confer�ncia de Dados' ,'Confer�ncia de Dados' ,'eso4_conferenciadados001.php' ,'1' ,'1' ,'Confer�ncia dos dados do e-social preenchidos pelos usu�rios.' ,'true' );
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10219 ,'Preenchimento do Formul�rio' ,'Preenchimento do Formul�rio' ,'eso4_preenchimento001.php' ,'1' ,'1' ,'Preenche o formul�rio do e-social.' ,'true' );
SQL;

    $this->execute($sql);
    }


    public function down(){
        $id = 10216;
        $sql = <<<SQL
        delete from atendcadareamod where at26_id_item = $id;
        delete from db_modulos where id_item = $id;
        delete from db_itensmenu where id_item = $id;
        delete from db_menu where modulo = $id;
        delete from db_itensmenu where id_item in (10218,10223);

SQL;

        $this->execute($sql);
    }
}
