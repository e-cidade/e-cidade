<?php

require_once('model/CgmFactory.model.php');
require_once('model/cadastro/Construcao.model.php');
include("classes/db_promitente_classe.php");

/**
 * Classe para manipua��o de im�veis
 *
 * @author   Rafael Serpa Nery  rafael.nery@dbseller.com.br
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @package  Cadastro
 * @revision $Author: dbanderson $
 * @version  $Revision: 1.8 $
 */
class Imovel
{

    /**
     * Matricula de registro do im�vel
     * @var integer
     */
    private $iMatricula;

    /**
     * C�digo do lote do im�vel
     * @var integer
     */
    private $iCodigoLote;

    /**
     * Data da baixa do im�vel
     * @var date
     */
    private $dtDataBaixa;

    /**
     * C�digo de averba��o do im�vel
     * @var integer
     */
    private $iCodigoAverbacao;

    /**
     * N�mero da fra��o do im�vel
     * @var float
     */
    private $nFracao;

    /**
     *
     * Cole��o com todos os proprietarios do im�vel
     * @var array
     */
    private $aProprietarios = array();

    /**
     *
     * Cole��o com todos os promitentes do im�vel
     * @var array
     */
    private $aPromitentes = array();

    /**
     * Numero do cgm do propriet�rio
     * @var integer
     */
    private $iNumcgm;

    /**
     * Construtor da Classe
     * @param integer $iMatricula - Matricula de registro do im�vel
     */
    public function __construct($iMatricula = null)
    {

        /**
         * Valida se a matricula informada � valida e define os atributos
         */
        if (!empty($iMatricula)) {

            $oDaoIPTUBase = db_utils::getDao('iptubase');

            $sSqlImovel   = $oDaoIPTUBase->sql_query_file($iMatricula);
            $rsImovel     = $oDaoIPTUBase->sql_record($sSqlImovel);

            if ($oDaoIPTUBase->erro_status == "0") {
                throw new Exception("Erro ao selecionar dados da tabela iptubase: \n" . $oDaoIPTUBase->erro_msg);
            }

            $oImovel                = db_utils::fieldsMemory($rsImovel, 0);
            $this->iMatricula       = $iMatricula;
            $this->iCodigoLote      = $oImovel->j01_idbql;
            $this->dtDataBaixa      = $oImovel->j01_baixa;
            $this->iCodigoAverbacao = $oImovel->j01_codave;
            $this->nFracao          = $oImovel->j01_fracao;
            $this->iNumcgm          = $oImovel->j01_numcgm;
        }
    }

    /**
     * Retorna Matricula do im�vel
     * @return integer
     */
    public function getMatricula()
    {
        return $this->iMatricula;
    }

    /**
     * Define matr�cula do im�vel
     * @param $iMatricula
     */
    public function setMatricula($iMatricula)
    {
        $this->iMatricula = $iMatricula;
    }

    /**
     * Retorna c�digo do lote do im�vel
     * @return integer
     */
    public function getCodigoLote()
    {
        return $this->iCodigoLote;
    }

    /**
     * Define c�digo do lote do im�vel
     * @param $iCodigoLote
     */
    public function setCodigoLote($iCodigoLote)
    {
        $this->iCodigoLote = $iCodigoLote;
    }

    /**
     * Retorna data da baixa do im�vel
     * @return date
     */
    public function getDataBaixa()
    {
        return $this->dtDataBaixa;
    }

    /**
     * Define data da baixa do im�vel
     * @param $dtDataBaixa
     */
    public function setDataBaixa($dtDataBaixa)
    {
        $this->dtDataBaixa = $dtDataBaixa;
    }

    /**
     * Retorna c�digo da averba��o
     * @return integer
     */
    public function getCodigoAverbacao()
    {
        return $this->iCodigoAverbacao;
    }

    /**
     * Define c�digo da averba��o
     * @param $iCodigoAverbacao
     */
    public function setCodigoAverbacao($iCodigoAverbacao)
    {
        $this->iCodigoAverbacao = $iCodigoAverbacao;
    }

    /**
     * Retorna fra��o do im�vel
     * @return float
     */
    public function getFracao()
    {
        return $this->nFracao;
    }

    /**
     * Define fra��o do im�vel
     * @param $nFracao
     */
    public function setFracao($nFracao)
    {
        $this->nFracao = $nFracao;
    }

    /**
     * @return int
     */
    public function getNumcgm()
    {
        return $this->iNumcgm;
    }

    /**
     * @param int $iNumcgm
     * @return Imovel
     */
    public function setNumcgm($iNumcgm)
    {
        $this->iNumcgm = $iNumcgm;
        return $this;
    }

