<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc17913 extends PostgresMigration
{
    CONST PMPIRAPORA = '23539463000121';
    /**
     * @throws Exception
     */
    public function up()
    {
        $arrInstit = $this->getInstituicaoByCnpj(self::PMPIRAPORA);
        if(empty($arrInstit) === true) {
            return;
        }

        $this->runMigration();
    }

    private function runMigration()
    {
        $services = array(
            array('code' => '01.09', 'description' => 'Disponibiliza��o, sem cess�o definitiva, de conte�dos de �udio, v�deo, imagem e texto por meio da internet, respeitada a imunidade de livros, jornais e peri�dicos (exceto a distribui��o de conte�dos pelas prestadoras de ...).', 'parent' => '01.00'),
            array('code' => '06.06', 'description' => 'Aplica��o de tatuagens, piercings e cong�neres.', 'parent' => '06.00'),
            array('code' => '11.05', 'description' => 'Servi�os relacionados ao monitoramento e rastreamento a dist�ncia, em qualquer via ou local, de ve�culos, cargas, pessoas e semoventes em circula��o ou movimento, realizados por meio de telefonia m�vel...', 'parent' => '11.00'),
            array('code' => '14.14', 'description' => 'Recauchutagem ou regenera��o de pneus.', 'parent' => '14.00'),
            array('code' => '17.25', 'description' => 'Inser��o de textos, desenhos e outros materiais de propaganda e publicidade, em qualquer meio (exceto em livros, jornais, peri�dicos e nas modalidades de servi�os de radiodifus�o sonora e de sons e imagens de recep��o livre e gratuita).', 'parent' => '17.00'),
            array('code' => '25.05', 'description' => 'Cess�o de uso de espa�os em cemit�rios para sepultamento.', 'parent' => '25.00')
        );
        foreach ($services as $service) {
            if ($this->checkExistsDbEstruturaValor($service['parent']) === false) {
                throw new Exception("Parent {$service['parent']} is not found");
            }

            if ($this->checkExistsDbEstruturaValor($service['code']) === false) {
                $this->insertDbEstruturaValor($service);
            }

            if ($this->checkExistsIssGrupoServico($service['code']) === false) {
                $this->insertIssGrupoServico($service['code']);
            }

            if ($this->checkExistsIssConfiguracaoGrupoServico($service['code']) === false) {
                $this->insertIssConfiguracaoGrupoServico($service['code']);
            }
        }
    }

    /**
     * @string $serviceCode
     * @return bool
     */
    private function checkExistsDbEstruturaValor($serviceCode)
    {
        $result = $this->fetchRow("SELECT * FROM db_estruturavalor WHERE db121_estrutural = '{$serviceCode}' and db121_db_estrutura = 150000");
        return empty($result) === false;
    }

    /**
     * @param string $serviceCode
     * @return bool
     */
    private function checkExistsIssGrupoServico($serviceCode)
    {
        $result = $this->fetchRow("SELECT * FROM issgruposervico WHERE q126_db_estruturavalor IN (select db121_sequencial FROM db_estruturavalor WHERE db121_estrutural = '{$serviceCode}' and db121_db_estrutura = 150000)");
        return empty($result) === false;
    }

    private function insertDbEstruturaValor($service)
    {
        $insert = "
        INSERT INTO db_estruturavalor
        VALUES (
                (select nextval('db_estruturavalor_db121_sequencial_seq')),
                150000,
                '{$service['code']}',
                '{$service['description']}',
                2,
                2
        );";

        $this->execute($insert);
    }

    /**
     * @param string $serviceCode
     * @return void
     */
    private function insertIssGrupoServico($serviceCode)
    {
        $insert = "
        INSERT INTO issgruposervico
        VALUES (
                (SELECT nextval('issgruposervico_q126_sequencial_seq')),
                (select db121_sequencial FROM db_estruturavalor WHERE db121_estrutural = '{$serviceCode}' and db121_db_estrutura = 150000)
        );";

        $this->execute($insert);
    }

    /**
     * @param string $serviceCode
     * @return bool
     */
    private function checkExistsIssConfiguracaoGrupoServico($serviceCode)
    {
        $result = $this->fetchRow("SELECT * FROM issconfiguracaogruposervico WHERE q136_issgruposervico IN (SELECT q126_sequencial FROM issgruposervico WHERE q126_db_estruturavalor IN (select db121_sequencial FROM db_estruturavalor WHERE db121_estrutural = '{$serviceCode}' and db121_db_estrutura = 150000))");
        return empty($result) === false;
    }

    /**
     * @param string $serviceCode
     * @return void
     */
    private function insertIssConfiguracaoGrupoServico($serviceCode)
    {
        $insert = "
        INSERT INTO issconfiguracaogruposervico
        VALUES (
                (SELECT nextval('issconfiguracaogruposervico_q136_sequencial_seq')),
                (SELECT q126_sequencial FROM issgruposervico WHERE q126_db_estruturavalor IN (select db121_sequencial FROM db_estruturavalor WHERE db121_estrutural = '{$serviceCode}' and db121_db_estrutura = 150000)),
                2022,
                2,
                3,
                1,
                3
        );";

        $this->execute($insert);
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
}
