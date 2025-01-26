<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Migrationoc18387 extends PostgresMigration
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
        $sql='BEGIN;

        CREATE TEMP TABLE instituicoes(
            sequencial SERIAL,
            inst INT
        );

        INSERT INTO instituicoes(inst) (SELECT codigo FROM db_config where codigo <> 1);

        SELECT * FROM instituicoes;


        CREATE OR REPLACE FUNCTION getAllCodigos() RETURNS SETOF instituicoes AS
        $$
        DECLARE
            r instituicoes%rowtype;
        BEGIN
            FOR r IN SELECT * FROM instituicoes
            LOOP


                insert into db_documento values ((select max(db03_docum)+1 from db_documento),\'SOLICITACAO DE DISPO. FINANCEIRA1\',(select max(db08_codigo) from db_tipodoc),r.inst);
                insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE1\',\'if($tipo==1){$pdf->x = 30;$pdf->cell(190,5,"De: Pregoeira/ Comiss�o permanente de Licita��o",0,1,"L",0);$pdf->x = 30;$pdf->cell(190,5,"Para: Setor cont�bil",0,1,"L",0);}else if($tipo==2){echo "<div ><strong>SOLICITA��O DE PARECER DE DISPONIBILIDADE FINANCEIRA</strong></div><div><p>De: Pregoeira/ Comiss�o permanente de Licita��o<br>Para: Setor cont�bil</p></div>";}\',0,0,1,1,1,\'J\',3,r.inst);
                insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),r.inst);
                insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE2\',\'if($codigo_preco == null || $codigo_preco = ""){if($tipo==1){$pdf->MultiCell(160,5,"     Solicito ao departamento cont�bil se h� no or�amento vigente, disponibilidade financeira que atenda ".mb_strtoupper($objeto,"ISO-8859-1").".",0,"J",0);}else if($tipo==2){echo "<p>Solicito ao departamento cont�bil se h� no or�amento vigente, disponibilidade financeira que atenda ".mb_strtoupper($objeto,"ISO-8859-1").".</p>";}} else {if($tipo==1){$pdf->MultiCell(160,5,"     Solicito ao departamento cont�bil se h� no or�amento vigente, disponibilidade financeira que atenda ".mb_strtoupper($objeto,"ISO-8859-1").", no valor total estimado de R$".trim(db_formatar($nTotalItens,"f")).".",0,"J",0);}else if($tipo==2){echo "<p>Solicito ao departamento cont�bil se h� no or�amento vigente, disponibilidade financeira que atenda ".mb_strtoupper($objeto,"ISO-8859-1").", no valor total estimado de R$ ".trim(db_formatar($nTotalItens,"f")).".</p>";}}\',0,0,1,1,1,\'J\',3,r.inst);
                insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),2);
                insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE3\',\'if($tipo==1){$pdf->cell(190,5,"________________________"                                ,0,1,"C",0);$pdf->cell(190,5,"Presidente da CPL"                                       ,0,1,"C",0);$pdf->cell(190,5,"e/ou Presidente da Comiss�o de Licita��o"                ,0,1,"C",0);}else if($tipo==2){echo "<div >             <center>  _________________________________________  </center>                <p>Presidente da CPL<br>e/ou Presidente da Comiss�o de Licita��o</p>            </div>";}\',0,0,1,1,1,\'J\',3,r.inst);
                insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),3);


                insert into db_documento values ((select max(db03_docum)+1 from db_documento),\'DECLARACAO DE REC. ORC. E FINANCEIRO1\',(select max(db08_codigo) from db_tipodoc),r.inst);
                insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE1\',\'if ($tipo==1){$pdf->MultiCell(160,5,"     Examinando  as  Dota��es  constantes  do  or�amento  fiscal  e  levando-se  em  conta  o objeto que se  pretende  contratar, ".mb_strtoupper($objeto,"ISO-8859-1")." , no valor total estimado de R$ ".trim(db_formatar($nTotalItens,"f"))." em atendimento aos dispositivos da Lei 8666/93, informo que existe dota��es das quais correr�o a despesas:",0,"J",0);}else if($tipo == 2){echo "     Examinando  as  Dota��es  constantes  do  or�amento  fiscal  e  levando-se  em  conta  o objeto que se  pretende  contratar, ".mb_strtoupper($objeto,"ISO-8859-1")." , no valor total estimado de R$ ".trim(db_formatar($nTotalItens,"f"))." em atendimento aos dispositivos da Lei 8666/93, informo que existe dota��es das quais correr�o a despesas:";}\',0,0,1,1,1,\'J\',3,r.inst);
                insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),r.inst);
                insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE2\',\'if ($tipo==1){$pdf->MultiCell(160,5,"que as despesas atendem ao disposto nos artigos 16 e 17 da Lei Complementar Federal 101/2000, uma vez, foi considerado o impacto na execu��o or�ament�ria e tamb�m est� de acordo com a previs�o do Plano Plurianual e da Lei de Diretrizes Or�ament�rias para exerc�cio. Informamos ainda que foi verificado o impacto financeiro da despesa e sua inclus�o na programa��o deste �rg�o.",0,"J",0);}else if($tipo == 2){echo "que as despesas atendem ao disposto nos artigos 16 e 17 da Lei Complementar Federal 101/2000, uma vez, foi considerado o impacto na execu��o or�ament�ria e tamb�m est� de acordo com a previs�o do Plano Plurianual e da Lei de Diretrizes Or�ament�rias para exerc�cio. Informamos ainda que foi verificado o impacto financeiro da despesa e sua inclus�o na programa��o deste �rg�o.";}\',0,0,1,1,1,\'J\',3,r.inst);
                insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),2);
                insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE3\',\'if($tipo==1){$pdf->cell(95,4,"________________________"                                ,0,0,"C",0);$pdf->cell(95,4,"________________________"                                ,0,1,"C",0);$pdf->cell(95,5,"Servi�o cont�bil"                                        ,0,0,"C",0);$pdf->cell(95,5,"Servi�o Financeiro"                                      ,0,0,"C",0);}else if($tipo==2){echo "<tr>                <td >  _________________________________________                  <p>Servi�o cont�bil</p></td>                <td >  _________________________________________                 <p>Servi�o Financeiro</p></td>";}\',0,0,1,1,1,\'J\',3,r.inst);
                insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),3);

                RETURN NEXT r;

            END LOOP;
            RETURN;
        END
        $$
        LANGUAGE plpgsql;

        SELECT * FROM getAllCodigos();

        DROP FUNCTION getAllCodigos();

        COMMIT;';

        $this->execute($sql);

    }
}
