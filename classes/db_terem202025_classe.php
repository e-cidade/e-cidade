<?
//MODULO: sicom
//CLASSE DA ENTIDADE terem202025
class cl_terem202025 {
  // cria variaveis de erro
  var $rotulo     = null;
  var $query_sql  = null;
  var $numrows    = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status= null;
  var $erro_sql   = null;
  var $erro_banco = null;
  var $erro_msg   = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $si196_sequencial = 0;
  var $si196_tiporegistro = 0;
  var $si196_codteto = 0;
  var $si196_vlrparateto = 0;
  var $si196_nrleiteto = null;
  var $si196_tipocadastro = 0;
  var $si196_dtpublicacaolei_dia = null;
  var $si196_dtpublicacaolei_mes = null;
  var $si196_dtpublicacaolei_ano = null;
  var $si196_dtpublicacaolei = null;
  var $si196_justalteracaoteto = null;
  var $si196_mes = 0;
  var $si196_inst = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si196_sequencial = int8 = si196_sequencial
                 si196_tiporegistro = int8 = Tipo registro
                 si196_codteto = int8 = C�digo do Teto remunerat�rio
                 si196_vlrparateto = float8 = Valor para o teto remunerat�rio
                 si196_nrleiteto = int8 = N�mero da lei do teto
                 si196_dtpublicacaolei = date = Data da publica��o
                 si196_justalteracaoteto = varchar(250) = Justificativa para a altera��o
                 si196_mes = int8 = si196_mes
                 si196_inst = int8 = si196_inst
                 ";
  //funcao construtor da classe
  function cl_terem202025() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("terem202025");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }
  //funcao erro
  function erro($mostra,$retorna) {
    if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
      echo "<script>alert(\"".$this->erro_msg."\");</script>";
      if($retorna==true){
        echo "<script>location.href='".$this->pagina_retorno."'</script>";
      }
    }
  }
  // funcao para atualizar campos
  function atualizacampos($exclusao=false) {
    if($exclusao==false){
      $this->si196_sequencial = ($this->si196_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si196_sequencial"]:$this->si196_sequencial);
      $this->si196_tiporegistro = ($this->si196_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si196_tiporegistro"]:$this->si196_tiporegistro);
      $this->si196_codteto = ($this->si196_codteto == ""?@$GLOBALS["HTTP_POST_VARS"]["si196_codteto"]:$this->si196_codteto);
      $this->si196_vlrparateto = ($this->si196_vlrparateto == ""?@$GLOBALS["HTTP_POST_VARS"]["si196_vlrparateto"]:$this->si196_vlrparateto);
      $this->si196_nrleiteto = ($this->si196_nrleiteto == ""?@$GLOBALS["HTTP_POST_VARS"]["si196_nrleiteto"]:$this->si196_nrleiteto);
      if($this->te01_dtinicial == ""){
        $this->te01_dtinicial_dia = ($this->te01_dtinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["te01_dtinicial_dia"]:$this->te01_dtinicial_dia);
        $this->te01_dtinicial_mes = ($this->te01_dtinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["te01_dtinicial_mes"]:$this->te01_dtinicial_mes);
        $this->te01_dtinicial_ano = ($this->te01_dtinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["te01_dtinicial_ano"]:$this->te01_dtinicial_ano);
        if($this->te01_dtinicial_dia != ""){
          $this->te01_dtinicial = $this->te01_dtinicial_ano."-".$this->te01_dtinicial_mes."-".$this->te01_dtinicial_dia;
        }
      }
      $this->si196_justalteracaotetoteto = ($this->si196_justalteracaotetoteto == ""?@$GLOBALS["HTTP_POST_VARS"]["si196_justalteracaotetoteto"]:$this->si196_justalteracaoteto);
    }else{
      $this->si196_sequencial = ($this->si196_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si196_sequencial"]:$this->si196_sequencial);
    }
  }
  // funcao para inclusao
  function  incluir ($si196_sequencial){
    $this->atualizacampos();
    if($this->si196_tiporegistro == null ){
      $this->erro_sql = " Campo Tipo registro n�o informado.";
      $this->erro_campo = "si196_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->si196_codteto == null ){
      $this->erro_sql = " Campo C�digo do Teto n�o informado.";
      $this->erro_campo = "si196_codteto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->si196_vlrparateto == null ){
      $this->erro_sql = " Campo Valor para o teto n�o informado.";
      $this->erro_campo = "si196_vlrparateto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->si196_nrleiteto == null ){
      $this->erro_sql = " Campo N�mero da lei do teto n�o informado.";
      $this->erro_campo = "si196_nrleiteto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }


    if($this->si196_dtpublicacaolei == null ){
      $this->erro_sql = " Campo Data prevista de retorno do afastamento n�o informado.";
      $this->erro_campo = "te01_dtpublicacaolei_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }

//    if($this->si196_dtfinal == null ){
//      $this->erro_sql = " Campo Data Final n�o informado.";
//      $this->erro_campo = "si196_dtfinal_dia";
//      $this->erro_banco = "";
//      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
//      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
//      $this->erro_status = "0";
//      return false;
//    }
    if($this->si196_tipocadastro == null ){
      $this->erro_sql = " Campo Tipo de cadastro n�o informado.";
      $this->erro_campo = "si196_tipocadastro";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->si196_mes == null ){
      $this->erro_sql = " Campo si196_mes n�o informado.";
      $this->erro_campo = "si196_mes";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }
    if($this->si196_inst == null ){
      $this->erro_sql = " Campo si196_inst n�o informado.";
      $this->erro_campo = "si196_inst";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }

    if($si196_sequencial == "" || $si196_sequencial == null ){
      $result = db_query("select nextval('terem202025_si196_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("
","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: terem202025_si196_sequencial_seq do campo: si196_sequencial";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
      $this->si196_sequencial = pg_result($result,0,0);
    }else{
      $result = db_query("select last_value from terem202025_si196_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $si196_sequencial)){
        $this->erro_sql = " Campo si196_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->si196_sequencial = $si196_sequencial;
      }
    }
    if(($this->si196_sequencial == null) || ($this->si196_sequencial == "") ){
      $this->erro_sql = " Campo si196_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into terem202025(
                                       si196_sequencial
                                      ,si196_tiporegistro
                                      ,si196_codteto
                                      ,si196_vlrparateto
                                      ,si196_nrleiteto
                                      ,si196_dtpublicacaolei
                                      ,si196_justalteracaoteto
                                      ,si196_mes
                                      ,si196_inst
                       )
                values (
                                $this->si196_sequencial
                               ,$this->si196_tiporegistro
                               ,'$this->si196_codteto'
                               ,$this->si196_vlrparateto
                               ,$this->si196_nrleiteto
                               ,".($this->si196_dtpublicacaolei == "null" || $this->si196_dtpublicacaolei == ""?"null":"'".$this->si196_dtpublicacaolei."'")."
                               ,'$this->si196_justalteracaoteto'
                               ,$this->si196_mes
                               ,$this->si196_inst
                      )";

    $result = db_query($sql);

    if($result==false){
      $this->erro_banco = str_replace("","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "terem202025 ($this->si196_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_banco = "terem202025 j� Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      }else{
        $this->erro_sql   = "terem202025 ($this->si196_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }

    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : ".$this->si196_sequencial;
    $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->si196_sequencial  ));
      if(($resaco!=false)||($this->numrows!=0)){

        /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,1009269,'$this->si196_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,1010194,1009269,'','".AddSlashes(pg_result($resaco,0,'si196_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010194,1009270,'','".AddSlashes(pg_result($resaco,0,'si196_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010194,1009271,'','".AddSlashes(pg_result($resaco,0,'si196_vlrparateto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010194,1009272,'','".AddSlashes(pg_result($resaco,0,'si196_tipocadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010194,1009273,'','".AddSlashes(pg_result($resaco,0,'si196_justalteracaoteto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010194,1009274,'','".AddSlashes(pg_result($resaco,0,'si196_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010194,1009275,'','".AddSlashes(pg_result($resaco,0,'si196_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
      }
    }
    return true;
  }
  // funcao para alteracao
  function alterar ($si196_sequencial=null) {
    $this->atualizacampos();
    $sql = " update terem202025 set ";
    $virgula = "";
    if(trim($this->si196_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si196_sequencial"])){
      $sql  .= $virgula." si196_sequencial = $this->si196_sequencial ";
      $virgula = ",";
      if(trim($this->si196_sequencial) == null ){
        $this->erro_sql = " Campo si196_sequencial n�o informado.";
        $this->erro_campo = "si196_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si196_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si196_tiporegistro"])){
      $sql  .= $virgula." si196_tiporegistro = $this->si196_tiporegistro ";
      $virgula = ",";
      if(trim($this->si196_tiporegistro) == null ){
        $this->erro_sql = " Campo Tipo registro n�o informado.";
        $this->erro_campo = "si196_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si196_vlrparateto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si196_vlrparateto"])){
      $sql  .= $virgula." si196_vlrparateto = $this->si196_vlrparateto ";
      $virgula = ",";
      if(trim($this->si196_vlrparateto) == null ){
        $this->erro_sql = " Campo Valor para o teto n�o informado.";
        $this->erro_campo = "si196_vlrparateto";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si196_justalteracaoteto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si196_justalteracaoteto"])){
      $sql  .= $virgula." si196_justalteracaoteto = '$this->si196_justalteracaoteto' ";
      $virgula = ",";
    }
    if(trim($this->si196_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si196_mes"])){
      $sql  .= $virgula." si196_mes = $this->si196_mes ";
      $virgula = ",";
      if(trim($this->si196_mes) == null ){
        $this->erro_sql = " Campo si196_mes n�o informado.";
        $this->erro_campo = "si196_mes";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if(trim($this->si196_inst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si196_inst"])){
      $sql  .= $virgula." si196_inst = $this->si196_inst ";
      $virgula = ",";
      if(trim($this->si196_inst) == null ){
        $this->erro_sql = " Campo si196_inst n�o informado.";
        $this->erro_campo = "si196_inst";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if($si196_sequencial!=null){
      $sql .= " si196_sequencial = $this->si196_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->si196_sequencial));
      if($this->numrows>0){

        for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

          /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,1009269,'$this->si196_sequencial','A')");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si196_sequencial"]) || $this->si196_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,1010194,1009269,'".AddSlashes(pg_result($resaco,$conresaco,'si196_sequencial'))."','$this->si196_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si196_tiporegistro"]) || $this->si196_tiporegistro != "")
            $resac = db_query("insert into db_acount values($acount,1010194,1009270,'".AddSlashes(pg_result($resaco,$conresaco,'si196_tiporegistro'))."','$this->si196_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si196_vlrparateto"]) || $this->si196_vlrparateto != "")
            $resac = db_query("insert into db_acount values($acount,1010194,1009271,'".AddSlashes(pg_result($resaco,$conresaco,'si196_vlrparateto'))."','$this->si196_vlrparateto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si196_tipocadastro"]) || $this->si196_tipocadastro != "")
            $resac = db_query("insert into db_acount values($acount,1010194,1009272,'".AddSlashes(pg_result($resaco,$conresaco,'si196_tipocadastro'))."','$this->si196_tipocadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si196_justalteracaoteto"]) || $this->si196_justalteracaoteto != "")
            $resac = db_query("insert into db_acount values($acount,1010194,1009273,'".AddSlashes(pg_result($resaco,$conresaco,'si196_justalteracaoteto'))."','$this->si196_justalteracaoteto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si196_mes"]) || $this->si196_mes != "")
            $resac = db_query("insert into db_acount values($acount,1010194,1009274,'".AddSlashes(pg_result($resaco,$conresaco,'si196_mes'))."','$this->si196_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si196_inst"]) || $this->si196_inst != "")
            $resac = db_query("insert into db_acount values($acount,1010194,1009275,'".AddSlashes(pg_result($resaco,$conresaco,'si196_inst'))."','$this->si196_inst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
        }
      }
    }
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("
","",@pg_last_error());
      $this->erro_sql   = "terem202025 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : ".$this->si196_sequencial;
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "terem202025 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : ".$this->si196_sequencial;
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : ".$this->si196_sequencial;
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir ($si196_sequencial=null,$dbwhere=null) {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {

      if ($dbwhere==null || $dbwhere=="") {

        $resaco = $this->sql_record($this->sql_query_file($si196_sequencial));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
      }
      if (($resaco != false) || ($this->numrows!=0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          /*$resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,1009269,'$si196_sequencial','E')");
          $resac  = db_query("insert into db_acount values($acount,1010194,1009269,'','".AddSlashes(pg_result($resaco,$iresaco,'si196_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010194,1009270,'','".AddSlashes(pg_result($resaco,$iresaco,'si196_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010194,1009271,'','".AddSlashes(pg_result($resaco,$iresaco,'si196_vlrparateto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010194,1009272,'','".AddSlashes(pg_result($resaco,$iresaco,'si196_tipocadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010194,1009273,'','".AddSlashes(pg_result($resaco,$iresaco,'si196_justalteracaoteto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010194,1009274,'','".AddSlashes(pg_result($resaco,$iresaco,'si196_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010194,1009275,'','".AddSlashes(pg_result($resaco,$iresaco,'si196_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
        }
      }
    }
    $sql = " delete from terem202025
                    where ";
    $sql2 = "";
    if($dbwhere==null || $dbwhere ==""){
      if($si196_sequencial != ""){
        if($sql2!=""){
          $sql2 .= " and ";
        }
        $sql2 .= " si196_sequencial = $si196_sequencial ";
      }
    }else{
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if($result==false){
      $this->erro_banco = str_replace("
","",@pg_last_error());
      $this->erro_sql   = "terem202025 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : ".$si196_sequencial;
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    }else{
      if(pg_affected_rows($result)==0){
        $this->erro_banco = "";
        $this->erro_sql = "terem202025 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : ".$si196_sequencial;
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      }else{
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : ".$si196_sequencial;
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao do recordset
  function sql_record($sql) {
    $result = db_query($sql);
    if($result==false){
      $this->numrows    = 0;
      $this->erro_banco = str_replace("
","",@pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if($this->numrows==0){
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:terem202025";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query ( $si196_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from terem202025 ";
    $sql2 = "";
    if($dbwhere==""){
      if($si196_sequencial!=null ){
        $sql2 .= " where terem202025.si196_sequencial = $si196_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  // funcao do sql
  function sql_query_file ( $si196_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from terem202025 ";
    $sql2 = "";
    if($dbwhere==""){
      if($si196_sequencial!=null ){
        $sql2 .= " where terem202025.si196_sequencial = $si196_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>
