<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/exceptions/ParameterException.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/FileException.php");
require_once("libs/exceptions/BusinessException.php");

/**
 * Singleton 
 * 
 * @abstract
 * @package pessoal
 *
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br> 
 */
abstract class DBPessoal {

  /**
   * Mes de competencia da folha
   *
   * @static
   * @var integer
   * @access private
   */
  static private $iMesCompetenciaFolha;

  /**
   * ANo de competencia da folha
   *
   * @static
   * @var integer
   * @access private
   */
  static private $iAnoCompetenciaFolha;

  /**
   * Busca e define mes e ano da folha 
   *
   * @static
   * @access public
   * @return void
   */
  static private function setCompetencia() { 

    $sSqlCompetencia  = " select r11_anousu,                            \n ";
    $sSqlCompetencia .= "        r11_mesusu                              \n";
    $sSqlCompetencia .= "   from cfpess                                  \n";
    $sSqlCompetencia .= "  where r11_instit = ".db_getsession("DB_instit", false);
    $sSqlCompetencia .= "  order by r11_anousu desc,                     \n";
    $sSqlCompetencia .= "           r11_mesusu desc                      \n"; 
    $sSqlCompetencia .= "  limit 1                                       \n";
    
    $rsCompetencia = db_query($sSqlCompetencia);

    if ( !$rsCompetencia ) {
      throw new DBException("Erro ao Buscar os Dados da Competencia da Folha. ". pg_last_error() );
    }

    if ( pg_num_rows($rsCompetencia) == 0 ) {
      throw new BusinessException("N�o existe nenhuma compentencia iniciada, Favor efetuar Abertura da Folha.");
    }

    $oDadosCompetencia = pg_fetch_object($rsCompetencia, 0);
    DBPessoal::$iMesCompetenciaFolha = str_pad($oDadosCompetencia->r11_mesusu, 2, 0, STR_PAD_LEFT);
    DBPessoal::$iAnoCompetenciaFolha = $oDadosCompetencia->r11_anousu;
    return;
  }

  /**
   * Retorna mes de competencia da folha 
   * 
   * @static
   * @access public
   * @return integer
   */
  static public function getMesFolha() {

    if ( is_null(DBPessoal::$iMesCompetenciaFolha) ) {
      DBPessoal::setCompetencia();
    }

    return DBPessoal::$iMesCompetenciaFolha;
  }

  /**
   * Retorna ano de competencia da folha 
   * 
   * @static
   * @access public
   * @return integer
   */
  static public function getAnoFolha() {

    if ( is_null(DBPessoal::$iAnoCompetenciaFolha) ) {
      DBPessoal::setCompetencia();
    }

    return DBPessoal::$iAnoCompetenciaFolha;
  }
   
  /**
   * Retorna a ultima compet�ncia da folha de pagamento
   * 
   * @return DBCompetencia
   */
  static public function getCompetenciaFolha() {
    return new DBCompetencia( DBPessoal::getAnoFolha(), DBPessoal::getMesFolha() );
  }

