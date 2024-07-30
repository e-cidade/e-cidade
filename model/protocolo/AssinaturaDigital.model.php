<?php

include("vendor/mpdf/mpdf/mpdf.php");
use App\Models\Configuracao\AssinaturaDigitalParametro;
use App\Repositories\Configuracao\AssinaturaDigitalParametroRepository;
use App\Repositories\Configuracao\AssinaturaDigitalAssinatesRepository;
use App\Models\Empenho\Empempenho;
use \App\Models\Empenho\Empanulado;
use Illuminate\Database\Capsule\Manager as DB;
use \App\Models\Caixa\Slip;
use \App\Models\Empenho\Empord;
use \App\Models\Configuracao\AssinaturaDigitalAssinante;
use GuzzleHttp\Client;

require_once("model/empenho/EmpenhoFinanceiro.model.php");
require_once("model/empenho/relatorio/FormularioEmpenho.model.php");
require_once("model/empenho/relatorio/FormularioLiquidacao.model.php");
require_once("model/configuracao/UsuarioSistema.model.php");

class AssinaturaDigital
{
    private const USUARIO_ADMINISTRADOR = 'admlibrecode';

    private const URL_SOLICITAR_ASSINATURA = "/ocs/v2.php/apps/libresign/api/v1/request-signature";

    private const URL_ARQUIVO = "/apps/libresign/p/pdf/";

    private const URL_LISTA_DOCUMENTOS = "/ocs/v2.php/apps/libresign/api/v1/file/list";

    private const URL_ASSINAR_DOCUMENTO = "/ocs/v2.php/apps/libresign/api/v1/sign/uuid/";

    private const URL_EXCLUIR_DOCUMENTO = "/ocs/v2.php/apps/libresign/api/v1/sign/file_id/";

    private const TIPO_DOC_EMPENHO = 0;

    private const TIPO_DOC_LIQUIDACAO = 1;

    private const TIPO_DOC_PAGAMENTO = 2;

    private const TIPO_DOC_SLIP = 3;

    protected int $instituicao;

    protected $base64Arquivo;

    private AssinaturaDigitalParametro $assinaturaDigitalParametro;

    private AssinaturaDigitalParametroRepository $assinaturaDigitalParametroRepository;

    private AssinaturaDigitalAssinatesRepository $assinaturaDigitalAssinanteRepository;

    private UsuarioSistema $oUsuario;

    private $client;

    private $curl_init;
    public function __construct()
    {
        $this->assinaturaDigitalParametroRepository = new AssinaturaDigitalParametroRepository();
        $this->assinaturaDigitalAssinanteRepository = new AssinaturaDigitalAssinatesRepository();
        $this->instituicao = db_getsession("DB_instit");
        $oAssinaturaParametro = $this->assinaturaDigitalParametroRepository->findBy('db242_instit', $this->instituicao);
        if($oAssinaturaParametro){
            $this->assinaturaDigitalParametro = $oAssinaturaParametro;
        }
        $this->oUsuario = new UsuarioSistema(db_getsession("DB_id_usuario"));
        $this->client = new Client([
            'timeout' => 10, // Definir timeout de 10 segundos
            'connect_timeout' => 5, // Timeout para conexão de 5 segundos
            'http_errors' => false, // Para evitar exceções HTTP
        ]);
        $this->curl_init = curl_init();
    }

    /**
     *  Método usado para assinar um documento pegos os assintes pela dotacao;
     *
     * @param int $iDotacao
     * @param $arquivo
     * @return string
     */
    public function solicitarAssinturaPorDotacao(int $iDotacao, int $iAnoUsu, $dData, $nomeDocumento, $tipo_doc, $codDocumento)
    {
        $aAssinantes = $this->getAssinatesPorDotacao($iDotacao, $iAnoUsu, $dData, $tipo_doc, $codDocumento);
        $data = json_encode([
            'file' => ['base64' => $this->base64Arquivo],
            'users' => $aAssinantes,
            'name' => utf8_encode(str_replace(".pdf", "", $nomeDocumento)),
        ]);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
        }

        $retorno = $this->postLibresing($data);

