<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13416 extends PostgresMigration
{
    CONST PMCAPITAOENEAS = '18017426000113';
    public function up()
    {
        $arrInstit = $this->getInstituicaoByCnpj(self::PMCAPITAOENEAS);
        if(!empty($arrInstit)) {
            $this->_updateReceita();
        }
    }

    private function _updateReceita()
    {
        $this->execute("update recibopaga set k00_receit = 1092 where k00_numnov in (select k00_numnov from recibopaga where k00_receit = 1091) and k00_receit = 999 and k00_hist = 400");
    }

    /**
     * Verifica se existe uma institui��o para o codcli
     * @param string $cnpj
     * @return Array
     */
    public function getInstituicaoByCnpj($cnpj = NULL)
    {
        $arr = array();
        if($cnpj){
            $sSql = "select codigo from db_config where cgc = '{$cnpj}'";
            $arr = $this->fetchAll($sSql);
        }
        return $arr;
    }

    public function down()
    {

    }
}
