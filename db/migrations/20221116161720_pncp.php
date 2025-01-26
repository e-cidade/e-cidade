<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Pncp extends PostgresMigration
{

    public function up()
    {
        $sql = "
                INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo),'l213_numerocontrolepncp','varchar(28)','C�digo PNCP','','C�digo PNCP',28,false,false,false,1,'text','C�digo PNCP');
                INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo),'l215_ata','varchar(28)','C�digo Ata PNCP','','C�digo Ata PNCP',28,false,false,false,1,'text','C�digo Ata PNCP');

                INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'PNCP', 'PNCP', ' ', 1, 1, 'PNCP', 't');
                INSERT INTO db_menu VALUES(1818,(select max(id_item) from db_itensmenu),16,381);

                INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Publica��o de Aviso', 'Publica��o de Aviso', 'lic1_pncpavisolicitacao001.php', 1, 1, 'Publica��o de Aviso', 't');
                INSERT INTO db_menu VALUES((select id_item from db_itensmenu where desctec like'%PNCP' and funcao = ' '),(select max(id_item) from db_itensmenu),1,381);

                INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Publica��o Resultados', 'Publica��o Resultados', 'lic1_pncpresultadolicitacao001.php', 1, 1, 'Publica��o Resultados', 't');
                INSERT INTO db_menu VALUES((select id_item from db_itensmenu where desctec like'%PNCP' and funcao = ' '),(select max(id_item) from db_itensmenu),2,381);

                INSERT INTO db_itensmenu VALUES ((select max(id_item)+1 from db_itensmenu), 'Publica��o Ata Registro Pre�o', 'Publica��o Ata Registro Pre�o', 'lic1_publicacaoatapncp.php', 1, 1, 'Publica��o Ata Registro Pre�o', 't');
                INSERT INTO db_menu VALUES((select id_item from db_itensmenu where desctec like'%PNCP' and funcao = ' '),(select max(id_item) from db_itensmenu),3,381);

                CREATE TABLE licontroleatarppncp(
                    l215_sequencial           int8 NOT NULL,
                    l215_licitacao            int8 NOT NULL,
                    l215_usuario			  int8 NOT NULL,
                    l215_dtlancamento         date NOT NULL,
                    l215_numerocontrolepncp   text NOT NULL,
                    l215_situacao			  int8 NOT NULL,
                    l215_ata 			  	  int8 NOT NULL,
                    l215_anousu 			  int8 NOT NULL,
                    PRIMARY KEY (l215_sequencial),
                    FOREIGN KEY (l215_licitacao) REFERENCES liclicita (l20_codigo)
                );

        ";
        $this->execute($sql);
    }
}
