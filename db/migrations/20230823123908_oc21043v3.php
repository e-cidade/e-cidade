<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc21043v3 extends  PostgresMigration
{
    public function up()
    {

        $jsonDados = file_get_contents('db/migrations/20230823123908_oc21043v3.json');
        $dados = json_decode(utf8_decode($jsonDados));
        if ($dados === null && json_last_error() !== JSON_ERROR_NONE) {
            // JSON decoding failed, handle the error here
            echo "Error decoding JSON: " . json_last_error_msg();
        } else {
            
        // print_r($dados->fontes);exit;
        foreach ($dados->fontes as $fonte) {
            
            $db121_sequencial           = current($this->fetchRow("SELECT nextval('db_estruturavalor_db121_sequencial_seq')"));
            $db121_db_estrutura         = 5;
            $db121_estrutural           = "$fonte->FonteEcidade";
            $db121_descricao            = "$fonte->DESCRICAO";
            $db121_estruturavalorpai    = 0;
            $db121_nivel                = 1;
            $db121_tipoconta            = 1;

            $checkInsert = $this->fetchRow("SELECT * FROM db_estruturavalor WHERE db121_estrutural = '{$fonte->FonteEcidade}'");

            if (empty($checkInsert)) {
                $aEstruturaValor = array($db121_sequencial, $db121_db_estrutura, $db121_estrutural, $db121_descricao, $db121_estruturavalorpai, $db121_nivel, $db121_tipoconta);
                $this->insertEstruturaValor($aEstruturaValor);
            }

            $checkInsert2 = $this->fetchRow("SELECT * FROM db_estruturavalor WHERE db121_estrutural = '{$fonte->FonteEcidade}'");
            $checkFonte  = $this->fetchRow("SELECT * FROM orctiporec WHERE o15_codigo = '{$fonte->FonteEcidade}'");

            if (!empty($checkInsert2) && empty($checkFonte)){

                $o15_codigo             = intval($fonte->FonteEcidade);
                $o15_descr              = substr($fonte->DESCRICAO, 0, 59);
                $o15_codtri             = $fonte->FonteTCEMG;
                $o15_finali             = $fonte->FINALIDADE;
                $o15_tipo               = 2;
                $o15_datalimite         = NULL;
                $o15_db_estruturavalor  = current($this->fetchRow("SELECT max(db121_sequencial) FROM db_estruturavalor WHERE db121_estrutural = '{$fonte->FonteEcidade}'"));
                $o15_codstn             = "";
                $o15_codstnnovo         = intval($fonte->FonteSTN);

                $aFonte = array($o15_codigo, $o15_descr, $o15_codtri, $o15_finali, $o15_tipo, $o15_datalimite, $o15_db_estruturavalor, $o15_codstn, $o15_codstnnovo);
                $this->insertFontes($aFonte);
            }
        }
        }

        $sql = <<<SQL

        BEGIN;
    
        SELECT fc_startsession();
    
            UPDATE orctiporec
                SET o15_datalimite = '2024-01-02'
                WHERE o15_codigo in(17040000,27040000);
      
        COMMIT;
    
    SQL;
        $this->execute($sql);
    }

    public function insertEstruturaValor(array $estruturaValor)
    {
        $fields = array(
            'db121_sequencial',
            'db121_db_estrutura',
            'db121_estrutural',
            'db121_descricao',
            'db121_estruturavalorpai',
            'db121_nivel',
            'db121_tipoconta'
        );

        $this->table('db_estruturavalor', array('schema' => 'configuracoes'))->insert($fields, array($estruturaValor))->saveData();

    }

    public function insertFontes(array $fontes)
    {
        $this->execute("INSERT INTO orctiporec VALUES ($fontes[0], '{$fontes[1]}', '{$fontes[2]}', '{$fontes[3]}', $fontes[4], NULL, $fontes[6], NULL, $fontes[8])");

    }

   

}