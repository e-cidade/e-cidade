<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Hotfixoc18387 extends PostgresMigration
{

    public function up()
    {
        $sql='BEGIN;

        insert into db_tipodoc values ((select max(db08_codigo)+1 from db_tipodoc),\'RELATORIOS ORCAMENTARIOS\');
        insert into db_documento values ((select max(db03_docum)+1 from db_documento),\'SOLICITACAO DE DISPO. FINANCEIRA1\',(select max(db08_codigo) from db_tipodoc),1);
insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE1\',\'if($tipo==1){$pdf->x = 30;$pdf->cell(190,5,"De: Pregoeira/ Comissão permanente de Licitação",0,1,"L",0);$pdf->x = 30;$pdf->cell(190,5,"Para: Setor contábil",0,1,"L",0);}else if($tipo==2){echo "<div ><strong>SOLICITAÇÃO DE PARECER DE DISPONIBILIDADE FINANCEIRA</strong></div><div><p>De: Pregoeira/ Comissão permanente de Licitação<br>Para: Setor contábil</p></div>";}\',0,0,1,1,1,\'J\',3,1);
insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),1);
insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE2\',\'if($codigo_preco == null || $codigo_preco = ""){if($tipo==1){$pdf->MultiCell(160,5,"     Solicito ao departamento contábil se há no orçamento vigente, disponibilidade financeira que atenda ".mb_strtoupper($objeto,"ISO-8859-1").".",0,"J",0);}else if($tipo==2){echo "<p>Solicito ao departamento contábil se há no orçamento vigente, disponibilidade financeira que atenda ".mb_strtoupper($objeto,"ISO-8859-1").".</p>";}} else {if($tipo==1){$pdf->MultiCell(160,5,"     Solicito ao departamento contábil se há no orçamento vigente, disponibilidade financeira que atenda ".mb_strtoupper($objeto,"ISO-8859-1").", no valor total estimado de R$".trim(db_formatar($nTotalItens,"f")).".",0,"J",0);}else if($tipo==2){echo "<p>Solicito ao departamento contábil se há no orçamento vigente, disponibilidade financeira que atenda ".mb_strtoupper($objeto,"ISO-8859-1").", no valor total estimado de R$ ".trim(db_formatar($nTotalItens,"f")).".</p>";}}\',0,0,1,1,1,\'J\',3,1);
insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),2);
insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE3\',\'if($tipo==1){$pdf->cell(190,5,"________________________"                                ,0,1,"C",0);$pdf->cell(190,5,"Francyele"                                       ,0,1,"C",0);$pdf->cell(190,5,"e/ou Presidente da Comissão de Licitação"                ,0,1,"C",0);}else if($tipo==2){echo "<div >             <center>  _________________________________________  </center>                <p>Francyele</p>            </div>";}\',0,0,1,1,1,\'J\',3,1);
insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),3);


insert into db_documento values ((select max(db03_docum)+1 from db_documento),\'DECLARACAO DE REC. ORC. E FINANCEIRO1\',(select max(db08_codigo) from db_tipodoc),1);
insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE1\',\'if ($tipo==1){$pdf->MultiCell(160,5,"     Examinando  as  Dotações  constantes  do  orçamento  fiscal  e  levando-se  em  conta  o objeto que se  pretende  contratar, ".mb_strtoupper($objeto,"ISO-8859-1")." , no valor total estimado de R$ ".trim(db_formatar($nTotalItens,"f"))." em atendimento aos dispositivos da Lei 8666/93, informo que existe dotações das quais correrão a despesas:",0,"J",0);}else if($tipo == 2){echo "     Examinando  as  Dotações  constantes  do  orçamento  fiscal  e  levando-se  em  conta  o objeto que se  pretende  contratar, ".mb_strtoupper($objeto,"ISO-8859-1")." , no valor total estimado de R$ ".trim(db_formatar($nTotalItens,"f"))." em atendimento aos dispositivos da Lei 8666/93, informo que existe dotações das quais correrão a despesas:";}\',0,0,1,1,1,\'J\',3,1);
insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),1);
insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE2\',\'if ($tipo==1){$pdf->MultiCell(160,5,"que as despesas atendem ao disposto nos artigos 16 e 17 da Lei Complementar Federal 101/2000, uma vez, foi considerado o impacto na execução orçamentária e também está de acordo com a previsão do Plano Plurianual e da Lei de Diretrizes Orçamentárias para exercício. Informamos ainda que foi verificado o impacto financeiro da despesa e sua inclusão na programação deste órgão.",0,"J",0);}else if($tipo == 2){echo "que as despesas atendem ao disposto nos artigos 16 e 17 da Lei Complementar Federal 101/2000, uma vez, foi considerado o impacto na execução orçamentária e também está de acordo com a previsão do Plano Plurianual e da Lei de Diretrizes Orçamentárias para exercício. Informamos ainda que foi verificado o impacto financeiro da despesa e sua inclusão na programação deste órgão.";}\',0,0,1,1,1,\'J\',3,1);
insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),2);
insert into db_paragrafo values ((select max(db02_idparag)+1 from db_paragrafo),\'PARTE3\',\'if($tipo==1){$pdf->cell(95,4,"________________________"                                ,0,0,"C",0);$pdf->cell(95,4,"________________________"                                ,0,1,"C",0);$pdf->cell(95,5,"Francyele"                                        ,0,0,"C",0);$pdf->cell(95,5,"Guilherme"                                      ,0,0,"C",0);}else if($tipo==2){echo "<tr>                <td >  _________________________________________                  <p>Serviço contábil</p></td>                <td >  _________________________________________                 <p>Serviço Financeiro</p></td>";}\',0,0,1,1,1,\'J\',3,1);
insert into db_docparag values ((select max(db03_docum) from db_documento),(select max(db02_idparag) from db_paragrafo),3);


        COMMIT;';

        $this->execute($sql);
    }
}
