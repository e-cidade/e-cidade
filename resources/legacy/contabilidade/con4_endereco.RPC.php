<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("model/endereco.model.php");
require_once("model/obrasDadosComplementares.model.php");
require_once("classes/db_obrasdadoscomplementares_classe.php");
require_once("model/configuracao/endereco/Municipio.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$iInstit  = db_getsession("DB_instit");
$oRetorno = new stdClass();
$oRetorno->status  = "1";
$oRetorno->message = "";

switch ($oParam->exec) {
    case 'findCep':
        $aRetorno->endereco = endereco::findCep($oParam->codigoCep, $oParam->sNomeBairro);
        if ($aRetorno->endereco !== false) {
            $aRetorno->estados  = endereco::findEstadoByCodigoPais($aRetorno->endereco[0]->ipais);
        }
        echo $oJson->encode($aRetorno);
        break;

  case 'findPaisByCodigo':

    $oRetorno->sNomePais = endereco::findPaisByCodigo($oParam->iCodigoPais);
    echo $oJson->encode($oRetorno);
    break;

  case 'findPaisByName':

    $aRetorno = endereco::findPaisByName($_POST["string"], true);
    echo $oJson->encode($aRetorno);
    break;

  case 'findEstadoByCodigoPais':

    $oRetorno->itens = endereco::findEstadoByCodigoPais($oParam->iCodigoPais);
    $oRetorno->pais  = endereco::findPaisDbConfig($iInstit);
    echo $oJson->encode($oRetorno);

    break;

  case 'findMunicipioByCodigo':

    $oRetorno->sNomeMunicipio   = endereco::findMunicipioByCodigo($oParam->iCodigoMunicipio, $oParam->iCodigoEstado);
    echo $oJson->encode($oRetorno);

    break;

  case 'findMunicipioByEstado':

    $oRetorno->aMunicipios = endereco::findMunicipioByEstado($oParam->iCodigoEstado);
    $aParametrosEndereco   = endereco::findParametrosEndereco(true);

    $oRetorno->iEstadoPadrao    = $aParametrosEndereco[0]->db72_cadenderestado;
    $oRetorno->iMunicipioPadrao = $aParametrosEndereco[0]->db72_sequencial;
    echo $oJson->encode($oRetorno);

    break;

  case 'findMunicipioByName':

    $aRetorno = endereco::findMunicipioByName($oParam->sQuery, $oParam->iCodigoEstado, true);
    echo $oJson->encode($aRetorno);
    break;

  case 'findCodigoIbge':
    $oRetorno = municipio::getCodigoIbge($oParam->estado, utf8_decode($oParam->cidade));
    echo $oJson->encode($oRetorno);
    break;

  case 'findBairroByCodigo':

    $oRetorno->sNomeBairro  = endereco::findBairroByCodigo($oParam->iCodigoBairro, $oParam->iCodigoMunicipio);
    echo $oJson->encode($oRetorno);
    break;

  case 'findBairroByName':

    $aRetorno = endereco::findBairroByName(
      $oParam->sQuery,
      $oParam->iCodigoEstado,
      $oParam->iCodigoMunicipio,
      true
    );
    echo $oJson->encode($aRetorno);
    break;

  case 'findRuaByCodigo':

    $oRetorno->dados  = endereco::findRuaByCodigo($oParam->iCodigoRua, $oParam->iCodigoMunicipio);
    echo $oJson->encode($oRetorno);
    break;

  case 'findRuaByName':

    //$aRetorno = endereco::findRuaByName($oParam->sQuery,$oParam->iCodigoMunicipio,true);
    $aRetorno = endereco::findRuaByName(
      $oParam->sQuery,
      $oParam->iCodigoEstado,
      $oParam->iCodigoMunicipio,
      $oParam->iCodigoBairro,
      true
    );



    echo $oJson->encode($aRetorno);
    break;

  case 'findNumeroByNumero':

    $oRetorno->dados  = endereco::findNumeroByNumero($oParam->iCodigoNumero, $oParam->iCodigoBairro, $oParam->iCodigoRua);
    //ByCodigo($oParam->iCodigoRua, $oParam->iCodigoMunicipio);
    echo $oJson->encode($oRetorno);
    break;

  case 'findCondominioByName':

    $aRetorno = endereco::findCondominioByName(
      $oParam->sQuery,
      $oParam->iCodigoNumero,
      $oParam->iCodigoBairro,
      $oParam->iCodigoRua,
      true
    );
    echo $oJson->encode($aRetorno);

    break;

  case 'findLoteamentoByName':

    $aRetorno = endereco::findLoteamentoByName(
      $oParam->sQuery,
      $oParam->iCodigoNumero,
      $oParam->iCodigoBairro,
      $oParam->iCodigoRua,
      true
    );
    echo $oJson->encode($aRetorno);

    break;

  case 'findComplementoRua':

    $aRetorno = endereco::findComplementoRua($oParam->iCodigoRua, $oParam->iCodigoMunicipio, $oParam->iCodigoBairro);
    echo $oJson->encode($aRetorno);
    break;

  case 'findComplementoBairro':

    $aRetorno = endereco::findComplementoBairro($oParam->iCodigoBairro);
    echo $oJson->encode($aRetorno);
    break;

  case 'salvarEndereco':

    db_inicio_transacao();
    try {

      $oEndereco = new endereco(null);

      $oEndereco->setCodigoEstado($oParam->endereco->codigoEstado);

      $oEndereco->setCodigoMunicipio($oParam->endereco->codigoMunicipio);

      //$oEndereco->setDescricaoMunicipio(utf8_decode($oParam->endereco->descricaoMunicipio));

      $oEndereco->setCodigoBairro($oParam->endereco->codigoBairro);

      $oEndereco->setDescricaoBairro(utf8_decode(($oParam->endereco->descricaoBairro)));

      $oEndereco->setCodigoRua($oParam->endereco->codigoRua);

      $oEndereco->setDescricaoRua(utf8_decode($oParam->endereco->descricaoRua));

      $oEndereco->setCodigoLocal($oParam->endereco->codigoLocal);

      $oEndereco->setCodigoEndereco($oParam->endereco->codigoEndereco);

      $oEndereco->setNumeroLocal($oParam->endereco->numeroLocal);

      $oEndereco->setComplementoEndereco(utf8_decode($oParam->endereco->descricaoComplemento));

      $oEndereco->setLoteamentoEndereco(utf8_decode($oParam->endereco->descricaoLoteamento));

      $oEndereco->setCondominioEndereco(utf8_decode($oParam->endereco->descricaoCondominio));

      $oEndereco->setPontoReferenciaEndereco(utf8_decode($oParam->endereco->descricaoPontoReferencia));

      $oEndereco->setCepEndereco($oParam->endereco->cepEndereco);
      $oEndereco->setCep($oParam->endereco->cepEndereco);

      $oEndereco->setCadEnderRuaTipo($oParam->endereco->codigoRuaTipo);

      $oEndereco->setCodigoRuasTipo($oParam->endereco->codigoRuasTipo);

      $oEndereco->setCodigoCep($oParam->endereco->codigoCepRua);

      $oEndereco->salvaEndereco();

      db_fim_transacao(false);
      $aRetorno->icodigoEndereco  = $oEndereco->getCodigoEndereco();

      $aRetorno->icodigoMunicipio = $oEndereco->getCodigoMunicipio();
      $aRetorno->icodigoBairro    = $oEndereco->getCodigoBairro();
      $aRetorno->icodigoRua       = $oEndereco->getCodigoRua();
      $aRetorno->message = urlencode("Endereco ($aRetorno->icodigoEndereco) includo com sucesso!");
      $aRetorno->status   = 1;
    } catch (Exception $erro) {

      db_fim_transacao(true);
      $aRetorno->message  = urlencode($erro->getMessage());
      $aRetorno->status   = 2;
    }

    echo $oJson->encode($aRetorno);

    break;

  case 'buscaValoresPadrao':

    try {

      $oRetorno->cepmunic      = endereco::findCepDbConfig($iInstit);
      $oRetorno->tiposRua      = endereco::findRuasTipo();
      $oRetorno->valoresPadrao = endereco::findParametrosEndereco();

      if ($oRetorno->valoresPadrao === false) {
        throw new Exception("usurio: \n\nParmetros do endereo no configurados!\n\nContate o Administrador.\n\n");
      }

      $aEstados = array();

      /**
       * estados pelo pais padrao
       */
      if (!empty($oRetorno->valoresPadrao[0]->db70_sequencial)) {
        $aEstados = endereco::findEstadoByCodigoPais($oRetorno->valoresPadrao[0]->db70_sequencial);
      }
      $oRetorno->estados = $aEstados;
      $oRetorno->aPaises = endereco::getPaises();
    } catch (Exception $oErro) {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode($oErro->getMessage());
    }

    echo $oJson->encode($oRetorno);

    break;

  case 'buscaBairroRuaMunicipio':

    $oRetorno->status        = 1;
    $oRetorno->tiposRua      = endereco::findRuasTipo();
    $oRetorno->valoresPadrao = endereco::findParametrosEndereco();
    $oRetorno->cepmunic      = endereco::findCepDbConfig($iInstit);


    if ($oRetorno->valoresPadrao !== false) {
      $oRetorno->estados   = endereco::findEstadoByCodigoPais($oRetorno->valoresPadrao[0]->db70_sequencial);
    } else {
      $oRetorno->status = 2;
      $oRetorno->message = urlencode("\n\nusurio: \n\nParmetros do endereo no configurados!\n\n Contate o Administrador.\n\n");
    }
    $oRetorno->bairroRuaMunicipio = endereco::buscaBairroRuaMunicipio(
      $oParam->icodigobairromunicipio,
      $oParam->icodigoruamunicipio,
      true
    );

    if ($oRetorno->bairroRuaMunicipio == false) {

      db_inicio_transacao();
      try {
        $oDados = endereco::findBairroRuaMunicipio(
          $oParam->icodigobairromunicipio,
          $oParam->icodigoruamunicipio
        );

        $oEndereco = new endereco(null);

        $oEndereco->setCodigoMunicipio($oDados->codigoMunicipio);

        $oEndereco->setCodigoBairro($oDados->codigoBairro);

        $oEndereco->setDescricaoBairro($oDados->descrBairro);

        $oEndereco->setCodigoRua($oDados->codigoEndereco);

        $oEndereco->setDescricaoRua($oDados->descrEndereco);

        $oEndereco->setCadEnderRuaTipo($oDados->ruaTipo);

        $oEndereco->setRuaCadEnderRuaRuas($oDados->codigoEndereco);

        $oEndereco->setRuasCadEnderRuaRuas($oDados->codigoRuas);

        $oEndereco->cadEnderBairroRuaMunicipio();

        db_fim_transacao(false);
      } catch (Exception $erro) {

        db_fim_transacao(true);
        $oRetorno->message  = urlencode($erro->getMessage());
        $oRetorno->status   = 2;
      }
    }

    if ($oRetorno->status == 1) {
      $oRetorno->bairroRuaMunicipio = endereco::buscaBairroRuaMunicipio(
        $oParam->icodigobairromunicipio,
        $oParam->icodigoruamunicipio,
        true
      );
    }

    echo $oJson->encode($oRetorno);

    break;

  case 'buscaEndereco':

    try {

      $oEndereco = new endereco($oParam->icodigoendereco);
      $oRetorno->endereco = new stdClass();

      $oRetorno->endereco->iPais             = $oEndereco->getCodigoPais();
      $oRetorno->endereco->sPais             = urlencode($oEndereco->getDescricaoPais());

      $oRetorno->endereco->iEstado           = $oEndereco->getCodigoEstado();
      $oRetorno->endereco->sEstado           = urlencode($oEndereco->getDescricaoEstado());

      $oRetorno->endereco->iMunicipio        = $oEndereco->getCodigoMunicipio();
      $oRetorno->endereco->sMunicipio        = urlencode($oEndereco->getDescricaoMunicipio());

      $oRetorno->endereco->iBairro           = $oEndereco->getCodigoBairro();
      $oRetorno->endereco->sBairro           = urlencode($oEndereco->getDescricaoBairro());

      $oRetorno->endereco->iRua              = $oEndereco->getCodigoRua();
      $oRetorno->endereco->sRua              = urlencode($oEndereco->getDescricaoRua());

      $oRetorno->endereco->iRuaTipo          = $oEndereco->getCadEnderRuaTipo();
      $oRetorno->endereco->iRuasTipo         = $oEndereco->getCodigoRuasTipo();

      $oRetorno->endereco->iLocal            = $oEndereco->getCodigoLocal();
      $oRetorno->endereco->sNumeroLocal      = urlencode($oEndereco->getNumeroLocal());

      $oRetorno->endereco->iEndereco         = $oEndereco->getCodigoEndereco();
      $oRetorno->endereco->sCondominio       = urlencode($oEndereco->getCondominioEndereco());
      $oRetorno->endereco->sLoteamento       = urlencode($oEndereco->getLoteamentoEndereco());
      $oRetorno->endereco->sComplemento      = urlencode($oEndereco->getComplementoEndereco());
      $oRetorno->endereco->sPontoReferencia  = urlencode($oEndereco->getPontoReferenciaEndereco());
      $oRetorno->endereco->sCep              = urlencode($oEndereco->getCepEndereco());
      $oRetorno->endereco->iCep              = $oEndereco->getCodigoCep();

      $oRetorno->aPaises          = endereco::getPaises();
      $oRetorno->estados          = endereco::findEstadoByCodigoPais($oRetorno->endereco->iPais);
      $oRetorno->iCodigoMunicipio = $oEndereco->getCodigoMunicipio();
      $oRetorno->tiposRua         = endereco::findRuasTipo();
    } catch (Exception $erro) {

      $oRetorno->status   = 2;
      $oRetorno->message  = urlencode($erro->getMessage());
    }

    echo $oJson->encode($oRetorno);

    break;
  case 'buscaEnderecoCidadao':
    /*
    $oRetorno->pais      = endereco::findPaisDbConfig($iInstit);
    $oRetorno->municipio = endereco::findMunicipioDbConfig($iInstit, $oRetorno->pais[0]->db71_sequencial);
    $oRetorno->tiposRua  = endereco::findRuasTipo();
    $oRetorno->estados   = endereco::findEstadoByCodigoPais($oRetorno->pais[0]->db70_sequencial);
    */
    $oRetorno->tiposRua      = endereco::findRuasTipo();
    $oRetorno->valoresPadrao = endereco::findParametrosEndereco();

    if ($oRetorno->valoresPadrao !== false) {
      $oRetorno->estados   = endereco::findEstadoByCodigoPais($oRetorno->valoresPadrao[0]->db70_sequencial);
    } else {
      $oRetorno->status = 2;
      $oRetorno->message = urlencode("\n\nusurio: \n\nParmetros do endereo no configurados!\n\n Contate o Administrador.\n\n");
    }

    $oRetorno->enderecocidadao = endereco::buscaEnderecoCidadao($oParam->ov02_sequencial, $oParam->ov02_seq);

    echo $oJson->encode($oRetorno);

    break;

  case 'salvarDadosComplementares':

    db_inicio_transacao();
    try {
      $oEndereco = new obrasDadosComplementares(null, $oParam->endereco->sLote);
      $oEndereco->setEstado($oParam->endereco->codigoEstado);
      $oEndereco->setPais($oParam->endereco->codigoPais);
      $oEndereco->setMunicipio($oParam->endereco->codigoMunicipio);
      $oEndereco->setBairro($oParam->endereco->descrBairro);
      $oEndereco->setNumero($oParam->endereco->numero);
      $oEndereco->setCep($oParam->endereco->cep);
      $oEndereco->setCodigoObra($oParam->endereco->codigoObra);
      $oEndereco->setDistrito($oParam->endereco->distrito);
      $oEndereco->setLogradouro($oParam->endereco->logradouro);

      if (!$oParam->endereco->lLote) {

        $oEndereco->setGrausLatitude($oParam->endereco->grausLatitude);
        $oEndereco->setMinutoLatitude($oParam->endereco->minutoLatitude);
        $oEndereco->setSegundoLatitude($oParam->endereco->segundoLatitude);
        $oEndereco->setGrausLongitude($oParam->endereco->grausLongitude);
        $oEndereco->setMinutoLongitude($oParam->endereco->minutoLongitude);
        $oEndereco->setSegundoLongitude($oParam->endereco->segundoLongitude);
      } else {

        $oEndereco->setLatitude($oParam->endereco->latitude);
        $oEndereco->setLongitude($oParam->endereco->longitude);
        $oEndereco->setLote($oParam->endereco->sLote);
        $oEndereco->setFlagLote($oParam->endereco->lLote);
      }

      $oEndereco->setPlanilhaTce($oParam->endereco->planilhaTce);
      $oEndereco->setClasseObjeto($oParam->endereco->classeObjeto);
      $oEndereco->setAtividadeObra($oParam->endereco->atividadeObra);
      $oEndereco->setAtividadeServico($oParam->endereco->atividadeServico);
      $oEndereco->setDescrAtividadeServico($oParam->endereco->descrAtividadeServico);
      $oEndereco->setAtividadeServicoEsp($oParam->endereco->atividadeServicoEsp);
      $oEndereco->setDescrAtividadeServicoEsp($oParam->endereco->descrAtividadeServicoEsp);
      $oEndereco->setGrupoBemPublico(intval($oParam->endereco->grupoBemPub));
      if ($oParam->endereco->grupoBemPub == '99') {
        $oEndereco->setSubGrupoBemPublico('9900');
      } else {
        $oEndereco->setSubGrupoBemPublico(intval($oParam->endereco->subGrupoBemPub));
      }
      $oEndereco->setBdi($oParam->endereco->bdi);
      $oEndereco->setLicita($oParam->endereco->licitacao);
      $oEndereco->setSequencial($oParam->endereco->sequencial);
      $oEndereco->setSeqObrasCodigos($oParam->endereco->seqobrascodigo);

      $oEndereco->salvaDadosComplementares($oParam->acao);
      db_fim_transacao(false);
    } catch (Exception $erro) {
      db_fim_transacao(true);
      $oRetorno->message  = urlencode($erro->getMessage());
      $oRetorno->status   = 2;
    }
    echo $oJson->encode($oRetorno);
    break;


  case 'findDadosObra':
    $oRetorno->dadoscomplementares = obrasDadosComplementares::findObraByCodigo($oParam->iSequencial, $oParam->licitacao);
    $oRetorno->status = 1;
    echo $oJson->encode($oRetorno);
    break;

  case 'findDadosObraLicitacao':
    try {
      if ($oParam->codLicitacao) {
        $oRetorno->dadoscomplementares = obrasDadosComplementares::findObrasByLicitacao($oParam->codLicitacao);
        //echo "<pre>";
        //var_dump(obrasDadosComplementares::findObrasByLicitacao($oParam->codLicitacao));
      }
      $oRetorno->status = 1;
    } catch (Exception $error) {
      $oRetorno->message = $error;
      $oRetorno->status = 2;
    }

    echo $oJson->encode($oRetorno);
    break;

  case 'excluiDadosObra':
    db_inicio_transacao();
    try {

      if ($oParam->sequencial) {
        if (obrasDadosComplementares::isManyRegisters($oParam->sequencial, $oParam->licitacao)) {
          throw new Exception("Primeiro Registro no pode ser excludo.\n\nRemova os demais registros para exclu-lo.\n\n");
        } else {

          $rsLicitacao = db_query('select l20_datacria, l20_tipojulg from liclicita where l20_codigo = ' . $oParam->licitacao);
          $oLicitacao = db_utils::fieldsMemory($rsLicitacao, 0);
          $tabela = $oLicitacao->l20_datacria >= '2021-05-01' ? 'obrasdadoscomplementareslote' : 'obrasdadoscomplementares';

          $clObras = db_utils::getDao($tabela);
          $sSql = $clObras->sql_query_completo('', 'db150_sequencial', '', 'db151_liclicita = ' . $oParam->licitacao);

          $rsSql = $clObras->sql_record($sSql);
          $iLinhas = $clObras->numrows;

          $oObrasCodigos = db_utils::getDao('obrascodigos');

          if ($tabela == 'obrasdadoscomplementares') {

            $clObras->excluir('', 'db150_sequencial = ' . $oParam->sequencial);

            if ($clObras->numrows_excluir) {
              $msg = urlencode($clObras->erro_msg);
            }

            if ($iLinhas == 1) {
              $oObrasCodigos->excluir('', 'db151_liclicita = ' . $oParam->licitacao);
              $msg = urlencode($oObrasCodigos->erro_msg);
            }
          } else {

            $sSqlLotes = "
                            SELECT db150_sequencial, db150_codobra
                              FROM obrasdadoscomplementareslote
                              WHERE db150_lote IN
                                (SELECT l04_codigo
                                   FROM liclicitemlote
                                   WHERE l04_descricao =
                                     (SELECT l04_descricao
                                        FROM liclicitemlote
                                        WHERE l04_codigo =
                                          (SELECT db150_lote
                                             FROM obrasdadoscomplementareslote
                                             WHERE db150_sequencial = $oParam->sequencial)))";

            $rsLotes = db_query($sSqlLotes);

            if (pg_numrows($rsLotes)) {

              $aLotes  = db_utils::getCollectionByRecord($rsLotes);

              for ($count = 0; $count < count($aLotes); $count++) {

                $clDadosComplementares = db_utils::getDao('obrasdadoscomplementareslote');
                $clDadosComplementares->excluir($aLotes[$count]->db150_sequencial);

                if (!$clDadosComplementares->numrows_excluir) {
                  throw new Exception('Registros dos dados complementares do lote no podem ser excludos');
                }
              }
            } else {
              $clDadosComplementares = db_utils::getDao('obrasdadoscomplementareslote');
              $clDadosComplementares->excluir($oParam->sequencial);

              if (!$clDadosComplementares->numrows_excluir) {
                throw new Exception('Registros dos dados complementares do lote no pode ser excludo');
              }
            }

            $sSqlItens = "
                            SELECT obrasdadoscomplementareslote.*
                              FROM obrasdadoscomplementareslote
                              INNER JOIN obrascodigos ON db151_sequencial = db150_seqobrascodigos
                              WHERE db151_liclicita = $oParam->licitacao
                        ";

            $rsItens = db_query($sSqlItens);

            if (!pg_numrows($rsItens)) {
              $oObrasCodigos->excluir('', 'db151_liclicita = ' . $oParam->licitacao);
            }

            if ($oLicitacao->l20_tipojulg == 1) {
              $msg = urlencode('Endereo da obra excludo com sucesso!');
            } else {
              $msg = urlencode('Endereo do lote excludo com sucesso!');
            }
          }
        }
      }
      $oRetorno->message = $msg;
      $oRetorno->lote = $tabela == 'obrasdadoscomplementareslote' ? true : false;
      db_fim_transacao(false);
    } catch (Exception $erro) {
      db_fim_transacao(true);
      $oRetorno->message  = urlencode($erro->getMessage());
      $oRetorno->status   = 2;
    }

    echo $oJson->encode($oRetorno);
    break;

  case 'getCodigoObra':
    $clObrasCodigos = new cl_obrascodigos();
    $sSql = $clObrasCodigos->sql_query('', 'db151_codigoobra', '', 'db151_liclicita = ' . $oParam->licitacao);
    $rsSql = $clObrasCodigos->sql_record($sSql);

    if ($clObrasCodigos->numrows) {
      $codigoObra = db_utils::fieldsMemory($rsSql, 0)->db151_codigoobra;
      $oRetorno->obra = $codigoObra;
      $oRetorno->status = 1;
    } else {
      $oRetorno->status = 2;
    }

    echo $oJson->encode($oRetorno);
    break;

  case 'isFirstRegister':
    $table_name = !$oParam->sLote ? 'obrasdadoscomplementares' : 'obrasdadoscomplementareslote';
    $clObras = db_utils::getDao($table_name);
    $sSql = $clObras->sql_query_completo('', 'min(db150_sequencial) as minimo', '', 'db151_codigoobra = ' . $oParam->iCodigo);
    $rsSql = $clObras->sql_record($sSql);

    $iCodigo = db_utils::fieldsMemory($rsSql, 0)->minimo;

    if ($iCodigo == $oParam->iSequencial) {
      $oRetorno->status = 1;
    } else {
      $oRetorno->status = 2;
    }

    echo $oJson->encode($oRetorno);

    break;

  case 'getBdi':
    $rsBdi = db_query('select distinct db150_bdi as bdi from obrasdadoscomplementareslote inner join obrascodigos on db151_sequencial = db150_seqobrascodigos inner join liclicita on l20_codigo = db151_liclicita where l20_codigo =  ' . $oParam->licitacao);

    $bdi = db_utils::fieldsMemory($rsBdi, 0);

    if ($bdi->bdi) {

      $oRetorno->bdi = $bdi->bdi;
    } else {
      $oRetorno->bdi = '';
    }

    echo $oJson->encode($oRetorno);

    break;
}
