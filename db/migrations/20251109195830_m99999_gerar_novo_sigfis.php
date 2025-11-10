<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class M99999GerarNovoSigfis extends PostgresMigration{
  public function up(){
    $linha = $this->fetchRow("SELECT nextval('db_itensmenu_id_item_seq') AS id_item");
    $idItem = $linha['id_item'];

    $this->execute("
      insert into db_itensmenu (
        id_item,
        descricao,
        help,
        funcao,
        itemativo,
        manutencao,
        desctec,
        libcliente
      ) values (
        {$idItem},
        'Gerar Novo Sigfis',
        'Gerar Novo Sigfis',
        'con4_gerarnovosigfis.php',
        '1',
        '1',
        'Cria o menu para a geração do novo sigfis.',
        'true'
      )
    ");
    
    $this->execute("
      insert into db_menu (
        id_item,
        id_item_filho,
        menusequencia,
        modulo
      ) values (
        8897,
        {$idItem},
        10,
        209
      )
    ");
  }

  public function down()
  {
    $this->execute("
      delete from db_menu 
      where id_item_filho in (
        select id_item from db_itensmenu where funcao = 'con4_gerarnovosigfis.php'
      )
    ");
    $this->execute("
      delete from db_itensmenu where funcao = 'con4_gerarnovosigfis.php'
    ");
  }
}
