<?php
//MODULO: Obras
//CLASSE DA ENTIDADE licitemobra
class cl_licitemobra {
  // cria variaveis de erro
  public $rotulo     = null;
  public $query_sql  = null;
  public $numrows    = 0;
  public $numrows_incluir = 0;
  public $numrows_alterar = 0;
  public $numrows_excluir = 0;
  public $erro_status= null;
  public $erro_sql   = null;
  public $erro_banco = null;
  public $erro_msg   = null;
  public $erro_campo = null;
  public $pagina_retorno = null;
  // cria variaveis do arquivo
  public $obr06_sequencial = 0;
  public $obr06_pcmater = 0;
  public $obr06_tabela = 0;
  public $obr06_descricaotabela = null;
  public $obr06_codigotabela = null;
  public $obr06_versaotabela = null;
  public $obr06_dtregistro_dia = null;
  public $obr06_dtregistro_mes = null;
  public $obr06_dtregistro_ano = null;
  public $obr06_dtregistro = null;
  public $obr06_dtcadastro_dia = null;
  public $obr06_dtcadastro_mes = null;
  public $obr06_dtcadastro_ano = null;
  public $obr06_dtcadastro = null;
  public $obr06_instit = 0;
  public $obr06_ordem = 0;
  public $obr06_solicitacao = null;

  // cria propriedade com as variaveis do arquivo
  public $campos = "
                 obr06_sequencial = int8 = C�d. Sequencial
                 obr06_pcmater = int8 = Material
                 obr06_tabela = int4 = Tabela
                 obr06_descricaotabela = text = Descri��o da Tabela
                 obr06_codigotabela = text = C�digo da Tabela
                 obr06_versaotabela = text = Vers�o da Tabela
                 obr06_dtregistro = date = Data do Registro
                 obr06_dtcadastro = date = Data do Cadastro
                 obr06_instit = int4 = Institui��o
                 obr06_ordem = int4 = Sequ�ncia
                 obr06_solicitacao = int = Sequencial da solicita��o de compra
                 ";

