<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//MODULO: contabilidade
//CLASSE DA ENTIDADE db_operacaodecredito
class cl_db_operacaodecredito
{
  // cria variaveis de erro 
  var $rotulo     = null;
  var $query_sql  = null;
  var $numrows    = 0;
  var $erro_status = null;
  var $erro_sql   = null;
  var $erro_banco = null;
  var $erro_msg   = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo 
  var $op01_sequencial = 0;
  var $op01_numerocontratoopc = null;
  var $op01_dataassinaturacop_dia = null;
  var $op01_dataassinaturacop_mes = null;
  var $op01_dataassinaturacop_ano = null;
  var $op01_dataassinaturacop = null;
  var $op01_numeroleiautorizacao = null;
  var $op01_dataleiautorizacao = null;
  var $op01_dataleiautorizacao_dia = null;
  var $op01_dataleiautorizacao_mes = null;
  var $op01_dataleiautorizacao_ano = null;
  var $op01_valorautorizadoporlei = null; 
  var $op01_credor = null;
  var $op01_tipocredor = null;
  var $op01_tipolancamento = null;
  var $op01_detalhamentodivida = 0;
  var $op01_subtipolancamento = 0;
  var $op01_objetocontrato = null;
  var $op01_descricaodividaconsolidada = null;
  var $op01_descricaocontapcasp = null;
  var $op01_moedacontratacao = null; 
  var $op01_taxajurosdemaisencargos = null;
  var $op01_valorcontratacao = null;
  var $op01_dataquitacao = null;
  var $op01_dataquitacao_dia = null;
  var $op01_dataquitacao_mes = null;
  var $op01_dataquitacao_ano = null;
  var $op01_instituicao = null;
  var $op01_datadepublicacaodalei = null;
  var $op01_datadepublicacaodalei_dia = null;
  var $op01_datadepublicacaodalei_mes = null;
  var $op01_datadepublicacaodalei_ano = null;
  var $op01_datadecadastro = null;
  var $op01_datadecadastro_dia = null;
  var $op01_datadecadastro_mes = null;
  var $op01_datadecadastro_ano = null;

  var $aObrigaSubtipos     = [1,3,4,6,7,21];
  var $aObrigaDetalhamento = [3,9,17,18,20,23];