  /**
   * Retorna quantidade de avos no periodo especificado
   * 
   * @param date $oDataInicial
   * @param date $oDataFinal
   * @param date $oHoje
   * @return number
   */
  static public function getQuantidadeAvos( DBDate $oDataInicial, DBDate $oDataFinal, DBDate $oHoje = null ){
  
  	require_once("libs/db_libpessoal.php");
  	
  	
  	if ( empty($oHoje) ) {
  		$oHoje = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
  	}
  	
  	$iQuantidadeDiasMesAtual   = DBDate::getQuantidadeDiasMes($oHoje->getMes(), $oHoje->getAno());
  	$iQuantidadeDiasMesInicial = DBDate::getQuantidadeDiasMes($oDataInicial->getMes(), $oDataInicial->getAno());
  	$iQuantidadeDiasMesFinal   = DBDate::getQuantidadeDiasMes($oDataFinal->getMes(), $oDataFinal->getAno());
  	
  	/**
  	 * Quantidade de Avos Representa a Fra��o de 1 mes no ano (1/12 - um, doze avos) 
  	 */
  	$iQuantidadeAvos = 0;
  	
  	if( $oHoje->getTimeStamp() < $oDataFinal->getTimeStamp()) {
  
  		if( $iQuantidadeDiasMesAtual == $iQuantidadeDiasMesInicial ) {
  			// no mesmo ano so ver a diferenca de meses
  			$iQuantidadeAvos = $oHoje->getMes() - $oDataInicial->getMes();
  		}else{
  			// meses restante do ano anterior mais meses do ano posterior
  			// (12 - mes) = quantidade de meses a contar como periodo aquisitivo no ano
  			$iQuantidadeAvos = ( 12 - $oDataInicial->getMes() ) + $oHoje->getMes() ;
  		}
  
  		if ( ( $iQuantidadeDiasMesAtual - $iQuantidadeDiasMesInicial ) > 14 ) {
  			// a fra��o superior a 14 dias - 1/12 avo. , conta como um mes a mais
  			$iQuantidadeAvos++;
  		}
  	} else {
  
  		$iQuantidadeAvos = DBDate::calculaIntervaloEntreDatas($oDataFinal, $oDataInicial, "d") / 30; //MES Comercial, 30 dias
  
  		if ( $iQuantidadeAvos < 0 ) {
  			$iQuantidadeAvos = $iQuantidadeAvos * (-1);
  		}
  
  		// o periodo aquisitivo nao pode ser maior que um ano ou seja 12 meses
  		if ( $iQuantidadeAvos > 12 ) {
  			$iQuantidadeAvos = 12;
  		}
  	}
  
  	return $iQuantidadeAvos;
  }
  
  /**
   * Retorna as Competencias da Folha de Pagamento Tendo Base intervalo de duas datas
   *
   * @param  DBDate $oDataInicial
   * @param  DBDate $oDataFinal
   * @access public
   * @return DBCompetencia[]
   */
  static function getCompetenciasIntervalo( DBDate $oDataInicial, DBDate $oDataFinal ) {
  
    /**
     * Competencia inicial
     */
    $iMesInicio = (int) $oDataInicial->getMes();
    $iAnoInicio = (int) $oDataInicial->getAno();
  
    /**
     * Competencia final
    */
    $iMesFim = (int) $oDataFinal->getMes();
    $iAnoFim = (int) $oDataFinal->getAno();
  
    /**
     * Valida datas, data inicial nao pode ser maior que final
    */
    if ( $oDataInicial->getTimeStamp() >  $oDataFinal->getTimeStamp() ) {
      throw new Exception('Data inicial n�o pode ser maior que a final');
    }
  
    $aRetorno = array();
  
    $iAnoCalculado = $iAnoInicio;
    $iMesCalculado = $iMesInicio;
  
    /**
     * Subrai 1 mes
     * quando:
     * - Mes de inicio � igual mes final
     * - Ano de inicio diferente do ano final
     * - Mes final maior que 1, para nao deixar mes 0
     */
    if ( $iMesInicio == $iMesFim && $iAnoInicio != $iAnoFim && $iMesFim > 1 ) {
      $iMesFim = $iMesFim - 1;
    }
  
    while ( 1 ) {
  
      $aRetorno[] = new DBCompetencia($iAnoCalculado, $iMesCalculado);
  
      /**
       * data dos periodos iguais
       */
      if ( $iAnoInicio == $iAnoFim && $iMesInicio == $iMesFim ) {
        break;
      }
  
      /**
       * Final do periodo calculado
       * Ano e mes calculado igual o ano e mes da data final
       */
      if ( $iAnoCalculado == $iAnoFim && $iMesCalculado ==  $iMesFim ) {
        break;
      }
  
      if ( $iMesCalculado == 12 ) {
  
        $iMesCalculado = 1;
        $iAnoCalculado++;
        continue;
      }
      $iMesCalculado++;
      continue;
    }
    return $aRetorno;
  }

