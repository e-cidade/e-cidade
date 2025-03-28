<?php

abstract class ArquivoSiprevBase {


  protected $iUnidadeGestora;
  protected $iTipoAto;
  protected $iNumeroAto;
  protected $iAnoAto;
  protected $dDataAto;
  protected $cRepresentante;
  /**
   * numero siafi da Entidade
   *
   * @var string
   */
  protected $sSiafi;	
/**
   * numero CNPJ da Entidade
   *
   * @var string
   */
  protected $sCnpj;	
	
  /**
   * nome do arquivo
   *
   * @var string
   */
  protected $sNomeArquivo;

  /**
   * formato de saida do arquivo (txt/XML/CSV)
   *
   * @var string
   */
  protected $sOutPut = "xml";
  
  /**
   * Cole��o de objetos com os dados do arquivo
   *
   * @var array
   */
  protected $aDados;
  
  /**
   * ano inicial do arquivo
   *
   * @var string
   */
  protected $iAnoInicial;
  
  /**
   * mes inicial do arquivo
   *
   * @var integer
   */
  protected $iMesInicial;

  
  /**
   * ano inicial do arquivo
   *
   * @var string
   */
  protected $iAnoFinal;
  
  /**
   * mes inicial do arquivo
   *
   * @var integer
   */
  protected $iMesFinal;
  
  /**
   * data final do arquivo
   *
   * @var string
   */
  protected $sDataFinal;
  
  /**
   * Enter description here...
   *
   * @var unknown_type
   */  
  protected  $rsLogger;
  /**
   * 
   * @see iPadArquivoBase::__construct()
   */
  function __construct() {
   
  }
  
  /**
   * 
   * @see iPadArquivoBase::gerarDados()
   */
  public function gerarDados() {

  }
  
  /**
   * 
   * @see iPadArquivoBase::getDados()
   */
  public function getDados() {
     return $this->aDados; 
  }
  
  /**
   * Retorna o nome do arquivo
   *
   * @return string
   */
  function getNomeArquivo() {

    return $this->sNomeArquivo;
  }
  
  /**
   * Define a competencia inicial 
   *
   * @param integer $iAno ano inicial
   * @param integer $iMes mes inicial
   */
  public function setCompetenciaInicial($iAno, $iMes) {
    
    $this->iAnoInicial = $iAno;
    $this->iMesInicial = $iMes;
  }
  
/**
   * Define a competencia final 
   *
   * @param integer $iAno ano final
   * @param integer $iMes mes final
   */
  public function setCompetenciaFinal($iAno, $iMes) {
    
    $this->iAnoFinal = $iAno;
    $this->iMesFinal = $iMes;
  }
  
  /**
   *retorna o Tipo de saida o arquivo 
   */
  function getOutPut() {
    return  $this->sOutPut;
  }
  public function setTXTLogger($fp) {
    
    $this->rsLogger  = $fp; 
  }
  
  public function addLog($sLog) {
    fputs($this->rsLogger, $sLog);
  }
  /**
   * Retorna o CNPJ da entidade
   *
   * @return string
   */
  function getCnpj() {
  	global $cgc;
	  $sql  = " select cgc                                   ";
	  $sql .= "  from db_config                              ";
	  $sql .= " where codigo =".db_getsession("DB_instit")    ;
	  $result = db_query($sql);
	  db_fieldsmemory($result,0);
   
    $this->sCnpj = $cgc;
    return $this->sCnpj;
  }  
  
  /**
   * Retorna o SIAFI da entidade
   *
   * @return string
   */
  function getSiafi() {
    global $q110_codigo;
    $sql  = " select cgc, q110_codigo                      ";
    $sql .= "  from db_config                              ";
    $sql .= " INNER JOIN municipiosiafi ON (db_config.cgc =municipiosiafi.q110_cnpj) ";
    $sql .= " where codigo =".db_getsession("DB_instit")    ;
    $result = db_query($sql);
    db_fieldsmemory($result,0);
   
    $this->sSiafi = $q110_codigo;
    return $this->sSiafi;
  }    
  public function setUnidadeGestora( $iunidade ){
    $this->iUnidadeGestora = $iunidade;
  }
  public function setTipoAto( $itipoato ){
    $this->iTipoAto = $itipoato;
  }
  public function setNumeroAto( $inumeroato ){
    $this->iNumeroAto = $inumeroato;
  }
  public function setAnoAto( $ianoato ){
    $this->iAnoAto = $ianoato;
  }
  public function setDataAto( $idataato ){
    $this->dDataAto = $idataato;
  }
  public function setRepresentante( $crepresentante ){
    $this->cRepresentante = $crepresentante;
  }
}

?>
