<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13009 extends PostgresMigration
{
    public function up(){
    	$sql = "
    		UPDATE db_itensmenu SET descricao = 'Inclus�o', help = 'Inclus�o' WHERE funcao = 'lic1_liclicitaoutrosorgaos001.php';
			UPDATE db_itensmenu SET descricao = 'Altera��o', help = 'Altera��o' WHERE funcao = 'lic1_liclicitaoutrosorgaos002.php';
			UPDATE db_itensmenu SET descricao = 'Licita��o de Outros �rg�os',
									help = 'Licita��o de Outros �rg�os',
									desctec = 'Licita��o de Outros �rg�os'
					WHERE descricao = 'Licitacao de Outros Orgaos';
    	";

		$this->execute($sql);

    }

    public function down(){
		$sql = "
    		UPDATE db_itensmenu SET descricao = 'Inclusao', help = 'Inclusao' WHERE funcao = 'lic1_liclicitaoutrosorgaos001.php';
			UPDATE db_itensmenu SET descricao = 'Alteracao', help = 'Alteracao' WHERE funcao = 'lic1_liclicitaoutrosorgaos002.php';
			UPDATE db_itensmenu SET descricao = 'Licitacao de Outros Org�os',
									help = 'Licitacao de Outros Org�os',
									desctec = 'Licitacao de Outros Org�os'
					WHERE descricao = 'Licita��o de Outros �rg�os';
    	";

		$this->execute($sql);
	}
}
