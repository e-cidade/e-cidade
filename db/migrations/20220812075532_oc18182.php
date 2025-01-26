<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class oc18182 extends PostgresMigration
{
    public function up()
    {

        foreach ($this->getData() as $item => $descricao) {
            $sql = "update db_estruturavalor set db121_descricao = '{$descricao}' where db121_estrutural = '{$item}';";
            $this->execute($sql);
        }
    }

    private function getData()
    {
        return array(
            '01.03' => 'Processamento, armazenamento ou hospedagem de dados, textos, imagens, v�deos, p�ginas eletr�nicas, aplicativos e sistemas de informa��o, entre outros formatos, e cong�neres.',
            '01.04' => 'Elabora��o de programas de computadores, inclusive de jogos eletr�nicos, independentemente da arquitetura construtiva da m�quina em que o programa ser� executado, incluindo tablets, smartphones e cong�neres.',
            '07.16' => 'Florestamento, reflorestamento, semeadura, aduba��o, repara��o de solo, plantio, silagem, colheita, corte e descascamento de �rvores, silvicultura, explora��o florestal e dos servi�os cong�neres indissoci�veis da forma��o, manuten��o e colheita de florestas, para quaisquer fins e por quaisquer meios.',
            '11.02' => 'Vigil�ncia, seguran�a ou monitoramento de bens, pessoas e semoventes.',
            '13.05' => 'Composi��o gr�fica, inclusive confec��o de impressos gr�ficos, fotocomposi��o, clicheria, zincografia, litografia e fotolitografia, exceto se destinados a posterior opera��o de comercializa��o ou industrializa��o, ainda que incorporados, de qualquer forma, a outra mercadoria que deva ser objeto de posterior circula��o, tais como bulas, r�tulos, etiquetas, caixas, cartuchos, embalagens e manuais t�cnicos e de instru��o, quando ficar�o sujeitos ao ICMS.',
            '14.05' => 'Restaura��o, recondicionamento, acondicionamento, pintura, beneficiamento, lavagem, secagem, tingimento, galvanoplastia, anodiza��o, corte, recorte, plastifica��o, costura, acabamento, polimento e cong�neres de objetos quaisquer.',
            '16.01' => 'Servi�os de transporte coletivo municipal rodovi�rio, metrovi�rio, ferrovi�rio e aquavi�rio de passageiros.',
            '25.02' => 'Translado intramunicipal e crema��o de corpos e partes de corpos cadav�ricos.',
        );
    }
}
