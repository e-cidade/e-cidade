<?
//MODULO: sicom
//CLASSE DA ENTIDADE ops122024
class cl_ops122024
{
  // cria variaveis de erro
  var $rotulo = null;
  var $query_sql = null;
  var $numrows = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql = null;
  var $erro_banco = null;
  var $erro_msg = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $si134_sequencial = 0;
  var $si134_tiporegistro = 0;
  var $si134_codreduzidoop = 0;
  var $si134_tipodocumentoop = null;
  var $si134_nrodocumento = null;
  var $si134_codctb = 0;
  var $si134_codfontectb = 0;
  var $si134_dtemissao_dia = null;
  var $si134_dtemissao_mes = null;
  var $si134_dtemissao_ano = null;
  var $si134_dtemissao = null;
  var $si134_vldocumento = 0;
  var $si134_desctipodocumentoop;
  var $si134_mes = 0;
  var $si134_reg10 = 0;
  var $si134_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si134_sequencial = int8 = sequencial
                 si134_tiporegistro = int8 = Tipo do  registro
                 si134_codreduzidoop = int8 = Código  Identificador da  Ordem
                 si134_tipodocumentoop = varchar(2) = Tipo do Documento
                 si134_nrodocumento = varchar(15) = Número do  documento
                 si134_codctb = int8 = Código Identificador da Conta Bancária
                 si134_codfontectb = int8 = Código da fonte de recursos
                 si134_dtemissao = date = Data de emissão do documento
                 si134_vldocumento = float8 = Valor da OP associado ao documento
                 si134_desctipodocumentoop = varchar(50) = Descrição
                 si134_mes = int8 = Mês
                 si134_reg10 = int8 = reg10
                 si134_instit = int4 = Instituição
                 ";

