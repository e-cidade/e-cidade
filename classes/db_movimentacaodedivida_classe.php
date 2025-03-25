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

class cl_movimentacaodedivida
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
  var $numrows_incluir = 0;
  // cria variaveis do arquivo 
  var $op02_sequencial = 0;
  var $op02_operacaodecredito = null;
  var $op02_movimentacao = null;
  var $op02_tipo = null;
  var $op02_data = null;
  var $op02_data_dia = null;
  var $op02_data_mes = null;
  var $op02_data_ano = null;
  var $op02_justificativa = null;
  var $op02_valor = null;
  var $op02_movautomatica = "f";
  var $op02_codigoplanilha = 0;

  // cria propriedade com as variaveis do arquivo 
  var $campos = "
                op02_sequencial = int4 = Sequencial
                op02_operacaodecredito = int8 = Operacao de credito
                op02_movimentacao = int8 = Movimentacao
                op02_tipo = int8 = Tipo
                op02_data = int8 = Data
                op02_justificativa = int8 = Justificativa
                op02_valor = int8 = Valor
                op02_movautomatica = bool = Movimentacao automatica
                op02_codigoplanilha  = int8 = Codigo planilha
                ";
  //funcao construtor da classe 
  function cl_movimentacaodedivida()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("movimentacaodedivida");
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
      $this->op02_operacaodecredito = ($this->op02_operacaodecredito == "" ? @$GLOBALS["HTTP_POST_VARS"]["op02_operacaodecredito"] : $this->op02_operacaodecredito);
      $this->op02_movimentacao = (!empty($GLOBALS["HTTP_POST_VARS"]["op02_movimentacao"]) ? $GLOBALS["HTTP_POST_VARS"]["op02_movimentacao"] : $this->op02_movimentacao);
      $this->op02_tipo = (!empty($GLOBALS["HTTP_POST_VARS"]["op02_tipo"]) ? $GLOBALS["HTTP_POST_VARS"]["op02_tipo"] : $this->op02_tipo);      
      if ($this->op02_data == "") {
        $this->op02_data_dia = @$GLOBALS["HTTP_POST_VARS"]["op02_data_dia"];
        $this->op02_data_mes = @$GLOBALS["HTTP_POST_VARS"]["op02_data_mes"];
        $this->op02_data_ano = @$GLOBALS["HTTP_POST_VARS"]["op02_data_ano"];
        if ($this->op02_data_dia != "") {
          $this->op02_data = $this->op02_data_ano . "-" . $this->op02_data_mes . "-" . $this->op02_data_dia;
        }
      }
      $this->op02_justificativa = (!empty($GLOBALS["HTTP_POST_VARS"]["op02_justificativa"]) ? $GLOBALS["HTTP_POST_VARS"]["op02_justificativa"] : $this->op02_justificativa);
      $this->op02_valor = (!empty($GLOBALS["HTTP_POST_VARS"]["op02_valor"]) ? $GLOBALS["HTTP_POST_VARS"]["op02_valor"] : $this->op02_valor);
      $this->op02_movautomatica = (!empty($GLOBALS["HTTP_POST_VARS"]["op02_movautomatica"]) ? $GLOBALS["HTTP_POST_VARS"]["op02_movautomatica"] : $this->op02_movautomatica);
      $this->op02_codigoplanilha = (!empty($GLOBALS["HTTP_POST_VARS"]["op02_codigoplanilha"]) ? $GLOBALS["HTTP_POST_VARS"]["op02_codigoplanilha"] : $this->op02_codigoplanilha);
    }
  }
  // funcao para inclusao
  function incluir()
  {
    $this->atualizacampos();
    if ($this->op02_operacaodecredito == null) {
      $this->erro_sql = " Campo Operação de Credito não Informado.";
      $this->erro_campo = "op02_operacaodecredito";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op02_movimentacao == null) {
      $this->erro_sql = " Campo Movimento não Informado.";
      $this->erro_campo = "op02_movimentacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->op02_data == null) {
      $this->erro_sql = " Campo Data não Informado.";
      $this->erro_campo = "op02_data";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sqlString = "INSERT INTO movimentacaodedivida (              
              op02_operacaodecredito,
              op02_movimentacao,
              op02_tipo,
              op02_data, 
              op02_justificativa,
              op02_valor,
              op02_movautomatica,
              op02_codigoplanilha
          ) VALUES (
              {$this->op02_operacaodecredito},
              {$this->op02_movimentacao},
              {$this->op02_tipo},
              '{$this->op02_data}',
              '{$this->op02_justificativa}',
              {$this->op02_valor},
              '{$this->op02_movautomatica}',
              {$this->op02_codigoplanilha}
               )";
    $result = db_query($sqlString);
      
    if ($result == false) {
      $this->erro_banco = $sqlString;//str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Houve um erro durante o cadastro.";
      $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Status: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n\\n";
    $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n" . $this->erro_sql . " \\n\\n";
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);

    $result = db_query("select currval('movimentacaodedivida_op02_sequencial_seq')");
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Verifique o cadastro da sequencia: movimentacaodedivida_op02_sequencial_seq do campo: op02_sequencial";
      $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Status: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->op02_sequencial = pg_result($result, 0, 0);
    
    return true;
  }
  // funcao para alteracao
  function alterar($op02_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update movimentacaodedivida set ";
    $virgula = "";

    if (trim($this->op02_operacaodecredito) != "" || isset($GLOBALS["HTTP_POST_VARS"]["op02_operacaodecredito"])) {
      $sql  .= $virgula . " op02_operacaodecredito = '$this->op02_operacaodecredito' ";
      $virgula = ",";
      if (trim($this->op02_operacaodecredito) == null) {
        $this->erro_sql = " Campo Operação de Credito nao Informado.";
        $this->erro_campo = "op02_operacaodecredito";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Status: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->op02_movimentacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["op02_movimentacao"])) {
      $sql  .= $virgula . " op02_movimentacao = $this->op02_movimentacao ";
      $virgula = ",";
      if (trim($this->op02_movimentacao) == null) {
        $this->erro_sql = " Campo Movimentacao nao Informado.";
        $this->erro_campo = "op02_movimentacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Status: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->op02_tipo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["op02_tipo"])) {
      $sql  .= $virgula . " op02_tipo = $this->op02_tipo ";
      $virgula = ",";
      if (trim($this->op02_tipo) == null) {
        $this->erro_sql = " Campo Tipo nao Informado.";
        $this->erro_campo = "op02_tipo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Status: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->op02_data) != "" || isset($GLOBALS["HTTP_POST_VARS"]["op02_data"])) {
      $sql  .= $virgula . " op02_data = '$this->op02_data' ";
      $virgula = ",";
      if (trim($this->op02_data) == null) {
        $this->erro_sql = " Campo Data nao Informado.";
        $this->erro_campo = "op02_data";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Status: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->op02_justificativa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["op02_justificativa"])) {
      $sql  .= $virgula . " op02_justificativa = '$this->op02_justificativa' ";
      $virgula = ",";
      if (trim($this->op02_justificativa) == null) {
        $this->erro_sql = " Campo Justificativa nao Informado.";
        $this->erro_campo = "op02_justificativa";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Status: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->op02_valor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["op02_valor"])) {
      $sql  .= $virgula . " op02_valor = $this->op02_valor ";
      $virgula = ",";
      if (trim($this->op02_valor) == null) {
        $this->erro_sql = " Campo Valor nao Informado.";
        $this->erro_campo = "op02_valor";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: " . db_getsession("DB_login") . "\\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Status: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }

    $sql .= " where  op02_sequencial = $op02_sequencial";

    $result = db_query($sql);

    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Registro nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->op02_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = " Registro nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->op02_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->op02_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        return true;
      }
    }
  }
  // funcao para exclusao  
  function excluir($op02_sequencial = null, $dbwhere = null)  
  {
  $sql = " delete from movimentacaodedivida
                  where ";
  $sql2 = "";
  if ($dbwhere == null || $dbwhere == "") {
    if ($op02_sequencial != "") {
      if ($sql2 != "") {
        $sql2 .= " and ";
      }
      $sql2 .= " op02_sequencial = $op02_sequencial ";
    }
  } else {
    $sql2 = $dbwhere;
  }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Registro nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->op02_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Registro nao Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $this->op02_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->op02_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        return true;
      }
    }
  }

  // funcao do recordset 
  function sql_record($sql)
  {
    $result = db_query($sql);
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
  function sql_query($op02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from movimentacaodedivida ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($op02_sequencial != null) {
        $sql2 .= " where movimentacaodedivida.op02_sequencial = $op02_sequencial ";
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
  function sql_query_file($op02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from movimentacaodedivida ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($op02_sequencial != null) {
        $sql2 .= " where movimentacaodedivida.op02_sequencial = $op02_sequencial ";
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

  function buscaDados($op02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from movimentacaodedivida 
              left join cgm on op01_credor = z01_numcgm";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($op02_sequencial != null) {
        $sql2 .= " where movimentacaodedivida.op02_sequencial = $op02_sequencial ";
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

  function buscaMovimentacoes($op02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from movimentacaodedivida 
              left join movimentacaodetalhe on op02_movimentacao = op03_sequencial";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($op02_sequencial != null) {
        $sql2 .= " where movimentacaodedivida.op02_sequencial = $op02_sequencial ";
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