    /**
     * Salva registros na base de dados
     */
    public function salvar()
    {
    }

    /**
     * Retorna os propriet�rios do im�vel
     * @return array
     */
    public function getProprietarios()
    {

        /**
         * Busca Propriet�rios do Imovel
         */

        if (empty($this->iMatricula)) {
            throw new Exception('Matr�cula n�o informada para busca dos propriet�rios');
        }

        $oDaoIPTUBase           = db_utils::getDao('iptubase');

        $sSqlProprietarios      = $oDaoIPTUBase->sql_query_proprietarios($this->iMatricula);
        $rsProprietarios        = $oDaoIPTUBase->sql_record($sSqlProprietarios);

        if ((int)$oDaoIPTUBase->erro_status == 0 && !empty($oDaoIPTUBase->erro_banco)) {
            throw new Exception("Erro ao constultar propriet�rios da matr�cula {$this->iMatricula}: \n" . $oDaoIPTUBase->erro_msg);
        }

        $aProprietarios         = db_utils::getCollectionByRecord($rsProprietarios);

        foreach ($aProprietarios as $oProprietario) {
            $this->aProprietarios[$oProprietario->j01_numcgm] = CgmFactory::getInstanceByCgm($oProprietario->j01_numcgm);
        }

        return $this->aProprietarios;
    }

    /**
     * Retorna os promitentes do im�vel
     * @return array
     */
    public function getPromitentes()
    {

        /**
         * Busca promitente do Imovel
         */
        if (empty($this->iMatricula)) {
            throw new Exception('Matr�cula n�o informada para busca dos propriet�rios');
        }

        $oDaoIPTUBase           = db_utils::getDao('iptubase');

        $sSqlPromitentes       = $oDaoIPTUBase->sql_query_promitentes($this->iMatricula);
        $rsPromitentes         = $oDaoIPTUBase->sql_record($sSqlPromitentes);

        if ((int)$oDaoIPTUBase->erro_status == 0 && !empty($oDaoIPTUBase->erro_banco)) {
            throw new Exception("Erro ao consultar promitentes da matr�cula {$this->iMatricula}.: \n" . $oDaoIPTUBase->erro_msg);
        }

        $aPromitentes          = db_utils::getCollectionByRecord($rsPromitentes);

        foreach ($aPromitentes as $oPromitente) {
            $this->aPromitentes[$oPromitente->j41_numcgm] = CgmFactory::getInstanceByCgm($oPromitente->j41_numcgm);
        }

        return $this->aPromitentes;
    }

    /**
     * Retorna o propriet�rio principal do im�vel
     * @return object
     */
    public function getProprietarioPrincipal()
    {

        $oDaoIPTUBase          = db_utils::getDao('iptubase');
        $sSqlProprietarios     = $oDaoIPTUBase->sql_query_proprietarios($this->iMatricula, true);
        $rsProprietarios       = db_query($sSqlProprietarios);

        if (!$rsProprietarios) {
            throw new Exception("Erro ao consultar proprietario principal da matricula {$this->iMatricula}: \n" . pg_last_error());
        }

        if (pg_num_rows($rsProprietarios) > 0) {
            $oProprietario = db_utils::fieldsMemory($rsProprietarios, 0);

            return CgmFactory::getInstanceByCgm($oProprietario->j01_numcgm);
        }
        return false;
    }

    /**
     * Retorna uma inst�ncia do cgm do promitente principal do im�vel
     * @return object
     */
    public function getPromitentePrincipal()
    {

        $oDaoIPTUBase          = db_utils::getDao('iptubase');
        $sSqlPromitentes       = $oDaoIPTUBase->sql_query_promitentes($this->iMatricula, true);
        $rsPromitentes         = db_query($sSqlPromitentes);

        if (!$rsPromitentes) {
            throw new Exception("Erro ao consultar promitente principal da matr�cula {$this->iMatricula}: \n" . pg_last_error());
        }

        if (pg_num_rows($rsPromitentes) > 0) {

            $oPromitente = db_utils::fieldsMemory($rsPromitentes, 0);

            return CgmFactory::getInstanceByCgm($oPromitente->j41_numcgm);
        }

        return false;
    }

    /**
     * Retorna respons�vel legal do imovel
     */
    public function getResponsavelLegal()
    {
    }