  //funcao construtor da classe
  function __construct() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("licitemobra");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }

  //funcao erro
  function erro($mostra,$retorna) {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )) {
      echo "<script>alert(\"".$this->erro_msg."\");</script>";
      if ($retorna==true) {
        echo "<script>location.href='".$this->pagina_retorno."'</script>";
      }
    }
  }

  // funcao para atualizar campos
  function atualizacampos($exclusao=false) {
    if ($exclusao==false) {
      $this->obr06_sequencial = ($this->obr06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_sequencial"]:$this->obr06_sequencial);
      $this->obr06_pcmater = ($this->obr06_pcmater == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_pcmater"]:$this->obr06_pcmater);
      $this->obr06_tabela = ($this->obr06_tabela == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_tabela"]:$this->obr06_tabela);
      $this->obr06_descricaotabela = ($this->obr06_descricaotabela == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_descricaotabela"]:$this->obr06_descricaotabela);
      $this->obr06_codigotabela = ($this->obr06_codigotabela == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_codigotabela"]:$this->obr06_codigotabela);
      $this->obr06_versaotabela = ($this->obr06_versaotabela == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_versaotabela"]:$this->obr06_versaotabela);
      if ($this->obr06_dtregistro == "") {
        $this->obr06_dtregistro_dia = ($this->obr06_dtregistro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_dtregistro_dia"]:$this->obr06_dtregistro_dia);
        $this->obr06_dtregistro_mes = ($this->obr06_dtregistro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_dtregistro_mes"]:$this->obr06_dtregistro_mes);
        $this->obr06_dtregistro_ano = ($this->obr06_dtregistro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_dtregistro_ano"]:$this->obr06_dtregistro_ano);
        if ($this->obr06_dtregistro_dia != "") {
          $this->obr06_dtregistro = $this->obr06_dtregistro_ano."-".$this->obr06_dtregistro_mes."-".$this->obr06_dtregistro_dia;
        }
      }
      if ($this->obr06_dtcadastro == "") {
        $this->obr06_dtcadastro_dia = ($this->obr06_dtcadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_dtcadastro_dia"]:$this->obr06_dtcadastro_dia);
        $this->obr06_dtcadastro_mes = ($this->obr06_dtcadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_dtcadastro_mes"]:$this->obr06_dtcadastro_mes);
        $this->obr06_dtcadastro_ano = ($this->obr06_dtcadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_dtcadastro_ano"]:$this->obr06_dtcadastro_ano);
        if ($this->obr06_dtcadastro_dia != "") {
          $this->obr06_dtcadastro = $this->obr06_dtcadastro_ano."-".$this->obr06_dtcadastro_mes."-".$this->obr06_dtcadastro_dia;
        }
      }
      $this->obr06_instit = ($this->obr06_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_instit"]:$this->obr06_instit);
      $this->obr06_solicitacao = ($this->obr06_solicitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["obr06_solicitacao"]:$this->obr06_solicitacao);
    } else {
    }
  }

  // funcao para inclusao
  function incluir () {
    $this->atualizacampos();
    if ($this->obr06_sequencial == null ) {
      $result = db_query("select nextval('licitemobra_obr06_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: licitemobra_obr06_sequencial_seq do campo: obr06_sequencial";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->obr06_sequencial = pg_result($result,0,0);
    }
    if ($this->obr06_pcmater == null ) {
      $this->erro_sql = " Campo Material n�o informado.";
      $this->erro_campo = "obr06_pcmater";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->obr06_tabela == null || $this->obr06_tabela == "0") {
      $this->erro_sql = " Campo Tabela n�o informado. Item: $this->obr06_pcmater";
      $this->erro_campo = "obr06_tabela";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->obr06_tabela == "3"){
      if ($this->obr06_descricaotabela == null ) {
        $this->erro_sql = " Campo Descri��o da Tabela n�o informado.";
        $this->erro_campo = "obr06_descricaotabela";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: tabelas do tipo 3 - outras tabelas oficiais e obrigatorio informar o campo descri��o da tabela. Item: $this->obr06_pcmater";
        $this->erro_status = "0";
        return false;
      }
    }else{
      $this->obr06_descricaotabela == "";
    }
    
    if ($this->obr06_tabela != "4" && $this->obr06_codigotabela == null ) {
      $this->erro_sql = " Campo C�digo da Tabela n�o informado.";
      $this->erro_campo = "obr06_codigotabela";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->obr06_tabela != "4" && $this->obr06_versaotabela == null ) {
      $this->erro_sql = " Campo Vers�o da Tabela n�o informado.";
      $this->erro_campo = "obr06_versaotabela";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    
    if ($this->obr06_dtcadastro == null ) {
      $this->erro_sql = " Campo Data do Registro n�o informado.";
      $this->erro_campo = "obr06_dtcadastro_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->obr06_instit == null ) {
      $this->erro_sql = " Campo Institui��o n�o informado.";
      $this->erro_campo = "obr06_instit";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->obr06_ordem == null ) {
      $this->erro_sql = " Campo ordem n�o informado.";
      $this->erro_campo = "obr06_ordem";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into licitemobra(
                                       obr06_sequencial
                                      ,obr06_pcmater
                                      ,obr06_tabela
                                      ,obr06_descricaotabela
                                      ,obr06_codigotabela
                                      ,obr06_versaotabela
                                      ,obr06_dtregistro
                                      ,obr06_dtcadastro
                                      ,obr06_instit
                                      ,obr06_ordem
                                      ,obr06_solicitacao
                       )
                values (
                                $this->obr06_sequencial
                               ,$this->obr06_pcmater
                               ,$this->obr06_tabela
                               ,'$this->obr06_descricaotabela'
                               ,'$this->obr06_codigotabela'
                               ,'$this->obr06_versaotabela'
                               ,".($this->obr06_dtregistro == "null" || $this->obr06_dtregistro == ""?"null":"'".$this->obr06_dtregistro."'")."
                               ,".($this->obr06_dtcadastro == "null" || $this->obr06_dtcadastro == ""?"null":"'".$this->obr06_dtcadastro."'")."
                               ,$this->obr06_instit
                               ,$this->obr06_ordem
                               ,$this->obr06_solicitacao
                      )";
    $result = db_query($sql);
    if ($result==false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
        $this->erro_sql   = "cadastro itens da obra () nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "cadastro itens da obra j� Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      } else {
        $this->erro_sql   = "cadastro itens da obra () nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

    }
    return true;
  }

  // funcao para alteracao
  function alterar ( $obr06_sequencial=null ) {
    $this->atualizacampos();
    $sql = " update licitemobra set ";
    $virgula = "";
    if (trim($this->obr06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_sequencial"])) {
      $sql  .= $virgula." obr06_sequencial = $this->obr06_sequencial ";
      $virgula = ",";
      if (trim($this->obr06_sequencial) == null ) {
        $this->erro_sql = " Campo C�d. Sequencial n�o informado.";
        $this->erro_campo = "obr06_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->obr06_pcmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_pcmater"])) {
      $sql  .= $virgula." obr06_pcmater = $this->obr06_pcmater ";
      $virgula = ",";
      if (trim($this->obr06_pcmater) == null ) {
        $this->erro_sql = " Campo Material n�o informado.";
        $this->erro_campo = "obr06_pcmater";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->obr06_tabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_tabela"])) {
      $sql  .= $virgula." obr06_tabela = $this->obr06_tabela ";
      $virgula = ",";
      if (trim($this->obr06_tabela) == null ) {
        $this->erro_sql = " Campo Tabela n�o informado. Item: $this->obr06_pcmater";
        $this->erro_campo = "obr06_tabela";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if($this->obr06_tabela == "3"){
        if (trim($this->obr06_descricaotabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_descricaotabela"])) {
            $sql  .= $virgula." obr06_descricaotabela = '$this->obr06_descricaotabela' ";
            $virgula = ",";
            if (trim($this->obr06_descricaotabela) == "") {
                $this->erro_sql = " Campo Descri��o da Tabela n�o informado. Item: $this->obr06_pcmater";
                $this->erro_campo = "obr06_descricaotabela";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
    }else{
        if (trim($this->obr06_descricaotabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_descricaotabela"])) {
            $sql  .= $virgula." obr06_descricaotabela = '$this->obr06_descricaotabela' ";
            $virgula = ",";
            if (trim($this->obr06_descricaotabela) == null ) {
                $this->erro_sql = " Campo Descri��o da Tabela n�o informado.";
                $this->erro_campo = "obr06_descricaotabela";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
    }
    if ($this->obr06_tabela != "4" && (trim($this->obr06_codigotabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_codigotabela"]))) {
      $sql  .= $virgula." obr06_codigotabela = '$this->obr06_codigotabela' ";
      $virgula = ",";
      if (trim($this->obr06_codigotabela) == null ) {
        $this->erro_sql = " Campo C�digo da Tabela n�o informado.";
        $this->erro_campo = "obr06_codigotabela";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->obr06_versaotabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_versaotabela"])) {
      $sql  .= $virgula." obr06_versaotabela = '$this->obr06_versaotabela' ";
      $virgula = ",";
      if (trim($this->obr06_versaotabela) == null ) {
        $this->erro_sql = " Campo Vers�o da Tabela n�o informado.";
        $this->erro_campo = "obr06_versaotabela";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    
    if (trim($this->obr06_dtcadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_dtcadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["obr06_dtcadastro_dia"] !="") ) {
      $sql  .= $virgula." obr06_dtcadastro = '$this->obr06_dtcadastro' ";
      $virgula = ",";
      if (trim($this->obr06_dtcadastro) == null ) {
        $this->erro_sql = " Campo Data do Registro n�o informado.";
        $this->erro_campo = "obr06_dtcadastro_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }     else{
      if (isset($GLOBALS["HTTP_POST_VARS"]["obr06_dtcadastro_dia"])) {
        $sql  .= $virgula." obr06_dtcadastro = null ";
        $virgula = ",";
        if (trim($this->obr06_dtcadastro) == null ) {
          $this->erro_sql = " Campo Data do Registro n�o informado.";
          $this->erro_campo = "obr06_dtcadastro_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->obr06_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_instit"])) {
      $sql  .= $virgula." obr06_instit = $this->obr06_instit ";
      $virgula = ",";
      if (trim($this->obr06_instit) == null ) {
        $this->erro_sql = " Campo Institui��o n�o informado.";
        $this->erro_campo = "obr06_instit";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->obr06_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["obr06_ordem"])) {
      $sql  .= $virgula." obr06_ordem = $this->obr06_ordem ";
      $virgula = ",";
      if (trim($this->obr06_ordem) == null ) {
        $this->erro_sql = " Campo Ordem n�o informado.";
        $this->erro_campo = "obr06_ordem";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    $sql .= "obr06_sequencial = $this->obr06_sequencial";
//    die($sql);
    $result = db_query($sql);
    if ($result==false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "cadastro itens da obra nao Alterado. Alteracao Abortada.\\n";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result)==0) {
        $this->erro_banco = "";
        $this->erro_sql = "cadastro itens da obra nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir ( $obr06_sequencial=null ,$dbwhere=null) {

    $sql = " delete from licitemobra
                    where ";
    $sql2 = "";
    if ($dbwhere==null || $dbwhere =="") {
      $sql2 = "obr06_sequencial = $obr06_sequencial";
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if ($result==false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "cadastro itens da obra nao Exclu�do. Exclus�o Abortada.\\n";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result)==0) {
        $this->erro_banco = "";
        $this->erro_sql = "cadastro itens da obra nao Encontrado. Exclus�o n�o Efetuada.\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao do recordset
  function sql_record($sql) {
    $result = db_query($sql);
    if ($result==false) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows==0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:licitemobra";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  // funcao do sql
  function sql_query ( $obr06_sequencial = null,$campos="licitemobra.obr06_sequencial,*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from licitemobra ";
    $sql .= " inner join pcmater on pc01_codmater = obr06_pcmater ";
    $sql2 = "";
    if ($dbwhere=="") {
      if ( $obr06_sequencial != "" && $obr06_sequencial != null) {
        $sql2 = " where licitemobra.obr06_sequencial = '$obr06_sequencial'";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  // funcao do sql
  function sql_query_file ( $obr06_sequencial = null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from licitemobra ";
    $sql2 = "";
    if ($dbwhere=="") {
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

    // funcao do sql
    function sql_query_itens_obras_licitacao ( $l20_codigo = null,$campos="*",$ordem=null,$dbwhere="") {
        $sql = "select ";
        if ($campos != "*" ) {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from liclicitem ";
        $sql .= " INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem ";
        $sql .= " LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem ";
        $sql .= " INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc ";
        $sql .= " LEFT JOIN itemprecoreferencia ON si02_itemproccompra = pcorcamitemproc.pc31_orcamitem ";
        $sql .= " INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem ";
        $sql .= " INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero ";
        $sql .= " INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo ";
        $sql .= " INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
        $sql .= " LEFT JOIN licitemobra ON obr06_pcmater = pc01_codmater AND  obr06_ordem = l21_ordem and obr06_solicitacao = solicita.pc10_numero ";
        $sql2 = "";
        if ($dbwhere=="") {
          $sql2 = "where l20_codigo = $l20_codigo";
        } else if ($dbwhere != "") {
            $sql2 = "where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null ) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    // funcao do sql
    function sql_query_itens_obras_licitacao_sem_ordem ( $l20_codigo = null,$campos="*",$ordem=null,$dbwhere="") {
        $sql = "select ";
        if ($campos != "*" ) {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from liclicitem ";
        $sql .= " INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem ";
        $sql .= " LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem ";
        $sql .= " INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc ";
        $sql .= " LEFT JOIN itemprecoreferencia ON si02_itemproccompra = pcorcamitemproc.pc31_orcamitem ";
        $sql .= " INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem ";
        $sql .= " INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero ";
        $sql .= " INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo ";
        $sql .= " INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
        $sql .= " LEFT JOIN licitemobra ON obr06_pcmater = pc01_codmater and obr06_solicitacao = solicita.pc10_numero ";
        $sql2 = "";
        if ($dbwhere=="") {
          $sql2 = "where l20_codigo = $l20_codigo";
        } else if ($dbwhere != "") {
            $sql2 = "where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null ) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    // funcao do sql
    function sql_query_itens_obras_processodecompras ( $pc80_codproc = null,$campos="*",$ordem=null,$dbwhere="") {
        $sql = "select ";
        if ($campos != "*" ) {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from pcproc ";
        $sql .= " INNER JOIN pcprocitem ON pc81_codproc = pc80_codproc ";
        $sql .= " INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem ";
        $sql .= " INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero ";
        $sql .= " INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo ";
        $sql .= " INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
        $sql .= " LEFT JOIN licitemobra ON obr06_pcmater = pc01_codmater AND obr06_ordem = pc11_seq and obr06_solicitacao = pc11_numero ";
        $sql2 = "";
        if ($dbwhere=="") {
            $sql2 = "where pc80_codproc = $pc80_codproc";
        } else if ($dbwhere != "") {
            $sql2 = "where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null ) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++) {
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    function sql_query_itens_obras_processodecompras_sem_seq ( $pc80_codproc = null,$campos="*",$ordem=null,$dbwhere="") {
      $sql = "select ";
      if ($campos != "*" ) {
          $campos_sql = explode("#", $campos);
          $virgula = "";
          for($i=0;$i<sizeof($campos_sql);$i++) {
              $sql .= $virgula.$campos_sql[$i];
              $virgula = ",";
          }
      } else {
          $sql .= $campos;
      }
      $sql .= " from pcproc ";
      $sql .= " INNER JOIN pcprocitem ON pc81_codproc = pc80_codproc ";
      $sql .= " INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem ";
      $sql .= " INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero ";
      $sql .= " INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo ";
      $sql .= " INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
      $sql .= " LEFT JOIN licitemobra ON obr06_pcmater = pc01_codmater and obr06_solicitacao = solicita.pc10_numero ";
      $sql2 = "";
      if ($dbwhere=="") {
          $sql2 = "where pc80_codproc = $pc80_codproc";
      } else if ($dbwhere != "") {
          $sql2 = "where $dbwhere";
      }
      $sql .= $sql2;
      if ($ordem != null ) {
          $sql .= " order by ";
          $campos_sql = explode("#", $ordem);
          $virgula = "";
          for($i=0;$i<sizeof($campos_sql);$i++) {
              $sql .= $virgula.$campos_sql[$i];
              $virgula = ",";
          }
      }
      return $sql;
  }

  function getSequencialSolicitacao($licitacao,$processocompra,$codigomaterial,$ordem){

    $sequencialLicitacao;

    if($licitacao != null){
    
      $rsSolicitacao = db_query("select pc11_numero from liclicita
      inner join liclicitem on l21_codliclicita = l20_codigo
      inner join pcprocitem on l21_codpcprocitem = pc81_codprocitem
      inner join solicitem on pc11_codigo = pc81_solicitem
      inner join solicitempcmater on pc16_solicitem = pc11_codigo
      where l20_codigo = $licitacao and pc16_codmater = $codigomaterial and l21_ordem = $ordem;");
      $sequencialLicitacao = db_utils::fieldsMemory($rsSolicitacao, 0)->pc11_numero;

    }

    if($processocompra != null){

      $rsSolicitacao = db_query("select pc11_numero from pcproc
      inner join pcprocitem on pc81_codproc = pc80_codproc
      inner join solicitem on pc11_codigo = pc81_solicitem
      inner join solicitempcmater on pc16_solicitem = pc11_codigo
      where pc80_codproc = $processocompra and pc16_codmater = $codigomaterial and pc11_seq = $ordem;");
      $sequencialLicitacao = db_utils::fieldsMemory($rsSolicitacao, 0)->pc11_numero;
    }
    
    return $sequencialLicitacao;

  }

  function getLicItemObraSicomContratos($codigoAgordoItem){


    $rsItems = db_query("SELECT
                            *
                        FROM
                            acordoitem
                        INNER JOIN
                            acordoliclicitem ON ac24_acordoitem = ac20_sequencial
                        INNER JOIN
                            liclicitem ON ac24_liclicitem = l21_codigo
                        INNER JOIN
                            liclicita ON l20_codigo = l21_codliclicita
                        INNER JOIN
                            pcprocitem ON pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem
                        INNER JOIN
                            solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                        LEFT JOIN
                            licitemobra ON (
                                obr06_pcmater = ac20_pcmater AND
                                obr06_solicitacao = pc11_numero AND
                                (
                                    (l20_anousu > 2023 AND obr06_ordem = l21_ordem) OR
                                    (l20_anousu <= 2023)
                                )
                            )
                        where ac20_sequencial = $codigoAgordoItem ");
    
    return $rsItems;

  }

}
?>
