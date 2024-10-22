<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21346 extends PostgresMigration
{
    public function up()
    {
        $sqlConsulta = $this->query("SELECT 1 FROM cadmodcarne WHERE k47_sequencial = 107");
        $resultado = $sqlConsulta->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($resultado)){
            $this->execute("insert into cadmodcarne VALUES (107, 'MODELO 4 CARNE IPTU PDF', '',0,0,NULL,NULL);");
        }
    }

}