    /**
     *
     * Retorna todas as constru��es vinculadas a obra
     * @return array com instancia da classe Construcao
     */
    public function getConstrucoes($lSomenteAtivas = false)
    {

        $oDaoIPTUConstr  = db_utils::getDao('iptuconstr');

        $sWhere = null;
        if ($lSomenteAtivas) {
            $sWhere = " j39_matric = {$this->iMatricula} and j39_dtdemo is null ";
        }

        $sSqlConstrucoes = $oDaoIPTUConstr->sql_query_file($this->iMatricula, null, 'j39_idcons', null, $sWhere);

        $rsConstrucoes   = $oDaoIPTUConstr->sql_record($sSqlConstrucoes);

        $aConstrucoes    = array();

        if ($oDaoIPTUConstr->numrows > 0) {

            $aIdConstrucoes = db_utils::getCollectionByRecord($rsConstrucoes);
            /**
             * Constroi um array com a instancia do objeto constru��es
             */
            foreach ($aIdConstrucoes as $oConstrucao) {

                $aConstrucoes[$oConstrucao->j39_idcons] = new Construcao($this->iMatricula, $oConstrucao->j39_idcons);
            }
        }

        return $aConstrucoes;
    }

    /**
     * Retorna o lote ao qual pertence o im�vel
     * @throws Exception C�digo do lote
     * @return object Lote
     */
    public function getLote()
    {

        db_app::import('cadastro.Lote');

        if (empty($this->iCodigoLote)) {
            throw new Exception('C�digo do lote n�o informado');
        }

        return new Lote($this->iCodigoLote);
    }

    /**
     * Retorna dados da isen��o de uma matr�cula, caso haja
     * @return Object
     */
    public function getDadosIsencaoExercicio()
    {

        if (empty($this->iMatricula)) {
            throw new Exception('Matr�cula n�o informada');
        }

        $oDaoIptuisen = db_utils::getDao('iptuisen');

        $sSqlIptuIsen = $oDaoIptuisen->sql_queryIsencao(db_getsession('DB_anousu'), $this->iMatricula);

        $rsIptuisen   = $oDaoIptuisen->sql_record($sSqlIptuIsen);

        $oIsencao  = new stdClass();

        $oIsencao->iTipoIsencao      = '';
        $oIsencao->sDescricaoIsencao = '';

        if ($oDaoIptuisen->numrows > 0) {
            $oIptuisen = db_utils::fieldsMemory($rsIptuisen, 0);

            $oIsencao->iTipoIsencao      = $oIptuisen->j45_tipo;
            $oIsencao->sDescricaoIsencao = $oIptuisen->j45_descr;
        }

        return $oIsencao;
    }

    /**
     * Retona um objeto ImovelEndereco com dados do endere�o da matr�cula
     * @return object
     */
    public function getImovelEndereco()
    {

        db_app::import('cadastro.ImovelEndereco');

        return new ImovelEndereco($this->iMatricula);
    }

    /**
     * Retorna um objeto com resultados do calculo
     * @return object
     */
    public function getCalculo()
    {

        db_app::import('cadastro.CalculoIPTU');

        return new CalculoIPTU($this->getMatricula(), db_getsession('DB_anousu'));
    }

    /**
     * Retorna dados da refer�ncia anterior de uma matricula
     * @return integer
     */
    public function getReferenciaAnterior()
    {

        if (empty($this->iMatricula)) {
            throw new Exception('Matr�cula para a busca da refer�ncia anterior n�o encontrada');
        }

        $oDaoIptuant = db_utils::getDao('iptuant');

        $rsIptuant   = $oDaoIptuant->sql_record($oDaoIptuant->sql_query_file($this->iMatricula));

        if ($oDaoIptuant->numrows > 0) {

            return db_utils::fieldsMemory($rsIptuant, 0)->j40_refant;
        }
    }

    public function alterarCgmProprietario()
    {
        $oDaoIptubase = db_utils::getDao("iptubase");

        $oDaoIptubase->j01_matric = $this->getMatricula();
        $oDaoIptubase->j01_numcgm = $this->getNumcgm();
        $oDaoIptubase->j01_idbql = $this->getCodigoLote();
        $oDaoIptubase->j01_baixa = $this->getDataBaixa();
        $oDaoIptubase->j01_codave = $this->getCodigoAverbacao();
        $oDaoIptubase->j01_fracao = $this->getFracao();
        if (!$oDaoIptubase->alterar($this->getMatricula())) {
            throw new Exception('Erro ao alterar o propriet�rio.');
        }
    }

    public function incluirCgmPromitente()
    {
        $clpromitente = new cl_promitente;

        $clpromitente->j41_numcgm = $this->getNumcgm();
        $clpromitente->j41_tipopro = 't';
        $clpromitente->j41_promitipo = 'C';
        $clpromitente->excluir($this->getMatricula());
        $clpromitente->incluir($this->getMatricula(),$this->getNumcgm());
    }
}