        if($retorno->data->message != "Sucesso"){
            throw new Exception("Documento {$codDocumento} não enviado para assinar. Motivo: ".$retorno->data->message);
        }
        return $retorno->data->data;
    }

    /**
     *  Método usado para assinar um documento por
     *
     * @param int $iDotacao
     * @param $arquivo
     * @return string
     */
    public function solicitarAssinturaContadorGestor($dData, $nomeDocumento, $tipo_doc, $codDocumento)
    {
        $aAssinantes = $this->getAssinatesContadorGestor($dData, $tipo_doc, $codDocumento);
        $data = json_encode([
            'file' => ['base64' => $this->base64Arquivo],
            'users' => $aAssinantes,
            'name' => utf8_encode(str_replace(".pdf", "", $nomeDocumento)),
        ]);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
        }
        $retorno = $this->postLibresing($data);
        if($retorno->data->message != "Sucesso"){
            throw new Exception("Documento {$codDocumento} não assinado. Motivo: ".$retorno->data->message);
        }
        return $retorno->data;
    }

    public function assinarEmpenho(int $e60_numemp, int $iDotacao, int $iAnoUsu, $dData, $nomeDocumento, $codEmpenho)
    {
        $oDocumentoAssinado = $this->solicitarAssinturaPorDotacao($iDotacao, $iAnoUsu, $dData, $nomeDocumento, self::TIPO_DOC_EMPENHO, $codEmpenho);
        if($oDocumentoAssinado->uuid){
            $empenho  = Empempenho::find($e60_numemp);
            if(!$empenho){
                throw new Exception("Empenho não encontrado!!");
            }
            if($empenho->e60_id_documento_assinado && $oDocumentoAssinado->nodeId){
                $this->excluirArquivo($empenho->e60_node_id_libresing);
            }
            $empenho->e60_id_documento_assinado = $oDocumentoAssinado->uuid;
            $empenho->e60_node_id_libresing     = $oDocumentoAssinado->nodeId;
            if(!$empenho->save()){
                throw new Exception("Erro ao salvar alteração do empenho!");
            }
        }
    }

    public function assinarLiquidacao(int $e60_numemp,int $e69_codnota, int $iDotacao, int $iAnoUsu, $dData, $nomeDocumento, $codDoc)
    {
        $empnota  = DB::table('empenho.empnota')->where('empenho.empnota.e69_codnota', '=', $e69_codnota)->first();
        if(!$empnota){
            throw new Exception("Nota Fiscal não encontrado!!");
        }
        $oDocumentoAssinado = $this->solicitarAssinturaPorDotacao($iDotacao, $iAnoUsu, $dData, $nomeDocumento, self::TIPO_DOC_LIQUIDACAO, $codDoc);
        if($oDocumentoAssinado->uuid){

            if($empnota->e69_id_documento_assinado && $oDocumentoAssinado->nodeId){
                $this->excluirArquivo($empnota->e69_node_id_libresing);
            }
            $empnota->e69_id_documento_assinado = $oDocumentoAssinado->uuid;
            $empnota->e69_node_id_libresing     = $oDocumentoAssinado->nodeId;
            $empnota = DB::table('empenho.empnota')->where('empenho.empnota.e69_codnota', '=', $e69_codnota)->update(['e69_id_documento_assinado' => $oDocumentoAssinado->uuid, 'e69_node_id_libresing'     => $oDocumentoAssinado->nodeId]);
            if(!$empnota){
                throw new Exception("Erro ao salvar alteração do nota fiscal!");
            }
        }
    }

    public function assinarOrdemPagamento(int $iMovimento, int $iDotacao, int $iAnoUsu, $dData, $nomeDocumento, $codDoc)
    {
        $oDocumentoAssinado = $this->solicitarAssinturaPorDotacao($iDotacao, $iAnoUsu, $dData, $nomeDocumento, self::TIPO_DOC_PAGAMENTO, $codDoc);
        if($oDocumentoAssinado->uuid){
            $empord  = Empord::find($iMovimento);
            if(!$empord){
                throw new Exception("Ordem Pagamento não encontrada!");
            }
            if($empord->e82_id_documento_assinado && $oDocumentoAssinado->nodeId){
                $this->excluirArquivo($empord->e82_node_id_libresing);
            }
            $empord->e82_id_documento_assinado = $oDocumentoAssinado->uuid;
            $empord->e82_node_id_libresing     = $oDocumentoAssinado->nodeId;
            if(!$empord->save()){
                throw new Exception("Erro ao salvar alteração da ordem pagamento!");
            }
        }
    }


    public function assinarSlip(int $numslip, $k17_data, $nomeDocumento)
    {
        $oDocumentoAssinado = $this->solicitarAssinturaContadorGestor($k17_data, $nomeDocumento, self::TIPO_DOC_SLIP, $numslip);
        if($oDocumentoAssinado->message != "Sucesso") {
            throw new Exception("Erro ao salvar alteração do slip! ".$oDocumentoAssinado->message);
        }
        $slip  = Slip::find($numslip);
        if(!$slip){
            throw new Exception("Slip não encontrado!!");
        }
        if($slip->k17_id_documento_assinado && $oDocumentoAssinado->data->nodeId){
            $this->excluirArquivo($slip->k17_node_id_libresing);
        }
        $slip->k17_id_documento_assinado = $oDocumentoAssinado->data->uuid;
        $slip->k17_node_id_libresing     = $oDocumentoAssinado->data->nodeId;
        if(!$slip->save()){
            throw new Exception("Erro ao salvar alteração do slip!");
        }

    }

    protected function excluirArquivo($e60_node_id_libresing)
    {
        $user = self::USUARIO_ADMINISTRADOR;
        $password = $this->assinaturaDigitalParametro->db242_assinador_token;
        $url = $this->assinaturaDigitalParametro->db242_assinador_url . self::URL_EXCLUIR_DOCUMENTO . $e60_node_id_libresing;
        $client = new Client();
        try {
            $response = $client->request('DELETE', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($user . ':' . $password),
                    'Content-Type' => 'application/json'
                ],
                'http_errors' => false
            ]);

            $body = $response->getBody();
            $data = json_decode($body);
            return $data->ocs->data->data;
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }

    protected function postLibresing($data)
    {
        curl_setopt($this->curl_init, CURLOPT_URL, $this->assinaturaDigitalParametro->db242_assinador_url.self::URL_SOLICITAR_ASSINATURA);
        curl_setopt($this->curl_init, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl_init, CURLOPT_CUSTOMREQUEST, 'POST');
        $user = self::USUARIO_ADMINISTRADOR;
        $password = $this->assinaturaDigitalParametro->db242_assinador_token;
        curl_setopt($this->curl_init, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Authorization: Basic ' . base64_encode($user. ':' . $password), 'Content-Type: application/json']);
        curl_setopt($this->curl_init, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($this->curl_init);
        curl_close($this->curl_init);
        return json_decode($response)->ocs;
    }

    public function verificaAssituraAtiva()
    {
        if(!empty($this->assinaturaDigitalParametro)){
            return $this->assinaturaDigitalParametro->db242_assinador_ativo;
        }
        return false;
    }

    private function getAssinatesPorDotacao(int $iDotacao, int $iAnoUsu, string $dData, int $tipo_doc, $codDoc): array
    {
        $aAssinantes = $this->assinaturaDigitalAssinanteRepository->getAssinantesPorDotacao($iDotacao, $iAnoUsu, $tipo_doc,  $dData);
        $usuarios = [];
        $usuariosHash = [];
        foreach ($aAssinantes as $assinante){

            if(!isset($usuariosHash[$assinante->login])){
                $usuarios[] = [
                    'displayName' => utf8_encode($assinante->nome)." (".iconv("ISO-8859-1", "UTF-8" ,AssinaturaDigitalAssinante::ASSINTAURA_CARGOS[$assinante->db243_cargo]).")",
                    'description' => 'Assinar este documento',
                    'identify' => ['account' => $assinante->login],
                    'notify' => true
                ];
                $usuariosHash[$assinante->login] = $assinante->login;
            }
        }

        if(empty($usuarios)){
            throw new Exception(" Não foi localizado nenhum pessoa para assinar o documento: {$codDoc}. Revise o cadastro e envie novamente para assinatura.");
        }
        return $usuarios;

    }

    private function getAssinatesContadorGestor(string $dData, int $tipo_doc, $coddocumento): array
    {
        $aAssinantes = $this->assinaturaDigitalAssinanteRepository->getAssinantesContadorGestor($tipo_doc,  $dData);
        $usuarios = [];
        $usuariosHash = [];
        foreach ($aAssinantes as $assinante){
            if(!isset($usuariosHash[$assinante->login])){
                $usuarios[] = [
                    'displayName' => utf8_encode($assinante->nome)." (".AssinaturaDigitalAssinante::ASSINTAURA_CARGOS[$assinante->db243_cargo].")",
                    'description' => 'Assinar este documento',
                    'identify' => ['account' => $assinante->login],
                    'notify' => true
                ];
                $usuariosHash[$assinante->login] = $assinante->login;
            }
        }

        if(empty($usuarios)){
            throw new Exception(" Não foi localizado nenhum pessoa para assinar o documento: {$coddocumento}. Revise o cadastro e envie novamente para assinatura.");
        }

        return $usuarios;
    }

    public function assinarAnulacaoEmpenho(int $e94_codanu,  int $iDotacao, int $iAnoUsu, $dData, $nomeDocumento, $codDoc)
    {
        $oDocumentoAssinado = $this->solicitarAssinturaPorDotacao($iDotacao, $iAnoUsu, $dData, $nomeDocumento, self::TIPO_DOC_EMPENHO, $codDoc);
        if($oDocumentoAssinado->uuid){
            $empanulado  = Empanulado::find($e94_codanu);
            if(!$empanulado){
                throw new Exception("Anulação não encontrado!!");
            }
            if($empanulado->e94_id_documento_assinado && $oDocumentoAssinado->nodeId){
                $this->excluirArquivo($empanulado->e94_node_id_libresing);
            }

            $empanulado->e94_id_documento_assinado = $oDocumentoAssinado->uuid;
            $empanulado->e94_node_id_libresing     = $oDocumentoAssinado->nodeId;
            if(!$empanulado->save()){
                throw new Exception("Erro ao salvar alteração da anulação!");
            }

        }
    }

    public function gerarArquivoBase64(string $nomeArquivo)
    {
        $file = base64_encode(file_get_contents(realpath("tmp/$nomeArquivo")));

        if (!$file) {
            throw new Exception("Erro ao decodificar a string!");
        }

        if (strlen($file) % 4 !== 0) {
            throw new Exception("Erro ao verificar o comprimento da string!");
        }

        // Verificar caracteres inválidos
        if (!preg_match('/^[A-Za-z0-9+\/=]*$/', $file)) {
            throw new Exception("Erro ao verificar caracteres inválidos!");
        }

        // Verificar se a string decodificada está corretamente codificada em UTF-8
        if (!mb_check_encoding($file, 'UTF-8')) {
            throw new Exception("Erro ao verificar se a string decodificada está corretamente codificada em UTF-8");
        }

        $this->base64Arquivo = $file;

    }

    public function getDocumentosPorUsuario()
    {
        try {
            $url = $this->assinaturaDigitalParametro->db242_assinador_url . self::URL_LISTA_DOCUMENTOS . '?page=1&length=100000000';
            $headers = [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->oUsuario->getLogin() . ':' . $this->oUsuario->getSenha()),
                'Content-Type' => 'application/json'
            ];
            $response = $this->client->request('GET', $url, [
                'headers' => $headers
            ]);
            return $response->getBody()->getContents();
        } catch (RequestException $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function getUrlBaseUrlFile()
    {
        return $this->assinaturaDigitalParametro->db242_assinador_url;
    }

    public function getUrlArquivo()
    {
        return self::URL_ARQUIVO;
    }

    public function assinarDocumento($uuid)
    {
        try {
            $response = $this->client->request('POST', $this->assinaturaDigitalParametro->db242_assinador_url.self::URL_ASSINAR_DOCUMENTO . $uuid, [
                'query' => ['method' => 'clickToSign'],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($this->oUsuario->getLogin() . ':' . $this->oUsuario->getSenha()),
                    'Content-Type' => 'application/json',
                ],
            ]);
            return $response->getBody()->getContents();
        } catch (RequestException $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function getDocumentoAssinado($uuid)
    {
        try {
//            $pdf = file_get_contents($this->assinaturaDigitalParametro->db242_assinador_url.self::URL_ARQUIVO.$uuid);
////            if ($pdf === false) {
////                throw new Exception(error_get_last()['message']);
////            }
            return file_get_contents($this->assinaturaDigitalParametro->db242_assinador_url.self::URL_ARQUIVO.$uuid);
        }catch (Exception $error){

            throw new Exception($error->getMessage());
        }
    }

    public function getEmpenhosAssinados($oParametros)
    {
        try {

            $aEmpenhos = $this->assinaturaDigitalAssinanteRepository->getEmpenhosAssinados($oParametros);

            $root_temp_dir = sys_get_temp_dir();
            $temp_name = md5(serialize($oParametros));
            $temp_dir = $root_temp_dir."/libresign/".$temp_name;
            if (is_dir($temp_dir)) {
                exec("rm -rf $temp_dir");
            }
            mkdir($temp_dir,0755, true);
            $aNomesArquivos = [];

            foreach ($aEmpenhos as $oEmpenho){
                if(!$oEmpenho->e60_id_documento_assinado){
                    continue;
                }
                $aNomesArquivos[] = $temp_dir."/".$oEmpenho->e60_id_documento_assinado.".pdf";
                file_put_contents($temp_dir."/".$oEmpenho->e60_id_documento_assinado.".pdf", $this->getDocumentoAssinado($oEmpenho->e60_id_documento_assinado));

            }
            if(!empty($aNomesArquivos)){
                $cmd = "gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=$temp_dir/$temp_name.pdf  -dBATCH  ". implode(' ', $aNomesArquivos);

                exec($cmd, $output, $return_var);

                // Verifica se o comando foi executado com sucesso
                if ($return_var !== 0) {
                    throw new Exception($output);
                }

                return file_get_contents("$temp_dir/$temp_name.pdf" );;
            }

        }catch (Exception $error){
            throw new Exception($error->getMessage());
        }
    }

    public function getLiquidacoesAssinadas($oParametros)
    {
        try {
            $aLiquidacoes = $this->assinaturaDigitalAssinanteRepository->getLiquidacaoesAssinadas($oParametros);

            $root_temp_dir = sys_get_temp_dir();
            $temp_name = md5(serialize($oParametros));
            $temp_dir = $root_temp_dir."/libresign/".$temp_name;
            if (is_dir($temp_dir)) {
                exec("rm -rf $temp_dir");
            }
            mkdir($temp_dir,0755, true);
            $aNomesArquivos = [];
            foreach ($aLiquidacoes as $oLiquidacao){
                if(!$oLiquidacao->e69_id_documento_assinado){
                    continue;
                }
                $aNomesArquivos[] = $temp_dir."/".$oLiquidacao->e69_id_documento_assinado.".pdf";
                file_put_contents($temp_dir."/".$oLiquidacao->e69_id_documento_assinado.".pdf", $this->getDocumentoAssinado($oLiquidacao->e69_id_documento_assinado));
            }
            if(!empty($aNomesArquivos)){
                $cmd = "gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=$temp_dir/$temp_name.pdf  -dBATCH -f ". implode(' ', $aNomesArquivos);
                exec($cmd);
                $pdf = file_get_contents("$temp_dir/$temp_name.pdf" );
                return $pdf;
            }
        }catch (Exception $error){
            throw new Exception($error->getMessage());
        }
    }

    public function getAnulacaoEmpenhoAssinadas($oParametros)
    {

        try {
            $aAnulacoesEmpenho = $this->assinaturaDigitalAssinanteRepository->getAnulacaoEmpenhoAssinadas($oParametros);
            $root_temp_dir = sys_get_temp_dir();
            $temp_name = md5(serialize($oParametros));
            $temp_dir = $root_temp_dir."/libresign/".$temp_name;
            if (is_dir($temp_dir)) {
                exec("rm -rf $temp_dir");
            }
            mkdir($temp_dir,0755, true);
            $aNomesArquivos = [];
            foreach ($aAnulacoesEmpenho as $oAnulacaoEmpenho){
                if(!$oAnulacaoEmpenho->e94_id_documento_assinado){
                    continue;
                }
                $aNomesArquivos[] = $temp_dir."/".$oAnulacaoEmpenho->e94_id_documento_assinado.".pdf";
                file_put_contents($temp_dir."/".$oAnulacaoEmpenho->e94_id_documento_assinado.".pdf", $this->getDocumentoAssinado($oAnulacaoEmpenho->e94_id_documento_assinado));
            }
            if(!empty($aNomesArquivos)){
                $cmd = "gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=$temp_dir/$temp_name.pdf  -dBATCH -f ". implode(' ', $aNomesArquivos);
                exec($cmd);
                $pdf = file_get_contents("$temp_dir/$temp_name.pdf" );
                return $pdf;
            }
        }catch (Exception $error){
            throw new Exception($error->getMessage());
        }
    }

    public function getOrdemPagamentoAssinadas($oParametros)
    {
        try {
            $aOrdensPagamento = $this->assinaturaDigitalAssinanteRepository->getOrdemPagamentoAssinadas($oParametros);
            if(empty($aOrdensPagamento)){
                throw new Exception("Não foi encotrado documentos assinados para esse filtro!");
            }
            $root_temp_dir = sys_get_temp_dir();
            $temp_name = md5(serialize($oParametros));
            $temp_dir = $root_temp_dir."/libresign/".$temp_name;
            if (is_dir($temp_dir)) {
                exec("rm -rf $temp_dir");
            }
            mkdir($temp_dir,0755, true);
            $aNomesArquivos = [];
            foreach ($aOrdensPagamento as $oOrdemPagamento){
                if(!$oOrdemPagamento->e82_id_documento_assinado){
                    continue;
                }
                $aNomesArquivos[] = $temp_dir."/".$oOrdemPagamento->e82_id_documento_assinado.".pdf";
                file_put_contents($temp_dir."/".$oOrdemPagamento->e82_id_documento_assinado.".pdf", $this->getDocumentoAssinado($oOrdemPagamento->e82_id_documento_assinado));
            }
            if(!empty($aNomesArquivos)){
                $cmd = "gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=$temp_dir/$temp_name.pdf  -dBATCH -f ". implode(' ', $aNomesArquivos);
                exec($cmd);
                $pdf = file_get_contents("$temp_dir/$temp_name.pdf" );
                return $pdf;
            }
        }catch (Exception $error){
            throw new Exception($error->getMessage());
        }
    }

    public function getSlipAssinadas($oParametros)
    {
        try {
            $aSlips = $this->assinaturaDigitalAssinanteRepository->getSlipAssinadas($oParametros);
            $root_temp_dir = sys_get_temp_dir();
            $temp_name = md5(serialize($oParametros));
            $temp_dir = $root_temp_dir."/libresign/".$temp_name;
            if (is_dir($temp_dir)) {
                exec("rm -rf $temp_dir");
            }
            mkdir($temp_dir,0755, true);
            $aNomesArquivos = [];
            foreach ($aSlips as $oOrdemPagamento){
                if(!$oOrdemPagamento->k17_id_documento_assinado){
                    continue;
                }
                $aNomesArquivos[] = $temp_dir."/".$oOrdemPagamento->k17_id_documento_assinado.".pdf";
                file_put_contents($temp_dir."/".$oOrdemPagamento->k17_id_documento_assinado.".pdf", $this->getDocumentoAssinado($oOrdemPagamento->k17_id_documento_assinado));
            }
            if(!empty($aNomesArquivos)){
                $cmd = "gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=$temp_dir/$temp_name.pdf  -dBATCH -f ". implode(' ', $aNomesArquivos);
                exec($cmd);
                $pdf = file_get_contents("$temp_dir/$temp_name.pdf" );
                return $pdf;
            }
        }catch (Exception $error){
            throw new Exception($error->getMessage());
        }
    }

    public function asssinarDocumentosFolha($sEmpenhosGerados)
    {
        try {
            $aEmpenhos = explode(",", $sEmpenhosGerados);

            foreach ($aEmpenhos as $iEmpenho) {
                $formularioEmpenho = new FormularioEmpenho();
                $formularioEmpenho->gerarFormularioEmpenho($iEmpenho);
                $formularioEmpenho->solitarAssinaturaEmpenho();
                $formularioLiquicao = new FormularioLiquidacao();
                $formularioLiquicao->gerarFormularioLiquidacao($iEmpenho);
                $formularioLiquicao->solitarAssinaturaLiquidacao();
            }
        }catch (Exception $error){
            throw new Exception($error->getMessage());
        }
    }

    public function removerAcentos($string)
    {
        $acentos = array(
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß',
            'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'
        );

        $semAcentos = array(
            'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'ss',
            'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'
        );

        $stringSemAcentos = str_replace($acentos, $semAcentos, $string);
        $stringSemAcentos = preg_replace('/[^a-zA-Z0-9 ]/', '', $stringSemAcentos);
        return $stringSemAcentos;
    }

}
