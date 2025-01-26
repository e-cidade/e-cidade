<?
//MODULO: sicom
//CLASSE DA ENTIDADE ctb102024
class cl_ctb102024
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
  var $si95_sequencial = 0;
  var $si95_tiporegistro = 0;
  var $si95_codctb = 0;
  var $si95_codorgao = null;
  var $si95_banco = null;
  var $si95_agencia = null;
  var $si95_digitoverificadoragencia = null;
  var $si95_contabancaria = 0;
  var $si95_digitoverificadorcontabancaria = null;
  var $si95_tipoconta = null;
  var $si95_nroseqaplicacao = 0;
  var $si95_desccontabancaria = null;
  var $si95_contaconvenio = 0;
  var $si95_nroconvenio = 0;
  var $si95_dataassinaturaconvenio_dia = null;
  var $si95_dataassinaturaconvenio_mes = null;
  var $si95_dataassinaturaconvenio_ano = null;
  var $si95_dataassinaturaconvenio = null;
  var $si95_mes = 0;
  var $si95_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si95_sequencial = int8 = sequencial
                 si95_tiporegistro = int8 = Tipo do  registro
                 si95_codctb = int8 = C�digo da Conta  Banc�ria
                 si95_codorgao = varchar(2) = C�digo do �rg�o
                 si95_banco = varchar(3) = N�mero do Banco
                 si95_agencia = varchar(6) = N�mero da Ag�ncia  Banc�ria
                 si95_digitoverificadoragencia = varchar(2) = Digito verificador Ag�ncia
                 si95_contabancaria = int8 = N�mero da Conta  Banc�ria
                 si95_digitoverificadorcontabancaria = varchar(2) = D�gito verificador  da conta banc�ria
                 si95_tipoconta = varchar(2) = Tipo da Conta
                 si95_nroseqaplicacao = int8 = N�mero sequencial  da aplica��o
                 si95_desccontabancaria = varchar(50) = Nome da Conta  Banc�ria
                 si95_contaconvenio = int8 = Conta vinculada a  conv�nio
                 si95_nroconvenio = int8 = N�mero do  Conv�nio
                 si95_dataassinaturaconvenio = date = Data da assinatura  do Conv�nio
                 si95_mes = int8 = M�s
                 si95_instit = int8 = Institui��o
                 ";

  //funcao construtor da classe
  function cl_ctb102024()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("ctb102024");
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
      $this->si95_sequencial = ($this->si95_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_sequencial"] : $this->si95_sequencial);
      $this->si95_tiporegistro = ($this->si95_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_tiporegistro"] : $this->si95_tiporegistro);
      $this->si95_codctb = ($this->si95_codctb == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_codctb"] : $this->si95_codctb);
      $this->si95_codorgao = ($this->si95_codorgao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_codorgao"] : $this->si95_codorgao);
      $this->si95_banco = ($this->si95_banco == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_banco"] : $this->si95_banco);
      $this->si95_agencia = ($this->si95_agencia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_agencia"] : $this->si95_agencia);
      $this->si95_digitoverificadoragencia = ($this->si95_digitoverificadoragencia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_digitoverificadoragencia"] : $this->si95_digitoverificadoragencia);
      $this->si95_contabancaria = ($this->si95_contabancaria == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_contabancaria"] : $this->si95_contabancaria);
      $this->si95_digitoverificadorcontabancaria = ($this->si95_digitoverificadorcontabancaria == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_digitoverificadorcontabancaria"] : $this->si95_digitoverificadorcontabancaria);
      $this->si95_tipoconta = ($this->si95_tipoconta == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_tipoconta"] : $this->si95_tipoconta);
      $this->si95_nroseqaplicacao = ($this->si95_nroseqaplicacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_nroseqaplicacao"] : $this->si95_nroseqaplicacao);
      $this->si95_desccontabancaria = ($this->si95_desccontabancaria == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_desccontabancaria"] : $this->si95_desccontabancaria);
      $this->si95_contaconvenio = ($this->si95_contaconvenio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_contaconvenio"] : $this->si95_contaconvenio);
      $this->si95_nroconvenio = ($this->si95_nroconvenio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_nroconvenio"] : $this->si95_nroconvenio);
      if ($this->si95_dataassinaturaconvenio == "") {
        $this->si95_dataassinaturaconvenio_dia = ($this->si95_dataassinaturaconvenio_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_dataassinaturaconvenio_dia"] : $this->si95_dataassinaturaconvenio_dia);
        $this->si95_dataassinaturaconvenio_mes = ($this->si95_dataassinaturaconvenio_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_dataassinaturaconvenio_mes"] : $this->si95_dataassinaturaconvenio_mes);
        $this->si95_dataassinaturaconvenio_ano = ($this->si95_dataassinaturaconvenio_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_dataassinaturaconvenio_ano"] : $this->si95_dataassinaturaconvenio_ano);
        if ($this->si95_dataassinaturaconvenio_dia != "") {
          $this->si95_dataassinaturaconvenio = $this->si95_dataassinaturaconvenio_ano . "-" . $this->si95_dataassinaturaconvenio_mes . "-" . $this->si95_dataassinaturaconvenio_dia;
        }
      }
      $this->si95_mes = ($this->si95_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_mes"] : $this->si95_mes);
      $this->si95_instit = ($this->si95_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_instit"] : $this->si95_instit);
    } else {
      $this->si95_sequencial = ($this->si95_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si95_sequencial"] : $this->si95_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si95_sequencial)
  {
    $this->atualizacampos();
    if ($this->si95_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do  registro nao Informado.";
      $this->erro_campo = "si95_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si95_codctb == null) {
      $this->si95_codctb = "0";
    }
    if ($this->si95_contabancaria == null) {
      $this->si95_contabancaria = "0";
    }
    if ($this->si95_nroseqaplicacao == null) {
      $this->si95_nroseqaplicacao = "0";
    }
    if ($this->si95_contaconvenio == null) {
      $this->si95_contaconvenio = "0";
    }
    if ($this->si95_nroconvenio == null) {
      $this->si95_nroconvenio = "0";
    }
    if ($this->si95_dataassinaturaconvenio == null) {
      $this->si95_dataassinaturaconvenio = "null";
    }
    if ($this->si95_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si95_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si95_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si95_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si95_sequencial == "" || $si95_sequencial == null) {
      $result = db_query("select nextval('ctb102024_si95_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: ctb102024_si95_sequencial_seq do campo: si95_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si95_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from ctb102024_si95_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si95_sequencial)) {
        $this->erro_sql = " Campo si95_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si95_sequencial = $si95_sequencial;
      }
    }
    if (($this->si95_sequencial == null) || ($this->si95_sequencial == "")) {
      $this->erro_sql = " Campo si95_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into ctb102024(
                                       si95_sequencial
                                      ,si95_tiporegistro
                                      ,si95_codctb
                                      ,si95_codorgao
                                      ,si95_banco
                                      ,si95_agencia
                                      ,si95_digitoverificadoragencia
                                      ,si95_contabancaria
                                      ,si95_digitoverificadorcontabancaria
                                      ,si95_tipoconta
                                      ,si95_nroseqaplicacao
                                      ,si95_desccontabancaria
                                      ,si95_contaconvenio
                                      ,si95_nroconvenio
                                      ,si95_dataassinaturaconvenio
                                      ,si95_mes
                                      ,si95_instit
                       )
                values (
                                $this->si95_sequencial
                               ,$this->si95_tiporegistro
                               ,$this->si95_codctb
                               ,'$this->si95_codorgao'
                               ,'$this->si95_banco'
                               ,'$this->si95_agencia'
                               ,'$this->si95_digitoverificadoragencia'
                               ,$this->si95_contabancaria
                               ,'$this->si95_digitoverificadorcontabancaria'
                               ,'$this->si95_tipoconta'
                               ,$this->si95_nroseqaplicacao
                               ,'$this->si95_desccontabancaria'
                               ,$this->si95_contaconvenio
                               ,'$this->si95_nroconvenio'
                               ," . ($this->si95_dataassinaturaconvenio == "null" || $this->si95_dataassinaturaconvenio == "" ? "null" : "'" . $this->si95_dataassinaturaconvenio . "'") . "
                               ,$this->si95_mes
                               ,$this->si95_instit
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "ctb102024 ($this->si95_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "ctb102024 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "ctb102024 ($this->si95_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si95_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si95_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,2010545,'$this->si95_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,2010324,2010545,'','" . AddSlashes(pg_result($resaco, 0, 'si95_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010546,'','" . AddSlashes(pg_result($resaco, 0, 'si95_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010547,'','" . AddSlashes(pg_result($resaco, 0, 'si95_codctb')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010548,'','" . AddSlashes(pg_result($resaco, 0, 'si95_codorgao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010549,'','" . AddSlashes(pg_result($resaco, 0, 'si95_banco')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010550,'','" . AddSlashes(pg_result($resaco, 0, 'si95_agencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010551,'','" . AddSlashes(pg_result($resaco, 0, 'si95_digitoverificadoragencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010552,'','" . AddSlashes(pg_result($resaco, 0, 'si95_contabancaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010553,'','" . AddSlashes(pg_result($resaco, 0, 'si95_digitoverificadorcontabancaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010554,'','" . AddSlashes(pg_result($resaco, 0, 'si95_tipoconta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010556,'','" . AddSlashes(pg_result($resaco, 0, 'si95_nroseqaplicacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010557,'','" . AddSlashes(pg_result($resaco, 0, 'si95_desccontabancaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010558,'','" . AddSlashes(pg_result($resaco, 0, 'si95_contaconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010559,'','" . AddSlashes(pg_result($resaco, 0, 'si95_nroconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010560,'','" . AddSlashes(pg_result($resaco, 0, 'si95_dataassinaturaconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2010561,'','" . AddSlashes(pg_result($resaco, 0, 'si95_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010324,2011607,'','" . AddSlashes(pg_result($resaco, 0, 'si95_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }

    return true;
  }

  // funcao para alteracao
  function alterar($si95_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update ctb102024 set ";
    $virgula = "";
    if (trim($this->si95_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_sequencial"])) {
      if (trim($this->si95_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si95_sequencial"])) {
        $this->si95_sequencial = "0";
      }
      $sql .= $virgula . " si95_sequencial = $this->si95_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si95_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_tiporegistro"])) {
      $sql .= $virgula . " si95_tiporegistro = $this->si95_tiporegistro ";
      $virgula = ",";
      if (trim($this->si95_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do  registro nao Informado.";
        $this->erro_campo = "si95_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si95_codctb) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_codctb"])) {
      if (trim($this->si95_codctb) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si95_codctb"])) {
        $this->si95_codctb = "0";
      }
      $sql .= $virgula . " si95_codctb = $this->si95_codctb ";
      $virgula = ",";
    }
    if (trim($this->si95_codorgao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_codorgao"])) {
      $sql .= $virgula . " si95_codorgao = '$this->si95_codorgao' ";
      $virgula = ",";
    }
    if (trim($this->si95_banco) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_banco"])) {
      $sql .= $virgula . " si95_banco = '$this->si95_banco' ";
      $virgula = ",";
    }
    if (trim($this->si95_agencia) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_agencia"])) {
      $sql .= $virgula . " si95_agencia = '$this->si95_agencia' ";
      $virgula = ",";
    }
    if (trim($this->si95_digitoverificadoragencia) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_digitoverificadoragencia"])) {
      $sql .= $virgula . " si95_digitoverificadoragencia = '$this->si95_digitoverificadoragencia' ";
      $virgula = ",";
    }
    if (trim($this->si95_contabancaria) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_contabancaria"])) {
      if (trim($this->si95_contabancaria) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si95_contabancaria"])) {
        $this->si95_contabancaria = "0";
      }
      $sql .= $virgula . " si95_contabancaria = $this->si95_contabancaria ";
      $virgula = ",";
    }
    if (trim($this->si95_digitoverificadorcontabancaria) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_digitoverificadorcontabancaria"])) {
      $sql .= $virgula . " si95_digitoverificadorcontabancaria = '$this->si95_digitoverificadorcontabancaria' ";
      $virgula = ",";
    }
    if (trim($this->si95_tipoconta) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_tipoconta"])) {
      $sql .= $virgula . " si95_tipoconta = '$this->si95_tipoconta' ";
      $virgula = ",";
    }
    if (trim($this->si95_nroseqaplicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_nroseqaplicacao"])) {
      if (trim($this->si95_nroseqaplicacao) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si95_nroseqaplicacao"])) {
        $this->si95_nroseqaplicacao = "0";
      }
      $sql .= $virgula . " si95_nroseqaplicacao = $this->si95_nroseqaplicacao ";
      $virgula = ",";
    }
    if (trim($this->si95_desccontabancaria) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_desccontabancaria"])) {
      $sql .= $virgula . " si95_desccontabancaria = '$this->si95_desccontabancaria' ";
      $virgula = ",";
    }
    if (trim($this->si95_contaconvenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_contaconvenio"])) {
      if (trim($this->si95_contaconvenio) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si95_contaconvenio"])) {
        $this->si95_contaconvenio = "0";
      }
      $sql .= $virgula . " si95_contaconvenio = $this->si95_contaconvenio ";
      $virgula = ",";
    }
    if (trim($this->si95_nroconvenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_nroconvenio"])) {
      if (trim($this->si95_nroconvenio) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si95_nroconvenio"])) {
        $this->si95_nroconvenio = "0";
      }
      $sql .= $virgula . " si95_nroconvenio = $this->si95_nroconvenio ";
      $virgula = ",";
    }
    if (trim($this->si95_dataassinaturaconvenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_dataassinaturaconvenio_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["si95_dataassinaturaconvenio_dia"] != "")) {
      $sql .= $virgula . " si95_dataassinaturaconvenio = '$this->si95_dataassinaturaconvenio' ";
      $virgula = ",";
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si95_dataassinaturaconvenio_dia"])) {
        $sql .= $virgula . " si95_dataassinaturaconvenio = null ";
        $virgula = ",";
      }
    }
    if (trim($this->si95_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_mes"])) {
      $sql .= $virgula . " si95_mes = $this->si95_mes ";
      $virgula = ",";
      if (trim($this->si95_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si95_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si95_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si95_instit"])) {
      $sql .= $virgula . " si95_instit = $this->si95_instit ";
      $virgula = ",";
      if (trim($this->si95_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si95_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si95_sequencial != null) {
      $sql .= " si95_sequencial = $this->si95_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si95_sequencial));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2010545,'$this->si95_sequencial','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_sequencial"]) || $this->si95_sequencial != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010545,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_sequencial')) . "','$this->si95_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_tiporegistro"]) || $this->si95_tiporegistro != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010546,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_tiporegistro')) . "','$this->si95_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_codctb"]) || $this->si95_codctb != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010547,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_codctb')) . "','$this->si95_codctb'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_codorgao"]) || $this->si95_codorgao != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010548,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_codorgao')) . "','$this->si95_codorgao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_banco"]) || $this->si95_banco != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010549,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_banco')) . "','$this->si95_banco'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_agencia"]) || $this->si95_agencia != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010550,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_agencia')) . "','$this->si95_agencia'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_digitoverificadoragencia"]) || $this->si95_digitoverificadoragencia != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010551,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_digitoverificadoragencia')) . "','$this->si95_digitoverificadoragencia'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_contabancaria"]) || $this->si95_contabancaria != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010552,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_contabancaria')) . "','$this->si95_contabancaria'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_digitoverificadorcontabancaria"]) || $this->si95_digitoverificadorcontabancaria != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010553,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_digitoverificadorcontabancaria')) . "','$this->si95_digitoverificadorcontabancaria'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_tipoconta"]) || $this->si95_tipoconta != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010554,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_tipoconta')) . "','$this->si95_tipoconta'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_nroseqaplicacao"]) || $this->si95_nroseqaplicacao != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010556,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_nroseqaplicacao')) . "','$this->si95_nroseqaplicacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_desccontabancaria"]) || $this->si95_desccontabancaria != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010557,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_desccontabancaria')) . "','$this->si95_desccontabancaria'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_contaconvenio"]) || $this->si95_contaconvenio != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010558,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_contaconvenio')) . "','$this->si95_contaconvenio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_nroconvenio"]) || $this->si95_nroconvenio != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010559,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_nroconvenio')) . "','$this->si95_nroconvenio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_dataassinaturaconvenio"]) || $this->si95_dataassinaturaconvenio != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010560,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_dataassinaturaconvenio')) . "','$this->si95_dataassinaturaconvenio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_mes"]) || $this->si95_mes != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2010561,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_mes')) . "','$this->si95_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        if (isset($GLOBALS["HTTP_POST_VARS"]["si95_instit"]) || $this->si95_instit != "") {
          $resac = db_query("insert into db_acount values($acount,2010324,2011607,'" . AddSlashes(pg_result($resaco, $conresaco, 'si95_instit')) . "','$this->si95_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ctb102024 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si95_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ctb102024 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si95_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si95_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si95_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si95_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2010545,'$si95_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,2010324,2010545,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010546,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010547,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_codctb')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010548,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_codorgao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010549,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_banco')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010550,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_agencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010551,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_digitoverificadoragencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010552,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_contabancaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010553,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_digitoverificadorcontabancaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010554,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_tipoconta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010556,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_nroseqaplicacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010557,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_desccontabancaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010558,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_contaconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010559,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_nroconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010560,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_dataassinaturaconvenio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2010561,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010324,2011607,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si95_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from ctb102024
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si95_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si95_sequencial = $si95_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ctb102024 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si95_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ctb102024 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si95_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si95_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
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
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:ctb102024";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($si95_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from ctb102024 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si95_sequencial != null) {
        $sql2 .= " where ctb102024.si95_sequencial = $si95_sequencial ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
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
  function sql_query_file($si95_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from ctb102024 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si95_sequencial != null) {
        $sql2 .= " where ctb102024.si95_sequencial = $si95_sequencial ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
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
   * M�todo para retornar as contas que ser�o utilizadas para gera��o do Registro 10
   *
   * @param integer $ano
   * @param integer $instit
   * @param integer $mes
   * @param string $dataFinal
   * @return $sSqlGeral
   */
  public function sql_Reg10($ano, $instit, $mes, $dataFinal)
  {
    $sSqlGeral = "SELECT 10 AS tiporegistro,
                          k13_reduz AS codctb,
                          c61_codtce AS codtce,
                          si09_tipoinstit,
                          si09_codorgaotce,
                          c63_banco,
                          c63_agencia,
                          c63_conta,
                          c63_dvconta,
                          c63_dvagencia,
                          CASE
                              WHEN db83_tipoconta IN (2, 3) THEN 2
                              ELSE 1
                          END AS tipoconta,
                          CASE
                              WHEN
                                      (SELECT si09_tipoinstit FROM infocomplementaresinstit
                                        WHERE si09_instit = {$instit}) = 5
                                  AND db83_tipoconta IN (2, 3) THEN db83_nroseqaplicacao::varchar
                              ELSE ' '
                          END AS nroseqaplicacao,
                          CASE
                              WHEN
                                  (SELECT si09_tipoinstit FROM infocomplementaresinstit
                                   WHERE si09_instit = {$instit} ) = 5
                                  AND db83_tipoconta IN (2,3)
				             THEN db83_tipoaplicacao::VARCHAR ELSE ' ' END AS tipoaplicacao,
                          db83_descricao AS desccontabancaria,
                          CASE
                              WHEN db83_numconvenio IS NULL THEN 2
                              ELSE 1
                          END AS contaconvenio,
                          CASE
                              WHEN db83_numconvenio IS NULL THEN ' '
                              ELSE c206_nroconvenio
                          END AS nroconvenio,
                          CASE
                              WHEN db83_numconvenio IS NULL THEN NULL
                              ELSE c206_dataassinatura
                          END AS dataassinaturaconvenio,
                          o15_codtri AS recurso,
                          CASE
                              WHEN substr(c60_estrut, 1, 3) = '111'
                                  AND substr(c60_estrut, 1, 7) != '1111101'
                                  AND substr(c60_estrut, 1, 7) != '1111102'
                                  AND substr(c60_estrut, 1, 6) != '111113'
                                  AND substr(c60_estrut, 1, 7) != '1112101' THEN 1
                              ELSE 2
                          END AS saldocec
                  FROM saltes
                  JOIN conplanoreduz ON k13_reduz = c61_reduz AND c61_anousu = {$ano}
                  JOIN conplanoconta ON c63_codcon = c61_codcon AND c63_anousu = c61_anousu
                  JOIN conplano ON c60_codcon = c61_codcon AND c60_anousu = c61_anousu
                  JOIN orctiporec ON c61_codigo = o15_codigo
                  LEFT JOIN conplanocontabancaria ON c56_codcon = c61_codcon AND c56_anousu = c61_anousu
                  LEFT JOIN contabancaria ON c56_contabancaria = db83_sequencial
                  LEFT JOIN convconvenios ON db83_numconvenio = c206_sequencial
                  LEFT JOIN infocomplementaresinstit ON si09_instit = c61_instit";
    
    
    $date = new DateTime($dataFinal);
    $datames = $date->format('m');
    $dataano = $date->format('Y');

    if ($ano == 2024 && $mes == 1) {
      $sSqlGeral .= " WHERE (k13_limite IS NULL OR (EXTRACT(MONTH FROM k13_limite) >= {$datames} and EXTRACT(YEAR FROM k13_limite) >= {$dataano}))
               AND c61_instit = {$instit} 
               ORDER BY CASE WHEN c61_codtce IS NOT NULL THEN c61_codtce ELSE k13_reduz END ASC";
    } else {
      $sSqlGeral .= " WHERE (k13_limite IS NULL OR (EXTRACT(MONTH FROM k13_limite) >= {$datames} and EXTRACT(YEAR FROM k13_limite) >= {$dataano}))
          AND (date_part('MONTH',k13_dtimplantacao) <= '$mes' OR date_part('YEAR',k13_dtimplantacao) < {$ano})
          AND c61_instit = {$instit} 
          ORDER BY CASE WHEN c61_codtce IS NOT NULL THEN c61_codtce ELSE k13_reduz END ASC";
    }

    return $sSqlGeral;
  }

  /**
   * Verifica se existe o codctb
   *
   * @param object $oRegVerifica
   * @param integer $instit
   * @param string $mes
   * @param $ano
   * @return string $sSqlVerifica
   */
  public function verificaExisteReg10($oRegVerifica, $instit, $mes, $ano)
  {

    $sSqlVerifica = "";

    $sSqlAplicacaoPrevidencia = "";
    if ($oRegVerifica->si09_tipoinstit == 5) {
      $sSqlAplicacaoPrevidencia = $oRegVerifica->nroseqaplicacao == "" ? "" : " AND si95_nroseqaplicacao = {$oRegVerifica->nroseqaplicacao} ";
    }

    for ($iContAnoReg10 = 2014; $iContAnoReg10 <= $ano; $iContAnoReg10++) {
      $table = 'ctb10' . $iContAnoReg10;
      $sSqlVerifica .= "SELECT '{$table}' AS ano, si95_codctb, si95_nroconvenio::varchar FROM {$table}
                        WHERE si95_codorgao::int = {$oRegVerifica->si09_codorgaotce}
                          AND si95_banco = '{$oRegVerifica->c63_banco}'
                          AND si95_agencia = '{$oRegVerifica->c63_agencia}'
                          AND si95_digitoverificadoragencia = '{$oRegVerifica->c63_dvagencia}'
                          AND si95_contabancaria = '{$oRegVerifica->c63_conta}'
                          AND si95_digitoverificadorcontabancaria = '{$oRegVerifica->c63_dvconta}'
                          AND si95_tipoconta::int = {$oRegVerifica->tipoconta}
                          {$sSqlAplicacaoPrevidencia}
                          AND si95_instit = {$instit}";

      $sSqlVerifica .= $iContAnoReg10 == $ano ? " AND si95_mes <= '{$mes}' " : " UNION ";
    }
    return $sSqlVerifica;
  }

  /**
   * Verificando a data de cadastro da conta
   *
   * @param object $oRegVerifica
   * @param integer $ano
   * @param integer $instit
   * @return $sSqlDtCad
   */
  public function verificaDtCadastro($oRegVerifica, $ano, $instit)
  {
    $sSqlDtCad = "SELECT k13_dtimplantacao,
                         si09_codorgaotce,
                         CASE
                             WHEN c61_codtce = 0 OR c61_codtce IS NULL THEN c61_reduz
                             ELSE c61_codtce
                         END AS codctb
                  FROM conplanoconta
                  JOIN conplanoreduz ON (c61_codcon, c61_anousu) = (c63_codcon, c63_anousu)
                  JOIN saltes ON (k13_reduz) = (c61_reduz)
                  LEFT JOIN infocomplementaresinstit on si09_instit = c61_instit
                  WHERE si09_codorgaotce = $oRegVerifica->si09_codorgaotce
                    AND c63_banco = '$oRegVerifica->c63_banco'
                    AND c63_agencia = '$oRegVerifica->c63_agencia'
                    AND c63_dvagencia = '$oRegVerifica->c63_dvagencia'
                    AND c63_conta = '$oRegVerifica->c63_conta'
                    AND c63_dvconta = '$oRegVerifica->c63_dvconta'
                    AND (($oRegVerifica->tipoconta IN (2,3) AND c63_tipoconta IN (2,3)) OR ($oRegVerifica->tipoconta = 1 AND c63_tipoconta = 1))
                    AND c61_instit = {$instit}
                    AND c61_anousu = {$ano}";

    return $sSqlDtCad;
  }
}

?>
