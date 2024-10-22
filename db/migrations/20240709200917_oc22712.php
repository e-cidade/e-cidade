<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc22712 extends PostgresMigration
{
    public function up()
    {
        $cbo = '224140';
        $descr = 'Profissional de educação física na saúde';

        $sql = "SELECT count(*) as qtd, rh70_sequencial FROM rhcbo WHERE rh70_estrutural = '{$cbo}' GROUP BY rh70_sequencial LIMIT 1";
        $row = $this->fetchRow($sql);


        if ($row['qtd'] == 0) {
            $this->addCbo('224140', $descr);
        } else {
            $this->alteraCbo($row['rh70_sequencial'], $descr);
        }
    }

    public function addCbo($cbo, $descr)
    {
        $sqlAjustaSeq = "SELECT setval('rhcbo_rh70_sequencial_seq',
                            (SELECT max(rh70_sequencial)+1 FROM pessoal.rhcbo))";
        $this->execute($sqlAjustaSeq);

        $sqlInsert = "INSERT INTO rhcbo VALUES (nextval('rhcbo_rh70_sequencial_seq'), '{$cbo}', '{$descr}', 5)";
        $this->execute($sqlInsert);
    }

    public function alteraCbo($cboSeq, $descr)
    {
        $sqlUpdate = "UPDATE rhcbo SET rh70_descr = '{$descr}' WHERE rh70_sequencial = {$cboSeq}";
        $this->execute($sqlUpdate);
    }
}
