<?php

namespace ECidade\Patrimonial\Licitacao\PNCP;

use ECidade\WebService\SimpleHttpClient;
use stdClass;

/**
 * Classe base para PNCP
 *
 * @package  PNCP
 * @autor    Mario Junior
 */
abstract class BasePNCP
{
    /**
     * Dados
     * @var \stdClass
     */
    protected $dados;

    protected $envs;

    protected $client;

    /**
     * @param \stdClass $dados
     */
    public function __construct()
    {
        $this->envs = parse_ini_file('legacy_config/PNCP/.env', true);
        $this->client = new SimpleHttpClient($this->envs['URL']);
    }

    /**
     * Retorna dados no formato necess�rio para envio
     * @return array stdClass
     */
    abstract public function montarDados();

    protected function formatDate($date)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $date);
        return $date->format('Y-m-d\TH:i:s');
    }

    protected function formatText($text)
    {
        return preg_replace(
            array(
                "/(ï¿½|ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½|ï¿½|ï¿½|ï¿½)/",
                "/(ï¿½)/",
                "/(ï¿½)/",
                "/(ï¿½)/",
                "/(ï¿½)/",
                "/(-)/"
            ),
            explode(" ", "a A e E i I o O u U n c C N "),
            $text
        );
    }

    /**
     * Realiza o login com Usuario e Senha da Institui��o na api do PNCP
     * @return token de acesso v�lido por 60 minutos
     */
    protected function login($codUnidade = null)
    {
        $url = 'usuarios/login';

        $response = $this->client->post($url, [
            'json' => [
                'login' => $this->getLogin($codUnidade),
                'senha' => $this->getPassword($codUnidade)
            ],
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        return $response->getHeaders()['authorization'] ?? null;
    }

    protected function getCnpj($codUnidade = null)
    {
        $sqlCnpj = "
            SELECT
                cgm.z01_cgccpf
            FROM
                db_config
                INNER JOIN cgm ON db_config.numcgm = cgm.z01_numcgm
            WHERE
                db_config.codigo = " . (!empty($codUnidade) ? $codUnidade : db_getsession('DB_instit'));
        $rsCnpj = db_query($sqlCnpj);
        $sCNPJ = pg_fetch_row($rsCnpj);
        return $sCNPJ[0];
    }

    protected function getLogin($codUnidade = null)
    {
        $sqlPNCP = "
            SELECT
                l12_loginpncp
            FROM
                licitaparam
            WHERE
                l12_instit = " . (!empty($codUnidade) ? $codUnidade : db_getsession('DB_instit'));
        $rsPNCP = db_query($sqlPNCP);
        $sPNCP = pg_fetch_row($rsPNCP);
        return $sPNCP[0];
    }

    protected function getPassword($codUnidade = null)
    {
        $sqlPNCP = "
            SELECT
                l12_passwordpncp
            FROM
                licitaparam
            WHERE
                l12_instit = " . (!empty($codUnidade) ? $codUnidade : db_getsession('DB_instit'));
        $rsPNCP = db_query($sqlPNCP);
        $sPNCP = pg_fetch_row($rsPNCP);
        return $sPNCP[0];
    }

    protected function getUndCompradora($codUnidade = null)
    {
        $sqlPNCP = "
            SELECT
                l12_unidadecompradora
            FROM
                licitaparam
            WHERE
                l12_instit = " . (!empty($codUnidade)? $codUnidade :db_getsession('DB_instit'));
        $rsPNCP = db_query($sqlPNCP);
        $sPNCP = pg_fetch_row($rsPNCP);
        return $sPNCP[0];
    }

    protected function getParancurl($method, $curl_data, $headers, $jsonencode = false, $rturnHeader = false)
    {
        if ($jsonencode) {
            $curl_data = json_encode($curl_data);
        }

        return array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => $rturnHeader,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POSTFIELDS     => $curl_data,
            CURLOPT_SSLVERSION     => 6,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_VERBOSE        => 1,
            CURLOPT_HTTPHEADER     => $headers
        );
    }
}
