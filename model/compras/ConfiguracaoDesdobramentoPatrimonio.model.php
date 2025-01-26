<?php
/**
 * Classe Singletown para verifica��o de configura��es de desdobramento do patrim�nio
 * @author vinicius.silva@dbseller.com.br
 * @package patrimonio
 */

class ConfiguracaoDesdobramentoPatrimonio {
  
  static $oInstance;
  
  protected $aListaDesdobramentos = array();
  
  /**
   * M�todo construtor
   */
  protected function __construct() {
    
    $oDaoConfiguracaoDesdobramentoPatrimonio = db_utils::getDao('configuracaodesdobramentopatrimonio');
    $sSqlBuscaDesdobramentos                 = $oDaoConfiguracaoDesdobramentoPatrimonio->sql_query_file();
    $rsBuscaDesdobramentos                   = $oDaoConfiguracaoDesdobramentoPatrimonio->sql_record($sSqlBuscaDesdobramentos);
    $this->aListaDesdobramentos[]            = db_utils::getCollectionByRecord($rsBuscaDesdobramentos);
  }
  
  /**
   * Retorna a inst�ncia da classe
   * @return ConfiguracaoDesdobramentoPatrimonio
   */
  protected function getInstance() {
    
    if (self::$oInstance == null) {
      self::$oInstance = new ConfiguracaoDesdobramentoPatrimonio();
    }
    return self::$oInstance;
  }
  
  /**
   * Verifica se o elemento passado no par�metro possui configura��o de desdobramento
   * @param integer $iCodigoElemento codido do elemento
   * @return mixed retorna objeto da configura��o, ou false se n�o houver a configura��o
   */
  public function getConfiguracaoElemento($iCodigoElemento) {
    
    $aDesdobramentos = self::getInstance()->aListaDesdobramentos;
    $mRetorno        = false;
    
    foreach ($aDesdobramentos as $aDesdobramento) {
      
      foreach ($aDesdobramento as $oDesdobramento) {
        
        if ($oDesdobramento->e135_desdobramento == $iCodigoElemento) {
          
          $mRetorno = $oDesdobramento;
          break;
        }
      }
    }
    return $mRetorno;
  }
}