  // cria propriedade com as variaveis do arquivo 
  var $campos = "
                  op01_sequencial = int4 = Sequencial 
                  op01_numerocontratoopc = varchar(30) =  
                  op01_dataassinaturacop = date =  
                  op01_numeroleiautorizacao = varchar(6) =
                  op01_dataleiautorizacao = date =
                  op01_valorautorizadoporlei = float8 = 
                  op01_credor = int4 = 
                  op01_tipocredor = int8 =
                  op01_tipolancamento = int8 = 
                  op01_detalhamentodivida = int8 =
                  op01_subtipolancamento = int8 =
                  op01_objetocontrato = varchar(1000) =
                  op01_descricaodividaconsolidada = varchar(500) =
                  op01_descricaocontapcasp = varchar(50) =
                  op01_moedacontratacao = varchar(20) = 
                  op01_taxajurosdemaisencargos = varchar(1000) =
                  op01_valorcontratacao = float8 = 
                  op01_dataquitacao = date =
                  op01_instituicao = int8 =
                  op01_datadepublicacaodalei = date =
                  op01_datadecadastro = date =
                ";
  //funcao construtor da classe 
  function cl_db_operacaodecredito()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("db_operacaodecredito");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }
  //funcao erro 
  function erro($mostra, $retorna)
  {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
      echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
      if ($retorna == true) {
        echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
      }
    }
  }
  // funcao para atualizar campos
  function atualizacampos($exclusao = false)
  {
    if ($exclusao == false) {
      $this->op01_sequencial = ($this->op01_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["op01_sequencial"] : $this->op01_sequencial);
      $this->op01_numerocontratoopc = ($this->op01_numerocontratoopc == "" ? @$GLOBALS["HTTP_POST_VARS"]["op01_numerocontratoopc"] : $this->op01_numerocontratoopc);

      if ($this->op01_dataassinaturacop == "") {
        $this->op01_dataassinaturacop_dia = @$GLOBALS["HTTP_POST_VARS"]["op01_dataassinaturacop_dia"];
        $this->op01_dataassinaturacop_mes = @$GLOBALS["HTTP_POST_VARS"]["op01_dataassinaturacop_mes"];
        $this->op01_dataassinaturacop_ano = @$GLOBALS["HTTP_POST_VARS"]["op01_dataassinaturacop_ano"];
        if ($this->op01_dataassinaturacop_dia != "") {
          $this->op01_dataassinaturacop = $this->op01_dataassinaturacop_ano . "-" . $this->op01_dataassinaturacop_mes . "-" . $this->op01_dataassinaturacop_dia;
        }
      }

      if($this->op01_dataleiautorizacao == "") {
        $this->op01_dataleiautorizacao_dia = @$GLOBALS["HTTP_POST_VARS"]["op01_dataleiautorizacao_dia"];
        $this->op01_dataleiautorizacao_mes = @$GLOBALS["HTTP_POST_VARS"]["op01_dataleiautorizacao_mes"];
        $this->op01_dataleiautorizacao_ano = @$GLOBALS["HTTP_POST_VARS"]["op01_dataleiautorizacao_ano"];
        if($this->op01_dataleiautorizacao_dia != "") {
          $this->op01_dataleiautorizacao = $this->op01_dataleiautorizacao_ano . "-" . $this->op01_dataleiautorizacao_mes . "-" . $this->op01_dataleiautorizacao_dia;
        }
      }

      if($this->op01_dataquitacao == "") {
        $this->op01_dataquitacao_dia = @$GLOBALS["HTTP_POST_VARS"]["op01_dataquitacao_dia"];
        $this->op01_dataquitacao_mes = @$GLOBALS["HTTP_POST_VARS"]["op01_dataquitacao_mes"];
        $this->op01_dataquitacao_ano = @$GLOBALS["HTTP_POST_VARS"]["op01_dataquitacao_ano"];
        if($this->op01_dataquitacao_dia != "") {
          $this->op01_dataquitacao = $this->op01_dataquitacao_ano . "-" . $this->op01_dataquitacao_mes . "-" . $this->op01_dataquitacao_dia;
        }
      }

      if($this->op01_datadepublicacaodalei == "") {
        $this->op01_datadepublicacaodalei_dia = @$GLOBALS["HTTP_POST_VARS"]["op01_datadepublicacaodalei_dia"];
        $this->op01_datadepublicacaodalei_mes = @$GLOBALS["HTTP_POST_VARS"]["op01_datadepublicacaodalei_mes"];
        $this->op01_datadepublicacaodalei_ano = @$GLOBALS["HTTP_POST_VARS"]["op01_datadepublicacaodalei_ano"];
        if($this->op01_datadepublicacaodalei_dia != "") {
          $this->op01_datadepublicacaodalei = $this->op01_datadepublicacaodalei_ano . "-" . $this->op01_datadepublicacaodalei_mes . "-" . $this->op01_datadepublicacaodalei_dia;
        }
      }

      if($this->op01_datadecadastro == "") {
        $this->op01_datadecadastro_dia = @$GLOBALS["HTTP_POST_VARS"]["op01_datadecadastro_dia"];
        $this->op01_datadecadastro_mes = @$GLOBALS["HTTP_POST_VARS"]["op01_datadecadastro_mes"];
        $this->op01_datadecadastro_ano = @$GLOBALS["HTTP_POST_VARS"]["op01_datadecadastro_ano"];
        if($this->op01_datadecadastro_dia != "") {
          $this->op01_datadecadastro = $this->op01_datadecadastro_ano . "-" . $this->op01_datadecadastro_mes . "-" . $this->op01_datadecadastro_dia;
        }
      }

      $this->op01_numeroleiautorizacao = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_numeroleiautorizacao"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_numeroleiautorizacao"] : $this->op01_numeroleiautorizacao);
      
      $this->op01_valorautorizadoporlei = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_valorautorizadoporlei"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_valorautorizadoporlei"] : 0);
      $this->op01_credor = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_credor"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_credor"] : $this->op01_credor);
      $this->op01_tipocredor = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_tipocredor"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_tipocredor"] : $this->op01_tipocredor);
      $this->op01_tipolancamento = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_tipolancamento"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_tipolancamento"] : $this->op01_tipolancamento);
      $this->op01_detalhamentodivida = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_detalhamentodivida"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_detalhamentodivida"] : $this->op01_detalhamentodivida);
      $this->op01_subtipolancamento = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_subtipolancamento"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_subtipolancamento"] : $this->op01_subtipolancamento);
      $this->op01_objetocontrato = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_objetocontrato"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_objetocontrato"] : $this->op01_objetocontrato);
      $this->op01_descricaodividaconsolidada = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_descricaodividaconsolidada"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_descricaodividaconsolidada"] : $this->op01_descricaodividaconsolidada);
      $this->op01_descricaocontapcasp = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_descricaocontapcasp"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_descricaocontapcasp"] : $this->op01_descricaocontapcasp);
      $this->op01_moedacontratacao = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_moedacontratacao"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_moedacontratacao"] : $this->op01_moedacontratacao);
      $this->op01_taxajurosdemaisencargos = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_taxajurosdemaisencargos"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_taxajurosdemaisencargos"] : $this->op01_taxajurosdemaisencargos);
      $this->op01_valorcontratacao = (!empty($GLOBALS["HTTP_POST_VARS"]["op01_valorcontratacao"]) ? $GLOBALS["HTTP_POST_VARS"]["op01_valorcontratacao"] : 0);
      
      $this->op01_instituicao = (!empty(db_getsession("DB_instit")) ? db_getsession("DB_instit") : 1);

    } else {
      $this->op01_sequencial = ($this->op01_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["op01_sequencial"] : $this->op01_sequencial);
    }
  }
  // funcao para inclusao
  function incluir($op01_sequencial)
  {
    $this->atualizacampos();

    $registroExistente = $this->registro_existente();

    if(!empty($registroExistente)) {     
      $this->erro_sql = " Registro já existente ou cadastrado anteriormente.";
      $this->erro_campo = "";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "99";
      return false;
    }

    if ($this->op01_datadecadastro == null) {
      $this->erro_sql = " Campo Data de Cadastro não Informado.";
      $this->erro_campo = "op01_datadecadastro";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_numeroleiautorizacao == null) {
      $this->erro_sql = " Campo Nº da Lei de Autorização não Informado.";
      $this->erro_campo = "op01_numeroleiautorizacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_dataleiautorizacao == null) {
      $this->erro_sql = " Campo Data da Lei de Autorização não Informado.";
      $this->erro_campo = "op01_dataleiautorizacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_datadepublicacaodalei == null) {
      $this->erro_sql = " Campo Data de Publicação da Lei não Informado.";
      $this->erro_campo = "op01_datadepublicacaodalei";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_numerocontratoopc == null) {
      $this->erro_sql = " Campo Nº do Contrato não Informado.";
      $this->erro_campo = "op01_numerocontratoopc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_dataassinaturacop == null) {
      $this->erro_sql = " Campo Data de Assinatura do Contrato não Informado.";
      $this->erro_campo = "op01_dataassinaturacop";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_credor == null) {
      $this->erro_sql = " Campo Credor não Informado.";
      $this->erro_campo = "op01_credor";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_tipocredor == "0"  || $this->op01_tipocredor == 0) {
      $this->erro_sql = " Campo Tipo de Credor não Informado.";
      $this->erro_campo = "op01_tipocredor";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_tipolancamento == "0" || $this->op01_tipolancamento == 0) {
      $this->erro_sql = " Campo Tipo de Lançamento não Informado.";
      $this->erro_campo = "op01_tipolancamento";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if(empty($this->op01_subtipolancamento) && in_array($this->op01_tipolancamento, $this->aObrigaSubtipos)) {
      $this->erro_sql = " Campo Subtipo do Lançamento é obrigatório para o Tipo de Lançamento selecionado.";
      $this->erro_campo = "op01_subtipolancamento";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if(empty($this->op01_detalhamentodivida) && in_array($this->op01_tipolancamento, $this->aObrigaDetalhamento)) {
      $this->erro_sql = " Campo Detalhamento da dívida é obrigatório para o Tipo de Lançamento selecionado.";
      $this->erro_campo = "op01_detalhamentodivida";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_objetocontrato == null) {
      $this->erro_sql = " Campo Objeto do Contrato não Informado.";
      $this->erro_campo = "op01_objetocontrato";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op01_descricaodividaconsolidada == null) {
      $this->erro_sql = " Campo Descrição da Dívida Consolidada não Informado.";
      $this->erro_campo = "op01_descricaodividaconsolidada";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    $descricaoContaPcaspSemEspeciais = preg_replace('/[^a-zA-Z0-9]/', '', $this->op01_descricaocontapcasp);

    if (empty($descricaoContaPcaspSemEspeciais) || strlen($descricaoContaPcaspSemEspeciais) < 10) {
      $this->erro_sql = " Campo Descrição da Conta PCASP precisa ter no mínimo 10 caracteres.";
      $this->erro_campo = "op01_descricaocontapcasp";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if (($this->op01_sequencial == null) || ($this->op01_sequencial == "")) {
      $result = db_query("select nextval('db_operacaodecredito_op01_sequencial_seq')");
      if ($result != false) {
        $this->op01_sequencial = pg_result($result, 0, 0);
      }
    } else {
      $result = db_query("select max(op01_sequencial)+1 from db_operacaodecredito");
      $this->op01_sequencial = pg_result($result, 0, 0);
    }

    if (($this->op01_sequencial == null) || ($this->op01_sequencial == "")) {
      $result = db_query("select max(op01_sequencial)+1 from db_operacaodecredito");
      $this->op01_sequencial = pg_result($result, 0, 0);
    }

    $sqlString = "INSERT INTO db_operacaodecredito (
              op01_sequencial,
              op01_numerocontratoopc,
              op01_dataassinaturacop,
              op01_dataleiautorizacao,
              op01_numeroleiautorizacao,
              op01_valorautorizadoporlei,
              op01_credor,
              op01_tipocredor,
              op01_tipolancamento, 
              op01_detalhamentodivida,
              op01_subtipolancamento,
              op01_objetocontrato,
              op01_descricaodividaconsolidada,
              op01_descricaocontapcasp,
              op01_moedacontratacao, 
              op01_taxajurosdemaisencargos,
              op01_valorcontratacao,
              op01_dataquitacao,
              op01_instituicao, 
              op01_datadepublicacaodalei, 
              op01_datadecadastro
          ) VALUES (
              $this->op01_sequencial,
              '$this->op01_numerocontratoopc',
              '$this->op01_dataassinaturacop',
              '$this->op01_dataleiautorizacao',
              '$this->op01_numeroleiautorizacao',
              $this->op01_valorautorizadoporlei,
              $this->op01_credor,
              $this->op01_tipocredor,
              $this->op01_tipolancamento,
              $this->op01_detalhamentodivida,
              $this->op01_subtipolancamento,
              '$this->op01_objetocontrato',
              '$this->op01_descricaodividaconsolidada',
              '$this->op01_descricaocontapcasp',
              '$this->op01_moedacontratacao',
              '$this->op01_taxajurosdemaisencargos',
              $this->op01_valorcontratacao,
              $this->op01_dataquitacao,
              $this->op01_instituicao, 
              '$this->op01_datadepublicacaodalei', 
              '$this->op01_datadecadastro'
          )";

    $result = @pg_query($sqlString);
      
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql   = " ($this->op01_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = " já Cadastrado";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->op01_sequencial = $op01_sequencial;
      } else {
        $this->erro_sql   = " ($this->op01_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->op01_sequencial = $op01_sequencial;
      }
      $this->erro_status = "0";
      return false;
    }

    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : " . $this->op01_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";

    $expiration = time() + (24 * 3600);
    setcookie('save_op01_sequencial', $this->op01_sequencial, $expiration, "/");
    
    return true;
  }
  // funcao para alteracao
  function alterar($op01_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update db_operacaodecredito set ";
    $virgula = "";
    if (trim($this->op01_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["op01_sequencial"])) {
      if (trim($this->op01_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["op01_sequencial"])) {
        $this->op01_sequencial = "0";
      }
      $sql  .= $virgula . " op01_sequencial = $this->op01_sequencial ";
      $virgula = ",";
      if (trim($this->op01_sequencial) == null) {
        $this->erro_sql = " Campo Sequencial não Informado.";
        $this->erro_campo = "op01_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }

    if (trim($this->op01_datadecadastro) != null) {
      $sql  .= $virgula . " op01_datadecadastro = '$this->op01_datadecadastro' ";
      $virgula = ",";
    } else {
      $this->erro_sql = " Campo Data de Cadastro não Informado.";
      $this->erro_campo = "op01_datadecadastro";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if (trim($this->op01_numeroleiautorizacao) != null) {
      $sql  .= $virgula . " op01_numeroleiautorizacao = '$this->op01_numeroleiautorizacao' ";
      $virgula = ",";
    } else {
      $this->erro_sql = " Campo Nº da Lei de Autorização não Informado.";
      $this->erro_campo = "op01_numeroleiautorizacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if (trim($this->op01_dataleiautorizacao) != null) {
      $sql  .= $virgula . " op01_dataleiautorizacao = '$this->op01_dataleiautorizacao' ";
      $virgula = ",";
    } else {
      $this->erro_sql = " Campo Data da Lei de Autorização não Informado.";
      $this->erro_campo = "op01_dataleiautorizacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if (trim($this->op01_datadepublicacaodalei) != null) {
      $sql  .= $virgula . " op01_datadepublicacaodalei = '$this->op01_datadepublicacaodalei' ";
      $virgula = ",";
    } else {
      $this->erro_sql = " Campo Data de Publicação da Lei não Informado.";
      $this->erro_campo = "op01_datadepublicacaodalei";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->op01_valorautorizadoporlei != null) {
      $sql  .= $virgula . " op01_valorautorizadoporlei = $this->op01_valorautorizadoporlei ";
      $virgula = ",";
    }

    if (trim($this->op01_numerocontratoopc) != null) {
      $sql  .= $virgula . " op01_numerocontratoopc = '$this->op01_numerocontratoopc' ";
      $virgula = ",";
    } else {
      $this->erro_sql = " Campo Nº do Contrato não Informado.";
      $this->erro_campo = "op01_numerocontratoopc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if (trim($this->op01_dataassinaturacop) != null) {
      $sql  .= $virgula . " op01_dataassinaturacop = '$this->op01_dataassinaturacop' ";
      $virgula = ",";
    } else {
      $this->erro_sql = " Campo Data de Assinatura do Contrato não Informado.";
      $this->erro_campo = "op01_dataassinaturacop_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if (trim($this->op01_credor) != null) {
      $sql  .= $virgula . " op01_credor = '$this->op01_credor' ";
      $virgula = ",";
    } else {
      $this->erro_sql = " Campo Credor não Informado.";
      $this->erro_campo = "op01_credor";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if (trim($this->op01_tipocredor) != null) {
      $sql  .= $virgula . " op01_tipocredor = $this->op01_tipocredor ";
      $virgula = ",";
    } else {
      $this->erro_sql = " Campo Tipo de Credor não Informado.";
      $this->erro_campo = "op01_tipocredor";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

      $sql  .= $virgula . " op01_instituicao = $this->op01_instituicao ";
      $virgula = ",";

    if (trim($this->op01_tipolancamento) != null) {
      $sql  .= $virgula . " op01_tipolancamento = $this->op01_tipolancamento ";
      $virgula = ",";
    } else {
        $this->erro_sql = " Campo Tipo de Lançamento não Informado.";
        $this->erro_campo = "op01_tipolancamento";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
    }

    if(empty($this->op01_subtipolancamento) && in_array($this->op01_tipolancamento, $this->aObrigaSubtipos)) {
        $this->erro_sql = " Campo Subtipo do Lançamento é obrigatório para o Tipo de Lançamento selecionado.";
        $this->erro_campo = "op01_subtipolancamento";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
    } else {
      $sql  .= $virgula . " op01_subtipolancamento = $this->op01_subtipolancamento ";
      $virgula = ",";
    }

    if(empty($this->op01_detalhamentodivida) && in_array($this->op01_tipolancamento, $this->aObrigaDetalhamento)) {
        $this->erro_sql = " Campo Detalhamento da dívida é obrigatório para o Tipo de Lançamento selecionado.";
        $this->erro_campo = "op01_detalhamentodivida";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
    } else {
      $sql  .= $virgula . " op01_detalhamentodivida = $this->op01_detalhamentodivida ";
      $virgula = ",";
    }

    if (trim($this->op01_objetocontrato) != null) {
      $sql  .= $virgula . " op01_objetocontrato = '$this->op01_objetocontrato' ";
      $virgula = ",";
    } else {
        $this->erro_sql = " Campo Objeto do Contrato não Informado.";
        $this->erro_campo = "op01_objetocontrato";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
    }

    if (trim($this->op01_descricaodividaconsolidada) != null) {
      $sql  .= $virgula . " op01_descricaodividaconsolidada = '$this->op01_descricaodividaconsolidada' ";
      $virgula = ",";
    } else {
        $this->erro_sql = " Campo Descrição da Dívida Consolidada não Informado.";
        $this->erro_campo = "op01_descricaodividaconsolidada";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
    }

    $descricaoContaPcaspSemEspeciais = preg_replace('/[^a-zA-Z0-9]/', '', $this->op01_descricaocontapcasp);

    if (strlen($descricaoContaPcaspSemEspeciais) > 10) {
      $sql  .= $virgula . " op01_descricaocontapcasp = '$this->op01_descricaocontapcasp' ";
      $virgula = ",";
    } else {
        $this->erro_sql = " Campo Descrição da Conta PCASP precisa ter no mínimo 10 caracteres.";
        $this->erro_campo = "op01_descricaocontapcasp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
    }

      $sql  .= $virgula . " op01_moedacontratacao = '$this->op01_moedacontratacao' ";
      $virgula = ",";

      $sql  .= $virgula . " op01_taxajurosdemaisencargos = '$this->op01_taxajurosdemaisencargos' ";
      $virgula = ",";

      $sql  .= $virgula . " op01_valorcontratacao = $this->op01_valorcontratacao ";
      $virgula = ",";

      if($this->op01_dataquitacao == "") {
        $sql  .= $virgula . " op01_dataquitacao = NULL ";
        $virgula = ",";
      } else {
        $sql  .= $virgula . " op01_dataquitacao = '$this->op01_dataquitacao' ";
        $virgula = ",";
      }

    $sql .= " where  op01_sequencial = $this->op01_sequencial
    ";

    $result = @pg_exec($sql);

    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->op01_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->op01_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->op01_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        return true;
      }
    }
  }
  // funcao para exclusao  
  function excluir($op01_sequencial = null)  
  {
    $this->atualizacampos(true);

    $this->excluirPcasp();

    $sql = " delete from db_operacaodecredito
                    where ";
    $sql2 = "";
    if ($this->op01_sequencial != "") {  
      if ($sql2 != "") {
        $sql2 .= " and ";
      }
      $sql2 .= " op01_sequencial = $this->op01_sequencial ";
    }
    $result = @pg_exec($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->op01_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $this->op01_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->op01_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        return true;
      }
    }
  }

  function excluirPcasp() {
     $sql = " DELETE FROM contabilidade.dv_dividaconsolidadapcasp WHERE dv01_codoperacaocredito = $this->op01_sequencial";
     @pg_exec($sql);
  }

  function getLastSequencial() {    
    $result = db_query("select max(db_operacaodecredito.op01_sequencial) from db_operacaodecredito");
    if ($result != false) {
      $this->op01_sequencial = pg_result($result, 0, 0);
    }
    return $this->op01_sequencial;
  }

  // funcao do recordset 
  function sql_record($sql)
  {
    $result = @pg_query($sql);
    if ($result == false) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Dados do Grupo nao Encontrado";
      $this->erro_msg   = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql 
  function sql_query($op01_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from db_operacaodecredito ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($op01_sequencial != null) {
        $sql2 .= " where db_operacaodecredito.op01_sequencial = $op01_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  // funcao do sql 
  function sql_query_file($op01_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from db_operacaodecredito ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($op01_sequencial != null) {
        $sql2 .= " where db_operacaodecredito.op01_sequencial = $op01_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function registro_existente() {

    $this->op01_dataquitacao = ($this->op01_dataquitacao == "") ? 'NULL' : "'".$this->op01_dataquitacao."'";

    $sql = "SELECT op01_sequencial FROM db_operacaodecredito 
            WHERE op01_numerocontratoopc    = '$this->op01_numerocontratoopc'    AND 
            op01_dataassinaturacop          = '$this->op01_dataassinaturacop'    AND 
            op01_numeroleiautorizacao       = '$this->op01_numeroleiautorizacao' AND 
            op01_dataleiautorizacao         = '$this->op01_dataleiautorizacao'   AND 
            op01_valorautorizadoporlei      = $this->op01_valorautorizadoporlei AND
            op01_credor                     = $this->op01_credor AND
            op01_tipocredor                 = $this->op01_tipocredor AND
            op01_tipolancamento             = $this->op01_tipolancamento AND
            op01_detalhamentodivida         = $this->op01_detalhamentodivida AND
            op01_subtipolancamento          = $this->op01_subtipolancamento AND
            op01_objetocontrato             = '$this->op01_objetocontrato' AND
            op01_descricaodividaconsolidada = '$this->op01_descricaodividaconsolidada' AND
            op01_descricaocontapcasp        = '$this->op01_descricaocontapcasp' AND
            op01_moedacontratacao           = '$this->op01_moedacontratacao' AND
            op01_taxajurosdemaisencargos    = '$this->op01_taxajurosdemaisencargos' AND
            op01_valorcontratacao           = $this->op01_valorcontratacao AND 
            op01_instituicao                = $this->op01_instituicao AND 
            op01_datadepublicacaodalei      = '$this->op01_datadepublicacaodalei' ";

    if($this->op01_dataquitacao !== 'NULL') {
      $sql2 = " AND op01_dataquitacao = $this->op01_dataquitacao;";
    } else {
      $sql2 = " AND op01_dataquitacao IS NULL;";
    }

    $sql = $sql . $sql2;

    $result = db_query($sql);
    $exists = null;

    if ($result != false) {
      $exists = pg_result($result, 0, 0);

      $expiration = time() + (24 * 3600);
      setcookie('save_op01_sequencial', $exists, $expiration, "/");
    }

    return $exists;
  }

  function buscaDados($op01_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from db_operacaodecredito 
              left join cgm on op01_credor = z01_numcgm";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($op01_sequencial != null) {
        $sql2 .= " where db_operacaodecredito.op01_sequencial = $op01_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
