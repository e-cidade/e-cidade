<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc11626Ajuste extends PostgresMigration
{
    public function change()
    {
        $sql = "
            update orcparamseq set o69_descr = 'TRANSFER�NCIAS OBRIGAT�RIAS EMENDAS INDIVIDUAIS', o69_labelrel = 'TRANSFER�NCIAS OBRIGAT�RIAS EMENDAS INDIVIDUAIS' where o69_codparamrel = 166 and o69_codseq = 15;

            update orcparamseq set o69_descr = 'TRANSFER�NCIAS OBRIGAT�RIAS EMENDAS DE BANCADA', o69_labelrel = 'TRANSFER�NCIAS OBRIGAT�RIAS EMENDAS DE BANCADA' where o69_codparamrel = 166 and o69_codseq = 16;
        ";
        $this->execute($sql);
    }
}
