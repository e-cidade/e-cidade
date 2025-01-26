<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc18229Ajuste extends PostgresMigration
{

    public function up()
    {
        $sql = "INSERT INTO db_itensmenu VALUES((select max(id_item)+1 from db_itensmenu),'Textos padr�es Publica��o','Textos padr�es Publica��o','',1,1,'Textos padr�es Publica��o','t');
        INSERT INTO db_menu VALUES(3470,(select max(id_item) from db_itensmenu),206,381);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Inclus�o','Inclus�o','lic1_lictextopublicacao001.php',1,1,'Inclus�o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Textos padr�es Publica��o%'),(select max(id_item) from db_itensmenu),1,381);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Altera��o','Altera��o','lic1_lictextopublicacao002.php',1,1,'Altera��o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Textos padr�es Publica��o%'),(select max(id_item) from db_itensmenu),2,381);

        INSERT INTO db_itensmenu values ((select max(id_item)+1 from db_itensmenu),'Exclus�o','Exclus�o','lic1_lictextopublicacao003.php',1,1,'Exclus�o','t');
        INSERT INTO db_menu VALUES((select id_item from db_itensmenu where descricao like'%Textos padr�es Publica��o%'),(select max(id_item) from db_itensmenu),3,381);


        INSERT INTO db_sysarquivo VALUES((select max(codarq)+1 from db_sysarquivo),'lictextopublicacao','textos padr�es publica��o','l214','2022-08-25','textos padr�es publica��o',0,'f','f','f','f');

        INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l214_sequencial','int8' ,'C�d. Sequencial','', 'C�d. Sequencial',11,false, false, false, 1, 'int8', 'C�d. Sequencial');

        INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo),'l214_tipo','varchar(150)' ,'Tipo','', 'Tipo',150,false, false, false, 0, 'text', 'Tipo');

        INSERT INTO db_syscampo VALUES ((select max(codcam)+1 from db_syscampo), 'l214_texto','text' ,'Texto','','Texto',999999,false, false, false, 0, 'text', 'Descri��o da Tabela');

        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l214_sequencial'), 1, 0);

        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l214_tipo'), 2, 0);

        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES ((select max(codarq) from db_sysarquivo), (select codcam from db_syscampo where nomecam = 'l214_texto'), 3, 0);

        create table lictextopublicacao(
        l214_sequencial int8 not null default 0,
        l214_tipo  varchar(150) not null,
        l214_texto text not null,
        CONSTRAINT lictextopublicacao_seq_pk PRIMARY KEY (l214_sequencial));

        create sequence lictextopublicacao_l214_sequencial_seq
        increment 1
        minvalue 1
        MAXVALUE 9223372036854775807
        START 3
        CACHE 1;

        INSERT INTO lictextopublicacao VALUES (1,'Aviso de Licita��o', 'Processo Licitat�rio n� \$l20_edital Modalidade \$l20_codtipocomdescr n� \$l20_numero. Crit�rio de Julgamento: \$l20_tipliticacao. Base Legal:\$l20_leidalicitacao. Objeto:\$l20_objeto. Data:\$l20_recdocumentacao. No endere�o: \$l20_localentrega. \$institui��o, data sistema (formato 01 de Janeiro de 2022.');

        INSERT INTO lictextopublicacao VALUES (2,'Aviso de Chamamento P�blico:', 'Processo n� \$l20_edital Modalidade \$l20_codtipocomdescr n� \$l20_numero. Base Legal: \$l20_leidalicitacao. Objeto: \$l20_objeto. Data:\$l20_recdocumentacao. No endere�o: \$l20_localentrega. \$institui��o, data sistema (formato 01 de Janeiro de 2022.');";

        $this->execute($sql);
    }
}
