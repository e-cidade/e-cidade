<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Hotfixoc21127 extends PostgresMigration
{
    public function up(){
        $sql = "
        update empenho.naturezabemservico set e101_resumo = 'Transporte de cargas, exceto os relacionados no c�digo 8767', e101_descr = 'Transporte de cargas, exceto os relacionados no c�digo 8767' where e101_codnaturezarendimento = 17006;
        update empenho.naturezabemservico set e101_resumo = 'Servi�os de aux�lio diagn�stico e terapia, patologia cl�nica, imagenologia, anatomia patol�gica e citopatol�gia, medi...', e101_descr = 'Servi�os de aux�lio diagn�stico e terapia, patologia cl�nica, imagenologia, anatomia patol�gica e citopatol�gia, medicina nuclear e an�lises e patologias cl�nicas de que trata o art. 31.' where e101_codnaturezarendimento = 17007;
        ";
        $this->execute($sql);
    }
}