  /**
   * Retorna as Vari�veis para c�lculo conforme o servidor
   *
   * @example 
   *   $iMatricula = 1
   *   $iAnoFolha  = 2013
   *   $iMesFolha  = 11
   *   $oServidor  = ServidorRepository::getInstanciaByCodigo($iMatricula, $iAnoFolha, $iMesFolha);
   *   $oVariaveis = DBPessoal::getVariaveisCalculo($oServidor);
   *     
   * @param  Servidor $oServidor Cont�m os dados do servidor na competencia.
   * @return stdClass            Cada atributo sera a variavel
   */
  function getVariaveisCalculo( Servidor $oServidor ) {
  
    $iInstituicao     = $oServidor->getInstituicao()->getSequencial();
    $iAno             = $oServidor->getAnoCompetencia();
    $iMes             = $oServidor->getMesCompetencia();
    $iMatricula       = $oServidor->getMatricula();

    $oDaoRhPessoalMov = db_utils::getDao('rhpessoalmov');
    $sSqlVariaveis    = $oDaoRhPessoalMov->sql_getVariaveisCalculo($iMatricula, $iAno, $iMes, $iInstituicao); 
    $rsSqlVar         = db_query($sSqlVariaveis);

    return db_utils::fieldsMemory($rsSqlVar,0);
  }

  /**
   * Valida se o sistema es� apto a utilizar a nova estrutura de c�lculo,
   * que modifica basicamente o c�lculo de complementar e cria a FIGURA DE FOLHA SUPLEMENTAR
   * 
   * @return Boolean
   */
  public static function verificarUtilizacaoEstruturaSuplementar() {
    return db_getsession("DB_COMPLEMENTAR", false);
  }
  
  /**
   * M�todo respons�vel por setar na sess�o a estrutura da folha de pagamento.
   * OBS.: Para utilizar a nova estrutura da folha de pagamento, 
   *       a vari�vel "r11_suplementar" deve estar ativada
   *       e ter pelo menos uma folha de pagamento gerada.
   * EX.: C/ Suplementar ou S/Suplementar
   * 
   * @param Instituicao $oInstituicao
   * @param DBCompetencia $oCompetencia
   * @throws DBException
   */
  public static function declararEstruturaFolhaPagamento(Instituicao $oInstituicao, DBCompetencia $oCompetencia) {
      
    $oDaoCfPess      = new cl_cfpess();
    $sSqlSuplementar = $oDaoCfPess->sql_query_suplementar($oInstituicao, $oCompetencia);
    $rsSuplementar   = db_query($sSqlSuplementar);
    
    if (!$rsSuplementar) {
      //throw new DBException("Ocorreu um erro ao declarar a estrutura da folha de pagamento.");
    } else {
    
      $oDadosSuplementar = db_utils::fieldsMemory($rsSuplementar, 0);
      $lSuplementar      = (bool)$oDadosSuplementar->r11_suplementar;
      $iFolhapagamento   = $oDadosSuplementar->rhfolhapagamento;
      
      if ($lSuplementar && $iFolhapagamento > 0) {
        DBPessoal::setEstruturaFolhaPagamento(true);
      } else {
        DBPessoal::setEstruturaFolhaPagamento(false);
      }
    }
  }
  
  /**
   * Seta a estrutura da folha de pagamento na sess�o.
   * 
   * @static
   * @access public
   * @param Boolean $lSuplementar
   */
  public static function setEstruturaFolhaPagamento($lSuplementar) {
    db_putsession("DB_COMPLEMENTAR", $lSuplementar);
  }

  /**
   * Arredonda o valor para duas casas decimais 
   * sem arredondar para mais ou para menos
   * 
   * @param float $valor
   * @return float 
   */
  public static function arredondarValor($valor) {
    $aVlr = explode(".", $valor);
    if (strlen($aVlr[1]) > 2) {
      return floor(($valor*100))/100;
    }
    return $valor;
  }

  /**
   * Retorna uma string com os par�metros do post 
   * formatados para serem usados como uma nova url
   * 
   * @param object $oPost
   * @return string 
   */
  public static function getPostParamAsUrl($oPost) {
    $aPostParams = array();
    foreach ($oPost as $key => $value) {
      if ($key == "opcao_geral" && $oPost->opcao_geral == 4) {
        $value = 1;
      }
      if ($key == "opcao_geral" && $oPost->opcao_geral == 1) {
        $value = 4;
      }
      $aPostParams[] = "{$key}={$value}";
    }
    return implode("&", $aPostParams);
  }
  
}
