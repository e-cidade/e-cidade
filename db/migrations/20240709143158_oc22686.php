<?php

use Phinx\Migration\AbstractMigration;

class Oc22686 extends AbstractMigration
{
    public function up()
    {
        $codSubFuncoes = [
            ['cod' => 241, 'descr' => "ASSISTENCIA A PESSOA IDOSA"],
            ['cod' => 242, 'descr' => "ASSISTENCIA A PESSOA COM DEFICIENCIA"],
            ['cod' => 245, 'descr' => "SERVIÇOS SOCIOASSISTENCIAIS"],
            ['cod' => 246, 'descr' => "SEGURANÇA DE RENDA"]
        ];

        foreach ($codSubFuncoes as $subFuncao) {
            $codigo = $subFuncao['cod'];
            $descricao = $subFuncao['descr'];

            $sql = "SELECT count(*) as qtd, o53_descr FROM orcsubfuncao WHERE o53_subfuncao = $codigo GROUP BY o53_descr";
            $row = $this->fetchRow($sql);

            if ($row['qtd'] == 0) {
                $this->addSubfuncOrcamento($codigo, $descricao);
            } else {
                $this->alteraSubfuncOrcamento($codigo, $descricao);
            }
        }
    }


    public function addSubfuncOrcamento($codFuncao, $descricao)
    {
        $sqlInsert = "INSERT INTO orcsubfuncao VALUES ($codFuncao, '$descricao', $codFuncao, '$descricao')";
        $this->execute($sqlInsert);
    }

    public function alteraSubfuncOrcamento($codFuncao, $descricao)
    {
        $sqlUpdate = "UPDATE orcsubfuncao SET o53_descr = '{$descricao}', o53_finali = '{$descricao}' WHERE o53_subfuncao = $codFuncao";
        $this->execute($sqlUpdate);
    }
}