  //funcao construtor da classe
  function cl_ops122024()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("ops122024");
    $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
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
      $this->si134_sequencial = ($this->si134_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_sequencial"] : $this->si134_sequencial);
      $this->si134_tiporegistro = ($this->si134_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_tiporegistro"] : $this->si134_tiporegistro);
      $this->si134_codreduzidoop = ($this->si134_codreduzidoop == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_codreduzidoop"] : $this->si134_codreduzidoop);
      $this->si134_tipodocumentoop = ($this->si134_tipodocumentoop == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_tipodocumentoop"] : $this->si134_tipodocumentoop);
      $this->si134_nrodocumento = ($this->si134_nrodocumento == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_nrodocumento"] : $this->si134_nrodocumento);
      $this->si134_codctb = ($this->si134_codctb == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_codctb"] : $this->si134_codctb);
      $this->si134_codfontectb = ($this->si134_codfontectb == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_codfontectb"] : $this->si134_codfontectb);
      if ($this->si134_dtemissao == "") {
        $this->si134_dtemissao_dia = ($this->si134_dtemissao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_dtemissao_dia"] : $this->si134_dtemissao_dia);
        $this->si134_dtemissao_mes = ($this->si134_dtemissao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_dtemissao_mes"] : $this->si134_dtemissao_mes);
        $this->si134_dtemissao_ano = ($this->si134_dtemissao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_dtemissao_ano"] : $this->si134_dtemissao_ano);
        if ($this->si134_dtemissao_dia != "") {
          $this->si134_dtemissao = $this->si134_dtemissao_ano . "-" . $this->si134_dtemissao_mes . "-" . $this->si134_dtemissao_dia;
        }
      }
      $this->si134_vldocumento = ($this->si134_vldocumento == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_vldocumento"] : $this->si134_vldocumento);
      $this->si134_mes = ($this->si134_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_mes"] : $this->si134_mes);
      $this->si134_reg10 = ($this->si134_reg10 == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_reg10"] : $this->si134_reg10);
      $this->si134_instit = ($this->si134_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_instit"] : $this->si134_instit);
    } else {
      $this->si134_sequencial = ($this->si134_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si134_sequencial"] : $this->si134_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si134_sequencial)
  {
    $this->atualizacampos();
    if ($this->si134_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do  registro nao Informado.";
      $this->erro_campo = "si134_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si134_codreduzidoop == null) {
      $this->si134_codreduzidoop = "0";
    }
    if ($this->si134_codctb == null) {
      $this->si134_codctb = "0";
    }
    if ($this->si134_codfontectb == null) {
      $this->si134_codfontectb = "0";
    }
    if ($this->si134_dtemissao == null) {
      $this->si134_dtemissao = "null";
    }
    if ($this->si134_vldocumento == null) {
      $this->si134_vldocumento = "0";
    }
    if ($this->si134_mes == null) {
      $this->erro_sql = " Campo Mês nao Informado.";
      $this->erro_campo = "si134_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si134_reg10 == null) {
      $this->si134_reg10 = "0";
    }
    if ($this->si134_instit == null) {
      $this->erro_sql = " Campo Instituição nao Informado.";
      $this->erro_campo = "si134_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si134_sequencial == "" || $si134_sequencial == null) {
      $result = db_query("select nextval('ops122024_si134_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: ops122024_si134_sequencial_seq do campo: si134_sequencial";
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si134_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from ops122024_si134_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si134_sequencial)) {
        $this->erro_sql = " Campo si134_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si134_sequencial = $si134_sequencial;
      }
    }
    if (($this->si134_sequencial == null) || ($this->si134_sequencial == "")) {
      $this->erro_sql = " Campo si134_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into ops122024(
                                       si134_sequencial
                                      ,si134_tiporegistro
                                      ,si134_codreduzidoop
                                      ,si134_tipodocumentoop
                                      ,si134_nrodocumento
                                      ,si134_codctb
                                      ,si134_codfontectb
                                      ,si134_dtemissao
                                      ,si134_vldocumento
                                      ,si134_desctipodocumentoop
                                      ,si134_mes
                                      ,si134_reg10
                                      ,si134_instit
                       )
                values (
                                $this->si134_sequencial
                               ,$this->si134_tiporegistro
                               ,$this->si134_codreduzidoop
                               ,'$this->si134_tipodocumentoop'
                               ,'$this->si134_nrodocumento'
                               ,$this->si134_codctb
                               ,$this->si134_codfontectb
                               ," . ($this->si134_dtemissao == "null" || $this->si134_dtemissao == "" ? "null" : "'" . $this->si134_dtemissao . "'") . "
                               ,$this->si134_vldocumento
                               ,'$this->si134_desctipodocumentoop'
                               ,$this->si134_mes
                               ,$this->si134_reg10
                               ,$this->si134_instit
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "ops122024 ($this->si134_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "ops122024 já Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "ops122024 ($this->si134_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si134_sequencial;
    $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si134_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,2010956,'$this->si134_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,2010363,2010956,'','" . AddSlashes(pg_result($resaco, 0, 'si134_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010957,'','" . AddSlashes(pg_result($resaco, 0, 'si134_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010958,'','" . AddSlashes(pg_result($resaco, 0, 'si134_codreduzidoop')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010959,'','" . AddSlashes(pg_result($resaco, 0, 'si134_tipodocumentoop')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010960,'','" . AddSlashes(pg_result($resaco, 0, 'si134_nrodocumento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010961,'','" . AddSlashes(pg_result($resaco, 0, 'si134_codctb')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2011348,'','" . AddSlashes(pg_result($resaco, 0, 'si134_codfontectb')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010962,'','" . AddSlashes(pg_result($resaco, 0, 'si134_dtemissao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010963,'','" . AddSlashes(pg_result($resaco, 0, 'si134_vldocumento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010964,'','" . AddSlashes(pg_result($resaco, 0, 'si134_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2010965,'','" . AddSlashes(pg_result($resaco, 0, 'si134_reg10')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010363,2011647,'','" . AddSlashes(pg_result($resaco, 0, 'si134_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }

    return true;
  }

  // funcao para alteracao
  function alterar($si134_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update ops122024 set ";
    $virgula = "";
    if (trim($this->si134_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_sequencial"])) {
      if (trim($this->si134_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si134_sequencial"])) {
        $this->si134_sequencial = "0";
      }
      $sql .= $virgula . " si134_sequencial = $this->si134_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si134_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_tiporegistro"])) {
      $sql .= $virgula . " si134_tiporegistro = $this->si134_tiporegistro ";
      $virgula = ",";
      if (trim($this->si134_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do  registro nao Informado.";
        $this->erro_campo = "si134_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si134_codreduzidoop) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_codreduzidoop"])) {
      if (trim($this->si134_codreduzidoop) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si134_codreduzidoop"])) {
        $this->si134_codreduzidoop = "0";
      }
      $sql .= $virgula . " si134_codreduzidoop = $this->si134_codreduzidoop ";
      $virgula = ",";
    }
    if (trim($this->si134_tipodocumentoop) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_tipodocumentoop"])) {
      $sql .= $virgula . " si134_tipodocumentoop = '$this->si134_tipodocumentoop' ";
      $virgula = ",";
    }
    if (trim($this->si134_nrodocumento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_nrodocumento"])) {
      $sql .= $virgula . " si134_nrodocumento = '$this->si134_nrodocumento' ";
      $virgula = ",";
    }
    if (trim($this->si134_codctb) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_codctb"])) {
      if (trim($this->si134_codctb) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si134_codctb"])) {
        $this->si134_codctb = "0";
      }
      $sql .= $virgula . " si134_codctb = $this->si134_codctb ";
      $virgula = ",";
    }
    if (trim($this->si134_codfontectb) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_codfontectb"])) {
      if (trim($this->si134_codfontectb) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si134_codfontectb"])) {
        $this->si134_codfontectb = "0";
      }
      $sql .= $virgula . " si134_codfontectb = $this->si134_codfontectb ";
      $virgula = ",";
    }
    if (trim($this->si134_dtemissao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_dtemissao_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["si134_dtemissao_dia"] != "")) {
      $sql .= $virgula . " si134_dtemissao = '$this->si134_dtemissao' ";
      $virgula = ",";
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si134_dtemissao_dia"])) {
        $sql .= $virgula . " si134_dtemissao = null ";
        $virgula = ",";
      }
    }
    if (trim($this->si134_vldocumento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_vldocumento"])) {
      if (trim($this->si134_vldocumento) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si134_vldocumento"])) {
        $this->si134_vldocumento = "0";
      }
      $sql .= $virgula . " si134_vldocumento = $this->si134_vldocumento ";
      $virgula = ",";
    }
    if (trim($this->si134_desctipodocumentoop) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_desctipodocumentoop"])) {
      if (trim($this->si134_desctipodocumentoop) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si134_desctipodocumentoop"])) {
        $this->si134_desctipodocumentoop = "0";
      }
      $sql .= $virgula . " si134_desctipodocumentoop = '$this->si134_desctipodocumentoop' ";
      $virgula = ",";
    }
    if (trim($this->si134_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_mes"])) {
      $sql .= $virgula . " si134_mes = $this->si134_mes ";
      $virgula = ",";
      if (trim($this->si134_mes) == null) {
        $this->erro_sql = " Campo Mês nao Informado.";
        $this->erro_campo = "si134_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si134_reg10) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_reg10"])) {
      if (trim($this->si134_reg10) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si134_reg10"])) {
        $this->si134_reg10 = "0";
      }
      $sql .= $virgula . " si134_reg10 = $this->si134_reg10 ";
      $virgula = ",";
    }
    if (trim($this->si134_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si134_instit"])) {
      $sql .= $virgula . " si134_instit = $this->si134_instit ";
      $virgula = ",";
      if (trim($this->si134_instit) == null) {
        $this->erro_sql = " Campo Instituição nao Informado.";
        $this->erro_campo = "si134_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si134_sequencial != null) {
      $sql .= " si134_sequencial = $this->si134_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si134_sequencial));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2010956,'$this->si134_sequencial','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_sequencial"]) || $this->si134_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010956,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_sequencial')) . "','$this->si134_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_tiporegistro"]) || $this->si134_tiporegistro != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010957,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_tiporegistro')) . "','$this->si134_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_codreduzidoop"]) || $this->si134_codreduzidoop != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010958,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_codreduzidoop')) . "','$this->si134_codreduzidoop'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_tipodocumentoop"]) || $this->si134_tipodocumentoop != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010959,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_tipodocumentoop')) . "','$this->si134_tipodocumentoop'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_nrodocumento"]) || $this->si134_nrodocumento != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010960,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_nrodocumento')) . "','$this->si134_nrodocumento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_codctb"]) || $this->si134_codctb != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010961,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_codctb')) . "','$this->si134_codctb'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_codfontectb"]) || $this->si134_codfontectb != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2011348,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_codfontectb')) . "','$this->si134_codfontectb'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_dtemissao"]) || $this->si134_dtemissao != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010962,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_dtemissao')) . "','$this->si134_dtemissao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_vldocumento"]) || $this->si134_vldocumento != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010963,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_vldocumento')) . "','$this->si134_vldocumento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_mes"]) || $this->si134_mes != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010964,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_mes')) . "','$this->si134_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_reg10"]) || $this->si134_reg10 != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2010965,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_reg10')) . "','$this->si134_reg10'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si134_instit"]) || $this->si134_instit != "")
          $resac = db_query("insert into db_acount values($acount,2010363,2011647,'" . AddSlashes(pg_result($resaco, $conresaco, 'si134_instit')) . "','$this->si134_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ops122024 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si134_sequencial;
      $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ops122024 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si134_sequencial;
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si134_sequencial;
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si134_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si134_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2010956,'$si134_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,2010363,2010956,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010957,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010958,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_codreduzidoop')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010959,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_tipodocumentoop')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010960,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_nrodocumento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010961,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_codctb')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2011348,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_codfontectb')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010962,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_dtemissao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010963,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_vldocumento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010964,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2010965,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_reg10')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010363,2011647,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si134_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from ops122024
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si134_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si134_sequencial = $si134_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ops122024 nao Excluído. Exclusão Abortada.\n";
      $this->erro_sql .= "Valores : " . $si134_sequencial;
      $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ops122024 nao Encontrado. Exclusão não Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si134_sequencial;
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si134_sequencial;
        $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao do recordset
  function sql_record($sql)
  {
    $result = db_query($sql);
    if ($result == false) {
      $this->numrows = 0;
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "Erro ao selecionar os registros.";
      $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:ops122024";
      $this->erro_msg = "Usuário: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
function sql_query($si134_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
{
    $ano = db_getsession("DB_anousu");

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
    $sql .= " from ops122024 ";
    $sql .= " left join ops10{$ano} on ops10{$ano}.si132_sequencial = ops122024.si134_reg10";
    $sql2 = "";
    if ($dbwhere == "") {
        if ($si134_sequencial != null) {
            $sql2 .= " where ops122024.si134_sequencial = $si134_sequencial ";
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
  function sql_query_file($si134_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from ops122024 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si134_sequencial != null) {
        $sql2 .= " where ops122024.si134_sequencial = $si134_sequencial ";
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
  /**
   * @param $reg12
   * @param $oEmpPago
   * @param $aInformado
   * @return array
   */
  public function verificaRetencao($reg12, $oEmpPago, $aInformado, $aDataIni, $aDataFim)
  {
    /**
     * Sera feito consulta para ver se houveram retencoes para esse movimento da OP
     * e essa consulta definira se sera montado o Reg. 13 para esse movimento da OP.
     */
    require_once "classes/db_retencaopagordem_classe.php";
    $clRetencao = new cl_retencaopagordem();

    $sqlReten = $clRetencao->sql_Retencao_OpsReg12($reg12, $oEmpPago, $aDataIni, $aDataFim);
    $rsReteIsIs = db_query($sqlReten);

    if (pg_num_rows($rsReteIsIs) > 0 && db_utils::fieldsMemory($rsReteIsIs, 0)->descontar > 0) {

      $nVolorOp = $this->get_float_OpValue($rsReteIsIs, $oEmpPago);

      if ($nVolorOp == 0) {
        $saldopag = db_utils::fieldsMemory($rsReteIsIs, 0)->descontar;
      } else {
        $saldopag = $nVolorOp;
      }
      $aInformado->retencao = 1;
      if ($nVolorOp < 0) {
        $nVolorOp = $oEmpPago->valor;
        $aInformado->retencao = 0;
      }

    } else {
      $nVolorOp = $oEmpPago->valor;
      $saldopag = $nVolorOp;
    }
    return array($sqlReten, $nVolorOp, $saldopag);
  }

  /**
   * @param $rsReteIsIs
   * @param $oEmpPago
   * @return float
   */
  public function get_float_OpValue($rsReteIsIs, $oEmpPago)
  {
    $descontar = number_format(db_utils::fieldsMemory($rsReteIsIs, 0)->descontar, 2, '.', '');
    $descontar = floatval($descontar);

    $valor = number_format($oEmpPago->valor, 2, '.', '');
    $valor = floatval($valor);

    $nVolorOp = number_format($valor - $descontar);
    $nVolorOp = floatval($nVolorOp);
    return $nVolorOp;
  }

    /**
     * @param $anousu
     * @param $oEmpPago
     * @param $instit
     * @return string
     */
    public function sqlReg12($anousu, $oEmpPago, $instit): string
    {
        $sSql12 = "SELECT 12 AS tiporegistro,
                            e82_codord AS codreduzidoop,
                            CASE
                                WHEN e96_codigo = 4 AND c60_codsis = 5 THEN 5
                                WHEN e96_codigo = 1 THEN 5
                                WHEN e96_codigo = 2 THEN 1
                                ELSE 99
                            END AS tipodocumentoop,
                            CASE
                                WHEN e96_codigo = 2 THEN e86_cheque
                                ELSE NULL
                            END AS nrodocumento,
                            c61_reduz AS codctb,
                            o15_codigo AS codfontectb,
                            e50_data AS dtemissao,
                            k12_valor AS vldocumento,
                            CASE
                                WHEN c60_codsis = 5 THEN ''
                                ELSE e96_descr
                            END desctipodocumentoop,
                            c23_conlancam AS codlan,
                            e81_codmov,
                            e81_numdoc
                     FROM empagemov
                     INNER JOIN empage ON empage.e80_codage = empagemov.e81_codage
                     INNER JOIN empord ON empord.e82_codmov = empagemov.e81_codmov
                     INNER JOIN empempenho ON empempenho.e60_numemp = empagemov.e81_numemp
                     LEFT JOIN empagemovforma ON empagemovforma.e97_codmov = empagemov.e81_codmov
                     LEFT JOIN empageforma ON empageforma.e96_codigo = empagemovforma.e97_codforma
                     LEFT JOIN empagepag ON empagepag.e85_codmov = empagemov.e81_codmov
                     LEFT JOIN empagetipo ON empagetipo.e83_codtipo = empagepag.e85_codtipo
                     LEFT JOIN empageconf ON empageconf.e86_codmov = empagemov.e81_codmov
                     LEFT JOIN empageconfgera ON (empageconfgera.e90_codmov, empageconfgera.e90_cancelado) = (empagemov.e81_codmov, 'f')
                     LEFT JOIN saltes ON saltes.k13_conta = empagetipo.e83_conta
                     LEFT JOIN empagegera ON empagegera.e87_codgera = empageconfgera.e90_codgera
                     LEFT JOIN empagedadosret ON empagedadosret.e75_codgera = empagegera.e87_codgera
                     LEFT JOIN empagedadosretmov ON (empagedadosretmov.e76_codret, empagedadosretmov.e76_codmov) = (empagedadosret.e75_codret, empagemov.e81_codmov)
                     LEFT JOIN empagedadosretmovocorrencia ON (empagedadosretmovocorrencia.e02_empagedadosretmov, empagedadosretmovocorrencia.e02_empagedadosret) = (empagedadosretmov.e76_codmov, empagedadosretmov.e76_codret)
                     LEFT JOIN errobanco ON errobanco.e92_sequencia = empagedadosretmovocorrencia.e02_errobanco
                     LEFT JOIN empageconfche ON empageconfche.e91_codmov = empagemov.e81_codmov AND empageconfche.e91_ativo IS TRUE
                     LEFT JOIN corconf ON corconf.k12_codmov = empageconfche.e91_codcheque AND corconf.k12_ativo IS TRUE
                     LEFT JOIN corempagemov ON corempagemov.k12_codmov = empagemov.e81_codmov
                     LEFT JOIN pagordemele ON e53_codord = empord.e82_codord
                     LEFT JOIN empagenotasordem ON e43_empagemov = e81_codmov
                     LEFT JOIN coremp ON (coremp.k12_id, coremp.k12_data, coremp.k12_autent) = (corempagemov.k12_id, corempagemov.k12_data, corempagemov.k12_autent)
                     JOIN pagordem ON (k12_empen, k12_codord) = (e50_numemp, e50_codord)
                     JOIN corrente ON (coremp.k12_autent, coremp.k12_data, coremp.k12_id) = (corrente.k12_autent, corrente.k12_data, corrente.k12_id) AND corrente.k12_estorn != TRUE
                     JOIN conplanoreduz ON c61_reduz = k12_conta AND c61_anousu = {$anousu}
                     JOIN conplano ON c61_codcon = c60_codcon AND c61_anousu = c60_anousu
                     LEFT JOIN conplanoconta ON c63_codcon = c60_codcon AND c60_anousu = c63_anousu
                     JOIN corgrupocorrente cg ON cg.k105_autent = corrente.k12_autent
                     JOIN orcdotacao ON (o58_coddot, o58_anousu) = (e60_coddot, e60_anousu)
                     JOIN orctiporec ON o58_codigo = o15_codigo AND (cg.k105_data, cg.k105_id) = (corrente.k12_data, corrente.k12_id)
                     JOIN conlancamcorgrupocorrente ON c23_corgrupocorrente = cg.k105_sequencial AND c23_conlancam = {$oEmpPago->lancamento}
                     WHERE e60_instit = {$instit}
                       AND k12_codord = {$oEmpPago->ordem}
                       AND e81_cancelado IS NULL";
        return $sSql12;
    }

    /**
     * @param bool $fromLanc
     * @return string
     */
  public function validaCtaExist($where, $fromLanc = false)
  {
    $sSqlContaPagFont = "";

    for ($iContAnoReg = 2014; $iContAnoReg <= db_getsession("DB_anousu"); $iContAnoReg++) {

      $ctb10 = 'ctb10' . $iContAnoReg;
      $ctb20 = 'ctb20' . $iContAnoReg;

      if ($sSqlContaPagFont !== "") {
        $sSqlContaPagFont .= "\nUNION\n";
      }

      $joinLanc = "";

      if ($fromLanc){
        $joinLanc = " JOIN conlancampag ON c82_reduz = c61_reduz AND c82_anousu = c61_anousu ";
      }

      $sSqlContaPagFont .= "SELECT DISTINCT '$ctb10' AS tabela,
                                            si95_codctb AS contapag,
                                            o15_codtri AS fonte
                            FROM conplanoconta
                            JOIN conplanoreduz ON c61_codcon = c63_codcon AND c61_anousu = c63_anousu
                            JOIN orctiporec ON c61_codigo = o15_codigo
                            {$joinLanc}
                            JOIN {$ctb10} ON si95_banco = c63_banco
                                AND substring(si95_agencia,'([0-9]{1,99})')::integer = substring(c63_agencia,'([0-9]{1,99})')::integer
                                AND coalesce(si95_digitoverificadoragencia, '') = coalesce(c63_dvagencia, '')
                                AND si95_contabancaria = c63_conta::int8
                                AND si95_digitoverificadorcontabancaria = c63_dvconta
                                AND si95_tipoconta::int8 = (CASE
                                                                WHEN c63_tipoconta IN (2, 3) THEN 2
                                                                ELSE 1
                                                            END)
                            JOIN {$ctb20} ON si96_codctb = si95_codctb AND si95_instit = c61_instit";

      $sSqlContaPagFont .= $where;

    }
    return $sSqlContaPagFont;
  }
}


