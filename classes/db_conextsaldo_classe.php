<?
//MODULO: contabilidade
//CLASSE DA ENTIDADE conextsaldo
class cl_conextsaldo {
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
   var $ces01_sequencial = 0;
   var $ces01_codcon = 0;
   var $ces01_reduz = 0;
   var $ces01_fonte = 0;
   var $ces01_valor = 0;
   var $ces01_anousu = 0;
   var $ces01_inst = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ces01_sequencial = int8 = ces01_sequencial
                 ces01_codcon = int8 = Plano de contas
                 ces01_reduz = int8 = Reduzido
                 ces01_fonte = int8 = Fonte
                 ces01_valor = float8 = Valor
                 ces01_anousu = int8 = ces01_anousu
                 ces01_inst = int8 = ces01_inst
                 ";
   //funcao construtor da classe
   function __construct() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conextsaldo");
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
       $this->ces01_sequencial = ($this->ces01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ces01_sequencial"]:$this->ces01_sequencial);
       $this->ces01_codcon = ($this->ces01_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["ces01_codcon"]:$this->ces01_codcon);
       $this->ces01_reduz = ($this->ces01_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["ces01_reduz"]:$this->ces01_reduz);
       $this->ces01_fonte = ($this->ces01_fonte == ""?@$GLOBALS["HTTP_POST_VARS"]["ces01_fonte"]:$this->ces01_fonte);
       $this->ces01_valor = ($this->ces01_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["ces01_valor"]:$this->ces01_valor);
       $this->ces01_anousu = ($this->ces01_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ces01_anousu"]:$this->ces01_anousu);
       $this->ces01_inst = ($this->ces01_inst == ""?@$GLOBALS["HTTP_POST_VARS"]["ces01_inst"]:$this->ces01_inst);
     }else{
       $this->ces01_sequencial = ($this->ces01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ces01_sequencial"]:$this->ces01_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ces01_sequencial=null){
      $this->atualizacampos();
     if($this->ces01_codcon == null ){
       $this->erro_sql = " Campo Plano de contas n�o informado.";
       $this->erro_campo = "ces01_codcon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ces01_reduz == null ){
       $this->erro_sql = " Campo Reduzido n�o informado.";
       $this->erro_campo = "ces01_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ces01_fonte == null ){
       $this->erro_sql = " Campo Fonte n�o informado.";
       $this->erro_campo = "ces01_fonte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ces01_valor == null ){
       $this->erro_sql = " Campo Valor n�o informado.";
       $this->erro_campo = "ces01_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ces01_anousu == null ){
       $this->erro_sql = " Campo ces01_anousu n�o informado.";
       $this->erro_campo = "ces01_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ces01_inst == null ){
       $this->erro_sql = " Campo ces01_inst n�o informado.";
       $this->erro_campo = "ces01_inst";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ces01_sequencial == "" || $ces01_sequencial == null ){
       $result = db_query("select nextval('conextsaldo_ces01_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conextsaldo_ces01_sequencial_seq do campo: ces01_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ces01_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conextsaldo_ces01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ces01_sequencial)){
         $this->erro_sql = " Campo ces01_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ces01_sequencial = $ces01_sequencial;
       }
     }
     if(($this->ces01_sequencial == null) || ($this->ces01_sequencial == "") ){
       $this->erro_sql = " Campo ces01_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conextsaldo(
                                       ces01_sequencial
                                      ,ces01_codcon
                                      ,ces01_reduz
                                      ,ces01_fonte
                                      ,ces01_valor
                                      ,ces01_anousu
                                      ,ces01_inst
                       )
                values (
                                $this->ces01_sequencial
                               ,$this->ces01_codcon
                               ,$this->ces01_reduz
                               ,$this->ces01_fonte
                               ,$this->ces01_valor
                               ,$this->ces01_anousu
                               ,$this->ces01_inst
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "conExtSaldo ($this->ces01_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "conExtSaldo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "conExtSaldo ($this->ces01_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ces01_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     /*$lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ces01_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009331,'$this->ces01_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010200,1009331,'','".AddSlashes(pg_result($resaco,0,'ces01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009337,'','".AddSlashes(pg_result($resaco,0,'ces01_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009332,'','".AddSlashes(pg_result($resaco,0,'ces01_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009333,'','".AddSlashes(pg_result($resaco,0,'ces01_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009334,'','".AddSlashes(pg_result($resaco,0,'ces01_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009335,'','".AddSlashes(pg_result($resaco,0,'ces01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009336,'','".AddSlashes(pg_result($resaco,0,'ces01_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }*/
     return true;
   }
   // funcao para alteracao
   function alterar ($ces01_sequencial=null) {
      $this->atualizacampos();
     $sql = " update conextsaldo set ";
     $virgula = "";
     if(trim($this->ces01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ces01_sequencial"])){
       $sql  .= $virgula." ces01_sequencial = $this->ces01_sequencial ";
       $virgula = ",";
       if(trim($this->ces01_sequencial) == null ){
         $this->erro_sql = " Campo ces01_sequencial n�o informado.";
         $this->erro_campo = "ces01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ces01_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ces01_codcon"])){
       $sql  .= $virgula." ces01_codcon = $this->ces01_codcon ";
       $virgula = ",";
       if(trim($this->ces01_codcon) == null ){
         $this->erro_sql = " Campo Plano de contas n�o informado.";
         $this->erro_campo = "ces01_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ces01_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ces01_reduz"])){
       $sql  .= $virgula." ces01_reduz = $this->ces01_reduz ";
       $virgula = ",";
       if(trim($this->ces01_reduz) == null ){
         $this->erro_sql = " Campo Reduzido n�o informado.";
         $this->erro_campo = "ces01_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ces01_fonte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ces01_fonte"])){
       $sql  .= $virgula." ces01_fonte = $this->ces01_fonte ";
       $virgula = ",";
       if(trim($this->ces01_fonte) == null ){
         $this->erro_sql = " Campo Fonte n�o informado.";
         $this->erro_campo = "ces01_fonte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ces01_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ces01_valor"])){
       $sql  .= $virgula." ces01_valor = $this->ces01_valor ";
       $virgula = ",";
       if(trim($this->ces01_valor) == null ){
         $this->erro_sql = " Campo Valor n�o informado.";
         $this->erro_campo = "ces01_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ces01_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ces01_anousu"])){
       $sql  .= $virgula." ces01_anousu = $this->ces01_anousu ";
       $virgula = ",";
       if(trim($this->ces01_anousu) == null ){
         $this->erro_sql = " Campo ces01_anousu n�o informado.";
         $this->erro_campo = "ces01_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ces01_inst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ces01_inst"])){
       $sql  .= $virgula." ces01_inst = $this->ces01_inst ";
       $virgula = ",";
       if(trim($this->ces01_inst) == null ){
         $this->erro_sql = " Campo ces01_inst n�o informado.";
         $this->erro_campo = "ces01_inst";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ces01_sequencial!=null){
       $sql .= " ces01_sequencial = $this->ces01_sequencial";
     }
     /*$lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ces01_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009331,'$this->ces01_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ces01_sequencial"]) || $this->ces01_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009331,'".AddSlashes(pg_result($resaco,$conresaco,'ces01_sequencial'))."','$this->ces01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ces01_codcon"]) || $this->ces01_codcon != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009337,'".AddSlashes(pg_result($resaco,$conresaco,'ces01_codcon'))."','$this->ces01_codcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ces01_reduz"]) || $this->ces01_reduz != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009332,'".AddSlashes(pg_result($resaco,$conresaco,'ces01_reduz'))."','$this->ces01_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ces01_fonte"]) || $this->ces01_fonte != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009333,'".AddSlashes(pg_result($resaco,$conresaco,'ces01_fonte'))."','$this->ces01_fonte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ces01_valor"]) || $this->ces01_valor != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009334,'".AddSlashes(pg_result($resaco,$conresaco,'ces01_valor'))."','$this->ces01_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ces01_anousu"]) || $this->ces01_anousu != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009335,'".AddSlashes(pg_result($resaco,$conresaco,'ces01_anousu'))."','$this->ces01_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ces01_inst"]) || $this->ces01_inst != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009336,'".AddSlashes(pg_result($resaco,$conresaco,'ces01_inst'))."','$this->ces01_inst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }*/
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conExtSaldo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ces01_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "conExtSaldo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ces01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ces01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ces01_sequencial=null,$dbwhere=null) {

     /*$lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ces01_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009331,'$ces01_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009331,'','".AddSlashes(pg_result($resaco,$iresaco,'ces01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009337,'','".AddSlashes(pg_result($resaco,$iresaco,'ces01_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009332,'','".AddSlashes(pg_result($resaco,$iresaco,'ces01_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009333,'','".AddSlashes(pg_result($resaco,$iresaco,'ces01_fonte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009334,'','".AddSlashes(pg_result($resaco,$iresaco,'ces01_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009335,'','".AddSlashes(pg_result($resaco,$iresaco,'ces01_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009336,'','".AddSlashes(pg_result($resaco,$iresaco,'ces01_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }*/
     $sql = " delete from conextsaldo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ces01_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ces01_sequencial = $ces01_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conExtSaldo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ces01_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "conExtSaldo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ces01_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ces01_sequencial;
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
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:conextsaldo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ces01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conextsaldo ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = conextsaldo.ces01_codcon";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplano.c60_consistemaconta";
     $sql2 = "";
     if($dbwhere==""){
       if($ces01_sequencial!=null ){
         $sql2 .= " where conextsaldo.ces01_sequencial = $ces01_sequencial ";
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
   function sql_query_file ( $ces01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from conextsaldo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ces01_sequencial!=null ){
         $sql2 .= " where conextsaldo.ces01_sequencial = $ces01_sequencial ";
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
